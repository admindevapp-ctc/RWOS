<?php 

session_start();
require_once('./../../core/ctc_init.php'); // add by CTC

if(isset($_SESSION['cusno']))
{       
	$_SESSION['cusnm'];
	$_SESSION['password'];
	$_SESSION['alias'];
	$_SESSION['tablename'];
	$_SESSION['user'];
	$_SESSION['dealer'];
	$_SESSION['group'];
	$_SESSION['type'];
	$_SESSION['custype'];

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
	$comp = ctc_get_session_comp(); 
  
}else{	
	header("Location: login.php");
}
/* Database connection information */
require('../db/conn.inc');



if(isset($_POST['vCusno1']) && isset($_POST['vPartno'])){
	$Cusno1 = $_POST['vCusno1'];
	$Partno = $_POST['vPartno'];
    $Cusgrp = $_POST['vCusgrp'];
    
   // $table  .= '<table border="0" id="part_detail">';
    $table  .= '<table border="0" id="data">';
    $table  .= '<thead>';
    $table  .= '<tr>';
    $table  .= '<th><span class=\"arial11redbold\">Customer Group</span></th>';
    $table  .= '<th><span class=\"arial11redbold\">Currency</span></th>';
    $table  .= '<th><span class=\"arial11redbold lasttd\">Price (Optional)</span></th>';
    $table  .= '</tr>';
    $table  .= '</thead>';
    $table  .= '<tbody>';

	$per_page=1;
	$num=5;

    $query1="SELECT distinct awsexc.Owner_Comp, cusno1,    
    case when sell = 1 then 'Sell' else 'Not Sell' end sell, cusgrp, price, curr
    FROM awsexc left join bm008pr on trim(awsexc.itnbr) = trim(bm008pr.ITNBR) AND awsexc.Owner_Comp = bm008pr.Owner_Comp
    WHERE awsexc.Owner_Comp='$comp' and cusno1 = '".$Cusno1."' and trim(awsexc.itnbr) = '".$Partno."'";
	if(strlen($Cusgrp) == 3){
        $query1.=" and awsexc.cusgrp in ('".$Cusgrp."')";
    }
	$sqlp=mysqli_query($msqlcon,$query1);
	$count = mysqli_num_rows($sqlp);
	
	$pages = ceil($count/$per_page);
	$page = $_GET['page'];
	if($page){ 
		$start = ($page - 1) * $per_page; 			
	}else{
		$start = 0;	
		$page=1;
	}
//echo $Cusgrp;

    $query="SELECT distinct awsexc.Owner_Comp, cusno1,    
    case when sell = 1 then 'Sell' else 'Not Sell' end sell, cusgrp, price, curr
    FROM awsexc left join bm008pr on trim(awsexc.itnbr) = trim(bm008pr.ITNBR) AND awsexc.Owner_Comp = bm008pr.Owner_Comp 
    WHERE awsexc.Owner_Comp='$comp' and cusno1 = '".$Cusno1."' and trim(awsexc.itnbr) = '".$Partno."'";
    if(strlen($Cusgrp) == 3){
        $query.=" and awsexc.cusgrp in ('".$Cusgrp."')";
    }
  //  echo $query;
    $sql=mysqli_query($msqlcon,$query);
    
    while($hasil = mysqli_fetch_array ($sql)){
		$price = $hasil['price'] == '' ? '' : number_format($hasil['price'],2);
        $table  .= '<tr>';
        $table  .=    '<td><input style="text-align:center;" type="text" id="p_cusgroup" readonly="true" value="'.$hasil['cusgrp'].'"/></td>';
        $table  .=    '<td> <input style="text-align:center;" type="text" id="p_currency" readonly="true" value="'.$hasil['curr'].'"/></td>';
        $table  .=    '<td><input style="text-align:right;" type="text" id="p_price" readonly="true" value="'.$price. '"/></td>';
        $table  .= '</tr>';
    }

    $table  .= '</tbody>';
    $table  .= '</table>';
    echo $table;

}

?>
