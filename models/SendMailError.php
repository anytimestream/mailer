<?php
class SendMailError extends PersistableObject {

    function  __construct() {
        //Persistable Data Members
        $this->properties['id'] = new Property('id', uniqid(), true, IndexType::PrimaryKey);
        $this->properties['type'] = new Property('type', '', true, IndexType::Normal);
        $this->properties['email'] = new Property('email', '', true, IndexType::Normal);
        $this->properties['error'] = new Property('error', "", true, IndexType::Normal);

        $this->properties['creation_date'] = new Property('creation_date', date('Y-m-d H:i:s'), true, IndexType::Normal);

        //Non-Persistable Data Members
        $this->properties['last_changed'] = new Property('last_changed','',false, IndexType::Timestamp);
        
        //ValidationRules
        $this->ValidationRules = new ValidationRules();
    }

    public function getTableName() {
        return self::GetDSN();
    }

    public static function GetDSN(){
        return PersistenceManager::getDSN_UserName().'mailer_1_3.send_mail_errors';
    }
}
