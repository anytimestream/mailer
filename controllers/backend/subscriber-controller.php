<?php
require BASE_PATH .'/require-admin.php';
require BASE_PATH .'/models/impl/SubscriberService.php';

if (isset($_GET['c'])) {
    switch ($_GET['c']) {
        case "edit":
            SubscriberService::DoUpdate($_GET['d']);
            $_GET['title'] = "Edit Account";
            $_GET['view'] = 'edit-account.php';
            break;
        case "delete":
            SubscriberService::GetAccountById($_GET['d']);
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
    SubscriberService::GetSubscribers();
}
require BASE_PATH .'/views/backend/subscriber.php';
