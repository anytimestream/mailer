<?php

class User extends PersistableObject {

    function  __construct() {
        //Persistable Data Members
        $this->properties['id'] = new Property('id',uniqid(), true, IndexType::PrimaryKey);
        $this->properties['username'] = new Property('username', '', true, IndexType::Normal);
        $this->properties['password'] = new Property('password', '', true, IndexType::Normal);
        $this->properties['status'] = new Property('status', 0, true, IndexType::Normal);
        $this->properties['creation_date'] = new Property('creation_date', date('Y-m-d H:i:s'), true, IndexType::Normal);

        //Non-Persistable Data Members
        $this->properties['last_changed'] = new Property('last_changed','',false, IndexType::Timestamp);
        $this->properties['password2'] = new Property('password2', 'iwebportal', false, IndexType::Normal);
        
        //ValidationRules
        $this->ValidationRules = new ValidationRules();
        $this->ValidationRules->add(new StringValidationRule('password', 2, 40, 'Invalid'));
        $this->ValidationRules->add(new StringValidationRule('password2', 2, 40, 'Invalid'));
        $this->ValidationRules->add(new EmailValidationRule('username', false, 'Invalid'));
    }

    public function getTableName() {
        return self::GetDSN();
    }

    public static function GetDSN() {
        return PersistenceManager::getDSN_UserName().'mailer_1_3.users';
    }

}
