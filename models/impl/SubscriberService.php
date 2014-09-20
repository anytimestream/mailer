<?php

class SubscriberService {

    public static function GetSubscribers() {
        try {
            $size = 20;
            $index = 1;
            $orderBy = " order by creation_date desc";
            $values = array();
            $criterias = array('Account', 'Email');
            $url_search = "";
            if (isset($_GET['page'])) {
                $index = $_GET['page'];
            }
            if (isset($_GET['order_by'])) {
                $orderBy = $_GET['order_by'];
            }
            $pm = PersistenceManager::NewPersistenceManager();
            $query = $pm->getQueryBuilder('Subscriber');
            $sql = "select account,id,email,sent,backlist,verified,creation_date,last_changed from " . Subscriber::GetDSN() . $orderBy;
            $csql = 'select count(*) from ' . Subscriber::GetDSN();
            if (isset($_GET['criteria']) && strcasecmp($_GET['criteria'], "any") != 0) {
                $url_search = "criteria=" . $_GET['criteria'] . "&value=" . $_GET['value'];
                $values[] = urldecode($_GET['value']);
                $query = $pm->getQueryBuilder('Subscriber');
                $sql = "select account,id,email,sent,backlist,verified,creation_date,last_changed from " . Subscriber::GetDSN() . " where " . $_GET['criteria'] . " = ?" . $orderBy;
                $csql = 'select count(*) from ' . Subscriber::GetDSN() . " where " . $_GET['criteria'] . " = ?";
            }
            $row = $query->execute($csql, $values)->fetch();
            $total = $row[0];
            $pagination = new DataPagination();
            $pagination->setIndex($index);
            $pagination->setSize($size);
            $pagination->setTotal($total);
            $pagination->setUrl('/backend/subscribers?' . $url_search . '&');
            $pagination->setPages();
            $_GET['subscribers'] = $query->executeQuery($sql, $values, (($index - 1) * $size), $size);
            $pagination->setPageCount($_GET['subscribers']->count());
            $_GET['pagination'] = $pagination;
            $_GET['criterias'] = $criterias;
        } catch (Exception $e) {
            ExceptionManager::RaiseException($e);
        }
    }

    public static function GetSubscribersByAccount() {
        try {
            $size = 20;
            $index = 1;
            $orderBy = "creation_date desc";
            $values = array(UserService::GetIPrincipal2());
            $criterias = array('Email');
            $url_search = "";
            if (isset($_GET['page'])) {
                $index = $_GET['page'];
            }
            if (isset($_GET['order_by'])) {
                $orderBy = $_GET['order_by'];
            }
            $pm = PersistenceManager::NewPersistenceManager();
            $query = $pm->getQueryBuilder('Subscriber');
            $sql = "select id,email,sent,backlist,verified,creation_date,last_changed from " . Subscriber::GetDSN() . " where account = ? order by " . $orderBy;
            $csql = 'select count(*) from ' . Subscriber::GetDSN() . " where account = ?";
            if (isset($_GET['criteria']) && strcasecmp($_GET['criteria'], "email") == 0) {
                $url_search = "criteria=" . $_GET['criteria'] . "&value=" . $_GET['value'];
                $values[] = urldecode($_GET['value']);
                $query = $pm->getQueryBuilder('Subscriber');
                $sql = "select account,id,email,sent,backlist,verified,creation_date,last_changed from " . Subscriber::GetDSN() . " where account = ? and email = ? order by " . $orderBy;
                $csql = 'select count(*) from ' . Subscriber::GetDSN() . " where account = ? and email = ?";
            }
            $row = $query->execute($csql, $values)->fetch();
            $total = $row[0];
            $pagination = new DataPagination();
            $pagination->setIndex($index);
            $pagination->setSize($size);
            $pagination->setTotal($total);
            $pagination->setUrl('/subscribers?' . $url_search . '&');
            $pagination->setPages();
            $_GET['subscribers'] = $query->executeQuery($sql, $values, (($index - 1) * $size), $size);
            $pagination->setPageCount($_GET['subscribers']->count());
            $_GET['pagination'] = $pagination;
            $_GET['criterias'] = $criterias;
        } catch (Exception $e) {
            ExceptionManager::RaiseException($e);
        }
    }

    public static function DoNew() {
        try {
            $_GET['subscriber'] = new Subscriber();
        } catch (Exception $e) {
            ExceptionManager::RaiseException($e);
        }
    }

    public static function DoUpdate($id) {
        try {

            $pm = PersistenceManager::getConnection();

            $_GET['subscriber'] = new Subscriber();

            $subscriber = $pm->getObjectById('Subscriber', $id);
            if ($subscriber != null) {
                if (isset($_POST['name'])) {
                    $subscriber->setValue('name', $_POST['name']);
                    $subscriber->CheckValidationRules($pm);
                    $_GET['subscriber'] = $subscriber;

                    if (count($subscriber->ValidationErrors) == 0) {
                        $pm->save($subscriber);
                        $_GET['status'] = "Subscriber Updated";
                    } else {
                        $_GET['msg'] = 'Validation Failed';
                    }
                }
                $_GET['subscriber'] = $subscriber;
            } else {
                $_GET['msg'] = 'Subscriber not found';
            }
        } catch (Exception $e) {
            if (strpos($e, '1062 Duplicate entry') != false) {
                $_GET['msg'] = "Subscriber already exists";
            } else {
                ExceptionManager::RaiseException($e);
            }
        }
    }

    public static function GetSubscriberById($id) {
        try {

            $pm = PersistenceManager::getConnection();

            $subscriber = $pm->getObjectById('Subscriber', $id);
            if ($subscriber != null) {
                $_GET['subscriber'] = $subscriber;
            } else {
                $_GET['msg'] = 'Subscriber not found';
            }
        } catch (Exception $e) {
            ExceptionManager::RaiseException($e);
        }
    }

    public static function UnubscriberById($id) {
        try {
            $pm = PersistenceManager::getConnection();
            $pm->beginTransaction();
            $query = $pm->getQueryBuilder('Subscriber');
            $subscriber = $pm->getObjectById('Subscriber', $id);
            $sql = "update " . Subscriber::GetDSN() . " set backlist = ? where id = ?";
            $query->execute($sql, array(1, $subscriber->getValue('id')));

            $sql = "update " . MailingListReport::GetDSN() . " set blacklist = blacklist + 1 where mailing_list = ?";
            $query->execute($sql, array($subscriber->getValue('mailing_list')));
            $pm->commit();
        } catch (Exception $e) {
            ExceptionManager::RaiseException($e);
        }
    }

    public static function DoDelete($id) {
        try {

            $pm = PersistenceManager::getConnection();

            $subscriber = $pm->getObjectById('Subscriber', $id);
            if ($subscriber != null) {
                $pm->deleteObjectById('Subscriber', $id);
                header('location: ' . CONTEXT_PATH . '/backend/subscriber-settings/subscribers');
            } else {
                $_GET['msg'] = 'Page not found';
            }
        } catch (Exception $e) {
            ExceptionManager::RaiseException($e);
        }
    }

}
