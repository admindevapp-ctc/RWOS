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
		//$dealer=$_SESSION['dealer'];
		$group=$_SESSION['group'];
		$comp = ctc_get_session_comp(); // add by CTC
	 }else{
		   echo "<script> document.location.href='../".redir."'; </script>";
	 }
}else{	
	header("Location:../login.php");
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
	
	//total Row Count
	$sql = "SELECT * from awsorderhdr inner join awsorderdtl on awsorderhdr.orderno=awsorderdtl.orderno and awsorderhdr.Owner_Comp=awsorderdtl.Owner_Comp where awsorderhdr.orderdate>='$datefrom' and awsorderhdr.orderdate<='$dateto' and awsorderhdr.Owner_Comp='$comp' ";

	//echo $sql."<br/>";
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
	
	
//$sQuery = "SELECT awsorderhdr.CUST3, awsorderhdr.cusno, partno, awsorderhdr.orderno, itdsc, awsorderhdr.Corno, awsorderhdr.orderdate, ordtype,DueDate, qty  from awsorderhdr inner join awsorderdtl on awsorderhdr.orderno=awsorderdtl.orderno".
//			  " where  awsorderhdr.orderdate>='$datefrom' and awsorderhdr.orderdate<='$dateto'" ;

	$sQuery = "SELECT awsorderhdr.Owner_Comp,awsorderhdr.dealer,awsorderhdr.CUST3,awsorderhdr.cusno, shipto, partno, awsorderhdr.orderno, itdsc,awsorderdtl.ordflg, awsorderhdr.Corno, awsorderhdr.orderdate, ordtype,DueDate, qty,vt_cst_ordr_prog.INV_NO,vt_cst_ordr_prog.SHPD_YMD,vt_cst_ordr_prog.SHPD_QTY,vt_cst_ordr_prog.CST_ORDR_LN_CMPLT_FLG,vt_cst_ordr_prog.CST_ORDR_QTY ,(qty * slsprice) as amount ,awscusmas.ship_to_cd2, awscusmas.ship_to_cd1
            from awsorderhdr 
                LEFT join awsorderdtl on awsorderhdr.orderno=awsorderdtl.orderno and awsorderhdr.Owner_Comp=awsorderdtl.Owner_Comp".
		" LEFT join vt_cst_ordr_prog on awsorderdtl.cusno=vt_cst_ordr_prog.CST_CD and awsorderdtl.Corno=vt_cst_ordr_prog.CST_PO_NO  and awsorderdtl.partno =vt_cst_ordr_prog.CST_ORDR_INPT_ITEM_NO and awsorderdtl.owner_comp=vt_cst_ordr_prog.owner_comp".	
		" LEFT JOIN awscusmas on awscusmas.cusno2 = awsorderhdr.CUST3 AND awsorderhdr.shipto = awscusmas.ship_to_cd2 AND awsorderhdr.Owner_Comp = awscusmas.Owner_Comp".
			  " where awsorderhdr.orderdate>='$datefrom' and awsorderhdr.orderdate<='$dateto' and awsorderhdr.Owner_Comp='$comp' " ;

	// $sQuery_select = "select *
	// from (";
	$sQuery_select1 = " SELECT  awsorderhdr.Owner_Comp,awsorderdtl.partno, awsorderhdr.cusno, awsorderhdr.orderno, awsorderhdr.Corno, awsorderhdr.orderdate, awsorderhdr.Dealer, itdsc, ordtype, qty,slsprice ,awsorderdtl.ordflg  as ordflg,awsorderhdr.shipto,  awscusmas.ship_to_cd1,awsorderhdr.shipto as 'ship_to_cd2',
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
                $fld="awsorderdtl.Corno";
                break;
			case "cusno1":
				$fld="awsorderdtl.Dealer";
				break;
			case "cusno2":
				$fld="awsorderdtl.cusno";
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
	
	 if($namafield!=''){
			$sQuery_from1 = $sQuery_from1 . " order by $namafield $sort, orderdate";		  
	  }else{
			$sQuery_from1 = $sQuery_from1 . " order by partno, orderdate";		   
	  }	

		$sQuery_from1=$sQuery_from1 ." LIMIT $start, $per_page";
		// $sQuery = $sQuery_select .$sQuery_select1. $sQuery_from1 . $union . $sQuery_select2 . $sQuery_from2;
		$sQuery = $sQuery_select .$sQuery_select1. $sQuery_from1;

	$i="0";
	   // echo $sQuery;
	$rResult = mysqli_query($msqlcon,$sQuery ) or die(mysqli_error());
	
	while ( $aRow = mysqli_fetch_array( $rResult ) )
	{
		if($i=="0"){
		
			echo "<table 	border=\"0\" cellspacing=\"0\" cellpadding=\"0\" width=\"100%\">";
			$i="1";
		}		
			$owner_comp=$aRow['Owner_Comp'];
			$orderno=$aRow['orderno'];
			$cusno1=$aRow['Dealer'];
			$cusno2=$aRow['cusno'];
			$shipto=$aRow['shipto'];
			$shipcd1=$aRow['ship_to_cd1'];
			$shipcd2=$aRow['ship_to_cd2'];
			$partno=$aRow['partno'];
			$partdes=$aRow['itdsc'];
			$corno=$aRow['Corno'];
			$duedt=$aRow['DueDate'];
			$orderdate=$aRow['orderdate'];
            $amount = number_format($aRow['slsprice'] * $aRow['qty'] ,2);
			if($corno=="")$corno="-";
			$qty=number_format($aRow['qty']);
			$ordrflg=$aRow['ordflg'];
			$orddate=substr($orderdate,-2)."/".substr($orderdate,4,2)."/".substr($orderdate,0,4);

	
			if(empty($shipdate))
			{
			// it's empty!
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
			
			if($qty=="0"){ //Zia Added for Order Progress check//
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
			//echo "<script type='text/javascript'>alert(\"$ordrflg\");</script>";	
			
			echo "<tr height=\"30\">";
			echo "<td align=\"center\" width=\"10%\">".$orddate."</td>";
			echo "<td align=\"center\" width=\"7%\">".$cusno1."</td>";
			echo "<td align=\"center\" width=\"8%\">".$shipcd1."</td>";
			echo "<td align=\"center\" width=\"7%\">".$cusno2."</td>";
			echo "<td align=\"center\" width=\"8%\">".$shipcd2."</td>";
			echo "<td align=\"center\" width=\"12%\">".$corno."</td>";
			echo "<td align=\"center\" width=\"12%\">".$partno."</td>";
			echo "<td  align=\"center\"width=\"12%\">".$qty."</td>";
			echo "<td style=\"padding-right:5px;\" align=\"right\" width=\"12%\">".$amount."</td>";
			if($ordrflg=="Incomplete"){
				echo "<td width=\"12%\" align=\"center\" class=\"lasttd\" style=\"color: blue\" >".$ordrflg."</td>";
			}
			elseif($ordrflg=="Cancelled"){
				echo "<td width=\"12%\" align=\"center\" class=\"lasttd\" style=\"color: #800000\" >".$ordrflg."</td>";
			}
			else{
				echo "<td width=\"12%\" align=\"center\" class=\"lasttd\" >".$ordrflg."</td>";
			}
			
			
			echo "</tr>";
				}
	if($i=="1") echo "</table>";
	

	
?>
