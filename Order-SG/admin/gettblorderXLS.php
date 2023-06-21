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
		$dealer=$_SESSION['dealer'];
		$group=$_SESSION['group'];
		$comp = ctc_get_session_comp(); // add by CTC

	 }else{
		echo "<script> document.location.href='../".redir."'; </script>";
	 }
}else{	
	header("Location:../login.php");
}

$datas = [];
// $header = ['PO number','PO Date','Part Number','Part Name','Due Date','QTY','Price','Amount','Ship Date','Ship Qty','Invoice No','Status'];//edit by CTC Pasakorn 03/11/2022
$header = ['Customer No.','Ship to','Ship to Name','PO number','PO Date','Order Status','Part Number','Part Name','QTY','Currency','Price','Amount','Due Date','Ship Date','Ship Qty','Invoice No','Status'];//edit by CTC Pasakorn 03/11/2022
$datas[0] = $header;

// -------- menampilkan data --------- //



	/* Database connection information */
	require('../db/conn.inc');
	$datefrom=trim($_GET['datefrom']);
	$dateto=trim($_GET['dateto']);
	$search=trim($_GET['search']);
	$choose=trim($_GET['choose']);
	$desc=trim($_GET['desc']);

$sQuery = "SELECT  orderdtl.slsprice,orderhdr.cusno, partno, orderhdr.orderno, itdsc, orderhdr.Corno, orderhdr.orderdate, SGPrice, CurCD,  ordtype,DueDate, qty, bprice,shipto,vt_cst_ordr_prog.SHPD_YMD,vt_cst_ordr_prog.SHPD_QTY,vt_cst_ordr_prog.CST_ORDR_LN_CMPLT_FLG,vt_cst_ordr_prog.INV_NO,vt_cst_ordr_prog.CST_ORDR_QTY,shiptoma.ship_to_nm  
from orderhdr 
inner join orderdtl on orderhdr.orderno=orderdtl.orderno and orderhdr.Owner_Comp=orderdtl.Owner_Comp".
" left join vt_cst_ordr_prog on orderdtl.cusno=vt_cst_ordr_prog.CST_CD and orderdtl.Corno=vt_cst_ordr_prog.CST_PO_NO  and orderdtl.partno =vt_cst_ordr_prog.CST_ORDR_INPT_ITEM_NO and orderdtl.owner_comp=vt_cst_ordr_prog.owner_comp LEFT JOIN shiptoma on orderhdr.cusno=shiptoma.Cusno and orderhdr.Owner_Comp=shiptoma.Owner_Comp and orderhdr.shipto=shiptoma.ship_to_cd".	
" where orderhdr.orderdate>='$datefrom' and orderhdr.orderdate<='$dateto' and orderhdr.Owner_Comp='$comp' " ;//12/20/2018 P.Pawan CTC add get shipto //25/11/2019 Zia added order progress



if($search!=''){
		// echo $search;
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
		$sQuery = $sQuery . " and $fld $op '$vdesc'";	
	 }

	
	$noBarisCell = 1;
	$rResult = mysqli_query($msqlcon,$sQuery ) or die(mysqli_error());
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
			$bprice=$aRow['bprice'];
			$slsamt=$aRow['slsprice'];
			//$SGPrice=$aRow['SGPrice'];
			$curcd=$aRow['CurCD'];
			$total=($qty*$slsamt);
			//$totalsg=($qty*$bprice*$SGPrice);
			$orderdate=$aRow['orderdate'];
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
			if($cstqty=="0"){ //Zia Added for Order Progress check//
				$ordrflg = "Cancelled"; 
			}else{
				switch($ordrflg){   
					case "1":
						$ordrflg="Completed";
					break;
					case "0":
						$ordrflg=" ";
					break;
				}
			}
		// $data= [$corno,$orddate,$partno,$desc,$duedt,number_format($qty,0),strval(number_format($slsamt,2)),strval(number_format($slsamt * $qty,2)),$shpdate,$invno,$ordrflg];
		$data= [$shpno,$shpto,$shptonm,$corno,$orddate,$ordsts,$partno,$desc,number_format($qty,0),$curcd,strval(number_format($slsamt,2)),strval(number_format($slsamt * $qty,2)),$duedt,$shpdate,$invno,$ordrflg];

		array_push($datas,$data);
	}

 $xlsx = SimpleXLSXGen::fromArray( $datas );
$xlsx->downloadAs('report.xlsx');

	

?>
