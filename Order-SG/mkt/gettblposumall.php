<?php 
	session_start();
	require_once('../../core/ctc_init.php'); // add by CTC

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
			$comp = ctc_get_session_comp(); // add by CTC
	 	}else{
		   echo "<script> document.location.href='../".redir."'; </script>";
	 	}
	}else{	
		header("Location:../login.php");
	}
	
	$per_page=10;
	
	/* Database connection information */
	require('../db/conn.inc');
	$datefrom=trim($_GET['datefrom']);
	$dateto=trim($_GET['dateto']);
	$page=trim($_GET['page']);
	$sort=trim($_GET['sort']);
	$namafield=trim($_GET['namafield']);
	$search=trim($_GET['search']);
	$choose=trim($_GET['choose']);
	$desc=trim($_GET['description']);
	
	$cusgroup=array();
	$prdgrp==array();
	$sql = "SELECT CusGr, GrpPrd from mktaccess where  usernm='$cusno' and Owner_Comp='$comp' group by CusGr, GrpPrd";
	$flg='';
	$xresult = mysqli_query($msqlcon, $sql ) or die(mysqli_error());
	while ( $aRow = mysqli_fetch_array( $xresult ) )	{
			$cusgroup[]=$aRow['CusGr'];
			$prdgrp[]=$aRow['GrpPrd'];
	}
	$vcusgroup=array_unique($cusgroup);
	$vprdgrp=array_unique($prdgrp);
	if(count($vcusgroup)>0){
			$cussel="('".implode("','",$vcusgroup). "')";
			$sql = "SELECT Cusno from cusmas where  cusgr in $cussel and Owner_Comp='$comp' group by cusno";
			$xresult = mysqli_query($msqlcon, $sql ) or die(mysqli_error());
			while ( $aRow = mysqli_fetch_array( $xresult ) )	{	
				$csgroup[]=$aRow['Cusno'];
			}
			$vcussel="('".implode("','",$csgroup). "')";
	}

	
	if(count($vprdgrp)>0){
			$prdsel="('".implode("','",$vprdgrp). "')";	
			$sql = "SELECT Prd from prdgrp where  GrpPrd in $prdsel and Owner_Comp='$comp' group by Prd";
			//echo $sql;
			$xresult = mysqli_query($msqlcon, $sql ) or die(mysqli_error());
			while ( $aRow = mysqli_fetch_array( $xresult ) )	{	
				$pdgroup[]=$aRow['Prd'];
								 
				
			}
			$vprdsel="('".implode("','",$pdgroup). "')";
	}
	
	
	

	
$sQuery=  " SELECT   sum( qty ) as ttlqty,   sum( bprice * qty ) as ttlamount , sum( bprice * qty * SGPrice ) as ttlamountsg 
FROM orderdtl INNER JOIN bm008pr ON orderdtl.partno = bm008pr.itnbr AND orderdtl.Owner_Comp = bm008pr.Owner_Comp ".
" where orderdtl.Owner_Comp='$comp' and orderdtl.orderdate>='$datefrom' and orderdtl.orderdate<='$dateto'"	;
if($vcussel!=''){
		$sQuery = $sQuery . " and orderdtl.cusno in " . $vcussel ;
		if($vprdsel!=''){
			$sQuery = $sQuery . " and bm008pr.product in " . $vprdsel ;
		}
	}



	//echo  $sQuery;
	$i="0";
	//echo $sQuery;
	$rResult = mysqli_query($msqlcon, $sQuery ) or die(mysqli_error());
	
	if( $aRow = mysqli_fetch_array( $rResult ) )
	{
			$ttlqty=number_format($aRow['ttlqty']);
			$ttlamount=number_format($aRow['ttlamount']);
			$ttlamountsg=number_format($aRow['ttlamountsg']);
	        echo $ttlqty.'||'.$ttlamount.'||'.$ttlamountsg;	
			
	}

?>
