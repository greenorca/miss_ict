<?php

class MySQL_SSD{
	private $servername = "localhost";
	private $username = "sven";
	private $password = "sven";
	private $dbname = "benchmark_smart_ssd";

	function __construct(){
		// Create connection
		$this->conn = new mysqli($this->servername, $this->username, $this->password, $this->dbname);

		// Check connection
		if ($this->conn->connect_error) {
	    	die("Connection failed: " . $this->conn->connect_error);
		}
		$this->conn->set_charset("utf8");
	}

	function getStats(){
		$sqlx = "SELECT * FROM log WHERE dev_model LIKE '%DEAD%' OR time=(SELECT MAX(time) FROM log);";
		$result = array();
		$myresult = $this->conn->query($sqlx);
		while($row = $myresult->fetch_assoc()) {
			$result[] = $row;
		}
		return $result;
	}
}

 ?>
