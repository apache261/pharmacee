<?php
require_once('../../config/db_config.php');
require_once('../../class/auth_manager.php');



if (isset($_SERVER['HTTP_ORIGIN'])) {
    header("Access-Control-Allow-Origin: {$_SERVER['HTTP_ORIGIN']}");
    header('Access-Control-Allow-Credentials: true');
    header('Access-Control-Max-Age: 8600');    // cache for 1 day
    }

    // Access-Control headers are received during OPTIONS requests
    if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {

        if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_METHOD']))
            header("Access-Control-Allow-Methods: GET, POST, OPTIONS");         

        if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']))
            header("Access-Control-Allow-Headers: {$_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']}");

        exit(0);
    }

$db = new Database();
$db_conn = $db->getDatabaseConnection();

$auth = new AuthManager($db_conn);
$message = "";
$code;

function errresponse($msg){
    http_response_code(200);
    echo json_encode(array("Message" => $msg, "Success" => 0)); 
}


if($_SERVER['REQUEST_METHOD'] == 'POST')
{
    $data = json_decode(file_get_contents("php://input"));
    try{
    $auth->authOwner = $data->authenticate->uid;
    $auth->authTmpPass= $data->authenticate->pass;

    // Get Login Info Using ID
    // if true, Validate Password 
    if($auth->getLoginInfo()){
        if($auth->validatePass()){
            if($auth->getJWTtoken()){
                echo json_encode(array('token'=> $auth->authJWTtoken, 'role'=> $auth->authActive, "Message" => "Authentication Success", "Success" =>1));
            }else{
                errresponse("Failed to Generate Token");
            }
        }else{
            errresponse("Invalid Password");
        }
    }else{
        errresponse("Invalid ID or Deactivated");
    }
    }catch(Exception $err){
        errresponse("An error Occured");
    }  

}else{
    errresponse("Invalid Operation");
}



