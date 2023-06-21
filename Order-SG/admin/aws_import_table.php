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
	
	
	include "../db/conn.inc";
	$qse="SELECT * FROM awscusmas_temp WHERE Owner_Comp='$comp'";
	// echo $qse;
	$arr_data = array();
	$sqlqse=mysqli_query($msqlcon,$qse);
	while($arx=mysqli_fetch_assoc($sqlqse)){
		$arr_data[] = $arx;
		$cusno1=$arx['cusno1'];
		$shipto1=$arx['ship_to_cd1'];
		$cusno2=$arx['cusno2'];
		$shipto2=$arx['ship_to_cd2'];
		$shipaddr1=$arx['ship_to_adrs1'];
		$shipaddr2=$arx['ship_to_adrs2'];
		$shipaddr3=$arx['ship_to_adrs3'];
		$email1=$arx['mail_add1'];
		$email2=$arx['mail_add2'];
		$email3=$arx['mail_add3'];
		$error=$arx['error'];
	}
	$limit = 10;
	$num = 1;
	$i = 1;
	foreach($arr_data as $k=>$v){
		if($i <= $limit){
			$i++;
			$arr_pass_set[$num][] = $v;
		}else{
			$i = 1;
			$num++;
		}
		
	}
	// print_r($arr_error_set);
	echo json_encode($arr_pass_set);
	
	
?>
