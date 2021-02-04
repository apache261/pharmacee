<?php
require_once('../../config/http_header.php');
require_once('../../class/session_manager.php');
require_once('../../config/db_config.php');



$validation_results = [];
function validateDate($date, $format = 'Y-m-d'){
	$d = DateTime::createFromFormat($format, $date);
	return $d && $d->format($format) === $date;
}


if($_SERVER['REQUEST_METHOD'] == 'POST'){
	$database  = new Database();
	$db_con = $database->getDatabaseConnection();
	$update = new UpdateStock($db_con);

	$data = json_decode(file_get_contents("php://input"));
	$update->itemID = $data->update->itemid;
	$update->itemQuantity = $data->update->quantity;
	$update->itemExpiry = $data->update->expiry;


	$isItemValid = !empty($data->update->itemid);
	$isItemQuantityNotEmpty= !empty($data->update->quantity);
	$isItemQuantityNumber = is_numeric($data->update->quantity);
	$isItemQuantityNotNegative = $isItemQuantityNumber?($data->update->quantity > 0):false;


	//expiry
	if(strlen($update->itemExpiry) > 10){
		$update->itemExpiry = substr($update->itemExpiry, 0, 10);
	}
	$isItemExpValid = !empty($update->itemExpiry) && validateDate($update->itemExpiry);



	$validation_results = array(
		"ItemID" => $isItemValid,
		"Quantity" => array(
			"Empty" => !$isItemQuantityNotEmpty,
			"Numeric" => $isItemQuantityNumber,
			"Negative" => !$isItemQuantityNotNegative
		),
		"Expiration" => $isItemExpValid
	);
	http_response_code(200);
	if(!($isItemQuantityNumber && $isItemQuantityNotNegative && $isItemQuantityNotEmpty)){
		echo json_encode(array('Message' => 'Invalid Quantity' , "Validation" => $validation_results, "Success" => 0));
		exit();
	}
	if(!$isItemExpValid){
		echo json_encode(array('Message' => 'Invalid Expiration' , "Validation" => $validation_results, "Success" => 0));
		exit();
	}

	if($update->InsertQuantity()){
		echo json_encode(array('Message'=>"Success", "Validation" => $validation_results, "Success" => 1));
		exit();
	}
}else{

	echo json_encode(array('Message'=>"Method Not Allowed" , "Validation" => $validation_results, "Success" => 0));
	exit();
}


class UpdateStock{
	private $db_conn;
	public $itemID;
	public $itemQuantity;
	public $itemExpiry;

	private $itemInsertReason = 1;
	private $itemCurrentOnHand;


	public function __construct($db){
		$this->db_conn = $db;
	}



	// public function InsertQuantity(){
	// 	$query = "INSERT INTO `quantity`( `ItemID`, `quantity`) VALUES (:id,:qty)";
	// 	$item = $this->db_conn->prepare($query);
	// 	try{

	// 		$item->bindParam(":id",$this->itemID,PDO::PARAM_INT);
	// 		$item->bindParam(":qty",$this->itemQuantity,PDO::PARAM_INT);


	// 		if($item->execute()){
	// 			return true;
	// 		}
	// 	}catch(PDOException $err){
	// 		echo $err;
	// 		return false;
	// 	}
	// 	return false;

	// }

	public function InsertQuantity(){
		if($this->getTotal()){
			if($this->add_To_Transaction_Table()){
				if($this->insertIntoExpiry()){
					return true;
			}
		}
	}
	return false;
}

private function add_To_Transaction_Table(){
	$currr = date('Y-m-d');
	$newBalance = $this->itemCurrentOnHand + $this->itemQuantity;
	$sql = "INSERT INTO `transaction`( `ItemID`, `reason`, `balance`, `QuantityIn`, `entrydate`) VALUES (:id,:reason,:balance,:quantityIn,:rDate)";

	$item = $this->db_conn->prepare($sql);
	try{
		$item->bindParam(":id",$this->itemID,PDO::PARAM_INT);
		$item->bindParam(":reason",$this->itemInsertReason,PDO::PARAM_INT);
		$item->bindParam("balance",$newBalance,PDO::PARAM_INT);
		$item->bindParam(":quantityIn",$this->itemQuantity,PDO::PARAM_INT);
		$item->bindParam(":rDate",$currr,PDO::PARAM_STR);
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

// Insert into expiry table
private function insertIntoExpiry(){
	$currr = date('Y-m-d');
	$sql = "INSERT INTO `item_expiry` (`ItemID`, `expiration`, `quantity`, `receivedDate`) VALUES (:itemid, :expiration, :quantity,:rDate)";
	$item = $this->db_conn->prepare($sql);
	try{
		$item->bindParam(":itemid",$this->itemID,PDO::PARAM_INT);
		$item->bindParam(":expiration",$this->itemExpiry,PDO::PARAM_STR);
		$item->bindParam(":quantity",$this->itemQuantity,PDO::PARAM_INT);
		$item->bindParam(":rDate",$currr,PDO::PARAM_STR);
		return $item->execute();
	}catch(PDOException $err){
		echo $err;
		return false;
	}
	return false;
}













}