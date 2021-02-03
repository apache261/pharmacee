<?php
require_once('../../config/http_header.php');
require_once('../../class/session_manager.php');
require_once('../../config/db_config.php');



if($_SERVER['REQUEST_METHOD'] == 'GET'){

	$key= htmlspecialchars(strip_tags($_GET['key']));

	$database  = new Database();
	$db_con = $database->getDatabaseConnection();
	$items = new ViewItem($db_con);

	$items->itemID = $key;
	
	if($items->getData()){
		http_response_code(200);
		echo json_encode($items->queryResult);
		exit();
	}else{
		http_response_code(500);
		echo json_encode(array("Message"=> "Error"));
		exit();
	}
	
}else{
	http_response_code(405);
	echo json_encode(array('Message'=>"Not Allowed"));
	exit();
}


class ViewUser{
public $db_conn;



public function __construct($db){
$this->db_conn = $db;
}









}