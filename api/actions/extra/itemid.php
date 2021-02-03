<?php

require_once('../../config/http_header.php');
require_once('../../class/generator.php');
require_once('../../class/session_manager.php');



if($_SERVER['REQUEST_METHOD'] == 'GET'){
	$generator = new IDGenerator();

	try{
		$uniqueItemID = $generator->generateUniqueItemID();
		echo json_encode(array("itemid"=>$uniqueItemID));
		http_response_code(200);
		exit();
}catch(Exception $err){
	http_response_code(500);
	echo json_encode(array('Message'=>"An Error Occured 500"));
}
}else{
	http_response_code(405);
	echo json_encode(array('Message'=>"An Error Occured 405"));
}
