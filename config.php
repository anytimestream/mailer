<?php

ini_set('session.use_only_cookies', true);
date_default_timezone_set('Africa/Lagos');
ini_set('post_max_size', 100000);
ini_set('upload_max_filesize', 100000);


define('AWS_ACCESS_KEY_ID', 'AKIAJ7HWUK6BQBC4TP5A');
define('AWS_SECRET_ACCESS_KEY', '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');

define('DEBUG_MODE', false);



define('DB_USERNAME', '');
define('BASE_PATH', dirname(__FILE__));

define('CONTEXT_PATH', 'http://' . $_SERVER['SERVER_NAME']);
