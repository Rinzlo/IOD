<?php
declare(strict_types=1);

namespace Core;

use App\Auth;
use App\Config;

class View
{

    /**
     * Render a view file
     *
     * @param string $view
     * @param array $args
     *
     * @return void
     * @throws \Exception
     */
    public static function render(string $view, array $args = []): void
    {
        extract($args, EXTR_SKIP);

        $file = "../App/Views/$view"; // relative to Core directory

        if(is_readable($file)){
            require $file;
        }else{
            throw new \Exception("$file not found");
        }
    }

    /**
     * Render a view template using Twig
     *
     * @param string $template
     * @param array $args
     *
     * @return void
     *
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    public static function renderTemplate(string $template, array $args = []): void
    {
        echo static::getTemplate($template, $args);
    }

    /**
     * Get a view template using Twig
     *
     * @param string $template
     * @param array $args
     *
     * @return string
     *
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    public static function getTemplate(string $template, array $args = [])
    {
        static $twig = null;

        if($twig === null){
            $loader = new \Twig_Loader_Filesystem(dirname(__DIR__).'/App/Views');
            $twig = new \Twig_Environment($loader);
            $twig->addGlobal('current_user', Auth::getUser());
            $twig->addGlobal('flash_messages', \App\Flash::getMessages());
            $twig->addGlobal('app', Config::APP_NAME);
            $twig->addGlobal('public', Config::RECAPTCHA_PUBLIC);
        }
        
        return $twig->render($template, $args);
    }
}