<?php
if(session_id() == ''){
      session_start();
   }
require('connection.php');
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST["InputPassword1"])) { //avoids errors on refresh
     // The request is using the POST method
     $login1=trim($_POST["InputLogin"]);
     $login1=strtolower($login1);


     if(($login1== null)|| ($_POST["InputPassword1"]== null) || ($_POST["InputPassword2"]== null)){ //checks if all forms are filled
       $_SESSION['message']="S'il vous plait de remplir tout les champs.";
       echo "<script type= 'text/javascript'>alert('S'il vous plait de remplir tout les champs.');</script>";
       echo "<script> window.location='index.php';</script>";
       exit();
     }

     elseif($_POST["InputPassword1"]!=$_POST["InputPassword2"]){ //checks that passwords match
       $_SESSION['message']="Les mots de passe saissis ne sont pas les mêmes.";
       echo "<script type= 'text/javascript'>alert('Les mots de passe saissis ne sont pas les mêmes.');</script>";
       echo "<script> window.location='index.php';</script>";
       exit();
     }
     else{
        $pass1= crypt($_POST["InputPassword1"],'$2a$07$usesomesillystringforsalt$'); //encrypts password
        try {
          $conn = new PDO("mysql:host=$servername;dbname=$db", $username, $password);
          $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            if($_SERVER["REQUEST_METHOD"] == "POST") {
                $sql = "SELECT nomLogin, uuid_field FROM LOGIN WHERE nomLogin = '".($login1)."'  AND passWord = '".($pass1)."';"; // query to retrive a user matching the  login details
                $query = $conn->prepare($sql);
                $query->execute(array($login1,$pass1));

                if ($query->rowCount() == 1) { //if a row exists
                    $result = $query->fetchColumn(1);
                    unset($_SESSION['message']);
                    echo "<script type= 'text/javascript'>alert('Connecté!');</script>";
                    $_SESSION['connected']=$result;
                    if(isset($_COOKIE['cookie-box']) && $_COOKIE['cookie-box'] == true){
                      setcookie("connection", true, time() + (60 * 15)); //if conscent was accepted creates a cookie that keeps the user connected for 15 minutes
                    };
                    echo "<script> window.location='page.php';</script>"; //redirect to the product page
                    exit();
                }

                else {
                  $_SESSION['message']="Mot de passe ou Nom Utilisateur faux.";
                    echo "<script type= 'text/javascript'>alert('Mot de passe ou Nom Utilisateur faux.');</script>";
                    echo "<script> window.location='index.php';</script>";
                    exit();
                }
            }
            $conn=NULL;
          }
        catch(PDOException $e){
            echo "<script type= 'text/javascript'>alert('Connection faillie.');</script>";
            echo "<script> window.location='index.php';</script>";
            exit();
            }
        }
      }
