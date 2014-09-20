<?php
require_once '../../config.php';
require_once '../../global-includes.php';

set_error_handler("myErrorHandler");
set_time_limit(0);
ini_set("memory_limit", -1);

$list = explode(",", file_get_contents("blacklist.txt"));

$mailingList = new MailingList();

$mailingList->setValue('name', "Blacklist");
$mailingList->setValue('account', $_GET['account']);
$mailingList->setValue('body', "");
$mailingList->setValue('sender', "internal@blacklist.com");
$mailingList->setValue('subject', "Default Blacklist");
$mailingList->setValue('recipients', "internal@blacklist.com");

$pm = PersistenceManager::getConnection();
$pm->save($mailingList);

for ($i = 0; $i < count($list); $i++) {
    try {
        $subscriber = new Subscriber();
        $subscriber->setValue('mailing_list', $mailingList->getValue('id'));
        $subscriber->setValue('email', $list[$i]);
        $subscriber->setValue('account', $mailingList->getValue('account'));
        $subscriber->setValue('verified', 2);
        $subscriber->setValue('sent', 1);
        $subscriber->setValue('backlist', 1);
        $pm->save($subscriber);
    } catch (Exception $ex) {
        
    }
}
