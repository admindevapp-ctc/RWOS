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
    $criteria=" where supawsexc.Owner_Comp='$comp' ";
   
    $sQuery="SELECT distinct supawsexc.Owner_Comp, supawsexc.cusno1, supawsexc.supcode, supmas.supnm,   supawsexc.prtno, supcatalogue.Prtnm, supcatalogue.Brand,
    case when sell = 1 then 'Sell' else 'Not Sell' end sell, cusgrp , supprice.price, supawsexc.curr
    FROM supawsexc 
    left join supcatalogue on trim(supcatalogue.Prtno) = trim(supawsexc.prtno)
    left join supmas on supmas.supno = supawsexc.supcode
    left join supprice on supprice.Cusno = supawsexc.cusno1  and supawsexc.prtno = supprice.partno ";

    $xcusno1=$_GET["s_cusno1"];
    $xcusgrp2=$_GET["s_cusgrp2"];
    $xcondition=$_GET["s_condition"];
    $xpartnumber=$_GET["s_partnumber"];
	$xsupcode=$_GET["s_supcode"];
    $xpartname=$_GET["s_partname"];
    $xbrand=$_GET["s_brand"];

		if(trim($xcusno1)!=''  && trim($xcusno1)!= NULL && trim($xcusno1)!= 'null'){
			$criteria .= ' and supawsexc.cusno1="'.$xcusno1.'"';	
		}
		if(trim($xcusgrp2)!=''  && trim($xcusgrp2)!= NULL && trim($xcusgrp2)!= 'null'){
			$criteria .= ' and supawsexc.cusgrp="'.$xcusgrp2.'"';
		}
		if(trim($xpartnumber)!=''  && trim($xpartnumber)!= NULL && trim($xpartnumber)!= 'null'){
			$criteria .= ' and supawsexc.prtno like "%'.$xpartnumber.'%"';
		}
		if(trim($xpartname)!=''  && trim($xpartname)!= NULL && trim($xpartname)!= 'null'){
			$criteria .= ' and supcatalogue.Prtnm like "%'.$xpartname.'%"';
		}
		if(trim($xbrand)!=''  && trim($xbrand)!= NULL && trim($xbrand)!= 'null'){
			$criteria .= ' and  supcatalogue.Brand ="'.$xbrand.'"';
		}
		if(trim($xsupcode)!=''  && trim($xsupcode)!= NULL && trim($xsupcode)!= 'null'){
			$criteria .= ' and  supawsexc.supcode ="'.$xsupcode.'"';
		}
		if(trim($xcondition)!=''  && trim($xcondition)!= NULL && trim($xcondition)!= 'null'){
            $criteria .= ' and sell="'.$xcondition.'"';
		}
	
	$criteria =  $criteria . " order by supawsexc.cusno1 ";
	$sQuery = $sQuery . $criteria;

// Header
$datas = [];
$header = ['1 st customer', 'Supplier', 'Brand',
'Part Number','Part Name','Condition','2 nd customer group', 
'Currency Code', '2 nd customer price(Optional)'];

$datas[0] = $header;
		echo $sQuery;
	
        $noBarisCell = 1;
	$rResult = mysqli_query($msqlcon, $sQuery ) or die(mysqli_error());
	while ( $hasil = mysqli_fetch_array( $rResult ) )
	{
        $vcusno1=$hasil['cusno1'];
        $vBrand=$hasil['Brand'];
        $vsupnm=$hasil['supnm'];
        $vpartno=$hasil['prtno'];
        $vpartnm=$hasil['Prtnm'];
        $vsell=$hasil['sell'];
        $vcusgrp=$hasil['cusgrp'];
        $vcurr=$hasil['curr'];
        $vprice=$hasil['price'];
	
	    $data= [$vcusno1,$vsupnm,$vBrand,$vpartno,$vpartnm,$vsell,$vcusgrp,$vcurr,$vprice];
	
        array_push($datas,$data);
	}


$xlsx = SimpleXLSXGen::fromArray( $datas );
$xlsx->downloadAs('2ndCustomer condition NON DENSO.xlsx');

?>
