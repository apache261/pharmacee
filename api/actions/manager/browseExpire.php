<?php
require_once('../../config/http_header.php');
require_once('../../class/session_manager.php');
require_once('../../config/db_config.php');


if(!(isset($_GET['action']))){
		http_response_code(500);
		echo json_encode(array("Message"=> "Error"));
		exit();
	}
$action = $_GET['action'];
$database  = new Database();
$db_con = $database->getDatabaseConnection();
$items = new ExpiryBrowser($db_con);

// 1 max and min
// 2 about to expire
if($action == 1){
	$items->minDate = $_GET['start'];
	$items->maxDate = $_GET['end'];
	if($items-> browseExpiry()){
		http_response_code(200);
		echo json_encode($items->queryResult);
		exit();
	}

}
if($action == 2){
if($items-> showAboutToExpire()){
		http_response_code(200);
		echo json_encode($items->queryResult);
		exit();
	}
}
if($action == 3){
	if($items-> showTodayExpire()){
		http_response_code(200);
		echo json_encode($items->queryResult);
		exit();
	}
}

echo json_encode($items->queryResult);
exit();

	

	$keyword = htmlspecialchars(strip_tags($_GET['min']));

		$items->minStocks =$keyword;
		if($items-> getLowStock()){
			http_response_code(200);
			echo json_encode($items->queryResult);
			exit();
		}else{
			http_response_code(500);
			echo json_encode(array("Message"=> "Error"));
			exit();
		}	



class ExpiryBrowser{

	private $db_conn;
	public $minStocks;
	public $queryResult = [];
	public $maxDate;
	public $minDate;
	public $currentDate;
	public $succeedDays;
	private $numOfDays = 5;

	public function __construct($db){
		$this->db_conn = $db;
		$this->currentDate = date('Y-m-d');
		$this->succeedDays = date('Y-m-d', strtotime($this->currentDate .' +7 day'));
	}
	function browseExpiry(){
		$sql = "SELECT item_expiry.ItemID, item_expiry.quantity, item_expiry.expiration,item_expiry.receiveDate, item.CommonName, item.GenericName from (item_expiry INNER JOIN ON item item_expiry.ItemID = item.ItemID) WHERE item_expiry.expiration >= :start AND item_expiry.expiration <= :endd";
		$item  = $this->db_conn->prepare($sql);
		try{
			$item->bindParam(":start", $this->minDate,PDO::PARAM_STR);
			$item->bindParam(":endd", $this->maxDate,PDO::PARAM_STR);
			if($item->execute()){
				$row = $item->fetchAll(PDO::FETCH_ASSOC);
				if(count($row) > 0 ){
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
	function showAboutToExpire(){
		$this->currentDate = date('Y-m-d', strtotime($this->currentDate .' +1 day'));
		$sql = "SELECT item_expiry.ItemID, item_expiry.quantity, item_expiry.expiration,item_expiry.receivedDate, item.CommonName, item.GenericName from (item_expiry INNER JOIN item ON  item.ItemID = item_expiry.ItemID ) WHERE item_expiry.expiration >= :currendt AND item_expiry.expiration <= :nexxt";
		$item  = $this->db_conn->prepare($sql);
		try{
			$item->bindParam(":currendt", $this->currentDate,PDO::PARAM_STR);
			$item->bindParam(":nexxt", $this->succeedDays,PDO::PARAM_STR);
			if($item->execute()){
				$row = $item->fetchAll(PDO::FETCH_ASSOC);
					$this->queryResult = $row;
				
					return true;
				
			}
		}catch(PDOException $err){
			echo $err;
			return false;
		}
		return false;

	}
	function showTodayExpire(){
		$sql = "SELECT item_expiry.ItemID, item_expiry.quantity, item_expiry.expiration,item_expiry.receivedDate, item.CommonName, item.GenericName from (item_expiry INNER JOIN item ON  item_expiry.ItemID = item.ItemID) WHERE item_expiry.expiration = :currendt";
		$item  = $this->db_conn->prepare($sql);
		try{
			$item->bindParam(":currendt", $this->currentDate,PDO::PARAM_STR);
			if($item->execute()){
				$row = $item->fetchAll(PDO::FETCH_ASSOC);
					$this->queryResult = $row;
					return true;
			}
		}catch(PDOException $err){
			echo $err;
			return false;
		}
		return false;
	}
}
