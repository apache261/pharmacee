<?php
require_once('../../config/http_header.php');
require_once('../../config/db_config.php');
require_once('../../class/admin.php');
require_once('../../class/session_manager.php');

if($_SERVER['REQUEST_METHOD'] == 'POST'){
    $db = new Database();
    $db_conn = $db->getDatabaseConnection();
    $item = new Admin($db_conn);
    
    
    try{
    $data = json_decode(file_get_contents("php://input"));
    

    $item->userID = htmlspecialchars(strip_tags($data->unemp->uid));
    
    if($item->removeEmployee()){
        http_response_code();
        echo json_encode(array("Message" => "Remove SuccessFully"));
    }else{
        http_response_code(500);
        echo json_encode(array('Message'=>"An Error Occured"));
    }

}catch(Exception $err){
    echo json_encode(array('Message'=>"$err"));
    http_response_code(401);

}
    
    }else{
        http_response_code(405);
        echo json_encode(array('Message'=>"An Error Occured"));
    }



