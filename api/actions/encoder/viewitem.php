<?php
require_once('../../config/http_header.php');
require_once('../../class/session_manager.php');
require_once('../../config/db_config.php');



if($_SERVER['REQUEST_METHOD'] == 'GET'){

	$key= htmlspecialchars(strip_tags($_GET['key']));

	$database  = new Database();
	$db_con = $database->getDatabaseConnection();
	$items = new ViewItem($db_con);

	$items->itemID = $key;
	
	if($items->getData()){
		http_response_code(200);
		echo json_encode($items->queryResult);
		exit();
	}else{
		http_response_code(500);
		echo json_encode(array("Message"=> "Error"));
		exit();
	}
	
}else{
	http_response_code(405);
	echo json_encode(array('Message'=>"Not Allowed"));
	exit();
}


class ViewItem{
	public $db_conn;
	public $db;
	public $itemID;
	public $itemCommon;
	public $itemGeneric;
	public $itemManu;
	public $itemDesc;
	public $itemForm;
	public $itemExp;
	public $itemReceive;
	public $itemRemarks;
	public $itemAuthor;
	public $queryResult;

	public function __construct($db){
		$this->db_conn = $db;

	}


	public function getData(){
		$this->itemID = $this->itemID.'%';

		// $sql = "SELECT DISTINCT item.ItemID, item.CommonName,item.GenericName,item.Expiration,item.Manufacturer,item.form,(SELECT SUM(quantity.quantity) from quantity WHERE quantity.ItemID=item.ItemID) - (SELECT SUM(deduct.DeductQuantity) from deduct where deduct.ItemID = item.ItemID) as remaining from item,quantity,deduct WHERE item.ItemID LIKE :key1 OR item.CommonName LIKE :key2 OR item.GenericName LIKE :key3 OR item.Manufacturer";

		$sql = "SELECT DISTINCT item.ItemID, item.CommonName,item.GenericName,item.Expiration,item.Manufacturer,item.form,(SELECT transaction.balance from transaction WHERE transaction.ItemID = item.ItemID order by entrydate  desc LIMIT 1) as remaining from item,transaction WHERE item.ItemID LIKE :key1 OR item.CommonName LIKE :key2 OR item.GenericName LIKE :key3 OR item.Manufacturer";
	
		try{
			$item = $this->db_conn->prepare($sql);
		$item->bindParam(":key1", $this->itemID,PDO::PARAM_STR);
		$item->bindParam(":key2", $this->itemID,PDO::PARAM_STR);
		$item->bindParam(":key3", $this->itemID,PDO::PARAM_STR);
		if($item->execute()){
			$row = $item->fetchAll(PDO::FETCH_ASSOC);
			if(count($row) >0 ){
				$this->queryResult = $row;
				return true;
			}
		}
	}catch(PDOException $err){
		echo $err;
		return false;
	}

	}


}
