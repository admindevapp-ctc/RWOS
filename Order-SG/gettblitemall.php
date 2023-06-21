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

	
	
	
//	$sql = " SELECT  Product, Subprod, orderdate , Corno , sum( qty ) as ttlqty , curcd, sum( bprice * qty ) as ttlamount , sum( bprice * qty * SGPrice ) as ttlamountsg, orderno FROM orderdtl INNER JOIN bm008pr ON orderdtl.partno = bm008pr.itnbr".
//" where orderdtl.cust3='$cusno' and orderdtl.orderdate>='$datefrom' and orderdtl.orderdate<='$dateto' GROUP BY Product, subprod, orderdate, corno ORDER BY Product, subprod, orderdate, corno";

$sql = " SELECT  partno, Product, Subprod, orderdate , Corno , sum( qty ) as ttlqty , curcd, sum( bprice * qty ) as ttlamount , sum( bprice * qty * SGPrice ) as ttlamountsg, orderno FROM orderdtl INNER JOIN bm008pr ON orderdtl.partno = bm008pr.itnbr AND orderdtl.Owner_Comp = bm008pr.Owner_Comp ".
" where orderdtl.cusno in $comp and orderdtl.orderdate>='$datefrom' and orderdtl.orderdate<='$dateto' and orderdtl.Owner_Comp='$owner_comp' GROUP BY Product, subprod, orderdate, corno ORDER BY Product, subprod, orderdate, corno";

	
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
	
	
//$sQuery=  " SELECT  Product, Subprod, orderdate , Corno , sum( qty ) as ttlqty , curcd, sum( bprice * qty ) as ttlamount , sum( bprice * qty * SGPrice ) as ttlamountsg, orderno FROM orderdtl INNER JOIN bm008pr ON orderdtl.partno = bm008pr.itnbr".
//" where orderdtl.cust3='$cusno' and orderdtl.orderdate>='$datefrom' and orderdtl.orderdate<='$dateto' GROUP BY Product, subprod, orderdate, corno ORDER BY Product, subprod, orderdate, corno"	;

$sQuery=  " SELECT  partno, Product, Subprod, orderdate , Corno , sum( qty ) as ttlqty , curcd, sum( bprice * qty ) as ttlamount , sum( bprice * qty * SGPrice ) as ttlamountsg, orderno FROM orderdtl INNER JOIN bm008pr ON orderdtl.partno = bm008pr.itnbr AND orderdtl.Owner_Comp = bm008pr.Owner_Comp ".
" where orderdtl.cusno in $comp and orderdtl.orderdate>='$datefrom' and orderdtl.orderdate<='$dateto' and orderdtl.Owner_Comp='$owner_comp' GROUP BY Product, subprod, orderdate, corno ORDER BY Product, subprod, orderdate, corno"	;


$sQuery=$sQuery ." LIMIT $start, $per_page";
	
	//echo  $sQuery;
	$i="0";
	//echo $sQuery;
	$rResult = mysqli_query($msqlcon, $sQuery ) or die(mysqli_error());
	
	while ( $aRow = mysqli_fetch_array( $rResult ) )
	{
		if($i=="0"){
		
			echo "<table border=\"0\" cellspacing=\"0\" cellpadding=\"0\" width=\"100%\">";
			$i="1";
		}		
		
			$partno=$aRow['partno'];
			$orderno=$aRow['orderno'];
			$product=$aRow['Product'];
			$subprod=$aRow['Subprod'];
			$corno=$aRow['Corno'];
			$shpno=$aRow['cusno'];
			if($corno=="")$corno="-";
			$ttlqty=number_format($aRow['ttlqty']);
			$ttlamount=number_format($aRow['ttlamount'],2,".",",");
			//$ttlamountsg=number_format($aRow['ttlamountsg'],2,".",",");
		
			$orderdate=$aRow['orderdate'];
			$orddate=substr($orderdate,-2)."/".substr($orderdate,4,2)."/".substr($orderdate,0,4);
			echo "<tr height=\"30\">";
			echo "<td width=\"15%\">".$partno. "</td>";
			echo "<td width=\"15%\">".$product. "</td>";
			echo "<td width=\"15%\">".$subprod. "</td>";
			echo "<td width=\"15%\" align=\"center\">".$corno."</td>";
			echo "<td width=\"10%\" align=\"center\">".$orddate."</td>";
			echo "<td width=\"10%\" align=\"right\">".$ttlqty."</td>";
			echo "<td width=\"10%\" align=\"right\" class=\"lasttd\" >".$ttlamount."</td>";	
			//echo "<td width=\"10%\" align=\"right\" class=\"lasttd\">".$ttlamountsg."</td>";	
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
