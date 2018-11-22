<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Auth;
use App\Config;
use App\Flash;
use App\Mail;
use Core\View;
use App\Models\Post;
use PHPMailer\PHPMailer\PHPMailer;

/**
 * Posts controller
 */
class Posts extends \App\Controllers\Authenticated
{

    /**
     * Show the index page
     *
     * @return void
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     * @throws \PHPMailer\PHPMailer\Exception
     */
    public function indexAction(): void
    {
        // TODO: take out maile check
        Mail::send(Config::MAIL_USERNAME, 'its all about me', 'me me me me', '<h1>not you you you you</h1>');

        $posts = Post::getAll();

        View::renderTemplate('Posts/index.html.twig', [
            'posts' => $posts
        ]);
    }

    /**
     * Add a new post
     */
    public function newAction(): void
    {
        echo "new post";
    }

    /**
     * Show an item
     */
    public function showAction(): void
    {
        echo "show action";
    }
}