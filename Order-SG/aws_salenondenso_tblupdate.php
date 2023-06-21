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
		$comp = ctc_get_session_comp(); // add by CTC
	 }else{
		echo "<script> document.location.href='../".redir."'; </script>";
	 }
}else{	
	header("Location:../login.php");
}
	require('../db/conn.inc');
     
	if(isset($_POST)){
		$per_page = 10;
		$num = 5;
		$criteria = " where supawsexc.Owner_Comp='$comp' and supawsexc.cusno1='$cusno'";
		if (sizeof($_POST) != 0) {
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
		$pages = ceil($count/$per_page);
		$page = $_POST['page'];
		if($page){ 
			$start = ($page - 1) * $per_page; 			
		}else{
			$start = 0;	
			$page=1;
		}
		$criteria .= "  group by  supawsexc.Owner_Comp,supawsexc.supcode, cusno1, trim(supawsexc.prtno) ";
		
		$query1 = "SELECT supawsexc.Owner_Comp,supawsexc.supcode, supmas.supnm, cusno1, trim(supawsexc.prtno) as prtno, supcatalogue.Prtnm, supcatalogue.brand,
			case when (
				select sell from supawsexc a where supawsexc.Owner_Comp=a.Owner_Comp and supawsexc.cusno1 = a.cusno1  and supawsexc.prtno = a.prtno order by sell desc limit 1
			) = 1 then 'Sell' else 'Not Sell' end sell, group_concat(DISTINCT cusgrp ORDER BY cusgrp ASC) as cusgrp, price, curr
			FROM supawsexc left join supcatalogue on trim(supawsexc.prtno) = trim(supcatalogue.ordprtno) and supawsexc.Owner_Comp = supcatalogue.Owner_Comp 
			left join supmas on supawsexc.supcode = supmas.supno and supawsexc.Owner_Comp = supmas.Owner_Comp  " . $criteria . "order by cusno1" .
			" LIMIT $start, $per_page";
		$html ="";
		$sql = mysqli_query($msqlcon, $query1);
		if (!mysqli_num_rows($sql)) {
			echo "<tr height=\"30\"><td colspan=\"12\" align=\"center\" class=\"arial12BoldGrey\">" . get_lng($_SESSION["lng"], "E0060") /*No Data Found.... ! */ . "</td></tr>";
		}
		while ($hasil = mysqli_fetch_array($sql)) {
			$vowner = $hasil['Owner_Comp'];
			$vcusno1 = $hasil['cusno1'];
			$vsupnm = $hasil['supnm'];
			$vbrand = $hasil['brand'];
			$vprtno = $hasil['prtno'];
			$vsupno = $hasil['supcode'];
			$vPrtnm = $hasil['Prtnm'];
			$vbrand = $hasil['brand'];
			$vsell = $hasil['sell'];
			$vcusgrp = $hasil['cusgrp'];
			$vprice = $hasil['price'];
			$vcurr = $hasil['curr'];
			if (strlen($vcusgrp) > 12) {
				$vcusgrp = substr($vcusgrp, 0, 12) . "...";
			}

			$html.= "<tr class=\"arial11black\" align=\"center\" height=\"30\">";
			$html.= "<td>" . $vowner . "</td>";
			$html.= "<td>" . $vsupnm . "</td>";
			$html.= "<td>" . $vbrand . "</td>";
			$html.= "<td><a href='#' class='view_edit' onclick='view_edit(this);' id='" . $vcusno1 . "||" . $vprtno . "||" . $vPrtnm . "||" . $vcusgrp . "||" . $vsupno . "'>" . $vprtno . "</a></td>";
			$html.= "<td style=\"padding-left:5px;\" align=\"left\">" . $vPrtnm . "</td>";
			$html.= "<td>" . $vsell . "</td>";
			$html.= "<td class=\"lasttd\">" . $vcusgrp . "</td>";
			$html.= "</tr>";
		}

		// require('pager.php');
		// if ($count > $per_page) {
			// echo "<tr height=\"30\"><td colspan=\"9\" align=\"right\" class=\"lasttd\"><div id=\"pagination\" >";
			// //echo $query;
			// $fld = "page";
			// paging($query, $per_page, $num, $page);
			// echo "</div></td></tr>";
		// }
		echo $html;

	}
	
		
		
	?>
