<? session_start() ;
?>
<?
if(isset($_SESSION['cusno']))
{       
	$_SESSION['cusnm'];
		$_SESSION['password'];
		$_SESSION['alias'];
		$_SESSION['tablename'];
		$_SESSION['user'];
		$_SESSION['dealer'];
		$_SESSION['group'];
		$_SESSION['type'];
		$_SESSION['custype'];
		

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
  
}else{	
header("Location: login.php");
}


	$per_page=10;
	
	/* Database connection information */
	require('db/conn.inc');
	$periode=trim($_GET['periode']);
	$page=trim($_GET['page']);
	$sort=trim($_GET['sort']);
	$namafield=trim($_GET['namafield']);
	$search=trim($_GET['search']);
	$choose=trim($_GET['choose']);
	$desc=trim($_GET['description']);
	
	//total Row Count
	$sql = "SELECT count(*) as scount from orderhdr inner join orderdtl on orderhdr.orderno=orderdtl.orderno".
			  " where (orderprd=".$periode. " or SUBSTR(DueDate,1,6)=".$periode. ") ";
	 
	//echo $sql;
	$result = mysqli_query($msqlcon,$sql);
	$yrow=mysqli_fetch_array( $result );
	$count=15;
	//$count $yrow['scount'];
	$pages = ceil($count/$per_page);
	$page = $_GET['page'];
	if($page){ 
		$start = ($page - 1) * $per_page; 			
	}else{
		$start = 0;	
		$page=1;
	}
	
	
	$sQuery = "SELECT partno, orderhdr.cusno, orderhdr.orderno, orderhdr.Corno, orderhdr.orderdate, ordtype, qty  from orderhdr inner join orderdtl on orderhdr.orderno=orderdtl.orderno".
			  " where (orderprd=".$periode. " or SUBSTR(DueDate,1,6)=".$periode. ") ";
	 if($search!=''){
		// echo $search;
		switch($search){
			case "partno":
				$fld="orderdtl.partno";
				break;
			case "cusno":
				$fld="orderhdr.cusno";
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
			$sQuery = $sQuery . " order by orderhdr.cusno, partno, orderhdr.orderdate";		   
	  }	

		$sQuery=$sQuery ." LIMIT $start, $per_page";
	
	$i="0";
	//echo $sQuery;
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
			//$partdes=$aRow['ITDSC'];
			$corno=$aRow['Corno'];
			if($corno=="")$corno="-";
			$qty=number_format($aRow['qty']);
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
			$qrycus="select Cusnm from cusmas where Cusno='".$cusno."'";
			$sqlcus=mysqli_query($msqlcon,$qrycus);		
			if($hasilx = mysqli_fetch_array ($sqlcus)){
				$cusnm=$hasilx['Cusnm'];
			}
			
			//prtdes from Bm008PR
			
			$qryPart="select ITDSC from bm008pr where ITNBR='".$partno."'";
			$sqlprt=mysqli_query($msqlcon,$qryPart);		
			if($hasilx = mysqli_fetch_array ($sqlprt)){
				$partdes=$hasilx['ITDSC'];
			}
			echo "<tr height=\"30\">";
			
			echo "<td width=\"15%\">".$cusno."<br>".$cusnm. "</td>";
			echo "<td width=\"20%\">".$partno."<br>".$partdes. "</td>";
			echo "<td width=\"15%\" align=\"center\">".$corno."</td>";
			echo "<td width=\"15%\" align=\"center\">".$orderno."</td>";
			echo "<td width=\"10%\" align=\"center\">".$orddate."</td>";
			echo "<td width=\"10%\" align=\"center\">".$ordsts."</td>";
			echo "<td width=\"15%\" align=\"right\" class=\"lasttd\">".$qty."</td>";	
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
