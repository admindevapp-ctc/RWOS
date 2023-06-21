<?php 

session_start();
require_once('./../../core/ctc_init.php'); // add by CTC

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
    $comp = ctc_get_session_comp(); // add by CTC
		if($type!='a'){
      header("Location:../main.php");
    }
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
  
    <?php ctc_get_logo(); ?> <!-- add by CTC -->

		<div id="mainNav">
       
        
			<ul>  
  				<li id="current"><a href="maincusadm.php" target="_self">Administration</a></li>
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
			$inpcusno= '<select name="vcusno" id="vcusno" class="arial11blue">';
			$inpcusno= $inpcusno .  ' <option value=""></option>';
			$query="select Cusno, Cusnm from cusmas cm where not exists (select 1 from cusrem cr where cm.Cusno = cr.Cusno and cm.Owner_Comp = cr.Owner_Comp) and Owner_Comp='$comp'";
			$sql=mysqli_query($msqlcon,$query);	
			while($hasil = mysqli_fetch_array ($sql)){
				$ycusno=$hasil['Cusno'];
				$ycusnm=$hasil['Cusnm'];
			    $inpcusno= $inpcusno .  ' <option value="'.$ycusno.'">'.$ycusno.' - '. $ycusnm. ' </option>';
				//echo $ycusno;
			}
        	$inpcusno= $inpcusno . ' </select>';
			
			//$inpcusno="<input type=\"text\" name=\"vcusno\" class=\"arial11blackbold\" maxlength=\"8\" size=\"9\"></input>";
			$inpname="<input type=\"text\" name=\"vcusnm\" class=\"arial11black\" maxlength=\"30\" size=\"30\" ></input>";
			$inpremark="<input type=\"text\"  name=\"vremark\" class=\"arial11black\" maxlength=\"45\" size=\"45\" required></input>";
			$inpcurcd="<input type=\"text\"  name=\"vcurcd\" class=\"arial11black\" maxlength=\"10\" size=\"10\" required></input>";
	    	$inpaction="<input type=\"hidden\" name=\"vaction\" value=".$action."></input>";			
	
		}else{
			$vcusno=trim($_GET['cusno']);
			$query="select * from cusrem inner join cusmas on cusrem.cusno=cusmas.cusno and cusrem.Owner_Comp=cusmas.Owner_Comp where cusrem.Cusno='". $vcusno."' and cusrem.Owner_Comp='". $comp."'";
		//echo $query;
			$sql=mysqli_query($msqlcon,$query);	
			if($hasil = mysqli_fetch_array ($sql)){
				$cusname=$hasil['Cusnm'];
				$curcd=$hasil['curcd'];
				$remark=$hasil['remark'];
				
			
			$inpcusno="<input type=\"text\" name=\"vcusno\" class=\"arial11blackbold\" style=\"width: 100px\"  readonly=\"true\" value =".$vcusno.">";
			
			$inpname="<input type=\"text\" name=\"vcusnm\" class=\"arial11black\" maxlength=\"30\" size=\"30\" readonly=\"true\" value='".$cusname."'>";
			$inpcurcd="<input type=\"text\"  name=\"vcurcd\" class=\"arial11black\" maxlength=\"10\" size=\"10\" value=".$curcd."></input>";
			$inpremark="<input type=\"text\"  name=\"vremark\" class=\"arial11black\" maxlength=\"45\" size=\"45\" value='".$remark."'></input>";
		
		$inpaction="<input type=\"hidden\" name=\"vaction\" value=".$action."></input>";			
			
		}
		}
		
		?>
         <form id="frmupdcusmas" name="frmupdcusmas" method="post" action="updcusrem.php">
            
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
    <td colspan="6" class="arial21redbold">Customer  Remark Maintenance</td>
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
    <td colspan="4" > <? echo $inpcusno ?>
       
      </td>
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
  <?
  if(trim($_GET['action'])!='add'){
  	echo '<tr class="arial11blackbold">';
    echo '<td>&nbsp;</td>';
    echo '<td>Customer Name</td>';
    echo '<td>:</td>';
    echo '<td colspan="4">'. $inpname .'</td>';
    echo '</tr>';
    echo '<tr class="arial11blackbold"> <td>&nbsp;</td> <td>&nbsp;</td> <td>&nbsp;</td> <td></td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr>';
  }

	?>
  <tr class="arial11blackbold">
    <td>&nbsp;</td>
    <td>Currency Code</td>
    <td>:</td>
    <td colspan="4"><? echo $inpcurcd ?></td>
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
    <td>Customer Remark</td>
    <td>:</td>
    <td colspan="4"><? echo $inpremark ?></td>
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

 

<div id="footerMain1">
	<ul>
     <!-- 
     
      -->
      </ul>

    <div id="footerDesc">

	Copyright &copy; 2023 DENSO . All rights reserved  
	
  </div>
</div>
</div>
	</body>
</html>
