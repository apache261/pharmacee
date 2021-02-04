<?php
class Database{
	private $db_host ="localhost";
	private $db_name = "pharmacee";
	private $db_user_name = "root";
	private $db_password = "";
	private $db_conn;


	public function getDatabaseConnection(){
	try{
		$init = array(PDO::ATTR_EMULATE_PREPARES => false, PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION);
		$this->db_conn = new PDO('mysql:host='.$this->db_host.';dbname='.$this->db_name,$this->db_user_name,$this->db_password,$init);
		return $this->db_conn;
	}catch(PDOException $error){
		return null;
	}
}
	
}
