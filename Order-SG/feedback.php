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
	</style><!--[if IE]>
<style type="text/css"> 
#twocolLeft{ padding-top: 0px; }
#twocolRight { zoom: 1; padding-top:10px; }
</style>	
<![endif]-->


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
			  	$_GET['current']="feedback";
				include("navUser.php");
			  ?>
          
        </div>
        <div id="twocolRight">
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
    <th width="20%" scope="col">&nbsp;</th>
    <th width="20%" scope="col">&nbsp;</th>
    <th width="20%" valign="middle" scope="col"></th>
    <th width="20%" scope="col"></th>
    <th width="20%" scope="col" align="right"></th>
  </tr>
  <tr height="5"><td colspan="5"></td><tr>
</table>

        <table width="100%" class="tbl1" cellspacing="0" cellpadding="0">
  <tr class="arial11grey" bgcolor="#AD1D36" >
    <th width="9%" height="30" scope="col">Order Date</th>
    <th width="23%" >Po Number</th>
    <th width="23%" >Denso Order Number</th>
    <th width="14%" >Order Date</th>
    <th width="13%" >Customer No</th>
    <th width="18%" class="lastth">action</th>
    </tr>
  
    
     <?
		  require('db/conn.inc');
		  $page=trim($_GET['page']);
		  $per_page=5;
		  $num=5;
		  include "crypt.php";
		  
		  if($page){ 
			$start = ($page - 1) * $per_page; 			
			}else{
				$start = 0;	
				$page=1;
			}
		  
		  
	$query="select orderdtl.orderno, orderdtl.cusno, orderdtl.orderdate, orderdtl.Corno FROM orderdtl inner JOIN feedback ON left(orderdtl.corno,10) = left(feedback.corno,10) AND orderdtl.partno = feedback.partno where  trim(orderdtl.cust3) ='".$cusno. "'";
	$query=$query . "  group by orderdtl.cusno, orderdtl.orderno order by orderdtl.orderno desc";
	$query1=$query . " LIMIT $start, $per_page";
	//echo $query1;
		$sql=mysqli_query($msqlcon,$query1);		
			while($hasil = mysqli_fetch_array ($sql)){
				$ordno=$hasil['orderno'];
				$corno=$hasil['Corno'];
				$shpno=$hasil['cusno'];
				if(trim($corno)=="")$corno="-";
				$orderstatus=substr($ordno,-1);
				$orderdate=$hasil['orderdate'];
				//$periode=$hasil['periode'];
				$orddate=substr($orderdate,-2)."/".substr($orderdate,4,2)."/".substr($orderdate,0,4);
				if($orderstatus=='R'){
					$ordsts='Regular';
				}
        		$trflg=$hasil['Trflg'];
			
		
		$paraview="ordno=".$ordno."&cusno=".$cusno."&shpno=".$shpno."&periode=".$periode."&corno=".$corno."&orderdt=".$orddate;
        $para= paramEncrypt($paraview);	
		$urlprint= "<a href='prtfeedpdf.php?".$para."' target=\"new\"> <img src=\"images/print.png\" width=\"20\" height=\"20\" border=\"0\"></a>";
		$urlview= "<a href='frmfeedbck.php?".$para."'> <img src=\"images/view.png\" width=\"20\" height=\"20\" border=\"0\"></a>";
		
		echo "<tr class=\"arial11black\" align=\"center\" height=\"25\"><td>".$orddate."</td><td>".$corno."</td>" ;
			
			echo "<td>".$ordno."</td>";
			echo "<td>".$orddate."</td><td>".$shpno."</td>";
			echo "<td class=\"lasttd\">";
			echo $urlprint;
			echo $urlview;
			echo "<td >";
			}
		  
		  echo "<tr height=\"30\"><td colspan=\"6\" align=\"right\" class=\"lasttd\"><div id=\"pagination\" >";
		  require('pager.php');
		  paging($query,$per_page,$num,$page);
		  echo "</div></td></tr>"
		
		  ?>
 
 <tr>
    <td colspan="6" class="lasttd" align="right"><img src="images/print.png" width="20" height="20"> <span class="arial11redbold">= print</span>,  <img src="images/view.png" width="20" height="20"><span class="arial11redbold">= view</span></td>
    </tr> 
</table>


        </div>
              
<div id="footerMain1">
	<ul>
      
     
          
      </ul>

    <div id="footerDesc">

	<p>
	Copyright &copy; 2023 DENSO . All rights reserved  
	
  </div>
</div>

	</body>
</html>
