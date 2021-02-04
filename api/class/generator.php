<?php

require_once('../../config/db_config.php');
class IDGenerator{

    private $db_con;
    private $database;
    private $currId;
    private $suffix;
    private $prefix;
    private $active;
    private $newIDValue;
    private $curItemID;
    private $newItemID;
    private $generateID;
    public $dataResult;
    public function __construct()
    {
        $this->database = new Database();
        $this->db_con = $this->database->getDatabaseConnection();
        $this->generateUniqueEmployeeID();
    }
    public function generateUniqueEmployeeID(){
        // {$this->suffix}
        $this->getCurrentID();
        $tempID = "{$this->prefix}{$this->currId}";
        $this->generateID = intval($tempID);
        return $this->generateID;
    }
    public function generateUniqueItemID(){
        // {$this->suffix}
        $this->getCurrentID();
        $tempID = "{$this->prefix}{$this->curItemID}";
        $this->generateID = intval($tempID);
        return $this->generateID;
    }


    public function getCurrentID(){
        $query = "SELECT * FROM `generator` WHERE 1 LIMIT 1";
        $item = $this->db_con->prepare($query);
//         $item->bindParam(":old",$old,PDO::PARAM_INT);
        try{
            $item->execute();
            $row =$item->fetch();
            $this->currId = intval($row['currentID']);
            $this->suffix = intval($row['suffix']);
            $this->prefix = intval($row['prefix']);
            $this->active = intval($row['active']);
            $this->curItemID = intval($row['curItem']);
            $this->newIDValue = $this->currId+1;
            $this->newItemID = $this->curItemID+1;
            $this->updateUniqueID();
            $this->updateItemID();
        }catch(PDOException $err){
            echo "ID GENERATION FAILED";
        }
    }

    public function updateUniqueID(){
        $query = 'UPDATE `generator` SET `currentID`=:new,`active`="1" WHERE currentID = :cur';
        $item = $this->db_con->prepare($query);
        $item->bindParam(":new",$this->newIDValue,PDO::PARAM_INT);
        $item->bindParam(":cur",$this->currId,PDO::PARAM_INT);
        try{
            $item->execute();
        }catch(PDOException $err){

        }
    }


    public function updateItemID(){
        // $this->curItemID = $this->curItemID +1;
        $query = 'UPDATE `generator` SET `curItem`=:new,`active`="1" WHERE curItem= :cur';
        $item = $this->db_con->prepare($query);
        $item->bindParam(":new",$this->newItemID,PDO::PARAM_INT);
        $item->bindParam(":cur",$this->curItemID,PDO::PARAM_INT);
        try{
            $item->execute();
        }catch(PDOException $err){

        }
    }

}
