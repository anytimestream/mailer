<?php

ini_set('session.use_only_cookies', true);
date_default_timezone_set('Africa/Lagos');
ini_set('post_max_size', 100000);
ini_set('upload_max_filesize', 100000);


define('AWS_ACCESS_KEY_ID', 'AKIAJ7HWUK6BQBC4TP5A');
define('AWS_SECRET_ACCESS_KEY', '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');

define('DEBUG_MODE', false);

if (DEBUG_MODE) {
    define('DB_HOST', 'localhost');
    define('DB_USER', 'root');
    define('DB_PASSWORD', 'mysql');
    define('CDN_CONTEXT_PATH', 'http://' . $_SERVER['SERVER_NAME']);
} else {
    define('DB_HOST', '54.86.187.53');
    define('DB_USER', 'mail');
    define('DB_PASSWORD', 'adminadmin21');
    define('CDN_CONTEXT_PATH', 'http://cdn.' . $_SERVER['SERVER_NAME']);
}

define('DB_USERNAME', '');
define('BASE_PATH', dirname(__FILE__));

define('CONTEXT_PATH', 'http://' . $_SERVER['SERVER_NAME']);
