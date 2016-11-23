<?php
	if(!isset($_SESSION)) 
	{ 
    session_start(); 
	}
	if (isset($_SESSION['wiss_db'])){	
		error_log('Info from ajax.php: in session');
		include_once($_SERVER["DOCUMENT_ROOT"].'/class/wiss_db.php');
		//__autoload($_SERVER["DOCUMENT_ROOT"].'/class/wiss_db');
		$_SESSION['wiss_db']=new wiss_db();
		error_log('Info from ajax.phh: in db_session');
		/* look up hanok for module id given in POST request */
		if($_POST['hanok']) {
			echo $_SESSION['wiss_db']->getHanok($_POST['hanok']);
		}
		
		/* lookup required modules */
		elseif($_POST['required_mods']) {
			echo $_SESSION['wiss_db']->getRequiredModules($_POST['required_mods']);	
		}
	}
	else {
		error_log('Info from ajax.php: invalid session');
	}
?>