<?php
	session_start(); 
		if (isset($_SESSION['wiss_db']) 
			&& isset($_COOKIE[session_name()]) 
			&& $_COOKIE[session_name()]==session_id()
			){		
			//error_log('Info from ajax.php: in session');
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
			
			
		/* updates */
		elseif(isset($_POST['update'])){
					/* updating ict_modules_table */
					if (isset($_POST['modul_id']) && isset($_POST['col']) && isset($_POST['new_val'])) {
						//ensure valid column names
						switch ($_POST['col']){
							case 'id_fachschaft':
								
								break;
							case 'bereich': 
								if (in_array($_POST['new_val'], array('API','SYS'))) {
									echo $_SESSION['wiss_db']->updateModulTable($_POST['modul_id'],$_POST['col'] ,$_POST['new_val']);
								}
								else echo "invalid value range";
								break;
								
							case 'semester': 
								if (in_array($_POST['new_val'], array(1,2,3,4,5,6,7,8))) {
									echo $_SESSION['wiss_db']->updateModulTable($_POST['modul_id'],$_POST['col'] ,$_POST['new_val']);		
								}
								else echo "invalid value";								
								break;

							case 'lessons_uifz': echo "not implemented"; break;
							case 'lessions_ifz': echo "not implemented"; break;
							case 'hanoks': echo "not implemented"; break;
							case 'todo': echo $_SESSION['wiss_db']->updateModulTable($_POST['modul_id'],$_POST['col'] ,$_POST['new_val']); break;
							default: echo 'invalid request'; return;						
						}
					}
		
		} 
	}
	else {
		error_log('Info from ajax.php: invalid session');
	}
?>