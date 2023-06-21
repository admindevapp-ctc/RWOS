<?php 

session_start();
require_once('./../core/ctc_init.php'); // add by CTC
require_once('../language/Lang_Lib.php');

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
	require('../db/conn.inc');
     
	if(isset($_POST)){
		$per_page=10;
		$num=5;
		$criteria=" where supawsexc.Owner_Comp='$owner_comp'  and cusno1 = '$cusno' ";
		if(!empty($_POST)){
			$xcusno = $_POST["s_cusno"];
			$xsup = $_POST["s_supplier"];
			$xpartnumber = $_POST["s_partnumber"];
			$xbrand = $_POST["s_brand"];
			$xpartname = $_POST["s_partname"];
			$xcondition = $_POST["s_condition"];
			if (trim($xcusno) != '') {
				$criteria .= ' and cusgrp="' . $xcusno . '"';
			}
			if (trim($xsup) != '') {
				$criteria .= ' and supcode="' . $xsup . '"';
			}
			if (trim($xpartnumber) != '') {
				$criteria .= ' and trim(supawsexc.prtno)="' . $xpartnumber . '"';
			}
			if (trim($xbrand) != '') {
				$criteria .= ' and supcatalogue.brand="' . $xbrand . '"';
			}
			if (trim($xpartname) != '') {
				$criteria .= ' and supcatalogue.Prtnm like "%' . $xpartname . '%"';
			}
			if (trim($xcondition) != '') {
				$criteria .= ' and sell="' . $xcondition . '"';
			}
		}
		$criteria .= "  group by  supawsexc.Owner_Comp,supawsexc.supcode, cusno1, trim(supawsexc.prtno) ";
		
	
	
		$query = "SELECT supawsexc.Owner_Comp,supawsexc.supcode, supmas.supnm, cusno1, trim(supawsexc.prtno) as prtno, supcatalogue.Prtnm, supcatalogue.brand,
			 case when (
				select sell from supawsexc a where supawsexc.Owner_Comp=a.Owner_Comp and supawsexc.cusno1 = a.cusno1  and supawsexc.prtno = a.prtno order by sell desc limit 1
			) = 1 then 'Sell' else 'Not Sell' end sell, group_concat(cusgrp ORDER BY cusgrp ASC) as cusgrp, price, curr
			FROM supawsexc left join supcatalogue on trim(supawsexc.prtno) = trim(supcatalogue.Prtno) and supawsexc.Owner_Comp = supcatalogue.Owner_Comp 
			left join supmas on supawsexc.supcode = supmas.supno and supawsexc.Owner_Comp = supmas.Owner_Comp " . $criteria;
		// echo $query;
		$sql=mysqli_query($msqlcon,$query);
		$count = mysqli_num_rows($sql);
		$pages = ceil($count/$per_page);
		$page = $_POST['page'];
		if($page){ 
			$start = ($page - 1) * $per_page; 			
		}else{
			$start = 0;	
			$page=1;
		}
	
		if($count>$per_page){
			// echo "<div id=\"search\"><a href=\"#\"> <img src=\"images/view.png\" width=\"16\" height=\"16\" border=\"0\"></a></div>";
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
	}
?>
