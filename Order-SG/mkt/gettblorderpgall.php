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
		$imptable=$_SESSION['imptable'];
		$comp = ctc_get_session_comp(); // add by CTC
	 }else{
		   echo "<script> document.location.href='../".redir."'; </script>";
	 }
}else{	
header("Location:../login.php");
}


	$per_page=10;
	$num=5;
	/* Database connection information */
	require('../db/conn.inc');
	$datefrom=trim($_GET['datefrom']);
	$dateto=trim($_GET['dateto']);
	$search=trim($_GET['search']);
	$choose=trim($_GET['choose']);
	$desc=trim($_GET['description']);
	//total Row Count
	
	$cusgroup=array();
	$prdgrp==array();
	$sql = "SELECT CusGr, GrpPrd from mktaccess where  usernm='$cusno' and Owner_Comp='$comp' group by CusGr, GrpPrd";
	//echo $sql;
	$flg='';
	$xresult = mysqli_query($msqlcon, $sql ) or die(mysqli_error());
	while ( $aRow = mysqli_fetch_array( $xresult ) )	{
			$cusgroup[]=$aRow['CusGr'];
			$prdgrp[]=$aRow['GrpPrd'];
	/**	if($flg==''){
			$cusgroup[]=$aRow['CusGr'];
			$prdgrp[]=$aRow['GrpPrd'];
			$flg='1';
		}
		$vCusGr= array_search($aRow['CusGr'],$cusgroup,true);
		if($vCusGr==false){
			$cusgroup[]=$aRow['CusGr'];
		}
		$vPrd= array_search($aRow['GrpPrd'],$prdgrp,true);
		if($vPrd== false){
			$prdgrp[]=$aRow['GrpPrd'];
		}
	**/		
	}
	$vcusgroup=array_unique($cusgroup);
	$vprdgrp=array_unique($prdgrp);
	//print_r($vprdgrp);
	if(count($vcusgroup)>0){
			$cussel="('".implode("','",$vcusgroup). "')";
			//$cusgroup=[];
			$sql = "SELECT Cusno from cusmas where  cusgr in $cussel and Owner_Comp='$comp' group by cusno";
			//echo $sql;
			$xresult = mysqli_query($msqlcon, $sql ) or die(mysqli_error());
			while ( $aRow = mysqli_fetch_array( $xresult ) )	{	
				$csgroup[]=$aRow['Cusno'];
								 
								 
		
			}
		//	print_r($csgroup);
			//echo $sql; 
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
	//echo $vcussel;
	//echo 'jumlah array ='.count($cusgroup);
	//echo '<br>';
	//for($i=0; $i<=count($csgroup); $i++){
	//	echo $csgroup[$i].'<br>';	
	//}
	//total Row Count
	//echo $vcussel;
	//echo $vprdsel;
	$sql =  "SELECT count(*) as count from orderdtl inner join orderhdr on orderhdr.orderno=orderdtl.orderno and orderhdr.Owner_Comp=orderdtl.Owner_Comp inner join bm008pr on orderdtl.partno=bm008pr.itnbr and orderdtl.Owner_Comp=bm008pr.Owner_Comp ".
			  " where orderhdr.Owner_Comp='$comp' and orderhdr.orderdate>='$datefrom' and orderhdr.orderdate<='$dateto' " ;   // edit by CTC
	if($vcussel!=''){
		$sql = $sql . " and orderdtl.cusno in " . $vcussel ;
		if($vprdsel!=''){
			$sql = $sql . " and bm008pr.product in " . $vprdsel ;
		}
	}
	
	
	
	
	
	
	
	//$sql = "SELECT count(*) as count from orderhdr inner join orderdtl on orderhdr.orderno=orderdtl.orderno".
  //" where  orderhdr.orderdate>='$datefrom' and orderhdr.orderdate<='$dateto'";


  
	 if($search!=''){
		//echo $search;
		switch($search){
			case "partno":
				$fld="orderdtl.partno";
				break;
			case "ITDSC":
				$fld="orderdtl.itdsc";
				break;
			case "corno":
				$fld="orderhdr.Corno";
				break;
		}
		switch($choose){
			case "eq":
				$op="=";
				$vdesc=$desc;
				break;
			case "like";
				$op="like";
				$vdesc="%$desc%";
				break;
		}
		$sql = $sql . " and $fld $op '$vdesc'";	
	 }		  
	$sql = $sql . " order by partno, orderhdr.orderdate";
	
	//echo $sql;
	$result = mysqli_query($msqlcon,$sql);
	//$count = mysqli_num_rows($result);
	$aRow = mysqli_fetch_array( $result );
	$count = $aRow['count'];
	//echo "<br> jumlah :".$count;
	$pages = ceil($count/$per_page);
	$page = $_GET['page'];
	if($page){ 
		$start = ($page - 1) * $per_page; 			
	}else{
		$start = 0;	
		$page=1;
	}
   
	if($count>$per_page){
		//echo "<div id=\"search\"><a href=\"#\"> <img src=\"images/view.png\" width=\"16\" height=\"16\" border=\"0\"></a></div>";
	}

	if($pages!=1 && $pages!=0){	
		$prev=$page-1;
		if($prev!=0){
			echo '<a href="#" onclick="paging('.$prev.')">Previous</a>';
		}else{
			echo '<a href="#">Previous</a>';
		}
	
	    if($page>=$num){
			if($pages<$page+2){
				$tgh=$pages-4;
			}else{
				$tgh=$page-2;
			}
		}else{
			$tgh=1;
		}
		
		$y=0;
		for($x=$tgh; $x<=$pages; $x++)
		{
			$y++;
			if($y<=5){
				if($page==$x){
				//	echo '<li id="'.$x.'" class="current">'.$x.'</li>';
					echo 	'<a href="#"  class="current" onclick="paging('.$x.')">'.$x.'</a>';
					}else{
					echo 	'<a href="#"  onclick="paging('.$x.')">'.$x.'</a>';
	
				}
			}else{
			  break;	
			}
		}
	
	
	
		if($pages>$page){
			$next=$page+1;
			echo '<a href="#" onclick="paging('.$next.')">Next</a>';
		}else{
			echo '<a href="#">Next</a>';
		}
	}
?>
