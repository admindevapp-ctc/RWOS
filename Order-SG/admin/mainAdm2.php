<? session_start() ?>
<?
if(isset($_SESSION['cusno']))
{       
		$_SESSION['cusno'];
		$_SESSION['cusnm'];
		$_SESSION['cusad1'];
		$_SESSION['cusad2'];
		$_SESSION['cusad3'];
		$_SESSION['type'];
		$_SESSION['zip'];
		$_SESSION['state'];
		$_SESSION['phone'];
		$_SESSION['password'];
		$_SESSION['alias'];
		$_SESSION['tablename'];

	$cusno=	$_SESSION['cusno'];
	$cusnm=	$_SESSION['cusnm'];
	$cusad1=$_SESSION['cusad1'];
	$cusad2=$_SESSION['cusad2'];
	$cusad3=$_SESSION['cusad3'];
	$type=$_SESSION['type'];
	$zip=$_SESSION['zip'];
	$state=$_SESSION['state'];
	$phone=$_SESSION['phone'];
	$password=$_SESSION['password'];
	$alias=$_SESSION['alias'];
	$table=$_SESSION['tablename'];
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
		$('.transfer').click(function(){
			$('#result').empty().html('<img src="images/35.gif" />');
			//$('#result').load('Updorder.php');
			var edata="";
			edata="ordtype=R";
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
              <ul>
            		<li id="current"><a href="#">Regular Order</a></li>
            		   <li><a href="mainaddAdm.php" target="_self">Additional Order</a></li>
                    <li><a href="History.php" target="_self">All Order</a></li>
            		
		</ul>
           <div class="headerbar1">Password</div>
          </div>
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
    <td colspan="6" class="arial21redbold">Un-Transfered Regular Order</td>
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
</table>

        <table width="100%" border="1" cellspacing="0" cellpadding="0">
  <tr class="arial11grey" bgcolor="#AD1D36" >
  	<th width="12%" height="30" scope="col">Customer No</th>
    <th width="9%" height="30" scope="col">Order Date</th>
    <th width="23%" scope="col">Po Number</th>
    <th width="20%" scope="col">Denso Order Number</th>
    <th width="17%" scope="col">Order Periode</th>
    <th width="9%" scope="col">Status</th>
    <th width="10%" scope="col">action</th>
    </tr>
  
    
     <?
		  require('db/conn.inc');
		  
		  $per_page=2;
		  $num=5;
		  
	$query="select * from orderhdr  where odrsts='R' and trim(Trflg) =''";
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
	
	$query="select * from orderhdr  where odrsts='R' and trim(Trflg) =''".		
	       " LIMIT $start, $per_page";
	$sql=mysqli_query($msqlcon,$query);	
		
			while($hasil = mysqli_fetch_array ($sql)){
				$cusnov=$hasil['cusno'];
				$ordno=$hasil['orderno'];
				$corno=$hasil['Corno'];
				if($corno=="")$corno="-";
				$orderstatus=$hasil['odrsts'];
				$orderdate=$hasil['orderdate'];
				$periode=$hasil['periode'];
				$orddate=substr($orderdate,-2)."/".substr($orderdate,4,2)."/".substr($orderdate,0,4);
				if($orderstatus=='R'){
					$ordsts='Regular';
				}
						
		$urlprint= "<a href='prtorderpdf.php?ordno=".$ordno."' target=\"new\"> <img src=\"images/print.png\" width=\"20\" height=\"20\"></a>";
		
		echo "<tr class=\"arial11blackbold\" align=\"center\" ><td>".$cusnov."</td><td>".$orddate."</td><td>".$corno."</td>" ;
			
			echo "<td>".$ordno."</td>";
			echo "<td>".$periode."</td><td>".$ordsts."</td>";
			echo "<td >".$urlprint."<td >";
			}
		  
		   echo "<tr height=\"20\"><td colspan=\"7\" align=\"right\"><div id=\"pagination\" >";
		   if($pages!=1 && $pages!=0){	
				$prev=$page-1;
				if($prev!=0){
					echo '<a href="?page='.$prev.'">Previous</a>';
				}else{
					echo '<a href="#">Previous</a>';
				}
	
	    		if($page>=$num){
					if($pages<$page+2){
						$tgh=$pages-4;
					}else{
						$tgh=$page-2;
					}
				}else{
					$tgh=1;
				}
		
				$y=0;
				for($x=$tgh; $x<=$pages; $x++)
					{
					$y++;
					if($y<=5){
						if($page==$x){
							echo 	"<a href='?page=".$x. "' class=\"current\" >".$x."</a>";
						}else{
							echo 	"<a href='?page=".$x. "'>".$x."</a>";
	
						}
					}else{
			  			break;	
					}
				}
	
	
	
				if($pages>$page){
					$next=$page+1;
					echo '<a href="?page='.$next.'">Next</a>';
				}else{
					echo '<a href="#">Next</a>';
				}
	}
		   
		   
		   
		   
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
    <td colspan="6" class="arial21redbold">Transfered Regular Order</td>
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
  	<th width="12%" height="30" scope="col">Customer No</th>
    <th width="9%" height="30" scope="col">Order Date</th>
    <th width="23%" scope="col">Po Number</th>
    <th width="20%" scope="col">Denso Order Number</th>
    <th width="17%" scope="col">Order Periode</th>
    <th width="9%" scope="col">Status</th>
    <th width="10%" scope="col">action</th>
    </tr>
  
    
     <?
		require('db/conn.inc');
		
		
		$query="select * from orderhdr  where odrsts='R' and trim(Trflg) !=''";
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

		
		$query="select * from orderhdr  where odrsts='R' and trim(Trflg) !='' LIMIT $mstart, $per_page"; 
		//echo $query;
		$sql=mysqli_query($msqlcon,$query);		
			while($hasil = mysqli_fetch_array ($sql)){
				$cusnov=$hasil['cusno'];
				$ordno=$hasil['orderno'];
				$corno=$hasil['Corno'];
				if($corno=="")$corno="-";
				$orderstatus=$hasil['odrsts'];
				$orderdate=$hasil['orderdate'];
				$periode=$hasil['periode'];
				$orddate=substr($orderdate,-2)."/".substr($orderdate,4,2)."/".substr($orderdate,0,4);
				if($orderstatus=='R'){
					$ordsts='Regular';
				}
			
		$urlprint= "<a href='prtorderpdf.php?ordno=".$ordno."' target=\"new\"> <img src=\"images/print.png\" width=\"20\" height=\"20\"></a>";
		
		echo "<tr class=\"arial11blackbold\" align=\"center\" ><td>".$cusnov."</td><td>".$orddate."</td><td>".$corno."</td>" ;
			
			echo "<td>".$ordno."</td>";
			echo "<td>".$periode."</td><td>".$ordsts."</td>";
			echo "<td >".$urlprint."<td >";
			}
		  echo "<tr height=\"20\"><td colspan=\"7\" align=\"right\"><div id=\"pagination1\" >";
		   if($mpages!=1 && $mpages!=0){	
				$prev=$mpage-1;
				if($prev!=0){
					echo '<a href="?mpage='.$prev.'">Previous</a>';
				}else{
					echo '<a href="#">Previous</a>';
				}
	
	    		if($mpage>=$num){
					if($mpages<$mpage+2){
						$tgh=$mpages-4;
					}else{
						$tgh=$mpage-2;
					}
				}else{
					$tgh=1;
				}
		
				$y=0;
				for($x=$tgh; $x<=$mpages; $x++)
					{
					$y++;
					if($y<=5){
						if($mpage==$x){
							echo 	"<a href='?mpage=".$x. "' class=\"current\" >".$x."</a>";
						}else{
							echo 	"<a href='?mpage=".$x. "'>".$x."</a>";
	
						}
					}else{
			  			break;	
					}
				}
	
	
	
				if($mpages>$mpage){
					$next=$mpage+1;
					echo '<a href="?page='.$next.'">Next</a>';
				}else{
					echo '<a href="#">Next</a>';
				}
	}
		   
		   
		   
		   
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
