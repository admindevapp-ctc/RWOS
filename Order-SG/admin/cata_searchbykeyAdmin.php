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
header("Location:../../login.php");
}
?>


<?php

	require_once 'cata_dbconfig.php';
	require('../db/conn.inc');
	if(isset($_GET['delete_id']))
	{
		// select image from db to delete
		$stmt_select = $DB_con->prepare('SELECT PrtPic FROM catalogue WHERE ID =:uid');
		$stmt_select->execute(array(':uid'=>$_GET['delete_id']));
		$imgRow=$stmt_select->fetch(PDO::FETCH_ASSOC);
		unlink("../user_images/".$imgRow['PrtPic']);
		
		// it will delete an actual record from db
		$stmt_delete = $DB_con->prepare('DELETE FROM catalogue WHERE ID =:uid');
		$stmt_delete->bindParam(':uid',$_GET['delete_id']);
		$stmt_delete->execute();
		
		header("Location: cata_partcatalogue.php");
	}

?>



<html>
	<head>
    <title>Denso Ordering System</title>
	<meta http-equiv="X-UA-Compatible" content="IE=9, IE=10, IE=11, IE=EDGE" /> 
   	<link rel="stylesheet" type="text/css" href="../css/dnia.css">
	<link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">


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

<script type="text/javascript" language="javascript" src="../lib/jquery-1.4.2.js"></script>
<script>  
function newPopup(url) {
	popupWindow = window.open(
		url,'popUpWindow','height=570,width=400,left=600,top=350,resizable=yes,scrollbars=yes,toolbar=no,menubar=no,location=no,directories=no,status=no')
}
</script>  


	</head>
	<body >
   		<div id="header">
        <img src="../images/denso.jpg" width="206" height="54" />
        </div>
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

	<div class="page-header" style="border:solid>
    	<h1 class="h2">all Parts. / <a class="btn btn-default" href="cata_addnew.php"> <span class="glyphicon glyphicon-plus"></span> &nbsp; Add New </a></h1> 
     
  <table width="97%" border="0" cellspacing="0" cellpadding="0">

  <tr class="arial11blackbold">
    <td width="5%">&nbsp;</td>
    <td width="1%">&nbsp;</td>
    <td width="5%">&nbsp;</td>
    <td width="1%">&nbsp;</td>
    <td width="60%">&nbsp;</td>
    <td width="10%">&nbsp;</td>
  </tr>
  <tr class="arial11blackbold">
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>Search</td>
    <td>:</td>
    <td align="left">
	<form method="post" action="cata_searchbykeyAdmin.php" id="orderdatefrom">
    <select name="cbosearch">
	    <option disabled="disabled" selected="selected">Search by..</option>
    	<option>DENSO Part Number</option>
    	<option>Car Maker</option>
        <option>Model Name</option>
        <option>Model Code</option>
		<option>Customer Part Number</option>
		<option>CG Part Number</option>
		<option>Part Name</option>
    </select>
	<input type="text" name="txtsearch" placeholder="Type to Search" size="30"/>	<input type="submit" name="cmdsearch" value="Search" class="arial11"/>
	</td>
	</form>

    <td>&nbsp;</td>
  </tr>
  <tr class="arial11blackbold">
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td></td>
    <td>OR</td>
   
    <td>&nbsp;</td>
  </tr>
  <tr class="arial11blackbold">
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td >&nbsp;</td>
	<td></td>
    <td align="left"><?php include("cata_dd3.php"); ?></td>
   
    <td>&nbsp;</td>
    </tr>

 </table> 
	
	</div>

	
	
<table cellpadding="0" cellspacing="0" border="0" class="tbl1" id="example" width="97%">
<tr align="center" height="30">

		<?php
		
			function writeMsg() { 
			?>
   			<div class="col-xs-12">
        	<div class="alert alert-warning" align ="center">
            <span class="glyphicon glyphicon-info-sign"></span> &nbsp; No Data Found....Please Try Again !
            </div>
			</div>
		<?
			}

		$cbo = $_POST['cbosearch'];
		$text = $_POST['txtsearch'];
		if($text=="" || $cbo==""){ writeMsg();
			//echo "<td align=\"center\" class=\"lasttd\">No Data Found....Please Try Again!!! </td>";
		}
		else {
		?>
		</tr>
</table>
	<?php
	function tableheader() { 
	?>
 <table cellpadding="0" cellspacing="0" border="1" class="tbl1" id="example" width="97%">
<thead>
		
		<tr align="center" height="20" class="arial11grey" bgcolor="#AD1D36">
			<th width="10%">Car Maker</th>
            <th width="10%">Model Name</th>
            <th width="10%">Model Code</th>
			<th width="10%">Engine Code</th>
			<th width="10%">Customer P/NO</th>
			<th width="10%">DENSO P/NO</th>
			<th width="10%">CG P/NO</th>
			<th width="10%">Part Name</th>
			<th width="5%" >Picture</th>
            <th width="5%" >Details</th>
		    <th width="7%" >Action</th>
			
		</tr>
	</thead>
	<tbody>
	<? }  ?>

	
<?php
		$cbo = $_POST['cbosearch'];
		$search = $_POST['txtsearch'];
		include('cata_connect.php');
	

		if($cbo=="DENSO Part Number")
		{
		
			$id = mysqli_query($msqlcon,"SELECT * FROM catalogue WHERE Prtno like '%".$search."%'");
			$count=mysqli_num_rows($id);
			if ($count<=0){ writeMsg();} else{
				tableheader();
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
			<td><img src="../user_images/<?php echo $di[3]; ?>" class="img-rounded" width="40px" height="40px" </td>
			<td align="center" class="lasttd"><a href="JavaScript:newPopup('cata_details.php?edit_id=<?php echo $di[0]; ?>')">Details</a></td>
			<td class="lasttd">
			<a  href="cata_editform.php?edit_id=<?php echo $di[0]; ?>" title="click for edit" onclick="return confirm('Confirm to edit ?')">Edit/</a>
			<a  href="?delete_id=<?php echo $di[0]; ?>" title="click for delete" onclick="return confirm('Confirm to delete ?')">Delete</a>
			</td>
			</tr>
            <?php
		}
			}	
		}else if($cbo=="Car Maker")
		{
			$na = mysqli_query($msqlcon,"SELECT * FROM catalogue WHERE CarMaker like '%".$search."%'");
			$count=mysqli_num_rows($na);
			if ($count<=0){ writeMsg();} else{
			tableheader();
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
			<td><img src="../user_images/<?php echo $an[3]; ?>" class="img-rounded" width="40px" height="40px" </td>
			<td align="center" class="lasttd"><a href="JavaScript:newPopup('cata_details.php?edit_id=<?php echo $an[0]; ?>')">Details</a></td>
			<td class="lasttd">
			<a  href="cata_editform.php?edit_id=<?php echo $an[0]; ?>" title="click for edit" onclick="return confirm('Confirm to edit ?')">Edit/</a>
			<a  href="?delete_id=<?php echo $an[0]; ?>" title="click for delete" onclick="return confirm('Confirm to delete ?')">Delete</a>
			</td>
			</tr>
     <?php
				}
			}
		}else if($cbo=="Model Name")
				{
        $add = mysqli_query($msqlcon,"SELECT * FROM catalogue WHERE ModelName like '%".$search."%'");
			$count=mysqli_num_rows($add);
			if ($count<=0){ writeMsg();} else{
			tableheader();
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
			<td><img src="../user_images/<?php echo $dda[3]; ?>" class="img-rounded" width="40px" height="40px" </td>
			<td align="center" class="lasttd"><a href="JavaScript:newPopup('cata_details.php?edit_id=<?php echo $dda[0]; ?>')">Details</a></td>
			<td class="lasttd">
			<a  href="cata_editform.php?edit_id=<?php echo $dda[0]; ?>" title="click for edit" onclick="return confirm('Confirm to edit ?')">Edit/</a>
			<a  href="?delete_id=<?php echo $dda[0]; ?>" title="click for delete" onclick="return confirm('Confirm to delete ?')">Delete</a>
			</td>
			</tr>
            <?php
				}
			}
			}else if($cbo=="Model Code")
			{
			$g = mysqli_query($msqlcon,"SELECT * FROM catalogue WHERE ModelCode like '%".$search."%'");
			$count=mysqli_num_rows($g);
			if ($count<=0){ writeMsg();} else{
				tableheader();
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
			<td><img src="../user_images/<?php echo $ge[3]; ?>" class="img-rounded" width="40px" height="40px" </td>
				<td align="center" class="lasttd"><a href="JavaScript:newPopup('cata_details.php?edit_id=<?php echo $ge[0]; ?>')">Details</a></td>
				<td class="lasttd">
				<a  href="cata_editform.php?edit_id=<?php echo $ge[0]; ?>" title="click for edit" onclick="return confirm('Confirm to edit ?')">Edit/</a>
			<a  href="?delete_id=<?php echo $ge[0]; ?>" title="click for delete" onclick="return confirm('Confirm to delete ?')">Delete</a>
			</td>
			</tr>
            
            <?php
				}
			}
			}else if($cbo=="Customer Part Number")
			{
			$gx = mysqli_query($msqlcon,"SELECT * FROM catalogue WHERE Cprtn like '%".$search."%'");
		  	$count=mysqli_num_rows($gx);
			if ($count<=0){ writeMsg();} else{
				tableheader();
				while($cptn=mysqli_fetch_array($gx))
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
			<td><img src="../user_images/<?php echo $cptn[3]; ?>" class="img-rounded" width="40px" height="40px" </td>
				<td align="center" class="lasttd"><a href="JavaScript:newPopup('cata_details.php?edit_id=<?php echo $cptn[0]; ?>')">Details</a></td>
				<td class="lasttd">
				<a  href="cata_editform.php?edit_id=<?php echo $cptn[0]; ?>" title="click for edit" onclick="return confirm('Confirm to edit ?')">Edit/</a>
			<a  href="?delete_id=<?php echo $cptn[0]; ?>" title="click for delete" onclick="return confirm('Confirm to delete ?')">Delete</a>
			</td>
			</tr>
            
            <?php
				}
			}
			}else if($cbo=="CG Part Number")
			{
			$gm = mysqli_query($msqlcon,"SELECT * FROM catalogue WHERE Cgprtno like '%".$search."%'");
		 	$count=mysqli_num_rows($gm);
			if ($count<=0){ writeMsg();} else{
				tableheader();
				while($cgp=mysqli_fetch_array($gm))
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
			<td><img src="../user_images/<?php echo $cgp[3]; ?>" class="img-rounded" width="40px" height="40px" </td>
				<td align="center" class="lasttd"><a href="JavaScript:newPopup('cata_details.php?edit_id=<?php echo $cgp[0]; ?>')">Details</a></td>
				<td class="lasttd">
				<a  href="cata_editform.php?edit_id=<?php echo $cgp[0]; ?>" title="click for edit" onclick="return confirm('Confirm to edit ?')">Edit/</a>
			<a  href="?delete_id=<?php echo $cgp[0]; ?>" title="click for delete" onclick="return confirm('Confirm to delete ?')">Delete</a>
			</td>
			</tr>
            
            <?php
				}
			}
			}else if($cbo=="Part Name")
			{
			$gt = mysqli_query($msqlcon,"SELECT * FROM catalogue WHERE Prtnm like '%".$search."%'");
			 $count=mysqli_num_rows($gt);
			if ($count<=0){ writeMsg();} else{		  
				tableheader();
				while($pnm=mysqli_fetch_array($gt))
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
			<td><img src="../user_images/<?php echo $pnm[3]; ?>" class="img-rounded" width="40px" height="40px" </td>
				<td align="center" class="lasttd"><a href="JavaScript:newPopup('cata_details.php?edit_id=<?php echo $pnm[0]; ?>')">Details</a></td>
				<td class="lasttd">
				<a  href="cata_editform.php?edit_id=<?php echo $pnm[0]; ?>" title="click for edit" onclick="return confirm('Confirm to edit ?')">Edit/</a>
			<a  href="?delete_id=<?php echo $pnm[0]; ?>" title="click for delete" onclick="return confirm('Confirm to delete ?')">Delete</a>
			</td>
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
</div>	
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
