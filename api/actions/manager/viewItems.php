<?php
require_once('../../config/http_header.php');
require_once('../../class/session_manager.php');
require_once('../../config/db_config.php');


if($_SERVER['REQUEST_METHOD'] == 'GET'){

$database  = new Database();
	$db_con = $database->getDatabaseConnection();
	$items = new Inventory($db_con);


		if($items->getInventory()){
			http_response_code(200);
			echo json_encode($items->queryResult);
			exit();
		}else{
			http_response_code(500);
			echo json_encode(array("Message"=> "Error"));
			exit();
		}	
}
http_response_code(500);
echo json_encode(array("Message"=> "Error"));
exit();



class Inventory{
	private $db_conn;
	public $queryResult;

	public function __construct($db){
		$this->db_conn = $db;
	}


// return itemID, Generic Name, Common Name, Manufacturer, Expiry, Available, TOtal Sales, Total In
	// public function getInventory(){
	// 	$this->srchKey = $this->srchKey.'%';
	// 	$sql = "SELECT DISTINCT
	// 	item.ItemID,
	// 	item.CommonName,
	// 	item.GenericName,
	// 	item.Expiration,
	// 	item.Manufacturer,
	// 	item.form,
	// 	(SELECT SUM(quantity.quantity) from quantity WHERE quantity.ItemID=item.ItemID) -
	// 	(SELECT SUM(deduct.DeductQuantity) from deduct where deduct.ItemID = item.ItemID)as remaining,
	// 	(SELECT SUM(quantity.quantity) from quantity WHERE quantity.ItemID=item.ItemID) as totalin,
	// 	(SELECT SUM(deduct.DeductQuantity) from deduct where deduct.ItemID = item.ItemID) as totalout
	// 	from item,quantity,deduct WHERE item.ItemID LIKE :key1 OR item.CommonName LIKE :key2 OR item.GenericName LIKE :key3 OR item.Manufacturer LIKE :key4";
	// 	try{
	// 		$item  = $this->db_conn->prepare($sql);
	// 		$item->bindParam(":key1", $this->srchKey,PDO::PARAM_STR);
	// 		$item->bindParam(":key2", $this->srchKey,PDO::PARAM_STR);
	// 		$item->bindParam(":key3", $this->srchKey,PDO::PARAM_STR);
	// 		$item->bindParam(":key4", $this->srchKey,PDO::PARAM_STR);
	// 		if($item->execute()){
				
	// 			$row = $item->fetchAll(PDO::FETCH_ASSOC);
	// 			if(count($row) > 0 ){

	// 				$this->queryResult = $row;
	// 				return true;
	// 			}
	// 		}
	// 	}catch(PDOException $err){
	// 		echo $err;
	// 		return false;
	// 	}
	// 	echo "we";
	// 	return false;
	// }


	public function getInventory(){


		$sql = "SELECT DISTINCT
		item.ItemID,
		item.CommonName,
		item.GenericName,
		item.Expiration,
		item.Manufacturer,
		item.form,
		item.receivedate,
       (SELECT IFNULL((sum(transaction.QuantityIn)),0) from transaction WHERE transaction.ItemID = item.ItemID) as totalin,
       (SELECT IFNULL((sum(transaction.QuantityOut)),0) from transaction WHERE transaction.ItemID = item.ItemID) as totalout,
       (SELECT transaction.balance from transaction WHERE transaction.ItemID = item.ItemID order by entrydate  desc LIMIT 1) as remaining
       from item,transaction order by item.receivedate desc LIMIT 15  ";
		try{
			$item  = $this->db_conn->prepare($sql);
			if($item->execute()){
				
				$row = $item->fetchAll(PDO::FETCH_ASSOC);
				if(count($row) > 0 ){

					$this->queryResult = $row;
					return true;
				}
			}
		}catch(PDOException $err){
			echo $err;
			return false;
		}
		echo "we";
		return false;
	}


}