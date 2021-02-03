<?php
require_once('../../config/http_header.php');
require_once('../../config/db_config.php');
require_once('../../class/auth_manager.php');




if($_SERVER['REQUEST_METHOD'] == 'POST'){
	$data = json_decode(file_get_contents("php://input"));

	$db = new Database();
	$db_conn = $db->getDatabaseConnection();
	$item = new AuthManager($db_conn);

	$item->authOwner = empty($data->update->owner)?"":$data->update->owner;
		// $item->authPass =  $data->reset->newPass;
	$item->authTmpPass =  empty($data->update->oldPass)?"":$data->update->oldPass;
	$item->authQuestion = ''; 
	$item->authAnswer = '';
	$item->authActive = 1;

	if(strlen($data->update->newPass) < 6 ){
		echo json_encode(array("Message" => "Password Too Short", "Success" => 0));
		exit();
	}

		// Validate old Password
	http_response_code(200);
	if(!$item->validatePass()){
		echo json_encode(array("Message" => "Invalid Password", "Success" => 0));
		exit();
	}

		//Update password
	$item->authPass =  empty($data->update->newPass)?"":$data->update->newPass;
	if($item->updatePassword()){
		echo json_encode(array("Message" => "Password was changed", "Success" => 1));
		exit();
	}else{
		echo json_encode(array("Message" => "Error Occured", "Success" => 0));
		exit();
	}
}
else{
	echo json_encode(array("Message" => "Invalid Request", "Success" => 0));
	exit();
}