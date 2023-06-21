<?php 

session_start();

require_once('./../core/ctc_init.php'); // add by CTC

$comp = ctc_get_session_comp(); // add by CTC

//ctc_checkuser_permission('../login.php');

require_once('../language/Lang_Lib.php');
$cusno = ctc_get_session_cusno();
$cusnm = ctc_get_session_cusnm();


	$per_page=10;
	$num=5;
	/* Database connection information */
    require('db/conn.inc');
	$datefrom=trim($_GET['datefrom']);
	$dateto=trim($_GET['dateto']);
	$search=trim($_GET['search']);
	$choose=trim($_GET['choose']);
	$desc=trim($_GET['description']);
	//total Row Count
	$sql = "SELECT count(*) as count ".
	" from suporderhdr inner join suporderdtl on suporderhdr.orderno=suporderdtl.orderno and suporderhdr.Owner_Comp=suporderdtl.Owner_Comp  and suporderhdr.Corno = suporderdtl.Corno ".
	" and suporderhdr.CUST3 = suporderdtl.CUST3 and suporderhdr.supno=suporderdtl.supno  ".
	" inner join supordernts on suporderhdr.orderno=supordernts.orderno and suporderhdr.Owner_Comp=supordernts.Owner_Comp  and suporderhdr.Corno = supordernts.Corno ".
	" and suporderhdr.CUST3 = supordernts.CUST3 and suporderhdr.supno=supordernts.supno  ".
    " where  suporderhdr.orderdate>='$datefrom' and suporderhdr.orderdate<='$dateto' and suporderhdr.Owner_Comp='$comp' and suporderhdr.cusno ='$cusno'";


  
	 if($search!=''){
		//echo $search;
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
		$sql = $sql . " and $fld $op '$vdesc'";	
	 }		  
	$sql = $sql . " order by partno, suporderhdr.orderdate";
	
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
