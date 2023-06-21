<? session_start() ?>
<?
//if (session_is_registered('cusno'))
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
	 }else{
		   echo "<script> document.location.href='../".redir."'; </script>";
	 }
}else{	
header("Location:../login.php");
}
include('chklogin.php');
include('db/conn.inc');
$tblname=$alias."regimp";
$sql= "DESC ".$tblname;

mysqli_query($msqlcon,$sql);
//echo $sql;
//echo mysqli_errno();
if (mysqli_errno()==1146){
	$query2="CREATE TABLE ".$tblname." (
	CUST3 varchar(45),
	Corno varchar(20),
	orderdate varchar(8),
	duedate varchar(8),
	ordprd varchar(6),
	cusno varchar(8),
	partno varchar(15),
	partdes varchar(30),
	ordsts varchar(1),
	qty int(11),
	CurCD varchar(2),
	bprice decimal(18,4) NOT NULL,
	SGCurCD varchar(2),
	SGPrice decimal(18,8),
	impgrp varchar(3),
	DlrCurCd varchar(2),
	DlrPrice decimal(18,4),
	OECus varchar(1),
	Shipment varchar(1),
	) ENGINE=MyISAM DEFAULT CHARSET=utf8;";	
}else{
	$query2="delete from ".$tblname;
	
}

	mysqli_query($msqlcon,$query2);
	$imptable=$tblname;
	session_register['imptable'];

?>

<html>
	<head>
    <title>Denso Ordering System</title>
   	<link rel="stylesheet" type="text/css" href="css/dnia.css">
	</style><!--[if IE]>
<style type="text/css"> 
#twocolLeft{ padding-top: 0px; }
#twocolRight { zoom: 1; padding-top:10px; }
</style>	
<![endif]-->
<script type="text/javascript" language="javascript" src="lib/jquery-1.4.2.js"></script>
<script>
$(function() {
		    $("#result_tr1").hide()	  
		   $('#frmimport').submit(function(){
			if($('#txtShpNo').val()==''){
				alert('Ship to should be filled!');
			 			return false;
				}
			if($('#OrderType').val()==''){
				alert('Order Type should be filled!');
			 		return false;
				}
			
			})
		     $( "#txtShpNo" ).change(function() {
			//alert($("#txtShpNo" ).val());
			var rcv=$("#txtShpNo" ).val().split("|");
			//var rcv=data.split("||");
			var oecus=rcv[1].toUpperCase();
			if(oecus=='Y'){
				$("#result_tr1").show();
			}else{
				$("#result_tr1").hide()	   
			}
	});
		   })
</script>

</head>
<body >
   		<div id="header">
        <img src="images/denso.jpg" width="206" height="54" />
        </div>
		<div id="mainNav">
           <?
				$_GET['selection']="main";
				include("navhoriz.php");
			
			?>
        
			
		</div> 
    	<div id="isi">
        
        <div id="twocolLeft">
         <?
			  	$_GET['current']="mainAdd";
				include("navUser.php");
			  ?>  	
         </div>
        <div id="twocolRight">
          <form id="frmimport" method="post" enctype="multipart/form-data" action="mainimportadd.php">
        <table width="97%" border="0" cellspacing="0" cellpadding="0">
  <tr class="arial11blackbold">
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td></td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr class="arial11blackbold">
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td></td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr class="arial11blackbold">
    <td width="22%">Customer Number</td>
    <td width="2%">:</td>
    <td width="26%"><? echo $cusno ?></td>
    <td width="4%"></td>
    <td width="20%">Customer Name</td>
    <td width="2%">:</td>
    <td width="25%"><? echo $cusnm ?></td>
  </tr>
  <tr class="arial11blackbold">
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td></td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr class="arial11blackbold">
    <td>Ship To 
    </td>
    <td>:</td>
    <td colspan="5" class="arial11blackbold">
    <?
    $qryshp="SELECT cusmas.OECus,cusrem.cusno, cusrem.curcd, cusrem.remark FROM `cusmas` INNER JOIN cusrem ON cusmas.cusno = cusrem.cusno  where  trim(cusmas.cust3) ='".$cusno. "' order by cusrem.cusno";
	$sqlshp=mysqli_query($msqlcon,$qryshp);
	$mcount = mysqli_num_rows($sqlshp);
	//echo $mcount;
	echo '<select name="txtShpNo" id="txtShpNo" " style="width: 300px; font-size:11px" class="arialgrey">';
	echo '<option value="" ></option>';
	while($hasil = mysqli_fetch_array ($sqlshp)){
			$bcusno=$hasil['cusno'];
			$bremark=$hasil['remark'];
			$bcurcd=$hasil['curcd'];
			$voecus=$hasil['OECus'];
			if(strtoupper($voecus)!='Y'){
				$voecus='N';
			}
			$gabung=$bcusno . ' - '. $bremark . '  (' .$bcurcd.')' ;
	       	echo '<option value='.$bcusno .'|'.$voecus .'>' .$gabung.'</option>';
          
      
	}
		  echo '</select>';
	?>
    
    
    </td>
 
     
      <tr class="arial11blackbold">
    <td>
    </td>
    <td>&nbsp;</td>
    <td colspan="5" class="arial11blackbold">
   
    
    </td>
    </tr>
      <tr class="arial11blackbold">
    <td>Order Type</td>
    <td>:</td>
    <td colspan="5" class="arial11blackbold">
    <select name="OrderType" id="OrderType" class="arial11blackbold" style="width: 100px" >
        <option value="C">Campaign</option>
    </select>
    
    
    
    </td>
    </tr>
       </tr>
      <tr class="arial11blackbold"  >
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td colspan="5" class="arial11blackbold">&nbsp;</td>
  </tr>
  <tr class="arial11blackbold" id="result_tr1">
    <td>Shipment Mode</td>
    <td>:</td>
    <td colspan="5" class="arial11blackbold">
    	<select name="shipment" id="shipment" style="width: 200px; font-size:11px" class="arialgrey">
        	<option value="S" selected>Sea</option>
        	<option value="A">Air</option>
      </select>
    </td>
  </tr>    
        
   </table>     
        <table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr valign="middle" class="arial11">
    <th  scope="col">&nbsp;</th>
    <th width="90" scope="col">&nbsp;</th>
    <th width="90" scope="col" align="right">&nbsp;</th>
  </tr>
  <tr height="5"><td colspan="5"></td><tr>
</table>
		<p class="arial21redbold">Please upload your excel file (.xls) - if you are using excel 2007 and up, you should 
        convert to excel 2003 or above (xls format)
        :		  </p>
		<blockquote>
		
			<input name="file" type="file" size="50">
			<input name="upload" type="submit" value="Import">
      
         
        </blockquote>
        Download format excel here : <a href="db/order.xls" target="_blank" ><img src="images/excel.jpg" width="16" height="16" border="0"></a>
        
         </form>  
        </div>
          
<div id="footerMain1">
	<ul>
      
     
          
      </ul>

    <div id="footerDesc">

	<p>
	Copyright © 2023 DENSO . All rights reserved  
	
  </div>
</div>

	</body>
</html>
