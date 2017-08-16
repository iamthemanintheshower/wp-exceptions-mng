<?php
set_error_handler('myErrorHandler');
function myErrorHandler($errno, $errstr, $errfile, $errline) {
    if (!(error_reporting() & $errno)) { return false; }
    $date = new DateTime();
    switch ($errno) {
        case E_USER_ERROR:
            error_log('!PHP-ERROR|'.$date->getTimestamp().'|'.$errno.'|'.$errstr.'|'.$errline.'|'.$errfile, 3, plugin_dir_path(__FILE__)."log/logs/php-exception.log");
            exit(1);
            break;

        case E_USER_WARNING:
            error_log('!PHP-WARNING|'.$date->getTimestamp().'|'.$errno.'|'.$errstr.'|'.$errline.'|'.$errfile, 3, plugin_dir_path(__FILE__)."log/logs/php-exception.log");
            break;

        case E_USER_NOTICE:
            error_log('!PHP-NOTICE|'.$date->getTimestamp().'|'.$errno.'|'.$errstr.'|'.$errline.'|'.$errfile, 3, plugin_dir_path(__FILE__)."log/logs/php-exception.log");
            break;

        default:
            error_log('!PHP-DEFAULT|'.$date->getTimestamp().'|'.$errno.'|'.$errstr.'|'.$errline.'|'.$errfile, 3, plugin_dir_path(__FILE__)."log/logs/php-exception.log");
            die('ERRORE');
            break;
    }
    return true;
}