<? session_start() ;
require_once('../../core/ctc_init.php'); // add by CTC
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
  
}else{	
header("Location: ../login.php");
}

$page=trim($_GET['page']);
$sort=trim($_GET['sort']);
$namafield=trim($_GET['namafield']);
$search=trim($_GET['search']);
$choose=trim($_GET['choose']);
$desc=trim($_GET['desc']);
$owner_comp = ctc_get_session_comp(); // add by CTC
 
// header file excel
$namaFile="ItemMaster.xlsx"; 

				
$datas = [];
// Header
$header = ['Part Number' ,'Part Name', 'Product',
'Sub Product','Lot Size','Currency Code','Price'];

$datas[0] = $header;
 
	/* Database connection information */
	require('../db/conn.inc');
	$qrycusmas="SELECT `cusgrp` FROM `awscusmas` WHERE `Owner_Comp` = '$owner_comp' and `cusno2` = '$cusno'";
	$sqlcusmas=mysqli_query($msqlcon,$qrycusmas);		
	$comp='(';
	$flag='';		
	while($hslcusmas = mysqli_fetch_array ($sqlcusmas)){
	  $cros=$hslcusmas[cusgrp];
	  if($flag==''){
		$comp=$comp . "'" . $cros . "'";
	  $flag='1';
	}else{
	  $comp=$comp .",'".$cros. "'";
	  }
	}
	$comp=$comp .')';
	
	
	//$sQuery = "SELECT bm008pr.ITNBR, bm008pr.ITDSC, bm008pr.PRODUCT, bm008pr.SUBPROD,bm008pr.Lotsize,  Sellprice.CURCD,Sellprice.Price  FROM sellprice inner join `bm008pr` on sellprice.itnbr=bm008pr.itnbr and sellprice.Owner_Comp=bm008pr.Owner_Comp where Sellprice.cusno in  $comp and Sellprice.Owner_Comp='$owner_comp' ";
	$sQuery = "SELECT  distinct awsexc.price,  awsexc.curr, bm008pr.ITNBR, bm008pr.ITDSC, bm008pr.PRODUCT, bm008pr.SUBPROD,bm008pr.Lotsize 
    from awsexc 
    Left join bm008pr on bm008pr.ITNBR = awsexc.itnbr and bm008pr.Owner_comp = awsexc.Owner_comp
     Where awsexc.Owner_comp = '$owner_comp' 
	 and awsexc.cusgrp in $comp  ";
    if($search!=''){
		//echo $search;
		switch($search){
			case "partno":
				$fld="bm008pr.ITNBR";
				break;
			case "ITDSC":
				$fld="bm008pr.itdsc";
				break;
			case "product":
				$fld="bm008pr.product";
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
		$sQuery = $sQuery . " and $fld $op '$vdesc'";	
	 }
	
	 if($namafield!=''){
			$sQuery = $sQuery . " order by bm008pr.$namafield $sort";		  
	  }else{
			$sQuery = $sQuery . " order by bm008pr.ITNBR";		   
	  }	

	$noBarisCell = 1;
	//echo $sQuery ;
	$rResult = mysqli_query($msqlcon, $sQuery ) or die(mysqli_error());
	while ( $aRow = mysqli_fetch_array( $rResult ) )
	{
		$partno=$aRow['ITNBR'];
		$partdes=$aRow['ITDSC'];
		$product=$aRow['PRODUCT'];
		$sub=$aRow['SUBPROD'];
		$lot=number_format($aRow['Lotsize']);
		$curcd=$aRow['curr'];
		$price=number_format($aRow['price'], 2);
			
		$data= [$partno,$partdes,$product,$sub,$lot,$curcd,$price];
	
		array_push($datas,$data);

	
	
	}




	$xlsx = SimpleXLSXGen::fromArray( $datas );
	$xlsx->downloadAs($namaFile);

	

?>
