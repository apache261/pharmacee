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
	$item = new Update($db_con);

	$data = json_decode(file_get_contents("php://input"));

	$item->itemID = $data->update->itemID;
	$item->itemCommonName = $data->update->common;
	$item->itemGenericName = $data->update->generic;
	$item->itemManufacturer = $data->update->manufacturer;
	$item->itemExpiration = $data->update->expiration;
	$item->itemForm = $data->update->form;

	$isItemValid = !empty($item->itemID);
	$isItemCommonNameValid  = !empty($item->itemCommonName);
	$isItemGenericNameValid = !empty($item->itemGenericName);
	$isItemManuValid = !empty($item->itemManufacturer);
	$isItemFormValid = !empty($item->itemForm);
	$isItemExpValid = !empty($item->itemExpiration) && validateDate($item->itemExpiration);

	$validation_results = array(
		"ItemID" => $isItemValid,
		"CommonName" => $isItemCommonNameValid,
		"GenericName" => $isItemGenericNameValid,
		"Manufacturer" => $isItemManuValid,
		"Expiration" => $isItemExpValid,
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
//Validate EXP
	if(!($isItemExpValid)){
		echo json_encode(array('Message' => 'Set Expiration' , "Validation" => $validation_results, "Success" => 0));
		exit();
	}


	if($item->updateItemInfo()){
		echo json_encode(array('Message'=>"Updated", "Success" => 1, "Validation" => $validation_results));
		exit();
	}
	
}else{
	http_response_code(200);
	echo json_encode(array('Message'=>"Invalid Method","Success" => 0, "Validation" => $validation_results));
}




class Update{
	private $db_conn;
	public $itemID;
	public $itemCommonName;
	public $itemGenericName;
	public $itemManufacturer;
	public $itemExpiration;
	public $itemForm;

	public function __construct($db){
		$this->db_conn = $db;
	}



	public function updateItemInfo(){
		$sql = "UPDATE `item` SET `CommonName`=:common,`GenericName`=:generic,`Manufacturer`=:manufacturer,`Form`=:form,`Expiration`=:expiration WHERE itemID = :id";
		$item = $this->db_conn->prepare($sql);

		try{
			$item->bindParam(":common",$this->itemCommonName,PDO::PARAM_STR);
			$item->bindParam(":generic",$this->itemGenericName,PDO::PARAM_STR);
			$item->bindParam(":manufacturer",$this->itemManufacturer,PDO::PARAM_STR);
			$item->bindParam(":form",$this->itemForm,PDO::PARAM_INT);
			$item->bindParam(":expiration",$this->itemExpiration,PDO::PARAM_STR);
			$item->bindParam(":id",$this->itemID,PDO::PARAM_INT);

			if($item->execute()){
				return true;
			}

		}catch(PDOException $err){
			echo $err;
			return false;
		}
		return false;

	}



}