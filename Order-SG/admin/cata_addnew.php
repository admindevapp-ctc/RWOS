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
		$comp = ctc_get_session_comp();
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
		$car_maker = $_POST['carmaker'];
		$model_name = $_POST['modelname'];
		$model_code = $_POST['modelcode'];
		$engine_code = $_POST['enginecode'];
		$cc = $_POST['ccw'];
		$start = $_POST['startdate'];
		$end = $_POST['enddate'];
		$custprth = "";
		$custprt = $_POST['custprtnumber'];
		$prtnh = "";
		$dnprt = $_POST['dnprtnumber'];
		$cgprtnoh = "";
		$cgprtno = $_POST['cgprtnumber'];
        $ordprtno = $_POST['ordprtnumber']; // Edit by CTC
		$prtnam = $_POST['partname'];
		$rmk = $_POST['remark'];
		
		$imgFile = $_FILES['user_image']['name'];
		$tmp_dir = $_FILES['user_image']['tmp_name'];
		$imgSize = $_FILES['user_image']['size'];
		//Add new column 2021
		$vin_code = $_POST['vincode'];
		$product_brand = $_POST['productbrand'];
		
		
		if(empty($car_maker)){
			$errMSG = "Please Enter Car Maker";
		}
		else if(empty($model_name)){
			$errMSG = "Please Enter Model Name";
		}
		else if(empty($vin_code)){
			$errMSG = "Please Enter VIN Code";
		}
		else if(empty($prtnam)){
			$errMSG = "Please Enter Part Name";
		}
		else if(empty($product_brand)){
			$errMSG = "Please Enter Product Brand";
		}
		else if(empty($imgFile)){
			$errMSG = "Please Select Image File";
		}
		else
		{
			$upload_dir = '../user_images/'; // upload directory
	
			$imgExt = strtolower(pathinfo($imgFile,PATHINFO_EXTENSION)); // get image extension
		
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
			$stmt = $DB_con->prepare('INSERT INTO catalogue(CarMaker,ModelName,PrtPic,ModelCode,EngineCode,Cc,Start,End,Custprthis,Cprtn,Prtnohis,Prtno,cgprtnohis,Cgprtno,ordprtno,Prtnm,Remark,Owner_Comp,Vincode,Brand) VALUES(:ucar_maker, :umodel_name, :upic, :umodel_code, :uengine_code, :ucc, :ustart, :uend, :ucustprth, :ucustprt, :uprtnh, :udnprt, :ucgprtnoh, :ucgprtno, :uordprtno, :uprtnam, :urmk, :owner_comp,:uvincode , :uproductbrand )'); // Edit by CTC
			$stmt->bindParam(':ucar_maker',$car_maker);
			$stmt->bindParam(':umodel_name',$model_name);
			$stmt->bindParam(':umodel_code',$model_code);
			$stmt->bindParam(':uengine_code',$engine_code);
			$stmt->bindParam(':ucc',$cc);
			$stmt->bindParam(':ustart',$start);
			$stmt->bindParam(':uend',$end);
			$stmt->bindParam(':ucustprth',$custprth);
			$stmt->bindParam(':ucustprt',$custprt);
			$stmt->bindParam(':uprtnh',$prtnh);
			$stmt->bindParam(':udnprt',$dnprt);
			$stmt->bindParam(':ucgprtnoh',$cgprtnoh);
			$stmt->bindParam(':ucgprtno',$cgprtno);
			$stmt->bindParam(':uordprtno',$ordprtno); // Edit by CTC
			$stmt->bindParam(':uprtnam',$prtnam);
			$stmt->bindParam(':urmk',$rmk);
			$stmt->bindParam(':upic',$userpic);
			$stmt->bindParam(':owner_comp',$comp);
			//Add new column 2021
			$stmt->bindParam(':uproductbrand',$product_brand);
			$stmt->bindParam(':uvincode',$vin_code);
			
			if($stmt->execute())
			{
				$successMSG = "new record succesfully inserted ...";
				header("refresh:2;cata_partcatalogue.php"); // redirects image view page after 2 seconds.
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
 
 </script



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
    	<a class="btn btn-default" href="cata_partcatalogue.php"> <span class="glyphicon glyphicon-eye-open"></span> &nbsp; view all </a>
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
<table width="80%" border="0" cellspacing="2" cellpadding="0">
	<tr class="arial11blackbold">
    	<td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
    </tr>
    <tr class="arial11blackbold">
		<td colspan="2" class="arial21redbold"><img src="../images/calendar.gif" width="16" height="15">&nbsp; Part Catalogue Maintenance (Add New)</td>
		<td >&nbsp;</td>
    </tr>
	<tr class="arial11blackbold">
    	<td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
    </tr>
    <tr class="arial11blackbold">
		<td rowspan="8">Car Information</td>
    	<td><label class="control-label">Car Maker *</label></td>
        <td><input class="arial11blackbold" style="width: 150px" type="text" name="carmaker" placeholder="Enter Car Maker" maxlength="20" value="<?php echo $car_maker; ?>" /></td>
    </tr>
    
    <tr class="arial11blackbold">
    	<td><label class="control-label">Model Name *</label></td>
        <td><input class="arial11blackbold" style="width: 250px" type="text" name="modelname" placeholder="Enter Model Name" maxlength="30" value="<?php echo $model_name; ?>" /></td>
        
    </tr>
    <tr class="arial11blackbold">
    	<td><label class="control-label">VIN Code*</label></td>
        <td><input class="arial11blackbold" style="width: 250px" type="text" name="vincode" placeholder="Enter VIN Code" maxlength="30" value="<?php echo $vin_code; ?>" /></td>
    </tr>
	<tr class="arial11blackbold">
    	<td><label class="control-label">Model Code *</label></td>
        <td><input class="arial11blackbold" style="width: 250px" type="text" name="modelcode" placeholder="Enter Model Code" maxlength="30" value="<?php echo $model_code; ?>" /></td>
    </tr>
	<tr class="arial11blackbold">
    	<td><label class="control-label">Engine Code </label></td>
        <td><input class="arial11blackbold" style="width: 250px" type="text" name="enginecode" placeholder="Enter Engine Code" maxlength="30" value="<?php echo $engine_code; ?>" /></td>
    </tr>
	<tr class="arial11blackbold">
    	<td><label class="control-label">CC.</label></td>
        <td><input class="arial11blackbold" style="width: 250px" type="text" name="ccw" placeholder="Enter CC." maxlength="30" value="<?php echo $cc; ?>" /></td>
    </tr>
	<tr class="arial11blackbold">
    	<td><label class="control-label">Start Date (dd/mm/yyyy)</label></td>
        <td><input class="arial11blackbold" style="width: 150px" type="date" name="startdate" placeholder="Enter Start Date"  maxlength="10" value="<?php echo $start; ?>" /></td>
    </tr>
	<tr class="arial11blackbold">
    	<td><label class="control-label">End Date (dd/mm/yyyy)</label></td>
        <td><input class="arial11blackbold" style="width: 150px" type="date" name="enddate" placeholder="Enter End Date"  maxlength="10" value="<?php echo $end; ?>" /></td>
    </tr>
	<tr class="arial11blackbold">
    	<td colspan="3"><hr/></td>
    </tr>
	<!--<tr class="arial11blackbold">
    	<td><label class="control-label">Genuine P/NO (Old)</label></td>
        <td><input class="arial11blackbold" style="width: 250px" type="text" name="custprthistory" placeholder="Enter History Customer P/NO" maxlength="30" value="<?php echo $custprth; ?>" /></td>
    </tr>-->
	<tr class="arial11blackbold">
		<td rowspan="3">Genuine Part No. information</td>
    	<td><label class="control-label">Genuine Part No. </label></td>
        <td><input class="arial11blackbold" style="width: 250px" type="text" name="custprtnumber" placeholder="Enter Genuine Part No." maxlength="30" value="<?php echo $custprt; ?>" /></td>
    </tr>
	<!--<tr class="arial11blackbold">
    	<td><label class="control-label">DENSO P/NO (Old)</label></td>
        <td><input class="arial11blackbold" style="width: 250px" type="text" name="dnprthistory" placeholder="Enter History  DENSO P/NO" maxlength="30" value="<?php echo $prtnh; ?>" /></td>
    </tr>-->
	<tr class="arial11blackbold">
    	<td><label class="control-label">DENSO Part No.  </label></td>
        <td><input class="arial11blackbold" style="width: 250px" type="text" name="dnprtnumber" placeholder="Enter DENSO Part No." maxlength="30" value="<?php echo $dnprt; ?>" /></td>
    </tr>
	<!--<tr class="arial11blackbold">
    	<td><label class="control-label">CG P/NO (Old)</label></td>
        <td><input class="arial11blackbold" style="width: 250px" type="text" name="cgprtnumberh" placeholder="Enter History  CG P/NO" maxlength="30" value="<?php echo $cgprtnoh; ?>" /></td>
    </tr>-->
		<tr class="arial11blackbold">
    	<td><label class="control-label">CG Part No.  </label></td>
        <td><input class="arial11blackbold" style="width: 250px" type="text" name="cgprtnumber" placeholder="Enter CG Part No." maxlength="30" value="<?php echo $cgprtno; ?>" /></td>
    </tr>
	<tr class="arial11blackbold">
    	<td colspan="3"><hr/></td>
    </tr>
	<tr class="arial11blackbold"> <!--Edit by CTC-->
		<td rowspan="5">Order Part No. information</td>
    	<td><label class="control-label">Order Part No.  </label></td> <!--Edit by CTC-->
        <td><input class="arial11blackbold" style="width: 250px" type="text" name="ordprtnumber" placeholder="Enter Order Part No." maxlength="30" value="<?php echo $ordprtno; ?>" /></td> <!--Edit by CTC-->
    </tr>  <!--Edit by CTC-->
	<tr class="arial11blackbold">
    	<td><label class="control-label">Part Name *</label></td>
        <td><input class="arial11blackbold" style="width: 250px" type="text" name="partname" placeholder="Enter Part Name" maxlength="60" value="<?php echo $prtnam; ?>" /></td>
    </tr>
    <tr class="arial11blackbold">
    	<td><label class="control-label">Product Brand*</label></td>
        <td><input class="arial11blackbold" style="width: 250px" type="text" name="productbrand" placeholder="Enter Product Brand" maxlength="30" value="<?php echo $product_brand; ?>" /></td>
    </tr>
	<tr class="arial11blackbold">
    	<td><label class="control-label">Remark</label></td>
        <td><input class="arial11blackbold" style="width: 400px" type="text" name="remark" placeholder="Enter Remark" maxlength="200" value="<?php echo $rmk; ?>" /></td>
    </tr>
	
    <tr class="arial11blackbold">
    	<td><label class="control-label">Part Picture*</label></td>
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
		  

<script src="bootstrap/js/bootstrap.min.js"></script>
              
	
	<div id="footerMain1">
	<ul>
      
     
     
      </ul>
	<br/>
    <div id="footerDesc">

	<p>
	Copyright &copy; 2023 DENSO . All rights reserved  
	
  </div>
</div>

</div>
	</body>
</html>
