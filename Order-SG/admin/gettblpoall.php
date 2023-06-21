<?php 

session_start();
require_once('./../../core/ctc_init.php'); // add by CTC

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
	
	
	
	
	//total Row Count
	$sql = " SELECT  bm008pr.Owner_Comp,cusno, orderno, orderdate , Corno , sum( qty ) as ttlqty , curcd, sum( bprice * qty ) as ttlamount , sum( bprice * qty * SGPrice ) as ttlamountsg, orderno 
FROM orderdtl INNER JOIN bm008pr ON orderdtl.partno = bm008pr.itnbr and orderdtl.Owner_Comp=bm008pr.Owner_Comp ".
" where orderdtl.orderdate>='$datefrom' and orderdtl.orderdate<='$dateto' and orderdtl.Owner_Comp='$comp' ";

if($vcussel!=''){
		$sql = $sql . " and orderdtl.cusno in " . $vcussel ;
		if($vprdsel!=''){
			$sql = $sql . " and bm008pr.product in " . $vprdsel ;
		}
	}



$sql = $sql .  " GROUP BY Corno, cusno, orderdate, orderno ORDER BY Corno, cusno, orderdate, orderno";

	
	$result = mysqli_query($msqlcon,$sql);
	$count = mysqli_num_rows($result);
	$pages = ceil($count/$per_page);
	$page = $_GET['page'];
	if($page){ 
		$start = ($page - 1) * $per_page; 			
	}else{
		$start = 0;	
		$page=1;
	}
	
	
$sQuery=  " SELECT  bm008pr.Owner_Comp,cusno, orderno, orderdate , Corno , sum( qty ) as ttlqty , curcd, sum( bprice * qty ) as ttlamount , sum( bprice * qty * SGPrice ) as ttlamountsg, orderno 
FROM orderdtl  INNER JOIN bm008pr ON orderdtl.partno = bm008pr.itnbr and orderdtl.Owner_Comp=bm008pr.Owner_Comp ".
" where  orderdtl.orderdate>='$datefrom' and orderdtl.orderdate<='$dateto' and orderdtl.Owner_Comp='$comp' ";
if($vcussel!=''){
		$sQuery=  $sQuery . " and orderdtl.cusno in " . $vcussel ;
		if($vprdsel!=''){
			$sQuery=  $sQuery. " and bm008pr.product in " . $vprdsel ;
		}
	}

$sQuery=  $sQuery. " GROUP BY Corno, cusno, orderdate, orderno ORDER BY Corno, cusno, orderdate, orderno"	;
		$sQuery=$sQuery ." LIMIT $start, $per_page";
	
	//echo  $sQuery;
	$i="0";
	//echo $sQuery;
	$rResult = mysqli_query($msqlcon, $sQuery ) or die(mysqli_error());
	
	while ( $aRow = mysqli_fetch_array( $rResult ) )
	{
		if($i=="0"){
		
			echo "<table 	border=\"0\" cellspacing=\"0\" cellpadding=\"0\" width=\"100%\">";
			$i="1";
		}		
			$owner_comp=$aRow['Owner_Comp'];
			$orderno=$aRow['orderno'];
			$corno=$aRow['Corno'];
			$shpno=$aRow['cusno'];
			if($corno=="")$corno="-";
			$ttlqty=number_format($aRow['ttlqty']);
			$ttlamount=number_format($aRow['ttlamount'],2);
			$ttlamountsg=number_format($aRow['ttlamountsg'],2);
		    $ordtype=substr( $orderno, -1);
			
			switch( $ordtype){
				case 'R':
					$ordsts='Regular';
					break;
					
				case 'U':
					$ordsts='Urgent';
					break;	
				case 'C':
					$ordsts='Campaign';
					break;	
				case 'S':
					$ordsts='Special';
					break;
				
				}
				
				
			
			$orderdate=$aRow['orderdate'];
			$orddate=substr($orderdate,-2)."/".substr($orderdate,4,2)."/".substr($orderdate,0,4);
			echo "<tr height=\"30\">";
			
			echo "<td width=\"10%\" align=\"center\">".$owner_comp. "</td>";
			echo "<td width=\"10%\" align=\"center\">".$corno. "</td>";
			echo "<td width=\"10%\" align=\"center\">".$shpno. "</td>";
			echo "<td width=\"10%\" align=\"center\">".$ordsts."</td>";
			echo "<td width=\"10%\" align=\"center\">".$orddate."</td>";
			echo "<td width=\"10%\" align=\"center\">".$ttlqty."</td>";
			echo "<td width=\"20%\" align=\"center\" class=\"lasttd\" >".$ttlamount."</td>";	
			//	echo "<td width=\"20%\" align=\"right\" class=\"lasttd\">".$ttlamountsg."</td>";	
			echo "</tr>";
	
	}
	if($i=="1") echo "</table>";
	
	/**for($x=1; $x<=$pages; $x++)
	{
		if($i=="1"){
			echo "<ul id=\"pagination\">";
			$i=2;
		}
		if($page==$x){
			echo '<li id="'.$x.'" class="current">'.$x.'</li>';
		}else{
			echo '<li id="'.$x.'">'.$x.'</li>';
		}
	}
	if($i=="2") echo "</ul>"; **/

?>
