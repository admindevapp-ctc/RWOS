<?php 

session_start();

require_once('../../core/ctc_init.php'); // add by CTC

$comp = ctc_get_session_comp(); // add by CTC

require_once('../../language/Lang_Lib.php');
$cusno = ctc_get_session_cusno();
$cusnm = ctc_get_session_cusnm();


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
	$sql = "SELECT count(*) as count".
	" from supawsorderhdr inner join supawsorderdtl on supawsorderhdr.orderno=supawsorderdtl.orderno and supawsorderhdr.Owner_Comp=supawsorderdtl.Owner_Comp  and supawsorderhdr.Corno = supawsorderdtl.Corno ".
	" and supawsorderhdr.cusno = supawsorderdtl.cusno and supawsorderhdr.supno=supawsorderdtl.supno  ".
	" left join supawsordernts on supawsorderhdr.orderno=supawsordernts.orderno and supawsorderhdr.Owner_Comp=supawsordernts.Owner_Comp  and supawsorderhdr.Corno = supawsordernts.Corno ".
	" and supawsorderhdr.cusno = supawsordernts.cusno and supawsorderhdr.supno=supawsordernts.supno  ".
	" where supawsorderhdr.orderdate>='$datefrom' and supawsorderhdr.orderdate<='$dateto' and supawsorderhdr.Owner_Comp='$comp'  and supawsorderhdr.cusno ='$cusno'" ;


  

if($search!=''){
	switch($search){
		case "partno":
			$fld="supawsorderdtl.partno";
			break;
		case "ITDSC":
			$fld="supawsorderdtl.itdsc";
			break;
		case "corno":
			$fld="supawsorderhdr.Corno";
			break;
		case "cuscode":
			$fld="supawsorderhdr.cusno";
			break;
		case "supcode":
			$fld="supawsorderhdr.supno";
			break;
		case "statusapprove":
			$fld="supawsorderdtl.ordflg";
			break;
		case "invoice":
			$fld="vt_cst_ordr_prog.INV_NO";
			break;
		case "status":
			$fld="supawsorderdtl.ordflg";
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
	$sql = $sql . " and $fld $op  $vdesc";	
}
	$sql = $sql . " order by partno, supawsorderhdr.orderdate";
	
	//echo $sql;
	$result = mysqli_query($msqlcon,$sql);
	//$count = mysqli_num_rows($result);
	$aRow = mysqli_fetch_array( $result );
	$count = $aRow['count'];
	//echo "<br> jumlah :".$count;
	$pages = ceil($count/$per_page);
	$page = $_GET['page'];
	if($page){ 
		$start = ($page - 1) * $per_page; 			
	}else{
		$start = 0;	
		$page=1;
	}
   /*
	if($count>$per_page){
		echo "<div id=\"search\"><a href=\"#\"> <img src=\"images/view.png\" width=\"16\" height=\"16\" border=\"0\"></a></div>";
	}
*/
	if($pages!=1 && $pages!=0){	
		$prev=$page-1;
		if($prev!=0){
			echo '<a href="#" onclick="paging('.$prev.')">Previous</a>';
		}else{
			echo '<a href="#">Previous</a>';
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
			echo '<a href="#" onclick="paging('.$next.')">Next</a>';
		}else{
			echo '<a href="#">Next</a>';
		}
	}
?>
