<?php
	session_start();
	require_once('class/wiss_db.php');
	if ($mySqlClass==null){
		$mySqlClass = new wiss_db();
	}	
	$_SESSION['wiss_db']=$mySqlClass;

	if (isset($_POST['user']) && isset($_POST['cred'])){
		//sanitize inputs
		$us = $_SESSION['wiss_db']->quoteInput($_POST['user']);
		$pwd = $_SESSION['wiss_db']->quoteInput($_POST['cred']);
		
			//if ($us=="sven" && $pwd=="sven"){
			if ($_SESSION['wiss_db']->checkCredentials($us, $pwd)==true){
	        $_SESSION['login_user']="intern";
	    		$_SESSION['login_fail']=0;

	    }
	    else {
	    		$_SESSION['login_fail']=1;
	        unset($_SESSION['login_user']);         
	    }
	}	

	if (isset($_GET["logout"])){
    unset($_SESSION['login_user']);
    //unset($_SESSION['wiss_db']);
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
							<h4>Hinweis</h4>
							<p>Die ist ein Prototyp zur übersichtlichen Darstellung der ICT-Module im Rahmen der EFZ-Ausbildung Informatiker (API & SYS). Als solches sind die gelieferten Daten insbesondere für Fachschaften ausserhalb DB, API und WEB oft unvollständig. </p> 
							<p>Fehlende (WISS)-LBVs können mit Modulnummer an <a href="mailto:schirmer@green-orca.com">schirmer@green-orca.com</a> gesendet werden.</p>		
							<p>Die Darstellung folgt strikt dem Bauhaus-Prinzip: <i>Form follows function</i>. LBVs und Toolboxen sind von Word in HTML re-konvertiert, damit die Archivierbarkeit und Wartbarkeit sichergestellt ist.</p>			  
							<p><b>Benutzung: </b>Module auf der linken Seite sowie LBV und HANOKs auf der rechten Seite sind klickbar.</p>
							<p><i>Modulinformationssystem Schirmer - ICT</i></p>
							<p><i><a href="http://www.green-orca.com">www.green-orca.com</a></i></p>
							
					  </div>
					 <?php  		
    		}
    		else if(isset($_SESSION['login_fail']) && $_SESSION['login_fail']==1) {
					?><h4>Login failed</h4>
				<?php 
				} else {    			
    	?>
    	<h1>ICT - Modulinformationssystem</h1>
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
						   echo "<tr class='tr_modul'>";
						   echo "<td  class='text_center'>".$module['modul_nr']."</td>";
						   echo "<td>".$module['modul_name']."</td>";
						   echo "<td class='td_fachschaft fachschaft_".$module['id_fs']."'>".$module['fachschaft']."</td>";
						   echo "<td>".$module['bereich']."</td>";
						   echo "<td class='text_center'>".$module['semester']."</td>";
						   echo "</tr>" ;    	
        	}
        ?>
        </table>
      </div>
      <div class='col-md-6' id='right'>
        <div id="req_mods"></div>
        <div id="lbv"></div>
        <div id="hanoks"></div>
      </div>
      <?php } ?>
   </article>
   <script src="js/jquery.min.js"></script>
   <script src="js/bootstrap.min.js"></script>
 	<script src="js/miss_ict.js"></script>
  </body>
  </html>
