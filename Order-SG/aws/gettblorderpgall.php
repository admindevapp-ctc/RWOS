<?php 

session_start();
require_once('../../core/ctc_init.php'); // add by CTC
require_once('../../language/Lang_Lib.php');

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
		   echo "<script> document.location.href='../../".redir."'; </script>";
	 }
}else{	
header("Location:../../login.php");
}


	$per_page=10;
	$num=5;
	/* Database connection information */
	require('../db/conn.inc');
	$datefrom=trim($_GET['datefrom']);
	$dateto=trim($_GET['dateto']);
	$search=trim($_GET['search']);
	$choose=trim($_GET['choose']);
	$desc=trim($_GET['description']);
	//total Row Count
	
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

	
	
//	$sql = "SELECT count(*) as count from awsorderhdr inner join awsorderdtl on awsorderhdr.orderno=awsorderdtl.orderno".
//	 " where awsorderhdr.Cust3='$cusno' and awsorderhdr.orderdate>='$datefrom' and awsorderhdr.orderdate<='$dateto'";

	$sql = "SELECT distinct awsorderdtl.slsprice,awsorderhdr.CUST3,awsorderhdr.cusno, partno, awsorderhdr.orderno, itdsc, awsorderhdr.Corno, awsorderhdr.orderdate
	, ordtype,DueDate, qty,vt_cst_ordr_prog.INV_NO,vt_cst_ordr_prog.SHPD_YMD,vt_cst_ordr_prog.SHPD_QTY
	,vt_cst_ordr_prog.CST_ORDR_LN_CMPLT_FLG,vt_cst_ordr_prog.CST_ORDR_QTY , awsorderdtl.ordflg
	from awsorderhdr inner join awsorderdtl on awsorderhdr.orderno=awsorderdtl.orderno and awsorderhdr.Owner_Comp=awsorderdtl.Owner_Comp ".
	" left join vt_cst_ordr_prog on awsorderdtl.cusno=vt_cst_ordr_prog.CST_CD and awsorderdtl.Corno=vt_cst_ordr_prog.CST_PO_NO  
		and awsorderdtl.partno =vt_cst_ordr_prog.CST_ORDR_INPT_ITEM_NO and awsorderdtl.owner_comp=vt_cst_ordr_prog.owner_comp".	
	" where awsorderhdr.cusno = '$cusno' and awsorderhdr.orderdate>='$datefrom' and awsorderhdr.orderdate<='$dateto' and awsorderhdr.Owner_Comp='$owner_comp' ";


	 if($search!=''){
		//echo $search;
		switch($search){
			case "partno":
				$fld="awsorderdtl.partno";
				break;
			case "ITDSC":
				$fld="awsorderdtl.itdsc";
				break;
			case "corno":
				$fld="awsorderhdr.Corno";
				break;
            case "statusapprove":
                $fld="awsorderdtl.ordflg";
                break;
            case "invoice":
                $fld="vt_cst_ordr_prog.INV_NO";
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
				if($desc == "R") {
					$desc="'$desc'";
				}
				$desc  = str_replace(",NULL", "'',NULL", $desc);
				$vdesc="($desc)";
		}
		$sql = $sql . " and $fld $op $vdesc";	
	 }		  
	$sql = $sql . " order by partno, awsorderhdr.orderdate";
	
	//echo $sql;
	$result = mysqli_query($msqlcon,$sql);
	$count = mysqli_num_rows($result);
	$aRow = mysqli_fetch_array( $result );
	//$count = $aRow['count'];
	//echo "<br> jumlah :".$count;
	$pages = ceil($count/$per_page);
	$page = $_GET['page'];
	if($page){ 
		$start = ($page - 1) * $per_page; 			
	}else{
		$start = 0;	
		$page=1;
	}
   
	if($count>$per_page){
		//echo "<div id=\"search\"><a href=\"#\"> <img src=\"images/view.png\" width=\"16\" height=\"16\" border=\"0\"></a></div>";
	}

	if($pages!=1 && $pages!=0){	
		$prev=$page-1;
		if($prev!=0){
			echo '<a href="#" onclick="paging('.$prev.')">'.get_lng($_SESSION["lng"], "L0223")/*Previous*/.'</a>';
		}else{
			echo '<a href="#">'.get_lng($_SESSION["lng"], "L0223")/*Previous*/.'</a>';
		}
	
	    if($page>=$num){
			if($pages<$page+2){
				$tgh=$pages-4;
			}else{
				$tgh=$page-2;
			}
		}else{
			$tgh=1;
		}
		
		$y=0;
		for($x=$tgh; $x<=$pages; $x++)
		{
			$y++;
			if($y<=5){
				if($page==$x){
				//	echo '<li id="'.$x.'" class="current">'.$x.'</li>';
					echo 	'<a href="#"  class="current" onclick="paging('.$x.')">'.$x.'</a>';
					}else{
					echo 	'<a href="#"  onclick="paging('.$x.')">'.$x.'</a>';
	
				}
			}else{
			  break;	
			}
		}
	
	
	
		if($pages>$page){
			$next=$page+1;
			echo '<a href="#" onclick="paging('.$next.')">'.get_lng($_SESSION["lng"], "L0224")/*Next*/.'</a>';
		}else{
			echo '<a href="#">'.get_lng($_SESSION["lng"], "L0224")/*Next*/.'</a>';
		}
	}
?>
