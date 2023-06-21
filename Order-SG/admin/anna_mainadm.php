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
	header("Location:../../login.php");
}
?>


<?php

	require_once 'cata_dbconfig.php';
	require('../db/conn.inc');
	if(isset($_GET['delete_id']))
	{
		// select image from db to delete
		$stmt_select = $DB_con->prepare('SELECT PrtPic FROM announce WHERE ID =:uid AND Owner_Comp=:comp');
		$stmt_select->execute(array(':uid'=>$_GET['delete_id'],':comp'=>"$comp"));
		$imgRow=$stmt_select->fetch(PDO::FETCH_ASSOC);
		unlink("../anna_images/".$imgRow['PrtPic']);
		
		// it will delete an actual record from db
		$stmt_delete = $DB_con->prepare('DELETE FROM announce WHERE ID =:uid AND Owner_Comp=:comp');
		$stmt_delete->bindParam(':uid',$_GET['delete_id']);
		$stmt_delete->bindParam(':comp',$comp);
		$stmt_delete->execute();
		
		header("Location: anna_mainadm.php");
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
		url,'popUpWindow','height=500,width=500,left=600,top=350,resizable=yes,scrollbars=yes,toolbar=no,menubar=no,location=no,directories=no,status=no')
}

</script>  


	</head>
	<body >

		<?php ctc_get_logo_new(); ?> <!-- add by CTC -->

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
			  	$_GET['current']="announcement";
				include("navAdm.php");
			  ?>
        </div>
        <div id="twocolRight">

	<div class="page-header" style="border:solid>
    	<h1 class="h2"><a class="btn btn-default" href="anna_addnew.php"> <span class="glyphicon glyphicon-plus"></span> &nbsp; Add New </a></h1> 

   <!-- 
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
    <td align="center">Search</td>
    <td>:</td>
    <td align="left">
	<form method="post" action="cata_searchbykeyAdmin.php" id="orderdatefrom">
		<select name="cbosearch">
	    <option disabled="disabled" selected="selected">Search by..</option>
    	<option>DENSO Part Number</option>
    	<option>Car Maker</option>
        <option>Model Name</option>
        <option>Model Code</option>
		<option>Genuine Part Number</option>
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
   
    <td><a href="#" id="ConvExcel"><img src="../images/export.png" width="85" height="25" border="0"></a></td>
    </tr>

 </table> -->
</div>

<marquee direction="left" style="font-size:9pt;color:red;"  scrolldelay="50" scrollamount="3" onmouseout="this.start()" onmouseover="this.stop()">
				<?php
					require 'conn_marquee.php';
					$sql = "SELECT * FROM `announce` WHERE `start`<=CURRENT_DATE and `end`>=CURRENT_DATE AND Owner_Comp='$comp' ORDER BY `ID` DESC";
					$result = mysqli_query($msqlcon,$sql);
				          if(mysqli_num_rows($result) > 0)  
                     {  
                          while($row = mysqli_fetch_array($result))  
                          {  
							   echo '<img src="../images/marquee.gif" width="20" height="20" />';
							   //echo '<a href="anna_details.php?edit_id='.$row[0].'"> '.$row['title'].'</a>'; 
                               //echo '<label><a href="JavaScript:newPopup('.$row['0'].')" target="_blank">'.$row['title'].'</a></label>';  
							    echo'<label><a href=JavaScript:newPopup("anna_details.php?edit_id='.$row['id'].'")>'.$row['title'].'</a></label>'; 
							 
                          }  
                     } 
				?>
</marquee>	

All Announcement :
<? function tableheader (){  ?>
 <table cellpadding="0" cellspacing="0" border="0" class="tbl1" id="example" width="97%">
<thead>
		
		<tr align="center" height="20" class="arial11grey" bgcolor="#AD1D36">
			<th width="10%">Company Code</th>
			<th width="10%">Title</th>
            <th width="35%">Detail</th>
            <th width="8%">Effective From</th>
			<th width="8%">Effective To</th>
			<th width="10%">Update by</th>
			<th width="5%">Picture</th>			
			<th width="5%">Detail</th>
		    <th width="10%">Action</th>
			
		</tr>
	</thead>
	<tbody>

<?php
}
	include_once "anna_function.php";
	//include('cata_connect.php');
	
	if(isset($_GET["page"]))
	$page = (int)$_GET["page"];
	else
	$page = 1;
	$setLimit = 10;
	$pageLimit = ($page * $setLimit) - $setLimit;
	
	$stmt = $DB_con->prepare('SELECT ID,title,detail,PrtPic,start,end,updateby,Owner_Comp FROM announce WHERE Owner_Comp=? ORDER BY ID DESC limit ?,?');  // edit by CTC
	$stmt->bindValue(1, $comp, PDO::PARAM_STR_CHAR);
	$stmt->bindValue(2, $pageLimit, PDO::PARAM_INT);
	$stmt->bindValue(3, $setLimit, PDO::PARAM_INT);
    $stmt->execute();
	
	if($stmt->rowCount() > 0)
	{
		tableheader ();
		while($row=$stmt->fetch(PDO::FETCH_ASSOC))
		{
			extract($row);
			
			echo "<tr class=\"arial11black\" align=\"Left\"height=\"10\"><td>".$Owner_Comp."</td><td>".$title."</td><td>".$detail."</td><td>".$start."</td><td>".$end."</td><td>".$updateby."</td>" ;
			?>
			<td><img src="../anna_images/<?php echo $row['PrtPic']; ?>" class="img-rounded" width="50px" height="50px" /></td>
			<td align="center" class="lasttd"><a href="JavaScript:newPopup('anna_details.php?edit_id=<?php echo $row['ID']; ?>')">Details</a></td>
			<?
			   echo "<td class=\"lasttd\">";?>
				<a  href="anna_editform.php?edit_id=<?php echo $row['ID']; ?>" title="click for edit" onclick="return confirm('Confirm to edit ?')">Edit/</a>
				<a  href="?delete_id=<?php echo $row['ID']; ?>" title="click for delete" onclick="return confirm('Confirm to delete ?')">Delete</a>
			<?
			echo "</td ></tr>";
			}
		  ?>
		  
		  
		<tr align="right" valign="middle" height="30" >
       	<td colspan="13" class="lastpg"><div id="pagination"><?php echo displayPaginationBelow($setLimit,$page);?></div></td>
        </tr>
		
		</tbody>
     </table>
	
			<?php	
	}
	else
	{
		?>
        <div class="col-xs-12">
        	<div class="alert alert-warning" align ="center">
            <span class="glyphicon glyphicon-info-sign"></span> &nbsp; No Data Found ......
            </div>
        </div>
        <?php
	}
	
?>

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
</div>	
</div>	
</div>


	</body>
</html>
