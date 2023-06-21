<? session_start() ?>
<?
//if (session_is_registered('cusno'))
if(isset($_SESSION['cusno']))
{       
	$_SESSION['cusno'];
	$_SESSION['cusnm'];
	$_SESSION['cusad1'];
	$_SESSION['cusad2'];
	$_SESSION['cusad3'];
	$_SESSION['type'];
	$_SESSION['zip'];
	$_SESSION['state'];
	$_SESSION['phone'];
	$_SESSION['password'];

	$cusno=	$_SESSION['cusno'];
	$cusnm=	$_SESSION['cusnm'];
	$cusad1=$_SESSION['cusad1'];
	$cusad2=$_SESSION['cusad2'];
	$cusad3=$_SESSION['cusad3'];
	$type=$_SESSION['type'];
	$zip=$_SESSION['zip'];
	$state=$_SESSION['state'];
	$phone=$_SESSION['phone'];
	$password=$_SESSION['password'];
  
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
