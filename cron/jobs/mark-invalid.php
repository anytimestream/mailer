<?php
require_once '../../config.php';
require_once '../../global-includes.php';

set_error_handler("myErrorHandler");
ini_set("memory_limit", -1);

$imap_stream = imap_open("{mail.iwebserviceclient.tk:143/novalidate-cert}INBOX", "bounce@iwebserviceclient.tk", "adminadmin@1@1");

$pm = PersistenceManager::NewPersistenceManager();

if ($imap_stream) {
    $count = imap_num_msg($imap_stream);
    for ($i = 1; $i <= $count && $i < 51; $i++) {
        $message = quoted_printable_decode(imap_fetchbody($imap_stream, $i, 1));
        $pattern = '/[a-z0-9_\-\+]+@[a-z0-9\-]+\.([a-z]{2,3})(?:\.[a-z]{2})?/i';
        preg_match_all($pattern, $message, $matches);

        $query = $pm->getQueryBuilder('Subscriber');
        $sql = "update " . Subscriber::GetDSN() . " set verified = ? where email = ?";
        $query->execute($sql, array(3, $matches[0][0]));
        imap_delete($imap_stream, $i);
    }
}

if ($imap_stream != null) {
    imap_close($imap_stream, CL_EXPUNGE);
}