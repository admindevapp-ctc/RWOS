<?php 

session_start();
require_once('./../../core/ctc_init.php'); // add by CTC
//require_once('../../language/Lang_Lib.php');

if(isset($_SESSION['cusno']))
{       
	if($_SESSION['redir']=='Order-SG'){
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
		$imptable=$_SESSION['imptable'];
		$owner_comp = ctc_get_session_comp(); // add by CTC
	 }else{
		   echo "<script> document.location.href='../".redir."'; </script>";
	 }
}else{	
header("Location:../login.php");
}
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
	$sql = "SELECT * from suporderhdr inner join suporderdtl on suporderhdr.orderno=suporderdtl.orderno and suporderhdr.Owner_Comp=suporderdtl.Owner_Comp where suporderhdr.orderdate>='$datefrom' and suporderhdr.orderdate<='$dateto' and suporderhdr.Owner_Comp='$owner_comp' ";
	//echo $sql;
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
	$sQuery_select = "select suporderdtl.qty, suporderdtl.slsprice, suporderhdr.supno, suporderhdr.CUST3,suporderhdr.cusno, partno, suporderhdr.orderno, itdsc, suporderhdr.Corno, suporderhdr.orderdate, suporderhdr.ordtype, DueDate , supordernts.remark ";
	$sQuery_from = " from suporderhdr inner join suporderdtl on suporderhdr.orderno=suporderdtl.orderno and suporderhdr.Owner_Comp=suporderdtl.Owner_Comp  and suporderhdr.Corno = suporderdtl.Corno ".
	" and suporderhdr.CUST3 = suporderdtl.CUST3 and suporderhdr.supno=suporderdtl.supno  ".
	" inner join supordernts on suporderhdr.orderno=supordernts.orderno and suporderhdr.Owner_Comp=supordernts.Owner_Comp  and suporderhdr.Corno = supordernts.Corno ".
	" and suporderhdr.CUST3 = supordernts.CUST3 and suporderhdr.supno=supordernts.supno  ".
	" where suporderhdr.orderdate>='$datefrom' and suporderhdr.orderdate<='$dateto' and suporderhdr.Owner_Comp='$owner_comp' " ;
	// edit by CTC Pasakorn 03/11/2022

			 

if($search!=''){
		// echo $search;
		switch($search){
			case "partno":
				$fld="suporderdtl.partno";
				break;
			case "ITDSC":
				$fld="suporderdtl.itdsc";
				break;
			case "corno":
				$fld="suporderhdr.Corno";
				break;
			case "cuscode":
				$fld="suporderhdr.cusno";
				break;
			case "supcode":
				$fld="suporderhdr.supno";
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
			$sQuery_from = $sQuery_from . " order by suporderdtl.$namafield $sort, suporderhdr.orderdate";		  
	  }else{
			$sQuery_from = $sQuery_from . " order by partno, suporderhdr.orderdate";		   
	  }	
	// sum section
		$sQuery_sum = "SELECT SUM(suporderdtl.qty) as sum_qty , SUM(suporderdtl.qty * suporderdtl.slsprice) as sum_total ".$sQuery_from;
		
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
	//echo  $sQuery;
	$i="0";
	//echo $sQuery;
	$rResult = mysqli_query($msqlcon,$sQuery) or die(mysqli_error());
	while ( $aRow = mysqli_fetch_array( $rResult ) )
	{
		
		if($i=="0"){
		
			echo "<table style='table-layout: fixed;' border=\"0\" cellspacing=\"0\" cellpadding=\"0\" width=\"100%\">";
			$i="1";
		}		
			$supno=$aRow['supno'];
			$cusno=$aRow['cusno'];
			$vcust3=$aRow['CUST3'];
			$partno=$aRow['partno'];
			$partdes=$aRow['itdsc'];
			$corno=$aRow['Corno'];
			$duedt=$aRow['DueDate'];
			$slsprice=$aRow['slsprice'];
			if($corno=="")$corno="-";
			$qty=number_format($aRow['qty']);
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
			//echo "<script type='text/javascript'>alert(\"$ordrflg\");</script>";	
			
			echo "<tr height=\"30\">";
				echo "<td width=\"10%\" align=\"center\">".$corno."</td>";
				echo "<td width=\"10%\" align=\"center\">".$orddate."</td>";
				echo "<td width=\"10%\" align=\"center\">".$partno."</td>";
				echo "<td width=\"15%\" align=\"left\">".$partdes."</td>";
				echo "<td width=\"10%\" align=\"center\">".$duedate."</td>";
				echo "<td width=\"5%\" align=\"center\">".$qty."</td>";
				echo "<td width=\"10%\" align=\"right\">".number_format($slsprice)."</td>";
				echo "<td width=\"10%\" align=\"right\">".number_format($qty * $slsprice)."</td>";  
				echo "<td width=\"10%\" align=\"center\">".$supno."</td>";  
				echo "<td width=\"10%\" class=\"lasttd\" style=\" word-wrap: break-word; word-break: break-all;\">".$remark."</td>";    
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