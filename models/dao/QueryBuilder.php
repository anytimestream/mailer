<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
class QueryBuilder {

    private $db;
    private $classname;

    public function  __construct($db, $classname) {
        $this->db = $db;
        $this->classname = $classname;
    }

    public function executeQuery($sql, $values, $index, $size){
        $st = $this->db->prepare($sql.' limit '.$index.','.$size);
        $st->execute($values);
        return new PersistableListObject($st, $this->classname);
    }

    public function execute($sql, $values){
        $st = $this->db->prepare($sql);
        $st->execute($values);
        return $st;
    }
}
?>
