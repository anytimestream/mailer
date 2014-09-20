<?php
require_once '../../config.php';
require_once '../../global-includes.php';

$pm = PersistenceManager::NewPersistenceManager();
$query = $pm->getQueryBuilder('Subscriber');

$sql = "update from " . Subscriber::GetDSN() . " set verified = ?, sent = ? where email = ?";
$query->execute($sql, array(3, 0, $_GET['email']));

$sendMailError = new SendMailError();
$sendMailError->setValue("type", "bounce");
$sendMailError->setValue("email", "chat4zeal@yahoo.com");
$sendMailError->setValue("error", $_GET['email']);
$pm->save($sendMailError);
