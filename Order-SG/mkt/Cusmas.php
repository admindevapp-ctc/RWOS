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
header("Location:../login.php");
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


<script type="text/javascript" language="javascript" src="../lib/jquery-1.4.2.js"></script>
<script type="text/javascript">
	$(document).ready(function() {
	
	$().ajaxStart(function() {
			$('#result').empty().html('<img src="images/35.gif" />');
	}).ajaxStop(function() {
		//$('#loading').hide();
		$('#result').fadeIn('slow');
	});
		
		
	$('#frmupdcusmas').submit(function() {
		$.ajax({
			type: 'POST',
			url: $(this).attr('action'),
			data: $(this).serialize(),
			success: function(data) {
				$('#result').html(data);
			}
		})
		return false;
	});	
		
		
		
   });
		
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
			  	$_GET['current']="mainCusAdm";
				include("navAdm.php");
			  ?>
        </div>
        <div id="twocolRight">
        <?
		require('../db/conn.inc');
		
		$action=trim($_GET['action']);
		//echo $action;
		if(trim($_GET['action'])=='add'){
			$inpcusno="<input type=\"text\" name=\"vcusno\" class=\"arial11blackbold\" maxlength=\"8\" size=\"9\"></input>";
			
			$inpname="<input type=\"text\" name=\"vcusnm\" class=\"arial11black\" maxlength=\"30\" size=\"30\" ></input>";
				$inpaddr1="<input type=\"text\"  name=\"vaddr1\" class=\"arial11black\" maxlength=\"45\" size=\"45\"></input>";
				$inpaddr2="<input type=\"text\"  name=\"vaddr2\" class=\"arial11black\" maxlength=\"45\" size=\"45\"></input>";
					$inpaddr3="<input type=\"text\"  name=\"vaddr3\" class=\"arial11black\" maxlength=\"45\" size=\"45\"></input>";
			$inptype="<input type=\"text\"  name=\"vtype\" class=\"arial11black\" maxlength=\"10\" size=\"10\" ></input>";
			$inpalias="<input type=\"text\" name=\"valias\"  class=\"arial11black\" maxlength=\"2\" size=\"3\" ></input>";
			$inpgroup="<input type=\"text\"  name=\"vgroup\" class=\"arial11black\" maxlength=\"2\" size=\"3\" ></input>";
			$inpdealer="<input type=\"text\"  name=\"vdealer\" class=\"arial11black\" maxlength=\"8\" size=\"9\"></input>";
				$inpcust2="<input type=\"text\"  name=\"vcust2\" class=\"arial11black\" maxlength=\"45\" size=\"45\"> </input>";
			$inpcust3="<input type=\"text\"  name=\"vcust3\" class=\"arial11black\" maxlength=\"45\" size=\"45\" ></input>";
			$inpcoy="<input type=\"text\"  name=\"vcoy\" class=\"arial11black\" maxlength=\"10\" size=\"10\" value=".$coy."></input>";
			$inpaction="<input type=\"hidden\" name=\"vaction\" value=".$action."></input>";	
			
$inproute="<input type=\"text\"  name=\"vroute\" class=\"arial11black\" maxlength=\"1\" size=\"2\"></input>";
		}else{
		$vcusno=trim($_GET['cusno']);
		$query="select * from cusmas where Cusno='". $vcusno."'";
		//echo $query;
		$sql=mysqli_query($msqlcon,$query);	
		if($hasil = mysqli_fetch_array ($sql)){
			$cusname=$hasil['Cusnm'];
			$type=$hasil['Custype'];
			$alias=$hasil['alias'];
			$group=$hasil['CusGr'];
			$dealer=$hasil['xDealer'];
			$coy=$hasil['COY'];
			$esca1=$hasil['ESCA1'];
			$esca2=$hasil['ESCA2'];
			$esca3=$hasil['ESCA3'];
			$cust2=$hasil['CUST2'];
			$cust3=$hasil['CUST3'];
			$route=$hasil['route'];
			
			$inpcusno="<input type=\"text\" name=\"vcusno\" class=\"arial11blackbold\" style=\"width: 100px\"  readonly=\"true\" value =".$vcusno.">";
			
			$inpname="<input type=\"text\" name=\"vcusnm\" class=\"arial11black\" maxlength=\"30\" size=\"30\" value='".$cusname."'>";
			$inptype="<input type=\"text\"  name=\"vtype\" class=\"arial11black\" maxlength=\"10\" size=\"10\" value=".$type."></input>";
			$inpcoy="<input type=\"text\"  name=\"vcoy\" class=\"arial11black\" maxlength=\"10\" size=\"10\" value=".$coy."></input>";
			$inpalias="<input type=\"text\" name=\"valias\"  class=\"arial11black\" maxlength=\"2\" size=\"3\" value=".$alias."></input>";
			$inpgroup="<input type=\"text\"  name=\"vgroup\" class=\"arial11black\" maxlength=\"2\" size=\"3\" value='".$group."'></input>";
			$inpaddr1="<input type=\"text\"  name=\"vaddr1\" class=\"arial11black\" maxlength=\"45\" size=\"45\" value='".$esca1."'></input>";
			$inpaddr2="<input type=\"text\"  name=\"vaddr2\" class=\"arial11black\" maxlength=\"45\" size=\"45\" value='".$esca2."'></input>";
			$inpaddr3="<input type=\"text\"  name=\"vaddr3\" class=\"arial11black\" maxlength=\"45\" size=\"45\" value='".$esca3."'></input>";
			$inpcust2="<input type=\"text\"  name=\"vcust2\" class=\"arial11black\" maxlength=\"45\" size=\"45\" value='".$cust2."'></input>";
			$inpcust3="<input type=\"text\"  name=\"vcust3\" class=\"arial11black\" maxlength=\"45\" size=\"45\" value='".$cust3."'></input>";
$inproute="<input type=\"text\"  name=\"vroute\" class=\"arial11black\" maxlength=\"1\" size=\"2\" value='".$route."'></input>";
			
			
			$inpdealer="<input type=\"text\"  name=\"vdealer\" class=\"arial11black\" maxlength=\"8\" size=\"9\" value=".$dealer."></input>";
$inpaction="<input type=\"hidden\" name=\"vaction\" value=".$action."></input>";			
			
		}
		}
		
		?>
         <form id="frmupdcusmas" name="frmupdcusmas" method="post" action="updcusmas.php">
            
        <table width="97%" border="0" cellspacing="0" cellpadding="0">
  <tr class="arial11blackbold">
    <td>&nbsp;</td>
    <td width="20%">&nbsp;</td>
    <td width="3%">&nbsp;</td>
    <td width="24%"></td>
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
    <td colspan="6" class="arial21redbold">Customer  Maintenance</td>
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
    <td>&nbsp;</td>
    <td>Customer Number</td>
    <td>:</td>
    <td colspan="4"> <? echo $inpcusno ?></td>
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
    <td>&nbsp;</td>
    <td>Customer Name</td>
    <td>:</td>
    <td colspan="4"><? echo $inpname ?></td>
    </tr>
  <tr class="arial11blackbold">
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td></td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td class="arial11redbold">(A=AWS, D=Dealer)</td>
  </tr>
  <tr class="arial11blackbold">
    <td>&nbsp;</td>
    <td>Alias</td>
    <td>:</td>
    <td><? echo $inpalias ?></td>
    <td>Type</td>
    <td>:</td>
    <td><? echo $inptype ?></td>
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
    <td>&nbsp;</td>
    <td>Address 1</td>
    <td>:</td>
    <td colspan="4"><? echo $inpaddr1 ?></td>
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
    <td>&nbsp;</td>
    <td>Address 2</td>
    <td>:</td>
    <td colspan="4"><? echo $inpaddr2 ?></td>
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
    <td>&nbsp;</td>
    <td>Address3</td>
    <td>:</td>
    <td colspan="4"><? echo $inpaddr3 ?></td>
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
    <td>&nbsp;</td>
    <td>Group</td>
    <td>:</td>
    <td><? echo $inpgroup ?></td>
    <td>COY</td>
    <td>&nbsp;</td>
    <td><? echo $inpcoy ?></td>
    </tr>
  <tr class="arial11blackbold">
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr class="arial11blackbold">
    <td>&nbsp;</td>
    <td>Cust 2</td>
    <td>&nbsp;</td>
    <td colspan="4"><? echo $inpcust2 ?></td>
    </tr>
  <tr class="arial11blackbold">
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr class="arial11blackbold">
    <td>&nbsp;</td>
    <td>Cust 3</td>
    <td>&nbsp;</td>
    <td colspan="4"><? echo $inpcust3 ?></td>
    </tr>
  <tr class="arial11blackbold">
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr class="arial11blackbold">
    <td>&nbsp;</td>
    <td>Dealer Code /Upline</td>
    <td>:</td>
    <td><? echo $inpdealer ?></td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr class="arial11blackbold">
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr class="arial11blackbold">
    <td>&nbsp;</td>
    <td>Route</td>
    <td>:</td>
    <td colspan="4"><? echo $inproute ?> &quot;D=Direct  N=Not Direct </td>
    </tr>
  <tr class="arial11blackbold">
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td><? echo $inpaction ?></td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td><input type="submit" name="Submit" class="arial11blackbold" id="Submit" value="Save Change"></td>
  </tr>
       
        </table>
       <p><div id="result"></div>
       <table width="100%" border="0" cellspacing="0" cellpadding="0">
          <tr valign="middle" class="arial11">
    <th width="20%" scope="col" height="24">&nbsp;</th>
    <th width="20%" scope="col"></th>
    <th width="20%" valign="middle" scope="col"></th>
    <th width="20%" scope="col"></th>
    <th width="20%" scope="col" align="right">
      </th>
  </tr>
</table>

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
