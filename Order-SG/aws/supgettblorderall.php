<?php 

session_start();

require_once('../../core/ctc_init.php'); // add by CTC

$owner_comp = ctc_get_session_comp(); // add by CTC

require_once('../../language/Lang_Lib.php');
$cusno = ctc_get_session_cusno();
$cusnm = ctc_get_session_cusnm();

	$per_page=10;
	$num=5;
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
	
	/*
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

	
	echo $qrycusmas;
	
	*/
	//total Row Count
	//$sql = "SELECT * from orderhdr inner join orderdtl on orderhdr.orderno=orderdtl.orderno where orderhdr.cust3='$cusno' and orderhdr.orderdate>='$datefrom' and orderhdr.orderdate<='$dateto'";
	$sql = "SELECT * from supawsorderhdr inner join supawsorderdtl on supawsorderhdr.orderno=supawsorderdtl.orderno and supawsorderhdr.Owner_Comp=supawsorderdtl.Owner_Comp where supawsorderhdr.orderdate>='$datefrom' and supawsorderhdr.orderdate<='$dateto' and supawsorderhdr.Owner_Comp='$owner_comp' and supawsorderhdr.cusno ='$cusno'";
//	echo $sql;
	$result = mysqli_query($msqlcon,$sql);
	$count = mysqli_num_rows($result);
	$pages = ceil($count/$per_page);
	$page = $_GET['page'];
	if($page){ 
		$start = ($page - 1) * $per_page; 			
	}else{
		$start = 0;	
		$page=1;
	}
	
	
//$sQuery = "SELECT orderhdr.cusno, partno, orderhdr.orderno, itdsc, orderhdr.Corno, orderhdr.orderdate, ordtype,DueDate, qty  from orderhdr inner join orderdtl on orderhdr.orderno=orderdtl.orderno".
//			  " where orderhdr.cust3='$cusno' and orderhdr.orderdate>='$datefrom' and orderhdr.orderdate<='$dateto'" ;
$sQuery_select = "
SELECT
    supawsorderhdr.supno,
    supawsorderhdr.CUST3,
    supawsorderhdr.cusno,
    supawsorderdtl.partno,
    supawsorderhdr.orderno,
	supawsorderdtl.qty,
	supawsorderdtl.slsprice,
    itdsc,
    supawsorderhdr.Corno,
    supawsorderhdr.orderdate,
    supawsorderhdr.ordtype,
    DueDate,
    supawsordernts.remark,
    supawsorderdtl.ordflg,
	rejectorder.message	";
	
$sQuery_from = 	" 
	FROM
		supawsorderhdr
	INNER JOIN supawsorderdtl ON supawsorderhdr.orderno = supawsorderdtl.orderno AND supawsorderhdr.Owner_Comp = supawsorderdtl.Owner_Comp AND supawsorderhdr.Corno = supawsorderdtl.Corno AND 	supawsorderhdr.cusno = supawsorderdtl.cusno AND supawsorderhdr.supno = supawsorderdtl.supno
	LEFT JOIN supawsordernts ON supawsorderhdr.orderno = supawsordernts.orderno AND supawsorderhdr.Owner_Comp = supawsordernts.Owner_Comp AND supawsorderhdr.Corno = supawsordernts.Corno AND supawsorderhdr.cusno = supawsordernts.cusno AND supawsorderhdr.supno = supawsordernts.supno
	LEFT JOIN rejectorder ON rejectorder.orderno = supawsorderhdr.orderno and rejectorder.partno = supawsorderdtl.partno
	where supawsorderhdr.orderdate>='$datefrom' and supawsorderhdr.orderdate<='$dateto' and supawsorderhdr.Owner_Comp='$owner_comp'  and supawsorderhdr.cusno ='$cusno'" ;


	//echo $sQuery;

if($search!=''){
		// echo $search;
		switch($search){
			case "partno":
				$fld="supawsorderdtl.partno";
				break;
			case "ITDSC":
				$fld="supawsorderdtl.itdsc";
				break;
			case "corno":
				$fld="supawsorderhdr.Corno";
				break;
			case "cuscode":
				$fld="supawsorderhdr.cusno";
				break;
			case "supcode":
				$fld="supawsorderhdr.supno";
				break;
            case "statusapprove":
                $fld="supawsorderdtl.ordflg";
                break;
            case "invoice":
                $fld="vt_cst_ordr_prog.INV_NO";
                break;
			case "status":
				$fld="supawsorderdtl.ordflg";
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
		$sQuery_from = $sQuery_from . " and $fld $op  $vdesc";	
	 }
	
	 if($namafield!=''){
		$sQuery_from = $sQuery_from . " order by supawsorderdtl.$namafield $sort, supawsorderhdr.orderdate";		  
	}else{
		$sQuery_from = $sQuery_from . " order by  supawsorderhdr.orderdate desc ,supawsorderhdr.Corno, supawsorderdtl.partno";		   
	}	
	$sQuery_sum = "SELECT SUM(supawsorderdtl.qty) as sum_qty , SUM(supawsorderdtl.qty * supawsorderdtl.slsprice) as sum_total ".$sQuery_from;
		$rSumResult = mysqli_query($msqlcon,$sQuery_sum) or die(mysqli_error());
		$sumqty = 0;
		$sumamnt = 0;
		while ( $sumRow = mysqli_fetch_array( $rSumResult ) )
		{
			$sumqty = number_format($sumRow['sum_qty']);
			$sumamnt = number_format($sumRow['sum_total'],2);

		}
	$sQuery_from=$sQuery_from ." LIMIT $start, $per_page";
	$sQuery = $sQuery_select . $sQuery_from;

	// echo  $sQuery;
	$i="0";
	$rResult = mysqli_query($msqlcon,$sQuery) or die(mysqli_error());
	while ( $aRow = mysqli_fetch_array( $rResult ) )
	{
		
		if($i=="0"){
		
			echo "<table 	border=\"0\" cellspacing=\"0\" cellpadding=\"0\" width=\"100%\">";
			$i="1";
		}		
			$supno=$aRow['supno'];
			$cusno=$aRow['cusno'];
			$vcust3=$aRow['CUST3'];
			$partno=$aRow['partno'];
			$partdes=$aRow['itdsc'];
			$corno=$aRow['Corno'];
			$duedt=$aRow['DueDate'];
			$ordflg=$aRow['ordflg'];
			$reason=$aRow['message'];
			if($corno=="")$corno="-";
			$qty=number_format($aRow['qty'],0);
			$price=number_format($aRow['slsprice'] * $aRow['qty'],2);

			$orderdate=$aRow['orderdate'];
			$remark=$aRow['remark'];
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
			
			if($cstqty=="0"){ //Zia Added for Order Progress check//
				$ordrflg = "Cancelled"; 
			}else{
				switch($ordrflg){   
					case "1":
						$ordrflg="Completed";
					break;
					case "0":
						$ordrflg="Incomplete";
					break;
					case "NULL":
						$ordrflg="";
					break;
				}
			}
			
			switch($ordflg){   
				case "1":
					$ordtext="Ship from Supplier";
					$ordertext="Complete";
					break;
				case "2":
					$ordtext="Shio from Warehouse";
					$ordertext="Complete";
					break;
				case "R":
					$ordtext="Reject : ". $reason;
					$ordertext="Complete";
					break;
				default:
					$ordtext="Pending";
					$ordertext="Incomplete";
					break;
			}
			

			echo "<tr height=\"30\">";
			echo "<td width=\"7% \" align=\"Center\">".$partno."</td>";
			echo "<td width=\"7% \" align=\"Center\">".$partdes."</td>";
			echo "<td width=\"7% \" align=\"Center\">".$corno."</td>";
			echo "<td width=\"7% \" align=\"Center\">".$orddate."</td>";
			echo "<td width=\"7% \" align=\"Center\">".$duedate."</td>";
			echo "<td width=\"4% \" align=\"Center\">".$qty."</td>";
			echo "<td width=\"7% \" style=\"padding-right:5px;\" align=\"Right\">".$price."</td>";
			echo "<td width=\"7% \" align=\"Center\">".$ordtext."</td>";
			echo "<td width=\"7% \" align=\"Center\">".$duedate."</td>";
			echo "<td width=\"7% \" align=\"Center\">".$qty."</td>"; 
			echo "<td width=\"7% \" align=\"Center\">".$supno."</td>";  
			echo "<td width=\"7% \">".$remark."</td>";  
			echo "<td width=\"7% \" align=\"Center\" class=\"lasttd\" style=\" word-wrap: break-word; word-break: break-all;\">".$ordertext."</td>";  
			
			
			echo "</tr>";
				}
	if($i=="1") echo "</table>";
	echo '<input type="hidden" id="hid_sum_qty" value="'.$sumqty.'">';
	echo '<input type="hidden" id="hid_sum_amnt" value="'.$sumamnt.'">';
	/*
	for($x=1; $x<=$pages; $x++)
	{
		if($i=="1"){
			echo "<ul id=\"pagination\">";
			$i=2;
		}
		if($page==$x){
			echo '<li id="'.$x.'" class="current">'.$x.'</li>';
		}else{
			echo '<li id="'.$x.'">'.$x.'</li>';
		}
	}
	if($i=="2") echo "</ul>"; 
*/
?>