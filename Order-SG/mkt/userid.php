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
		require('../db/conn.inc');
		$vid=trim($_GET['id']);
		echo $_GET['id'];
		$action=trim($_GET['action']);
		if($action=="edit"){
			$query="select * from userid  where UserName='". $vid."'";
		//echo $query;
		$sql=mysqli_query($msqlcon,$query);	
		if($hasil = mysqli_fetch_array ($sql)){
			$xCusno=$hasil['Cusno'];
			$type=$hasil['Type'];
			$xcompany=$hasil['COM'];
			$xredir=$hasil['Redir'];
			$password=$hasil['Password'];
			//echo $xcompany;			
			$inpid="<input type=\"text\" name=\"vuserid\" class=\"arial11blackbold\" style=\"width: 150px\"  readonly=\"true\" value ='".$vid."'>";
			$inpcusno="<input type=\"text\" name=\"vcusno\" class=\"arial11black\" maxlength=\"45\" size=\"46\" value='".$xCusno."'>";
			$inpcom="<input type=\"text\" name=\"vcompany\" class=\"arial11black\" maxlength=\"2\" size=\"3\" value='".$xcompany."'>";
			
			$inptype="<input type=\"text\"  name=\"vtype\" class=\"arial11black\" maxlength=\"1\" size=\"2\" value=".$type."></input>";
			$inppassword="<input type=\"password\"  name=\"vpassword\" class=\"arial11black\" maxlength=\"30\" size=\"30\" value='".$password."'></input>";
				
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
    <td colspan="6" class="arial21redbold">User ID  Maintenance</td>
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
    <td>User Id</td>
    <td>:</td>
    <td colspan="4"><? echo $inpid ?></td>
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
    <td><? echo $inpcusno ?></td>
    <td colspan="3">:<?
	if($action=="edit"){
		echo $inpname ;
	}
	?></td>
    </tr>
  <tr class="arial11blackbold">
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td colspan="4"><span class="arial11redbold">(A= Administrator, U=user, V=View only, M=Marketing)</span></td>
    </tr>
  <tr class="arial11blackbold">
    <td>&nbsp;</td>
    <td>Type</td>
    <td>:</td>
    <td><? echo $inptype ?></td>
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
    <td>&nbsp;</td>
    <td>Company</td>
    <td>:</td>
    <td><? echo $inpcom ?></td>
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
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr class="arial11blackbold">
    <td><img src="../images/password.png" width="16" height="16"></td>
    <td colspan="3"><span class="function"> Password</span></td>
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
    <td>Password</td>
    <td>:</td>
    <td><? echo $inppassword ?></td>
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
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td><input type="submit" name="Submit" class="arial11blackbold" id="Submit" value="Save Change"></td>
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
