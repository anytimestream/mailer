<?php

ini_set('session.use_only_cookies', true);
date_default_timezone_set('Africa/Lagos');
ini_set('post_max_size', 100000);
ini_set('upload_max_filesize', 100000);




define('DEBUG_MODE', false);



define('DB_USERNAME', '');
define('BASE_PATH', dirname(__FILE__));

define('CONTEXT_PATH', 'http://' . $_SERVER['SERVER_NAME']);
