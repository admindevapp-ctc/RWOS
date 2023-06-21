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
		
		$('#frmupduserid').submit(function() {
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
	function prt(x){
				
		}	
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
			  	$_GET['current']="mainUsrAdm";
				include("navAdm.php");
			  ?>
        </div>
        <div id="twocolRight">
        <?
		
		 $year=2012;
		$cyear=date('Y');
		$cmonth=date('m');
		$beda=$cyear-$year;
		
		
		
		$selthn='<option value=""></option>';
		$kali=$beda+1;
		
		for($x=1;$x<=$kali;$x++){
			$thn=$year+$x;
			if($thn!=$cyear){
			$selthn=$selthn . "<option value=".$thn.">".$thn."</option>";
			}else{
			$selthn=$selthn . "<option value=".$thn.">".$thn."</option>";
			};
		}
      $selbln="<select name=\"bulan\" class=\"arial11black\" id=\"blnprd\"> ";
		$selbln= $selbln."<option value=\"\"></option>";
			$selbln= $selbln."<option value=\"01\">January</option>";
			$selbln=$selbln."<option value=\"02\">February</option>";
		 	$selbln=$selbln."<option value=\"03\">March</option>";
			$selbln=$selbln."<option value=\"04\">April</option>";
			$selbln=$selbln."<option value=\"05\">May</option>";
			$selbln=$selbln."<option value=\"06\">June</option>";
			$selbln=$selbln."<option value=\"07\">July</option>";
			$selbln=$selbln."<option value=\"08\">Augustus</option>";
			$selbln=$selbln."<option value=\"09\">September</option>";
			$selbln=$selbln."<option value=\"10\">October</option>";
			$selbln=$selbln."<option value=\"11\">November</option>";
			$selbln=$selbln."<option value=\"12\">December</option>";
		$selbln=$selbln."</select>";
		
		require('../db/conn.inc');
		$vperiode=trim($_GET['periode']);
		$action=trim($_GET['action']);
		if($action=="edit"){
			$query="select * from cutofdate  where Period='". $vperiode."'";
		$sql=mysqli_query($msqlcon,$query);	
		if($hasil = mysqli_fetch_array ($sql)){
			$xCusno=$hasil['Cusno'];
			$type=$hasil['Type'];
			$inpid="<input type=\"text\" name=\"vuserid\" class=\"arial11blackbold\" style=\"width: 100px\"  readonly=\"true\" value =".$vid.">";
			$inpcusno="<input type=\"text\" name=\"vcusno\" class=\"arial11black\" maxlength=\"45\" size=\"46\" value='".$xCusno."'>";
			$inpcom="<input type=\"text\" name=\"vcompany\" class=\"arial11black\" maxlength=\"2\" size=\"3\" value='".$xcompany."'>";
			
			$inptype="<input type=\"text\"  name=\"vtype\" class=\"arial11black\" maxlength=\"1\" size=\"2\" value=".$type."></input>";
			$inppassword="<input type=\"password\"  name=\"vpassword\" class=\"arial11black\" maxlength=\"30\" size=\"30\" value=".$password."></input>";
				
		}
		}else{
			$inpid="<input type=\"text\" name=\"vuserid\" class=\"arial11blackbold\" style=\"width: 100px\"  ></input>";
			$inpcusno="<input type=\"text\" name=\"vcusno\" class=\"arial11black\" maxlength=\"45\" size=\"46\" ></input>";
			$inptype="<input type=\"text\"  name=\"vtype\" class=\"arial11black\" maxlength=\"1\" size=\"2\" ></input>";
			$inppassword="<input type=\"password\"  name=\"vpassword\" class=\"arial11black\" maxlength=\"30\" size=\"30\" ></input>";
				$inpcom="<input type=\"text\" name=\"vcompany\" class=\"arial11black\" maxlength=\"2\" size=\"3\"> </input>";
		}
	

		 $inpaction="<input type=\"hidden\" name=\"vaction\" value=".$action."></input>";
		?>
         <form name="frmupduserid" id="frmupduserid" method="post" action="upduserid.php">
           
        <table width="97%" border="0" cellspacing="0" cellpadding="0">
  <tr class="arial11blackbold">
    <td>&nbsp;</td>
    <td width="106">&nbsp;</td>
    <td width="16">&nbsp;</td>
    <td width="100"></td>
    <td width="92">&nbsp;</td>
    <td width="7">&nbsp;</td>
    <td width="197">&nbsp;</td>
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
    <td width="16"><img src="../images/calendar.gif" width="16" height="15"></td>
    <td colspan="6" class="arial21redbold">Cut Of Date  Maintenance</td>
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
    <td>Periode</td>
    <td>:</td>
    <td colspan="4"><? echo $inpPeriode ?></td>
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
    <td>Order Cut Of Date</td>
    <td>:</td>
    <td width="100"><? echo $selbln;
		echo "</td><td>";
		echo "<select name=\"tahun\" class=\"arial11black\" id=\"thnprd\">";
		echo $selthn;
		echo "</select>"; ?></td>
    <td colspan="2">&nbsp;</td>
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
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr class="arial11blackbold">
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td><? echo $inpaction ?></td>
    <td><input type="submit" name="Submit" class="arial11blackbold" id="Submit" value="Save Change"></td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
       
        </table>
       <p>
       <div id="result"> </div>
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
