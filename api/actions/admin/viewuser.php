<?php
require_once('../../config/http_header.php');
require_once('../../config/db_config.php');
require_once('../../class/admin.php');
require_once('../../class/session_manager.php');



if($_SERVER['REQUEST_METHOD'] == 'GET'){
    $db = new Database();
    $db_conn = $db->getDatabaseConnection();
    $item = new Admin($db_conn);
    
    
    $offset = htmlspecialchars(strip_tags($_GET['offset']));
    $limit = htmlspecialchars(strip_tags($_GET['limit']));
    $active = htmlspecialchars(strip_tags($_GET['active']));
    $type = htmlspecialchars(strip_tags($_GET['type']));
    $uid = htmlspecialchars(strip_tags($_GET['uid']));



    function callAction($obj,$ofst,$lim,$adqur){
        if($obj->getData($ofst,$lim,$adqur)){
            echo json_encode($obj->queryResult);
            http_response_code(200);
            exit();
        }else{
            http_response_code(500);
            echo json_encode(array('Message'=> 'No Result'));
            exit();
        }


    }

    if(!(is_numeric($offset) && is_numeric($limit) && is_numeric($active) && is_numeric($type))){
        http_response_code(500);
        echo json_encode(array('Message'=>"An Error Occured"));
    }


// $type means user Action
// 1 => data search
// 2 => trainee
// 3 => part time
// 4 => regular
// 5 => employed
// 6 => unemployed

    // 7 => Pharmacist
    // 8=?encoder
    // 9 =>session_manager
    // 10 => admin

    try{
        $item->userID = $uid;

        if($type == 1 ){
            if($item->getById()){
                echo json_encode($item->queryResult);
                http_response_code(200);
                exit();
            }else{
                http_response_code(500);
                echo json_encode(array('Message'=> 'No Result'));
                exit();
            }
        }





if ($type == 2){
callAction($item,$offset,$limit,"employee.status = :val");  
}
if($type == 3){
 callAction($item,$offset,$limit,"employee.active = :val");
}
if($type == 4){
    callAction($item,$offset,$limit,"role.rolevalue = :val");
}



















    // $item->userID = htmlspecialchars(strip_tags($data->search->uid));



        if($item->getTraines($offset,$limit,$active)){

            echo json_encode($item->queryResult);
            http_response_code(200);
            exit();
        }else{
            http_response_code(500);
            echo json_encode(array('Message'=>"An Error Occured 500"));
            exit();
        }




    }catch(Exception $err){
        echo json_encode(array('Message'=>$err));
        http_response_code(401);

    }
    
}else{
    http_response_code(405);
    echo json_encode(array('Message'=>"An Error Occured 405"));
}

