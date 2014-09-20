<?php

require BASE_PATH .'/require-admin.php';
require BASE_PATH .'/models/impl/AccountService.php';
require BASE_PATH .'/models/impl/AccountUserService.php';

if (isset($_GET['d'])) {
    switch ($_GET['d']) {
        case "new":
            AccountService::GetAccountNames();
            AccountUserService::DoNew();
            if (isset($_POST['username'])) {
                AccountUserService::DoInsert();
            }
            $_GET['title'] = "New User";
            $_GET['view'] = 'new-user.php';
            break;
        case "edit":
            AccountService::GetAccountNames();
            AccountUserService::DoUpdate($_GET['e']);
            $_GET['title'] = "Edit User";
            $_GET['view'] = 'edit-user.php';
            break;
        case "delete":
            AccountUserService::GetAccountById($_GET['e']);
            $_GET['title'] = "Confirm Deletion";
            $_GET['view'] = 'confirm-user-delete.php';
            if(isset($_POST['confirmed'])){
                AccountUserService::DoDelete($_POST['id']);
            }
            break;
    }
} else {
    $_GET['title'] = "Users";
    $_GET['view'] = 'view-users.php';
    AccountUserService::GetUsers();
}
require BASE_PATH .'/views/backend/account-settings/user.php';
