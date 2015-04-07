<?php

class UserService {

    public static function GetUsers() {
        try {
            $size = 20;
            $index = 1;
            $orderBy = "name";
            $values = array();
            $url_search = "";
            $_GET['criterias'] = array('username');
            if (isset($_GET['page'])) {
                $index = $_GET['page'];
            }
            if (isset($_GET['order_by'])) {
                $orderBy = $_GET['order_by'];
            }
            $pm = PersistenceManager::NewPersistenceManager();
            $query = $pm->getQueryBuilder('User');
            $sql = "select u.id,concat(up.firstname,' ', up.lastname) as name,u.username,u.status,u.creation_date,u.last_changed from " . User::GetDSN() . " as u inner join " . UserProfile::GetDSN() . " as up on u.id = up.user_id order by " . $orderBy;
            $csql = 'select count(*) from ' . User::GetDSN();
            if (isset($_GET['criteria']) && strcasecmp($_GET['criteria'], 'any') != 0) {
                $sql = "select u.id,concat(up.firstname,' ', up.lastname) as name,u.username,u.status,u.creation_date,u.last_changed from " . User::GetDSN() . " as u inner join " . UserProfile::GetDSN() . " as up on u.id = up.user_id where u.username = ? order by " . $orderBy;
                $csql = 'select count(*) from ' . User::GetDSN() . " where username = ?";
                $url_search = 'criteria=username&value=' . $_GET['value'];
                $values[] = urldecode($_GET['value']);
            }
            $row = $query->execute($csql, $values)->fetch();
            $total = $row[0];
            $pagination = new DataPagination();
            $pagination->setIndex($index);
            $pagination->setSize($size);
            $pagination->setTotal($total);
            if (strlen($url_search) > 0) {
                $pagination->setUrl('/backend/user-management/users?' . $url_search . '&');
            } else {
                $pagination->setUrl('/backend/user-management/users?');
            }
            $pagination->setPages();
            $_GET['users'] = $query->executeQuery($sql, $values, (($index - 1) * $size), $size);
            $pagination->setPageCount($_GET['users']->count());
            $_GET['pagination'] = $pagination;
        } catch (Exception $e) {
            ExceptionManager::RaiseException($e);
        }
    }

    public static function GetUser($id) {
        $pm = null;
        try {

            $pm = PersistenceManager::getConnection();

            $user = $pm->getObjectById('User', $id);

            if ($user != null) {
                $_GET['user'] = $user;
            } else {
                $_GET['msg'] = 'User not found';
            }
        } catch (Exception $e) {
            ExceptionManager::RaiseException($e);
        }
    }

    public static function ChangePassword() {
        try {
            $assertProperties = new AssertProperties();
            $assertProperties->addProperty($_POST, 'password');
            $assertProperties->addProperty($_POST, 'password2');
            $assertProperties->assert();

            $pm = PersistenceManager::getConnection();

            $user = $pm->getObjectById('User', UserService::GetIPrincipal());

            if (strcasecmp($_POST['password'], $_POST['password2']) != 0) {
                $_GET['msg'] = 'Password and Re-Password are not the same';
            } else if ($user == null) {
                $_GET['msg'] = 'User not found';
            } else if (strlen($_POST['password']) < 6 || strlen($_POST['password']) > 16) {
                $_GET['msg'] = 'Password must be between 6 and 16 characters';
            } else {
                $user->setValue('password', md5('iwebportal' . $_POST['password'] . $user->getValue('id')));
                $pm->save($user);
                return true;
            }
        } catch (Exception $e) {
            ExceptionManager::RaiseException($e);
        }
        return false;
    }

    public static function RequireLogin($url) {
        if (!self::IsAuthenticated()) {
            self::RedirectBack($url);
        }
    }

    private static function RedirectBack($url) {
        $redirect = 'http';
        if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on') {
            $redirect . 's';
        }
        $redirect .= '://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
        if (!empty($_SERVER['QUERY_STRING'])) {
            $redirect .= '?' . $_SERVER['QUERY_STRING'];
        }
        header('Location: ' . $url . '?return=' . $redirect);
    }

    public static function RequireRole($role, $url) {
        if (is_array($role)) {
            $allow = false;
            for ($i = 0; $i < count($role); $i++) {
                if (self::IsInRole($role[$i])) {
                    $allow = true;
                    break;
                }
            }
            if (!$allow) {
                self::RedirectBack($url);
            }
        } else if (!self::IsInRole($role)) {
            self::RedirectBack($url);
        }
    }

    public static function Login($userName, $password, $redirect) {
        try {
            if (self::IsAuthenticated()) {
                self::Logout();
            }
            $pm = PersistenceManager::getConnection();
            $query = $pm->getQueryBuilder('User');
            $sql = 'select id,password,status from ' . User::GetDSN() . ' where username = ? and status = ?';
            $values = array($userName, 1);
            $logins = $query->executeQuery($sql, $values, 0, 1);
            if ($logins->count() > 0 && $logins[0]->getValue('status') == 1 && strcmp($logins[0]->getValue('password'), md5('iwebportal' . $password . $logins[0]->getValue('id'))) == 0) {

                $userActivity = new UserActivity();
                $userActivity->setValue('user_id', $logins[0]->getValue('id'));
                $userActivity->setValue('activity', "Login");
                $userActivity->setValue('page', $_SERVER['REQUEST_URI']);
                $userActivity->setValue('ip_address', $_SERVER['REMOTE_ADDR']);
                $userActivity->setValue('referer', $_SERVER["HTTP_REFERER"]);
                $userActivity->setValue('user_agent', $_SERVER['HTTP_USER_AGENT']);

                $pm->save($userActivity);

                if (strcasecmp($logins[0]->getValue('id'), "1") == 0) {
                    session_regenerate_id();
                    $_SESSION['IPrincipal'] = $logins[0]->getValue('id');
                    self::Redirect($redirect);
                    return true;
                } else {
                    $accountUser = $pm->getObjectByColumn("AccountUser", "user", $logins[0]->getValue('id'));
                    session_regenerate_id();
                    $_SESSION['IPrincipal'] = $logins[0]->getValue('id');
                    $_SESSION['IPrincipal2'] = $accountUser->getValue('account');
                    self::Redirect($redirect);
                    return true;
                }
            }
        } catch (Exception $e) {
            ExceptionManager::RaiseException($e);
        }
    }

    public static function Redirect($redirect) {
        $str = '';
        if (!empty($_SERVER['QUERY_STRING'])) {
            $str .= '?' . $_SERVER['QUERY_STRING'];
        }
        $pos = strpos($str, 'return=');
        if ($pos) {
            header('Location: ' . substr($str, $pos + 7));
        } else {
            header('Location: ' . $redirect);
        }
    }

    public static function IsInRole($role) {
        if (isset($_SESSION['UserRoles'])) {
            for ($i = 0; $i < $_SESSION['UserRoles']->count(); $i++) {
                if ($_SESSION['UserRoles'][$i]->getValue('role') == $role) {
                    return true;
                }
            }
        }
        return false;
    }

    public static function IsAdmin() {
        if (isset($_SESSION['IPrincipal']) && $_SESSION['IPrincipal'] == '1') {
            return true;
        }
        return false;
    }

    public static function Logout() {
        try {
            if (isset($_SESSION['IPrincipal'])) {
                unset($_SESSION['IPrincipal']);
                unset($_SESSION['UserRoles']);
            }
        } catch (Exception $e) {
            ExceptionManager::RaiseException($e);
        }
    }

    public static function IsAuthenticated() {
        try {
            if (isset($_SESSION['IPrincipal'])) {
                return true;
            }
            return false;
        } catch (Exception $e) {
            ExceptionManager::RaiseException($e);
        }
    }

    public static function GetIPrincipal() {
        try {
            if (isset($_SESSION['IPrincipal'])) {
                return $_SESSION['IPrincipal'];
            }
        } catch (Exception $e) {
            ExceptionManager::RaiseException($e);
        }
    }

    public static function GetIPrincipal2() {
        try {
            if (isset($_SESSION['IPrincipal2'])) {
                return $_SESSION['IPrincipal2'];
            }
        } catch (Exception $e) {
            ExceptionManager::RaiseException($e);
        }
    }

}
