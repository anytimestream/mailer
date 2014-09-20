<?php
class Subscriber extends PersistableObject {

    function  __construct() {
        //Persistable Data Members
        $this->properties['id'] = new Property('id', uniqid(rand(), true), true, IndexType::PrimaryKey);
        $this->properties['account'] = new Property('account', '', true, IndexType::Normal);
        $this->properties['mailing_list'] = new Property('mailing_list', '', true, IndexType::Normal);
        $this->properties['email'] = new Property('email', '', true, IndexType::Normal);
        $this->properties['sent'] = new Property('sent', 0, true, IndexType::Normal);
        $this->properties['backlist'] = new Property('backlist', 0, true, IndexType::Normal);
        $this->properties['verified'] = new Property('verified', 0, true, IndexType::Normal);

        $this->properties['creation_date'] = new Property('creation_date', date('Y-m-d H:i:s'), true, IndexType::Normal);

        //Non-Persistable Data Members
        $this->properties['last_changed'] = new Property('last_changed','',false, IndexType::Timestamp);
        
        //ValidationRules
        $this->ValidationRules = new ValidationRules();
        $this->ValidationRules->add(new EmailValidationRule('email', false, 'Invalid'));
    }

    public function getTableName() {
        return self::GetDSN();
    }

    public static function GetDSN(){
        return PersistenceManager::getDSN_UserName().'mailer_1_3.subscribers';
    }
}
