<? session_start() ;
?>
<?
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
	
	// Customer master..
	// Because SDML have 2 User so, we should use cusno instead of cust3
	$qrycusmas="select cusno from cusmas where cust3= '$cusno' ";
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

	
	
	
	//total Row Count
	//$sql = "SELECT * from orderhdr inner join orderdtl on orderhdr.orderno=orderdtl.orderno where orderhdr.cust3='$cusno' and orderhdr.orderdate>='$datefrom' and orderhdr.orderdate<='$dateto'";
	$sql = "SELECT * from orderhdr inner join orderdtl on orderhdr.orderno=orderdtl.orderno where orderhdr.cusno in $comp and orderhdr.orderdate>='$datefrom' and orderhdr.orderdate<='$dateto'";
	
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
$sQuery = "SELECT orderhdr.CUST3,orderhdr.cusno, partno, orderhdr.orderno, itdsc, orderhdr.Corno, orderhdr.orderdate, ordtype,DueDate, qty,shipto  from orderhdr inner join orderdtl on orderhdr.orderno=orderdtl.orderno".
			  " where orderhdr.cusno in $comp and orderhdr.orderdate>='$datefrom' and orderhdr.orderdate<='$dateto'" ;//12/20/2018 P.Pawan CTC add get shipto


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
	
	 if($namafield!=''){
			$sQuery = $sQuery . " order by orderdtl.$namafield $sort, orderhdr.orderdate";		  
	  }else{
			$sQuery = $sQuery . " order by partno, orderhdr.orderdate";		   
	  }	

		$sQuery=$sQuery ." LIMIT $start, $per_page";
	
	//echo  $sQuery;
	$i="0";
	//echo $sQuery;
	$rResult = mysqli_query($msqlcon, $sQuery ) or die(mysqli_error());
	
	while ( $aRow = mysqli_fetch_array( $rResult ) )
	{
		if($i=="0"){
		
			echo "<table 	border=\"0\" cellspacing=\"0\" cellpadding=\"0\" width=\"100%\">";
			$i="1";
		}		
			$orderno=$aRow['orderno'];
			$vcust3=$aRow['CUST3'];
			$partno=$aRow['partno'];
			$partdes=$aRow['itdsc'];
			$corno=$aRow['Corno'];
			$duedt=$aRow['DueDate'];
			$shpno=$aRow['shipto'];//12/20/2018 P.Pawan CTC get shipto
			if($corno=="")$corno="-";
			$qty=number_format($aRow['qty']);
			$orderdate=$aRow['orderdate'];
			$orddate=substr($orderdate,-2)."/".substr($orderdate,4,2)."/".substr($orderdate,0,4);
			$duedate=substr($duedt,-2)."/".substr($duedt,4,2)."/".substr($duedt,0,4);
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
			echo "<tr height=\"30\">";
			echo "<td width=\"13%\">".$partno. "</td>";
			echo "<td width=\"20%\">".$partdes. "</td>";
			echo "<td width=\"11%\" align=\"center\">".$corno."</td>";
			echo "<td width=\"12%\" align=\"center\">".$shpno."</td>";
			echo "<td width=\"9%\" align=\"center\">".$orddate."</td>";
			echo "<td width=\"9%\" align=\"center\">".$duedate."</td>";
			echo "<td width=\"11%\" align=\"right\" >".$qty."</td>";
			echo "<td width=\"10%\" align=\"center\" class=\"lasttd\">".$vcust3."</td>";
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
