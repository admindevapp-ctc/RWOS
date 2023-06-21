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

	//error_reporting( ~E_NOTICE );
	
	require_once 'cata_dbconfig.php';
	
	if(isset($_GET['edit_id']) && !empty($_GET['edit_id']))
	{
		$id = $_GET['edit_id'];
				
		//$stmt_edit = $DB_con->prepare('SELECT userName, userProfession, userPic FROM catalogue WHERE userID =:uid');
		$stmt_edit = $DB_con->prepare('SELECT title,detail,start,end,updateby,PrtPic FROM announce WHERE ID =:uid AND Owner_Comp=:comp');
		$stmt_edit->execute(array(':uid'=>$id,':comp'=>$comp));
		$edit_row = $stmt_edit->fetch(PDO::FETCH_ASSOC);
		
		extract($edit_row);	

	}
	else
	{
		header("Location: anna_mainadm.php");
	}
	
	
	
	if(isset($_POST['btn_save_updates']))
	{

		$title1 = $_POST['title'];
		$detail1 = $_POST['detail'];
		$start1 = $_POST['start'];
		$end1 = $_POST['end'];
		$updateby1 = $_POST['updateby'];
                   
			
		$imgFile = $_FILES['user_image']['name'];
		$tmp_dir = $_FILES['user_image']['tmp_name'];
		$imgSize = $_FILES['user_image']['size'];
					
		if($imgFile)
		{
			$upload_dir = '../anna_images/'; // upload directory	
			$imgExt = strtolower(pathinfo($imgFile,PATHINFO_EXTENSION)); // get image extension
			$valid_extensions = array('jpeg', 'jpg', 'png', 'gif'); // valid extensions
			$userpic = rand(1000,1000000).".".$imgExt;
			if(in_array($imgExt, $valid_extensions))
			{			
				if($imgSize < 5000000)
				{
					unlink($upload_dir.$edit_row['PrtPic']);
					move_uploaded_file($tmp_dir,$upload_dir.$userpic);
				}
				else
				{
					$errMSG = "Sorry, your file is too large it should be less then 5MB";
				}
			}
			else
			{
				$errMSG = "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";		
			}	
		}
		else
		{
			// if no image selected the old image remain as it is.
			$userpic = $edit_row['PrtPic']; // old image from database
		}	
					
		
		// if no error occured, continue ....
		if(!isset($errMSG))
			
		{
			/* $stmt = $DB_con->prepare('UPDATE catalogue 
									     SET userName=:uname, 
										     userProfession=:ujob, 
										     userPic=:upic 
								       WHERE userID=:uid');
			$stmt->bindParam(':uname',$username);
			$stmt->bindParam(':ujob',$userjob);
			$stmt->bindParam(':upic',$userpic);
			$stmt->bindParam(':uid',$id);  */
						$stmt = $DB_con->prepare('UPDATE announce
						SET title=:utitle,
						detail=:udetail,
						start=:ustart,
						end=:uend,
						updateby=:uupdateby,
						PrtPic=:upic
						WHERE ID=:uid AND Owner_Comp=:comp');
			$stmt->bindParam(':utitle',$title1);
			$stmt->bindParam(':udetail',$detail1);
			$stmt->bindParam(':ustart',$start1);
			$stmt->bindParam(':uend',$end1);
			$stmt->bindParam(':uupdateby',$updateby1);	
			$stmt->bindParam(':upic',$userpic);
		    $stmt->bindParam(':uid',$id);
		    $stmt->bindParam(':comp',$comp);

   //echo "<script>alert('$title');</script>";

			if($stmt->execute()){
				
				?>
                <script>
				alert('Successfully Updated ...');
				window.location.href='anna_mainadm.php';
				</script>
                <?php
			}
			else{
				$errMSG = "Sorry Data Could Not Updated !";
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
	</style><!--[if IE]>
<style type="text/css"> 
#twocolLeft{ padding-top: 0px; }
#twocolRight { zoom: 1; padding-top:10px; }
</style>	
<![endif]-->
 <style type="text/css">

 </style>


	</head>
	<body>

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
			  	$_GET['current']="mainCusAdm";
				include("navAdm.php");
			  ?>
        </div>
        <div id="twocolRight">

	<div class="page-header">
    	<a class="btn btn-default" href="anna_mainadm.php"> View all </a>
    </div>

<form method="post" enctype="multipart/form-data" class="form-horizontal">
	
    
    <?php
	if(isset($errMSG)){
		?>
        <div class="alert alert-danger">
          <span class="glyphicon glyphicon-info-sign"></span> &nbsp; <?php echo $errMSG; ?>
        </div>
        <?php
	}
	?>


	<!-- <table class="table table-bordered table-responsive"> -->
	<table width="80%" border="0" cellspacing="4" cellpadding="0">
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
        <td><input type="text" class="arial11blackbold" style="width: 300px"  name="title" placeholder="Enter Title" maxlength="200" value="<?php echo $title;?>" /></td>
    </tr>
    <tr class="arial11blackbold">
    	<td>&nbsp;</td>
        <td>&nbsp;</td>
    </tr>
    <tr class="arial11blackbold">
    	<td><label class="control-label">Detail *</label></td>
        <td><textarea  type="text" class="arial11blackbold" style="width: 300px" rows="15" cols="40" name="detail" placeholder="Enter Detail"  maxlength="500" value="" /><?php echo $detail; ?></textarea></td>
    </tr>
    <tr class="arial11blackbold">
    	<td>&nbsp;</td>
        <td>&nbsp;</td>
    </tr>
	<tr class="arial11blackbold">
    	<td><label class="control-label">Effective Date From *</label></td>
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
        <td><input class="arial11blackbold" style="width: 250px" type="text" name="updateby" placeholder="Enter Update By" maxlength="100" value="<?php echo $updateby; ?>" /></td>
    </tr>
			    
	

	 <tr class="arial11blackbold">
    	<td><label class="control-label">Picture (Size: 855x153 pixel)*</label></td>
        <td>
        	<p><img src="../anna_images/<?php echo $PrtPic; ?>" height="150" width="150" /></p>
        	<input class="input-group" type="file" name="user_image" accept="image/*" />
        </td>
    </tr>
	
	<tr class="arial11blackbold">
    	<td>&nbsp;</td>
        <td>&nbsp;</td>
    </tr>
    
    <tr>
		<td>&nbsp;</td>
        <td colspan="2"><button type="submit" name="btn_save_updates" class="arial11blackbold">&nbsp;Update&nbsp;</button></td>
  
    </tr>
	
		<tr class="arial11blackbold">
    	<td>&nbsp;</td>
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
