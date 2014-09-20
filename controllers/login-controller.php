<?php
if (UserService::IsAuthenticated()) {
    UserService::Logout();
} else if (isset($_POST['username'])) {
    UserService::Login($_POST['username'], $_POST['password'], CONTEXT_PATH.'/detect-account');
}
require BASE_PATH .'/views/login.php';
?>