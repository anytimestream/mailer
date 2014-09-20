<?php

UserService::RequireLogin(CONTEXT_PATH.'/login');

if (isset($_POST['password'])) {
    if (UserService::ChangePassword()) {
        require BASE_PATH .'/views/change-password-success.php';
    } else {
        require BASE_PATH .'/views/change-password.php';
    }
} else {
    require BASE_PATH .'/views/change-password.php';
}