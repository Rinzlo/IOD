<?php
declare(strict_types=1);

namespace App\Models;


use App\Token;
use PDO;


class RememberedLogin extends \Core\Model
{
	
	/**
	 * Find a remembered login model by the token
	 *
	 * @param string $token
	 * @return mixed Remembered login object if found, false otherwise
	 * @throws \Exception
	 */
	public static function findByToken(string $token)
	{
		$token = new Token($token);
		$token_hash = $token->getHash();
		
		$sql = 'SELECT * FROM remembered_logins
				WHERE token_hash = :token_hash';
		
		$db = static::getDB();
		$stmt = $db->prepare($sql);
		$stmt->bindValue(':token_hash', $token_hash, PDO::PARAM_STR);
		
		$stmt->setFetchMode(PDO::FETCH_CLASS, get_called_class());
		
		$stmt->execute();
		
		return $stmt->fetch();
	}
	
	public function getUser(): User
	{
		return User::findByID($this->user_id);
	}
	
	/**
	 * See if the remember token has expired or not, based on the current system time
	 */
	public function hasExpired(): bool
	{
		return strtotime($this->expires_at) < time();
	}
	
	/**
	 * Delete this model
	 */
	public function delete(): void
	{
		$sql = 'DELETE FROM remembered_logins
				WHERE token_hash = :token_hash';
		
		$db = static::getDB();
		$stmt = $db->prepare($sql);
		$stmt->bindValue(':token_hash', $this->token_hash, PDO::PARAM_STR);
		
		$stmt->execute();
		
		
	}
}