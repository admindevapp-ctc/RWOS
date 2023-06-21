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



<script type="text/javascript" language="javascript" src="lib/jquery-1.4.2.js"></script>
<script type="text/javascript">
	$(document).ready(function() {
		$('.transfer').click(function(){
			//alert('test');						  
			$('#result').empty().html('<img src="images/35.gif" />');
			//$('#result').load('Updorder.php');
			var edata="";
			edata="ordtype=R";
						$.ajax({
							type: 'GET',
							url: 'DownFeedBack.php',
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
  				<li id="current"><a href="mainRFQ.php" target="_self">Marketing</a></li>
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
			  	$_GET['current']="mainFedAdm";
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
    <td colspan="6" class="arial21redbold">FeedBack Table</td>
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

        <table class="tbl1" width="100%"  cellspacing="0" cellpadding="0">
  <tr class="arial11grey" bgcolor="#AD1D36" >
  	<th width="10%" height="30" scope="col">Customer No</th>
    <th width="9%" height="30" scope="col">Order Date</th>
    <th width="23%" scope="col">Po Number</th>
    <th width="15%" scope="col">Denso Order Number</th>
    <th width="22%" scope="col">Part Number</th>
    <th width="11%" scope="col">Delivery Date</th>
    <th width="10%" scope="col">Qty</th>
    </tr>
  
     <?
	require('db/conn.inc');
	$per_page=10;
	 $num=5;
		  
	$query="select * from feedback  where substr(orderno,-1)='R'";
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
	
	$query1="select * from feedback  where substr(orderno,-1)='R'".		
	       " LIMIT $start, $per_page";
	$sql=mysqli_query($msqlcon,$query1);	
	while($hasil = mysqli_fetch_array ($sql)){
		$cusnov=$hasil['cusno'];
		$ordno=$hasil['orderno'];
		$corno=$hasil['Corno'];
		if(trim($corno)=="")$corno="-";
		$partno=$hasil['partno'];
		$orderdate=$hasil['orderdate'];
		$dlvdate=$hasil['dlvdate'];
		$periode=$hasil['periode'];
		$qty=$hasil['dlvQty'];
		$orddate=substr($orderdate,-2)."/".substr($orderdate,4,2)."/".substr($orderdate,0,4);
		$deldate=substr($dlvdate,-2)."/".substr($dlvdate,4,2)."/".substr($dlvdate,0,4);
		echo "<tr class=\"arial11black\" align=\"center\" ><td>".$cusnov."</td><td>".$orddate."</td><td>".$corno."</td>" ;
			
		echo "<td>".$ordno."</td>";
		echo "<td>".$partno."</td><td>".$deldate."</td>";
		echo "<td class=\"lasttd\">".$qty."<td ></tr>";
	}  //end while
	require('pager.php');
	if($count>$per_page){
			//echo $query;
			echo "<tr height=\"30\"><td colspan=\"7\" align=\"right\" class=\"lasttd\"><div id=\"pagination\" >";
		  	paging($query,$per_page,$num,$page);
		  	echo "</div></td></tr>";   
		   
	 echo "</div></td></tr>";
	}
  ?>
    
 
 <tr>
    <td colspan="7" class="lasttd" align="right" ><img src="images/print.png" width="20" height="20"> <span class="arial11redbold">= Print</span></td>
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
