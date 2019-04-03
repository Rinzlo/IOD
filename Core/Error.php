<?php

namespace Core;

use App\Config;

/**
 * Error and exception handler
 *
 * Class Error
 * @package Core
 */
class Error
{

    /**
     * @param $level
     * @param $message
     * @param $file
     * @param $line
     * @throws \ErrorException
     */
    public static function errorHandler($level, $message, $file, $line)
    {
        if(error_reporting() !== 0) {   // to keep the @ operator working
            throw new \ErrorException($message, 0, $level, $file, $line);
        }
    }

    /**
     * Exception handler
     *
     * @param \Exception $exception
     *
     * @return void
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    public static function exceptionHandler($exception): void
    {
        // Code is 404 (not found) or 500 (general error)
        $code = $exception->getCode();
        if($code != 404)
            $code = 500;
        http_response_code($code);

        $exclass = get_class($exception);
        $msg = $exception->getMessage();
        $trace = $exception->getTraceAsString();
        $file = $exception->getFile();
        $line = $exception->getLine();

        if(Config::SHOW_ERRORS) {
            View::renderTemplate("$code.html.twig", [
                'showErros' => true,
                'exclass' => $exclass,
                'msg' => $msg,
                'trace' => $trace,
                'file' => $file,
                'line' => $line
            ]);
        } else{
            $log = dirname(__DIR__) . '/logs/' . date('Y-m-d') . '.txt';
            ini_set('error_log', $log);
            
            $message = "Uncaught exception: '" . $exclass . "'";
            $message .= " with message '" . $msg . "'";
            $message .= "\nStack trace: " . $trace;
            $message .= "\nThrown in '" . $file . "' on line " . $line;

            error_log($message);

            View::renderTemplate("$code.html.twig", [
                'showErros' => false,
            ]);
        }
    }
}