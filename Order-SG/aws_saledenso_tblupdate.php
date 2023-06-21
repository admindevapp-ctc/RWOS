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
		$criteria=" where awsexc.Owner_Comp='$owner_comp'  and cusno1 = '$cusno' ";
		if(!empty($_POST)){
			$xcusno1=$_POST["cusgrp"];
			$xproduct=$_POST["product"];
			$xpartnumber=$_POST["part_no"];
			$xpartname=$_POST["part_name"];
			$xcondition=$_POST["condition"];
			if(trim($xcusno1)!=''){
				$criteria .= ' and cusgrp="'.$xcusno1.'"';	
			}
			if(trim($xpartnumber)!=''){
				$criteria .= ' and trim(awsexc.itnbr)="'.$xpartnumber.'"';
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
			 
		$query1="SELECT awsexc.Owner_Comp, cusno1, Product, trim(awsexc.itnbr) as itnbr, ITDSC,   
		case when (
				select sell from awsexc a where awsexc.Owner_Comp=a.Owner_Comp and awsexc.cusno1 = a.cusno1 and a.itnbr = awsexc.itnbr  order by sell desc limit 1
			) = 1 then 'Sell' else 'Not Sell' end sell
		, group_concat(cusgrp ORDER BY cusgrp ASC) as cusgrp, price, curr
		FROM awsexc left join bm008pr on trim(awsexc.itnbr) = trim(bm008pr.ITNBR) and awsexc.Owner_Comp = bm008pr.Owner_Comp ". $criteria . "order by cusno1".		
			   " LIMIT $start, $per_page";
		// echo $query1;
		$sql=mysqli_query($msqlcon,$query1);	
			$html ="";
			if( ! mysqli_num_rows($sql) ) {
				$html = "<tr height=\"30\"><td colspan=\"12\" align=\"center\" class=\"arial12BoldGrey tbl_data\">" . get_lng($_SESSION["lng"], "E0060") /*No Data Found.... ! */ . "</td></tr>";
			}
			
			while($hasil = mysqli_fetch_array ($sql)){
				$vowner=$hasil['Owner_Comp'];
				$vcusno1=$hasil['cusno1'];
				$vproduct=$hasil['Product'];
				$vitnbr=$hasil['itnbr'];
				$vitdsc=$hasil['ITDSC'];
				$vsell=$hasil['sell'];
				$vcusgrp=$hasil['cusgrp'];
				$vprice=$hasil['price'];
				$vcurr=$hasil['curr'];
				if(strlen($vcusgrp) > 12){
					$vcusgrp = substr($vcusgrp,0,12) ."...";
				}
				
				$html .= "<tr class=\"arial11black tbl_data\" height=\"30\">";
				$html .= "<td  align=\"center\" >".$vowner."</td>";
				$html .= "<td align=\"center\">".$vproduct."</td>";
				$html .= "<td align=\"center\"><a href='#' onclick='view_edit(this);' class='view' id='".$vcusno1."||".$vitnbr."||".$vitdsc."||".$vcusgrp."'>".$vitnbr."</a></td>";
				$html .= "<td style=\"padding-left:5px;\">".$vitdsc."</td>";
				$html .= "<td align=\"center\" >".$vsell."</td>";
				$html .= "<td align=\"center\"  class=\"lasttd\">".$vcusgrp ."</td>";
				$html .= "</tr>";
			
			}
			
		// require('pager.php');
		// if($count>$per_page){		
			// $html .= "<tr height=\"30\"><td colspan=\"6\" align=\"right\" class=\"lasttd\"><div id=\"pagination\" >";
			// //echo $query;
			// $fld="page";
			// paging($query,$per_page,$num,$page);
			// $html.= "</div></td></tr>";
		// }
		echo $html;

	}
	
		
		
	?>
