<?php
class SMTPAccount extends PersistableObject {

    function  __construct() {
        //Persistable Data Members
        $this->properties['id'] = new Property('id', uniqid(), true, IndexType::PrimaryKey);
        $this->properties['provider'] = new Property('provider', '', true, IndexType::Normal);
        $this->properties['host'] = new Property('host', '', true, IndexType::Normal);
        $this->properties['account'] = new Property('account', '', true, IndexType::Normal);
        $this->properties['username'] = new Property('username', '', true, IndexType::Normal);
        $this->properties['password'] = new Property('password', '', true, IndexType::Normal);
        $this->properties['status'] = new Property('status', '', true, IndexType::Normal);

        $this->properties['creation_date'] = new Property('creation_date', date('Y-m-d H:i:s'), true, IndexType::Normal);

        //Non-Persistable Data Members
        $this->properties['last_changed'] = new Property('last_changed','',false, IndexType::Timestamp);
        
        //ValidationRules
        $this->ValidationRules = new ValidationRules();
        $this->ValidationRules->add(new IllegalValueValidationRule('provider', '-','Invalid'));
        $this->ValidationRules->add(new IllegalValueValidationRule('account', '-','Invalid'));
        $this->ValidationRules->add(new EmailValidationRule('username', false, 'Invalid'));
        $this->ValidationRules->add(new StringValidationRule('host', 3, 300, 'Must be between 3 and 300 characters'));
        $this->ValidationRules->add(new StringValidationRule('password', 2, 40, 'Must be between 2 and 40 characters'));
    }

    public function getTableName() {
        return self::GetDSN();
    }

    public static function GetDSN(){
        return PersistenceManager::getDSN_UserName().'mailer_1_3.smtp_accounts';
    }
}
