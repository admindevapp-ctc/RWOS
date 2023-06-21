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
		$dealer=$_SESSION['dealer'];
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
	//$periode=trim($_GET['periode']);
	$page=trim($_GET['page']);
	$sort=trim($_GET['sort']);
	$namafield=trim($_GET['namafield']);
	$search=trim($_GET['search']);
	$choose=trim($_GET['choose']);
	$desc=trim($_GET['description']);
	//echo $cusno;
	//total Row Count
	
	$qrycusmas="select cusno from cusmas where cust3= '$cusno' and Owner_Comp='$owner_comp' ";
	$sqlcusmas=mysqli_query($msqlcon,$qrycusmas);		
	$comp='(';
	$flag='';		
	while($hslcusmas = mysqli_fetch_array ($sqlcusmas)){
	  $cros=$hslcusmas[cusno];
	  if($flag==''){
	  	$comp=$comp .$cros;
		$flag='1';
	  }else{
		  	$comp=$comp .','.$cros;
	  }
	}
	$comp=$comp .')';
	
	//echo $comp;
	$sql = "SELECT bm008pr.ITNBR, bm008pr.ITDSC, bm008pr.PRODUCT, bm008pr.SUBPROD, bm008pr.LotSize, Sellprice.CURCD,Sellprice.Price  FROM sellprice inner join `bm008pr` on sellprice.itnbr=bm008pr.itnbr and sellprice.Owner_Comp=bm008pr.Owner_Comp where Sellprice.cusno in  $comp and Sellprice.Owner_Comp='$owner_comp' " ;
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
	
	
	$sQuery = "SELECT bm008pr.ITNBR, bm008pr.ITDSC, bm008pr.PRODUCT, bm008pr.SUBPROD,bm008pr.Lotsize,  Sellprice.CURCD,Sellprice.Price  FROM sellprice inner join `bm008pr` on sellprice.itnbr=bm008pr.itnbr and sellprice.Owner_Comp=bm008pr.Owner_Comp where Sellprice.cusno in  $comp and Sellprice.Owner_Comp='$owner_comp' ";
	 if($search!=''){
		//echo $search;
		switch($search){
			case "partno":
				$fld="Bm008pr.ITNBR";
				break;
			case "ITDSC":
				$fld="Bm008pr.itdsc";
				break;
			case "product":
				$fld="Bm008pr.product";
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
			$sQuery = $sQuery . " order by bm008pr.$namafield $sort";		  
	  }else{
			$sQuery = $sQuery . " order by Bm008pr.ITNBR";		   
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
			$partno=$aRow['ITNBR'];
			$partdes=$aRow['ITDSC'];
			$product=$aRow['PRODUCT'];
			$sub=$aRow['SUBPROD'];
			$lot=number_format($aRow['Lotsize']);
			$curcd=$aRow['CURCD'];
			$price=number_format($aRow['Price'], 2);
			echo "<tr height=\"30\">";
			echo "<td width=\"15%\">".$partno. "</td>";
			echo "<td width=\"20%\">".$partdes. "</td>";
			echo "<td width=\"15%\" align=\"center\">".$product."</td>";
			echo "<td width=\"15%\" align=\"center\">".$sub."</td>";
			echo "<td width=\"10%\" align=\"center\">".$lot."</td>";
			echo "<td width=\"10%\" align=\"center\">".$curcd."</td>";
			echo "<td width=\"15%\" align=\"right\" class=\"lasttd\">".$price."</td>";	
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
