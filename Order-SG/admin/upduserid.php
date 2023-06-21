<?php 

session_start();
require_once('./../../core/ctc_init.php'); // add by CTC

if(isset($_SESSION['cusno']))
{       
	if($_SESSION['redir']=='Order-SG'){
		$cusno=	$_SESSION['cusno'];
		$cusnm=	$_SESSION['cusnm'];
		$password=$_SESSION['password'];
		$alias=$_SESSION['alias'];
		$table=$_SESSION['tablename'];
		$type=$_SESSION['type'];
		$custype=$_SESSION['custype'];
		$user=$_SESSION['user'];
		$dealer=$_SESSION['dealer'];
		$group=$_SESSION['group'];
		$redir=$_SESSION['redir'];
		$comp = ctc_get_session_comp(); // add by CTC
		if($type!='a'){
			header("Location:../main.php");
		}
	 }else{
		echo "<script> document.location.href='../../".redir."'; </script>";
	 }
}else{	
	header("Location:../login.php");
}

$vcusno=trim($_POST['vcusno']);
$vuserid=trim($_POST['vuserid']);
$vtype=strtolower(trim($_POST['vtype']));
$vpassword=trim($_POST['vpassword']);
$vcompany=trim($_POST['vcompany']);
$vaction=trim($_POST['vaction']);
$xaction=trim($_GET['action']);
if($xaction!=""){
	$vaction=$xaction;
	$vuserid=trim($_GET['id']);
}

require('../db/conn.inc');

/**echo $vcusno."<br>";
echo $vcusnm."<br>";
echo $vtype."<br>";
echo $valias."<br>";
echo $vpwd."<br>";**/
$err="";
if($vcusno==""){
	$err="customer no";
}
if($vuserid==""){
	if($err==""){
			$err="user id";
	}else{
			$err=$err.", user id";
	}
}
if (strlen($vpassword) <9 && strtoupper($vaction) != 'DELETE') {
	$err = '* Your password must be between 9 and 40 characters ';
}else{
	switch(strtoupper($vaction)){
		case "EDIT":
			$query="update userid set Cusno='$vcusno', Type='$vtype', Password= '$vpassword', COM='$vcompany', Redir='$redir' where trim(UserName) = '$vuserid' and Owner_Comp = '$comp'";
			mysqli_query($msqlcon,$query);
			$logindb=mysqli_select_db ($lgdb, $conn);
			mysqli_query($msqlcon,$query);
			
			break;
		case "ADD":
			$query="insert into userid(UserName, Password, Cusno, Type, COM, Redir, Owner_Comp) values('$vuserid', '$vpassword', '$vcusno', '$vtype', '$vcompany','$redir','$comp' )";
			//echo $query;
			mysqli_query($msqlcon,$query);
			$logindb=mysqli_select_db ($lgdb, $conn);
			mysqli_query($msqlcon,$query);
				
			break;	
		case "DELETE":
			$err="";
			$query="delete from  userid where trim(UserName) = '$vuserid' and Owner_Comp='$comp'";
			//echo $query;
			$hasil=mysqli_query($msqlcon,$query);
			//echo "hasil:" . $hasil;
			$logindb=mysqli_select_db ($lgdb, $conn);
			$query="delete from  userid where trim(UserName) = '$vuserid' and Owner_Comp='$comp'";
			mysqli_query($msqlcon,$query);
		break;	
	}
}

//echo  $query;
if($err==""){
	echo "<script> document.location.href='mainUsrAdm.php'; </script>";
}else{
    echo $err . " should be filled";	
}

?>
