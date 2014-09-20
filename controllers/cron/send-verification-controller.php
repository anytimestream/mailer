<?php
set_time_limit(0);

$pm = PersistenceManager::NewPersistenceManager();
$query = $pm->getQueryBuilder('Subscriber');
$values = array(0, $_GET['account']);
$sql = "select id,email,mailing_list from " . Subscriber::GetDSN() . " where verified = ? and account = ? order by creation_date";

$subscribers = $query->executeQuery($sql, $values, 0, 100);

for ($i = 0; $i < $subscribers->count(); $i++) {

    sleep(2);

    $domain = substr($subscribers[$i]->getValue('email'), strpos($subscribers[$i]->getValue('email'), "@") + 1);

    if (checkdnsrr($domain, "MX")) {
        $pm->beginTransaction();
        $sql = "update " . Subscriber::GetDSN() . " set verified = ? where id = ?";
        $query->execute($sql, array(2, $subscribers[$i]->getValue('id')));
        
        $sql = "update " . MailingListReport::GetDSN() . " set valid = valid + 1, pending = pending - 1 where mailing_list = ?";
        $query->execute($sql, array($subscribers[$i]->getValue('mailing_list')));
        $pm->commit();
    } else {
        $pm->beginTransaction();
        $sql = "update " . Subscriber::GetDSN() . " set verified = ? where id = ?";
        $query->execute($sql, array(3, $subscribers[$i]->getValue('id')));
        
        $sql = "update " . MailingListReport::GetDSN() . " set invalid = invalid + 1, pending = pending - 1 where mailing_list = ?";
        $query->execute($sql, array($subscribers[$i]->getValue('mailing_list')));
        $pm->commit();
    }
}