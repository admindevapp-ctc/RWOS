<? session_start() ?>
<?
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
		if($type!='a')header("Location:../main.php");
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
	
$host = "order.denso.com";
$user = "root";
$pass = "";
$dbnm = "ordering-sg";
$lgdb="login";
$conn = mysql_connect ($host, $user, $pass);
$condb=mysqli_select_db ($dbnm, $conn);


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


switch(strtoupper($vaction)){
		case "EDIT":
			$query="update userid set Cusno='$vcusno', Type='$vtype', Password= '$vpassword', COM='$vcompany', Redir='$redir'	 where trim(UserName) = '$vuserid'";
			mysqli_query($msqlcon,$query);
			$logindb=mysqli_select_db ($lgdb, $conn);
			mysqli_query($msqlcon,$query);
			
			break;
		case "ADD":
			 $query="insert into userid(UserName, Password, Cusno, Type, COM, Redir) values('$vuserid', '$vpassword', '$vcusno', '$vtype', '$vcompany','$redir' )";
			 //echo $query;
			 mysqli_query($msqlcon,$query);
			 $logindb=mysqli_select_db ($lgdb, $conn);
			 mysqli_query($msqlcon,$query);
			 
			break;	
		case "DELETE":
			$err="";
			 $query="delete from  userid where trim(UserName) = '$vuserid'";
			 //echo $query;
			 $hasil=mysqli_query($msqlcon,$query);
			 //echo "hasil:" . $hasil;
			 $logindb=mysqli_select_db ($lgdb, $conn);
			 $query="delete from  userid where trim(UserName) = '$vuserid'";
			 mysqli_query($msqlcon,$query);
			break;	
}
//echo  $query;
if($err==""){
	 echo "<script> document.location.href='mainUsrAdm.php'; </script>";
}else{
    echo $err . " should be filled";	
}
?>
