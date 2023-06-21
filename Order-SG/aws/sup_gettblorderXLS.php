<?php 

session_start();

require_once('../../core/ctc_init.php'); 

$owner_comp = ctc_get_session_comp(); 


require_once('../../language/Lang_Lib.php');
$cusno = ctc_get_session_cusno();
$cusnm = ctc_get_session_cusnm();


/* Database connection information */
    require('../db/conn.inc');
	$datefrom=trim($_GET['datefrom']);
	$dateto=trim($_GET['dateto']);
	$search=trim($_GET['search']);
	$choose=trim($_GET['choose']);
	$desc=trim($_GET['desc']);
	
	$sQuery = "
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
		rejectorder.message
	FROM
		supawsorderhdr
	INNER JOIN supawsorderdtl ON supawsorderhdr.orderno = supawsorderdtl.orderno AND supawsorderhdr.Owner_Comp = supawsorderdtl.Owner_Comp AND supawsorderhdr.Corno = supawsorderdtl.Corno AND supawsorderhdr.cusno = supawsorderdtl.cusno AND supawsorderhdr.supno = supawsorderdtl.supno
	LEFT JOIN supawsordernts ON supawsorderhdr.orderno = supawsordernts.orderno AND supawsorderhdr.Owner_Comp = supawsordernts.Owner_Comp AND supawsorderhdr.Corno = supawsordernts.Corno AND supawsorderhdr.cusno = supawsordernts.cusno AND supawsorderhdr.supno = supawsordernts.supno
	LEFT JOIN rejectorder ON rejectorder.orderno = supawsorderhdr.orderno AND rejectorder.partno = supawsorderdtl.partno
	WHERE
		supawsorderhdr.orderdate >= '$datefrom' AND supawsorderhdr.orderdate <= '$dateto' AND supawsorderhdr.Owner_Comp = '$owner_comp' AND supawsorderhdr.cusno = '$cusno'" ;


	// echo $sQuery;

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
    $sQuery = $sQuery . " and $fld $op  $vdesc";	
 }

//	echo 	$sQuery ;
		
$datas = [];
// Header
$header = ['Part Number' ,'Part Name', 'PO number',
'Order date','Due date','QTY','Amount','Order Approve Status'
, 'Ship Date', 'Ship Qty','Supplier Code','Remark','Status'];

$datas[0] = $header;
	$noBarisCell = 1;
	$rResult = mysqli_query($msqlcon,$sQuery ) or die(mysqli_error());
	while ( $aRow = mysqli_fetch_array( $rResult ) )
	{
		$supno=$aRow['supno'];
		$cusno=$aRow['cusno'];
		$vcust3=$aRow['CUST3'];
		$partno=$aRow['partno'];
		$partdes=$aRow['itdsc'];
		$corno=$aRow['Corno'];
		$duedt=$aRow['DueDate'];
        $ordflg=$aRow['ordflg'];
		if($corno=="")$corno="-";
		$qty=number_format($aRow['qty'],0);
		$price=number_format($aRow['slsprice'] * $aRow['qty'],2);
		$remark=$aRow['remark'];
		$orderdate=$aRow['orderdate'];
		$reason = $aRow['message'];
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
			
		$data= [$partno,$partdes,$corno,$orddate,$duedate,$qty,$price,$ordtext,
        $shpdate,$qty,$supno,$remark,$ordertext];
	
		array_push($datas,$data);
	
	
	}


$xlsx = SimpleXLSXGen::fromArray( $datas );
$xlsx->downloadAs('reportordersup.xlsx');
	

?>
