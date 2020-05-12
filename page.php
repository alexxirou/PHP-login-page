<?php
	if(session_id() == ''){
    session_start();
 	}
	include('login.php');
	require('connection.php');
	ini_set('display_errors', 1);
	ini_set('display_startup_errors', 1);
	error_reporting(E_ALL);
	//echo $_SESSION['connected'];
	if(!isset($_COOKIE['connection']) || $_COOKIE['connection'] == false){ //if cookie is set no need to verify uuid with the database
		try {
			$conn4 = new PDO("mysql:host=$servername;dbname=$db", $username, $password); //checking with database to make sure user has logged in
			$conn4->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			$sql="SELECT uuid_field FROM LOGIN WHERE uuid_field ='".$_SESSION['connected']."';"; //check if the uuid matches the session info
			$sql=$conn4->prepare($sql);
			$sql->execute();

			if ($sql->rowCount() == 0) {

		  	echo "<script> window.location='index.php';</script>";
				exit;

			}

			else {
				if(isset($_SESSION['timeout'])){ //if cookies are disabled making sure session is cleared on new login
					unset($_SESSION['timeout']);
	 			}
 			}
			$conn4=null;
		}
		catch(PDOException $e) {

			echo $e;
		}
	}

	if(isset($_COOKIE['cookie-box']) && $_COOKIE['cookie-box'] == true){ //check if cookie consent is accepted
		if(!isset($_COOKIE['connection']) || $_COOKIE['connection'] == false){ // if no  cookie or expired the user will be logged out on refresh or when he leaves the page

			if(isset($_SESSION['connected'])){
			unset($_SESSION['connected']);

			}
			$_SESSION['message']="déconnecté";
			session_write_close();

			echo "<script> window.location='index.php';</script>";
			exit();

			}
		}

		else{
			if(!isset($_SESSION['timeout'])){ //if cookie consent is rejected use session to track time on login

				$_SESSION['timeout']=strtotime('+15 minutes', time()); //same as with cookie but using session
			}

			if(time()>$_SESSION['timeout']){
				if(isset($_SESSION['connected'])){
					unset($_SESSION['connected']);

				}
			$_SESSION['message']="déconnecté";
			session_write_close();

			echo "<script> window.location='index.php';</script>";
			exit();
		}
	}


?>

<html>
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width,initial-scale=1.0">
		<link rel="stylesheet" href="http://code.jquery.com/mobile/1.2.0/jquery.mobile-1.2.0.min.css" >
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
		<link rel="stylesheet" href="slam1.css">
	</head>
	<body>
		<noscript>
			<h1>Nous sommes désolés, mais la page de démarrage ne fonctionne pas correctement sans l'activation de JavaScript. S'il vous plait, activez le pour continuer.</h1>
		</noscript>
		<div class="time">
			<p id=timer></p>
		</div>
		<div class="logout">
			<form action="logout.php" method="post">
					<input name="return" type="hidden" value="<?php echo urlencode($_SERVER["PHP_SELF"]);?>" />
					<input type="submit" value="Déconnecter" />
			</form>
		</div>
		<div class="Container3">
			<div class="form-group">
				<div class= "subcnt">
					<form method="post" id="Couleur" action="" >
    				<label>Couleur: </label>
    				<select name="Couleur" onchange='submit()'>
    					<option value="All">Tout</option>
							<?php
							try {
	        		$conn3 = new PDO("mysql:host=$servername;dbname=$db", $username, $password);
	        		$conn3->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    					$query="SELECT DISTINCT idCouleur,nomCouleur  FROM COULEUR  ORDER BY idCouleur;";
    					$result = $conn3->query($query);
    	    		foreach ($result as $row) {
    	        	if($row['idCouleur'] == $_POST['Couleur']){
    	            	echo '<option selected value="' . $row['idCouleur'] .'">'. $row['nomCouleur'] . '</option>';
    	        	}
								else{
    	               echo '<option value="' . $row['idCouleur'] .'">'. $row['nomCouleur'] . '</option>';
    	        	}

    	    		}



				  		}

							catch(PDOException $e){

			    			echo "<script type= 'text/javascript'>alert('Connection faillie.');</script>";
								echo "<script> window.location='index.php';</script>";
				  		}

    				?>

   					</select>
					</div>
					<div class="subcont">
						<label>Cepage: </label>
						<select name="Cepage" onchange='submit()'>
							<option value="All">Tout</option>
							<?php
							try {
								$conn3 = new PDO("mysql:host=$servername;dbname=$db", $username, $password);
								$conn3->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
								$query="SELECT DISTINCT idCepage,nomCepage  FROM CEPAGE  ORDER BY idCepage;"; //query that fills the dynamic drop down bar
								$result = $conn3->query($query);
								foreach ($result as $row) {
									if($row['idCepage'] == $_POST['Cepage']){
										echo '<option selected value="' . $row['idCepage'] .'">'. $row['nomCepage'] . '</option>';
									}
									else{
										echo '<option value="' . $row['idCepage'] .'">'. $row['nomCepage'] . '</option>';
										}

									}



								}

								catch(PDOException $e){

									echo "<script type= 'text/javascript'>alert('Connection faillie.');</script>";
									echo "<script> window.location='index.php';</script>";
								}

								?>

							</select>
						</form>
					</div>
				</div>
			</div>
	<?php

		ini_set('display_errors', 1);
		ini_set('display_startup_errors', 1);
		error_reporting(E_ALL);
  	if ($_SERVER["REQUEST_METHOD"] == "POST") {
			try {
				$conn2 = new PDO("mysql:host=$servername;dbname=$db", $username, $password);
				$conn2->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    		if (!empty($_POST['Couleur']) && !empty($_POST['Cepage'])) { //avoid errors on refresh
        		if( $_POST['Couleur']=="All" &&  $_POST['Cepage']=="All") {
						$query = "SELECT * FROM VIN";

          	}
			 	elseif ( $_POST['Couleur']=="All" &&  $_POST['Cepage']!="All" ) {
			 			$query = "SELECT nomVin,millesime,degresAlcool FROM VIN INNER JOIN CEPAGE ON fk_idCepage=idCepage WHERE idCepage='".$_POST['Cepage']."';";

			 			}

				elseif ( $_POST['Couleur']!="All" &&  $_POST['Cepage']=="All" ) {
			 			$query = "SELECT nomVin,millesime,degresAlcool FROM VIN INNER JOIN COULEUR ON fk_idCouleur=idCouleur WHERE idCouleur='".$_POST['Couleur']."';";

			 		}

        else {
        		$query = "SELECT nomVin,millesime,degresAlcool FROM VIN INNER JOIN COULEUR ON fk_idCouleur=idCouleur INNER JOIN CEPAGE ON fk_idCepage=idCepage WHERE idCouleur='".$_POST['Couleur']."' AND idCepage='".$_POST['Cepage']."';";

				}
				$result = $conn2->query($query);
				$rows = $result->fetchAll(PDO::FETCH_ASSOC);

				foreach ($rows as $row) {
					echo "<div class='Container'>"."<br>".$row['nomVin']."<br>"."Année: ".$row['millesime']."<br>"."Degrés: ".$row['degresAlcool']."%"."</div>";
				}

    	}





    	$conn2 =null;
			}
			catch(PDOException $e){

				echo "<script type= 'text/javascript'>alert('Une erreur générale s'est produite');</script>";
				echo "<script> window.location='index.php';</script>";
			}
		}
	?>
		<script>
			function submit(){
				document.getElementById("Couleur").submit //function to get values from both forms
			}
			var IDLE_TIMEOUT = 120; //seconds
						 var _idleSecondsCounter = 0;
						 document.onclick = function () {
								 _idleSecondsCounter = 0;

						 };

						 document.onkeypress = function () {
								 _idleSecondsCounter = 0;
								 ;
						 };
						 window.setInterval(CheckIdleTime, 1000);
						 function CheckIdleTime() {  //if two minutes without click or key press logout function
								 _idleSecondsCounter++;
								 if (_idleSecondsCounter >= IDLE_TIMEOUT) {


	 							 alert("déconnecté à cause d' inactivité ");

									try{location.replace('logout.php');}
									catch(e) {window.location ='logout.php';}
						 }
					 }
			var timeoutHandle;
			function countdown(minutes, seconds) { //function for on screen timer
							 function tick() {
									 var counter = document.getElementById("timer");
									 counter.innerHTML =
											 "Temps jusqu'à déconnexion à cause d'inactivité: "+minutes.toString() + ":" + (seconds < 10 ? "0" : "") + String(seconds);
									 seconds--;
									 document.onclick = function () {
											 seconds = 0;
											 minutes=2;
										 };
										 document.onkeypress = function () {
												 seconds = 0;
												 minutes=2;
											 };
									 if (seconds >= 0) {
											 timeoutHandle = setTimeout(tick, 1000);
									 } else {
											 if (minutes >= 1) {
													 // countdown(mins-1);   never reach “00″ issue solved:Contributed by Victor Streithorst
													 setTimeout(function () {
															 countdown(minutes - 1, 59);
													 }, 1000);
											 }
									 }
							 	}
							 	tick();
				}

					 window.onload=countdown(2, 00);
		</script>

	</body>
</html>
