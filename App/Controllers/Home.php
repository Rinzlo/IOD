<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Auth;
use App\Config;
use Core\View;

class Home extends \Core\Controller
{

    /**
     * Before filter
     *
     * @return bool
     */
    protected function before(): bool
    {
        // things to do before calling the action
//        return false;
        return true;
    }

    /**
     * After filter
     *
     * @return void
     */
    protected function after(): void
    {
        // things to do after calling action
    }

    /**
     * Show the index page
     *
     * @return void
     *
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    public function indexAction(): void
    {
        View::renderTemplate('Home/index.html.twig');
    }
}