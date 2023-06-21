<? session_start() ?>
<?
//if (session_is_registered('cusno'))
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
	 }else{
		   echo "<script> document.location.href='../".redir."'; </script>";
	 }
}else{	
header("Location:../login.php");
}


include('chklogin.php');

?>


<html>
<head>
    <title>Denso Ordering System</title>
   	<link rel="stylesheet" type="text/css" href="css/dnia.css">

</head>
<body >
    
<?
	   include "crypt.php";
	   require('db/conn.inc');
	   $var = decode($_SERVER['REQUEST_URI']);
	   $vordno=trim($var['ordno']);
	   $vcusno=trim($var['cusno']);
	   $vperiode=trim($var['periode']);
	   $vcorno=trim($var['corno']);
	   $vorderdt=trim($var['orderdt']);
	   $vthn=substr($vperiode,0,4);
	   $vbln=substr($vperiode,-2);
	 	
		
	?>
   		<div id="header">
        <img src="images/denso.jpg" width="206" height="54" />
        </div>
		<div id="mainNav">
       
        
			<?
				$_GET['selection']="main";
				include("navhoriz.php");
			
			?>
	</div> 
    	<div id="isi1">
        
        
        
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
    <td width="25%">Customer Number</td>
    <td width="2%">:</td>
    <td width="28%"><? echo $cusno ?></td>
    <td width="3%"></td>
    <td width="16%">Customer Name</td>
    <td width="1%">:</td>
    <td width="24%"><? echo $cusnm ?></td>
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
    <td>Order Date</td>
    <td>:</td>
    <td><? echo $vorderdt ?></td>
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
   
    <td>Denso Order No.</td>
    <td>:</td>
    <td  class="arial11blackbold"><? echo $vordno ?></td>
   <td></td>
    <td>Status</td>
    <td>:</td>
    <td><span class="arial11blackbold"> Regular</span></td>
  </tr>
  <tr class="arial11blackbold">
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td  class="arial11blackbold">&nbsp;</td>
    <td></td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td width="1%">&nbsp;</td>
  </tr>
  <tr class="arial11blackbold">
    
    <td>PO Number</td>
    <td>:</td>
    <td  class="arial11blackbold"><? echo $vcorno ?></td>
    <td></td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
        </table>
        
        
      
       
<p>&nbsp;</p>
        <table width="97%" class="tbl1" cellspacing="0" cellpadding="0" id="myTable">
          <tbody>
          <tr class="arial11white" bgcolor="#AD1D36" align="center" >
            <th width="25%" rowspan="2">Part Number</th>
            <th width="11%" rowspan="2">Order Qty</th>
            <th colspan="4">FeedBack /Answer </th>
          </tr>
          <tr class="arial11white" bgcolor="#AD1D36" align="center" >
            <th >Back Order</th>
            <th >Ready Stock</th>
            <th >S/I Issue</th>
            <th >New Order</th>
           </tr>
          <?
		  	$query="select * from orderdtl left join feedback on orderdtl.cusno=feedback.cusno and left(orderdtl.corno,10)=left(feedback.corno,10)  and orderdtl.partno=feedback.partno where trim(orderdtl.cust3) ='".$vcusno. "' and trim(orderdtl.orderno)='".$vordno."'  order by orderdtl.partno";
		//echo $query;
		$sql=mysqli_query($msqlcon,$query);	
		$mcount = mysqli_num_rows($sql);
			while($hasil = mysqli_fetch_array ($sql)){
				$partno=$hasil['partno'];
				$qty=$hasil['qty'];
				$qty1=$hasil['qty1'];
				$qty2=$hasil['qty2'];
				$qty3=$hasil['qty3'];
				$qty4=$hasil['qty4'];
				$qty5=$hasil['qty5'];
				echo "<tr class=\"arial11black\" align=\"center\" height=\"25\"><td>". $partno."</td><td>". $qty."</td><td>". $qty2."</td><td>". $qty4."</td><td>". $qty5."</td><td>". $qty3."</td></tr>";		
											
			}
			

		 
		  ?>
          
          
          
          </tbody>
          
</table>
        

              
<p>&nbsp;</p>
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
