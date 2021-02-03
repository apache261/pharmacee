<?php
require_once('../../config/http_header.php');
require_once('../../class/session_manager.php');
require_once('../../config/db_config.php');




if($_SERVER['REQUEST_METHOD'] == 'GET'){
	$database  = new Database();
	$db_con = $database->getDatabaseConnection();
	$item = new Deduct($db_con);


	$data = json_decode(file_get_contents("php://input"));

	// $item->expiryStart= $data->date->start;
	$item->expiryStart= date("Y/m/d");

	if($item->scan_expired_items()){
		http_response_code(200);
		
	}

}else{
	http_response_code(500);
	echo json_encode(array('Message'=>"An Error Occured 500"));
}



class Deduct{

	private $db_conn;
	public $itemID;
	public $itemQuantityDeduction;
	public $expiryStart;
	private $itemDeductReason = 3;
	private $itemCurrentOnHand;
	private $checkItem = 1;
	private $uncheck = 0;
	private $expiryID;
	public $expiredItemList;
	public function __construct($db){
		$this->db_conn = $db;
	}



public function scan_expired_items(){
	$sql= "SELECT expiry_ID, itemID, quantity FROM item_expiry WHERE `expiration` <= :exp AND `hasCheck` = :uncheck";
	try{
		$item = $this->db_conn->prepare($sql);
		$item->bindParam(":exp",$this->expiryStart,PDO::PARAM_STR);
		$item->bindParam(":uncheck",$this->uncheck,PDO::PARAM_STR);
		if($item->execute()){
			$row = $item->fetchAll(PDO::FETCH_ASSOC); 
			if(count($row) > 0){
				foreach ($row as $expiredItem) {
					$this->itemID = 				$expiredItem['itemID'];
					$this->itemQuantityDeduction = 	$expiredItem['quantity'];
					$this->expiryID =				$expiredItem['expiry_ID'];
					$this->deductInventory();
					$this->updateExpiryStatus();
				}
			echo json_encode($row);
			exit();
			}
		echo json_encode(array('Message'=>"None"));
		}


	}catch(PDOException $err){
		echo $err;
		return false;
	}
	return false;
}



private function updateExpiryStatus(){
	$sql = "UPDATE item_expiry SET `hasCheck` = :chec WHERE expiry_ID = :id";

	try{
		$item = $this->db_conn->prepare($sql);
		$item->bindParam(":chec",$this->checkItem,PDO::PARAM_INT);
		$item->bindParam(":id",$this->expiryID,PDO::PARAM_INT);

	return $item->execute();
	}catch(PDOException $err){
		echo $err;
		return false;
	}
	return false;
}




	public function deductInventory(){

		if($this->getTotal()){
			if($this->deduct_To_Transaction_Table()){
				return true;
			}
		}
		return false;
	}

private function deduct_To_Transaction_Table(){
		$newBalance = $this->itemCurrentOnHand - $this->itemQuantityDeduction;
		$sql = "INSERT INTO `transaction`( `ItemID`, `reason`, `balance`, `QuantityOut`) VALUES (:id,:reason,:balance,:quantityOut)";

		$item = $this->db_conn->prepare($sql);
		try{
			$item->bindParam(":id",$this->itemID,PDO::PARAM_INT);
			$item->bindParam(":reason",$this->itemDeductReason,PDO::PARAM_INT);
			$item->bindParam("balance",$newBalance,PDO::PARAM_INT);
			$item->bindParam(":quantityOut",$this->itemQuantityDeduction,PDO::PARAM_INT);
			
			if($item->execute()){
				return true;
			}
		}catch(PDOException $err){
			echo $err;
			return false;
		}

		return false;
	}


//GET TOTAL RETURN 0 if not exist
	private function getTotal(){
		$sql = "SELECT IFNULL((sum(QuantityIn) - sum(QuantityOut)),0) as curbal from transaction WHERE ItemID = :id";

		$item = $this->db_conn->prepare($sql);
		try{
			$item->bindParam(":id",$this->itemID,PDO::PARAM_INT);
			if($item->execute()){
				$row = $item->fetch(PDO::FETCH_ASSOC);
				if(count($row) > 0 ){
					$this->itemCurrentOnHand = intval($row['curbal']);
					// echo json_encode($row);
					return true;
				}
			}
		}catch(PDOException $err){
			echo $err;
			return false;
		}

		return false;
	}



}
