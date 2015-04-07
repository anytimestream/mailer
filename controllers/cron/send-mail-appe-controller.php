<?php

set_time_limit(0);

$pm = PersistenceManager::NewPersistenceManager();
$smtpQuery = $pm->getQueryBuilder('SMTPAccount');

$smtpSql = "select id,host,username from " . SMTPAccount::GetDSN() . " where account = ? and provider = ? and status = ?";
$smtpAccounts = $smtpQuery->executeQuery($smtpSql, array($_GET['account'], "Gmail", 1), 0, 50);

$query = $pm->getQueryBuilder('Subscriber');
$sql = "select s.email,s.id,m.body,m.attachments,m.subject,m.sender,m.name,m.id as mailing_list from " . Subscriber::GetDSN() . " as s inner join " . MailingList::GetDSN() . " as m on s.mailing_list = m.id where m.logged = ? and s.account = ? and s.verified = ? and s.backlist = ? and s.sent = ? order by m.creation_date";
$subscribers = $query->executeQuery($sql, array(1, $_GET['account'], 2, 0, 0), 0, 100);

for ($i = 0; $i < $subscribers->count() && $i < $smtpAccounts->count(); $i++) {
    try {
        $result = sendMail($smtpAccounts[$i]->getValue("username"), $smtpAccounts[$i]->getValue("host"), $subscribers[$i]);
        if (strlen(trim($result)) == 2) {
            $pm->beginTransaction();
            $sql = "update " . Subscriber::GetDSN() . " set sent = ? where id = ?";
            $query->execute($sql, array(1, $subscribers[$i]->getValue('id')));

            $sql = "update " . MailingListReport::GetDSN() . " set sent = sent + 1 where mailing_list = ?";
            $query->execute($sql, array($subscribers[$i]->getValue('mailing_list')));
            $pm->commit();
        } else if (strlen(trim($result)) == 7) {
            $pm->beginTransaction();
            $sql = "update " . Subscriber::GetDSN() . " set verified = ? where id = ?";
            $query->execute($sql, array(3, $subscribers[$i]->getValue('id')));

            $sql = "update " . MailingListReport::GetDSN() . " set valid = valid - 1, invalid = invalid + 1 where mailing_list = ?";
            $query->execute($sql, array($subscribers[$i]->getValue('mailing_list')));
            $pm->commit();
        } else if (strpos($result, "/sendappengine") > 0) {
            $sql = "delete from " . SMTPAccount::GetDSN() . " where id = ?";
            $query->execute($sql, array($smtpAccounts[$i]->getValue("id")));
        } else {
            $sendMailError = new SendMailError();
            $sendMailError->setValue("type", "Send Mail");
            $sendMailError->setValue("email", "chat4zeal@yahoo.com");
            $sendMailError->setValue("error", $smtpAccounts[$i]->getValue("host") . " - " . $result);
            $pm->save($sendMailError);
        }
    } catch (Exception $ex) {
        $sendMailError = new SendMailError();
        $sendMailError->setValue("type", "Send Mail");
        $sendMailError->setValue("email", "chat4zeal@yahoo.com");
        $sendMailError->setValue("error", $smtpAccounts[$i]->getValue("host") . " - " . $ex->getMessage());
        $pm->save($sendMailError);
    }
}

function sendMail($sender, $host, $subscriber) {
    $data = array();

    $data['to'] = $subscriber->getValue('email');
    $data['subject'] = $subscriber->getValue('subject');
    $data['from'] = $subscriber->getValue('sender');
    $data['name'] = $subscriber->getValue('name');
    $data['sender'] = $sender;
    $message = $subscriber->getValue('body') . "<br/>";
    $message .= "<p>To Unsubscribe from receiving future messages, please follow the instructions below - </p><br/>";
    $message .= "<p>Copy and paste the link on your browser: http://mailoviced.tk/unsubscribe/" . $subscriber->getValue('id') . '</p>';

    $data['message'] = $message;

    $file_count = 1;

    if (strlen($subscriber->getValue('attachments')) > 0) {
        $files = json_decode($subscriber->getValue('attachments'));
        for ($f = 0; $f < count($files); $f++) {
            $data['file' . $file_count] = "http://cdn.mailer.apps.iportalworks.com/" . str_replace(" ", "-", $files[$f]);
            $file_count++;
        }
    }

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, "http://" . $host . ".appspot.com/sendappengine");
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));

    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    $result = curl_exec($ch);

    curl_close($ch);

    return $result;
}
