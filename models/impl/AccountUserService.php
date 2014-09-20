<?php

class AccountUserService {

    public static function GetUsers() {
        try {
            $size = 20;
            $index = 1;
            $orderBy = "account, u.username";
            $values = array();
            if (isset($_GET['page'])) {
                $index = $_GET['page'];
            }
            if (isset($_GET['order_by'])) {
                $orderBy = $_GET['order_by'];
            }
            $pm = PersistenceManager::NewPersistenceManager();
            $query = $pm->getQueryBuilder('AccountUser');
            $sql = "select a.name as account,u.id,u.username,u.status,u.creation_date,u.last_changed  from " . Account::GetDSN() . " as a inner join ".AccountUser::GetDSN()." as au inner join " . User::GetDSN() . " as u on a.no = au.account and au.user = u.id order by " . $orderBy;
            $csql = 'select count(*) from ' . AccountUser::GetDSN();
            $row = $query->execute($csql, $values)->fetch();
            $total = $row[0];
            $pagination = new DataPagination();
            $pagination->setIndex($index);
            $pagination->setSize($size);
            $pagination->setTotal($total);
            $pagination->setUrl('/backend/account-settings/users?');
            $pagination->setPages();
            $_GET['users'] = $query->executeQuery($sql, $values, (($index - 1) * $size), $size);
            $pagination->setPageCount($_GET['users']->count());
            $_GET['pagination'] = $pagination;
        } catch (Exception $e) {
            echo $e;
            ExceptionManager::RaiseException($e);
        }
    }

    public static function DoNew() {
        try {
            $_GET['account-user'] = new AccountUser();
            $_GET['user'] = new User();
        } catch (Exception $e) {
            ExceptionManager::RaiseException($e);
        }
    }

    public static function DoInsert() {
        $pm = null;
        try {
            $assertProperties = new AssertProperties();
            $assertProperties->addProperty($_POST, 'account');
            $assertProperties->addProperty($_POST, 'username');
            $assertProperties->addProperty($_POST, 'password');
            $assertProperties->assert();

            $accountUser = new AccountUser();

            $user = new User();

            $user->setValue('username', $_POST['username']);
            $user->setValue('password', $_POST['password']);
            $user->setValue('status', 1);

            $accountUser->setValue('account', $_POST['account']);
            $accountUser->setValue('user', $user->getValue("id"));

            $accountUser->CheckValidationRules($pm);
            $user->CheckValidationRules($pm);

            $_GET['account-user'] = $accountUser;
            $_GET['user'] = $user;

            if (count($accountUser->ValidationErrors) == 0 && count($user->ValidationErrors) == 0) {
                $pm = PersistenceManager::getConnection();
                $user->setValue("password", md5('iwebportal'.$user->getValue('password').$user->getValue('id')));
                $pm->save($user);
                $pm->save($accountUser);
                $_GET['status'] = "User Created";
                $_GET['account-user'] = new AccountUser();
                $_GET['user'] = new User();
            } else {
                $_GET['msg'] = 'Validation Failed';
            }
        } catch (Exception $e) {
            if (strpos($e, '1062 Duplicate entry') != false) {
                $_GET['msg'] = "User already exists";
            } else {
                ExceptionManager::RaiseException($e);
            }
        }
    }

    public static function DoUpdate($id) {
        try {

            $pm = PersistenceManager::getConnection();

            $_GET['account-user'] = new AccountUser();
            $_GET['user'] = new User();

            $accountUser = $pm->getObjectByColumn('AccountUser', 'user', $id);
            $user = $pm->getObjectById('User', $id);

            if ($accountUser != null && $user != null) {
                if (isset($_POST['username'])) {
                    $user->setValue('username', $_POST['username']);
                    $user->setValue('password2', $_POST['password2']);
                    $user->setValue('status', $_POST['status']);

                    $accountUser->setValue('account', $_POST['account']);
                    $accountUser->CheckValidationRules($pm);
                    $user->CheckValidationRules($pm);
                    $_GET['account-user'] = $accountUser;
                    $_GET['user'] = $user;

                    if (count($accountUser->ValidationErrors) == 0 && count($user->ValidationErrors) == 0) {
                        if(strcasecmp($user->getValue('password2'), "iwebportal") != 0){
                            $user->setValue("password", md5('iwebportal'.$user->getValue('password2').$user->getValue('id')));
                            $user->setValue('password2', 'iwebportal');
                        }
                        $pm->save($user);
                        $pm->save($accountUser);
                        $_GET['status'] = "User Updated";
                    } else {
                        $_GET['msg'] = 'Validation Failed';
                    }
                }
                $_GET['account-user'] = $accountUser;
                $_GET['user'] = $user;
            } else {
                $_GET['msg'] = 'User not found';
            }
        } catch (Exception $e) {
            if (strpos($e, '1062 Duplicate entry') != false) {
                $_GET['msg'] = "User already exists";
            } else {
                ExceptionManager::RaiseException($e);
            }
        }
    }

    public static function GetAccountUserById($id) {
        try {

            $pm = PersistenceManager::getConnection();

            $accountUser = $pm->getObjectByColumn('AccountUser', 'user', $id);
            $user = $pm->getObjectById('User', $id);
            if ($accountUser != null && $user != null) {
                $user->setValue('password', 'iwebportal');
                $_GET['account-user'] = $accountUser;
                $_GET['user'] = $user;
            } else {
                $_GET['msg'] = 'User not found';
            }
        } catch (Exception $e) {
            ExceptionManager::RaiseException($e);
        }
    }

    public static function DoDelete($id) {
        try {

            $pm = PersistenceManager::getConnection();

            $user = $pm->getObjectById('User', $id);
            if ($user != null) {
                $pm->deleteObjectById('User', $id);
                header('location: ' . CONTEXT_PATH . '/backend/account-settings/users');
            } else {
                $_GET['msg'] = 'User not found';
            }
        } catch (Exception $e) {
            ExceptionManager::RaiseException($e);
        }
    }

}
