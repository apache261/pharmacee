<?php
require_once('../../class/session_manager.php');

class Validate{
	private $sess;

	public function __construct($token = "")
	{
		$this->sess = new ManageSession();
		$this->sess->jwt_token = $token;
		
	}
	public function isValid(){
		return $this->sess->validateJWTToken();
	}
}

