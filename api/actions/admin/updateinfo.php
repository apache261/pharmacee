<?php
require_once('../../config/http_header.php');
require_once('../../class/session_manager.php');
require_once('../../config/db_config.php');
require_once('../../trait/useraddress.php');
require_once('../../trait/userInfo.php');
require_once('../../trait/role.php');
require_once('../../trait/contact.php');

function validateDate($date, $format = 'Y-m-d'){
		$d = DateTime::createFromFormat($format, $date);
		return $d && $d->format($format) === $date;
	}


$validation_results = [];

if($_SERVER['REQUEST_METHOD'] == 'POST'){
	$database  = new Database();
	$db_con = $database->getDatabaseConnection();
	$item = new UpdateUser($db_con);

	$data = json_decode(file_get_contents("php://input"));
	$item->userID = $data->info->uid;
	$item->userFirstName = $data->info->firstname;
	$item->userMiddleName = $data->info->middlename;
	$item->userLastName = $data->info->familyname;
	$item->userGender = $data->info->gender;
	$item->userBirthdate = $data->info->birthday;



// ADDRESS
// $item->owner = $data->uid;
	$item->barangay = $data->address->barangay;
	$item->street = $data->address->street;
	$item->city = $data->address->city;
	$item->street = $data->address->street;
	$item->province = $data->address->province;
	$item->zipcode = $data->address->zipcode;

//Contact Info
// $item->contactOwner = $data->uid;
	$item->contactType = $data->contact->type;
	$item->contactInformation = $data->contact->info;
// ROLE
	$item->roleValue = $data->role->value;

//Employment
	$item->userEmpStatus = $data->status->type;

	$isvalidFirstName = !empty($item->userFirstName) && preg_match('/^[a-zA-Z._ -]*$/i', $item->userFirstName);
	$isvalidLastName = !empty($item->userLastName) && preg_match('/^[a-zA-Z._ -]*$/i', $item->userLastName);
	$isvalidMiddleName = !empty($item->userMiddleName) && preg_match('/^[a-zA-Z._ -]*$/i', $item->userMiddleName);
	$isvalidGender = !empty($item->userGender);
	$isvalidBday = !empty($item->userBirthdate) && validateDate($item->userBirthdate);
	$isvalidBarangay = !empty($item->barangay) && preg_match('/^[a-zA-Z0-9._ -]*$/i', $item->barangay);
	$isvalidStreet = !empty($item->street) && preg_match('/^[a-zA-Z0-9._ -]*$/i', $item->street);
	$isvalidCity = !empty($item->city) && preg_match('/^[a-zA-Z0-9._ -]*$/i', $item->city);
	$isvalidProvince = !empty($item->province) && preg_match('/^[a-zA-Z._ -]*$/i', $item->province);
	$isvalidZipcode = !empty($item->zipcode) && preg_match('#[0-9]{4}#', $item->zipcode);


	$isvalidEmail = filter_var($item->contactInformation, FILTER_VALIDATE_EMAIL)?true:false;
	$isvalidRole = !empty($item->roleValue) && preg_match('#[1-4]{1}#', $item->roleValue);
	$isvalidEmployStatus = !empty($item->userEmpStatus) && preg_match('#[1-4]{1}#', $item->userEmpStatus);

	$validation_results = array(
// 		"UserID" => $isvalidUser,
		"FirstName" => $isvalidFirstName,
		"LastName" => $isvalidLastName,
		"MiddleName" => $isvalidMiddleName,
		"Gender" => $isvalidGender,
		"Birthdate" => $isvalidBday,
		"Barangay" => $isvalidBarangay,
		"Street" => $isvalidStreet,
		"City" => $isvalidCity,
		"Province" => $isvalidProvince,
		"Zipcode" => $isvalidZipcode,
		"Email" => $isvalidEmail,
		"Role" => $isvalidRole,
		"Empstatus" => $isvalidEmployStatus
	);


	http_response_code(200);

	if(!($isvalidUser)){
		echo json_encode(array('Message'=>"Invalid User","Validation" => $validation_results, "Success" => 0));
		exit();
	}

// validate First Last Middle Name
	if(!($isvalidFirstName && $isvalidLastName && $isvalidMiddleName)){
		echo json_encode(array('Message'=>"Invalid Name","Validation" => $validation_results, "Success" => 0));
		exit();
	}
//Validate Bday yyyy-mm-dd
	if(!($isvalidBday)){
		echo json_encode(array('Message'=>"Invalid Birthdate","Validation" => $validation_results, "Success" => 0));
		exit();
	}
//Validate Bday yyyy-mm-dd
	if(!($isvalidGender)){
		echo json_encode(array('Message'=>"Select Gender","Validation" => $validation_results, "Success" => 0));
		exit();
	}
//Validate Address, optional Street
	if(!($isvalidBarangay && $isvalidCity && $isvalidProvince)){
		echo json_encode(array('Message'=>"Invalid Address","Validation" => $validation_results, "Success" => 0));
		exit();
	}
// Validate Zipcode eg 6121
	if(!($isvalidZipcode)){
		echo json_encode(array('Message'=>"Invalid Zipcode","Validation" => $validation_results, "Success" => 0));
		exit();
	}
// Validate user email
	if(!($isvalidEmail)){
		echo json_encode(array('Message'=>"Invalid Email","Validation" => $validation_results, "Success" => 0));
		exit();
	}
// VALIDATE USER ROLE
	if(!($isvalidRole)){
		echo json_encode(array('Message'=>"Select Role","Validation" => $validation_results, "Success" => 0));
		exit();
	}
	if(!($isvalidEmployStatus)){
		echo json_encode(array('Message'=>"Set Employee Status","Validation" => $validation_results, "Success" => 0));
		exit();
	}


	if($item->updateUserDetails()){
		echo json_encode(array('Message'=>"Updated","Validation" => $validation_results, "Success" => 1));
		exit();
		
	}

	
}else{
	echo json_encode(array('Message'=>"Method Not Allowed","Validation" => $validation_results, "Success" => 0));
	exit();
}




class UpdateUser{
	public $addressID;
	public $addressowner;
	public $barangay;
	public $street;
	public $city;
	public $province;
	public $zipcode;
	public $addressStatus;

	public $roleID;
	public $roleOwner;
	public $roleValue;
	public $roleActive;
	public $userID;
	// public $userAccountName;
	// public $userCommonName;
	public $userFirstName;
	public $userMiddleName;
	public $userLastName;
	public $userGender;
	public $userBirthdate;
	public $userActive;
	public $userDeactivate = 0;
	public $userEmpStatus;
	public $contactID;
	public $contactOwner;
	public $contactType; 
	public $contactInformation;
	public $contactActive;

	public $db_conn;

	private $itemInsertReason = 1;
	private $itemCurrentOnHand;

	public function __construct($db){
		$this->db_conn = $db;
	}

	public function updateUserDetails(){
		if($this->updateInfo()){
			
			if($this->updateAddress()){
				
				if($this->updateContact()){
					
					if($this->updateRole()){
						
						return true;
					}
				}
			}
		}
		return false;
	}
	public function updateAddress(){
		$active = 1;
		$sql = "UPDATE `address` SET `Barangay`=:brgy,`City`=:city,`Street`=:strt,`Province`=:prov,`ZipCode`=:zip WHERE `EmployeeID`=:owner AND `active` = :active";

		try{
			$item = $this->db_conn->prepare($sql);
			$item->bindParam(":brgy",$this->barangay,PDO::PARAM_STR);
			$item->bindParam(":city",$this->city,PDO::PARAM_STR);
			$item->bindParam(":strt",$this->street,PDO::PARAM_STR);
			$item->bindParam(":prov",$this->province,PDO::PARAM_STR);
			$item->bindParam(":zip",$this->zipcode,PDO::PARAM_INT);
			$item->bindParam(":owner",$this->userID,PDO::PARAM_STR);
			$item->bindParam(":active",$active,PDO::PARAM_STR);
			return $item->execute();
		}catch(PDOException $err){
			echo $err;
			return false;
		}
		return false;
	}

	public function updateRole(){
		$active = 1;
		$sql = "UPDATE `role` SET `RoleValue`=:value WHERE `EmployeeID`=:owner AND `active` = :active";

		try{
			$item = $this->db_conn->prepare($sql);
			$item->bindParam(":value",$this->roleValue,PDO::PARAM_INT);
			$item->bindParam(":owner",$this->userID, PDO::PARAM_INT);
			$item->bindParam(":active",$this->active, PDO::PARAM_INT);
			return $item->execute();
		}catch(PDOException $err){
			echo $err;
			return false;
		}
		return false;
	}

	public function updateInfo(){
		$sql = "UPDATE `employee` SET `FirstName`=:fname,`MiddleName`=:mname,`LastName`=:lname,`Gender`=:gender,`Birthdate`=:bday, `status`=:stat WHERE `EmployeeID`=:owner";

		try{
			$item = $this->db_conn->prepare($sql);
			$item->bindParam(":fname",$this->userFirstName,PDO::PARAM_STR);
			$item->bindParam(":mname",$this->userMiddleName,PDO::PARAM_STR);
			$item->bindParam(":lname",$this->userLastName,PDO::PARAM_STR);
			$item->bindParam(":gender",$this->userGender,PDO::PARAM_INT);
			$item->bindParam(":bday",$this->userBirthdate,PDO::PARAM_STR);
			$item->bindParam(":stat",$this->userEmpStatus,PDO::PARAM_INT);
			$item->bindParam(":owner",$this->userID,PDO::PARAM_INT);
			// echo ($this->userGender);
			return $item->execute();

		}catch(PDOException $err){
			echo $err;
			return false;
		}
		return false;
	}

	public function updateContact(){
		$active = 1;
		$sql = "UPDATE `contact` SET `ContactInformation`= :value WHERE `EmployeeID`= :owner AND `active`=:active";

		try{
			$item = $this->db_conn->prepare($sql);
			$item->bindParam(":value",$this->contactInformation, PDO::PARAM_STR);
			$item->bindParam(":owner",$this->userID, PDO::PARAM_INT);
			$item->bindParam(":active",$this->active, PDO::PARAM_INT);

			return $item->execute();

		}catch(PDOException $err){
			echo $err;
			return false;
		}
		return false;
	}

}
