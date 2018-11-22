<?php
declare(strict_types=1);

namespace App;


class ExampleConfig
{
    /**
     * The name of the app
     * @var string
     */
    const APP_NAME = 'Arcaic';

    /**
     * Secure protocol or not
     * @var bool
     */
    const SSL = false;

    /**
     * @var string
     */
    const DB_HOST = 'localhost';

    /**
     * @var string
     */
    const DB_NAME = 'mvc';

    /**
     * @var string
     */
    const DB_USER = 'root';

    /**
     * @var string
     */
    const DB_PASSWORD = 'root';

    /**
     * Show or hide error messages on screen
     * @var bool
     */
    const SHOW_ERRORS = false;
	
	/**
	 * Secret key for hashing
	 * @var string
	 */
    const SECRET_KEY = 'secret_hash';

    /**
     * smtp gmail username
     * @var string
     */
    const MAIL_USERNAME = 'username@gmail.com';

    /**
     * smtp gmail password
     * @var string
     */
    const MAIL_PASSWORD = 'yourpassword';

    /**
     * smtp gmail from email
     * @var string
     */
    const MAIL_FROM = 'DoNotReply@gmail.com';

    /**
     * smtp gmail reply to email
     * @var string
     */
    const MAIL_REPLY_TO = 'DoNotReply@gmail.com';

    /**
     * Enable SMTP debugging
     * 0 = off (for production use)
     * 1 = client messages
     * 2 = client and server messages
     * @var int
     */
    const SMTP_DEBUG = 0;

    /**
     * re-captcha secret key
     * @var string
     */
    const RECAPTCHA_SECRET = 'secret_key';

    /**
     * re-captcha public key
     * @var string
     */
    const RECAPTCHA_PUBLIC = 'public_key';
}