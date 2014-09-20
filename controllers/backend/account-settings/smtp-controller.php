<?php

require BASE_PATH .'/require-admin.php';
require BASE_PATH .'/models/impl/AccountService.php';
require BASE_PATH .'/models/impl/SMTPAccountService.php';

if (isset($_GET['d'])) {
    switch ($_GET['d']) {
        case "new":
            AccountService::GetAccountNames();
            SMTPAccountService::DoNew();
            if (isset($_POST['username'])) {
                SMTPAccountService::DoInsert();
            }
            $_GET['title'] = "New SMTP";
            $_GET['view'] = 'new-smtp.php';
            break;
        case "edit":
            AccountService::GetAccountNames();
            SMTPAccountService::DoUpdate($_GET['e']);
            $_GET['title'] = "Edit SMTP";
            $_GET['view'] = 'edit-smtp.php';
            break;
        case "delete":
            SMTPAccountService::GetSMTPAccountById($_GET['e']);
            $_GET['title'] = "Confirm Deletion";
            $_GET['view'] = 'confirm-smtp-delete.php';
            if(isset($_POST['confirmed'])){
                SMTPAccountService::DoDelete($_POST['id']);
            }
            break;
    }
} else {
    $_GET['title'] = "SMTPs";
    $_GET['view'] = 'view-smtps.php';
    SMTPAccountService::GetSMTPAccounts();
}
require BASE_PATH .'/views/backend/account-settings/smtp.php';
