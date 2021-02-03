<?php
require_once('../../class/session_manager.php');

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: OPTIONS,GET,POST,PUT,DELETE");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
if (isset($_SERVER['HTTP_ORIGIN'])) {
    header("Access-Control-Allow-Origin: {$_SERVER['HTTP_ORIGIN']}");
    header('Access-Control-Allow-Credentials: true');
        header('Access-Control-Max-Age: 3600');    // cache for 1 day
    }

    // Access-Control headers are received during OPTIONS requests
    if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {

        if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_METHOD']))
            header("Access-Control-Allow-Methods: GET, POST, OPTIONS");         

        if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']))
            header("Access-Control-Allow-Headers: {$_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']}");

        exit(0);
    }

$headers = apache_request_headers();
$token = "";
if(isset($_COOKIE['token']) || isset($headers['Authorization'])){
    if(isset($_COOKIE['token'])){
     $token = $_COOKIE['token'];
    }else{
        if(isset($headers['Authorization'])){
            $bearer = $headers['Authorization'];
            $parToken = explode(" ",$bearer);
            $token = $parToken[1];
        }
    }
    //init JWT CLASS
    $sess = new ManageSession();
    //set token
    $sess->jwt_token = $token;

    if($sess->validateJWTToken()){
        //array token
        $decoded = $sess->decoded_content;
        http_response_code(200);
        echo json_encode($decoded);
        exit();
}
}
http_response_code(200);
echo json_encode(array("valid" => false));
exit();

