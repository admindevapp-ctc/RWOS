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
	

	$cusno=	$_SESSION['cusno'];
	$cusnm=	$_SESSION['cusnm'];
	$password=$_SESSION['password'];
	$alias=$_SESSION['alias'];
	$table=$_SESSION['tablename'];
	$type=$_SESSION['type'];
	$user=$_SESSION['user'];
	$dealer=$_SESSION['dealer'];
	$group=$_SESSION['group'];
	
   
}else{	
header("Location: login.php");
}

$discount=trim($_POST['vdiscount']);
$dlrdiscount=trim($_POST['vdlrdiscount']);

//if($dlrdiscount=''){
//	$dlrdiscount=0;
//}
$action=trim($_POST['action']);
$group=trim($_POST['vgroup']);
$cdate=date('Ymd');
$chour=date('Hi');
require('db/conn.inc');


if($action!=="add"){
	$dlrtype=trim($_POST['dlrtype']);
	$ordtype=trim($_POST['ordtype']);
	$dlrdiscount=trim($_POST['vdlrdiscount']);
	//echo $dlrdiscount;
	//update password
	 $query="update discount set discount=$discount, DlrDiscount=$dlrdiscount, MTDate='$cdate', MTTime='$chour',MTUser='$user' where trim(DlrType) = '$dlrtype' and trim(OrdType)='$ordtype' and trim(grpcs)='$group'";
	 mysqli_query($msqlcon,$query);
	//echo $query;
	echo "<script> document.location.href='mainDisAdm.php'; </script>";

}else{
		$dlrtype=trim($_POST['ndlrtype']);
		$ordtype=trim($_POST['nordtype']);
		$dlrdiscount=trim($_POST['vdlrdiscount']);
	 $query="Select * from discount where trim(DlrType) = '$dlrtype' and trim(OrdType)='$ordtype' and trim(grpcs)='$group'";
	  $sql=mysqli_query($msqlcon,$query);	
	  if($hasil = mysqli_fetch_array ($sql)){
		echo "<div align=\"center\">";  
	  	echo "<h3> Discount has found!, You can't create new Record!, Please use Edit!</h3>";
	  	echo "<p><a href=\"mainDisAdm.php\">Back to Main Page</a>";
		echo "</div>";
	  }else{
		  $query1="insert into discount values('$dlrtype','$ordtype', '$group',$discount,$dlrdiscount,'$cdate','$chour','$user')";
		  mysqli_query($msqlcon,$query1);
		  //echo $query1;
		   echo "<script> document.location.href='mainDisAdm.php'; </script>";

	  }
	  
}
	





?>
