<?
//if (session_is_registered('cusno'))

require_once('../../language/Lang_Lib.php');
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
		$password=$_SESSION['password'];
		$alias=$_SESSION['alias'];
		$table=$_SESSION['tablename'];
		$type=$_SESSION['type'];
		$custype=$_SESSION['custype'];
		$user=$_SESSION['user'];
		//$dealer=$_SESSION['dealer'];
		$group=$_SESSION['group'];
	 }else{
		   echo "<script> document.location.href='../".redir."'; </script>";
	 }
}else{	
header("Location:../login.php");
}
?>


<?Php

/*
/////// Update your database login details here /////
$dbhost_name = "order.denso.com"; // Your host name 
$database = "ordering-sg";       // Your database name
$username = "root";            // Your login userid 
$password = "P@ssw0rD";            // Your password 
//////// End of database details of your server //////
*/

$dbhost_name = "order.denso.com"; // Your host name 
$database = "ordering-sg";       // Your database name
$username = "root";            // Your login userid 
$password = "P@ssw0rD";            // Your password 
//////// Do not Edit below /////////
try {
$dbo = new PDO('mysql:host='.$dbhost_name.';dbname='.$database, $username, $password);
} catch (PDOException $e) {
print "Error!: " . $e->getMessage() . "<br/>";
die();
}


?> 
