<?php
if(session_id() == ''){
    session_start();
 }
if(isset($_SESSION['message'])){
  echo '<p class="message">'.$_SESSION['message'].'</p>'; //show deconnection message
  unset($_SESSION['message']);
  unset($_SESSION['timeout']); //session that tracks maximum login time
  session_write_close();


}


 ?>

<html lang="fr">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,initial-scale=1.0">
    <link rel="stylesheet" href="http://code.jquery.com/mobile/1.2.0/jquery.mobile-1.2.0.min.css" >
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/cookie-consent-box@2.3.1/dist/cookie-consent-box.min.css" />
    <script defer src="https://cdn.jsdelivr.net/npm/cookie-consent-box@2.3.1/dist/cookie-consent-box.min.js"></script>
    <link rel="stylesheet" href="slam1.css">


    <title>Enrgistrement</title>
  </head>
  <body>
    <noscript>
      <h1>Nous sommes désolés, mais la page de démarrage ne fonctionne pas correctement sans l'activation de JavaScript. S'il vous plait, activez le pour continuer.</h1>
    </noscript>


    <div class="container">
      <div>
        <form  class= "form1" action="login.php" method="post">
        <label><h4>Login</h4></label>
            <div class="form-group">
              <label for="exampleInputEmail1">Username</label>
              <input type="login" class="form-control" id="InputLogin"  name="InputLogin"aria-describedby="loginlHelp" placeholder="Nom d'utilisateur">
              <small id="login" class="form-text text-muted">Ne partagez jamais votre nom d'utilisateur avec quelqu'un d'autre.</small>
            </div>
            <div class="form-group">
              <label for="exampleInputPassword1">Password</label>
              <input type="password" class="form-control" id="InputPassword1" name="InputPassword1" placeholder="Mot de passe">
              <small id="password" class="form-text text-muted">Ne partagez jamais votre mot de passe avec quelqu'un d'autre.</small>
              </div>
              <div class="form-group">
                <label for="exampleInputPassword1">Verifier Password</label>
                <input type="password" class="form-control" id="InputPassword2" name="InputPassword2" placeholder="Verification mot de passe">
                <small id="Passhelp" class="form-text text-muted">Saisissez le mot de passe à nouveau.</small>
              </div>
                <button type="submit" id="button2" class="btn btn-primary">Submit</button>
            </form>
            <button class="btn btn-primary" id="myButton">Môt de passe oublié</button>
      </div>

    	<form  class= "form2" action="index.php" method="post">
   	 	     <label><h4>Enregistrer</h4></h4></label>
  	 		     <div class="form-group">
    			        <label for="exampleInputEmail1">Username</label>
    			        <input type="login" class="form-control" id="InputLogin"  name="InputLogin"aria-describedby="loginlHelp" placeholder="Nom utilisateur">
    			        <small id="login" class="form-text text-muted">Ne partagez jamais votre nom d'utilisateur avec quelqu'un d'autre.</small>
  			     </div>
  			     <div class="form-group">
    			        <label for="exampleInputPassword1">Password</label>
    			        <input type="password" class="form-control" id="InputPassword1" name="InputPassword1" placeholder="Mot de passe">
    			        <small id="password" class="form-text text-muted">Ne partagez jamais votre mot de passe avec quelqu'un d'autre.</small>
  			     </div>
  			     <div class="form-group">
    		         <label for="exampleInputPassword2">Verifier Password</label>
    		         <input type="password" class="form-control" id="InputPassword2" name="InputPassword2" placeholder="Verification mot de passe">
  			     </div>
  			     <div class="form-group">
    			        <label for="exampleInputEmail1">Email address</label>
    			        <input type="email" class="form-control" id="InputEmail1" name="InputEmail1" aria-describedby="emailHelp" placeholder="Adresse email">
    			        <small id="emailHelp" class="form-text text-muted">Nous ne partagerons jamais votre e-mail avec quelqu'un d'autre.</small>
  			     </div>
  			     <button type="submit" class="btn btn-primary">Submit</button>
		   </form>
    </div>
    <?php
        ini_set('display_errors', 1);
        ini_set('display_startup_errors', 1);
        error_reporting(E_ALL);
        require ('connection.php');

        if($_SERVER["REQUEST_METHOD"] == "POST") {

          $login1=trim($_POST["InputLogin"]);
          $email=trim($_POST["InputEmail1"]);
          $login1=strtolower($login1);
          $email=strtolower($email);

          if(($login1== null) || ($_POST["InputPassword1"]== null) || ($_POST["InputPassword2"]== null) || ($email== null)){ //checks that forms are filled
            echo "<script type= 'text/javascript'>alert('S il vous plait de remplir tout les champs.');</script>";
            echo "<script> window.location='index.php';</script>";
            }

            elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) { //check email format
              echo "<script type= 'text/javasript'>alert('Invalid email format');</script>";
              echo "<script> window.location='index.php';</script>";
            }

            elseif (strlen($_POST["InputPassword1"])<=5){ //check password is atleast 5 characters long
              echo "<script type= 'text/javascript'>alert('LE mot de pass doit être plus de 6 characters.');</script>";
              echo "<script> window.location='index.php';</script>";
            }

            elseif ($_POST["InputPassword1"]!=$_POST["InputPassword2"]){ //check passwords match
              echo "<script type= 'text/javascript'>alert('Mots de passe saisis ne sont pas les mêmes.');</script>";
              echo "<script> window.location='index.php';</script>";
            }
            else {
              $pass1= crypt($_POST["InputPassword1"],'$2a$07$usesomesillystringforsalt$'); //encrupts password using $2a$07$ salt
              try {
                $conn = new PDO("mysql:host=$servername;dbname=$db", $username, $password); //connection to db
                $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                $sql = "INSERT INTO LOGIN(eMail,nomLogin,passWord) VALUES('".($email)."','".($login1)."','".($pass1)."');"; //query to insert values to the db
                    if ($conn->query($sql)) {
                        //set_include_path ('/usr/share/php');
                        //include 'Mail.php';
                        $headers="From: admin@cavavin.lan"; //if server mail is set sends a mail using the email given
                        $to =$email;
                        $subject = " Bienvenue sur vote Cave à Vin!!!";
                        $body = " Bienvenu. Votre mot de passe est: ".$_POST["InputPassword1"];
                        $result=mail($to,$subject,$body,$headers);

                        if (!$result) {
                          echo "<script type= 'text/javascript'>alert('Utilisateur creé mais il y un problème avec l e-mail.');</script>";
                          echo "<script> window.location='index.php';</script>";
                          }
                        else {
                          echo "<script type='text/javasript'>alert('Utilisateur creé.');</script>";
                          echo "<script> window.location='index.php';</script>";
                    }
                  }
                    else {
                        echo "<script type= 'text/javascript'>alert( 'Réessayez.');</script>";
                        echo "<script> window.location='index.php';</script>";
                        }

                $conn = null;
                exit;
                }

                catch(PDOException $e){
                  if(($e->getCode()==23000) && (isset($email))){ //checks if email was filled and exists in database
                    $_SESSION['message']="E-mail ou nom utilisateur existe déja.";
                    echo "<script type= 'text/javascript'>alert('E-mail existe déja.');</script>";
                    echo "<script> window.location='index.php';</script>";


                    }
                    else{
                    echo "<script type= 'text/javascript'>alert('Connection faillie.');</script>";
                    echo "<script> window.location='index.php';</script>";
                  }

                  }
                }
              }
        ?>

        <script type="text/javascript">
          document.getElementById("myButton").onclick = function () { //button that leads to page to reset password
            location.href = "oublie.php";
        };
        CookieBoxConfig = { backgroundColor: '#ec008c', language: 'fr', cookieExpireInDays: '30' } //cookied consent form

        </script>
    </body>
</html>
