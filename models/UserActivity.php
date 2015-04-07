<?php
class UserActivity extends PersistableObject {

    function  __construct() {
        //Persistable Data Members
        $this->properties['id'] = new Property('id', uniqid(), true, IndexType::PrimaryKey);
        $this->properties['activity'] = new Property('activity', '', true, IndexType::Normal);
        $this->properties['user_id'] = new Property('user_id', '', true, IndexType::Normal);
        $this->properties['page'] = new Property('page', '', true, IndexType::Normal);
        $this->properties['ip_address'] = new Property('ip_address', '', true, IndexType::Normal);
        $this->properties['referer'] = new Property('referer', '', true, IndexType::Normal);
        $this->properties['user_agent'] = new Property('user_agent', '', true, IndexType::Normal);
        $this->properties['creation_date'] = new Property('creation_date', date('Y-m-d H:i:s'), true, IndexType::Normal);

        //Non-Persistable Data Members
        $this->properties['last_changed'] = new Property('last_changed','',false, IndexType::Timestamp);

        //ValidationRules
    }

    public function getTableName() {
        return self::GetDSN();
    }

    public static function GetDSN() {
        return PersistenceManager::getDSN_UserName().'mailer_1_3.user_activities';
    }

}
