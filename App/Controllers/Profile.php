<?php
declare(strict_types=1);

namespace App\Controllers;


use App\Auth;
use App\Config;
use App\Flash;
use Core\View;

class Profile extends Authenticated
{

    public function showAction(): void
    {
        View::renderTemplate('Profile/show.html.twig', [
            'user' => Auth::getUser()
        ]);
    }

    public function editAction(): void
    {
        View::renderTemplate('Profile/edit.html.twig', [
            'user' => Auth::getUser()
        ]);
    }

    public function updateAction(): void
    {
        $user = Auth::getUser();

        if ($user->updateProfile($_POST)){

            Flash::addMessage('Changes saved!', Flash::SUCCESS);

            $this->redirect('/'.Config::APP_NAME.'/profile/show');

        } else {

            View::renderTemplate('Profile/edit.html.twig', [
                'user' => $user
            ]);

        }
    }
}