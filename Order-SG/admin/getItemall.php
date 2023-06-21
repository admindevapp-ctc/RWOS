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
header("Location:../../login.php");
}



	$per_page=10;
	
	/* Database connection information */
	require('../db/conn.inc');
	//$periode=trim($_GET['periode']);
	$page=trim($_GET['page']);
	$sort=trim($_GET['sort']);
	$namafield=trim($_GET['namafield']);
	$search=trim($_GET['search']);
	$choose=trim($_GET['choose']);
	$desc=trim($_GET['description']);
	//echo $cusno;
	//total Row Count
	$sql = "SELECT bm008pr.Owner_Comp,bm008pr.ITNBR, bm008pr.ITDSC, bm008pr.PRODUCT, bm008pr.SUBPROD, bm008pr.LotSize FROM `bm008pr` where Owner_Comp = '$comp'" ;
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
	
	
	$sQuery = "SELECT bm008pr.Owner_Comp,ITNBR, ITDSC, PRODUCT, SUBPROD, L1AWQY, Lotsize, ASSYCD, ITTYP, MTO FROM bm008pr LEFT JOIN hd100pr ON `ITNBR`=prtno and bm008pr.Owner_Comp=hd100pr.Owner_Comp where bm008pr.Owner_Comp = '$comp' ";
	 if($search!=''){
		//echo $search;
		switch($search){
			case "partno":
				$fld="ITNBR";
				break;
			case "ITDSC":
				$fld="itdsc";
				break;
			case "product":
				$fld="product";
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
			$owner_comp=$aRow['Owner_Comp'];
			$partno=$aRow['ITNBR'];
			$partdes=$aRow['ITDSC'];
			$product=$aRow['PRODUCT'];
			$sub=$aRow['SUBPROD'];
			$lot=number_format($aRow['Lotsize']);
			$curcd=$aRow['CURCD'];
			$mto=$aRow['MTO'];
			//$ittyp=$aRow['ITTYP'];
			$stkqty=$aRow['L1AWQY'];
			$assycd=$aRow['ASSYCD'];
			$price=number_format($aRow['Price']);
			echo "<tr height=\"30\">";
			echo "<td width=\"7%\">".$owner_comp. "</td>";
			echo "<td width=\"10%\">".$partno. "</td>";
			echo "<td width=\"18%\">".$partdes. "</td>";
			echo "<td width=\"15%\" align=\"center\">".$product."</td>";
			echo "<td width=\"15%\" align=\"center\">".$sub."</td>";
			echo "<td width=\"10%\" align=\"center\">".$lot."</td>";
			echo "<td width=\"8%\" align=\"center\">".$assycd."</td>";
			echo "<td width=\"8%\" align=\"center\">".$mto."</td>";
			//echo "<td width=\"5%\" align=\"center\">".$ittyp."</td>";
			echo "<td width=\"5%\" align=\"center\">".$stkqty."</td>";
			echo "<td width=\"5%\" align=\"center\" class=\"lasttd\">"."<a href='itemmaster.php?action=edit&partno=".$partno."' > <img src=\"../images/edit.png\" width=\"20\" height=\"20\" border=\"0\"></a>"."</td>";	
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
