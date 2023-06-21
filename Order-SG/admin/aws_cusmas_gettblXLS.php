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
	
		
	//$sQuery  = "SELECT * FROM awscusmas ";
    $sQuery  = "select  awscusmas.* ,cusmas.Cusnm from awscusmas join cusmas on awscusmas.cusno2 = cusmas.Cusno  and awscusmas.Owner_Comp = cusmas.Owner_Comp";
	$criteria =  " where awscusmas.Owner_Comp = '$comp'";
   
	$xcusno1=$_GET["cusno1"];
	$xcusno2=$_GET["cusno2"];
	$xcusname=$_GET["cusname"];
	$xcusgroup=$_GET["cusgroup"];


		if(trim($xcusno1)!=''  && trim($xcusno1)!= NULL && trim($xcusno1)!= 'null'){
			$criteria .= " and cusno1='$xcusno1'";	
		}
		if(trim($xcusno2)!=''  && trim($xcusno2)!= NULL && trim($xcusno2)!= 'null'){
			$criteria .= " and cusno2='$xcusno2'";
		}
		if(trim($xcusname)!=''  && trim($xcusname)!= NULL && trim($xcusname)!= 'null'){
			$criteria .= "and cusmas.Cusnm like '%$xcusname%'";
		}
		if(trim($xcusgroup)!=''  && trim($xcusgroup)!= NULL && trim($xcusgroup)!= 'null'){
			$criteria .= " and cusgrp='$xcusgroup'";
		}
	
	$criteria =  $criteria . " order by awscusmas.cusno1 ";
	$sQuery = $sQuery . $criteria;

// Header
$datas = [];
$header = ['Company Code',' 1 st customer', 'Shipto 1st',
'2 nd customer','Shipto 2nd','Customer Name','2 nd customer group', 
'Ship to address1', 'Ship to address2', 'Ship to address3',
'e-mail1', 'e-mail2', 'e-mail3'];

$datas[0] = $header;
	//	echo $sQuery;
	$noBarisCell = 1;
	$rResult = mysqli_query($msqlcon, $sQuery ) or die(mysqli_error());
	while ( $hasil = mysqli_fetch_array( $rResult ) )
	{
        $vowner=$hasil['Owner_Comp'];
        $vcusno1=$hasil['cusno1'];
        $vshpto1=$hasil['ship_to_cd1'];
        $vcusno2=$hasil['cusno2'];
        $vshpto2=$hasil['ship_to_cd2'];
        $vcusnm2=$hasil['Cusnm'];
        $vcusgrp=$hasil['cusgrp'];
        $vshpaddr1=$hasil['ship_to_adrs1'];
        $vshpaddr2=$hasil['ship_to_adrs2'];
        $vshpaddr3=$hasil['ship_to_adrs3'];
        $vmail1=$hasil['mail_add1'];
        $vmail2=$hasil['mail_add2'];
        $vmail3=$hasil['mail_add3'];
	
	    $data= [$vowner,$vcusno1,$vshpto1,$vcusno2,$vshpto2,$vcusnm2,$vcusgrp,$vshpaddr1,$vshpaddr2,$vshpaddr3,$vmail1,$vmail2,$vmail3];
	
        array_push($datas,$data);
	}


$xlsx = SimpleXLSXGen::fromArray( $datas );
$xlsx->downloadAs('2ndCustomer MA.xlsx');

?>
