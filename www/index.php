<?php
	session_start();
	require_once('class/wiss_db.php');
	if (!isset($mySqlClass)){
		$mySqlClass = new wiss_db();
	}	
	$_SESSION['wiss_db']=$mySqlClass;

	if (isset($_POST['user']) && isset($_POST['cred'])){
		//sanitize inputs
		$us = $_SESSION['wiss_db']->quoteInput($_POST['user']);
		$pwd = $_SESSION['wiss_db']->quoteInput($_POST['cred']);
		
			if ($_SESSION['wiss_db']->checkCredentials($us, $pwd)==true){
	        $_SESSION['login_user']=$us;
	    		$_SESSION['login_fail']=0;
					setcookie(session_name(), session_id(),time()+3600);
	    }
	    else {
	    		$_SESSION['login_fail']=1;
	        unset($_SESSION['login_user']);         
	    }
	  }

	if (isset($_GET["logout"])){
		if (isset($_SESSION['wiss_db'])){
				unset($_SESSION['wiss_db']);
		}
		session_destroy() ;
	}
	header('Content-Type: text/html; charset=utf-8');
?>

<!DOCTYPE html>
  <html>
  <head>
    <title>MISS-ICT</title>
    <meta charset="UTF-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
		<link rel="stylesheet" href="css/bootstrap.css"/>
    <link rel="stylesheet" href="css/slimbox2.css"/>    
    <link rel="stylesheet" href="css/style.css"/>
    <link rel="stylesheet" href="css/easyeditor.css"/>
    
  </head>
  <body>
    <article class="container">
			<?php    
    		if (!isset($_SESSION['login_user'])){
					?>				
						<div>	
						<h1>Miss-ICT Login</h1>					  
					  <form action="." method="POST">
					  	<label>Username</label><input type="text" name="user"/>
					  	<label>Password</label><input type="password" name="cred"/>
					  	
					  	<input type="submit" value="Senden"/>
					  </form>
					  </div>
					  <div class="usage">

					  	<h4>Update 2016-12-15</h4>
					  	<p><i>Ab sofort können Modul-Spezialisierung (API/SYS)) und Semester-Informationen in der Tabelle links per Doppelklick bearbeitet werden. Abspeichern der aktuellen Zelle wird mit <b>ENTER</b> durchgeführt.Nach erfolgreichem Speicher wird das Feld grün hinterlegt.</i></p>
					  	<p><i>TODOs zu den jeweiligem Modul (rechts unter HANOKs) können ebenfalls per Doppelklick editiert werden. Dazu gibt es sogar einen einfachen WYSIWYG-Editor und einen <b>Save</b> Button.</i></p>
							<p><i><span class="has_todo">Rot gekennzeichnete Module</span> haben bereits TODOs.</i></p>
					  	
							<h4>Hinweis</h4>
							<p>Die ist ein Prototyp zur übersichtlichen Darstellung der ICT-Module im Rahmen der EFZ-Ausbildung Informatiker (API &amp; SYS). Als solches sind die gelieferten Daten insbesondere für Fachschaften ausserhalb DB, API und WEB oft unvollständig. </p> 
							<p>Fehlende (WISS)-LBVs können mit Modulnummer an <a href="mailto:schirmer@green-orca.com">schirmer@green-orca.com</a> gesendet werden.</p>		
							<p>Die Darstellung folgt strikt dem Bauhaus-Prinzip: <i>Form follows function</i>. LBVs und Toolboxen sind von Word in HTML re-konvertiert, damit die Archivierbarkeit und Wartbarkeit sichergestellt ist.</p>			  
							<p><b>Benutzung: </b>Module auf der linken Seite sowie LBV und HANOKs auf der rechten Seite sind klickbar.</p>
							<p><i><b>M</b>odul<b>I</b>nformations<b>S</b>ystem <b>S</b>chirmer - ICT</i></p>
							<p><i><a href="http://www.green-orca.com">www.green-orca.com</a></i></p>
							
					  </div>
					 <?php  		
    		}
    		else if(isset($_SESSION['login_fail']) && $_SESSION['login_fail']==1) {
					?><h4>Login failed</h4>
				<?php 
				} else {    			
    	?>
    	<h1>ICT - Modulinformationssystem
    		<a href="<?php echo $_SERVER['REQUEST_URI']; ?>?logout" >
    			<span title="Logout" style="color:red;font-size: 1.0em" class="glyphicon glyphicon-log-out"></span>
    		</a>
     	</h1>
      <div class='col-md-6' id='left'>
      	<table class="table table-condensed" id="t_modules">
      	<!--colgroup>
					<col width="7%">
					<col width="70%">
					<col width="30%">
					<col width="7%">
					<col width="6%">      	
      	</colgroup-->
      	<tr><th>Nr</th><th>Name</th>
      		<th><select id="s_fachschaft" name="s_fachschaft" style="width:100px;">
      			<option value="">Fachschaft</option>
      			<?php
      				foreach($mySqlClass->getFachschaftList() as $fachschaft){
								echo "<option value=".$fachschaft['id'].">".$fachschaft['fachschaft']."</optionn>";      				
      				}
      			?>
      		</select></th>
      		
      		<th>Spez</th>      		
      		<th>Sem</th>
      	</tr>
        <?php 
        	$modules = $mySqlClass->getModuleInfo();
        	foreach($modules as $module){
        			 $has_todo=($module['has_todo']=="NULL" || $module['has_todo']=="0" || $module['has_todo']=="4")?"":" has_todo";
						   echo "<tr class='tr_modul".$has_todo."' data-id=".$module['modul_nr'].">";
						   echo "<td  class='text_center'>".$module['modul_nr']."</td>";
						   echo "<td>".$module['modul_name']."</td>";
						   echo "<td class='td_fachschaft fachschaft_".$module['id_fs']."'>".$module['fachschaft']."</td>";
						   echo "<td class='td_edi' data-col='bereich'>".$module['bereich']."</td>";
						   echo "<td class='text_center td_edi' data-col='semester'>".$module['semester']."</td>";
						   echo "</tr>" ;   	
        	}
        ?>
        </table>
      </div>
      <div class='col-md-6' id='right'>
        <div id="req_mods"></div>
        <div id="lbv"></div>
        <div id="hanoks"></div>
        <div id="todo"></div>
      </div>
      <?php } ?>
   </article>
   <script src="js/jquery.min.js"></script>
   <script src="js/bootstrap.min.js"></script>
   <script src="js/miss_ict.js"></script>
   <script src="js/jquery.easyeditor.js"></script>
  </body>
  </html>
