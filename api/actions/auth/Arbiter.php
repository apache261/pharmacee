<?php
// require_once('../../config/http_header.php');
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

    if($_SERVER['REQUEST_METHOD'] != 'POST'){
    	http_response_code(501);
    	echo json_encode(array("Message" => "Method not Allowed"));
    	exit();
    }

    $rolee = "";
    $path = "";
    if(isset($_COOKIE['token']) ){
    	$token = $_COOKIE['token'];

    //init JWT CLASS
    	$sess = new ManageSession();
	//set token
    	$sess->jwt_token = $token;

    	if($sess->validateJWTToken()){

    		$decoded = $sess->decoded_content;

		 // echo($decoded[0]->iat);
    		$rolee = $decoded[0]->data->userRole;

    		switch ($rolee) {
    			case 1:
    			$path = "pharma.php";
// 				header('location:https://pharmafront.herokuapp.com/pharma.php');
    			break;
    			case 2:
    			$path = "encoder.php";
// 				header('location:https://pharmafront.herokuapp.com/encoder.php');
    			break;
    			case 3:
    			$path = "manager.php";
// 				header('location:https://pharmafront.herokuapp.com/manager.php');
    			break;
    			case 4:
    			$path = "dashboard.php";
// 				header('location:https://pharmafront.herokuapp.com/dashboard.php');
    			break;
    			default:
		 	// echo "Path not found";
    			break;
    		}

    		http_response_code(200);
    		echo json_encode(array("path" =>$path));
    		exit();
    	}else{
    		http_response_code(401);
    		echo json_encode(array("Message" => "Invalid Token"));
    		exit();
    	}
    }
    exit();








