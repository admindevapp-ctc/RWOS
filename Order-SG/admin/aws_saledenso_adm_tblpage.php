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
		echo "<script> document.location.href='../".redir."'; </script>";
	 }
}else{	
	header("Location:../login.php");
}
	require('../db/conn.inc');
     
	if(isset($_POST)){
		$per_page=10;
		$num=5;
		$criteria=" where awsexc.Owner_Comp='$owner_comp'";
		if(!empty($_POST)){
			// print_r($_POST);
			$xcusno1=$_POST["cusno1"];
			$xcusgrp2=$_POST["cusgrp"];
			$xpartnumber=$_POST["part_no"];
			$xproduct=$_POST["product"];
			$xpartname=$_POST["part_name"];
			$xcondition=$_POST["condition"];
			if(trim($xcusno1)!=''){
				$criteria .= ' and cusno1="'.$xcusno1.'"';	
			}
			if(trim($xcusgrp2)!=''){
				$criteria .= ' and cusgrp="'.$xcusgrp2.'"';
			}
			if(trim($xpartnumber)!=''){
				$criteria .= ' and trim(awsexc.itnbr) like "%'.$xpartnumber.'%"';
			}
			if(trim($xproduct)!=''){
				$criteria .= ' and Product="'.$xproduct.'"';
			}
			if(trim($xpartname)!=''){
				$criteria .= ' and ITDSC like "%'.$xpartname.'%"';
			}
			if(trim($xcondition)!=''){
				$criteria .= ' and sell="'.$xcondition.'"';
			}
		}
		$criteria.="  group by  awsexc.Owner_Comp, cusno1, trim(awsexc.itnbr) ";
		$query="SELECT awsexc.Owner_Comp, cusno1, Product, trim(awsexc.itnbr) as itnbr, ITDSC,   
			case when (
				select sell from awsexc a where awsexc.Owner_Comp=a.Owner_Comp and a.itnbr = awsexc.itnbr and awsexc.cusno1 = a.cusno1 order by sell desc limit 1
			) = 1 then 'Sell' else 'Not Sell' end sell
			, group_concat(cusgrp ORDER BY cusgrp ASC) as cusgrp, price, curr
		FROM awsexc left join bm008pr on trim(awsexc.itnbr) = trim(bm008pr.ITNBR) and awsexc.Owner_Comp = bm008pr.Owner_Comp ". $criteria;
		//echo $query;
		// $result = mysqli_query($msqlcon,$query);
		//$count = mysqli_num_rows($result);
	
	
		$query="SELECT awsexc.Owner_Comp, cusno1, Product, trim(awsexc.itnbr) as itnbr, ITDSC,   
					case when sell = 1 then 'Sell' else 'Not Sell' end sell, group_concat(cusgrp ORDER BY cusgrp ASC) as cusgrp, price, curr
				FROM awsexc left join bm008pr on trim(awsexc.itnbr) = trim(bm008pr.ITNBR) and awsexc.Owner_Comp = bm008pr.Owner_Comp ". $criteria;
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
