<?php
declare(strict_types=1);

namespace App\Controllers\Admin;

class Users extends \Core\Controller
{

    /**
     * Before filter
     *
     * @return bool
     */
    protected function before(): bool
    {
        // Make sure an admin user is logged in
        // return false
        return true;
    }

    /**
     * Show the index page
     *
     * @return void
     */
    public function indexAction(): void
    {
        echo 'User admin index';
    }
}