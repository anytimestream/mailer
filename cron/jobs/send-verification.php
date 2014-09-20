<?php

require_once '../../config.php';
require_once '../../global-includes.php';

set_time_limit(0);

set_error_handler("myErrorHandler");
ini_set("memory_limit", -1);

$pm = PersistenceManager::NewPersistenceManager();
$query = $pm->getQueryBuilder('Subscriber');
$values = array(0, $_GET['account']);
$sql = "select id,email from " . Subscriber::GetDSN() . " where verified = ? and account = ? order by creation_date";

$subscribers = $query->executeQuery($sql, $values, 0, 100);
for ($i = 0; $i < $subscribers->count(); $i++) {

    sleep(2);

    $domain = substr($subscribers[$i]->getValue('email'), strpos($subscribers[$i]->getValue('email'), "@") + 1);

    if (checkdnsrr($domain, "MX")) {
        $sql = "update " . Subscriber::GetDSN() . " set verified = ? where id = ?";
        $query->execute($sql, array(2, $subscribers[$i]->getValue('id')));
    } else {
        $sql = "update " . Subscriber::GetDSN() . " set verified = ? where id = ?";
        $query->execute($sql, array(3, $subscribers[$i]->getValue('id')));
    }
}