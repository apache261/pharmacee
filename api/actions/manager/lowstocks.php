<?php
require_once('../../config/http_header.php');
require_once('../../class/session_manager.php');
require_once('../../config/db_config.php');


if($_SERVER['REQUEST_METHOD'] == 'GET' && array_key_exists('min', $_GET)){

$database  = new Database();
	$db_con = $database->getDatabaseConnection();
	$items = new LowStocks($db_con);


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
}
http_response_code(500);
echo json_encode(array("Message"=> "Erdror"));
exit();


class LowStocks{

	private $db_conn;
	public $minStocks;
	public $queryResult;

	public function __construct($db){
		$this->db_conn = $db;
	}

	public function getLowStock(){
		$sql = "SELECT DISTINCT
		xx.ItemID,
		xx.CommonName,
		xx.GenericName,
		xx.Expiration,
		xx.Manufacturer,
		bb.totalin as totalin,
		bb.totalout as totalout,
		bb.maxBal as remaining
		from ((item xx
		INNER JOIN (SELECT ItemID, sum(QuantityIn) as totalin,sum(QuantityOut) as totalout ,max(balance) as maxBal from transaction GROUP by ItemID) bb on bb.itemID =xx.ItemID AND bb.maxbal <= :low))";
		try{
			$item  = $this->db_conn->prepare($sql);
			$item->bindParam(":low", $this->minStocks,PDO::PARAM_INT);
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
}

// SELECT xx.ItemID,xx.Co  mmonName,bb.maxBal from item xx INNER JOIN (SELECT ItemID, max(balance) as maxBal from transaction GROUP by ItemID) bb on bb.itemID =xx.ItemID AND bb.maxbal <= 500