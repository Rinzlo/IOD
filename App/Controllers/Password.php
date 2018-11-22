<?php
declare(strict_types=1);

namespace App\Controllers;


use App\Models\User;
use Core\View;
use Couchbase\UserSettings;

class Password extends \Core\Controller
{

    /**
     * Show the forgotten password page
     */
    public function forgotAction(): void
    {
        View::renderTemplate('Password/forgot.html.twig');
    }

    /**
     * Send the password reset link to the supplied email
     */
    public function requestResetAction(): void
    {
        User::sendPasswordReset($_POST['email']);

        View::renderTemplate('Password/reset_requested.html.twig');
    }

    /**
     * Show the reset password form
     */
    public function resetAction(): void
    {
        $token = $this->route_params['token'];

        $user = $this->getUserOrExit($token);

        View::renderTemplate('Password/reset.html.twig', [
            'token' => $token
        ]);

    }

    /**
     * Reset the user's password
     */
    public function resetPasswordAction()
    {
        $token = $_POST['token'];

        $user = $this->getUserOrExit($token);

        if($user->resetPassword($_POST['password'], $_POST['password_confirmation'])) {

            View::renderTemplate('Password/reset_success.html.twig');

        } else {

            View::renderTemplate('Password/reset.html.twig', [
                'token' => $token,
                'user' => $user
            ]);

        }

    }

    protected function getUserOrExit(string $token)
    {
        $user = User::findByPasswordReset($token);

        if($user){

            return $user;

        } else {

            View::renderTemplate('Password/token_expired.html.twig');
            exit;

        }
    }
}