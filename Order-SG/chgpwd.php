<?php 

session_start();
require_once('./../core/ctc_init.php'); // add by CTC

if(isset($_SESSION['cusno']))
{       
	if($_SESSION['redir']=='Order-SG'){
		$_SESSION['cusno'];
		$_SESSION['cusnm'];
		$_SESSION['redir'];
		$_SESSION['type'];
		$_SESSION['com'];
		$_SESSION['user'];
		$_SESSION['alias'];
		$_SESSION['tablename'];
    	$_SESSION['custype'];
		$_SESSION['dealer'];
		$_SESSION['group'];
		$cusno=	$_SESSION['cusno'];
		$cusnm=	$_SESSION['cusnm'];
		$alias=$_SESSION['alias'];
		$table=$_SESSION['tablename'];
		$type=$_SESSION['type'];
		$custype=$_SESSION['custype'];
		$user=$_SESSION['user'];
		$dealer=$_SESSION['dealer'];
		$group=$_SESSION['group'];
		$owner_comp = ctc_get_session_comp(); // add by CTC
	 }else{
		echo "<script> document.location.href='../".redir."'; </script>";
	 }
}else{	
	header("Location:../login.php");
}
require('../db/conn.inc');
$query="select * from userid where trim(cusno) = '$cusno' and Owner_Comp='$owner_comp' ";
$sql=mysqli_query($msqlcon,$query);		
$hasil = mysqli_fetch_array ($sql);
if($hasil){
	$password=strtoupper($hasil['Password']);
}

if (trim($_POST['OldPassword']) == '') {
	$error[] = '* Old Password should be filled';
}
if (trim($_POST['ReoldPassword']) == '') {
	$error[] = '* Confirmed Old Password should be filled';
}

if (trim(strtoupper($_POST['ReoldPassword'])) != trim($password)) {
	$error[] = '* Your old password is wrong ';
}

if (trim($_POST['NewPassword']) == '') {
	$error[] = '* New Password should be filled';

}

if (strlen($_POST['NewPassword']) <9) {
	$error[] = '* Your password must be between 9 and 40 characters ';
}

//dan seterusnya

if ($error) {
	echo '<b>Error</b>: <br />'.implode('<br />', $error);
} else {
  
	$newpwd=trim($_POST['NewPassword']);  

	 $query="update userid set Password='$newpwd' where trim(cusno) = '$cusno' and Owner_Comp='$owner_comp' ";
	 mysqli_query($msqlcon,$query);
	// echo $query;
    //$_SESSION['password']=$newpwd;  
     /**$issubject="Dealer Ordering System - New Password";
	 $ismemo="\n\n your password has been change to :".$newpwd;
     mail($mail, $issubject, $ismemo);**/ 
     //echo $query;   
     

	
				
}
	


die();
?>