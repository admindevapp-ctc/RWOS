<? session_start() ?>
<?
require_once('../../language/Lang_Lib.php');
if(isset($_SESSION['cusno']))
{       
	 if($_SESSION['redir']=='Order-SG'){
		$_SESSION['cusno'];
		$_SESSION['cusnm'];
		$_SESSION['redir'];
		$_SESSION['type'];
		$_SESSION['com'];
		$_SESSION['user'];
		$_SESSION['alias'];
		$_SESSION['tablename'];
    	$_SESSION['custype'];
		$_SESSION['dealer'];
		$_SESSION['group'];
		$cusno=	$_SESSION['cusno'];
		$cusnm=	$_SESSION['cusnm'];
		$password=$_SESSION['password'];
		$alias=$_SESSION['alias'];
		$table=$_SESSION['tablename'];
		$type=$_SESSION['type'];
		$custype=$_SESSION['custype'];
		$user=$_SESSION['user'];
		//$dealer=$_SESSION['dealer'];
		$group=$_SESSION['group'];

	 }else{
		   echo "<script> document.location.href='../".redir."'; </script>";
	 }
}else{	
header("Location:../login.php");
}
?>

<html>
	<head>
    <title>Denso Ordering System</title>
	<meta http-equiv="X-UA-Compatible" content="IE=9, IE=10, IE=11, IE=EDGE" /> 
   	<link rel="stylesheet" type="text/css" href="../css/dnia.css">
	<link rel="stylesheet" href="../admin/bootstrap/css/bootstrap.min.css">
    </style><!--[if IE]>
<style type="text/css"> 
#twocolLeft{ padding-top: 0px; }
#twocolRight { zoom: 1; padding-top:10px; }
</style>	
<![endif]-->

 <style type="text/css">
<!--
#example tbody {
	font-family: Arial, Helvetica, sans-serif;
	font-size: 11px;
	font-weight: normal;
}
#example thead{
	font-family: Arial, Helvetica, sans-serif;
	font-size: 12px;
	font-weight: bold;
	
}
#example thead tr th{
	border-bottom-width: 2px;
	border-bottom-style: solid;
	border-bottom-color: #B00;
	background-image: url(images/thbg.png);
	border-top-width: 1px;
	border-top-style: solid;
	border-top-color: #B00;
}

#example tbody tr td table tr td{
	border-bottom-width: 1px;
	border-bottom-style: solid;
	border-bottom-color: #B00;
		
}

#pagination a 
{
	list-style: none;
	margin-right: 5px;
	padding:5px;
	color:#0063DC;
	text-decoration: none;
	background-color: #E8E8E8;
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
-->

    </style>

<script type="text/javascript" language="javascript" src="lib/jquery-1.4.2.js"></script>
 <link rel="stylesheet" href="themes/ui-lightness/jquery-ui.css">	<script src="lib/jquery.ui.core.js"></script>
	<script src="lib/jquery.ui.widget.js"></script>
	<script src="lib/jquery.ui.mouse.js"></script>
	<script src="lib/jquery.ui.button.js"></script>
	<script src="lib/jquery.ui.draggable.js"></script>
	<script src="lib/jquery.ui.position.js"></script>
	<script src="lib/jquery.ui.resizable.js"></script>
	<script src="lib/jquery.ui.dialog.js"></script>
	<script src="lib/jquery.effects.core.js"></script>
    <script src="lib/jquery.ui.datepicker.js"></script>

	<link rel="stylesheet" href="../css/demos.css"> 
 
  <script type="text/javascript" charset="utf-8">
			
// Popup window code
	function newPopup(url) {
	popupWindow = window.open(
		url,'popUpWindow','height=710,width=500,left=500,top=300,resizable=yes,scrollbars=yes,toolbar=no,menubar=no,location=no,directories=no,status=no')
}	

			var globalfield="";
			var globalsort="";
			var globalsearch="";
			var globalchoose="";
			var globaldesc=""
			$(document).ready(function() {
			
				$('#ConvExcel').click(function(){
						url= 'cata_gettblcatalogueXLS.php',
						window.open(url);		
			  })	
				var edata="namafield="+namafield+"&sort="+order+"&search="+searchfield+"&choose="+choose+"&description="+desc;			  
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
  				<li><a href="tblpartno.php" target="_self">Table Part</a></li>
  				<li ><a href="logout.php" target="_self">Log out</a></li>
  				  				
			</ul>
	</div>
	
	<!--	<div id="mainNav">
        <?
				//$_GET['selection']="main";
				//include("navhoriz.php");
			
			?>
		</div> -->
    	<div id="isi">
        <div id="twocolRight1">
		
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
    <td width="13%">Customer Number</td>
    <td width="1%">:</td>
    <td width="51%"><? echo $cusno ?></td>
    <td width="1%"></td>
    <td width="15%">Customer Name</td>
    <td width="1%">:</td>
    <td width="15%"><? echo $cusnm ?></td>
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
    <td>Search</td>
    <td>:</td>
    <td align="left">
	
<form method="post" action="cata_searchbykey.php" id="orderdatefrom">
    <select name="cbosearch">
	    <option disabled="disabled" selected="selected">Search by ..</option>
    	<option>DENSO Part Number</option>
    	<option>Car Maker</option>
        <option>Model Name</option>
        <option>Model Code</option>
		<option>Customer Part Number</option>
		<option>CG Part Number</option>
		<option>Part Name</option>
    </select>
	<input type="text" name="txtsearch" placeholder="Type to Search" size="30" /><input type="submit" name="cmdsearch" value="<?php echo get_lng($_SESSION["lng"], "L0213"); ?>" class="arial11"/>
	</td>
</form>	
    <td>&nbsp;</td>
	<td>&nbsp;</td>
	<td>&nbsp;</td>
	<td>&nbsp;</td>
  </tr>
  <tr class="arial11blackbold">
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>OR</td>
    <td></td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr class="arial11blackbold">
    <td>&nbsp;</td>
	<td>&nbsp;</td>
	<td align="left"><?php include("cata_dd3.php"); ?></td>
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
        
 </table> 



<table cellpadding="0" cellspacing="0" border="0" class="tbl1" id="example" width="97%">
<tr align="center" height="30">

<?php
		
			function writeMsg() { 
			?>
   			<div class="col-xs-12">
        	<div class="alert alert-warning" align ="center">
            <span class="glyphicon glyphicon-info-sign"></span> &nbsp; <?php echo get_lng($_SESSION["lng"], "L0331"); ?>
            </div>
        </div>
		<?
			}
		$cat=$_POST['cat'];
		$subcat=$_POST['subcat'];
		$subcat3=$_POST['subcat3'];
		if($text=="" && $cat=="" && $subcat=="" && $subcat3==""){
			writeMsg();
		}
		else {
		?>
		</tr>
</table>

<table width="97%" border="0" cellspacing="0" cellpadding="0">
    <tr valign="middle" class="arial11" height="30">
    <th scope="col">&nbsp;</th>
    <th scope="col">&nbsp;</th>
    <th valign="middle" scope="col"></th>
    <th align="right"></th>
	<th width="20%" scope="col"><a href="#" id="ConvExcel"><img src="../images/export.png" width="101" height="25" border="0"></a></th>
    </tr>
  <tr valign="middle" class="arial11">
    <th width="20%" scope="col">&nbsp;</th>
    <th width="20%" scope="col">&nbsp;</th>
    <th width="20%" valign="middle" scope="col"></th>
    <th width="20%" scope="col"></th>
    <th width="10%" scope="col" align="right"></th>
  </tr>
</table>

<table cellpadding="0" cellspacing="0" border="0" class="tbl1" id="example" width="97%">
<thead>

		<tr align="center" height="20" >
			<th width="10%">Car Maker</th>
            <th width="10%">Model Name</th>
            <th width="10%">Model Code</th>
			<th width="10%">Engine Code</th>
			<th width="10%" >Customer P/NO</th>
			<th width="10%" >DENSO P/NO</th>
			<th width="10%" >CG P/NO</th>
			<th width="15%" >Part Name</th>
			<th width="5%" >Picture</th>
            <th width="5%" class="lastth">Details</th>
		</tr>

	</thead>
	<tbody>
    
<?php	

include('cata_connect.php');		

//echo " cat=$cat <br> subcat=$subcat <br> subcat3= $subcat3 ";


if ($cat == "" && $subcat == "" && $subcat3 == "") {
    $sql = "SELECT * FROM catalogue";
	echo " All Part";
    }
  elseif ($subcat == "" && $subcat3 == "") {
    $sql = "SELECT * FROM catalogue WHERE CarMaker LIKE '%".$cat."%'";
	echo " Car Maker=$cat";
    }
  elseif ($cat == "" && $subcat3 == "") {
    $sql = "SELECT * FROM catalogue WHERE ModelName LIKE '%$subcat%'";
	echo " Model Name=$subcat";
    }
  elseif ($cat == "" && $subcat == "") {
    $sql = "SELECT * FROM catalogue WHERE ModelCode LIKE '%".$subcat3."%'";
	echo " Model Code=$subcat3";
    }
  elseif ($subcat3 == "") {
    $sql = "SELECT * FROM catalogue WHERE CarMaker LIKE '%$cat%' AND ModelName LIKE '%$subcat%'";
	echo " Car Maker=$cat and Model Name=$subcat";
    }
  elseif ($cat == "") {
    $sql = "SELECT * FROM catalogue WHERE ModelName LIKE '%$subcat%' AND ModelCode LIKE '%$subcat3%'";
	echo " Model Name=$subcat and Model Code=$subcat3";
    }
  elseif ($subcat == "") {
    $sql = "SELECT * FROM catalogue WHERE CarMaker LIKE '%$cat%' AND ModelCode LIKE '%$subcat3%'";
	echo " Car Maker=$cat and Model Code=$subcat3";
    }
 else{
    $sql = "SELECT * FROM catalogue WHERE CarMaker LIKE '%$cat%' AND ModelName LIKE '%$subcat%' AND ModelCode LIKE '%$subcat3%'";
    echo " Car Maker=$cat and Model Name=$subcat and Model Code=$subcat3";
	}
	echo "<br>";
  // here you have your $sql ... now do whatever you want with your query
  //echo $sql;

		$id1 = mysqli_query($conn,$sql) or die(mysqli_error());

		while($dix=mysqli_fetch_array($id1))
		{
			
	?>
			<tr>
            <td><?php echo $dix[1]; ?></td>
            <td><?php echo $dix[2]; ?></td>
            <td><?php echo $dix[4]; ?></td>
            <td><?php echo $dix[5]; ?></td>
            <td><?php echo $dix[10]; ?></td>
            <td><?php echo $dix[12]; ?></td>
			<td><?php echo $dix[14]; ?></td>
			<td><?php echo $dix[15]; ?></td>
			<td><img src="../user_images/<?php echo $dix[3]; ?>" class="img-rounded" width="50px" height="50px" </td>
			<td align="center" class="lasttd"><a href="JavaScript:newPopup('cata_details.php?edit_id=<?php echo $dix[0]; ?>')">Details</a></td>
			</tr>
            <?php
		}
		}
?>


</tbody>
</table>
</div>
</div> 
          <p><br>
				
						
<div id="footerMain1">
	

	<ul>
      	<!--
     
          
	 -->
      </ul>  

    <div id="footerDesc">

	<p>
	Copyright &copy; 2023 DENSO . All rights reserved  
  </div>
</div>


	</body>
</html>
