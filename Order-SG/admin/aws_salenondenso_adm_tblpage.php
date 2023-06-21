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
		$comp = ctc_get_session_comp(); // add by CTC
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
		$criteria=" where supawsexc.Owner_Comp='$comp' ";
		if(!empty($_POST)){
			// print_r($_POST);
			$xcusno1=$_POST["cusno1"];
			$xcusgrp2=$_POST["cusgrp"];
			$xpartnumber=$_POST["part_no"];
			$xpartname=$_POST["part_name"];
			$xbrand=$_POST["brand"];
			$xsupcode=$_POST["supcode"];
			$xcondition=$_POST["condition"];
			if(trim($xcusno1)!=''){
				$criteria .= ' and supawsexc.cusno1="'.$xcusno1.'"';	
			}
			if(trim($xcusgrp2)!=''){
				$criteria .= ' and supawsexc.cusgrp="'.$xcusgrp2.'"';
			}
			if(trim($xpartnumber)!=''){
				$criteria .= ' and supawsexc.prtno like "%'.$xpartnumber.'%"';
			}
			if(trim($xpartname)!=''){
				$criteria .= ' and supcatalogue.Prtnm like "%'.$xpartname.'%"';
			}
			if(trim($xbrand)!=''){
				$criteria .= ' and  supcatalogue.Brand ="'.$xbrand.'"';
			}
			if(trim($xsupcode)!=''){
				$criteria .= ' and  supawsexc.supcode ="'.$xsupcode.'"';
			}
			if(trim($xcondition)!=''){
				$criteria .= ' and supawsexc.sell="'.$xcondition.'"';
			}
		}
		$criteria .= " group by supawsexc.Owner_Comp, cusno1,supawsexc.supcode, trim(supawsexc.prtno)";

		$page = $_POST['page'];
		if($page){ 
			$start = ($page - 1) * $per_page; 			
		}else{
			$start = 0;	
			$page=1;
		}
			 
		$query1="SELECT distinct supawsexc.Owner_Comp, supawsexc.cusno1, supawsexc.supcode, supmas.supnm,   supawsexc.prtno, supcatalogue.Prtnm, supcatalogue.Brand,
				case when sell = 1 then 'Sell' else 'Not Sell' end sell, group_concat(cusgrp ORDER BY cusgrp ASC) as cusgrp, supprice.price, supawsexc.curr
			FROM supawsexc 
			left join supcatalogue on trim(supcatalogue.Prtno) = trim(supawsexc.prtno)
			left join supmas on supmas.supno = supawsexc.supcode
			left join supprice on supprice.Cusno = supawsexc.cusno1  and supawsexc.prtno = supprice.partno ". $criteria . " order by supawsexc.cusno1"	;
		 // echo $query1;
		$sql=mysqli_query($msqlcon,$query1);	
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
