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
	$criteria=" where awsexc.Owner_Comp='$owner_comp'  and cusno1 = '$cusno' ";
	$sQuery="SELECT awsexc.Owner_Comp, cusno1, Product, trim(awsexc.itnbr) as itnbr, ITDSC,   
        case when sell = 1 then 'Sell' else 'Not Sell' end sell, cusgrp, price, curr
    FROM awsexc left join bm008pr on trim(awsexc.itnbr) = trim(bm008pr.ITNBR) and awsexc.Owner_Comp = bm008pr.Owner_Comp ";
	
    $xcusno1=$_GET["s_cusno"];
	$xproduct=$_GET["s_product"];
	$xpartnumber=$_GET["s_partnumber"];
	$xpartname=$_GET["s_partname"];
	$xcondition=$_GET["s_condition"];
    echo $xcusno1."<br/>";
		if(trim($xcusno1)!='' && trim($xcusno1)!= NULL && trim($xcusno1)!= 'null'){
			$criteria .= ' and cusgrp="'.$xcusno1.'"';	
		}
		if(trim($xpartnumber)!='' && trim($xpartnumber)!= NULL && trim($xpartnumber)!= 'null'){
			$criteria .= ' and trim(awsexc.itnbr)="'.$xpartnumber.'"';
		}
		if(trim($xproduct)!='' && trim($xproduct)!= NULL && trim($xproduct)!= 'null'){
			$criteria .= ' and Product="'.$xproduct.'"';
		}
		if(trim($xpartname)!='' && trim($xpartname)!= NULL && trim($xpartname)!= 'null'){
			$criteria .= ' and ITDSC like "%'.$xpartname.'%"';
		}
		if(trim($xcondition)!='' && trim($xcondition)!= NULL && trim($xcondition)!= 'null'){
			$criteria .= ' and sell="'.$xcondition.'"';
		}

	$criteria =  $criteria . " order by awsexc.itnbr ,awsexc.cusgrp ";
	$sQuery = $sQuery . $criteria;
//echo $sQuery;
// Header
$datas = [];
$header = ['1 st customer', 'Product',
'Part Number','Part Name','Condition','2 nd customer group', 
'Currency Code', '2 nd customer price(Optional)'];

$datas[0] = $header;
		//echo $sQuery;
	$noBarisCell = 1;
	$rResult = mysqli_query($msqlcon, $sQuery ) or die(mysqli_error());
	while ( $hasil = mysqli_fetch_array( $rResult ) )
	{
        $vcusno1=$hasil['cusno1'];
        $vproduct=$hasil['Product'];
        $vpartno=$hasil['itnbr'];
        $vpartnm=$hasil['ITDSC'];
        $vsell=$hasil['sell'];
        $vcusgrp=$hasil['cusgrp'];
        $vcurr=$hasil['curr'];
        $vprice=$hasil['price'];
	
	    $data= [$vcusno1,$vproduct,$vpartno,$vpartnm,$vsell,$vcusgrp,$vcurr,$vprice];
	
        array_push($datas,$data);
	}


$xlsx = SimpleXLSXGen::fromArray( $datas );
$xlsx->downloadAs('2ndCustomer condition MA.xlsx');

?>
