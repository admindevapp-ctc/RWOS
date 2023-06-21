<? session_start() ?>
<?
if(isset($_SESSION['cusno']))
{       
	if($_SESSION['redir']=='Order-SG'){
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
		if($type!='a')header("Location:../main.php");
	 }else{
		   echo "<script> document.location.href='../../".redir."'; </script>";
	 }
}else{	
header("Location:../../login.php");
}
?>

<html>
	<head>
    <title>Denso Ordering System</title>
   	<link rel="stylesheet" type="text/css" href="../css/dnia.css">
	</style><!--[if IE]>
<style type="text/css"> 
#twocolLeft{ padding-top: 0px; }
#twocolRight { zoom: 1; padding-top:10px; }
</style>	
<![endif]-->
 <style type="text/css">
<!--

#pagination a 
{
	list-style: none;
	margin-right: 5px;
	padding:5px;
	color:#333;
	text-decoration: none;
	background-color: #F3F3F3;
	font-family: Verdana, Geneva, sans-serif;
	font-size: 10px;
}
#pagination a:hover 
{
color:#FF0084;
cursor: pointer;
}

#pagination a.current 
{
	list-style: none;
	margin-right: 5px;
	padding:5px;
	color:#FFF;
	background-color: #000;
}

#pagination1 a 
{
	list-style: none;
	margin-right: 5px;
	padding:5px;
	color:#333;
	text-decoration: none;
	background-color: #F3F3F3;
	font-family: Verdana, Geneva, sans-serif;
	font-size: 10px;
}
#pagination1 a:hover 
{
color:#FF0084;
cursor: pointer;
}

#pagination1 a.current 
{
	list-style: none;
	margin-right: 5px;
	padding:5px;
	color:#FFF;
	background-color: #000;
}


-->
 </style>


    <link rel="stylesheet" href="../themes/ui-lightness/jquery-ui.css">

	<script src="../lib/jquery-1.4.2.min.js"></script>

	<script src="../lib/jquery.ui.core.js"></script>
 	<script src="../lib/jquery.ui.widget.js"></script>
     <script src="../lib/jquery.ui.mouse.js"></script>
	<script src="../lib/jquery.ui.button.js"></script>
	<script src="../lib/jquery.ui.draggable.js"></script>
	<script src="../lib/jquery.ui.position.js"></script>
	<script src="../lib/jquery.ui.resizable.js"></script>
	<script src="../lib/jquery.ui.dialog.js"></script>
	<script src="../lib/jquery.effects.core.js"></script>
     <script src="../lib/jquery.ui.datepicker.js"></script>
   <script src="../lib/jquery.ui.autocomplete.js"></script>
  <link rel="stylesheet" href="../css/demos.css">   
    <style>
		body { font-size: 62.5%; }
		label, input { display:block; }
		input.text { margin-bottom:12px; width:95%; padding: .4em; }
		fieldset { padding:0; border:0; margin-top:25px; }
		h1 { font-size: 1.2em; margin: .6em 0; }
		div#users-contain { width: 350px; margin: 20px 0; }
		div#users-contain table { margin: 1em 0; border-collapse: collapse; width: 100%; }
		div#users-contain table td, div#users-contain table th { border: 1px solid #eee; padding: .6em 10px; text-align: left; }
		.ui-dialog .ui-state-error { padding: .3em; }
		.validateTips { border: 1px solid transparent; padding: 0.3em; }
		button .btn{
			height: 18px;
			width: 18px;
		}
	</style>
    
     
<script type="text/javascript">
$(function() {
$('#ConvExcel').click(function(){
						url= 'gettblslsPLNXLS.php',
						window.open(url);	
					
		 })	

 })
</script>



	</head>
	<body >
   		<div id="header">
        <img src="../images/denso.jpg" width="206" height="54" />
        </div>
		<div id="mainNav">
       
        
			<ul>  
  				<li id="current"><a href="mainRFQ.php" target="_self">Marketing</a></li>
				<li><a href="Profile.php" target="_self">User Profile</a></li>
  				<li ><a href="../logout.php" target="_self">Log out</a></li>
  				  				
			</ul>
	</div> 
    	<div id="isi">
        
        <div id="twocolLeft">
           	<div class="hmenu">
        	  <div class="headerbar">Administration</div>
               <?
			  	$MYROOT=$_SERVER['DOCUMENT_ROOT'];
			  	$_GET['current']="mainSlsPlnAdm";
				include("navAdm.php");
			  ?>
        </div>
        <div id="twocolRight">
        <table width="97%" border="0" cellspacing="0" cellpadding="0">
  <tr class="arial11blackbold">
    <td>&nbsp;</td>
    <td width="19%">&nbsp;</td>
    <td width="25%">&nbsp;</td>
    <td width="3%"></td>
    <td width="19%">&nbsp;</td>
    <td width="1%">&nbsp;</td>
    <td width="30%">&nbsp;</td>
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
    <td width="3%"><img src="../images/calendar.gif" width="16" height="15"></td>
    <td colspan="6" class="arial21redbold">Sales Plan Maintenance</td>
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
    <th scope="col" height="24">&nbsp;</th>
    <th scope="col">&nbsp;</th>
    <th valign="middle" scope="col"> <a href="#" id="ConvExcel"><img src="../images/export.png" width="101" height="25" border="0"></a>
    </th>
    <th scope="col">&nbsp;</th>
    <th scope="col" align="right"><? 
	if($type!="v") echo "<a href=\"imslsplan.php\"><img src=\"../images/importxls.gif\" width=\"80\" height=\"20\"></a>";
	?></th>
  </tr>
  <tr valign="middle" class="arial11">
    <th width="20%" scope="col" height="24">&nbsp;</th>
    <th width="20%" scope="col">&nbsp;</th>
    <th width="20%" valign="middle" scope="col"></th>
    <th width="20%" scope="col"></th>
    <th width="20%" scope="col" align="right"></th>
  </tr>
</table>

     <table width="100%"  class="tbl1" cellspacing="0" cellpadding="0">
  <tr class="arial11grey" bgcolor="#AD1D36" >
  	<th width="15%" scope="col">Product</th>
    <th width="15%" scope="col">Sub Group</th>
    <th width="10%" scope="col">CUST3</th>
    <th width="10%" scope="col">Month</th>
    <th width="20%" scope="col">Qty</th>
    <th width="20%" scope="col">Amount</th>
    <th width="10%" scope="col">action</th>
    </tr>
    
     <?
		  require('../db/conn.inc');
		  
		  $per_page=10;
		  $num=5;
		  
	$query="select * from slsplan";
	$sql=mysqli_query($msqlcon,$query);
	$count = mysqli_num_rows($sql);
	
	$pages = ceil($count/$per_page);
	$page = $_GET['page'];
	if($page){ 
		$start = ($page - 1) * $per_page; 			
	}else{
		$start = 0;	
		$page=1;
	}
	
	$query1="select * from slsplan   order by PROD, SUBPROD".		
	       " LIMIT $start, $per_page";
	$sql=mysqli_query($msqlcon,$query1);	
		
			while($hasil = mysqli_fetch_array ($sql)){
				$vprod=$hasil['PROD'];
				$vsubprod=$hasil['SUBPROD'];
				$vbiztyp=$hasil['BIZTYP'];
				$vcusnm=$hasil['CUSNM'];
				$vyyyymm=$hasil['YYYYMM'];
				$vqty=$hasil['QTY'];
				$vamount=$hasil['AMOUNT'];
				$vcust3=$hasil['CUST3'];
				
				
		echo "<tr class=\"arial11black\" align=\"center\" height=\"30\"><td>".$vprod."</td><td>".$vsubprod."</td><td>".$vcust3."</td><td>".$vyyyymm."</td><td>".$vqty."</td><td>".$vamount."</td>" ;
			
			echo "<td class=\"lasttd\">";
			echo "<a href='getslsplan.php?action=delete&prod=$vprod&subprod=$vsubprod&cust3=$vcust3' onclick=\"return confirm('Are you sure you want to delete?')\"> <img src=\"../images/delete.png\" width=\"20\" height=\"20\" border=\"0\"></a>";
			
		//	echo "<a href='#' class='edit' id=".$vprod."||".$vsubprod."||".$vcust3."||".$vyyyymm."||".$vbiztyp."||".$vcusnm."||".$vqty."||".$vamount."> <img src=\"../images/edit.png\" width=\"20\" height=\"20\" border=\"0\"  ></a> ";
			
			echo "<td ></tr>";
			
			}
			
			require('pager.php');
		if($count>$per_page){		
		  	echo "<tr height=\"30\"><td colspan=\"7\" align=\"right\" class=\"lasttd\"><div id=\"pagination\" >";
		  	//echo $query;
		  	$fld="page";
		  	paging($query,$per_page,$num,$page);
		  	echo "</div></td></tr>";
		}
		
		
		  ?>
 
 <tr>
    <td colspan="7"  align="right" class="lasttd" ><img src="../images/edit.png" width="20" height="20"><span class="arial11redbold"> = edit</span></td>
    </tr> 
</table>
<div id="result" class="arial11redbold" align="center"> </div>
<p>





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
