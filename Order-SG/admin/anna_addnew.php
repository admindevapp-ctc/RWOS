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


<?php


	//error_reporting( ~E_NOTICE ); // avoid notice
	
	require_once 'cata_dbconfig.php';
	
	if(isset($_POST['btnsave']))
	{
		$title = $_POST['title'];
		$detail = $_POST['detail'];
		$start = $_POST['start'];
		$end = $_POST['end'];
		$updateby = $_POST['updateby'];
		
		$imgFile = $_FILES['user_image']['name'];
		$tmp_dir = $_FILES['user_image']['tmp_name'];
		$imgSize = $_FILES['user_image']['size'];
		
		
		if(empty($title)){
			$errMSG = "Please Enter Title";
		}
		else if(empty($detail)){
			$errMSG = "Please Enter Detail";
		}
		else if(empty($start)){
			$errMSG = "Please Enter Start Date ";
		}
		
		else if(empty($end)){
			$errMSG = "Please Enter End Date";
		}

		else if(empty($imgFile)){
			$errMSG = "Please Select Image File";
		} 
		else
		{
			$upload_dir = '../anna_images/'; // upload directory
	
			$imgExt = strtolower(pathinfo($imgFile,PATHINFO_EXTENSION)); // get image extension
		//echo imgExt;
			// valid image extensions
			$valid_extensions = array('jpeg', 'jpg', 'png', 'gif'); // valid extensions
		
			// rename uploading image
			$userpic = rand(1000,1000000).".".$imgExt;
				
			// allow valid image file formats
			if(in_array($imgExt, $valid_extensions)){			
				// Check file size '5MB'
				if($imgSize < 5000000)				{
					move_uploaded_file($tmp_dir,$upload_dir.$userpic);
				}
				else{
					$errMSG = "Sorry, your file is too large.";
				}
			}
			else{
				$errMSG = "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";		
			}
		}
		
		
		// if no error occured, continue ....
		if(!isset($errMSG))
		{
			$stmt = $DB_con->prepare('INSERT INTO announce(title,detail,start,end,updateby,PrtPic,Owner_Comp) VALUES(:utitle, :udetail, :ustart, :uend, :uwpdateby, :upic,:comp)');
			$stmt->bindParam(':utitle',$title);
			$stmt->bindParam(':udetail',$detail);
			$stmt->bindParam(':ustart',$start);
			$stmt->bindParam(':uend',$end);
			$stmt->bindParam(':uwpdateby',$updateby);
			$stmt->bindParam(':upic',$userpic);
			$stmt->bindParam(':comp',$comp);
			
			if($stmt->execute())
			{
				$successMSG = "new record succesfully inserted ...";
				header("refresh:2;anna_mainadm.php"); // redirects image view page after 2 seconds.
			}
			else
			{
				$errMSG = "error while inserting....";
			}
		}
	}
?>




<html>
	<head>
    <title>Denso Ordering System</title>
	<meta http-equiv="X-UA-Compatible" content="IE=9, IE=10, IE=11, IE=EDGE" /> 
   	<link rel="stylesheet" type="text/css" href="../css/dnia.css">
	<link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">

<!-- Optional theme -->
<link rel="stylesheet" href="bootstrap/css/bootstrap-theme.min.css">
	
	</style><!--[if IE]>
	<style type="text/css"> 
	#twocolLeft{ padding-top: 0px; }
	#twocolRight { zoom: 1; padding-top:10px; }
	</style>	
	<![endif]-->
	<style type="text/css">

 </style>

 <script>
 
 </script>



	</head>
	<body>

   		<?php ctc_get_logo_new() ?>

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
  
         
		 
	<div class="page-header">
    	<a class="btn btn-default" href="anna_mainadm.php"> <span class="glyphicon glyphicon-eye-open"></span> &nbsp; view all </a>
    </div>
    
		 	<?php
	if(isset($errMSG)){
			?>
            <div class="alert alert-danger">
            	<span class="glyphicon glyphicon-info-sign"></span> <strong><?php echo $errMSG; ?></strong>
            </div>
            <?php
	}
	else if(isset($successMSG)){
		?>
        <div class="alert alert-success">
              <strong><span class="glyphicon glyphicon-info-sign"></span> <?php echo $successMSG; ?></strong>
        </div>
        <?php
	}
	?> 
		 
		 
<form method="post" enctype="multipart/form-data" class="form-horizontal">

<!--<table class="table table-bordered table-responsive"> -->
<table width="80%" border="0" cellspacing="5" cellpadding="0">
	<tr class="arial11blackbold">
    	<td width="20%">&nbsp;</td>
        <td width="60%">&nbsp;</td>
    </tr>
    <tr class="arial11blackbold">
    <td class="arial21redbold"><img src="../images/calendar.gif" width="16" height="15">&nbsp; Announcement Maintenance</td>
    <td >&nbsp;</td>
    </tr>
	<tr class="arial11blackbold">
    	<td>&nbsp;</td>
        <td>&nbsp;</td>
    </tr>
	
    <tr class="arial11blackbold">
    	<td><label class="control-label">Title *</label></td>
        <td><input class="arial11blackbold" style="width: 300px" type="text" name="title" placeholder="Please type Title" maxlength="200" value="<?php echo $title; ?>" /></td>
    </tr>
    <tr class="arial11blackbold">
    	<td>&nbsp;</td>
        <td>&nbsp;</td>
    </tr>
    <tr class="arial11blackbold">
    	<td><label class="control-label">Detail *</label></td>
        <td><textarea class="arial11blackbold" style="width: 300px" rows="15" cols="40" type="text" name="detail" placeholder="Please type detail" maxlength="500" value="<?php echo $detail; ?>" /></textarea></td>
    </tr>
    <tr class="arial11blackbold">
    	<td>&nbsp;</td>
        <td>&nbsp;</td>
    </tr>
	<tr class="arial11blackbold">
    	<td><label class="control-label">Effective Date From  *</label></td>
        <td><input class="arial11blackbold" style="width: 150px" type="date" name="start" placeholder="yyyy/mm/dd" maxlength="10" value="<?php echo $start; ?>" /></td>
    </tr>
    <tr class="arial11blackbold">
    	<td>&nbsp;</td>
        <td>&nbsp;</td>
    </tr>
	<tr class="arial11blackbold">
    	<td><label class="control-label">Effective Date To *</label></td>
        <td><input class="arial11blackbold" style="width: 150px" type="date" name="end" placeholder="yyyy/mm/dd" maxlength="10" value="<?php echo $end; ?>" /></td>
    </tr>
    <tr class="arial11blackbold">
    	<td>&nbsp;</td>
        <td>&nbsp;</td>
    </tr>
	<tr class="arial11blackbold">
    	<td><label class="control-label">Update By *</label></td>
        <td><input class="arial11blackbold" style="width: 250px" type="text" name="updateby" placeholder="Enter Name" maxlength="30" value="<?php echo $updateby; ?>" /></td>
    </tr>
	
	<tr class="arial11blackbold">
    	<td>&nbsp;</td>
        <td>&nbsp;</td>
    </tr>
	<!--
	<tr class="arial11blackbold">
    	<td><label class="control-label">Genuine P/NO (Current) </label></td>
        <td><input class="arial11blackbold" style="width: 250px" type="text" name="custprtnumber" placeholder="Enter Customer P/NO" maxlength="30" value="<?php echo $custprt; ?>" /></td>
    </tr>
	<tr class="arial11blackbold">
    	<td><label class="control-label">DENSO P/NO (Old)</label></td>
        <td><input class="arial11blackbold" style="width: 250px" type="text" name="dnprthistory" placeholder="Enter History  DENSO P/NO" maxlength="30" value="<?php echo $prtnh; ?>" /></td>
    </tr>
	<tr class="arial11blackbold">
    	<td><label class="control-label">DENSO P/NO (Current) </label></td>
        <td><input class="arial11blackbold" style="width: 250px" type="text" name="dnprtnumber" placeholder="Enter DENSO P/NO" maxlength="30" value="<?php echo $dnprt; ?>" /></td>
    </tr>
	<tr class="arial11blackbold">
    	<td><label class="control-label">CG P/NO (Old)</label></td>
        <td><input class="arial11blackbold" style="width: 250px" type="text" name="cgprtnumberh" placeholder="Enter History  CG P/NO" maxlength="30" value="<?php echo $cgprtnoh; ?>" /></td>
    </tr>
		<tr class="arial11blackbold">
    	<td><label class="control-label">CG P/NO (Current) </label></td>
        <td><input class="arial11blackbold" style="width: 250px" type="text" name="cgprtnumber" placeholder="Enter CG P/NO" maxlength="30" value="<?php echo $cgprtno; ?>" /></td>
    </tr>
	<tr class="arial11blackbold">
    	<td><label class="control-label">Part Name *</label></td>
        <td><input class="arial11blackbold" style="width: 250px" type="text" name="partname" placeholder="Enter Part Name" maxlength="60" value="<?php echo $prtnam; ?>" /></td>
    </tr>
	<tr class="arial11blackbold">
    	<td><label class="control-label">Remark</label></td>
        <td><input class="arial11blackbold" style="width: 400px" type="text" name="remark" placeholder="Enter Remark" maxlength="200" value="<?php echo $rmk; ?>" /></td>
    </tr>
-->
    <tr class="arial11blackbold">
    	<td><label class="control-label">Picture(Size: 855x153 pixel)* </label></td>
        <td><input class="input-group" type="file" name="user_image" accept="image/*" /></td>
    </tr>

	    <tr class="arial11blackbold">
    	<td>&nbsp;</td>
        <td>&nbsp;</td>
    </tr>
    <tr class="arial11blackbold">
	    <td><label class="control-label">&nbsp;</label></td>
        <td colspan="2"><button type="submit" name="btnsave" class="arial11blackbold">&nbsp; Save&nbsp;</button></td>
    </tr>
	<tr class="arial11blackbold">
    	<td><font color="red">* = required </font></td>
        <td>&nbsp;</td>
    </tr>
    
    </table>
    
</form>
		
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
	</body>
</html>
