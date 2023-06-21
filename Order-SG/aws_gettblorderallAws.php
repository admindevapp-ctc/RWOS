<?php 

session_start();
require_once('./../core/ctc_init.php'); // add by CTC

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
		echo "<script> document.location.href='../".redir."'; </script>";
	 }
}else{	
	header("Location:../login.php");
}

	$per_page=10;
	
	/* Database connection information */
	require('db/conn.inc');
	
	$datefrom=trim($_GET['datefrom']);
	$dateto=trim($_GET['dateto']);
	$page=trim($_GET['page']);
	$sort=trim($_GET['sort']);
	$namafield=trim($_GET['namafield']);
	$search=trim($_GET['search']);
	$choose=trim($_GET['choose']);
	$desc=trim($_GET['description']);
	
	//total Row Count
	$sql = "SELECT count(*) as scount
	FROM awsorderhdr
	inner join awsorderdtl on awsorderhdr.orderno=awsorderdtl.orderno and awsorderhdr.Owner_Comp=awsorderdtl.Owner_Comp and awsorderhdr.Corno = awsorderdtl.Corno
	inner join cusmas on awsorderhdr.cusno=cusmas.Cusno AND awsorderhdr.Owner_Comp=cusmas.Owner_Comp  ".
	" where awsorderhdr.Dealer='$cusno' and awsorderhdr.cusno<>awsorderhdr.Dealer  
	and awsorderhdr.orderdate>='$datefrom' and awsorderhdr.orderdate<='$dateto' 
	and awsorderhdr.Owner_Comp='$owner_comp' ";
	 
	//echo $sql;
	$result = mysqli_query($msqlcon,$sql);
	$yrow=mysqli_fetch_array( $result );

	$count=15;
	//$count = $yrow['scount'];
	$pages = ceil($count/$per_page);
	$page = $_GET['page'];
	if($page){ 
		$start = ($page - 1) * $per_page; 			
	}else{
		$start = 0;	
		$page=1;
	}
	
	/*
	$sQuery = "SELECT partno, awsorderhdr.cusno, awsorderhdr.orderno, awsorderhdr.Corno, awsorderhdr.orderdate, awsorderhdr.Dealer, itdsc,  ordtype, qty,slsprice  ,'' as shipfrom
	FROM awsorderhdr
	inner join awsorderdtl on awsorderhdr.orderno=awsorderdtl.orderno and awsorderhdr.Owner_Comp=awsorderdtl.Owner_Comp and awsorderhdr.Corno = awsorderdtl.Corno
	inner join cusmas on awsorderhdr.cusno=cusmas.Cusno AND awsorderhdr.Owner_Comp=cusmas.Owner_Comp  ".
	" where awsorderhdr.Dealer='$cusno' and awsorderhdr.cusno<>awsorderhdr.Dealer  
	and awsorderhdr.orderdate>='$datefrom' and awsorderhdr.orderdate<='$dateto' 
	and awsorderhdr.Owner_Comp='$owner_comp' ";
	*/
	$sQuery_select = "select *
	from ";
	$sQuery_select1 = " (SELECT  awsorderhdr.Owner_Comp,awsorderdtl.partno, awsorderhdr.cusno, awsorderhdr.orderno, awsorderhdr.Corno, awsorderhdr.orderdate, awsorderhdr.Dealer, itdsc, ordtype, qty,slsprice ,awsorderdtl.ordflg  as ordflg,awsorderhdr.shipto , 
		CONCAT(
            shiptoma.adrs1,
            shiptoma.adrs2,
            shiptoma.adrs3
        ) AS address,
        rejectorder.message ";
	$sQuery_from1 = "FROM awsorderhdr 
		LEFT join awsorderdtl on awsorderhdr.orderno=awsorderdtl.orderno and awsorderhdr.Owner_Comp=awsorderdtl.Owner_Comp and awsorderhdr.Corno = awsorderdtl.Corno 
		LEFT join cusmas on awsorderhdr.cusno=cusmas.Cusno AND awsorderhdr.Owner_Comp=cusmas.Owner_Comp 
		LEFT JOIN awscusmas ON awscusmas.Owner_Comp = awsorderhdr.Owner_Comp AND awscusmas.cusno1 = awsorderhdr.Dealer AND awscusmas.ship_to_cd2 = awsorderhdr.shipto
		LEFT JOIN shiptoma ON shiptoma.Cusno = awscusmas.cusno1 AND shiptoma.ship_to_cd = awscusmas.ship_to_cd1
		LEFT JOIN rejectorder ON rejectorder.Owner_Comp = awsorderhdr.Owner_Comp and rejectorder.orderno = awsorderhdr.orderno and rejectorder.partno = awsorderdtl.partno
		where awsorderhdr.Dealer='$cusno' and awsorderhdr.cusno<>awsorderhdr.Dealer and awsorderhdr.orderdate>='$datefrom' and awsorderhdr.orderdate<='$dateto'  and awsorderhdr.Owner_Comp='$owner_comp'";
	
	
	$union = "UNION";
		
	$sQuery_select2 = "	SELECT supawsorderhdr.Owner_Comp, supawsorderdtl.partno, supawsorderhdr.cusno, supawsorderhdr.orderno, supawsorderhdr.Corno, supawsorderhdr.orderdate, supawsorderhdr.Dealer, itdsc, ordtype, qty,slsprice ,supawsorderdtl.ordflg COLLATE utf8_general_ci as ordflg,supawsorderhdr.shipto, 
		CONCAT(
            shiptoma.adrs1,
            shiptoma.adrs2,
            shiptoma.adrs3
        ) AS address,
        rejectorder.message";
	$sQuery_from2 = " FROM supawsorderhdr 
		LEFT join supawsorderdtl on supawsorderhdr.orderno=supawsorderdtl.orderno and supawsorderhdr.Owner_Comp=supawsorderdtl.Owner_Comp and supawsorderhdr.Corno = supawsorderdtl.Corno 
		LEFT join cusmas on supawsorderhdr.cusno=cusmas.Cusno AND supawsorderhdr.Owner_Comp=cusmas.Owner_Comp 
		LEFT JOIN awscusmas ON awscusmas.Owner_Comp = supawsorderhdr.Owner_Comp AND awscusmas.cusno1 = supawsorderhdr.Dealer AND awscusmas.ship_to_cd2 = supawsorderhdr.shipto
		LEFT JOIN shiptoma ON shiptoma.Cusno = awscusmas.cusno1 AND shiptoma.ship_to_cd = awscusmas.ship_to_cd1
		LEFT JOIN rejectorder ON rejectorder.Owner_Comp = supawsorderhdr.Owner_Comp and rejectorder.orderno = supawsorderhdr.orderno and rejectorder.partno = supawsorderdtl.partno
		where supawsorderhdr.Dealer='$cusno'and supawsorderhdr.cusno<>supawsorderhdr.Dealer and supawsorderhdr.orderdate>='$datefrom' and supawsorderhdr.orderdate<='$dateto'  and supawsorderhdr.Owner_Comp='$owner_comp'
		) a where Owner_Comp='$owner_comp'
	";
	 if($search!=''){
		// echo $search;
		switch($search){
			case "partno":
				$fld="partno";
				break;
			case "itdsc":
				$fld="itdsc";
				break;	
			case "cusno":
				$fld="cusno";
				break;
			case "corno":
				$fld="Corno";
				break;
			case "status":
				$fld="ordflg";
				break;
			case "shipfrom":
				$fld="ordflg";
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
					$op="!=";
					$vdesc="'R'";
				}
				else if($desc=="P"){
					$op="=";
					$vdesc="''";
				}
				else{
					$op="=";
					$vdesc="''";
				}
				break;
		}
		$sQuery_from2 = $sQuery_from2 . " and $fld $op $vdesc";	
	 }
	
		$sQuery_sum = "SELECT SUM(qty) as sum_qty , SUM(qty * slsprice) as sum_total from ".$sQuery_select1. $sQuery_from1 . $union . $sQuery_select2 . $sQuery_from2." ";
 
		$rSumResult = mysqli_query($msqlcon,$sQuery_sum) or die(mysqli_error());
		$sumqty = 0;
		$sumamnt = 0;
		while ( $sumRow = mysqli_fetch_array( $rSumResult ) )
		{
			$sumqty = number_format($sumRow['sum_qty']);
			$sumamnt = number_format($sumRow['sum_total'],2);

		}
 
	 if($namafield!=''){
			$sQuery_from2 = $sQuery_from2 . " order by $namafield $sort, orderdate";		  
	  }else{
			$sQuery_from2 = $sQuery_from2 . " order by cusno, partno, orderdate";		   
	  }	

		
	


	 $sQuery_from2=$sQuery_from2 ." LIMIT $start, $per_page";
	
	$sQuery = $sQuery_select .$sQuery_select1. $sQuery_from1 . $union . $sQuery_select2 . $sQuery_from2;
	$i="0";
	 // echo $sQuery;
	
	$rResult = mysqli_query($msqlcon, $sQuery ) or die(mysqli_error());
	
	while ( $aRow = mysqli_fetch_array( $rResult ) )
	{
		if($i=="0"){
		
			echo "<table 	border=\"0\" cellspacing=\"0\" cellpadding=\"0\" width=\"100%\">";
			$i="1";
		}		
			$cusno=$aRow['cusno'];
			$orderno=$aRow['orderno'];
			$partno=$aRow['partno'];
			$partdes=$aRow['itdsc'];
			$corno=$aRow['Corno'];
			$dealer=$aRow['Dealer'];
			$shipto=$aRow['shipto'];
			$addr=$aRow['ESCA1'];
			$price=$aRow['slsprice'];
			$ordflg=$aRow['ordflg'];
			$address=$aRow['address'];
			$message=$aRow['message'];
			
			
			if($corno=="")$corno="-";
			$qty=number_format($aRow['qty']);
			$amount=number_format($price * $qty);
			$orderdate=$aRow['orderdate'];
			$orddate=substr($orderdate,-2)."/".substr($orderdate,4,2)."/".substr($orderdate,0,4);
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
				case "R":
					$ordflgtext="Rejected : ".$message;
					break;
				case "1":
					$ordflgtext="Approved";
					break;
				case "2":
					$ordflgtext="Approved";
					break;
				default:
					$ordflgtext="Pending";
					break;
			}


			switch($ordflg){
				case "1":
					$shipfrom="Supplier";
					break;
				case "2":
					$shipfrom="Own Warehouse";
					break;
				default:
					$shipfrom="";
					break;
			}

			$qrycus="select Cusnm from cusmas where Cusno='".$cusno."'";
			$sqlcus=mysqli_query($msqlcon,$qrycus);		
			if($hasilx = mysqli_fetch_array ($sqlcus)){
				$cusnm=$hasilx['Cusnm'];
			}
			echo "<tr height=\"30\">";
				echo "<td width=\"8%\" align=\"center\">".$orddate."</td>";
				echo "<td width=\"8%\" align=\"center\">".$dealer. "</td>";
				echo "<td width=\"8%\" align=\"center\">".$cusno."</td>";
				echo "<td width=\"5%\" align=\"center\">".$shipto."</td>";
				echo "<td style=\"padding:0 5px;\" width=\"15%\">".$address."</td>";
				echo "<td width=\"10%\" align=\"center\">".$corno."</td>";
				echo "<td width=\"8%\" align=\"center\">".$partno."</td>";
				echo "<td width=\"5%\" align=\"center\">".number_format($qty,0)."</td>";
				echo "<td style=\"padding:0 5px;\" width=\"5%\" align=\"right\">".number_format($amount,2)."</td>";
				echo "<td width=\"10%\" align=\"center\">".$ordflgtext."</td>";
				echo "<td width=\"10%\" align=\"center\" class=\"lasttd\">".$shipfrom."</td>";	
			echo "</tr>";
	
	}
	if($i=="1") echo "</table>";
	echo '<input type="hidden" id="hid_sum_qty" value="'.$sumqty.'">';
	echo '<input type="hidden" id="hid_sum_amnt" value="'.$sumamnt.'">';
	/**for($x=1; $x<=$pages; $x++)
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
	if($i=="2") echo "</ul>"; **/

?>
