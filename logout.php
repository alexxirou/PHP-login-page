<?php
session_start();
unset($_SESSION['connected']);
unset($_SESSION['message']);
session_write_close();
setcookie("connection", "", time() -10); //removes cookie
echo "<script> window.location='index.php';</script>";

die;
?>
