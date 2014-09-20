<?php

class UserActivityService {

    public static function GetLastLoginActivity() {
        try {
            $pm = PersistenceManager::getConnection();
            
            $query = $pm->getQueryBuilder('UserActivity');
            $sql = "select * from ".UserActivity::GetDSN()." where user_id = ? order by creation_date desc";
            $userActivities = $query->executeQuery($sql, array(UserService::GetIPrincipal()), 0, 1);
            $_GET['user-activity'] = $userActivities[0];
        } catch (Exception $e) {
            ExceptionManager::RaiseException($e);
        }
    }

}
