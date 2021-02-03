<?php
require_once('../../config/http_header.php');
require_once('../../config/db_config.php');

require_once('../../class/auth_manager.php');
require_once('../../class/session_manager.php');



if($_SERVER['REQUEST_METHOD'] == 'POST'){
$data = json_decode(file_get_contents("php://input"));





// {
// 	reset:{
// 		"owner":val,
// 		"password":,
// 		"answer":,
// 		"question":,
// 		"active":,
// 	}



// }

	try{
		$db = new Database();
		$db_conn = $db->getDatabaseConnection();
		$item = new AuthManager($db_conn);

		$item->authOwner = $data->reset->owner;
		$item->authPass = '12345';
		$item->authQuestion = '';
		$item->authAnswer = '';
		$item->authActive = 1;
		if($item->updatePassword()){
    http_response_code(200);
    echo json_encode(array("Message"=>"Updated Successfully!"));
}else{
    http_response_code(500);
    echo json_encode(array('Message'=>"An Errorsd Occured"));
}






}catch(Exception $err){
	echo json_encode(array('Message'=>$err));
	http_response_code(500);

}

}else{
	http_response_code(500);
	echo json_encode(array('Message'=>"An Error Occured 405"));
}
