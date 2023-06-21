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

	/* Database connection information */
	require('../db/conn.inc');
    $criteria=" where supawsexc.Owner_Comp='$owner_comp' and supawsexc.cusno1='$cusno'";
	$sQuery="SELECT supawsexc.Owner_Comp, cusno1, trim(supawsexc.prtno) as prtno, supcatalogue.Prtnm, supcatalogue.brand,supmas.supnm,
        case when sell = 1 then 'Sell' else 'Not Sell' end sell, cusgrp , price, curr
    FROM supawsexc left join supcatalogue on trim(supawsexc.prtno) = trim(supcatalogue.Prtno) and supawsexc.Owner_Comp = supcatalogue.Owner_Comp 
    left join supmas on supawsexc.supcode = supmas.supno and supawsexc.Owner_Comp = supmas.Owner_Comp ";


    $xcusno1=$_GET["s_cusno"];
	$xsupplier=$_GET["s_supplier"];
	$xpartnumber=$_GET["s_partnumber"];
	$xbrand=$_GET["s_brand"];
    $xpartname=$_GET["s_partname"];
	$xcondition=$_GET["s_condition"];
   // echo $xcusno1."<br/>";
		if(trim($xcusno1)!='' && trim($xcusno1)!= NULL && trim($xcusno1)!= 'null'){
			$criteria .= ' and cusgrp="'.$xcusno1.'"';	
		}
		if(trim($xpartnumber)!='' && trim($xpartnumber)!= NULL && trim($xpartnumber)!= 'null'){
			$criteria .= ' and trim(supawsexc.prtno)="'.$xpartnumber.'"';
		}
		if(trim($xsupplier)!='' && trim($xsupplier)!= NULL && trim($xsupplier)!= 'null'){
			$criteria .= ' and supawsexc.supcode="'.$xsupplier.'"';
		}
		if(trim($xbrand)!='' && trim($xbrand)!= NULL && trim($xbrand)!= 'null'){
			$criteria .= ' and supcatalogue.brand="'.$xbrand.'"';
		}
		if(trim($xpartname)!='' && trim($xpartname)!= NULL && trim($xpartname)!= 'null'){
			$criteria .= ' and supcatalogue.Prtnm like "%'.$xpartname.'%"';
		}
		if(trim($xcondition)!='' && trim($xcondition)!= NULL && trim($xcondition)!= 'null'){
			$criteria .= ' and sell="'.$xcondition.'"';
		}


	$sQuery = $sQuery . $criteria;
//echo $sQuery;
// Header
$datas = [];
$header = ['1 st customer', 'Supplier','Brand',
'Part Number','Part Name','Condition','2 nd customer group', 
'Currency Code', '2 nd customer price(Optional)'];

$datas[0] = $header;
		//echo $sQuery;
	$noBarisCell = 1;
	$rResult = mysqli_query($msqlcon, $sQuery ) or die(mysqli_error());
	while ( $hasil = mysqli_fetch_array( $rResult ) )
	{
        $vcusno1=$hasil['cusno1'];
		$vbrand=$hasil['brand'];
        $vpartno=$hasil['prtno'];
        $vpartnm=$hasil['Prtnm'];
        $vsell=$hasil['sell'];
        $vsupname=$hasil['supnm'];
        $vcusgrp=$hasil['cusgrp'];
        $vcurr=$hasil['curr'];
        $vprice=$hasil['price'];
	    $data= [ $vcusno1,$vsupname,$vbrand,$vpartno,$vpartnm,$vsell,$vcusgrp,$vcurr,$vprice];
	
        array_push($datas,$data);
	}


$xlsx = SimpleXLSXGen::fromArray( $datas );
$xlsx->downloadAs('2ndCustomer condition MA(Non Denso).xlsx');

?>
