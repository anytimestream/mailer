<?php
require BASE_PATH .'/models/impl/FileService.php';

$pm = PersistenceManager::NewPersistenceManager();
$query = $pm->getQueryBuilder('MailingList');
$sql = "select m.id,m.attachments from " . MailingList::GetDSN() . " as m inner join ".MailingListReport::GetDSN()." as mr on m.id = mr.mailing_list where (mr.pending + mr.valid + mr.invalid + mr.sent) = ?";
$mailingList = $query->executeQuery($sql, array(0), 0, 4000);
for($i = 0; $i < $mailingList->count(); $i++){
    if (strlen($mailingList[$i]->getValue('attachments')) > 0) {
        $files = json_decode($mailingList[$i]->getValue('attachments'));
        for ($f = 0; $f < count($files); $f++) {
            FileService::DeleteFromS3($files[$f]);
        }
    }
}