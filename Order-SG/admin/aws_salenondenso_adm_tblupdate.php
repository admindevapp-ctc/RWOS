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
				case when sell = 1 then 'Sell' else 'Not Sell' end sell, group_concat(DISTINCT cusgrp ORDER BY cusgrp ASC) as cusgrp, supprice.price, supawsexc.curr
			FROM supawsexc 
			left join supcatalogue on trim(supcatalogue.ordprtno) = trim(supawsexc.prtno)
			left join supmas on supmas.supno = supawsexc.supcode
			left join supprice on supprice.Cusno = supawsexc.cusno1  and supawsexc.prtno = supprice.partno ". $criteria . " order by supawsexc.cusno1".
			   " LIMIT $start, $per_page";

		// echo $query1;   
		$sql=mysqli_query($msqlcon,$query1);	
			$html ="";
			if( ! mysqli_num_rows($sql) ) {
				$html = "<tr height=\"30\"><td colspan=\"12\" align=\"center\" class=\"arial12BoldGrey tbl_data\">" . get_lng($_SESSION["lng"], "E0060") /*No Data Found.... ! */ . "</td></tr>";
			}
			$html = '';
			while($hasil = mysqli_fetch_array ($sql)){
				$vowner=$hasil['Owner_Comp'];
				$vcusno1=$hasil['cusno1'];
				$vsupname=$hasil['supnm'];
				$vbrand=$hasil['Brand'];
				$vprtno=$hasil['prtno'];
				$vprtnm=$hasil['Prtnm'];
				$vsupnm=$hasil['supnm'];
				$vsell=$hasil['sell'];
				$vsupcd=$hasil['supcode'];
				$vcusgrp=$hasil['cusgrp'];
				
				if(strlen($vcusgrp) > 12){
					$title_vcusgrp = $vcusgrp;
                    $vcusgrp = substr($vcusgrp,0,12) ."...";
                }else{
					$title_vcusgrp = '';
				}
				if(strlen($vprtnm) > 25){
					$title_vprtnm = $vprtnm;
                    $vprtnm = substr($vprtnm,0,25) ."...";
                }else{
					$title_vprtnm = '';
				}
				
				$html .= "<tr class=\"arial11black\" align=\"center\" height=\"30\">";
                $html .= "<td>".$vowner."</td>";
                $html .= "<td>".$vcusno1."</td>";
				$html .= "<td>".$vsupnm."</td>";
				$html .= "<td>".$vbrand."</td>";
				$html .= "<td><a href='#' onclick='view_edit(this);' class='view' id='".$vcusno1."||".$vprtno."||".$vprtnm."||".$vsupcd."||".$vcusgrp."'>".$vprtno."</a></td>";
				$html .= "<td  align=\"left\" style=\"padding-left:5px;\" title='$title_vcusgrp'>".$vprtnm."</td>";
				$html .= "<td>".$vsell."</td>";
				$html .= "<td class=\"lasttd\" title='$title_vcusgrp'> ".$vcusgrp."</td>";
                $html .= "</tr>";
			
			}

		echo $html;

	}
	
		
		
	?>
