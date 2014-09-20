<?php
require BASE_PATH .'/models/impl/SubscriberService.php';

if (isset($_GET['b'])) {
    SubscriberService::UnubscriberById($_GET['b']);
}
require BASE_PATH .'/views/unsubscribe.php';
