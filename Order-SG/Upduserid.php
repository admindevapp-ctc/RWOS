<? session_start() ?>
<?
if(isset($_SESSION['cusno']))
{       
		$_SESSION['cusno'];
		$_SESSION['cusnm'];
		$_SESSION['password'];
		$_SESSION['alias'];
		$_SESSION['tablename'];
		$_SESSION['user'];
		$_SESSION['dealer'];
		$_SESSION['group'];
		$_SESSION['type'];
		$_SESSION['custype'];
		

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
	
}else{	
header("Location: login.php");
}
$vcusno=trim($_POST['vcusno']);
$vuserid=trim($_POST['vuserid']);
$vtype=strtolower(trim($_POST['vtype']));
$vpassword=trim($_POST['vpassword']);
$vaction=trim($_POST['vaction']);
$xaction=trim($_GET['action']);
if($xaction!=""){
	$vaction=$xaction;
	$vuserid=trim($_GET['id']);
}
	
require('db/conn.inc');
/**echo $vcusno."<br>";
echo $vcusnm."<br>";
echo $vtype."<br>";
echo $valias."<br>";
echo $vpwd."<br>";**/

switch(strtoupper($vaction)){
		case "EDIT":
			$query="update userid set Cusno='$vcusno', Type='$vtype', Password= '$vpassword' 	 where trim(UserName) = '$vuserid'";
			break;
		case "ADD":
			 $query="insert into userid(UserName, Password, Cusno, Type) values('$vuserid', '$vpassword', '$vcusno', '$vtype')";
			break;	
		case "DELETE":
			 $query="delete from  userid where trim(UserName) = '$vuserid'";
			break;	
}

mysqli_query($msqlcon,$query);
echo "<script> document.location.href='mainUsrAdm.php'; </script>";
?>
