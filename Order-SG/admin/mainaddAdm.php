<? session_start() ?>
<?
//if (session_is_registered('cusno'))
if(isset($_SESSION['cusno']))
{       
		$_SESSION['cusno'];
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
	if($type!='a')header("Location: main.php");
   //echo $type;
}else{	
header("Location: login.php");
}
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


<script type="text/javascript" language="javascript" src="lib/jquery-1.4.2.js"></script>
<script type="text/javascript">
	$(document).ready(function() {
		$().ajaxStart(function() {
			$('#result').empty().html('<img src="images/35.gif" />');
	}).ajaxStop(function() {
		//$('#loading').hide();
		$('#result').fadeIn('slow');
	});
		
		$('.transfer').click(function(){
			$('#result').empty().html('<img src="images/35.gif" />');
			//$('#result').load('Updorder.php');
			edata="ordtype=S";
						$.ajax({
							type: 'GET',
							url: 'Updorder.php',
							data: edata,
							success: function(data) {
								//alert(data);
								location.reload();
								$('#result').html(data);
								}
						})
			
			
								  
		 })
			
						   
   });
	function prt(x){
				
		}	
</script>



	</head>
	<body >
   		<div id="header">
        <img src="images/denso.jpg" width="206" height="54" />
        </div>
		<div id="mainNav">
       
        
			<ul>  
  				<li id="current"><a href="mainadm.php" target="_self">Administration</a></li>
				<li><a href="Profile.php" target="_self">User Profile</a></li>
  				<li><a href="tblpartno.php" target="_self">Table Part</a></li>
  				<li ><a href="logout.php" target="_self">Log out</a></li>
  				  				
			</ul>
	</div> 
    	<div id="isi">
        
        <div id="twocolLeft">
           	<div class="hmenu">
        	  <div class="headerbar">Administration</div>
              <?
			  	$MYROOT=$_SERVER['DOCUMENT_ROOT'];
			  	$_GET['current']="mainaddAdm";
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
    <td width="3%"><img src="images/calendar.gif" width="16" height="15"></td>
    <td colspan="6" class="arial21redbold">Un-Transfered Additonal Order</td>
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
    <th width="20%" scope="col" height="24">&nbsp;</th>
    <th width="20%" scope="col">&nbsp;</th>
    <th width="20%" valign="middle" scope="col"></th>
    <th width="20%" scope="col"></th>
    <th width="20%" scope="col" align="right"><a href="#"><img src="images/transfer.png" width="100" height="20" class="transfer"></a></th>
  </tr>
  <tr valign="middle"  >
    <td colspan="5" height="5"></td>
  </tr>
</table>

     <table width="100%" border="1" cellspacing="0" cellpadding="0">
  <tr class="arial11grey" bgcolor="#AD1D36" >
  	<th width="9%" height="30" scope="col">Customer</th>
    <th width="9%" height="30" scope="col">Order Date</th>
    <th width="20%" scope="col">Po Number</th>
    <th width="20%" scope="col">Denso Order Number</th>
    <th width="16%" scope="col">Delivered by</th>
    <th width="10%" scope="col">Status</th>
    <th width="16%" scope="col">action</th>
    </tr>
    
     <?
		  require('db/conn.inc');
		  
		  $per_page=2;
		  $num=5;
		  
	$query="select * from orderhdr  where ordtype!='R' and ordflg='1' and trim(Trflg) =''";
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
	
	$query="select * from orderhdr  where ordtype!='R' and ordflg='1' and trim(Trflg) =''".		
	       " LIMIT $start, $per_page";
	$sql=mysqli_query($msqlcon,$query);	
		
			while($hasil = mysqli_fetch_array ($sql)){
				$cusnov=$hasil['cusno'];
				$ordno=$hasil['orderno'];
				$corno=$hasil['Corno'];
				if($corno=="")$corno="-";
				$dlvby=trim($hasil['DlvBy']);
				$orderstatus=$hasil['ordtype'];
				$orderdate=$hasil['orderdate'];
				$periode=$hasil['periode'];
				$orddate=substr($orderdate,-2)."/".substr($orderdate,4,2)."/".substr($orderdate,0,4);
				switch($orderstatus){
				case 'R':
					$ordsts='Regular';
					break;
					
				case 'U':
					$ordsts='Urgent';
					break;	
				case 'C':
					$ordsts='Campaign';
					break;	
				case 'S':
					$ordsts='Special';
					break;
				
				}
				
				switch(strtoupper($dlvby)){
				case 'A':
					$delivered='By Air';
					break;
					
				case 'N':
					$delivered='Normal';
					break;	
				case 'H':
					$delivered='Hand Carry';
					break;	
					
				}
			

						
		$urlprint= "<a href='prtaddorderpdf.php?ordno=".$ordno."' target=\"new\"> <img src=\"images/print.png\" width=\"20\" height=\"20\"></a>";
		
		echo "<tr class=\"arial11blackbold\" align=\"center\"><td>".$cusnov."</td><td>".$orddate."</td><td>".$corno."</td>" ;
			echo "<td>".$ordno."</td>";
			echo "<td>".$delivered."</td><td>".$ordsts."</td>";
			
			echo "<td >".$urlprint."<td >";
			
			}
		   echo "<tr height=\"20\"><td colspan=\"7\" align=\"right\"><div id=\"pagination\" >";
		  require('pager.php');
		  $fld="page";
		  pagingfld($query,$per_page,$num,$mpage,$fld);
		  echo "</div></td></tr>"
		  ?>
 
 <tr>
    <td colspan="7" class="arial21redbold" align="right"><img src="images/print.png" width="20" height="20"> = print</td>
    </tr> 
</table>
<div id="result" class="arial11redbold" align="center"> </div>
<p>
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
    <td width="3%"><img src="images/calendar.gif" width="16" height="15"></td>
    <td colspan="6" class="arial21redbold">Transfered Additional Order</td>
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
<table width="100%" border="1" cellspacing="0" cellpadding="0">
  <tr class="arial11grey" bgcolor="#AD1D36" >
  	<th width="9%" height="30" scope="col">Customer</th>
    <th width="9%" height="30" scope="col">Order Date</th>
    <th width="20%" scope="col">Po Number</th>
    <th width="20%" scope="col">Denso Order Number</th>
    <th width="16%" scope="col">Delivered by</th>
    <th width="10%" scope="col">Status</th>
    <th width="16%" scope="col">action</th>
    </tr>
  
    
     <?
		require('db/conn.inc');
		
		
		$query="select * from orderhdr  where ordtype!='R' and trim(Trflg) !=''";
		$sql=mysqli_query($msqlcon,$query);
		$mcount = mysqli_num_rows($sql);
		$mpages = ceil($mcount/$per_page);
		$mpage = $_GET['mpage'];
		if($mpage){ 
			$mstart = ($mpage - 1) * $per_page; 			
		}else{
			$mstart = 0;	
			$mpage=1;
		}

		
		$query1="select * from orderhdr  where ordtype!='R' and trim(Trflg) !='' LIMIT $mstart, $per_page"; 
		//echo $query;
		$sql=mysqli_query($msqlcon,$query1);		
			while($hasil = mysqli_fetch_array ($sql)){
				$cusnov=$hasil['cusno'];
				$ordno=$hasil['orderno'];
				$corno=$hasil['Corno'];
				if($corno=="")$corno="-";
				$dlvby=trim($hasil['DlvBy']);
				$orderstatus=$hasil['ordtype'];
				$orderdate=$hasil['orderdate'];
				$periode=$hasil['orderprd'];
				$orddate=substr($orderdate,-2)."/".substr($orderdate,4,2)."/".substr($orderdate,0,4);
				switch($orderstatus){
				case 'R':
					$ordsts='Regular';
					break;
					
				case 'U':
					$ordsts='Urgent';
					break;	
				case 'C':
					$ordsts='Campaign';
					break;	
				case 'S':
					$ordsts='Special';
					break;
				
				}
				
				switch(strtoupper($dlvby)){
				case 'A':
					$delivered='By Air';
					break;
					
				case 'N':
					$delivered='Normal';
					break;	
				case 'H':
					$delivered='Hand Carry';
					break;	
					
				}
			

						
		$urlprint= "<a href='prtaddorderpdf.php?ordno=".$ordno."' target=\"new\"> <img src=\"images/print.png\" width=\"20\" height=\"20\"></a>";
		
		echo "<tr class=\"arial11blackbold\" align=\"center\"><td>".$cusnov."</td><td>".$orddate."</td><td>".$corno."</td>" ;
			echo "<td>".$ordno."</td>";
			echo "<td>".$delivered."</td><td>".$ordsts."</td>";
			echo "<td >".$urlprint."<td >";
			}
		  echo "<tr height=\"30\"><td colspan=\"7\" align=\"right\"><div id=\"pagination1\" >";
		  //require('pager.php');
		  $fld="mpage";
		  pagingfld($query,$per_page,$num,$mpage,$fld);
		  echo "</div></td></tr>"
		  
		  ?>
 
 <tr>
    <td colspan="7" class="arial21redbold" align="right"><img src="images/print.png" width="20" height="20"> = print</td>
    </tr> 
</table>

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
