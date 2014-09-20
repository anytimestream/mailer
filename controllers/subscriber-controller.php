<?php
require BASE_PATH .'/require-login.php';
require BASE_PATH .'/models/impl/SubscriberService.php';

if (isset($_GET['b'])) {
    switch ($_GET['b']) {
        case "edit":
            SubscriberService::DoUpdate($_GET['c']);
            $_GET['title'] = "Edit Account";
            $_GET['view'] = 'edit-account.php';
            break;
        case "delete":
            SubscriberService::GetAccountById($_GET['c']);
            $_GET['title'] = "Confirm Deletion";
            $_GET['view'] = 'confirm-account-delete.php';
            if(isset($_POST['confirmed'])){
                SubscriberService::DoDelete($_POST['id']);
            }
            break;
    }
} else {
    $_GET['title'] = "Subscribers";
    $_GET['view'] = 'view-subscribers.php';
    SubscriberService::GetSubscribersByAccount();
}
require BASE_PATH .'/views/subscriber.php';
