<?php

require_once('../../trait/user_auth.php');
require_once('../../trait/role.php');
require_once('../../trait/userInfo.php');
require_once('session_manager.php');
class AuthManager{
    use UserAuth;
    use UserInfo;
    private $db_con;
    private $sess;
    use UserRole;

    public function __construct($db)
    {
        $this->db_con = $db;
        $this->sess = new ManageSession($db);
    }


public function getAuthID(){
        try{
           
        $itemQuery = "SELECT * FROM `password` WHERE `EmployeeID` =:id AND `Active` =:active";
        $item = $this->db_con->prepare($itemQuery);

        $item->bindParam(":id",$this->authOwner,PDO::PARAM_INT);
        $item->bindParam(":active",$this->authActivated,PDO::PARAM_INT);
            if($item->execute()){
                if($item->rowCount() > 0){
                    $row =$item->fetch();
                    $this->authFound = true;
                    $this->authID = intval($row['PasswordID']);


                    // $this->sess->userId = $this->authOwner;
                    return true;
                }
            }
        }catch(PDOException $err){
            echo $err;
            return false;
        }
        return false;
    }
    public function getLoginInfo(){
        try{
             $itemQuery ="SELECT password.PasswordID, password.EmployeeID,password.Active,password.Password,employee.FirstName,employee.LastName,employee.MiddleName,employee.Gender, employee.Birthdate, employee.status as empstat  from (password INNER JOIN employee on password.EmployeeID = employee.EmployeeID AND password.EmployeeID = :id AND password.Active = :active AND employee.active = :active2)";
        // $itemQuery = "SELECT * FROM `password` WHERE `EmployeeID` =:id AND `Active` =:active";
        $item = $this->db_con->prepare($itemQuery);

        $item->bindParam(":id",$this->authOwner,PDO::PARAM_INT);
        $item->bindParam(":active",$this->authActivated,PDO::PARAM_INT);
        $item->bindParam(":active2",$this->authActivated,PDO::PARAM_INT);
            if($item->execute()){
                if($item->rowCount() > 0){
                    $row =$item->fetch();
                    $this->authFound = true;
                    $this->authID = intval($row['PasswordID']);
                    // $this->authOwner= intval($row['EmployeeID']);
                    // $this->authQuestion= intval($row['RecoveryQuestion']);
                    // // $this->authAnswer = intval($row['RecoveryAnswer']);
                    $this->userFirstName = $row['FirstName'];
                    $this->userMiddleName = $row['MiddleName'];
                    $this->userLastName = $row['LastName'];
                    $this->userBirthDate = $row['Birthdate'];
                    $this->userGender = $row['Gender'];
                    $this->authPass = $row['Password'];
                    $this->authActive = $row['Active'];
                    $this->userEmpStatus = $row['empstat'];
                    // $this->sess->userId = $this->authOwner;
                    return true;
                }
            }
        }catch(PDOException $err){
            echo $err;
            return false;
        }
        return false;
    }

    public function isActive(){
        return $this->authActive == 1;
    }

    public function validatePass(){
        $this->getLoginInfo();
        $this->authPassValid = $this->authTmpPass == $this->authPass;
        return $this->authPassValid && $this->isActive();
    }
    public function updatePassword(){

       if($this->getAuthID()){

           if($this->deactivatePassword()){
        
               if($this->insertPassword()){
            
                   return true;
               }
           }
       }
       return false;
    }
    public function updateRecoveryQuestion(){
        
        try{
            if($this->getLoginInfo()){
        $update = "UPDATE `password` SET `RecoveryQuestion` = :question `RecoveryAnswer` = :answer WHERE `password`.`PasswordID` = :passID";

        $item = $this->db_con->prepare($update);
        $item->bindParam(":question",$this->authQuestion,PDO::PARAM_STR);
        $item->bindParam(":answer",$this->authAnswer,PDO::PARAM_STR);
        $item->bindParam(":passID",$this->authID);

        if($item->execute()){
            return true;
        }
    }
    }catch(PDOException $err){
        echo "Error Updating Recovery Question";
    }
    return false;
    }

    public function insertPassword(){
        try{
            $insertQuery = "INSERT INTO `password` (`PasswordID`, `EmployeeID`, `RecoveryAnswer`, `RecoveryQuestion`, `Password`, `Active`) VALUES (NULL, :id, :answer, :question, :pass,:active)";
            $item = $this->db_con->prepare($insertQuery);

            $item->bindParam(":id",$this->authOwner,PDO::PARAM_INT);
            $item->bindParam(":answer",$this->authAnswer,PDO::PARAM_STR);
            $item->bindParam(":question",$this->authQuestion,PDO::PARAM_STR);
            $item->bindParam(":pass",$this->authPass,PDO::PARAM_STR);
            $item->bindParam(":active",$this->authActive,PDO::PARAM_INT);
            if($item->execute()){
                return true;
            }
        }catch(PDOException $err){
            echo $err;
            echo "Error Inserting";
            return false;
        }
        return false;
    }
    public function deactivatePassword(){
        try{
            $insertQuery = "UPDATE `password` SET `Active` = :deactivate WHERE `password`.`PasswordID` = :passID";
            $item = $this->db_con->prepare($insertQuery);
            $item->bindParam(":deactivate",$this->authDeactivate,PDO::PARAM_INT);
            $item->bindParam(":passID",$this->authID,PDO::PARAM_INT);
            if($item->execute()){
                return true;
            }
        }catch(PDOException $err){
            echo "Error Updating Password!";
        }
    }
    public function getRole(){  
		$itemInsertQuery = "SELECT `active`, `RoleValue` FROM `role` WHERE employeeID = :uid AND active=:active";
		try{
		$item = $this->db_con->prepare($itemInsertQuery);
		$item->bindParam(":uid",$this->authOwner,PDO::PARAM_STR);
		$item->bindParam(":active",$this->roleActivated,PDO::PARAM_INT);
		if($item->execute()){
            if($item->rowCount() > 0){
                $row = $item->fetch();
                $this->roleValue=$row['RoleValue'];
                $this->roleActive = $row['active'];
                return true;
            }
		}
		}catch(PDOException $err){
			echo $err;
			return false;
		}
		return false;

    }
    
    public function getJWTtoken(){
            if($this->getRole()){
                $this->sess->userId = $this->authOwner;
                $this->sess->userRole = $this->roleValue;
                $this->sess->userActive = $this->roleActive;
                $this->sess->firstname = $this->userFirstName ;
                $this->sess->middlename = $this->userMiddleName;
                $this->sess->lastname = $this->userLastName ;
                $this->sess->gender = $this->userGender;
                $this->sess->empstatus = $this->userEmpStatus;
                $this->authJWTtoken = $this->sess->generateClientKey();
                return true;
            }

        return false;
    }
 



}


