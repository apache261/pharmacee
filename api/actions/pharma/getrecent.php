<?php
require_once('../../config/http_header.php');
require_once('../../class/session_manager.php');
require_once('../../config/db_config.php');



if($_SERVER['REQUEST_METHOD'] == 'GET'){



	$database  = new Database();
	$db_con = $database->getDatabaseConnection();
	$items = new ViewItem($db_con);


	
	if($items->getData()){
		http_response_code(200);
		echo json_encode($items->queryResult);
	}
	
}else{
	http_response_code(500);
	echo json_encode(array('Message'=>"An Error Occured 405"));
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

		$sql = "SELECT DISTINCT item.ItemID, item.CommonName,item.GenericName,item.Expiration,item.Manufacturer,item.form,(SELECT transaction.balance from transaction WHERE transaction.ItemID = item.ItemID order by entrydate  desc LIMIT 1) as remaining, item.ReceiveDate from item,transaction ORDER BY date(item.ReceiveDate) DESC";
	
		try{
			$item = $this->db_conn->prepare($sql);
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
