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
		
	<!--	
		<div id="mainNav">
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
	<td align="left"><?Php include("cata_dd3.php"); ?></td>
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

   
<table width="97%" border="0" cellspacing="0" cellpadding="0">
	<tr >
    <th scope="col">&nbsp;</th>
    <th scope="col">&nbsp;</th>
    <th valign="middle" scope="col"></th>
    <!-- <th colspan="2" scope="col" align="right"><a href="#" id="new1" ><img src="../images/search.png" width="101" height="25" border="0"></a></th> -->
    </tr>
	<tr valign="middle" class="arial11">
    <th width="20%" scope="col">&nbsp;</th>
    <th width="20%" scope="col">&nbsp;</th>
    <th width="20%" valign="middle" scope="col"></th>
    <th width="20%" scope="col"></th>
    <th width="20%" scope="col" align="right"></th>
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

		$text = $_POST['txtsearch'];
		$cbo = $_POST['cbosearch'];
		if($text=="" || $cbo==""){ writeMsg();
			//echo "<td align=\"center\" class=\"lasttd\">No Data Found....Please Try Again!!! </td>";
		}
		else {
		?>
	</tr>
</table>

<?php 
function tableheader (){  ?>

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
}
		$cbo = $_POST['cbosearch'];
		$search = $_POST['txtsearch'];
		include('cata_connect.php');
	

		if($cbo=="DENSO Part Number")
		{
			$id = mysqli_query($conn,"SELECT * FROM catalogue WHERE Prtno like '%".$search."%'");
			$count=mysqli_num_rows($id);
			if ($count<=0){ writeMsg();} else{
				tableheader ();
		while($di=mysqli_fetch_array($id))
		{
			
	?>
			<tr>
            <td><?php echo $di[1]; ?></td>
            <td><?php echo $di[2]; ?></td>
            <td><?php echo $di[4]; ?></td>
            <td><?php echo $di[5]; ?></td>
            <td><?php echo $di[10]; ?></td>
            <td><?php echo $di[12]; ?></td>
			<td><?php echo $di[14]; ?></td>
			<td><?php echo $di[15]; ?></td>
			<td><img src="../user_images/<?php echo $di[3]; ?>" class="img-rounded" width="50px" height="50px" </td>
			<td align="center" class="lasttd"><a href="JavaScript:newPopup('cata_details.php?edit_id=<?php echo $di[0]; ?>')">Details</a></td>
			</tr>
            <?php
		}
			}
		}else if($cbo=="Car Maker")
		{
			$na = mysqli_query($conn,"SELECT * FROM catalogue WHERE CarMaker like '%".$search."%'");
			$count=mysqli_num_rows($na);
			if ($count<=0){ writeMsg();} else{
				tableheader ();
		while($an=mysqli_fetch_array($na))
		{
	?>
			<tr>
            <td><?php echo $an[1]; ?></td>
            <td><?php echo $an[2]; ?></td>
            <td><?php echo $an[4]; ?></td>
            <td><?php echo $an[5]; ?></td>
            <td><?php echo $an[10]; ?></td>
            <td><?php echo $an[12]; ?></td>
			<td><?php echo $an[14]; ?></td>
			<td><?php echo $an[15]; ?></td>
			<td><img src="../user_images/<?php echo $an[3]; ?>" class="img-rounded" width="50px" height="50px" </td>
			<td align="center" class="lasttd"><a href="JavaScript:newPopup('cata_details.php?edit_id=<?php echo $an[0]; ?>')">Details</a></td>
			</tr>
     <?php
				}
		 
			}
		}else if($cbo=="Model Name")
				{
        $add = mysqli_query($conn,"SELECT * FROM catalogue WHERE ModelName like '%".$search."%'");
			$count=mysqli_num_rows($add);
			if ($count<=0){ writeMsg();} else{
				tableheader ();
		while($dda=mysqli_fetch_array($add))
		{
	?>
			<tr>
            <td><?php echo $dda[1]; ?></td>
            <td><?php echo $dda[2]; ?></td>
            <td><?php echo $dda[4]; ?></td>
            <td><?php echo $dda[5]; ?></td>
            <td><?php echo $dda[10]; ?></td>
            <td><?php echo $dda[12]; ?></td>
			<td><?php echo $dda[14]; ?></td>
			<td><?php echo $dda[15]; ?></td>
			<td><img src="../user_images/<?php echo $dda[3]; ?>" class="img-rounded" width="50px" height="50px" </td>
			<td align="center" class="lasttd"><a href="JavaScript:newPopup('cata_details.php?edit_id=<?php echo $dda[0]; ?>')">Details</a></td>
			</tr>
            <?php
				}
			}
			}else if($cbo=="Model Code")
			{
			$g = mysqli_query($conn,"SELECT * FROM catalogue WHERE ModelCode like '%".$search."%'");
			 $count=mysqli_num_rows($g);
			if ($count<=0){ writeMsg();} else{
				tableheader ();
				while($ge=mysqli_fetch_array($g))
				{			
			?>
            <tr>
            <td><?php echo $ge[1]; ?></td>
            <td><?php echo $ge[2]; ?></td>
            <td><?php echo $ge[4]; ?></td>
            <td><?php echo $ge[5]; ?></td>
            <td><?php echo $ge[10]; ?></td>
            <td><?php echo $ge[12]; ?></td>
			<td><?php echo $ge[14]; ?></td>
			<td><?php echo $ge[15]; ?></td>
			<td><img src="../user_images/<?php echo $ge[3]; ?>" class="img-rounded" width="50px" height="50px" </td>
				<td align="center" class="lasttd"><a href="JavaScript:newPopup('cata_details.php?edit_id=<?php echo $ge[0]; ?>')">Details</a></td>
			</tr>
            
            <?php
				}
			}
			}else if($cbo=="Customer Part Number")
			{
			$gq = mysqli_query($conn,"SELECT * FROM catalogue WHERE Cprtn like '%".$search."%'");
				$count=mysqli_num_rows($gq);
				if ($count<=0){ writeMsg();} else{
					tableheader ();
				while($cptn=mysqli_fetch_array($gq))
				{			
			?>
            <tr>
            <td><?php echo $cptn[1]; ?></td>
            <td><?php echo $cptn[2]; ?></td>
            <td><?php echo $cptn[4]; ?></td>
            <td><?php echo $cptn[5]; ?></td>
            <td><?php echo $cptn[10]; ?></td>
            <td><?php echo $cptn[12]; ?></td>
			<td><?php echo $cptn[14]; ?></td>
			<td><?php echo $cptn[15]; ?></td>
			<td><img src="../user_images/<?php echo $cptn[3]; ?>" class="img-rounded" width="50px" height="50px" </td>
				<td align="center" class="lasttd"><a href="JavaScript:newPopup('cata_details.php?edit_id=<?php echo $cptn[0]; ?>')">Details</a></td>
			</tr>
            
            <?php
				}
				}
			}else if($cbo=="CG Part Number")
			{
			$gw = mysqli_query($conn,"SELECT * FROM catalogue WHERE Cgprtno like '%".$search."%'");
		 		$count=mysqli_num_rows($gw);
				if ($count<=0){ writeMsg();} else{
				tableheader ();
				while($cgp=mysqli_fetch_array($gw))
				{			
			?>
            <tr>
            <td><?php echo $cgp[1]; ?></td>
            <td><?php echo $cgp[2]; ?></td>
            <td><?php echo $cgp[4]; ?></td>
            <td><?php echo $cgp[5]; ?></td>
            <td><?php echo $cgp[10]; ?></td>
            <td><?php echo $cgp[12]; ?></td>
			<td><?php echo $cgp[14]; ?></td>
			<td><?php echo $cgp[15]; ?></td>
			<td><img src="../user_images/<?php echo $cgp[3]; ?>" class="img-rounded" width="50px" height="50px" </td>
				<td align="center" class="lasttd"><a href="JavaScript:newPopup('cata_details.php?edit_id=<?php echo $cgp[0]; ?>')">Details</a></td>
			</tr>
            
            <?php
				}
				}
			}else if($cbo=="Part Name")
			{
			$gr = mysqli_query($conn,"SELECT * FROM catalogue WHERE Prtnm like '%".$search."%'");
			  	$count=mysqli_num_rows($gr);
				if ($count<=0){ writeMsg();} else{
				tableheader ();
				while($pnm=mysqli_fetch_array($gr))
				{			
			?>
            <tr>
            <td><?php echo $pnm[1]; ?></td>
            <td><?php echo $pnm[2]; ?></td>
            <td><?php echo $pnm[4]; ?></td>
            <td><?php echo $pnm[5]; ?></td>
            <td><?php echo $pnm[10]; ?></td>
            <td><?php echo $pnm[12]; ?></td>
			<td><?php echo $pnm[14]; ?></td>
			<td><?php echo $pnm[15]; ?></td>
			<td><img src="../user_images/<?php echo $pnm[3]; ?>" class="img-rounded" width="50px" height="50px" </td>
				<td align="center" class="lasttd"><a href="JavaScript:newPopup('cata_details.php?edit_id=<?php echo $pnm[0]; ?>')">Details</a></td>
			</tr>
            
            <?php
				}
				}
			}
		}	
?>

	</tbody>
</table>

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
