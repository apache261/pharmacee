<?php
// require_once('admin_db_query.php');
require_once('utili.php');
require_once('../../trait/userInfo.php');
require_once('../../trait/useraddress.php');
require_once('../../trait/contact.php');
require_once('../../trait/user_auth.php');
require_once('../../trait/role.php');
require_once('auth_manager.php');
require_once('generator.php');

// require_once('../session_manager.php');


class Admin{

	use UserInfo;
	use UserAddress;
	use USerContact;
	use UserAuth;
	use UserRole;
	
	
	private $db_connection;
	private $util;
	// private $newID;
	private $generate;
	private $auth;

	public $queryResult;


	public function __construct($db_conn)
	{
		$this->db_connection = $db_conn;
		$this->util = new Utility();
		$this->auth = new AuthManager($db_conn);
		$this->generate = new IDGenerator();
		$this->userID = $this->generate->generateUniqueEmployeeID();

		
	}



	public function insertAll(){
		return $this->addEmployeeInfo() && $this->addEmployeeContact() && $this->addEmployeeAddress() && $this->addEmployeeLogin() && $this->addRole();
	}
	public function addEmployeeInfo(){

		$itemQuery = "INSERT INTO `employee`(`EmployeeID`,`FirstName`, `MiddleName`, `LastName`, `Gender`, `Birthdate`,active,status) VALUES (:id,:first,:middle,:last,:gend,:birth,:active,:status)";
		$newItem = $this->db_connection->prepare($itemQuery);

		$this->util->stripTags($this->userID);
		// $this->util->stripTags($this->userAccountName);
		// $this->util->stripTags($this->userCommonName);
		$this->util->stripTags($this->userFirstName);
		$this->util->stripTags($this->userMiddleName);
		$this->util->stripTags($this->userLastName);
		$this->util->stripTags($this->userGender);
		$this->util->stripTags($this->userBirthdate);

		$newItem->bindParam(":id",$this->userID,PDO::PARAM_INT);
		// $newItem->bindParam(":acname",$this->userAccountName,PDO::PARAM_STR);
		// $newItem->bindParam(":nick",$this->userCommonName,PDO::PARAM_STR);
		$newItem->bindParam(":first",$this->userFirstName,PDO::PARAM_STR);
		$newItem->bindParam(":middle",$this->userMiddleName,PDO::PARAM_STR);
		$newItem->bindParam(":last",$this->userLastName,PDO::PARAM_STR);
		$newItem->bindParam(":gend",$this->userGender,PDO::PARAM_INT);
		$newItem->bindParam(":birth",$this->userBirthdate,PDO::PARAM_STR);
		$newItem->bindParam(":active",$this->userActive,PDO::PARAM_INT);
		$newItem->bindParam(":status",$this->userEmpStatus,PDO::PARAM_INT);
		
		try{ 
			if($newItem->execute()){
				return true;
			}
		}catch(PDOException $err){
			echo $err;
			return false;
		}
		return false;
	}

	public function addEmployeeContact(){

		$itemQuery = "INSERT INTO `contact` (`ContactID`, `TypeID`, `EmployeeID`, `ContactInformation`,active) VALUES (NULL, :type, :owner, :info,:active);";
		$newItem = $this->db_connection->prepare($itemQuery);
		$this->contactOwner = $this->userID;

		$this->util->stripTags($this->contactOwner);
		$this->util->stripTags($this->contactType);
		$this->util->stripTags($this->contactInformation);

		$newItem->bindParam(":type",$this->contactType,PDO::PARAM_INT);
		$newItem->bindParam(":owner",$this->contactOwner,PDO::PARAM_STR);
		$newItem->bindParam(":info",$this->contactInformation,PDO::PARAM_STR);
		$newItem->bindParam(":active",$this->contactActive,PDO::PARAM_INT);
		try{ 
			if($newItem->execute()){
				return true;
			}
		}catch(PDOException $err){
			echo"Contact{$err}";
			return false;
		}
		return false;
	}

	public function unEmployed(){
		$itemQuery = "UPDATE `employee` SET `active` = :deac WHERE `employee`.`EmployeeID` = :uid";
		try{
			$item = $this->db_connection->prepare($itemQuery);
			$item->bindParam(":deac",$this->userDeactivate,PDO::PARAM_INT);
			$item->bindParam(":uid",$this->userID,PDO::PARAM_INT);
			if($item->execute()){
				return true;
			}
		}catch(PDOException $err){
			return false;
		}
		return true;
	}

	public function addRole(){
		$this->roleOwner = $this->userID;
		$itemInsertQuery = "INSERT INTO `role`(`RoleID`, `EmployeeID`, `active`, `RoleValue`) VALUES (NULL,:uid,:active,:role)";
		try{
			$item = $this->db_connection->prepare($itemInsertQuery);
			$item->bindParam(":uid",$this->roleOwner,PDO::PARAM_STR);
			$item->bindParam(":active",$this->roleActive,PDO::PARAM_INT);
			$item->bindParam(":role",$this->roleValue,PDO::PARAM_INT);
			if($item->execute()){
				return true;
			}
		}catch(PDOException $err){
			echo $err;
			return false;
		}
		return false;

	}
	public function addEmployeeAddress(){
		$this->contactOwner = $this->userID;
		$itemQuery = "INSERT INTO `address`(`AddressID`, `EmployeeID`, `Barangay`, `City`, `Street`, `Province`, `ZipCode`, `Active`) VALUES (NULL,:uid,:brgy,:city,:strt,:prov,:zip,:active)";
		$newItem = $this->db_connection->prepare($itemQuery);
		// $this->util->stripTags($this->addressID); //AutoIncrement
		$this->util->stripTags($this->contactOwner);
		$this->util->stripTags($this->street);
		$this->util->stripTags($this->barangay);
		$this->util->stripTags($this->city);
		$this->util->stripTags($this->province);
		$this->util->stripTags($this->zipcode);
		$this->util->stripTags($this->addressStatus);
		// (NULL,:uid,:brgy,:city,:strt,:prov,:zip,:active)";
		// $newItem = $this->db_connection->prepare($itemQuery);
		$newItem->bindParam(":uid",$this->contactOwner,PDO::PARAM_INT);
		$newItem->bindParam(":brgy",$this->barangay,PDO::PARAM_STR);
		$newItem->bindParam(":city",$this->city,PDO::PARAM_STR);
		$newItem->bindParam(":strt",$this->street,PDO::PARAM_STR);
		$newItem->bindParam(":prov",$this->province,PDO::PARAM_STR);
		$newItem->bindParam(":zip",$this->zipcode,PDO::PARAM_INT);
		$newItem->bindParam(":active",$this->addressStatus,PDO::PARAM_INT);
		try{ 
			if($newItem->execute()){
				return true;
			}
		}catch(PDOException $err){
			echo"Address{$err}";
			return false;
		}
		return false;

	}

	public function addEmployeeLogin(){

		$this->util->stripTags($this->authAnswer);
		$this->util->stripTags($this->authQuestion);
		$this->util->stripTags($this->authActive);
		$this->auth->authOwner = $this->userID;
		$this->auth->authAnswer = $this->authAnswer;
		$this->auth->authQuestion = $this->authQuestion;
		$this->auth->authPass = $this->authPass;
		$this->auth->authActive = $this->authActive;
		if($this->auth->insertPassword()){
			return true;
		}
		return false;

	}

	public function BuildFilter(){


	}
	public function getByAddress(){
		$query = "SELECT * FROM `address` WHERE `Barangay` LIKE 'cabahug' OR `City` LIKE 'cadiz'";

	}








	public function userinfoByID(){
		$sql = "SELECT `EmployeeID`, `FirstName`, `MiddleName`, `LastName`, `Gender`, `Birthdate`, `active`, `status` FROM `employee` WHERE EmployeeID = :id";
		$item = $this->db_connection->prepare($sql);
		$item->bindParam(":id",$this->userID,PDO::PARAM_INT);
		try{
			if($item->execute()){
				$row = $item->fetch();
				if(count($row) > 0){
					$this->userFirstName = $row['FirstName'];
					$this->userMiddleName = $row['MiddleName'];
					$this->userLastName = $row['LastName'];
					$this->userGender = $row['Gender'];
					$this->userBirthdate = $row['Birthdate'];
					$this->userActive = $row['active'];
					$this->userEmpStatus = $row['status'];
				}

				return true;
			}
		}catch(PDOException $err){
			echo $err;
			return false;
		}
		return false;
	}








	public function removeEmployee(){
		$this->auth->authOwner = $this->userID;
		if(!($this->userinfoByID())){
			return false;
		}
		if($this->auth->getLoginInfo()){
			if($this->auth->deactivatePassword()){
				$this->unEmployed();
				return true;
			}
		}
		return false;
	}

	public function getTraines($offset, $limit,$active){
		$additional = ($active <= 1?"AND employee.Active = :val":"");
		$sql = "SELECT employee.EmployeeID,employee.FirstName,employee.MiddleName,employee.LastName,employee.Gender,employee.Birthdate,employee.Active,employee.status,contact.TypeID,contact.contactinformation, address.Street,address.Barangay,address.City,address.Province,address.ZipCode,role.RoleValue FROM (((employee INNER JOIN contact on employee.EmployeeID = contact.EmployeeID {$additional}) INNER JOIN address on contact.EmployeeID = address.EmployeeID) INNER JOIN role on address.EmployeeID= role.EmployeeID) LIMIT :limit OFFSET :offset ";
		$items = $this->db_connection->prepare($sql);
		if($active <= 1){$items->bindParam(":val",$active,PDO::PARAM_INT);}
		$items->bindParam(":limit",$limit,PDO::PARAM_INT);
		$items->bindParam(":offset",$offset,PDO::PARAM_INT);
		try{
			if($items->execute()){
				$row = $items->fetchAll(PDO::FETCH_ASSOC);
				if(count($row) >0 ){
					$this->queryResult = $row;
					return true;
				}
			}
		}catch(PDOException $err){
			echo $err;
			return false;
		}

		return false;

	}

	public function getById(){
		$this->userID = $this->userID.'%';
		$sql = "SELECT employee.EmployeeID,employee.FirstName,employee.MiddleName,employee.LastName,employee.Gender,employee.Birthdate,employee.Active,employee.status,contact.TypeID,contact.contactinformation, address.Street,address.Barangay,address.City,address.Province,address.ZipCode,role.RoleValue FROM (((employee INNER JOIN contact on employee.EmployeeID = contact.EmployeeID) INNER JOIN address on contact.EmployeeID = address.EmployeeID) INNER JOIN role on address.EmployeeID = role.EmployeeID) WHERE employee.EmployeeID LIkE :uid OR employee.FirstName LIKE :uid2 OR employee.LastName LIKE :uid3";
		$items = $this->db_connection->prepare($sql);
		// $items->bindParam(":uid4",$this->userID,PDO::PARAM_STR);
		$items->bindParam(":uid",$this->userID,PDO::PARAM_STR);
		$items->bindParam(":uid2",$this->userID,PDO::PARAM_STR);
		$items->bindParam(":uid3",$this->userID,PDO::PARAM_STR);
		
		try{
			if($items->execute()){
				$row = $items->fetchAll(PDO::FETCH_ASSOC);
				if(count($row) >0 ){
					$this->queryResult = $row;
					return true;
				}
			}
		}catch(PDOException $err){
			echo $err;
			return false;
		}

		return false;

	}
	public function getEmployeeList($admin,$address,$contact,$name,$active){
		// SELECT employee.EmployeeID, employee.FirstName,employee.LastName,
		//  employee.Birthdate, contact.ContactInformation,address.Street,address.city,
		//  address.Province from ((employee INNER join contact on employee.EmployeeID = contact.EmployeeID)
		//  INNER join address on contact.EmployeeID = address.EmployeeID)
		// if()

		$getAdminListQuery = "SELECT employee.FirstName, employee.LastName, contact.ContactInformation from employee, contact where employee.EmployeeID = contact.EmployeeID AND contact.active = 1";
		$employeeInfo = $this->db_connection->prepare($getAdminListQuery);
		$employeeInfo->execute();
		$adminList = $employeeInfo->fetchAll(PDO::FETCH_ASSOC);
		return $adminList;
	}



















	public function getData($offset, $limit,$additional){

		$sql = "SELECT employee.EmployeeID,employee.FirstName,employee.MiddleName,employee.LastName,employee.Gender,employee.Birthdate,employee.Active,employee.status,contact.TypeID,contact.contactinformation, address.Street,address.Barangay,address.City,address.Province,address.ZipCode,role.RoleValue FROM (((employee INNER JOIN contact on employee.EmployeeID = contact.EmployeeID ) INNER JOIN address on contact.EmployeeID = address.EmployeeID) INNER JOIN role on address.EmployeeID= role.EmployeeID) WHERE {$additional} LIMIT :limit OFFSET :offset ";
		$items = $this->db_connection->prepare($sql);
		$items->bindParam(":limit",$limit,PDO::PARAM_INT);
		$items->bindParam(":offset",$offset,PDO::PARAM_INT);
		$items->bindParam(":val",$this->userID,PDO::PARAM_STR);
		try{
			if($items->execute()){
				$row = $items->fetchAll(PDO::FETCH_ASSOC);
				if(count($row) >0 ){
					$this->queryResult = $row;
					return true;
				}
			}
		}catch(PDOException $err){
			echo $err;
			return false;
		}

		return false;

	}


}


