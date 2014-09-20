<?php

require BASE_PATH .'/require-admin.php';
require BASE_PATH .'/models/impl/AccountService.php';

if (isset($_GET['d'])) {
    switch ($_GET['d']) {
        case "new":
            AccountService::DoNew();
            if (isset($_POST['name'])) {
                AccountService::DoInsert();
            }
            $_GET['title'] = "New Account";
            $_GET['view'] = 'new-account.php';
            break;
        case "edit":
            AccountService::DoUpdate($_GET['e']);
            $_GET['title'] = "Edit Account";
            $_GET['view'] = 'edit-account.php';
            break;
        case "delete":
            AccountService::GetAccountById($_GET['e']);
            $_GET['title'] = "Confirm Deletion";
            $_GET['view'] = 'confirm-account-delete.php';
            if(isset($_POST['confirmed'])){
                AccountService::DoDelete($_POST['id']);
            }
            break;
    }
} else {
    $_GET['title'] = "Accounts";
    $_GET['view'] = 'view-accounts.php';
    AccountService::GetAccounts();
}
require BASE_PATH .'/views/backend/account-settings/account.php';
