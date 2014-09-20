<?php
require BASE_PATH .'/require-login.php';
require BASE_PATH .'/models/impl/MailingListService.php';
require BASE_PATH .'/models/impl/FileService.php';


if (isset($_GET['b'])) {
    switch ($_GET['b']) {
        case "new":
            MailingListService::DoNew();
            if (isset($_POST['name'])) {
                MailingListService::DoInsert();
            }
            $_GET['title'] = "Compose";
            $_GET['view'] = 'new-mailing-list.php';
            break;
        case "edit":
            MailingListService::DoUpdate($_GET['c']);
            $_GET['title'] = "Edit Mailing List";
            $_GET['view'] = 'edit-mailing-list.php';
            break;
        case "delete":
            MailingListService::GetAccountById($_GET['c']);
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
    MailingListService::GetMailingListsByAccount();
}
require BASE_PATH .'/views/mailing-list.php';
