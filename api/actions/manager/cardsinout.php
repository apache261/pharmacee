<?php
require_once('../../config/http_header.php');
require_once('../../class/session_manager.php');
require_once('../../config/db_config.php');


if($_SERVER['REQUEST_METHOD'] == 'GET'){

	$database  = new Database();
	$db_con = $database->getDatabaseConnection();
	$items = new Cards($db_con);


	$keyword= htmlspecialchars(strip_tags($_GET['keyword']));

	$items->target = $keyword;
	if($items->getCardStock()){
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
echo json_encode(array("Message"=> "Error"));
exit();


class Cards{
	private $db_conn;
	public $target;
	public $queryResult;

	public function __construct($db){
		$this->db_conn = $db;
	}
	public function getCardStock(){
		$sql = "SELECT xx.ItemID, xx.commonName, xx.genericName, date(vv.entry)as entry, vv.quantityIn, vv.quantityOut, vv.balance, vv.reason,zz.remaining from (item xx INNER JOIN (SELECT ItemID, quantityIN, entrydate as entry, quantityOut, balance, reason from transaction) vv on xx.itemID = :target AND xx.itemID = vv.itemID ) INNER JOIN (select itemID, max(balance) as remaining from transaction GROUP by itemID)zz on zz.itemID = vv.itemID ORDER BY xx.ItemID DESC, vv.entry ASC";
		try{
			$item  = $this->db_conn->prepare($sql);
			$item->bindParam(":target", $this->target,PDO::PARAM_INT);
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
// SELECT * from quantity WHERE date(entrydate) >= '2020-10-27' and date(entrydate) <= '2020-10-29'
// select quantity.ItemID,quantity.quantity,date(quantity.entrydate), '1' as status from quantity union all select deduct.ItemID, deduct.DeductQuantity,date(deduct.entrydate),'2' as status from deduct

// select * from (select ItemID,quantity,date(entrydate) as entry, '1' as status from quantity union all select ItemID, DeductQuantity,date(entrydate) as entry,'2' as status from deduct)a order by entry