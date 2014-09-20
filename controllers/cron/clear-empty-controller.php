<?php
$pm = PersistenceManager::NewPersistenceManager();
$query = $pm->getQueryBuilder('MailingList');
$sql = "select m.id,m.attachments from " . MailingList::GetDSN() . " as m inner join ".MailingListReport::GetDSN()." as mr on m.id = mr.mailing_list where (mr.pending + mr.valid + mr.invalid + mr.sent + mr.blacklist) = ?";
$mailingList = $query->executeQuery($sql, array(0), 0, 4000);
for($i = 0; $i < $mailingList->count(); $i++){
    $query->execute("delete from ".MailingList::GetDSN()." where id = ?", array($mailingList[$i]->getValue('id')));
}