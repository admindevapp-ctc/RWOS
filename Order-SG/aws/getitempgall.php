<?php 

session_start();
require_once('../../core/ctc_init.php'); // add by CTC
require_once('../../language/Lang_Lib.php');

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
		   echo "<script> document.location.href='../../".redir."'; </script>";
	 }
}else{	
header("Location:../../login.php");
}

	$per_page=10;
	$num=5;
	/* Database connection information */
	require('../db/conn.inc');
	$periode=trim($_GET['periode']);
	$search=trim($_GET['search']);
	$choose=trim($_GET['choose']);
	$desc=trim($_GET['description']);
	//total Row Count
	$qrycusmas="SELECT `cusgrp` FROM `awscusmas` WHERE `Owner_Comp` = '$owner_comp' and `cusno2` = '$cusno' ";
	$sqlcusmas=mysqli_query($msqlcon,$qrycusmas);		
	$comp='(';
	$flag='';		
	while($hslcusmas = mysqli_fetch_array ($sqlcusmas)){
	  $cros=$hslcusmas[cusgrp];
	  	if($flag==''){
			$comp=$comp . "'" . $cros . "'";
		$flag='1';
		}else{
			$comp=$comp .",'".$cros. "'";
		}
	}
	$comp=$comp .')';
	
	//$sql = "SELECT count(*) as count  FROM sellprice inner join `bm008pr` on sellprice.itnbr=bm008pr.itnbr and sellprice.Owner_Comp=bm008pr.Owner_Comp where Sellprice.cusno in  $comp and sellprice.Owner_Comp='$owner_comp'";
    $sql = "SELECT distinct awsexc.price,  awsexc.curr, bm008pr.ITNBR, bm008pr.ITDSC, bm008pr.PRODUCT, bm008pr.SUBPROD,bm008pr.Lotsize 
    from awsexc 
    join bm008pr on bm008pr.ITNBR = awsexc.itnbr and bm008pr.Owner_comp = awsexc.Owner_comp
    Where awsexc.Owner_comp = '$owner_comp' 
	and awsexc.cusgrp in $comp AND cusno1 = '$dealer' AND awsexc.sell != '0'";
    if($search!=''){
		//echo $search;
		switch($search){
			case "partno":
				$fld="bm008pr.ITNBR";
				break;
			case "ITDSC":
				$fld="bm008pr.itdsc";
				break;
			case "product":
				$fld="bm008pr.product";
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
	$sql = $sql . " order by bm008pr.ITNBR";
	
	//echo $sql;
	$result = mysqli_query($msqlcon,$sql);
	$count = mysqli_num_rows($result);
	//$aRow = mysqli_fetch_array( $result );
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
			echo '<a href="#" onclick="paging('.$prev.')">'.get_lng($_SESSION["lng"], "L0103")/*Previous*/.'</a>';
		}else{
			echo '<a href="#">'.get_lng($_SESSION["lng"], "L0103")/*Previous*/.'</a>';
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
			echo '<a href="#" onclick="paging('.$next.')">'.get_lng($_SESSION["lng"], "L0104")/*Next*/.'</a>';
		}else{
			echo '<a href="#">'.get_lng($_SESSION["lng"], "L0104")/*Next*/.'</a>';
		}
                
                /* Start Add Code Detail paging by CTC Sippavit 30/09/2020 */
                $item_from=(($page-1)*$per_page)+1;
                $item_to=$page*$per_page;
                if($item_to>$count){
                    $item_to=$count;
                }
                echo "  | <span class=\"arial11black\">  View   : </span>";
                echo "  <span class=\"arial11blue\">"."  ".$item_from. " - ". $item_to."  "."</span>";
                echo "  <span class=\"arial11black\">". "  Of  " . "</span>";
                echo "  <span class=\"arial11blue\">". $count."</span>";
                /* End Add Code Detail paging by CTC Sippavit 30/09/2020 */
	}
?>
