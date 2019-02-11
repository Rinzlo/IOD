<?php

declare(strict_types=1);

namespace App\Models;

use App\Config;
use App\Mail;
use App\Token;
use Core\View;
use PDO;
use Core\Model;

/**
 * @property string remember_token
 * @property float|int expirey_timestamp
 * @property string password_reset_token
 * @property string password
 * @property string email
 * @property string password_confirmation
 * @property string username
 * @property string activation_token
 */
class User extends Model
{

    /**
     * @var array
     */
    public $errors = [];

    public function __construct(array $data = [])
    {
        if(isset($_POST['g-recaptcha-response'])){
            // evaluate captcha to be true
            unset($_POST['g-recaptcha-response']);
        }
        foreach ($data as $key => $value){
            $this->$key = $value;
        };
    }

    /**
     * expects to use attributes:
     * username,
     * email,
     * password
     */
    public function save(): bool
    {
        $this->validate();

        if(!empty($this->errors)){
            return false;
        }

        $hash = password_hash($this->password, PASSWORD_DEFAULT);

        $token = new Token();
        $hashed_token = $token->getHash();
        $this->activation_token = $token->getValue();

        $sql = 'INSERT INTO users (username, email, password, activation_hash)
                VALUES (:username, :email, :hash, :activation_hash)';

        $db = static::getDB();
        $stmt = $db->prepare($sql);

        $stmt->bindValue(':username', $this->username, PDO::PARAM_STR);
        $stmt->bindValue(':email', $this->email, PDO::PARAM_STR);
        $stmt->bindValue(':hash', $hash, PDO::PARAM_STR);
        $stmt->bindValue(':activation_hash', $hashed_token, PDO::PARAM_STR);

        return $stmt->execute();
    }

    /**
     * validate the user's creation information:
     * username,
     * email,
     * password,
     * password_confirmation
     */
    public function validate(): void
    {
        if($this->username == ''){
            $this->errors[] = 'Name is required';
        }

        if(filter_var($this->email, FILTER_VALIDATE_EMAIL) === false) {
            $this->errors[] = 'Invalid email';
        }
        if(static::emailExists($this->email, $this->id ?? null)){
            $this->errors[] = 'email already taken';
        }

        if(isset($this->password)) {
            if (isset($this->password_confirmation) &&
                $this->password != $this->password_confirmation) {
                $this->errors[] = 'Password must match confirmation';
            }

            if (strlen($this->password) < 6) {
                $this->errors[] = 'Please enter at least 6 characters for the password';
            }

            if (preg_match('/.*[a-z]+.*/i', $this->password) == 0) {
                $this->errors[] = 'Password needs at least one letter';
            }

            if (preg_match('/.*\d+.*/i', $this->password) == 0) {
                $this->errors[] = 'Password needs at least one number';
            }
        }
    }

    /**
     * @param string $email
     * @return bool
     */
    public static function emailExists(string $email, $ignore_id = null): bool
    {
        $user = static::findByEmail($email);

        if($user)
            if($user->id != $ignore_id)
                return true;

        return false;
    }
	
	/**
	 * @param string $email
	 * @return mixed
	 */
    public static function findByEmail(string $email)
    {
        $sql = 'SELECT * FROM users WHERE email = :email';

        $db = static::getDB();
        $stmt = $db->prepare($sql);
        $stmt->bindParam(':email', $email, PDO::PARAM_STR);

        $stmt->setFetchMode(PDO::FETCH_CLASS, get_called_class());

        $stmt->execute();

        return $stmt->fetch();
    }
	
	/**
	 * Find a user model by ID
	 *
	 * @param $id The user ID
	 * @return mixed User object if found, false otherwise
	 */
    public static function findByID($id)
    {
    	$sql = 'SELECT * FROM users WHERE id = :id';
    	
    	$db = static::getDB();
    	$stmt = $db->prepare($sql);
    	$stmt->bindValue(':id', $id, PDO::PARAM_INT);
    	
    	$stmt->setFetchMode(PDO::FETCH_CLASS, get_called_class());
    	
    	$stmt->execute();
    	
    	return $stmt->fetch();
    }
	
	/**
	 * @param string $email
	 * @param string $password
	 * @return bool|mixed
	 */
    public static function authenticate(string $email, string $password)
    {
        $user = static::findByEmail($email);

        if($user && $user->is_active){
            if(password_verify($password, $user->password)){
                return $user;
            }
        }

        return false;
    }

    public function loggingIn()
    {
//        $sql = 'SELECT * '
    }
    
	public function rememberLogin(): bool
    {
    	$token = new Token();
    	$hashed_token = $token->getHash();
    	$this->remember_token = $token->getValue();
    	
    	$this->expirey_timestamp = time() + 60 * 60 * 24 * 30;    // 30 days from now
	    
	    $sql = 'INSERT INTO remembered_logins (token_hash, user_id, expires_at)
				VALUES (:token_hash, :user_id, :expires_at)';
	    
	    $db = static::getDB();
	    $stmt = $db->prepare($sql);
	    
	    $stmt->bindValue(':token_hash', $hashed_token, PDO::PARAM_STR);
	    $stmt->bindValue(':user_id', $this->id, PDO::PARAM_INT);
	    $stmt->bindValue(':expires_at', date('Y-m-d H:i:s', $this->expirey_timestamp), PDO::PARAM_STR);
	    
	    return $stmt->execute();
    }

    /**
     * Send password reset instructions to the user specified
     */
    public static function sendPasswordReset(string $email): void
    {
        $user = static::findByEmail($email);

        if($user){
            if($user->startPasswordReset()){

                $user->sendPasswordResetEmail();

            }
        }
    }

    /**
     * Start the password reset process by generating a new token and expiry
     */
    protected function startPasswordReset()
    {
        $token = new Token();
        $hashed_token = $token->getHash();
        $this->password_reset_token = $token->getValue();

        $expiry_timestamp = time() + 60 * 60 * 2;   // 2 hours from now

        $sql = 'UPDATE users
                SET password_reset_hash = :token_hash,
                    password_reset_expires_at = :expires_at
                WHERE id = :id';

        $db = static::getDB();
        $stmt = $db->prepare($sql);

        $stmt->bindValue(':token_hash', $hashed_token, PDO::PARAM_STR);
        $stmt->bindValue(':expires_at', date('Y-m-d H:i:s', $expiry_timestamp), PDO::PARAM_STR);
        $stmt->bindValue(':id', $this->id, PDO::PARAM_INT);

        return $stmt->execute();
    }

    /**
     * Send password reset instructions in an email to the user
     */
    protected function sendPasswordResetEmail(): void
    {
        $url = 'http://' . $_SERVER['HTTP_HOST'] . '/password/reset/' . $this->password_reset_token;

        $text = View::getTemplate('Password/reset_email.txt.twig', ['url' => $url]);
        $html = View::getTemplate('Password/reset_email.html.twig', ['url' => $url]);

        Mail::send($this->email, 'Password reset', $text, $html);
    }

    /**
     * Find a user model by password reset token and expiry
     */
    public static function findByPasswordReset(string $token)
    {
        $token = new Token($token);
        $hashed_token = $token->getHash();

        $sql = 'SELECT * FROM users
                WHERE password_reset_hash = :token_hash';

        $db = static::getDB();
        $stmt = $db->prepare($sql);

        $stmt->bindValue(':token_hash', $hashed_token, PDO::PARAM_STR);

        $stmt->setFetchMode(PDO::FETCH_CLASS, get_called_class());

        $stmt->execute();

        $user = $stmt->fetch();

        if($user){
            if(strtotime($user->password_reset_expires_at) > time()){

                return $user;

            }
        }
    }

    /**
     * Reset the password
     */
    public function resetPassword(string $password, string $password_confirmation): bool
    {
        $this->password = $password;
        $this->password_confirmation = $password_confirmation;

        $this->validate();

        if(empty($this->errors)) {

            $password_hash = password_hash($this->password, PASSWORD_DEFAULT);

            $sql = 'UPDATE users
                    SET password = :password_hash,
                        password_reset_hash = NULL,
                        password_reset_expires_at = NULL
                    WHERE id = :id';

            $db = static::getDB();
            $stmt = $db->prepare($sql);

            $stmt->bindValue(':id', $this->id, PDO::PARAM_INT);
            $stmt->bindValue(':password_hash', $password_hash, PDO::PARAM_STR);

            return $stmt->execute();
        }

        return false;
    }

    /**
     * Send an email to the user containing the activation link
     */
    public function sendActivationEmail(): void
    {
        $url = 'http://' . $_SERVER['HTTP_HOST']. '/'.Config::APP_NAME. '/accounts/activated/' . $this->activation_token;

        $text = View::getTemplate('Accounts/activation_email.txt.twig', ['url' => $url]);
        $html = View::getTemplate('Accounts/activation_email.html.twig', ['url' => $url]);

        Mail::send($this->email, 'Account activation', $text, $html);
    }
	
	/**
	 * Activate the user account with the specified activation token
	 *
	 * @param string $value Activation token from the URL
	 * @throws \Exception
	 */
    public static function activate(string $value): void
    {
        $token = new Token($value);
        $hashed_token = $token->getHash();

        $sql = 'UPDATE users
                SET is_active = 1,
                    activation_hash = null
                WHERE activation_hash = :hashed_token';

        $db = static::getDB();
        $stmt = $db->prepare($sql);

        $stmt->bindValue(':hashed_token', $hashed_token, PDO::PARAM_STR);

        $stmt->execute();

    }

    /**
     * Update the user's profile
     * @param array $data
     * @return bool
     */
    public function updateProfile(array $data): bool
    {
        $this->username = $data['username'];
        $this->email = $data['email'];
        if($data['password'] != '') {
            var_dump($data);
            exit();
            $this->password = $data['password'];
            $this->password_confirmation = $data['password_confirmation'];
        }

        $this->validate();

        if(empty($this->errors)) {

            $sql = 'UPDATE users
                    SET username = :username,
                        email = :email';
            if(isset($this->password))
                $sql .= ', password = :password';

            $sql .= "\nWHERE id = :id";

            $db = static::getDB();
            $stmt = $db->prepare($sql);

            $stmt->bindValue(':username', $this->username, PDO::PARAM_STR);
            $stmt->bindValue(':email', $this->email, PDO::PARAM_STR);
            $stmt->bindValue(':id', $this->id, PDO::PARAM_INT);

            if(isset($this->password)) {
                $password = password_hash($this->password, PASSWORD_DEFAULT);
                $stmt->bindValue(':password', $password, PDO::PARAM_STR);
            }

            return $stmt->execute();

        }

        return false;
    }
}