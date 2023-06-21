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
		$password=$_SESSION['password'];
		$alias=$_SESSION['alias'];
		$table=$_SESSION['tablename'];
		$type=$_SESSION['type'];
		$custype=$_SESSION['custype'];
		$user=$_SESSION['user'];
		//$dealer=$_SESSION['dealer'];
		$group=$_SESSION['group'];
		$owner_comp = ctc_get_session_comp(); // add by CTC
	 }else{
		   echo "<script> document.location.href='../".redir."'; </script>";
	 }
}else{	
header("Location:../login.php");
}
	$per_page=10;
	
	/* Database connection information */
	require('db/conn.inc');
	$datefrom=trim($_GET['datefrom']);
	$dateto=trim($_GET['dateto']);
	$page=trim($_GET['page']);
	$sort=trim($_GET['sort']);
	$namafield=trim($_GET['namafield']);
	$search=trim($_GET['search']);
	$choose=trim($_GET['choose']);
	$desc=trim($_GET['description']);
	

	
// Customer master..
	// Because SDML have 2 User so, we should use cusno instead of cust3
	$qrycusmas="select cusno from cusmas where cust3= '$cusno' and Owner_Comp='$owner_comp' ";
	$sqlcusmas=mysqli_query($msqlcon,$qrycusmas);		
	$comp='(';
	$flag='';		
	while($hslcusmas = mysqli_fetch_array ($sqlcusmas)){
	  $cros=$hslcusmas['cusno'];
	  if($flag==''){
	  	$comp=$comp .$cros;
		$flag='1';
	  }else{
		  	$comp=$comp .','.$cros;
	  }
	}
	$comp=$comp .')';
	
//$sQuery=  " SELECT   sum( qty ) as ttlqty,   sum( bprice * qty ) as ttlamount , sum( bprice * qty * SGPrice ) as ttlamountsg FROM orderdtl INNER JOIN bm008pr ON orderdtl.partno = bm008pr.itnbr".
//" where orderdtl.cust3='$cusno' and orderdtl.orderdate>='$datefrom' and orderdtl.orderdate<='$dateto'"	;

$sQuery=  " SELECT   sum( qty ) as ttlqty,   sum( bprice * qty ) as ttlamount , sum( bprice * qty * SGPrice ) as ttlamountsg FROM orderdtl INNER JOIN bm008pr ON orderdtl.partno = bm008pr.itnbr AND orderdtl.Owner_Comp = bm008pr.Owner_Comp ".
" where orderdtl.cusno in $comp and orderdtl.orderdate>='$datefrom' and orderdtl.orderdate<='$dateto' AND orderdtl.Owner_Comp='$owner_comp' "	;

	//echo  $sQuery;
	$i="0";
	//echo $sQuery;
	$rResult = mysqli_query($msqlcon, $sQuery ) or die(mysqli_error());
	
	if( $aRow = mysqli_fetch_array( $rResult ) )
	{
			$ttlqty=number_format($aRow['ttlqty']);
			$ttlamount=number_format($aRow['ttlamount'],2,".",",");
			$ttlamountsg=number_format($aRow['ttlamountsg'],2,".",",");
	        echo $ttlqty.'||'.$ttlamount.'||'.$ttlamountsg;	
			
	}

?>
