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
		if($type!='a'){
			header("Location: ../main.php");
		}
		}else{
			echo "<script> document.location.href='../../".$redir."'; </script>";
		}
	}else{	
		header("Location:../../login.php");
	}

?>
<?php
	require('../db/conn.inc');
	if(isset($_POST)){
		$per_page=10;
		$num=5;
		$html = '';
		$criteria=" where awsexc.Owner_Comp='$comp' ";
		if(!empty($_POST)){
			 print_r($_POST);
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
				$criteria .= ' and Product = "'.$xproduct.'"';
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
			 
		$query1="SELECT awsexc.Owner_Comp, cusno1, Product, trim(awsexc.itnbr) as itnbr, ITDSC,   
		case when (
				select sell from awsexc a where awsexc.Owner_Comp=a.Owner_Comp and awsexc.cusno1 = a.cusno1 and a.itnbr = awsexc.itnbr  order by sell desc limit 1
			) = 1 then 'Sell' else 'Not Sell' end sell
		, group_concat(cusgrp ORDER BY cusgrp ASC) as cusgrp, price, curr
		FROM awsexc left join bm008pr on trim(awsexc.itnbr) = trim(bm008pr.ITNBR) and awsexc.Owner_Comp = bm008pr.Owner_Comp ". $criteria . "order by cusno1".		
			   " LIMIT $start, $per_page";
		// echo $query1;
		$query1="SELECT awsexc.Owner_Comp, cusno1, Product, trim(awsexc.itnbr) as itnbr, ITDSC,   
    case when sell = 1 then 'Sell' else 'Not Sell' end sell, group_concat(cusgrp ORDER BY cusgrp ASC) as cusgrp, price, curr
    FROM awsexc left join bm008pr on trim(awsexc.itnbr) = trim(bm008pr.ITNBR) and awsexc.Owner_Comp = bm008pr.Owner_Comp ". $criteria . "order by cusno1".		
	       " LIMIT $start, $per_page";
	//echo $query1;
	$sql=mysqli_query($msqlcon,$query1);	
			if( ! mysqli_num_rows($sql) ) {
				echo "<tr height=\"30\"><td colspan=\"12\" align=\"center\" class=\"arial12BoldGrey\">" . get_lng($_SESSION["lng"], "E0060") /*No Data Found.... ! */ . "</td></tr>";
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
					$title_vcusgrp = $vcusgrp;
                    $vcusgrp = substr($vcusgrp,0,12) ."...";
                }else{
					$title_vcusgrp = '';
				}
				if(strlen($vitdsc) > 25){
					$title_vitdsc = $vitdsc;
                    $vitdsc = substr($vitdsc,0,25) ."...";
                }else{
					$title_vitdsc = '';
				}
				
				$html .= "<tr class=\"arial11black\" align=\"center\" height=\"30\">";
                $html .= "<td>".$vowner."</td>";
                $html .= "<td>".$vcusno1."</td>";
				$html .= "<td>".$vproduct."</td>";
				$html .= "<td><a href='#' onclick='view_edit(this);' class='view' id='".$vcusno1."||".$vitnbr."||".$vitdsc."||".$vcusgrp."'>".$vitnbr."</a></td>";
				$html .= "<td align=\"left\" style=\"padding-left:5px;\" title='$title_vitdsc'>".$vitdsc."</td>";
				$html .= "<td>".$vsell."</td>";
				$html .= "<td class=\"lasttd\" title='$title_vcusgrp'>".$vcusgrp ."</td>";
                $html .= "</tr>";
			
			}
			
			// require('pagerx.php');
		// if($count>$per_page){		
		  	// echo "<tr height=\"30\"><td colspan=\"9\" align=\"right\" class=\"lasttd\"><div id=\"pagination\" >";
		  	// //echo $query;
		  	// $fld="page";
		  	// pagingx($query,$per_page,$num,$page);
		  	// echo "</div></td></tr>";

		// }
		
	
		echo $html;

	}
		
		
	?>
