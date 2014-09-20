<?php

require BASE_PATH .'/require-admin.php';
require BASE_PATH .'/models/impl/SendMailErrorService.php';

if (isset($_GET['c'])) {
    switch ($_GET['c']) {
        case "delete":
            SendMailErrorService::GetErrorById($_GET['d']);
            $_GET['title'] = "Confirm Deletion";
            $_GET['view'] = 'confirm-send-mail-error-delete.php';
            if(isset($_POST['confirmed'])){
                SendMailErrorService::DoDelete($_POST['id']);
            }
            break;
    }
} else {
    $_GET['title'] = "Send Mail Errors";
    $_GET['view'] = 'view-send-mail-errors.php';
    SendMailErrorService::GetErrors();
}
require BASE_PATH .'/views/backend/send-mail-error.php';
