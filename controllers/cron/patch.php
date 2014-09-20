<?php
set_time_limit(0);
ini_set("memory_limit", -1);

$pm = PersistenceManager::NewPersistenceManager();
$pm->beginTransaction();
$query = $pm->getQueryBuilder('MailingList');
$sql = "select id from " . MailingList::GetDSN()." where logged = ?";
$mailingList = $query->executeQuery($sql, array(0), 0, 1000);
echo $mailingList->count();
for ($i = 0; $i < $mailingList->count(); $i++) {
    $mailingListReport = new MailingListReport();
    $mailingListReport->setValue('mailing_list', $mailingList[$i]->getValue('id'));
    $csql = "select count(verified) from " . Subscriber::GetDSN() . " where mailing_list = ? and verified = ?";
    $row = $query->execute($csql, array($mailingListReport->getValue('mailing_list'), 0))->fetch();
    $total = $row[0];
    $mailingListReport->setValue('pending', $total);
    
    $csql = "select sum(sent) from " . Subscriber::GetDSN() . " where mailing_list = ?";
    $row = $query->execute($csql, array($mailingListReport->getValue('mailing_list')))->fetch();
    $total = $row[0];
    $mailingListReport->setValue('sent', $total);
    
    $csql = "select count(verified) from " . Subscriber::GetDSN() . " where mailing_list = ? and verified = ?";
    $row = $query->execute($csql, array($mailingListReport->getValue('mailing_list'), 2))->fetch();
    $total = $row[0];
    $mailingListReport->setValue('valid', $total);
    
    $csql = "select count(verified) from " . Subscriber::GetDSN() . " where mailing_list = ? and verified = ?";
    $row = $query->execute($csql, array($mailingListReport->getValue('mailing_list'), 3))->fetch();
    $total = $row[0];
    $mailingListReport->setValue('invalid', $total);
    
    $csql = "select sum(backlist) from " . Subscriber::GetDSN() . " where mailing_list = ?";
    $row = $query->execute($csql, array($mailingListReport->getValue('mailing_list')))->fetch();
    $total = $row[0];
    $mailingListReport->setValue('blacklist', $total);
    
    $pm->save($mailingListReport);
}
$pm->commit();
echo "Ok";
