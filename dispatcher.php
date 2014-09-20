<?php
require 'config.php';
require 'rewrite-url.php';
require 'global-includes.php';

session_start();

if(!isset($_SESSION['MOBILE_VIEW'])){
    $_SESSION['MOBILE_VIEW'] = "OFF";
}

$viewControllers = array();

//login/logout controllers
$viewControllers[] = array('path' => '/login', 'controller' => 'login-controller.php');
$viewControllers[] = array('path' => '/detect-account', 'controller' => 'detect-account-controller.php');
$viewControllers[] = array('path' => '/change-password', 'controller' => 'change-password-controller.php');
$viewControllers[] = array('path' => '/logout', 'controller' => 'logout-controller.php');

//cron
$viewControllers[] = array('path' => '/cron-job/send-verification/*', 'controller' => 'cron/send-verification-controller.php');
$viewControllers[] = array('path' => '/cron-job/send-mail-appe/*', 'controller' => 'cron/send-mail-appe-controller.php');$viewControllers[] = array('path' => '/cron-job/patch/*', 'controller' => 'cron/patch.php');
$viewControllers[] = array('path' => '/cron-job/clear-empty/*', 'controller' => 'cron/clear-empty-controller.php');
$viewControllers[] = array('path' => '/cron-job/clear-attachments/*', 'controller' => 'cron/clear-attachments-controller.php');
$viewControllers[] = array('path' => '/cron-job/log-report/*', 'controller' => 'cron/log-report-controller.php');


//mobile detect controllers
$viewControllers[] = array('path' => '/mobile/*', 'controller' => 'mobile-controller.php');


//client
$viewControllers[] = array('path' => '/mailing-list/*', 'controller' => 'mailing-list-controller.php');
$viewControllers[] = array('path' => '/subscribers/*', 'controller' => 'subscriber-controller.php');
$viewControllers[] = array('path' => '/unsubscribe/*', 'controller' => 'unsubscribe-controller.php');

//backend
$viewControllers[] = array('path' => '/backend', 'controller' => 'backend/mailing-list-controller.php');
$viewControllers[] = array('path' => '/backend/account-settings/accounts/*', 'controller' => 'backend/account-settings/account-controller.php');
$viewControllers[] = array('path' => '/backend/account-settings/users/*', 'controller' => 'backend/account-settings/user-controller.php');
$viewControllers[] = array('path' => '/backend/account-settings/smtps/*', 'controller' => 'backend/account-settings/smtp-controller.php');
$viewControllers[] = array('path' => '/backend/mailing-list/*', 'controller' => 'backend/mailing-list-controller.php');
$viewControllers[] = array('path' => '/backend/subscribers/*', 'controller' => 'backend/subscriber-controller.php');
$viewControllers[] = array('path' => '/backend/send-mail-errors/*', 'controller' => 'backend/send-mail-error-controller.php');


//default controller
$defaultViewController = array('path' => '/', 'controller' => 'mailing-list-controller.php');


//404 controller
$_404ViewController = array('path' => '/', 'controller' => '404-controller.php');

dispatchRequest();

function dispatchRequest() {
    $uri = $_SERVER['REQUEST_URI'];
    if(strpos($uri, 'dispatcher.php') > 0){
        header('location: '.CONTEXT_PATH);
    }
    $strpos = strpos($uri, '?');
    if(strlen($strpos) > 0){
        $uri = substr($uri, 0, $strpos);
    }
    $viewController = getViewController($uri);
    if ($viewController['controller'] != null) {
        require 'controllers/' . $viewController['controller'];
    }
    else{
        require 'views/' . $viewController['view'];
    }
}

function getViewController($uri) {
    global $viewControllers;
    global $defaultViewController;
    global $_404ViewController;

    if ($uri == '/') {
        return $defaultViewController;
    }

    foreach ($viewControllers as $viewController) {
        $strpos = strpos($viewController['path'], '/*');
        if (strlen($strpos) > 0) {
            if(substr($uri, 0, $strpos) == substr($viewController['path'], 0, $strpos)){
                return $viewController;
            }
        } else {
            if ($uri == $viewController['path'] || $uri == $viewController['path'] . '/') {
                return $viewController;
            }
        }
    }

    return $_404ViewController;
}
