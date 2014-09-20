<?php

class SMTPAccountService {

    public static function GetSMTPAccounts() {
        try {
            $size = 20;
            $index = 1;
            $orderBy = "account,s.provider,s.host,s.status";
            $values = array();
            if (isset($_GET['page'])) {
                $index = $_GET['page'];
            }
            if (isset($_GET['order_by'])) {
                $orderBy = $_GET['order_by'];
            }
            $pm = PersistenceManager::NewPersistenceManager();
            $query = $pm->getQueryBuilder('SMTPAccount');
            $sql = "select a.name as account,s.id,s.provider,s.host,s.username,s.password,s.status,s.creation_date,s.last_changed  from " . Account::GetDSN() . " as a inner join ".SMTPAccount::GetDSN()." as s on a.no = s.account order by " . $orderBy;
            $csql = 'select count(*) from ' . SMTPAccount::GetDSN();
            $row = $query->execute($csql, $values)->fetch();
            $total = $row[0];
            $pagination = new DataPagination();
            $pagination->setIndex($index);
            $pagination->setSize($size);
            $pagination->setTotal($total);
            $pagination->setUrl('/backend/account-settings/smtps?');
            $pagination->setPages();
            $_GET['smtps'] = $query->executeQuery($sql, $values, (($index - 1) * $size), $size);
            $pagination->setPageCount($_GET['smtps']->count());
            $_GET['pagination'] = $pagination;
        } catch (Exception $e) {
            echo $e;
            ExceptionManager::RaiseException($e);
        }
    }

    public static function DoNew() {
        try {
            $_GET['smtp-account'] = new SMTPAccount();
        } catch (Exception $e) {
            ExceptionManager::RaiseException($e);
        }
    }

    public static function DoInsert() {
        $pm = null;
        try {
            $assertProperties = new AssertProperties();
            $assertProperties->addProperty($_POST, 'account');
            $assertProperties->addProperty($_POST, 'provider');
            $assertProperties->addProperty($_POST, 'host');
            $assertProperties->addProperty($_POST, 'username');
            $assertProperties->addProperty($_POST, 'password');
            $assertProperties->assert();

            $smtpAccount = new SMTPAccount();

            $smtpAccount->setValue('username', $_POST['username']);
            $smtpAccount->setValue('password', $_POST['password']);
            $smtpAccount->setValue('account', $_POST['account']);
            $smtpAccount->setValue('provider', $_POST['provider']);
            $smtpAccount->setValue('host', $_POST['host']);
            $smtpAccount->setValue('status', 1);

            $smtpAccount->CheckValidationRules($pm);

            $_GET['smtp-account'] = $smtpAccount;

            if (count($smtpAccount->ValidationErrors) == 0) {
                $pm = PersistenceManager::getConnection();
                $pm->save($smtpAccount);
                $_GET['status'] = "SMTP Created";
                $_GET['smtp-account'] = new SMTPAccount();
            } else {
                $_GET['msg'] = 'Validation Failed';
            }
        } catch (Exception $e) {
            if (strpos($e, '1062 Duplicate entry') != false) {
                $_GET['msg'] = "SMTP already exists";
            } else {
                ExceptionManager::RaiseException($e);
            }
        }
    }

    public static function DoUpdate($id) {
        try {

            $pm = PersistenceManager::getConnection();

            $_GET['smtp-account'] = new SMTPAccount();

            $smtpAccount = $pm->getObjectById('SMTPAccount', $id);

            if ($smtpAccount != null) {
                if (isset($_POST['username'])) {
                    $smtpAccount->setValue('username', $_POST['username']);
                    $smtpAccount->setValue('password', $_POST['password']);
                    $smtpAccount->setValue('status', $_POST['status']);
                    $smtpAccount->setValue('account', $_POST['account']);
                    $smtpAccount->setValue('provider', $_POST['provider']);
                    $smtpAccount->setValue('host', $_POST['host']);
                    $smtpAccount->CheckValidationRules($pm);
                    $_GET['smtp-account'] = $smtpAccount;

                    if (count($smtpAccount->ValidationErrors) == 0) {
                        $pm->save($smtpAccount);
                        $_GET['status'] = "SMTP Updated";
                    } else {
                        $_GET['msg'] = 'Validation Failed';
                    }
                }
                $_GET['smtp-account'] = $smtpAccount;
            } else {
                $_GET['msg'] = 'SMTP not found';
            }
        } catch (Exception $e) {
            if (strpos($e, '1062 Duplicate entry') != false) {
                $_GET['msg'] = "SMTP already exists";
            } else {
                ExceptionManager::RaiseException($e);
            }
        }
    }

    public static function GetSMTPAccountById($id) {
        try {

            $pm = PersistenceManager::getConnection();

            $smtpAccount = $pm->getObjectById('SMTPAccount', $id);
            if ($smtpAccount != null) {
                $_GET['smtp-account'] = $smtpAccount;
            } else {
                $_GET['msg'] = 'SMTP not found';
            }
        } catch (Exception $e) {
            ExceptionManager::RaiseException($e);
        }
    }

    public static function DoDelete($id) {
        try {

            $pm = PersistenceManager::getConnection();

            $smtpAccount = $pm->getObjectById('SMTPAccount', $id);
            if ($smtpAccount != null) {
                $pm->deleteObjectById('SMTPAccount', $id);
                header('location: ' . CONTEXT_PATH . '/backend/account-settings/smtps');
            } else {
                $_GET['msg'] = 'SMTP not found';
            }
        } catch (Exception $e) {
            ExceptionManager::RaiseException($e);
        }
    }

}
