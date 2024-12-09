
<?php
session_start();
include("includes/dbcon.php");
date_default_timezone_set('Asia/Manila');
$ldate=date( 'd-m-Y h:i:s A', time () );
$email_add=$_SESSION['email'];
$sql="UPDATE userlog  SET logout=:ldate WHERE userEmail = '$email_add' ORDER BY log_id DESC LIMIT 1";
$query=$dbh->prepare($sql);
$query->bindParam(':ldate',$ldate,PDO::PARAM_STR);
$query->execute();
$_SESSION['errmsg']="You have successfully logout";
unset($_SESSION['cpmsaid']);
session_destroy(); // destroy session
header("location:index.php"); 
?>