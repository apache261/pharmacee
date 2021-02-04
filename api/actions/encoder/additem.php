<?php
require_once('../../config/http_header.php');
require_once('../../class/session_manager.php');
require_once('../../config/db_config.php');
require_once('../../class/generator.php');


$validation_results = [];
function validateDate($date, $format = 'Y-m-d'){
		$d = DateTime::createFromFormat($format, $date);
		return $d && $d->format($format) === $date;
	}

if($_SERVER['REQUEST_METHOD'] == 'POST'){
	$database  = new Database();

	$db_con = $database->getDatabaseConnection();
	$items = new AddItem($db_con);
	$generator = new IDGenerator();
	$uniqueItemID = $generator->generateUniqueItemID();
	$data = json_decode(file_get_contents("php://input"));
	$items->itemID = empty($data->uid)?$uniqueItemID:$data->uid;
	$items->itemCommon = $data->common;
	$items->itemGeneric = $data->generic;
	$items->itemManu = $data->manufacturer;
	$items->itemDesc = $data->description;
	$items->itemForm = $data->mform;
	$items->itemExp = $data->expiry;
	// $items->itemReceive = $data->receive;
	$items->itemRemarks = $data->remarks;
	$items->itemAuthor = $data->author;
	$items->itemQuantity = $data->quantity;




	$isItemValid = !empty($items->itemID);
	$isItemCommonNameValid  = !empty($items->itemCommon);
	$isItemGenericNameValid = !empty($items->itemGeneric);
	$isItemManuValid = !empty($items->itemManu);
	$isItemDescValid = !empty($items->itemDesc);
	$isItemFormValid = !empty($items->itemForm);

	if(strlen($items->itemExp) > 10){
		$items->itemExp = substr($items->itemExp, 0, 10);
	}
	$isItemExpValid = !empty($items->itemExp) && validateDate($items->itemExp);
	$isItemRemarkValid = !empty($items->itemRemarks);
	$isItemAuthorValid = !empty($items->itemAuthor);

	$isItemQuantityNotEmpty= !empty($items->itemQuantity);
	$isItemQuantityNumber = is_numeric($items->itemQuantity);
	$isItemQuantityNotNegative = $isItemQuantityNumber?($items->itemQuantity > 1):false;



	$validation_results = array(
		"ItemID" => $isItemValid,
		"CommonName" => $isItemCommonNameValid,
		"GenericName" => $isItemGenericNameValid,
		"Manufacturer" => $isItemManuValid,
		"Description" => $isItemDescValid,
		"Expiration" => $isItemExpValid,
		"Remarks" => $isItemRemarkValid,
		"Author" => $isItemAuthorValid,
		"Quantity" => array(
			"Empty" => !$isItemQuantityNotEmpty,
			"Numeric" => $isItemQuantityNumber,
			"Negative" => !$isItemQuantityNotNegative
		)
	);


	http_response_code(200);

//Validate Common Name
	if(!($isItemCommonNameValid)){
		echo json_encode(array('Message' => 'Invalid CommonName' , "Validation" => $validation_results, "Success" => 0));
		exit();
	}
//Validate Generic Name
	if(!($isItemGenericNameValid)){
		echo json_encode(array('Message' => 'Invalid Generic Name' , "Validation" => $validation_results, "Success" => 0));
		exit();
	}
	//Validate Manufacturer
	if(!($isItemManuValid)){
		echo json_encode(array('Message' => 'Invalid Manufacturer' , "Validation" => $validation_results, "Success" => 0));
		exit();
	}
//Validate Form
	if(!($isItemFormValid)){
		echo json_encode(array('Message' => 'Invalid Form' , "Validation" => $validation_results, "Success" => 0));
		exit();
	}
//Validate Form
	if(!($isItemExpValid)){
		echo json_encode(array('Message' => 'Set Expiration' , "Validation" => $validation_results, "Success" => 0));
		exit();
	}
//Validate Quantity -- negative, empty , non numeric
	if(!($isItemQuantityNumber && $isItemQuantityNotNegative && $isItemQuantityNotEmpty)){
		echo json_encode(array('Message' => 'Invalid Quantity' , "Validation" => $validation_results, "Success" => 0));
		exit();
	}

	if($items->addItem()){
		echo json_encode(array('Message' => 'Added' , "Validation" => $validation_results, "Success" => 1));
		exit();
	}
	
}else{
	echo json_encode(array('Message' => 'Method Not Allowed' , "Validation" => $validation_results, "Success" => 0));
	exit();
}



class AddItem{
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
	public $itemquantity;
	public $itemQuantity;
	public $itemQuantityDeduction = 0;
	public $currr;

	private $itemInsertReason = 1;
	private $itemCurrentOnHand;

	public function __construct($db){
		$this->db_conn = $db;
		$this->currr = date('Y-m-d');
	}


	public function addItem(){
		$query = "INSERT INTO `item`(`ItemID`, `CommonName`, `GenericName`, `Manufacturer`, `Description`, `Form`, `Expiration`, `Remarks`, `EmployeeID`, `ReceiveDate`) VALUES (:uid,:common,:generic,:manufac,:desr,:form,:exp,:remarks,:author, :rDate)";
		$item = $this->db_conn->prepare($query);
		try{
			
			$item->bindParam(":uid",$this->itemID,PDO::PARAM_INT);
			$item->bindParam(":common",$this->itemCommon,PDO::PARAM_STR);
			$item->bindParam(":generic",$this->itemGeneric,PDO::PARAM_STR);
			$item->bindParam(":manufac",$this->itemManu,PDO::PARAM_STR);
			$item->bindParam(":desr",$this->itemDesc,PDO::PARAM_STR);
			$item->bindParam(":form",$this->itemForm,PDO::PARAM_INT);
			$item->bindParam(":exp",$this->itemExp,PDO::PARAM_STR);
			
			$item->bindParam(":remarks",$this->itemRemarks,PDO::PARAM_STR);
			$item->bindParam(":author",$this->itemAuthor,PDO::PARAM_INT);
			$item->bindParam(":rDate",$this->currr,PDO::PARAM_STR);
			if($item->execute()){
				if($this->getTotal()){
					if($this->add_To_Transaction_Table()){
						return true;
					}
				}
			}
		}catch(PDOException $err){
			echo $err;
			return false;
		}
		return false;

	}
	

//Reason 1 means Stocks IN
	private function add_To_Transaction_Table(){
		$newBalance = $this->itemCurrentOnHand + $this->itemQuantity;
		$sql = "INSERT INTO `transaction`( `ItemID`, `reason`, `balance`, `QuantityIn`) VALUES (:id,:reason,:balance,:quantityIn)";

		$item = $this->db_conn->prepare($sql);
		try{
			$item->bindParam(":id",$this->itemID,PDO::PARAM_INT);
			$item->bindParam(":reason",$this->itemInsertReason,PDO::PARAM_INT);
			$item->bindParam("balance",$newBalance,PDO::PARAM_INT);
			$item->bindParam(":quantityIn",$this->itemQuantity,PDO::PARAM_INT);
			if($item->execute()){
				$this->add_to_item_expiry();
				return true;
			}
		}catch(PDOException $err){
			echo $err;
			return false;
		}

		return false;
	}
//Add item to expiry table
//to monitor expired medecine
	private function add_to_item_expiry(){
	
		$sql = "INSERT INTO `item_expiry`(`ItemID`, `expiration`, `quantity`, `receivedDate`) VALUES (:id,:exp,:quan,:rDate)";
		try{
			$item = $this->db_conn->prepare($sql);
			$item->bindParam(":id",$this->itemID,PDO::PARAM_INT);
			$item->bindParam(":exp",$this->itemExp,PDO::PARAM_STR);
			$item->bindParam(":quan",$this->itemQuantity,PDO::PARAM_INT);
			$item->bindParam(":rDate",$this->currr,PDO::PARAM_STR);
			return $item->execute();
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
