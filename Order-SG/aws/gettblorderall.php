

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
		//$dealer=$_SESSION['dealer'];
		$group=$_SESSION['group'];
		$owner_comp = ctc_get_session_comp(); // add by CTC
	}else{
		echo "<script> document.location.href='../../".redir."'; </script>";
	}
}else{	
	header("Location:../../login.php");
}
	$per_page=10;
	
	/* Database connection information */
	require('../db/conn.inc');
	$datefrom=trim($_GET['datefrom']);
	$dateto=trim($_GET['dateto']);
	$page=trim($_GET['page']);
	$sort=trim($_GET['sort']);
	$namafield=trim($_GET['namafield']);
	$search=trim($_GET['search']);
	$choose=trim($_GET['choose']);
	$desc=trim($_GET['description']);
	
	// Customer master..
	// Because SDML have 2 User so, we should use cusno instead of cust3
	$qrycusmas="select cusno from cusmas where cust3= '$cusno' and Owner_Comp='$owner_comp' ";
	$sqlcusmas=mysqli_query($msqlcon,$qrycusmas);		
	$comp='(';
	$flag='';		
	while($hslcusmas = mysqli_fetch_array ($sqlcusmas)){
	  $cros=$hslcusmas['cusno'];
	  if($flag==''){
	  	$comp=$comp .$cros;
		$flag='1';
	  }else{
		  	$comp=$comp .','.$cros;
	  }
	}
	$comp=$comp .')';
	//echo $qrycusmas;
	
	
	
	//total Row Count
	//$sql = "SELECT * from awsorderhdr inner join awsorderdtl on awsorderhdr.orderno=awsorderdtl.orderno where awsorderhdr.cust3='$cusno' and awsorderhdr.orderdate>='$datefrom' and awsorderhdr.orderdate<='$dateto'";
	$sql = "SELECT distinct awsorderdtl.slsprice,awsorderhdr.CUST3,awsorderhdr.cusno, partno, awsorderhdr.orderno, itdsc, awsorderhdr.Corno, awsorderhdr.orderdate
	, ordtype,DueDate, qty,vt_cst_ordr_prog.INV_NO,vt_cst_ordr_prog.SHPD_YMD,vt_cst_ordr_prog.SHPD_QTY
	,vt_cst_ordr_prog.CST_ORDR_LN_CMPLT_FLG,vt_cst_ordr_prog.CST_ORDR_QTY , awsorderdtl.ordflg
	from awsorderhdr inner join awsorderdtl on awsorderhdr.orderno=awsorderdtl.orderno and awsorderhdr.Owner_Comp=awsorderdtl.Owner_Comp ".
	" left join vt_cst_ordr_prog on awsorderdtl.cusno=vt_cst_ordr_prog.CST_CD and awsorderdtl.Corno=vt_cst_ordr_prog.CST_PO_NO  
		and awsorderdtl.partno =vt_cst_ordr_prog.CST_ORDR_INPT_ITEM_NO and awsorderdtl.owner_comp=vt_cst_ordr_prog.owner_comp".	
	" where awsorderhdr.cusno = '$cusno' and awsorderhdr.orderdate>='$datefrom' and awsorderhdr.orderdate<='$dateto' and awsorderhdr.Owner_Comp='$owner_comp' ";
	
	$result = mysqli_query($msqlcon,$sql);
	$count = mysqli_num_rows($result);
	//echo $count ;
	$pages = ceil($count/$per_page);
	$page = $_GET['page'];
	if($page){ 
		$start = ($page - 1) * $per_page; 			
	}else{
		$start = 0;	
		$page=1;
	}
	$sQuery_select = "SELECT distinct awsorderdtl.slsprice,awsorderhdr.CUST3,awsorderhdr.cusno, awsorderdtl.partno, awsorderhdr.orderno, itdsc, awsorderhdr.Corno, awsorderhdr.orderdate
	, ordtype,DueDate, qty,vt_cst_ordr_prog.INV_NO,vt_cst_ordr_prog.SHPD_YMD,vt_cst_ordr_prog.SHPD_QTY
	,vt_cst_ordr_prog.CST_ORDR_LN_CMPLT_FLG,vt_cst_ordr_prog.CST_ORDR_QTY , awsorderdtl.ordflg,
    rejectorder.message ";
	$sQuery_from = " from awsorderhdr inner join awsorderdtl on awsorderhdr.orderno=awsorderdtl.orderno and awsorderhdr.Owner_Comp=awsorderdtl.Owner_Comp ".
	" left join vt_cst_ordr_prog on awsorderdtl.cusno=vt_cst_ordr_prog.CST_CD and awsorderdtl.Corno=vt_cst_ordr_prog.CST_PO_NO  
		and awsorderdtl.partno =vt_cst_ordr_prog.CST_ORDR_INPT_ITEM_NO and awsorderdtl.owner_comp=vt_cst_ordr_prog.owner_comp".	
	" LEFT JOIN rejectorder on rejectorder.Owner_Comp = awsorderhdr.Owner_Comp and rejectorder.orderno = awsorderhdr.orderno AND rejectorder.partno = awsorderdtl.partno".
	" where awsorderhdr.cusno = '$cusno' and awsorderhdr.orderdate>='$datefrom' and awsorderhdr.orderdate<='$dateto' and awsorderhdr.Owner_Comp='$owner_comp' " ;//12/20/2018 P.Pawan CTC add get shipto //25/11/2019 Zia added order progress

	// echo $sQuery;	 

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
		$sQuery_from = $sQuery_from . " and $fld $op $vdesc";		
	 }
	
	 if($namafield!=''){
			$sQuery_from = $sQuery_from . " order by awsorderdtl.$namafield $sort, awsorderhdr.orderdate";		  
	  }else{
			$sQuery_from = $sQuery_from . " order by awsorderdtl.partno, awsorderhdr.orderdate";		   
	  }	

	  $sQuery_sum = "SELECT SUM(awsorderdtl.qty) as sum_qty , SUM(awsorderdtl.qty * awsorderdtl.slsprice) as sum_total ".$sQuery_from;
		
		$rSumResult = mysqli_query($msqlcon,$sQuery_sum) or die(mysqli_error());
		$sumqty = 0;
		$sumamnt = 0;
		while ( $sumRow = mysqli_fetch_array( $rSumResult ) )
		{
			$sumqty = number_format($sumRow['sum_qty']);
			$sumamnt = number_format($sumRow['sum_total'],2);

		}
	  

		$sQuery= $sQuery_select . $sQuery_from ." LIMIT $start, $per_page";
	
	// /echo  $sQuery;
	$i="0";
	//echo $sQuery;
	$rResult = mysqli_query($msqlcon,$sQuery) or die(mysqli_error());
	while ( $aRow = mysqli_fetch_array( $rResult ) )
	{
		
		if($i=="0"){
		
			echo "<table 	border=\"0\" cellspacing=\"0\" cellpadding=\"0\" width=\"100%\">";
			$i="1";
		}		
		
			$reject_reason = "";
			$orderno=$aRow['orderno'];
			$vcust3=$aRow['CUST3'];
			$partno=$aRow['partno'];
			$partdes=$aRow['itdsc'];
			$corno=$aRow['Corno'];
			$duedt=$aRow['DueDate'];
			$slsprice=$aRow['slsprice'];
			$amount = number_format($aRow['slsprice'] * $aRow['qty'],2);
			//$shpno=$aRow['shipto'];//12/20/2018 P.Pawan CTC get shipto
			if($corno=="")$corno="-";
			$qty=number_format($aRow['qty']);
			$orderdate=$aRow['orderdate'];
			$shipdate=$aRow['SHPD_YMD'];
			$slsqty=$aRow['SHPD_QTY'];
			$invno=$aRow['INV_NO'];
			$cstqty=$aRow['CST_ORDR_QTY'];
			$ordrflg=$aRow['ordflg'];
			$orddate=substr($orderdate,-2)."/".substr($orderdate,4,2)."/".substr($orderdate,0,4);
			$duedate=substr($duedt,-2)."/".substr($duedt,4,2)."/".substr($duedt,0,4);

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
			
			
			switch($ordrflg){   
				case "1":
					$ordrflg="Ship from Supplier";
					$ordertext="Complete";
					break;
				case "2":
					$ordrflg="Ship from warehouse";
					$ordertext="Complete";
					break;
				case "R":
					$ordrflg="Reject";
					$reject_reason= " : ".$aRow['message'];
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

			echo "<tr height=\"30\">";
			echo "<td width=\"8% \" style=\"padding:0 5px\">".$partno."</td>";
			echo "<td width=\"13%\" style=\"padding:0 5px\">".$partdes."</td>";
			echo "<td width=\"8% \" align=\"center\">".$corno."</td>";
			echo "<td width=\"8% \" align=\"center\">".$orddate."</td>";
			echo "<td width=\"8% \" align=\"center\">".$duedate."</td>";
			echo "<td width=\"3% \" align=\"center\">".$qty."</td>";
			echo "<td width=\"8% \" align=\"right\" style=\"padding-right:5px;\">".$amount."</td>";
			echo "<td width=\"10%\"  align=\"center\">".$ordrflg. $reject_reason."</td>";
			echo "<td width=\"8% \" align=\"center\">".$shpdate."</td>";    //Zia Added for Order Progress check//
			echo "<td width=\"8% \" align=\"center\">".$slsqty."</td>";
			echo "<td width=\"8% \" align=\"center\">".$invno."</td>";
			                   
			if($ordertext=="Incomplete"){
				echo "<td width=\"8%\" align=\"center\" class=\"lasttd\" style=\"color: blue\" >".$ordertext."</td>";
			}
			elseif($ordertext=="Cancelled"){
				echo "<td width=\"8%\" align=\"center\" class=\"lasttd\" style=\"color: #800000\" >".$ordertext."</td>";
			}
			else{
				echo "<td width=\"8%\" align=\"center\" class=\"lasttd\" >".$ordertext."</td>";
			}
			

			echo "</tr>";
				}
	if($i=="1") echo "</table>";
	echo '<input type="hidden" id="hid_sum_qty" value="'.$sumqty.'">';
	echo '<input type="hidden" id="hid_sum_amnt" value="'.$sumamnt.'">';
	
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
