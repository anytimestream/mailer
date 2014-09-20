<?php
class Account extends PersistableObject {

    function  __construct() {
        //Persistable Data Members
        $this->properties['id'] = new Property('id', uniqid(), true, IndexType::PrimaryKey);
        $this->properties['name'] = new Property('name', '', true, IndexType::Normal);
        $this->properties['retention'] = new Property('retention', '', true, IndexType::Normal);
        $this->properties['no'] = new Property('no', rand(100, 999).'-'.rand(100, 999).'-'.rand(100, 999), true, IndexType::Normal);

        $this->properties['creation_date'] = new Property('creation_date', date('Y-m-d H:i:s'), true, IndexType::Normal);

        //Non-Persistable Data Members
        $this->properties['last_changed'] = new Property('last_changed','',false, IndexType::Timestamp);
        
        //ValidationRules
        $this->ValidationRules = new ValidationRules();
        $this->ValidationRules->add(new IllegalValueValidationRule('retention', '-','Invalid'));
        $this->ValidationRules->add(new StringValidationRule('name', 2, 200, 'Invalid'));
    }

    public function getTableName() {
        return self::GetDSN();
    }

    public static function GetDSN(){
        return PersistenceManager::getDSN_UserName().'mailer_1_3.accounts';
    }
}
