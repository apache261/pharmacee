<?php

require_once('validate.php');

// header("Access-Control-Allow-Credentials: true");
// header("Content-Type: application/json; charset=UTF-8");
// header("Access-Control-Allow-Methods: GET, POST, OPTIONS, DELETE");
// header("Access-Control-Max-Age: 3600");
// header("Access-Control-Allow-Origin: *");
// header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
// if(isset($_SERVER['HTTP_ORIGIN'])){
//     header("Access-Control-Allow-Origin: {$_SERVER['HTTP_ORIGIN']}");
// }

// if($_SERVER['REQUEST_METHOD'] == 'OPTIONS'){
//     if(isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_METHOD'])){
//         header("Access-Control-Allow-Methods: GET, POST, OPTIONS, DELETE");
//     }
//     if(isset($_SERVER['HTTP_REQUEST_CONTROL_HEADERS'])){
//         header("Access-Control-Allow-Headers: {$_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']}");
//     }
//     exit(0);
// }

// header("Access-Control-Allow-Origin: *");
// header("Content-Type: application/json; charset=UTF-8");
// header("Access-Control-Allow-Methods: OPTIONS,GET,POST,PUT,DELETE");
// header("Access-Control-Max-Age: 3600");
// header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");



// function redirectHome(){
//     http_response_code(401);
//     echo json_encode(array("Message" => "Not Authorize"));
//     // header('location:http://localhost/pharma/login.php');
//     exit(); 
// }
// header("Content-Type: application/json; charset=UTF-8");
// if(isset($_SERVER['HTTP_AUTHORIZATION'])){
//     $auth_header = $_SERVER['HTTP_AUTHORIZATION'];
//     $data = explode(" ", $auth_header);
//     $token = $data[1];
// $valid = new Validate($token);
// if($valid->isValid()){
//     echo "Valid";
//     exit();
// }else{
//     echo "failed";
//     exit();
// }

// }else{
//    redirectHome();
// }
function redirectHome(){
    // set to 401
    http_response_code(200); 
    echo json_encode(array("Message" => "Not Authorize","Success" => 0));
    // header('location:http://localhost/pharma/login.php');
    exit(); 
}


// header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

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
if(isset($_COOKIE['token']) || isset($headers['Authorization'])){
//COOKIE BASE
    if(isset($_COOKIE['token'])){
        $token = $_COOKIE['token'];
        $valid = new Validate($token);
        if(!$valid->isValid()){
         redirectHome();
     }
 }
 // Authorization
if(isset($headers['Authorization'])){
    $bearer = $headers['Authorization'];
   // var_dump($bearer);
    $token = explode(" ",$bearer);
    $valid = new Validate($token[1]);
if(!$valid->isValid()){
   redirectHome();
}
}

}else{
   redirectHome();
}

