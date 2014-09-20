<?php
require_once '../../config.php';
require_once '../../global-includes.php';
set_time_limit(0);
ini_set("memory_limit", -1);

$pm = PersistenceManager::NewPersistenceManager();
$pm->beginTransaction();
$query = $pm->getQueryBuilder('MailingList');
$sql = "select mailing_list as id from " . MailingListReport::GetDSN()." where sent < valid";
$mailingList = $query->executeQuery($sql, array(), 0, 1000);
for ($i = 0; $i < $mailingList->count(); $i++) {
    $mailingListReport = new MailingListReport();
    $mailingListReport->setValue('mailing_list', $mailingList[$i]->getValue('id'));
    $csql = "select count(verified) from " . Subscriber::GetDSN() . " where mailing_list = ? and verified = ?";
    $row = $query->execute($csql, array($mailingListReport->getValue('mailing_list'), 0))->fetch();
    $total = $row[0];
    $mailingListReport->setValue('pending', $total);
   
    $pm->save($mailingListReport);
}
$pm->commit();
echo "Ok";
