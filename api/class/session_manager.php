<?php
require_once('../../../vendor/autoload.php');
use \Firebase\JWT\JWT;
class ManageSession{

    private $serverKey = "12345dedfdsfs";
    private $algorithm = "HS256";
    private $issuer = "pharmacee";
    private $audience = "pharmacee";
    public $userId = "";
    public $userRole = "";
    public $userActStatus = "";
    public $header_authorization = "";
    public $decoded_content;
    public $userPass = "";
    public $jwt_token;
    public $empstatus = "";



    public $firstname;
    public $lastname;
    public $middlename;
    public $gender;
    public $db_conn;

    public function __construct($db = null)
    {
        $this->db_conn = $db;
    }


    // Generate JWT Token 
    public function generateClientKey(){
        $iat = time();
        //Time before token is valid
        $nbf = $iat;
        // expire after 5 mins
        $exp = $iat + (60 * 60);

        $token = array(
            "iss" => $this->issuer,
            "aud" => $this->audience,
            "iat" => $iat,
            "nbf" => $nbf,
            "exp" => $exp,
            "data" => array(
                "userId" => $this->userId,
                "userRole" => $this->userRole,
                "firstname" => $this->firstname,
                "lastname" => $this->lastname,
                "middlename" => $this->middlename,
                "gender" => $this->gender,
                "empstatus" =>$this->empstatus
            )

            );

            return $this->jwt_token = JWT::encode(
                $token,
                $this->serverKey
            );
    }

    public function validateJWTToken(){
        try{
            $this->decoded_content = array(JWT::decode($this->jwt_token,$this->serverKey, array($this->algorithm)));
            // echo json_encode((array) $this->decoded_content);
            return true;
        }catch(Exception $err){
            return false;
        }
    }

    // public function authenticateUser(){
    //     $sql = "";
    //     $reqLogin = $this->db_conn->prepare($sql);
    //     $this->userId = htmlspecialchars(strip_tags($this->userId));
    //     $this->userPass = htmlspecialchars(strip_tags($this->userPass));

    //     $reqLogin->bindParam(':uid',$this->userId);
       
    //     if($reqLogin->execute()){

    //         if($reqLogin->rowCount() > 0){
    //             if($row = $reqLogin->fetch()){
    //                 $hashedPAss = $row['userPass'];
    //                 if(password_verify($this->userPass,$hashedPAss)){
    //                     $this->userRole = $row['userRole'];
    //                     $this->userActStatus = $row['userActStat'];
    //                     $this->generateClientKey();
    //                     return true; 
    //                 }
    //             }
    //         }
        
    //     }


    //     return false;
    // }
    

}