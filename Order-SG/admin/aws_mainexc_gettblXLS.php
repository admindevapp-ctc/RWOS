<?php 

session_start();
require_once('./../../core/ctc_init.php'); // add by CTC

if(isset($_SESSION['cusno']))
{       
	$_SESSION['cusnm'];
	$_SESSION['password'];
	$_SESSION['alias'];
	$_SESSION['tablename'];
	$_SESSION['user'];
	$_SESSION['dealer'];
	$_SESSION['group'];
	$_SESSION['type'];
	$_SESSION['custype'];

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
	$comp = ctc_get_session_comp(); // add by CTC
  
}else{	
	header("Location: login.php");
}
	/* Database connection information */
	require('../db/conn.inc');

    $sQuery  = "SELECT awsexc.Owner_Comp, cusno1, Product, trim(awsexc.itnbr) as itnbr, ITDSC,
      case when sell = 1 then 'Sell' else 'Not Sell' end sell, cusgrp, price, curr
    FROM awsexc left join bm008pr on awsexc.itnbr = bm008pr.ITNBR AND awsexc.Owner_Comp = bm008pr.Owner_Comp";
	$criteria=" where awsexc.Owner_Comp='$comp' ";

	
    $xcusno1=$_GET["s_cusno1"];
    $xcusgrp2=$_GET["s_cusgrp2"];
    $xpartnumber=$_GET["s_partnumber"];
    $xproduct=$_GET["s_product"];
    $xpartname=$_GET["s_partname"];
    $xcondition=$_GET["s_condition"];


		if(trim($xcusno1)!=''  && trim($xcusno1)!= NULL && trim($xcusno1)!= 'null'){
			$criteria .= ' and cusno1="'.$xcusno1.'"';	
		}
		if(trim($xcusgrp2)!=''  && trim($xcusgrp2)!= NULL && trim($xcusgrp2)!= 'null'){
			$criteria .= ' and cusgrp="'.$xcusgrp2.'"';	
		}
		if(trim($xpartnumber)!=''  && trim($xpartnumber)!= NULL && trim($xpartnumber)!= 'null'){
            $criteria .= ' and trim(awsexc.itnbr)  like "%'.$xpartnumber.'%"';
		}
		if(trim($xproduct)!=''  && trim($xproduct)!= NULL && trim($xproduct)!= 'null'){
            $criteria .= ' and Product="'.$xproduct.'"';
		}
		if(trim($xpartname)!=''  && trim($xpartname)!= NULL && trim($xpartname)!= 'null'){
            $criteria .= ' and ITDSC like "%'.$xpartname.'%"';
		}
		if(trim($xcondition)!=''  && trim($xcondition)!= NULL && trim($xcondition)!= 'null'){
            $criteria .= ' and sell="'.$xcondition.'"';
		}
	
	$criteria =  $criteria . " order by awsexc.cusno1 ";
	$sQuery = $sQuery . $criteria;

// Header
$datas = [];
$header = ['1 st customer', 'Product',
'Part Number','Part Name','Condition','2 nd customer group', 
'Currency Code', '2 nd customer price(Optional)'];

$datas[0] = $header;
		// echo $sQuery;
		
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
