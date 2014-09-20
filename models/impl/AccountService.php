<?php

class AccountService {

    public static function GetAccounts() {
        try {
            $size = 20;
            $index = 1;
            $orderBy = "name";
            $values = array();
            if (isset($_GET['page'])) {
                $index = $_GET['page'];
            }
            if (isset($_GET['order_by'])) {
                $orderBy = $_GET['order_by'];
            }
            $pm = PersistenceManager::NewPersistenceManager();
            $query = $pm->getQueryBuilder('Account');
            $sql = "select * from " . Account::GetDSN() . " order by " . $orderBy;
            $csql = 'select count(*) from ' . Account::GetDSN();
            $row = $query->execute($csql, $values)->fetch();
            $total = $row[0];
            $pagination = new DataPagination();
            $pagination->setIndex($index);
            $pagination->setSize($size);
            $pagination->setTotal($total);
            $pagination->setUrl('/backend/account-settings/accounts?');
            $pagination->setPages();
            $_GET['accounts'] = $query->executeQuery($sql, $values, (($index - 1) * $size), $size);
            $pagination->setPageCount($_GET['accounts']->count());
            $_GET['pagination'] = $pagination;
        } catch (Exception $e) {
            ExceptionManager::RaiseException($e);
        }
    }
    
    public static function GetAccountNames() {
        try {
            $orderBy = "name";
            $values = array();
            $pm = PersistenceManager::NewPersistenceManager();
            $query = $pm->getQueryBuilder('Account');
            $sql = "select * from " . Account::GetDSN() . " order by " . $orderBy;
            $_GET['account-names'] = $query->executeQuery($sql, $values, 0, 3000);
        } catch (Exception $e) {
            ExceptionManager::RaiseException($e);
        }
    }

    public static function DoNew() {
        try {
            $_GET['account'] = new Account();
        } catch (Exception $e) {
            ExceptionManager::RaiseException($e);
        }
    }

    public static function DoInsert() {
        $pm = null;
        try {
            $assertProperties = new AssertProperties();
            $assertProperties->addProperty($_POST, 'name');
            $assertProperties->addProperty($_POST, 'retention');
            $assertProperties->assert();

            $account = new Account();

            $account->setValue('name', $_POST['name']);
            $account->setValue('retention', $_POST['retention']);

            $account->CheckValidationRules($pm);

            $_GET['account'] = $account;

            if (count($account->ValidationErrors) == 0) {
                $pm = PersistenceManager::getConnection();
                $pm->save($account);
                $_GET['status'] = "Account Created";
                $_GET['account'] = new Account();
            } else {
                $_GET['msg'] = 'Validation Failed';
            }
        } catch (Exception $e) {
            if (strpos($e, '1062 Duplicate entry') != false) {
                $_GET['msg'] = "Account already exists";
            } else {
                ExceptionManager::RaiseException($e);
            }
        }
    }

    public static function DoUpdate($id) {
        try {

            $pm = PersistenceManager::getConnection();

            $_GET['account'] = new Account();

            $account = $pm->getObjectById('Account', $id);
            if ($account != null) {
                if (isset($_POST['name'])) {
                    $account->setValue('name', $_POST['name']);
                    $account->setValue('retention', $_POST['retention']);
                    $account->CheckValidationRules($pm);
                    $_GET['account'] = $account;

                    if (count($account->ValidationErrors) == 0) {
                        $pm->save($account);
                        $_GET['status'] = "Account Updated";
                    } else {
                        $_GET['msg'] = 'Validation Failed';
                    }
                }
                $_GET['account'] = $account;
            } else {
                $_GET['msg'] = 'Account not found';
            }
        } catch (Exception $e) {
            if (strpos($e, '1062 Duplicate entry') != false) {
                $_GET['msg'] = "Account already exists";
            } else {
                ExceptionManager::RaiseException($e);
            }
        }
    }

    public static function GetAccountById($id) {
        try {

            $pm = PersistenceManager::getConnection();

            $account = $pm->getObjectById('Account', $id);
            if ($account != null) {
                $_GET['account'] = $account;
            } else {
                $_GET['msg'] = 'Account not found';
            }
        } catch (Exception $e) {
            ExceptionManager::RaiseException($e);
        }
    }

    public static function DoDelete($id) {
        try {

            $pm = PersistenceManager::getConnection();

            $account = $pm->getObjectById('Account', $id);
            if ($account != null) {
                $pm->deleteObjectById('Account', $id);
                header('location: ' . CONTEXT_PATH . '/backend/account-settings/accounts');
            } else {
                $_GET['msg'] = 'Page not found';
            }
        } catch (Exception $e) {
            ExceptionManager::RaiseException($e);
        }
    }

}
