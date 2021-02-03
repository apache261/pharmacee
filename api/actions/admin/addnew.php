<?php

require_once('../../config/http_header.php');
require_once('../../config/db_config.php');
require_once('../../class/admin.php');
require_once('../../class/session_manager.php');




function validateDate($date, $format = 'Y-m-d'){
		$d = DateTime::createFromFormat($format, $date);
		return $d && $d->format($format) === $date;
	}


$validation_results = [];
if($_SERVER['REQUEST_METHOD'] == 'POST'){
	$db = new Database();
	$db_conn = $db->getDatabaseConnection();
	$item = new Admin($db_conn);
	$data = json_decode(file_get_contents("php://input"));

	




// if(
// 	empty($data->info->firstname) ||
// 	empty($data->info->middlename) ||
// 	empty($data->info->familyname) ||
// 	empty($data->info->gendey) ||
// 	empty($data->info->birthday) ||
// 	empty($data->info->active) ||
// 	empty($data->address->barangay) ||
// 	empty($data->address->street) ||
// 	empty($data->address->city) ||
// 	empty($data->address->street) ||
// 	empty($data->address->province) ||
// 	empty($data->address->zipcode) ||
// 	empty($data->address->active) ||
// 	empty($data->contact->type) ||
// 	empty($data->contact->info) ||
// 	empty($data->contact->active) ||
// 	empty($data->auth->password) ||
// 	empty($data->auth->active) ||
// 	empty($data->role->value) ||
// 	empty($data->role->active)
// ){
// http_response_code(200);




// }









// {
//     info{
//         "firstname":"value (STR)",
//         "middlename":"value2(STR)",
//         "familyname":"value3 (STR)",
//         "gender":"value4 (1,2,3,4)",
//         "birthday":"value5 (March 1 2020)"
//     },
//     "address":{
//         "barangay":"value6(STR)",
//         "street":"value7(STR)",
//         "city":"value8(STR)",
//         "province":"value9(STR)",
//         "zipcode":"value10(INT)",
//         "isActive":"value11 (1 or 0)"
//     },
//     "contact":{
//         "type":"value13".
//         "info":"Value14",
//         "active":"value(int 1 or 0)"
//     },
//     "auth":{
//         "password": "value",
//         "active": "value",
//         "question":"value",
//         "answer": "value"
//     }


// }
// decode JSON and Assign


//PERSONAL INFO
// $item->userID = $data->uid;
// $item->userAccountName = $data->accountname;
// $item->userCommonName = $data->nickname;
	$item->userFirstName = $data->info->firstname;
	$item->userMiddleName = $data->info->middlename;
	$item->userLastName = $data->info->familyname;
	$item->userGender = $data->info->gender;
	$item->userBirthdate = $data->info->birthday;
	$item->userActive = $data->info->active;





// ADDRESS
// $item->owner = $data->uid;
	$item->barangay = $data->address->barangay;
	$item->street = $data->address->street;
	$item->city = $data->address->city;
	$item->street = $data->address->street;
	$item->province = $data->address->province;
	$item->zipcode = $data->address->zipcode;
	$item->addressStatus = $data->address->active;

//Contact Info
// $item->contactOwner = $data->uid;
	$item->contactType = $data->contact->type;
	$item->contactInformation = $data->contact->info;
	$item->contactActive = $data->contact->active;
//PASSWORD
	$item->authPass = $data->auth->password;
	$item->authActive = $data->auth->active;
	$item->authQuestion = empty($data->auth->question)?"":$data->auth->question;
	$item->authAnswer = empty($data->auth->answer)?"":$data->auth->answer;

// ROLE
	$item->roleValue = $data->role->value;
	$item->roleActive = $data->role->active;

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













	
	if($item->insertAll()){

		echo json_encode(array("Message"=>"Added Successfully!", "Validation" => $validation_results, "Success" => 1));
		exit();
	}else{

		echo json_encode(array('Message'=>"An Error Occured","Validation" => $validation_results, "Success" => 0));
		exit();

	}

}else{

	echo json_encode(array('Message'=>"Invalid Method ","Validation" => $validation_results, "Success" => 0));
	exit();
}

