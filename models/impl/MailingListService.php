<?php

class MailingListService {

    public static function GetMailingLists() {
        try {
            $size = 20;
            $index = 1;
            $orderBy = "m.creation_date desc";
            $values = array();
            $criterias = array('Account');
            $url_search = "";
            if (isset($_GET['page'])) {
                $index = $_GET['page'];
            }
            if (isset($_GET['order_by'])) {
                $orderBy = $_GET['order_by'];
            }
            $pm = PersistenceManager::NewPersistenceManager();
            $query = $pm->getQueryBuilder('MailingList');
            $sql = "select a.name as account,m.logged,m.id,m.logged,m.subject,m.sender,m.name,m.creation_date,m.last_changed,mr.sent as sent,mr.valid as valid,mr.invalid as invalid,mr.pending as pending,mr.blacklist as blacklist from " . MailingList::GetDSN() . " as m left join ".MailingListReport::GetDSN()." as mr on m.id = mr.mailing_list inner join " . Account::GetDSN() . " as a on m.account = a.no order by " . $orderBy;
            $csql = 'select count(*) from ' . MailingList::GetDSN();
            if (isset($_GET['criteria']) && strcasecmp($_GET['criteria'], "account") == 0) {
                $url_search = "criteria=" . $_GET['criteria'] . "&value=" . $_GET['value'];
                $values[] = urldecode($_GET['value']);
                $sql = "select a.name as account,m.logged,m.logged,m.id,m.subject,m.sender,m.name,m.creation_date,m.last_changed,mr.sent as sent,mr.valid as valid,mr.invalid as invalid,mr.pending as pending,mr.blacklist as blacklist from " . MailingList::GetDSN() . " as m left join " . MailingListReport::GetDSN() . " as mr on m.id = mr.mailing_list inner join " . Account::GetDSN() . " as a on m.account = a.no where m.account = ? order by " . $orderBy;
                $csql = 'select count(*) from ' . MailingList::GetDSN() . " where account = ?";
            }
            $row = $query->execute($csql, $values)->fetch();
            $total = $row[0];
            $pagination = new DataPagination();
            $pagination->setIndex($index);
            $pagination->setSize($size);
            $pagination->setTotal($total);
            $pagination->setUrl('/backend/mailing-list?' . $url_search . '&');
            $pagination->setPages();
            $_GET['mailing-list'] = $query->executeQuery($sql, $values, (($index - 1) * $size), $size);
            $pagination->setPageCount($_GET['mailing-list']->count());
            $_GET['criterias'] = $criterias;
            $_GET['pagination'] = $pagination;
        } catch (Exception $e) {
            echo $e;
            ExceptionManager::RaiseException($e);
        }
    }

    public static function GetMailingListsByAccount() {
        try {
            $size = 20;
            $index = 1;
            $orderBy = "m.creation_date desc";
            $values = array(UserService::GetIPrincipal2());
            if (isset($_GET['page'])) {
                $index = $_GET['page'];
            }
            if (isset($_GET['order_by'])) {
                $orderBy = $_GET['order_by'];
            }
            $pm = PersistenceManager::NewPersistenceManager();
            $query = $pm->getQueryBuilder('MailingList');
            $sql = "select m.id,m.logged,m.subject,m.sender,m.name,m.creation_date,m.last_changed,mr.sent as sent,mr.valid as valid,mr.invalid as invalid,mr.pending as pending,mr.blacklist as blacklist from " . MailingList::GetDSN() . " as m left join " . MailingListReport::GetDSN() . " as mr on mr.mailing_list = m.id where m.account = ? order by " . $orderBy;
            $csql = 'select count(*) from ' . MailingList::GetDSN() . " where account = ?";
            $row = $query->execute($csql, $values)->fetch();
            $total = $row[0];
            $pagination = new DataPagination();
            $pagination->setIndex($index);
            $pagination->setSize($size);
            $pagination->setTotal($total);
            $pagination->setUrl('/mailing-list?');
            $pagination->setPages();
            $_GET['mailing-list'] = $query->executeQuery($sql, $values, (($index - 1) * $size), $size);
            $pagination->setPageCount($_GET['mailing-list']->count());
            $_GET['pagination'] = $pagination;
        } catch (Exception $e) {
            ExceptionManager::RaiseException($e);
        }
    }

    public static function DoNew() {
        try {
            $_GET['mailing-list'] = new MailingList();
        } catch (Exception $e) {
            ExceptionManager::RaiseException($e);
        }
    }

    public static function DoInsert() {
        $pm = null;
        try {
            $assertProperties = new AssertProperties();
            $assertProperties->addProperty($_POST, 'name');
            $assertProperties->addProperty($_POST, 'body');
            $assertProperties->addProperty($_POST, 'sender');
            $assertProperties->addProperty($_POST, 'subject');
            $assertProperties->addProperty($_POST, 'recipients');
            $assertProperties->assert();

            $mailingList = new MailingList();

            $mailingList->setValue('name', $_POST['name']);
            $mailingList->setValue('account', UserService::GetIPrincipal2());
            if (get_magic_quotes_gpc()) {
                $mailingList->setValue('body', stripslashes($_POST['body']));
            } else {
                $mailingList->setValue('body', $_POST['body']);
            }
            $mailingList->setValue('sender', $_POST['sender']);
            $mailingList->setValue('subject', $_POST['subject']);
            $mailingList->setValue('recipients', $_POST['recipients']);

            $mailingList->CheckValidationRules($pm);

            $_GET['mailing-list'] = $mailingList;

            if (count($mailingList->ValidationErrors) == 0) {
                $attachments = array();
                for ($i = 1; $i <= 3; $i++) {
                    if (file_exists($_FILES['file' . $i]['tmp_name'])) {
                        $key = 'attachments/' . str_replace(".", "", uniqid(rand(), true)) . str_replace(" ", "-", $_FILES['file' . $i]['name']);
                        $attachments[] = $key;
                        if (!FileService::UploadToS3('file' . $i, $key)) {
                            throw new Exception('10023');
                        }
                    }
                }
                $mailingList->setValue("attachments", json_encode($attachments));
                $pm = PersistenceManager::getConnection();
                $pm->save($mailingList);
                $mailingListReport = new MailingListReport();
                $mailingListReport->setValue('mailing_list', $mailingList->getValue('id'));
                $account = $pm->getObjectByColumn('Account', 'no', $mailingList->getValue('account'));
                $list = explode(',', strtolower($_POST['recipients']));
                for ($i = 0; $i < count($list); $i++) {
                    if (strpos($list[$i], '@') != false) {
                        try {
                            $subscriber = new Subscriber();
                            $subscriber->setValue('mailing_list', $mailingList->getValue('id'));
                            $subscriber->setValue('email', trim($list[$i]));
                            $subscriber->setValue('account', $mailingList->getValue('account'));
                            $pm->save($subscriber);
                        } catch (Exception $ex) {
                            try {
                                $sql = "update " . Subscriber::GetDSN() . " set mailing_list = ?, sent = ? where account = ? and email = ? and sent = ? and last_changed <= ?";
                                $query = $pm->getQueryBuilder("Subscriber");
                                $retentionPeriod = time() - (60 * 60 * 24 * $account->getValue('retention'));
                                $query->execute($sql, array($mailingList->getValue('id'), 0, $mailingList->getValue('account'), trim($list[$i]), 1, date('Y-m-d H:i:s', $retentionPeriod)));
                            } catch (Exception $ex2) {
                                
                            }
                        }
                    }
                }
                $_GET['status'] = "Saved";
                $_GET['mailing-list'] = new MailingList();
            } else {
                $_GET['msg'] = 'Validation Failed';
            }
        } catch (Exception $e) {
            if (strpos($e, '10023') != false) {
                $_GET['msg'] = "Unable to upload image";
            } else {
                ExceptionManager::RaiseException($e);
            }
        }
    }

    public static function DoUpdate($id) {
        try {

            $pm = PersistenceManager::getConnection();

            $_GET['subscriber'] = new MailingList();

            $subscriber = $pm->getObjectById('MailingList', $id);
            if ($subscriber != null) {
                if (isset($_POST['name'])) {
                    $subscriber->setValue('name', $_POST['name']);
                    $subscriber->CheckValidationRules($pm);
                    $_GET['subscriber'] = $subscriber;

                    if (count($subscriber->ValidationErrors) == 0) {
                        $pm->save($subscriber);
                        $_GET['status'] = "MailingList Updated";
                    } else {
                        $_GET['msg'] = 'Validation Failed';
                    }
                }
                $_GET['subscriber'] = $subscriber;
            } else {
                $_GET['msg'] = 'MailingList not found';
            }
        } catch (Exception $e) {
            if (strpos($e, '1062 Duplicate entry') != false) {
                $_GET['msg'] = "MailingList already exists";
            } else {
                ExceptionManager::RaiseException($e);
            }
        }
    }

    public static function GetMailingListById($id) {
        try {

            $pm = PersistenceManager::getConnection();

            $subscriber = $pm->getObjectById('MailingList', $id);
            if ($subscriber != null) {
                $_GET['subscriber'] = $subscriber;
            } else {
                $_GET['msg'] = 'MailingList not found';
            }
        } catch (Exception $e) {
            ExceptionManager::RaiseException($e);
        }
    }

    public static function DoDelete($id) {
        try {

            $pm = PersistenceManager::getConnection();

            $mailingList = $pm->getObjectById('MailingList', $id);
            if ($mailingList != null) {
                $pm->deleteObjectById('MailingList', $id);
                header('location: ' . CONTEXT_PATH . '/backend/mailing-list');
            } else {
                $_GET['msg'] = 'Page not found';
            }
        } catch (Exception $e) {
            ExceptionManager::RaiseException($e);
        }
    }

}
