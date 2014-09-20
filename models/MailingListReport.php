<?php
class MailingListReport extends PersistableObject {

    function  __construct() {
        //Persistable Data Members
        $this->properties['id'] = new Property('id', uniqid(rand(), true), true, IndexType::PrimaryKey);
        $this->properties['mailing_list'] = new Property('mailing_list', '', true, IndexType::Normal);
        $this->properties['pending'] = new Property('pending', 0, true, IndexType::Normal);
        $this->properties['sent'] = new Property('sent', 0, true, IndexType::Normal);
        $this->properties['valid'] = new Property('valid', 0, true, IndexType::Normal);
        $this->properties['invalid'] = new Property('invalid', 0, true, IndexType::Normal);
        $this->properties['blacklist'] = new Property('blacklist', 0, true, IndexType::Normal);

        $this->properties['creation_date'] = new Property('creation_date', date('Y-m-d H:i:s'), true, IndexType::Normal);

        //Non-Persistable Data Members
        $this->properties['last_changed'] = new Property('last_changed', '',false, IndexType::Timestamp);
        
        //ValidationRules
    }

    public function getTableName() {
        return self::GetDSN();
    }

    public static function GetDSN(){
        return PersistenceManager::getDSN_UserName().'mailer_1_3.mailing_list_reports';
    }
}
