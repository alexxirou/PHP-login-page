<?php
if(session_id() == ''){
    session_start();
 }
 if(isset($_SESSION['oublie'])) {
   echo '<p class="message">'.$_SESSION['oublie'].'</p>'; //creates a session var for the password
   unset($_SESSION['oublie']);
}
 ?>

<html lang="fr">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,initial-scale=1.0">
    <link rel="stylesheet" href="http://code.jquery.com/mobile/1.2.0/jquery.mobile-1.2.0.min.css" >
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <link rel="stylesheet" href="slam1.css">


    <title>Récuperation Compte Utilisateur</title>
  </head>
  <body>
    <noscript>
      <h1>We're sorry but startpage doesn't work properly without JavaScript enabled. Please enable it to continue.</h1>
    </noscript>
    <div class="container">
    	<form  class= "form1" action="oublie.php" method="post">
    	<label><h4>Mettre votre Email</h4></label>
  	 		<div class="form-group">
    			<label for="exampleInputEmail1">Username</label>
    			<input type="email" class="form-control" id="InputLogin"  name="InputLogin"aria-describedby="loginlHelp" placeholder="Mettre votre Email">
    			<small id="email" class="form-text text-muted">Garder les identifients secrets</small>
    			</div>
    			<button type="submit" class="btn btn-primary">Submit</button>
  		</form>
  	</div>


    <?php
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
    require('connection.php');
    $permitted_chars = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ'; //chars for the password


    if($_SERVER["REQUEST_METHOD"] == "POST") {
      $login1=trim($_POST["InputLogin"]);
      $login1=strtolower($login1);

        if($login1 == null){
          echo "<script type='text/javascript'>alert('E-mail vide.');</script>";
          echo "<script> window.location='oublie.php';</script>";
        }
        else{
          try {
            $conn = new PDO("mysql:host=$servername;dbname=$db", $username, $password);
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            if($_SERVER["REQUEST_METHOD"] == "POST") {
                $sql = "SELECT eMail FROM LOGIN WHERE eMail = '".($login1)."';"; //check if email exists
                $query =$conn->prepare($sql);
                $query->execute(array($login1));

                if($query->rowCount() == 1) {
                    $pass=substr(str_shuffle($permitted_chars), 6, 16);

                    $pass2 = crypt($pass,'$2a$07$usesomesillystringforsalt$');
                    $sql2 = "UPDATE LOGIN SET passWord='".($pass2)."' WHERE eMail = '".($login1)."';"; //updates database with new pass

                    $conn->exec($sql2);
                    $_SESSION['oublie']=$pass; //fills the session var with the pass
                    echo "<script type= 'text/javascript'>alert('Mot de passe géneré!');</script>";
                    echo "<script> window.location='oublie.php';</script>";  //redirects to the page to avoid resetting the pass on refresh
                    exit;
                /*$headers="From: admin@cavavin.lan";
                $to = $result;
                $subject = "Votre mot de passe à été reinitialisé";
                $body = "Votre nouvel mot de passe est: ".$pass;
                mail($to,$subject,$body,$headers);

                if (!mail($to,$subject,$body,$headers)) {
                  echo "<script type= 'text/javascript'>alert('il y a eu un problème avec l'envoi de l'e-mail');</script>";

                      }
                else {
                  echo "<script type='text/javasript'>alert('E-mail envoyé');</script>";
                }*/
                  }
                else{
                  $_SESSION['oublie']="E-mail n'existe pas.";
                  echo "<script type= 'text/javascript'>alert('Nom utilisateur ou E-mail n'existe pas.');</script>";
                  echo "<script> window.location='oublie.php';</script>";
                }
                }
              }
              catch(PDOException $e){
                    echo "<script type= 'text/javascript'>alert('Connection est faillie.');</script>";
                    echo "<script> window.location='oublie.php';</script>";
                    }
            }
        }
    ?>
