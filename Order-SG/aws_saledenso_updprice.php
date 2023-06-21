<?php



session_start();
require_once('./../core/ctc_init.php'); // add by CTC

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

    $array=  $_POST['data'];
    
    $sql = "";
    for($i = 0; $i < count($array); $i++) {
        if($array[$i][0] == "All"){
            $sql =  " update awsexc set curr='".$array[$i][1]."', price='".$array[$i][2]."' , sell=".$array[$i][5]."  where Owner_Comp = '$owner_comp' and cusno1 = '".$array[$i][3]."' and  trim(itnbr) = '".$array[$i][4]."'";
        }
        else{
            //$result = $result .  $array[$i][0] .  $array[$i][1] . $array[$i][2] . "<br>";
            $sql =   "update awsexc set curr='".$array[$i][1]."', price='".$array[$i][2]."'  , sell=".$array[$i][5]." where Owner_Comp = '$owner_comp' and cusgrp = '". $array[$i][0] ."' and cusno1 = '".$array[$i][3]."' and  trim(itnbr) = '".$array[$i][4]."'";
        }
        $result = mysqli_query($msqlcon,$sql);
    }
    
   // echo $sql;
?>