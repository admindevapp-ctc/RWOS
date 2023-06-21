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

$namaFile = "rptawsdtlorder.xlsx";
  
// Header
$datas = [];
$header = ['Order Date', '1 st Customer code',
'1 st Customer shipto','2 nd Customer code','2 nd Customer shipto',
'PO Number', 'Part Number', 'Quantity', 'Amount','Status'];

$datas[0] = $header;


	/* Database connection information */
	require('../db/conn.inc');
	$datefrom=trim($_GET['datefrom']);
	$dateto=trim($_GET['dateto']);
	$search=trim($_GET['search']);
	$choose=trim($_GET['choose']);
	$desc=trim($_GET['desc']);
	
// $sQuery = "SELECT awsorderhdr.Owner_Comp,awsorderhdr.dealer,awsorderhdr.CUST3,awsorderhdr.cusno, shipto, partno, awsorderhdr.orderno, itdsc,awsorderdtl.ordflg, awsorderhdr.Corno, awsorderhdr.orderdate, ordtype,DueDate, qty,vt_cst_ordr_prog.INV_NO,vt_cst_ordr_prog.SHPD_YMD,vt_cst_ordr_prog.SHPD_QTY,vt_cst_ordr_prog.CST_ORDR_LN_CMPLT_FLG,vt_cst_ordr_prog.CST_ORDR_QTY   ,(qty * slsprice) as amount 
// from awsorderhdr 
    // inner join awsorderdtl on awsorderhdr.orderno=awsorderdtl.orderno and awsorderhdr.Owner_Comp=awsorderdtl.Owner_Comp".
    // " left join vt_cst_ordr_prog on awsorderdtl.cusno=vt_cst_ordr_prog.CST_CD and awsorderdtl.Corno=vt_cst_ordr_prog.CST_PO_NO  and awsorderdtl.partno =vt_cst_ordr_prog.CST_ORDR_INPT_ITEM_NO and awsorderdtl.owner_comp=vt_cst_ordr_prog.owner_comp".	    
    // " where awsorderhdr.orderdate>='$datefrom' and awsorderhdr.orderdate<='$dateto' and awsorderhdr.Owner_Comp='$comp' " ;
// $sQuery_select = "select *
	// from (";
	$sQuery_select1 = " SELECT  awsorderhdr.Owner_Comp,awsorderdtl.partno, awsorderhdr.cusno, awsorderhdr.orderno, awsorderhdr.Corno, awsorderhdr.orderdate, awsorderhdr.Dealer, itdsc, ordtype, qty,slsprice ,awsorderdtl.ordflg  as ordflg,awsorderhdr.shipto,  awscusmas.ship_to_cd1, awsorderhdr.shipto as 'ship_to_cd2',
        rejectorder.message ";
	$sQuery_from1 = "FROM awsorderhdr 
		LEFT join awsorderdtl on awsorderhdr.orderno=awsorderdtl.orderno and awsorderhdr.Owner_Comp=awsorderdtl.Owner_Comp and awsorderhdr.Corno = awsorderdtl.Corno 
		LEFT join cusmas on awsorderhdr.cusno=cusmas.Cusno AND awsorderhdr.Owner_Comp=cusmas.Owner_Comp 
		LEFT JOIN awscusmas ON awscusmas.Owner_Comp = awsorderhdr.Owner_Comp AND awscusmas.cusno1 = awsorderhdr.Dealer AND awscusmas.ship_to_cd2 = awsorderhdr.shipto
		LEFT JOIN shiptoma ON shiptoma.Cusno = awscusmas.cusno1 AND shiptoma.ship_to_cd = awscusmas.ship_to_cd1
		LEFT JOIN rejectorder ON rejectorder.Owner_Comp = awsorderhdr.Owner_Comp and rejectorder.orderno = awsorderhdr.orderno and rejectorder.partno = awsorderdtl.partno
		where awsorderhdr.orderdate>='$datefrom' and awsorderhdr.orderdate<='$dateto'  and awsorderhdr.Owner_Comp='$comp'";
	
	
	// $union = "UNION";
		
	// $sQuery_select2 = "	SELECT supawsorderhdr.Owner_Comp, supawsorderdtl.partno, supawsorderhdr.cusno, supawsorderhdr.orderno, supawsorderhdr.Corno, supawsorderhdr.orderdate, supawsorderhdr.Dealer, itdsc, ordtype, qty,slsprice ,supawsorderdtl.ordflg COLLATE utf8_general_ci as ordflg,supawsorderhdr.shipto, 
		// CONCAT(
            // shiptoma.adrs1,
            // shiptoma.adrs2,
            // shiptoma.adrs3
        // ) AS address,
        // rejectorder.message";
	// $sQuery_from2 = " FROM supawsorderhdr 
		// inner join supawsorderdtl on supawsorderhdr.orderno=supawsorderdtl.orderno and supawsorderhdr.Owner_Comp=supawsorderdtl.Owner_Comp and supawsorderhdr.Corno = supawsorderdtl.Corno 
		// inner join cusmas on supawsorderhdr.cusno=cusmas.Cusno AND supawsorderhdr.Owner_Comp=cusmas.Owner_Comp 
		// INNER JOIN awscusmas ON awscusmas.Owner_Comp = supawsorderhdr.Owner_Comp AND awscusmas.cusno1 = supawsorderhdr.Dealer AND awscusmas.ship_to_cd2 = supawsorderhdr.shipto
		// INNER JOIN shiptoma ON shiptoma.Cusno = awscusmas.cusno1 AND shiptoma.ship_to_cd = awscusmas.ship_to_cd1
		// LEFT JOIN rejectorder ON rejectorder.Owner_Comp = supawsorderhdr.Owner_Comp and rejectorder.orderno = supawsorderhdr.orderno and rejectorder.partno = supawsorderdtl.partno
		// where  supawsorderhdr.orderdate>='$datefrom' and supawsorderhdr.orderdate<='$dateto'  and supawsorderhdr.Owner_Comp='$comp'
		// ) a where Owner_Comp='$comp'
	// ";

    if($search!=''){
		// echo $search;
		switch($search){
			case "partno":
				$fld="awsorderdtl.partno";
				break;
            case "corno":
                $fld="awsorderhdr.Corno";
                break;
			case "cusno1":
				$fld="awsorderhdr.cusno";
				break;
			case "cusno2":
				$fld="awsorderhdr.Dealer";
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
				if($desc=="R"){
					$op="=";
					$vdesc="'$desc'";
				}
				else if($desc=="1,2"){
					$vdesc="($desc)";
				}
				else{
					$op="=";
					$vdesc="''";
				}
				break;
		}
		$sQuery_from1 = $sQuery_from1 . " and $fld $op $vdesc";	
	 }
	
				$sQuery = $sQuery_select .$sQuery_select1. $sQuery_from1;

	$noBarisCell = 2;
	$rResult = mysqli_query($msqlcon,$sQuery ) or die(mysqli_error());
	while ( $aRow = mysqli_fetch_array( $rResult ) )
	{	
        $owner_comp=$aRow['Owner_Comp'];
        $orderno=$aRow['orderno'];
        $cusno1=$aRow['Dealer'];
        $cusno2=$aRow['cusno'];
        $shipto=$aRow['shipto'];
        $partno=$aRow['partno'];
        $partdes=$aRow['itdsc'];
        $corno=$aRow['Corno'];
        $duedt=$aRow['DueDate'];
        $orderdate=$aRow['orderdate'];
        $amount = number_format($aRow['slsprice'] * $aRow['qty'],2);
        if($corno=="")$corno="-";
        $qty=number_format($aRow['qty']);
        $ordrflg=$aRow['ordflg'];
        $orddate=substr($orderdate,-2)."/".substr($orderdate,4,2)."/".substr($orderdate,0,4);


	
			if(empty($shipdate))
			{
			    $shpdate=$shipdate;
			}
			else
			{
			    $shpdate=substr($shipdate,-2)."/".substr($shipdate,5,2)."/".substr($shipdate,0,4);
			}
			
			
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
			if($qty=="0"){ 
				$ordrflg = "Cancelled"; 
			}else{
				switch($ordrflg){   
					case "1":
						$ordrflg="Completed";
					break;
					case "2":
						$ordrflg="Completed";
					break;
					case "0":
						$ordrflg="Incomplete";
					break;
					case "R":
						$ordrflg="Reject";
					break;
					case "":
						$ordrflg="Pending";
					break;
				}
			}
	

	
	    $data= [$orddate,$cusno1,$shipto,$cusno2,$shipto,$corno,$partno,$qty,$amount,$ordrflg];
	
        array_push($datas,$data);
	
	}

 
 
$xlsx = SimpleXLSXGen::fromArray( $datas );
$xlsx->downloadAs($namaFile);


	

?>
