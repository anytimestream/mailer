<?php
require_once '../../config.php';
require_once '../../global-includes.php';

set_error_handler("myErrorHandler");
ini_set("memory_limit", -1);

$current_time = strtotime(date("Y-m-d H:i:s"));

$time = $current_time  - (60 * 60 * 2);

$pm = PersistenceManager::NewPersistenceManager();
$query = $pm->getQueryBuilder('Subscriber');
$sql = "update " . Subscriber::GetDSN() . " set verified = ? where verified = ? and last_changed < ?";
$query->execute($sql, array(2, 1, date("Y-m-d H:i:s", $time)));