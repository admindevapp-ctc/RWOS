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
		$dealer=$_SESSION['dealer'];
		$group=$_SESSION['group'];
		$owner_comp = ctc_get_session_comp(); // add by CTC
	 }else{
		echo "<script> document.location.href='../".redir."'; </script>";
	 }
}else{	
	header("Location:../login.php");
}
/*
xlsWriteLabel(0,0,"Order Date");              
xlsWriteLabel(0,1,"Supplier");  
xlsWriteLabel(0,2,"2 nd Customer Code");             
xlsWriteLabel(0,3,"Ship to Code");             
xlsWriteLabel(0,4,"Address");
xlsWriteLabel(0,5,"PO Number");  
xlsWriteLabel(0,6,"Part Number");
xlsWriteLabel(0,7,"Quantity");
xlsWriteLabel(0,8,"Amount");
xlsWriteLabel(0,9,"Status");
xlsWriteLabel(0,10,"shipfrom");*/
// Header
$datas = [];
$header = ['Order Date','1st Customer Code', '2 nd Customer Code',
'Ship to Code','Address','PO Number','Part Number', 
'Quantity', 'Amount', 'Status',
'shipfrom'];

$datas[0] = $header;
	require('db/conn.inc');
    $datefrom=trim($_GET['datefrom']);
	$dateto=trim($_GET['dateto']);
	$page=trim($_GET['page']);
	$sort=trim($_GET['sort']);
	$namafield=trim($_GET['namafield']);
	$search=trim($_GET['search']);
	$choose=trim($_GET['choose']);
	$desc=trim($_GET['desc']);
	$sQuery =  "select *
	from(
	SELECT  awsorderhdr.Owner_Comp,awsorderdtl.partno, awsorderhdr.cusno, awsorderhdr.orderno, awsorderhdr.Corno, awsorderhdr.orderdate, awsorderhdr.Dealer, itdsc, ordtype, qty,slsprice ,awsorderdtl.ordflg  as ordflg,awsorderhdr.shipto , 
		CONCAT(
            shiptoma.adrs1,
            shiptoma.adrs2,
            shiptoma.adrs3
        ) AS address,
        rejectorder.message
	FROM awsorderhdr 
		LEFT join awsorderdtl on awsorderhdr.orderno=awsorderdtl.orderno and awsorderhdr.Owner_Comp=awsorderdtl.Owner_Comp and awsorderhdr.Corno = awsorderdtl.Corno 
		LEFT join cusmas on awsorderhdr.cusno=cusmas.Cusno AND awsorderhdr.Owner_Comp=cusmas.Owner_Comp 
		LEFT JOIN awscusmas ON awscusmas.Owner_Comp = awsorderhdr.Owner_Comp AND awscusmas.cusno1 = awsorderhdr.Dealer AND awscusmas.ship_to_cd2 = awsorderhdr.shipto
		LEFT JOIN shiptoma ON shiptoma.Cusno = awscusmas.cusno1 AND shiptoma.ship_to_cd = awscusmas.ship_to_cd1
		LEFT JOIN rejectorder ON rejectorder.Owner_Comp = awsorderhdr.Owner_Comp and rejectorder.orderno = awsorderhdr.orderno and rejectorder.partno = awsorderdtl.partno
		where awsorderhdr.Dealer='$cusno' and awsorderhdr.cusno<>awsorderhdr.Dealer and awsorderhdr.orderdate>='$datefrom' and awsorderhdr.orderdate<='$dateto'  and awsorderhdr.Owner_Comp='$owner_comp'
	UNION
		SELECT supawsorderhdr.Owner_Comp, supawsorderdtl.partno, supawsorderhdr.cusno, supawsorderhdr.orderno, supawsorderhdr.Corno, supawsorderhdr.orderdate, supawsorderhdr.Dealer, itdsc, ordtype, qty,slsprice ,supawsorderdtl.ordflg COLLATE utf8_general_ci as ordflg,supawsorderhdr.shipto, 
		CONCAT(
            shiptoma.adrs1,
            shiptoma.adrs2,
            shiptoma.adrs3
        ) AS address,
        rejectorder.message
	FROM supawsorderhdr 
		LEFT join supawsorderdtl on supawsorderhdr.orderno=supawsorderdtl.orderno and supawsorderhdr.Owner_Comp=supawsorderdtl.Owner_Comp and supawsorderhdr.Corno = supawsorderdtl.Corno 
		LEFT join cusmas on supawsorderhdr.cusno=cusmas.Cusno AND supawsorderhdr.Owner_Comp=cusmas.Owner_Comp 
		LEFT JOIN awscusmas ON awscusmas.Owner_Comp = supawsorderhdr.Owner_Comp AND awscusmas.cusno1 = supawsorderhdr.Dealer AND awscusmas.ship_to_cd2 = supawsorderhdr.shipto
		LEFT JOIN shiptoma ON shiptoma.Cusno = awscusmas.cusno1 AND shiptoma.ship_to_cd = awscusmas.ship_to_cd1
		LEFT JOIN rejectorder ON rejectorder.Owner_Comp = supawsorderhdr.Owner_Comp and rejectorder.orderno = supawsorderhdr.orderno and rejectorder.partno = supawsorderdtl.partno
		where supawsorderhdr.Dealer='$cusno'and supawsorderhdr.cusno<>supawsorderhdr.Dealer and supawsorderhdr.orderdate>='$datefrom' and supawsorderhdr.orderdate<='$dateto'  and supawsorderhdr.Owner_Comp='$owner_comp'
		) a where Owner_Comp='$owner_comp'
	";
	 if($search!=''){
		// echo $search;
		switch($search){
			case "partno":
				$fld="partno";
				break;
			case "itdsc":
				$fld="itdsc";
				break;	
			case "cusno":
				$fld="cusno";
				break;
			case "corno":
				$fld="Corno";
				break;
			case "status":
				$fld="ordflg";
				break;
			case "shipfrom":
				$fld="ordflg";
				break;
		}
		switch($choose){
			case "eq":
				$op="=";
				$vdesc="'$desc'";
				break;
			case "like";
				$op="like";
				$vdesc="'%$desc%'";
				break;
			case "in";
				$op="in";
				if($desc=="R"){
					$op="=";
					$vdesc="'$desc'";
				}
				else if($desc=="1,2"){
					$op="!=";
					$vdesc="'R'";
				}
				else{
					$op="=";
					$vdesc="''";
				}
				break;
		}
		$sQuery = $sQuery . " and $fld $op $vdesc";	
	 }
	
	 if($namafield!=''){
			$sQuery = $sQuery . " order by $namafield $sort, orderdate";		  
	  }else{
			$sQuery = $sQuery . " order by cusno, partno, orderdate";		   
	  }	
	$noBarisCell = 1;
    //echo $sQuery;
	$rResult = mysqli_query($msqlcon, $sQuery ) or die(mysqli_error());
	
	while ( $aRow = mysqli_fetch_array( $rResult ) )
	{
		$cusno=$aRow['cusno'];
		$orderno=$aRow['orderno'];
		$partno=$aRow['partno'];
		$partdes=$aRow['itdsc'];
		$corno=$aRow['Corno'];
		$dealer=$aRow['Dealer'];
		$shipto=$aRow['shipto'];
		$addr=$aRow['ESCA1'];
		$price=$aRow['slsprice'];
		$ordflg=$aRow['ordflg'];
		$address=$aRow['address'];
		$message=$aRow['message'];
		if($corno=="")$corno="-";
		$qty=number_format($aRow['qty']);
		$amount=number_format($price * $qty);
		$orderdate=$aRow['orderdate'];
		$orddate=substr($orderdate,-2)."/".substr($orderdate,4,2)."/".substr($orderdate,0,4);
		$odrsts=$aRow['ordtype'];
			switch($odrsts){
				case "U":
					$ordsts="Urgent";
					break;
				case "R":
					$ordsts="Regular";
					break;
				case "N":
					$ordsts="Normal";
					break;
				case "C":
					$ordsts="Campaign";
					break;
			}

			switch($ordflg){
				case "R":
					$ordflgtext="Rejected : ".$message;
					break;
				case "1":
					$ordflgtext="Approved";
					break;
				case "2":
					$ordflgtext="Approved";
					break;
				default:
					$ordflgtext="Pending";
					break;
			}

			switch($ordflg){
				case "1":
					$shipfrom="Supplier";
					break;
				case "2":
					$shipfrom="Own Warehouse";
					break;
				default:
					$shipfrom="";
					break;
			}
	
	$qrycus="select Cusnm from cusmas where Cusno = '".$shpno."'";
			$sqlcus=mysqli_query($msqlcon,$qrycus);		
			if($hasilx = mysqli_fetch_array ($sqlcus)){
				$cusnm=$hasilx['Cusnm'];
			}
			
			//prtdes from Bm008PR
			
			$qryPart="select ITDSC from bm008pr where ITNBR='".$partno."'";
			$sqlprt=mysqli_query($msqlcon,$qryPart);		
			if($hasilx = mysqli_fetch_array ($sqlprt)){
				$desc=$hasilx['ITDSC'];
			}
	/*
	// menampilkan no. urut data
		xlsWriteLabel($noBarisCell,0,$orddate);
		xlsWriteLabel($noBarisCell,1,$dealer);
		xlsWriteLabel($noBarisCell,2,$cusno);
		xlsWriteLabel($noBarisCell,3,$shipto);
		xlsWriteLabel($noBarisCell,4,iconv('UTF-8', 'UTF-8',$address));

		xlsWriteLabel($noBarisCell,5,$corno);
 		xlsWriteLabel($noBarisCell,6,$partno);
		xlsWriteNumber($noBarisCell,7,$qty);
		xlsWriteNumber($noBarisCell,8,$amount);
		xlsWriteLabel($noBarisCell,9,$ordflgtext);
		xlsWriteLabel($noBarisCell,10,$shipfrom);
		$data= [$vowner,$vcusno1,$vshpto1,$vcusno2,$vshpto2,$vcusnm2,$vcusgrp,$vshpaddr1,$vshpaddr2,$vshpaddr3,$vmail1,$vmail2,$vmail3];
	
		$noBarisCell++;

	*/
	
		$data= [$orddate,$dealer,$cusno,$shipto,$address
		,$corno,$partno,$qty,$amount,$ordflgtext,$shipfrom];
		
		array_push($datas,$data);
	}




	$xlsx = SimpleXLSXGen::fromArray( $datas );
	$xlsx->downloadAs('orderaws.xlsx');
	
	
?>
