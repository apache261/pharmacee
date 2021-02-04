<?php
require_once('../../config/http_header.php');
require_once('../../class/session_manager.php');
require_once('../../config/db_config.php');



$validation_results = [];
if($_SERVER['REQUEST_METHOD'] == 'POST'){
	$database  = new Database();
	$db_con = $database->getDatabaseConnection();
	$item = new Deduct($db_con);


	$data = json_decode(file_get_contents("php://input"));

	$item->itemID = $data->deduct->itemid;
	$item->itemQuantityDeduction = $data->deduct->quantity;


	$isItemValid = !empty($item->itemID );
	$isItemQuantityNotEmpty= !empty($item->itemQuantityDeduction);
	$isItemQuantityNumber = is_numeric($item->itemQuantityDeduction);
	$isItemQuantityNotNegative = $isItemQuantityNumber?($item->itemQuantityDeduction > 0) && ($item->itemQuantityDeduction - floor($item->itemQuantityDeduction) == 0):false;


http_response_code(200);

$validation_results = array(
	"ItemID" => $isItemValid,
	"Quantity" => array(
			"Empty" => !$isItemQuantityNotEmpty,
			"Numeric" => $isItemQuantityNumber,
			"Negative" => !$isItemQuantityNotNegative
		)
);
	http_response_code(200);
	if(!($isItemQuantityNumber && $isItemQuantityNotNegative && $isItemQuantityNotEmpty)){
		echo json_encode(array('Message' => 'Invalid Quantity' , "Validation" => $validation_results, "Success" => 0));
		exit();
	}

	if($item->deductInventory()){
		echo json_encode(array('Message'=>"Success", "Validation" => $validation_results, "Success" => 1));
		exit();
	}else{
		echo json_encode(array('Message'=>"Not Enough Stock", "Validation" => $validation_results, "Success" => 0));
		exit();
	}

}else{
	echo json_encode(array('Message'=>"Method Not Allowed", "Success" => 0, "Validation" => $validation_results));

exit();
}


class Deduct{

	private $db_conn;
	public $itemID;
	public $itemQuantityDeduction;

	private $itemDeductReason = 2;
	private $itemCurrentOnHand;

	public function __construct($db){
		$this->db_conn = $db;
	}
	// public function deductInventory(){
	// 	$out = 1;
	// 	$sql = "INSERT INTO `deduct`( `ItemID`, `DeductQuantity`,`Reason`) VALUES (:id,:quantity,:reason)";
	// 	$item = $this->db_conn->prepare($sql);
	// 	try{
	// 		$item->bindParam(":id",$this->itemID,PDO::PARAM_INT);
	// 		$item->bindParam(":quantity",$this->itemQuantityDeduction,PDO::PARAM_INT);
	// 		$item->bindParam(":reason",$out,PDO::PARAM_INT);
	// 		if($item->execute()){
	// 			return true;
	// 		}

	// 	}catch(PDOException $err){
	// 		echo $err;
	// 		return false;
	// 	}

	// 	return false;
	// }

	public function deductInventory(){

		if($this->getTotal()){
			if($this->deduct_To_Transaction_Table()){
				return true;
			}
		}
		return false;
	}









private function deduct_To_Transaction_Table(){
	$currr = date('Y-m-d');
		if( $this->itemCurrentOnHand < $this->itemQuantityDeduction){
			return false;
		}
		$newBalance = $this->itemCurrentOnHand - $this->itemQuantityDeduction;
		$sql = "INSERT INTO `transaction`( `ItemID`, `reason`, `balance`, `QuantityOut`, `entrydate`) VALUES (:id,:reason,:balance,:quantityOut,:rDate)";

		$item = $this->db_conn->prepare($sql);
		try{
			$item->bindParam(":id",$this->itemID,PDO::PARAM_INT);
			$item->bindParam(":reason",$this->itemDeductReason,PDO::PARAM_INT);
			$item->bindParam("balance",$newBalance,PDO::PARAM_INT);
			$item->bindParam(":quantityOut",$this->itemQuantityDeduction,PDO::PARAM_INT);
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



}
