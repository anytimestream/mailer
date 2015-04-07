<?php
require BASE_PATH .'/require-admin.php';
require BASE_PATH .'/models/impl/MailingListService.php';

if (isset($_GET['c'])) {
    switch ($_GET['c']) {
        case "edit":
            MailingListService::DoUpdate($_GET['d']);
            $_GET['title'] = "Edit Mailing List";
            $_GET['view'] = 'edit-mailing-list.php';
            break;
        case "delete":
            MailingListService::GetAccountById($_GET['d']);
            $_GET['title'] = "Confirm Deletion";
            $_GET['view'] = 'confirm-mailing-list-delete.php';
            if(isset($_POST['confirmed'])){
                MailingListService::DoDelete($_POST['id']);
            }
            break;
    }
} else {
    $_GET['title'] = "Mailing List";
    $_GET['view'] = 'view-mailing-list.php';
    MailingListService::GetMailingLists();
}
require BASE_PATH .'/views/backend/mailing-list.php';
