<?php
class MailingList extends PersistableObject {

    function  __construct() {
        //Persistable Data Members
        $this->properties['id'] = new Property('id', uniqid(rand(), true), true, IndexType::PrimaryKey);
        $this->properties['account'] = new Property('account', '', true, IndexType::Normal);
        $this->properties['body'] = new Property('body', '', true, IndexType::Normal);
        $this->properties['attachments'] = new Property('attachments', '', true, IndexType::Normal);
        $this->properties['subject'] = new Property('subject', '', true, IndexType::Normal);
        $this->properties['sender'] = new Property('sender', '', true, IndexType::Normal);
        $this->properties['name'] = new Property('name', '', true, IndexType::Normal);
        $this->properties['logged'] = new Property('logged', 0, true, IndexType::Normal);

        $this->properties['creation_date'] = new Property('creation_date', date('Y-m-d H:i:s'), true, IndexType::Normal);

        //Non-Persistable Data Members
        $this->properties['last_changed'] = new Property('last_changed','',false, IndexType::Timestamp);
        $this->properties['recipients'] = new Property('recipients','',false, IndexType::Normal);
        
        //ValidationRules
        $this->ValidationRules = new ValidationRules();
        $this->ValidationRules->add(new StringValidationRule('name', 2, 200, 'Must be between 2 and 200 characters'));
        $this->ValidationRules->add(new StringValidationRule('subject', 2, 200, 'Must be between 2 and 200 characters'));
        $this->ValidationRules->add(new EmailValidationRule("sender", false, "Invalid"));
        //$this->ValidationRules->add(new CommaDataValidationRule('recipients', 1, 1000, 'Must be between 1 and 1000 recipients'));
    }

    public function getTableName() {
        return self::GetDSN();
    }

    public static function GetDSN(){
        return PersistenceManager::getDSN_UserName().'mailer_1_3.mailing_list';
    }
}
