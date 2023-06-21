<? session_start() ?>
<?
if(isset($_SESSION['cusno']))
{       
	 if($_SESSION['redir']!='denso-sg'){
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
		$dealer=$_SESSION['dealer'];
		$group=$_SESSION['group'];
	 }else{
		   echo "<script> document.location.href='../".redir."'; </script>";
	 }
}else{	
header("Location:../login.php");
}


require('db/conn.inc');
//echo $action;
$vaction=trim($_GET['action']);
$vorddate=trim($_GET['orddate']);
$vprtno=trim($_GET['partno']);
$vdesc=trim($_GET['desc']);
$cyear=date('Y');
$cmonth=date('m');
$ym=$cyear.$cmonth;
$cymd=date('Ymd');
/** get order no **/
$query="select * from RFQ where RFQYM='".$ym."' order by RFQNO Desc Limit 1";
//echo $query;
$sql=mysqli_query($msqlcon,$query);		
if($hasil = mysqli_fetch_array ($sql)){
	$order =substr(substr($hasil['RFQNO'],1,6),-3);
	$order=int($order)+1;
	$xorder=str_pad((int) $order,3,"0",STR_PAD_LEFT);
}else{
	$xorder='001';
	
}
$noorder='RFQ'.$xorder."/".$cmonth."/".$cyear;
$query="insert into RFQ(`STS`,`CUST3`,`RFQDT`,`RFQYM`,`RFQNO`,`PRTNO`,`ITDSC`) values('P', '$cusno','$cymd', '$ym','$noorder','$vprtno', '$vdesc')";
echo $query;
mysqli_query($msqlcon,$query);
?>
