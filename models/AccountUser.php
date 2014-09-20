<?php
class AccountUser extends PersistableObject {

    function  __construct() {
        //Persistable Data Members
        $this->properties['id'] = new Property('id', uniqid(), true, IndexType::PrimaryKey);
        $this->properties['account'] = new Property('account', '', true, IndexType::Normal);
        $this->properties['user'] = new Property('user', '', true, IndexType::Normal);

        $this->properties['creation_date'] = new Property('creation_date', date('Y-m-d H:i:s'), true, IndexType::Normal);

        //Non-Persistable Data Members
        $this->properties['last_changed'] = new Property('last_changed','',false, IndexType::Timestamp);
        
        //ValidationRules
        $this->ValidationRules = new ValidationRules();
        $this->ValidationRules->add(new IllegalValueValidationRule('user', '-','Invalid'));
        $this->ValidationRules->add(new IllegalValueValidationRule('account', '-','Invalid'));
    }

    public function getTableName() {
        return self::GetDSN();
    }

    public static function GetDSN(){
        return PersistenceManager::getDSN_UserName().'mailer_1_3.account_users';
    }
}
