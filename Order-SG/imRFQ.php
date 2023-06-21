<? session_start() ?>
<?
//if (session_is_registered('cusno'))
ini_set('session.bug_compat_warn', 0);
ini_set('session.bug_compat_42', 0);
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
//echo $sql;
//echo mysqli_errno();


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
		   
		   $('#frmimport').submit(function(){
			
		   
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
			  	$_GET['current']="RFQ";
				include("navUser.php");
			  ?>
        </div>
        <div id="twocolRight">
          <form id="frmimport" method="post" enctype="multipart/form-data" action="RFQimport.php">
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
        Download format excel here : <a href="db/rfq.xls" target="_blank" ><img src="images/excel.jpg" width="16" height="16" border="0"></a>
        
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
