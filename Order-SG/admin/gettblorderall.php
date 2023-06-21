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
	$sql = "SELECT * from orderhdr inner join orderdtl on orderhdr.orderno=orderdtl.orderno and orderhdr.Owner_Comp=orderdtl.Owner_Comp where orderhdr.orderdate>='$datefrom' and orderhdr.orderdate<='$dateto' and orderhdr.Owner_Comp='$comp' ";

	
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
	
	
//$sQuery = "SELECT orderhdr.CUST3, orderhdr.cusno, partno, orderhdr.orderno, itdsc, orderhdr.Corno, orderhdr.orderdate, ordtype,DueDate, qty  from orderhdr inner join orderdtl on orderhdr.orderno=orderdtl.orderno".
//			  " where  orderhdr.orderdate>='$datefrom' and orderhdr.orderdate<='$dateto'" ;

			$sQuery_select = "SELECT orderdtl.slsprice,orderhdr.Owner_Comp,orderhdr.CUST3,orderhdr.cusno, partno, orderhdr.orderno, itdsc, orderhdr.Corno, orderhdr.orderdate, ordtype,DueDate, qty,vt_cst_ordr_prog.INV_NO,vt_cst_ordr_prog.SHPD_YMD,vt_cst_ordr_prog.SHPD_QTY,vt_cst_ordr_prog.CST_ORDR_LN_CMPLT_FLG,vt_cst_ordr_prog.CST_ORDR_QTY";
			$sQuery_from = " from orderhdr inner join orderdtl on orderhdr.orderno=orderdtl.orderno and orderhdr.Owner_Comp=orderdtl.Owner_Comp".
				" left join vt_cst_ordr_prog on orderdtl.cusno=vt_cst_ordr_prog.CST_CD and orderdtl.Corno=vt_cst_ordr_prog.CST_PO_NO  and orderdtl.partno =vt_cst_ordr_prog.CST_ORDR_INPT_ITEM_NO and orderdtl.owner_comp=vt_cst_ordr_prog.owner_comp".	
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
		$sQuery_from = $sQuery_from . " and $fld $op '$vdesc'";	
	 }
	
	 if($namafield!=''){
			$sQuery_from = $sQuery_from . " order by orderdtl.$namafield $sort, orderhdr.orderdate";		  
	  }else{
			$sQuery_from = $sQuery_from . " order by partno, orderhdr.orderdate";		   
	  }	
		// sum section
		$sQuery_sum = "SELECT SUM(orderdtl.qty) as sum_qty , SUM(orderdtl.qty * orderdtl.slsprice) as sum_total ".$sQuery_from;
		
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
	//echo $sQuery;
	$rResult = mysqli_query($msqlcon,$sQuery ) or die(mysqli_error());
	
	while ( $aRow = mysqli_fetch_array( $rResult ) )
	{
		if($i=="0"){
		
			echo "<table 	border=\"0\" cellspacing=\"0\" cellpadding=\"0\" width=\"100%\">";
			$i="1";
		}		
			$owner_comp=$aRow['Owner_Comp'];
			$orderno=$aRow['orderno'];
			$vcust3=$aRow['CUST3'];
			$partno=$aRow['partno'];
			$partdes=$aRow['itdsc'];
			$corno=$aRow['Corno'];
			$duedt=$aRow['DueDate'];
			$slsprice=$aRow['slsprice'];
			//$shpno=$aRow['shipto'];//12/20/2018 P.Pawan CTC get shipto
			if($corno=="")$corno="-";
			$qty=number_format($aRow['qty']);
			$orderdate=$aRow['orderdate'];
			$shipdate=$aRow['SHPD_YMD'];
			$slsqty=$aRow['SHPD_QTY'];
			$invno=$aRow['INV_NO'];
			$cstqty=$aRow['CST_ORDR_QTY'];
			$ordrflg=$aRow['CST_ORDR_LN_CMPLT_FLG'];
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
			//echo "<script type='text/javascript'>alert(\"$ordrflg\");</script>";	
			
			echo "<tr height=\"30\">";
			echo "<td width=\"8%\" align=\"center\">".$corno."</td>";
			echo "<td width=\"8%\" align=\"center\">".$orddate."</td>";
			echo "<td width=\"8%\" align=\"center\">".$partno."</td>";
			echo "<td width=\"13%\" align=\"left\">".$partdes."</td>";
			echo "<td width=\"8%\"align=\"center\">".$duedate."</td>";
			echo "<td width=\"3%\"align=\"center\">".$qty."</td>";
			echo "<td width=\"8%\"align=\"right\">".number_format($slsprice,2)."</td>";
			echo "<td width=\"8%\"align=\"right\">".number_format($slsprice*$qty,2)."</td>";
			//echo "<td width=\"5%\">".$shpno."</td>";
			echo "<td width=\"8%\"align=\"center\">".$shpdate."</td>";    //Zia Added for Order Progress check//
			echo "<td width=\"8%\"align=\"center\">".$slsqty."</td>";
			echo "<td width=\"8%\"align=\"center\">".$invno."</td>";
			
			if($ordrflg=="Incomplete"){
				echo "<td width=\"8%\" align=\"center\" class=\"lasttd\" style=\"color: blue\" >".$ordrflg."</td>";
			}
			elseif($ordrflg=="Cancelled"){
				echo "<td width=\"8%\" align=\"center\" class=\"lasttd\" style=\"color: #800000\" >".$ordrflg."</td>";
			}
			else{
				echo "<td width=\"8%\" align=\"center\" class=\"lasttd\" >".$ordrflg."</td>";
			}
			
			//echo "<td width=\"9%\" align=\"center\" class=\"lasttd\">".$vcust3."</td>";
			/**
			echo "<td >".$partno. "</td>";
			echo "<td >".$partdes. "</td>";
			echo "<td  align=\"center\">".$corno."</td>";
			echo "<td align=\"center\">".$shpno."</td>";
			echo "<td  align=\"center\">".$orddate."</td>";
			echo "<td align=\"center\">".$duedate."</td>";
			echo "<td  align=\"right\" >".$qty."</td>";
			echo "<td  align=\"center\" class=\"lasttd\">".$vcust3."</td>";
			**/

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
