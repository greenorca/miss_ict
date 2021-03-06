<?php
class wiss_db{

function __construct(){
	$config = parse_ini_file($_SERVER["DOCUMENT_ROOT"].'/miss_ict.ini.php');
	//var_dump($config);
	// Create connection	
	$this->conn = new mysqli($config['host'], $config['user'], $config['pass'], $config['database']);

	// Check connection
	if ($this->conn->connect_error) {
    	die("Connection failed: " . $this->conn->connect_error);
	}
	$this->conn->set_charset("utf8");
}

/**
updates $col in row with $PK with $new_val, returns false if that failed
*/
function updateModulTable($pk, $col, $new_value) {
	$sql = "UPDATE ict_module SET ".$col."='".$new_value."' WHERE modul_nr='".$pk."';";
	error_log('Info from updateModuleTable: '.$sql);
	if ($this->conn->query($sql)===TRUE){
		$this->conn->commit();
		return "OK";
	}
	return $this->conn->error;	
}


function checkCredentials($user, $pwd){
	$sqlx = "SELECT COUNT(*) FROM miss_users WHERE username='".$this->quoteInput($user)."' AND passwd=PASSWORD('".$this->quoteInput($pwd)."');";
	$result = array();
	$myresult = $this->conn->query($sqlx);
	try{
		$row = $myresult->fetch_assoc();
		//var_dump($row);
		return ($row['COUNT(*)']=='1');
	} catch(Exception $e){
		
	}
	return false;
}

/* eliminate wierd user input stuff */
function quoteInput($value){
	return mysqli_real_escape_string($this->conn,$value);
}

/* 
returns a list of all modules + informations with no parameters
returns just the information for a given module $id or even specific attributes ($cols) e.g "modul_nr, modul_name, fachschaft.id as id_fs, fachschaft, bereich, semester, lbv, lessons_ifz, lessons_uifz" 
*/
function getModuleInfo($id="", $cols="modul_nr, modul_name, fachschaft.id as id_fs, fachschaft, bereich, semester, lbv, lessons_ifz, lessons_uifz, CHAR_LENGTH(todo) as has_todo"){
	if ($id!==""){
		$id = " WHERE modul_nr='".$this->quoteInput($id)."'";
	}
	$sqlx = "SELECT ".$cols." FROM ict_module JOIN fachschaft ON ict_module.id_fachschaft=fachschaft.id ".$id." ORDER BY modul_nr;;";
	error_log('Info from getModuleInfo: '.$sqlx);
	$result = array();
	$myresult = $this->conn->query($sqlx);
	while($row = $myresult->fetch_assoc()) {
		$result[] = $row;
	}
	return $result;
}

/**
 returns list of fachschaft plus id
*/ 
function getFachschaftList(){
	$sqlx = "SELECT id, fachschaft from fachschaft;";
	$result = array();
	$myresult = $this->conn->query($sqlx);
	while($row = $myresult->fetch_assoc()) {
		$result[] = $row;
	}
	return $result;
}

/**
 fetch hanoks+todos (html) from database for given module
*/
function getHanok($id){
	$sqlx = "SELECT modul_nr, hanoks, todo FROM ict_module WHERE modul_nr='".$this->quoteInput($id)."';";
	error_log('Info from getHanok: '.$id."...".$sqlx);
	$result = array();
	$myresult = $this->conn->query($sqlx);
	while($row = $myresult->fetch_assoc()) {
		$result[] = $row;
	}
	return json_encode($result);
}

/**
returns required modules for $id module
*/
function getRequiredModules($id){
	$sqlx = "SELECT id_module, GROUP_CONCAT(id_required) AS id_required FROM module_deps WHERE id_module='".$this->quoteInput($id)."' GROUP BY id_module;";
	error_log('Info from getRequiredModules: '.$id."...".$sqlx);
	$result = array();
	$myresult = $this->conn->query($sqlx);
	while($row = $myresult->fetch_assoc()) {
		$result[] = $row['id_required'];
	}
	return $result[0];
}

}
?>
