<?php
require('db/conn.inc');
require_once('../core/ctc_init.php');
$user = $_SESSION['user'];
$comp = ctc_get_session_comp();

$query="select sessid from userid where trim(UserName) = '$user' and Owner_Comp='$comp' ";
$result = mysqli_query($msqlcon,$query);
$hasil = mysqli_fetch_array ($result);
if(trim($hasil['sessid'])!=trim(session_id())){
	$error="<script> document.location.href='main.php'; </script>";
}


?>