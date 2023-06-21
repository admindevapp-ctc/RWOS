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
		$dealer=$_SESSION['dealer'];
		$group=$_SESSION['group'];
		$owner_comp = ctc_get_session_comp(); // add by CTC
	 }else{
		   echo "<script> document.location.href='../../".redir."'; </script>";
	 }
}else{	
header("Location:../../login.php");
}

$namaFile = "rptdtlorder.xlsx";
 

	/* Database connection information */
	require('../db/conn.inc');
	$datefrom=trim($_GET['datefrom']);
	$dateto=trim($_GET['dateto']);
	$search=trim($_GET['search']);
	$choose=trim($_GET['choose']);
	$desc=trim($_GET['desc']);
	
//$sQuery = "SELECT orderhdr.cusno, partno, orderhdr.orderno, itdsc, orderhdr.Corno, orderhdr.orderdate, SGPrice, CurCD,  ordtype,DueDate, qty, bprice from orderhdr inner join orderdtl on orderhdr.orderno=orderdtl.orderno".
//			  " where orderhdr.cust3='$cusno' and orderhdr.orderdate>='$datefrom' and orderhdr.orderdate<='$dateto'" ;

$sQuery =  "SELECT awsorderdtl.slsprice,awsorderhdr.CUST3,awsorderhdr.cusno, awsorderdtl.partno, awsorderhdr.orderno, itdsc, awsorderhdr.Corno, awsorderhdr.orderdate
, ordtype,DueDate, qty,vt_cst_ordr_prog.INV_NO,vt_cst_ordr_prog.SHPD_YMD,vt_cst_ordr_prog.SHPD_QTY
,vt_cst_ordr_prog.CST_ORDR_LN_CMPLT_FLG,vt_cst_ordr_prog.CST_ORDR_QTY , awsorderdtl.ordflg,
    rejectorder.message
from awsorderhdr inner join awsorderdtl on awsorderhdr.orderno=awsorderdtl.orderno and awsorderhdr.Owner_Comp=awsorderdtl.Owner_Comp ".
" left join vt_cst_ordr_prog on awsorderdtl.cusno=vt_cst_ordr_prog.CST_CD and awsorderdtl.Corno=vt_cst_ordr_prog.CST_PO_NO  
    and awsorderdtl.partno =vt_cst_ordr_prog.CST_ORDR_INPT_ITEM_NO and awsorderdtl.owner_comp=vt_cst_ordr_prog.owner_comp".	
	" LEFT JOIN rejectorder on rejectorder.Owner_Comp = awsorderhdr.Owner_Comp and rejectorder.orderno = awsorderhdr.orderno AND rejectorder.partno = awsorderdtl.partno ".
" where awsorderhdr.cusno = '$cusno' and awsorderhdr.orderdate>='$datefrom' and awsorderhdr.orderdate<='$dateto' and awsorderhdr.Owner_Comp='$owner_comp' " ;//12/20/2018 P.Pawan CTC add get shipto //25/11/2019 Zia added order progress

if($search!=''){
		// echo $search;
		switch($search){
			case "partno":
				$fld="awsorderdtl.partno";
				break;
			case "ITDSC":
				$fld="awsorderdtl.itdsc";
				break;
			case "corno":
				$fld="awsorderhdr.Corno";
				break;
            case "statusapprove":
                $fld="awsorderdtl.ordflg";
                break;
            case "invoice":
                $fld="vt_cst_ordr_prog.INV_NO";
                break;
			case "status":
				$fld="awsorderdtl.ordflg";
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
				if($desc == "R") {
					$desc="'$desc'";
				}
				$desc  = str_replace(",NULL", "'',NULL", $desc);
				$vdesc="($desc)";
		}
		$sQuery = $sQuery . " and $fld $op $vdesc";	
	 }
				
$datas = [];
// Header
$header = ['Part Number' ,'Part Name', 'PO Number',
'Order date','Due date','QTY','Amount','Order Approve Status'
, 'Ship Date', 'Ship Qty','Invoice No.','Status'];

$datas[0] = $header;
	$noBarisCell = 1;
	$rResult = mysqli_query($msqlcon,$sQuery ) or die(mysqli_error());
	// echo $sQuery ;
	// exit;
	while ( $aRow = mysqli_fetch_array( $rResult ) )
	{
			$shpno=$aRow['cusno'];
			$shpto=$aRow['shipto'];
			$shptonm=$aRow['ship_to_nm'];
			$orderno=$aRow['orderno'];
			$partno=$aRow['partno'];
			$corno=$aRow['Corno'];
			$desc=$aRow['itdsc'];
			if($corno=="")$corno="-";
			$qty=$aRow['qty'];
			$slsprice=$aRow['slsprice'];
			$amount = ($qty * $slsprice);
			$bprice=$aRow['bprice'];
			//$SGPrice=$aRow['SGPrice'];
			$curcd=$aRow['CurCD'];
			$total=($qty*$bprice);
			//$totalsg=($qty*$bprice*$SGPrice);
			$orderdate=$aRow['orderdate'];
			$ordflg=$aRow['ordflg'];
			$duedate=$aRow['DueDate'];
			$shipdate=$aRow['SHPD_YMD']; // Zia Added for Order Progress
			$slsqty=$aRow['SHPD_QTY']; // Zia Added for Order Progress
			$invno=$aRow['INV_NO']; // Zia Added for Order Progress
			$cstqty=$aRow['CST_ORDR_QTY']; // Zia Added for Order Progress
			$ordrflg=number_format($aRow['CST_ORDR_LN_CMPLT_FLG']); // Zia Added for Order Progress
			$orddate=substr($orderdate,-2)."/".substr($orderdate,4,2)."/".substr($orderdate,0,4);
			$duedt=substr($duedate,-2)."/".substr($duedate,4,2)."/".substr($duedate,0,4);
			
//Zia Added for Order Progress > Start	
	
			if(empty($shipdate))
			{
			// it's empty!
			$shpdate=$shipdate;
			}
			else
			{
			$shpdate=substr($shipdate,-2)."/".substr($shipdate,5,2)."/".substr($shipdate,0,4);
			}
			
		//echo "<script type='text/javascript'>alert(\"$shpdate\");</script>";

//Zia Added for Order Progress	> End
			
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
				case "1":
					$ordrflg="Ship from Supplier";
					$ordertext="Complete";
					break;
				case "2":
					$ordrflg="Ship from warehouse";
					$ordertext="Complete";
					break;
				case "R":
					$ordrflg="Reject : ". $aRow['message'];
					$ordertext="Complete";
					break;
				case "":
					$ordrflg="Pending";
					$ordertext="Incomplete";
					break;
				case "NULL":
					$ordrflg="Pending";
					$ordertext="Incomplete";
					break;
				}
	

		$data= [$partno,$desc,$corno,$orddate,$duedt,number_format($qty,0),number_format($amount,2),$ordrflg,
        $shpdate,$slsqty,$invno,$ordertext];
	
		array_push($datas,$data);
	
	
	}

 


	$xlsx = SimpleXLSXGen::fromArray( $datas );
	$xlsx->downloadAs('reportordersup.xlsx');
 
?>
<?php

function checkcolumn($column, $value) {
	$return="";
	if($column == "statusapprove"){
		switch($value){
			case "shipfromsupplier":
				$return = "1";
				break;
			case "shipfromwarehouse":
				$return = "2";
				break;
			case "reject":
				$return = "R";
				break;
			case "r":
				$return = "R";
				break;
			case "1":
				$return = "1";
				break;
			case "2":
				$return = "2";
				break;
			default :
				$return = "";
				break;
		}
	}
	else{
		switch($value){
			case "complete":
				$return = "1";
				break;
			case "1":
				$return = "1";
				break;
			case "reject":
				$return = "R";
				break;
			case "r":
				$return = "R";
				break;
			case "incomplete":
				$return = "R";
				break;
			case "2":
				$return = "2";
				break;
			default :
				$return = "";
				break;
		}
	}
	return $return;
}

?>