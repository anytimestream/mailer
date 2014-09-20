<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
*/

class PersistenceManager {

    private $db;
    private static $instance;

    private function  __construct() {
        try {
            $this->db = new PDO('mysql:host='.DB_HOST.';', self::getDSN_UserName().DB_USER, DB_PASSWORD);
            $this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        }
        catch(Exception $e) {
            echo $e;
            //convertErrorToException($e, null);
        }
    }

    public function getDB(){
        return $this->db;
    }

    public static function getDSN_UserName(){
        return DB_USERNAME;
    }

    public static function getConnection() {
        if(self::$instance == null){
            self::$instance = new PersistenceManager();
        }
        return self::$instance;
    }

    public static function NewPersistenceManager() {
        if(self::$instance == null){
            self::$instance = new PersistenceManager();
        }
        return self::$instance;
    }

    public function beginTransaction(){
        $this->db->beginTransaction();
    }

    public function commit(){
        $this->db->commit();
    }

    public function rollBack(){
        $this->db->rollBack();
    }

    public function getQueryBuilder($classname){
        return new QueryBuilder($this->db, $classname);
    }

    public function getObjectById($classname, $value) {
        $object = new $classname;
        $sql = 'select * from '.$object->getTableName().' where '.$object->getPrimaryKey()->getName().' = ?';
        $st = $this->db->prepare($sql);
        $st->execute(array($value));
        if(($row = $st->fetch())){
            $object->get($row);
            return $object;
        }
        else{
            return null;
        }
    }
    
    public function getObjectByColumn($classname, $column, $value) {
        $object = new $classname;
        $sql = 'select * from '.$object->getTableName().' where '.$column.' = ?';
        $st = $this->db->prepare($sql);
        $st->execute(array($value));
        if(($row = $st->fetch())){
            $object->get($row);
            return $object;
        }
        else{
            return null;
        }
    }

    public function save($object) {
        $object->CheckValidationRules($this);
        if($object->IsNew) {
            $this->insert($object);
        }
        else if($object->IsDirty){
            $this->update($object);
        }
    }

    private function insert($object) {
        $fields = '';
        $values = '';
        $properties = $object->getProperties();
        $array = array();
        foreach($properties as $property) {
            if($property->IsSavable) {
                $fields .= $property->getName().',';
                $values .= ':'.$property->getName().',';
                $array[':'.$property->getName()] = $property->getValue();
            }
        }
        
        $fields = substr($fields, 0, strlen($fields) - 1);
        $values = substr($values, 0, strlen($values) - 1);

        $sql = 'insert into '.$object->getTableName().' ('.$fields.') values('.$values.')';
        $st = $this->db->prepare($sql);
        
        $st->execute($array);
        $this->getTimestamp($object);
        $object->markOld();
    }

    private function update($object) {
        $sql = 'update '.$object->getTableName().' set ';
        $properties = $object->getProperties();
        foreach($properties as $property) {
            if($property->IsSavable && $property->IndexType != IndexType::PrimaryKey) {
                $sql .= $property->getName().' = :'.$property->getName().',';
            }
        }

        $array = array();
        $sql = substr($sql, 0, strlen($sql) - 1);

        $sql .= ' where '.$object->getPrimarykey()->getName().' = :'.$object->getPrimarykey()->getName();
        $sql .= ' and '.$object->getTimestamp()->getName().' = :'.$object->getTimestamp()->getName();

        $st = $this->db->prepare($sql);
        foreach($properties as $property) {
            if($property->IsSavable || $property->IndexType == IndexType::Timestamp || $property->IndexType == IndexType::PrimaryKey){
                $array[':'.$property->getName()] =  $property->getValue();
            }
        }
        $st->execute($array);
        $this->getTimestamp($object);
        $object->markOld();
    }

    public function delete($object) {
        $sql = 'delete from '.$object->getTableName();
        $sql .= ' where '.$object->getPrimarykey()->getName().' = :'.$object->getPrimarykey()->getName();
        $st = $this->db->prepare($sql);
        $st->execute(array(':'.$object->getPrimaryKey()->getName() => $object->getPrimaryKey()->getValue()));
    }

    public function deleteByObjectId($classname, $id) {
        $object = new $classname;
        $sql = 'delete from '.$object->getTableName();
        $sql .= ' where '.$object->getPrimarykey()->getName().' = :'.$object->getPrimarykey()->getName();
        $st = $this->db->prepare($sql);
        $st->execute(array(':'.$object->getPrimaryKey()->getName() => $id));
    }
    
    public function deleteObjectById($classname, $id) {
        $object = new $classname;
        $sql = 'delete from '.$object->getTableName();
        $sql .= ' where '.$object->getPrimarykey()->getName().' = :'.$object->getPrimarykey()->getName();
        $st = $this->db->prepare($sql);
        $st->execute(array(':'.$object->getPrimaryKey()->getName() => $id));
    }

    private function getTimestamp($object){
        $sql = 'select '.$object->getTimestamp()->getName().' from '.$object->getTableName();
        $sql .= ' where '.$object->getPrimaryKey()->getName().' = :'.$object->getPrimaryKey()->getName();

        $st = $this->db->prepare($sql);
        $array = array();
        $array[':'.$object->getPrimaryKey()->getName()] = $object->getPrimaryKey()->getValue();
        $st->execute($array);
        $row = $st->fetch();

        $object->setValue($object->getTimestamp()->getName(), $row[$object->getTimestamp()->getName()]);
    }

    public function close(){
        
    }
}
?>
