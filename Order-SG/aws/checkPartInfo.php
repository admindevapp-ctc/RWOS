<?php 

session_start();
require_once('../../core/ctc_init.php'); // add by CTC

require_once('../../language/Lang_Lib.php');

$comp = ctc_get_session_comp(); // add by CTC


if(isset($_SESSION['cusno']))
{       
	 if($_SESSION['redir']!='denso-sg'){
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
		$_SESSION['awstable'];
		$awstable=$_SESSION['awstable'];
		$cusno=	$_SESSION['cusno'];
		$cusnm=	$_SESSION['cusnm'];
		$alias=$_SESSION['alias'];
		$table=$_SESSION['tablename'];
		$type=$_SESSION['type'];
		$custype=$_SESSION['custype'];
		$user=$_SESSION['user'];
		$dealer=$_SESSION['dealer'];
		$group=$_SESSION['group'];
	 }else{
		echo "<script> document.location.href='../../".redir."'; </script>";
	 }
}else{	
	header("Location:../../login.php");
}

$msg='';
//require('../sendemail.php');

if(empty($_GET['partno'])){
	$msg=get_lng($_SESSION["lng"], "W0009") /*'Please key in part no to check.</br>'*/;
}
else if(!empty($_GET['partno'])) {
	$partno=strtoupper(trim($_GET['partno']));
	$qty=!empty($_GET['qty']) ? $_GET['qty'] : 0;
	if(is_numeric($qty)){
		$ordertype=$_GET['ordertype'];
		require('../db/conn.inc');
		
		#***************MTO*********************
		# $mtoQry="select * from mto where prtno='".$partno."'"; (DGAMS New MTO)
		if(ctc_get_session_erp() == '0'){
			$mtoQry="select * from mto where prtno='".$partno."' and Owner_Comp='$comp'";  // edit by CTC
		}else{
			$mtoQry="select * from bm008pr where MTO='1' and ITNBR='".$partno."' and Owner_Comp='$comp'";  // edit by CTC
		}
		$mtoflag='';
		$mtoResult=mysqli_query($msqlcon,$mtoQry);
		if($mtoArray = mysqli_fetch_array ($mtoResult)){
			$mtoflag="Yes";
		}
		else{
			$mtoflag="-";
		}
		
		#***************Part Name and Lotsize*********************
		$lotsizeQry="select * from bm008pr where ITNBR='".$partno."' and Owner_Comp='$comp'";	// edit by CTC
		$productResult=mysqli_query($msqlcon,$lotsizeQry);
		if($productArray = mysqli_fetch_array ($productResult)){
			$lotsize=$productArray['Lotsize'];
			$itdsc=$productArray['ITDSC'];
		}
		else{
			$lotsize=0;
		}
		$msg.= get_lng($_SESSION["lng"], "L0145"). /*Part Name*/": <font color=green>".$itdsc."</font><br/>";
		#***************Stock*********************
		$stockqty1=0;
		$stockqty2=0;
		$stockqty=0;
		
		$qry1="select * from availablestock where prtno='".$partno."' and Owner_Comp='$comp' ";  // edit by CTC
			$qry1Result=mysqli_query($msqlcon,$qry1);
			if($stockArray = mysqli_fetch_array ($qry1Result)){
				$stockqty=$stockArray['qty'];
				if($stockqty >= $qty){
					$msg.= get_lng($_SESSION["lng"], "L0071").":<font color=green>".get_lng($_SESSION["lng"], "L0288")."</font> <br/>"/*"Stock Availability: <font color=green>Yes</font> <br/>"*/;
				}
				else{
					$msg.= get_lng($_SESSION["lng"], "L0071").":<font color=green>".get_lng($_SESSION["lng"], "L0289")."</font> <br/>"/*"Stock Availability: <font color=red>No</font> <br/>"*/;
				}
			}
			else{
				$qry2="select * from hd100pr where prtno='".$partno."' and (l1awqy+l2awqy)>=".$qty;
				$qry2Result = mysqli_query($msqlcon,$qry2);
				$count2 = mysqli_num_rows($qry2Result);
				if($count2>0){
					$msg.= get_lng($_SESSION["lng"], "L0071").":<font color=green>".get_lng($_SESSION["lng"], "L0288")."</font> <br/>"/*"Stock Availability: <font color=green>Yes</font> <br/>"*/;
				} else{
					$msg.= get_lng($_SESSION["lng"], "L0071").":<font color=green>".get_lng($_SESSION["lng"], "L0289")."</font> <br/>"/*"Stock Availability: <font color=red>No</font> <br/>"*/;
				}
			
			}
		
		$msg.=get_lng($_SESSION["lng"], "L0072")./*MTO*/": <font color=green>".$mtoflag."</font><br/>";
		$msg.=get_lng($_SESSION["lng"], "L0073")./*Sales Lot Size*/": <font color=green>".number_format($lotsize)."</font><br>";
	}
	else{
		$msg=get_lng($_SESSION["lng"], "W0025")/*"Error: Qty should be filled by numeric"*/;
	}
	
}
echo $msg;

?>