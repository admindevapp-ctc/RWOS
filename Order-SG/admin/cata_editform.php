<?php session_start() ?>
<?php require_once('./../../core/ctc_init.php'); ?> <!-- add by CTC -->
<?php
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

	//error_reporting( ~E_NOTICE );
	
	require_once 'cata_dbconfig.php';
	
	if(isset($_GET['edit_id']) && !empty($_GET['edit_id']))
	{
		$id = $_GET['edit_id'];
		//$stmt_edit = $DB_con->prepare('SELECT userName, userProfession, userPic FROM catalogue WHERE userID =:uid');
		$stmt_edit = $DB_con->prepare('SELECT CarMaker,ModelName,PrtPic,ModelCode,EngineCode,Cc,Start,End,Custprthis,Cprtn,Prtnohis,Prtno,cgprtnohis,Cgprtno,ordprtno,Prtnm,Remark,Vincode,Brand FROM catalogue WHERE ID =:uid'); //edit by ctc
		$stmt_edit->execute(array(':uid'=>$id));
		$edit_row = $stmt_edit->fetch(PDO::FETCH_ASSOC);
		extract($edit_row);
	}
	else
	{
		header("Location: cata_partcatalogue.php");
	}
	
	
	
	if(isset($_POST['btn_save_updates']))
	{

		$car_maker1 = $_POST['carmaker'];
		$model_name1 = $_POST['modelname'];
		$model_code1 = $_POST['modelcode'];
		$engine_code1 = $_POST['enginecode'];
		$cc1 = $_POST['ccw'];
		$start1 = $_POST['startdate'];
		$end1 = $_POST['enddate'];
		$custprth1 = "";
		$custprt1 = $_POST['custprtnumber'];
		$prtnh1 = "";
		$dnprt1 = $_POST['dnprtnumber'];
		$cgprtnoh1 = "";
		$cgprtno1 = $_POST['cgprtnumber'];
		$ordprtno1 = $_POST['ordprtnumber']; //edit by ctc
		$prtnam1 = $_POST['partname'];
		$rmk1 = $_POST['remark'];
		//Add new column 2021
		$vin_code = $_POST['vincode'];
		$product_brand = $_POST['productbrand'];
			
		$imgFile = $_FILES['user_image']['name'];
		$tmp_dir = $_FILES['user_image']['tmp_name'];
		$imgSize = $_FILES['user_image']['size'];
					
		if($imgFile)
		{
			$upload_dir = '../user_images/'; // upload directory	
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
						$stmt = $DB_con->prepare('UPDATE catalogue
						SET CarMaker=:ucar_maker,
						ModelName=:umodel_name,
						PrtPic=:upic,
						ModelCode=:umodel_code,
						EngineCode=:uengine_code,
						Cc=:ucc,
						Start=:ustart,
						End=:uend,
						Custprthis=:ucustprth,
						Cprtn=:ucustprt,
						Prtnohis=:uprtnh,
						Prtno=:udnprt,
						cgprtnohis=:ucgprtnoh,
						Cgprtno=:ucgprtno,
						ordprtno=:uordprtno,
						Prtnm=:uprtnam,
						Vincode=:uvincode,
						Brand=:uproductbrand,
						Remark=:urmk WHERE ID=:uid'); //edit by ctc
			$stmt->bindParam(':ucar_maker',$car_maker1);
			$stmt->bindParam(':umodel_name',$model_name1);
			$stmt->bindParam(':umodel_code',$model_code1);
			$stmt->bindParam(':uengine_code',$engine_code1);
			$stmt->bindParam(':ucc',$cc1);
			$stmt->bindParam(':ustart',$start1);
			$stmt->bindParam(':uend',$end1);
			$stmt->bindParam(':ucustprth',$custprth1);
			$stmt->bindParam(':ucustprt',$custprt1);
			$stmt->bindParam(':uprtnh',$prtnh1);
			$stmt->bindParam(':udnprt',$dnprt1);
			$stmt->bindParam(':ucgprtnoh',$cgprtnoh1);
			$stmt->bindParam(':ucgprtno',$cgprtno1);
			$stmt->bindParam(':uordprtno',$ordprtno1); //edit by ctc
			$stmt->bindParam(':uprtnam',$prtnam1);
			$stmt->bindParam(':urmk',$rmk1);
			$stmt->bindParam(':upic',$userpic);	
		    $stmt->bindParam(':uid',$id);
			//Add new column 2021
			$stmt->bindParam(':uproductbrand',$product_brand);
			$stmt->bindParam(':uvincode',$vin_code);
				
			if($stmt->execute()){
				?>
                <script>
				alert('Successfully Updated ...');
				window.location.href='cata_partcatalogue.php';
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
    	<a class="btn btn-default" href="cata_partcatalogue.php"> View all </a>
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
	<table width="80%" border="0" cellspacing="2" cellpadding="0">
    	<td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
    </tr>
    <tr class="arial11blackbold">
		<td colspan="2" class="arial21redbold"><img src="../images/calendar.gif" width="16" height="15">&nbsp; Part Catalogue Maintenance (Update)</td>
		<td >&nbsp;</td>
    </tr>
	<tr class="arial11blackbold">
    	<td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
    </tr>
	
	<tr class="arial11blackbold">
		<td rowspan="8">Car Information</td>
    	<td><label class="control-label">Car Maker</label></td>
        <td><input type="text" class="arial11blackbold" style="width: 150px"  name="carmaker" placeholder="Enter Car Maker" value="<?php echo $CarMaker; ?>" /></td>
    </tr>
    
    <tr class="arial11blackbold">
    	<td><label class="control-label">Model Name</label></td>
        <td><input  type="text" class="arial11blackbold" style="width: 250px" name="modelname" placeholder="Enter Model Name" value="<?php echo $ModelName; ?>" /></td>
    </tr>
    <tr class="arial11blackbold">
    	<td><label class="control-label">VIN Code</label></td>
        <td><input class="arial11blackbold" style="width: 250px" type="text" name="vincode" placeholder="Enter VIN Code" maxlength="30" value="<?php echo $Vincode; ?>" /></td>
    </tr>
	<tr class="arial11blackbold">
    	<td><label class="control-label">Model Code</label></td>
        <td><input type="text" class="arial11blackbold" style="width: 250px" name="modelcode" placeholder="Enter Model Code" value="<?php echo $ModelCode; ?>" /></td>
    </tr>
	<tr class="arial11blackbold">
    	<td><label class="control-label">Engine Code</label></td>
        <td><input type="text" class="arial11blackbold" style="width: 250px" name="enginecode" placeholder="Enter Engine Code" value="<?php echo $EngineCode; ?>" /></td>
    </tr>
	<tr class="arial11blackbold">
    	<td><label class="control-label">CC.</label></td>
        <td><input class="arial11blackbold" style="width: 250px" type="text" name="ccw" placeholder="Enter CC." value="<?php echo $Cc; ?>" /></td>
    </tr>
	<tr class="arial11blackbold">
    	<td><label class="control-label">Start Date (dd/mm/yyyy)</label></td>
        <td><input class="arial11blackbold" style="width: 150px" type="date" name="startdate" placeholder="Enter Start Date" value="<?php echo $Start; ?>" /></td>
    </tr>
	<tr class="arial11blackbold">
    	<td><label class="control-label">End Date (dd/mm/yyyy)</label></td>
        <td><input class="arial11blackbold" style="width: 150px" type="date" name="enddate" placeholder="Enter End Date" value="<?php echo $End; ?>" /></td>
    </tr>
	<!--<tr class="arial11blackbold">
    	<td><label class="control-label">History Customer P/NO</label></td>
        <td><input class="arial11blackbold" style="width: 250px" type="text" name="custprthistory" placeholder="Enter History Customer P/NO" value="<?php echo $Custprthis; ?>" /></td>
    </tr>-->
	<tr class="arial11blackbold">
    	<td colspan="3"><hr/></td>
    </tr>
	<tr class="arial11blackbold">
		<td rowspan="3">Genuine Part No. information</td>
    	<td><label class="control-label">Genuine Part No.</label></td>
        <td><input class="arial11blackbold" style="width: 250px" type="text" name="custprtnumber" placeholder="Enter Genuine Part No." value="<?php echo $Cprtn; ?>" /></td>
    </tr>
	<!--<tr class="arial11blackbold">
    	<td><label class="control-label">History  DENSO P/NO</label></td>
        <td><input class="arial11blackbold" style="width: 250px" type="text" name="dnprthistory" placeholder="Enter History  DENSO P/NO" value="<?php echo $Prtnohis; ?>" /></td>
    </tr>-->
	<tr class="arial11blackbold">
    	<td><label class="control-label">DENSO Part No.</label></td>
        <td><input class="arial11blackbold" style="width: 250px" type="text" name="dnprtnumber" placeholder="Enter DENSO Part No." value="<?php echo $Prtno; ?>" /></td>
    </tr>
	<!--<tr class="arial11blackbold">
    	<td><label class="control-label">History  CG P/NO</label></td>
        <td><input class="arial11blackbold" style="width: 250px" type="text" name="cgprtnumberh" placeholder="Enter History  CG P/NO" value="<?php echo $cgprtnohis; ?>" /></td>
    </tr>-->
	<tr class="arial11blackbold">
    	<td><label class="control-label">CG Part No.</label></td>
        <td><input class="arial11blackbold" style="width: 250px" type="text" name="cgprtnumber" placeholder="Enter CG Part No." value="<?php echo $Cgprtno; ?>" /></td>
    </tr>
	<tr class="arial11blackbold">
    	<td colspan="3"><hr/></td>
    </tr>
	<tr class="arial11blackbold"> <!-- edit by ctc -->
		<td rowspan="5">Order Part No. information</td>
    	<td><label class="control-label">Order Part No.</label></td> <!-- edit by ctc -->
        <td><input class="arial11blackbold" style="width: 250px" type="text" name="ordprtnumber" placeholder="Enter Order Part No." value="<?php echo $ordprtno; ?>" /></td> <!-- edit by ctc -->
    </tr> <!-- edit by ctc -->
	<tr class="arial11blackbold">
    	<td><label class="control-label">Part Name</label></td>
        <td><input class="arial11blackbold" style="width: 250px" type="text" name="partname" placeholder="Enter Part Name" value="<?php echo $Prtnm; ?>" /></td>
    </tr>
    <tr class="arial11blackbold">
    	<td><label class="control-label">Product Brand</label></td>
        <td><input class="arial11blackbold" style="width: 250px" type="text" name="productbrand" placeholder="Enter Product Brand" maxlength="30" value="<?php echo $Brand; ?>" /></td>
    </tr>
	<tr class="arial11blackbold">
    	<td><label class="control-label">Remark</label></td>
        <td><input class="arial11blackbold" style="width: 400px" type="text" name="remark" placeholder="Enter Remark" value="<?php echo $Remark; ?>" /></td>
    </tr>
	
	 <tr class="arial11blackbold">
    	<td><label class="control-label">Part Picture</label></td>
        <td>
        	<p><img src="../user_images/<?php echo $PrtPic; ?>" height="150" width="150" /></p>
        	<input class="input-group" type="file" name="user_image" accept="image/*" />
        </td>
    </tr>
	
	
  <!--  <tr class="arial11blackbold">
    	<td><label class="control-label">Profile Img.</label></td>
        <td><input class="input-group" type="file" name="user_image" accept="image/*" /></td>
    </tr> -->
	
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
