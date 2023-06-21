<?php

session_start();
require_once('./../../core/ctc_init.php'); // add by CTC
require_once('../../language/Lang_Lib.php');
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
		$supno=$_SESSION['supno'];
		$comp = ctc_get_session_comp();
		if($type!='s'){
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
		$vin_code = $_POST['vincode'];
		$model_code = $_POST['modelcode'];
		$engine_code = $_POST['enginecode'];
		$cc = $_POST['ccw'];
		$start = $_POST['startdate'];
		$end = $_POST['enddate'];
		$custprt = $_POST['custprtnumber'];
		$prtno = $_POST['prtnumber'];
		$prtnam = $_POST['partname'];
		$pbrand = $_POST['brand'];
        $ordprtno = $_POST['ordprtnumber'];  
		$lot_size = $_POST['lotsize'];
		$mto = $_POST['mto'];
		//$stock_info = $_POST['stockinfo'];
		$rmk = $_POST['remark'];
		
		$imgFile = $_FILES['user_image']['name'];
		$tmp_dir = $_FILES['user_image']['tmp_name'];
		$imgSize = $_FILES['user_image']['size'];
		
		
		if(empty($car_maker)){
			$errMSG = get_lng($_SESSION["lng"], "W9016");//"Please Enter Car Maker";
		}
		else if(empty($model_name)){
			$errMSG = get_lng($_SESSION["lng"], "W9017");//"Please Enter Model Name";
		}
		else if(empty($model_code)){
			$errMSG = get_lng($_SESSION["lng"], "W9018");//"Please Enter Model Code";
		}
		
		else if(empty($prtnam)){
			$errMSG = get_lng($_SESSION["lng"], "W9019");//"Please Enter Part Name";
		}
		else if(empty($pbrand)){
			$errMSG = get_lng($_SESSION["lng"], "W9020");//"Please Enter Product Brand";
		}
		else if(empty($ordprtno)){
			$errMSG = get_lng($_SESSION["lng"], "W9021");//"Please Enter Order P/NO";
		}
		else if(empty($lot_size)){
            if(((int) $lot_size) <= 0){
                $errMSG = get_lng($_SESSION["lng"], "W9022");//"Please Enter Lot size more than 0";
            }
            else{
			    $errMSG = get_lng($_SESSION["lng"], "W9023");//"Please Enter Lot size";
            }
		} 
        else if($lot_size <= 0){
			$errMSG = get_lng($_SESSION["lng"], "W9022");//"Please Enter Lot size more than 0";
        }
		else
		{
			$upload_dir = '../sup_catalogue/'; // upload directory
			if (!file_exists($upload_dir)) {
				mkdir($upload_dir, 0777, true);
			}
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
					$errMSG = get_lng($_SESSION["lng"], "W9008");//"Sorry, your file is too large.";
				}
			}
			else{
				$errMSG = get_lng($_SESSION["lng"], "W9009");//"Sorry, only JPG, JPEG, PNG & GIF files are allowed.";		
			}
		}
		
		
		// if no error occured, continue ....
		if(!isset($errMSG))
		{
			//$resut = 'INSERT INTO supcatalogue(CarMaker,ModelName,PrtPic,ModelCode,EngineCode,Cc,Start,End,Cprtn,Prtno,ordprtno,Supcd,Prtnm, Vincode,Lotsize,Brand,Remark,Owner_Comp) 
			//VALUES(:ucar_maker, :umodel_name, :upic, :umodel_code, :uengine_code, :ucc, :ustart, :uend, :ucustprt, :udnprt, :uordprtno, :supno,:uprtnam, ,:vincode, :lotsize, :brand, :urmk, :owner_comp )';

			//echo $resut."<br/>";
			//$stmt = $DB_con->prepare('INSERT INTO supcatalogue(CarMaker,ModelName,PrtPic,ModelCode,EngineCode,Cc,Start,End,Custprthis,Cprtn,Prtnohis,Prtno,cgprtnohis,Cgprtno,ordprtno,Prtnm,Remark,Owner_Comp) VALUES(:ucar_maker, :umodel_name, :upic, :umodel_code, :uengine_code, :ucc, :ustart, :uend, :ucustprth, :ucustprt, :uprtnh, :udnprt, :ucgprtnoh, :ucgprtno, :uordprtno, :uprtnam, :urmk, :owner_comp )'); // Edit by CTC
			$stmt = $DB_con->prepare('INSERT INTO supcatalogue(Owner_Comp, CarMaker,ModelName,PrtPic,ModelCode,EngineCode,Cc,Start,End,Cprtn,Prtno,Prtnm
				,Brand,ordprtno,Supcd, Vincode,Lotsize,Remark,MTO) 
				VALUES(:owner_comp , :ucar_maker, :umodel_name, :upic, :umodel_code, :uengine_code, :ucc, :ustart, :uend, :ucustprt, :uprtno,:uprtnam
				, :brand, :uordprtno, :supno ,:vincode, :lotsize, :urmk, :umto)');
			$stmt->bindParam(':owner_comp',$comp);
			$stmt->bindParam(':ucar_maker',$car_maker);
			$stmt->bindParam(':umodel_name',$model_name);
			$stmt->bindParam(':umodel_code',$model_code);
			$stmt->bindParam(':uengine_code',$engine_code);
			$stmt->bindParam(':ucc',$cc);
			$stmt->bindParam(':upic',$userpic);
			$stmt->bindParam(':ustart',$start);
			$stmt->bindParam(':uend',$end);
			$stmt->bindParam(':ucustprt',$custprt);//Genuine P/NO
			$stmt->bindParam(':uprtno',$prtno);//Supplier Genuine P/NO
			$stmt->bindParam(':uprtnam',$prtnam);
			$stmt->bindParam(':brand',$pbrand);
			$stmt->bindParam(':uordprtno',$ordprtno); // Order P/NO 
			$stmt->bindParam(':supno',$supno);
			$stmt->bindParam(':vincode',$vin_code);
			$stmt->bindParam(':lotsize',$lot_size);
			$stmt->bindParam(':urmk',$rmk);
			$stmt->bindParam(':umto',$mto);

			//echo "INSERT INTO supcatalogue(Owner_Comp, CarMaker,ModelName,PrtPic,ModelCode,EngineCode,Cc,Start,End,Cprtn,Prtno,Prtnm,Brand,ordprtno,Supcd, Vincode,Lotsize,Remark)  VALUES('$comp' , '$car_maker', '$model_name', '$userpic', '$model_code', '$engine_code', '$cc', '$start', '$end', '$custprt', '$prtno','$prtnam' , '$pbrand', '$ordprtno', '$supno' ,'$vin_code', '$lot_size', '$rmk')";
			//Custprthis,,Prtnohis,cgprtnohis,Cgprtno   :ucustprth, :uprtnh, ucgprtnoh, :ucgprtno

			if($stmt->execute())
			{
				$successMSG = get_lng($_SESSION["lng"], "W9010");//"new record succesfully inserted ...";
				header("refresh:2;supcata_cataloguemain.php"); // redirects image view page after 2 seconds.
			}
			else
			{
				$errMSG = get_lng($_SESSION["lng"], "W9011");//"error while inserting....";
			}
		}
	}
?>




<html>
	<head>
    <title>Denso Ordering System</title>
	<meta http-equiv="X-UA-Compatible" content="IE=9, IE=10, IE=11, IE=EDGE" /> 
   	<link rel="stylesheet" type="text/css" href="../css/dnia.css">
	<link rel="stylesheet" href="../admin/bootstrap/css/bootstrap.min.css">

<!-- Optional theme -->
<link rel="stylesheet" href="../admin/bootstrap/css/bootstrap-theme.min.css">
	
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
		<?php 
			  	$_GET['step']="1";
				include("supnavhoriz.php");
			?>
	</div> 
    	<div id="isi">
        
        <div id="twocolLeft">
           	<div class="hmenu">
              <?
			  	$MYROOT=$_SERVER['DOCUMENT_ROOT'];
			  	$_GET['current']="supCatalogue";
				include("supnavAdm.php");
			  ?>
        </div>
        <div id="twocolRight">
  
         
		 
	<div class="page-header">
    	<a class="btn btn-default" href="supcata_cataloguemain.php"> <span class="glyphicon glyphicon-eye-open"></span> &nbsp; view all </a>
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
<table width="90%" border="0" cellspacing="2" cellpadding="0">
	<tr class="arial11blackbold">
    	<td width="10%">&nbsp;</td>
        <td width="20%">&nbsp;</td>
        <td width="60%">&nbsp;</td>
    </tr>
    <tr class="arial11blackbold">
		<td class="arial21redbold" colspan="2"><img src="../images/calendar.gif" width="16" height="15">&nbsp; <?php  echo get_lng($_SESSION["lng"], "L0531")//Part Catalogue Maintenance (Add New)?></td>
		<td >&nbsp;</td>
    </tr>
	<tr class="arial11blackbold">
    	<td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
    </tr>
	
    <tr class="arial11blackbold">
        <td rowspan="8"><?php echo get_lng($_SESSION["lng"], "L0498");//Car Information?></td>
    	<td><label class="control-label"><?php echo get_lng($_SESSION["lng"], "L0501");//Car Maker?> *</label></td>
        <td><input class="arial11blackbold" style="width: 150px" type="text" name="carmaker" placeholder="<?php echo get_lng($_SESSION["lng"], "L0533");?>" maxlength="20" value="<?php echo $car_maker; ?>" /></td>
    </tr>
    
    <tr class="arial11blackbold">
    	<td><label class="control-label"><?php echo get_lng($_SESSION["lng"], "L0502");//Model Name?> *</label></td>
        <td><input class="arial11blackbold" style="width: 250px" type="text" name="modelname" placeholder="<?php echo get_lng($_SESSION["lng"], "L0534");?>" maxlength="30" value="<?php echo $model_name; ?>" /></td>
    </tr>
	<tr class="arial11blackbold">
    	<td><label class="control-label"><?php echo get_lng($_SESSION["lng"], "L0503");//Vin Code ?></label></td>
        <td><input class="arial11blackbold" style="width: 250px" type="text" name="vincode" placeholder="<?php echo get_lng($_SESSION["lng"], "L0535");?>" maxlength="60" value="<?php echo $vin_code; ?>" /></td>
    </tr>
	<tr class="arial11blackbold">
    	<td><label class="control-label"><?php echo get_lng($_SESSION["lng"], "L0504");//Model Code?> *</label></td>
        <td><input class="arial11blackbold" style="width: 250px" type="text" name="modelcode" placeholder="<?php echo get_lng($_SESSION["lng"], "L0536");?>" maxlength="30" value="<?php echo $model_code; ?>" /></td>
    </tr>
	<tr class="arial11blackbold">
    	<td><label class="control-label"><?php echo get_lng($_SESSION["lng"], "L0505");//Engine Code?> </label></td>
        <td><input class="arial11blackbold" style="width: 250px" type="text" name="enginecode" placeholder="<?php echo get_lng($_SESSION["lng"], "L0537");?>" maxlength="30" value="<?php echo $engine_code; ?>" /></td>
    </tr>
	<tr class="arial11blackbold">
    	<td><label class="control-label"><?php echo get_lng($_SESSION["lng"], "L0506");//CC.?></label></td>
        <td><input class="arial11blackbold" style="width: 250px" type="text" name="ccw" placeholder="<?php echo get_lng($_SESSION["lng"], "L0538");?>" maxlength="30" value="<?php echo $cc; ?>" /></td>
    </tr>
	<tr class="arial11blackbold">
    	<td><label class="control-label"><?php echo get_lng($_SESSION["lng"], "L0507");//Start Date (yyyy/mm/dd)?></label></td>
        <td><input class="arial11blackbold" style="width: 150px" type="date" name="startdate" placeholder="<?php echo get_lng($_SESSION["lng"], "L0539");?>" maxlength="10" value="<?php echo $start; ?>" /></td>
    </tr>
	<tr class="arial11blackbold">
    	<td><label class="control-label"><?php echo get_lng($_SESSION["lng"], "L0508");//End Date (yyyy/mm/dd)?></label></td>
        <td><input class="arial11blackbold" style="width: 150px" type="date" name="enddate" placeholder="<?php echo get_lng($_SESSION["lng"], "L0540");?>" maxlength="10" value="<?php echo $end; ?>" /></td>
    </tr>
	<tr class="arial11blackbold">
    	<td colspan="3"><hr/></td>
    </tr>
	<tr class="arial11blackbold">
        <td rowspan="2"><?php echo get_lng($_SESSION["lng"], "L0499");//Genuine PN Information?></td>
    	<td><label class="control-label"><?php echo get_lng($_SESSION["lng"], "L0509");//Genuine P/NO ?></label></td>
        <td><input class="arial11blackbold" style="width: 250px" type="text" name="custprtnumber" placeholder="<?php echo get_lng($_SESSION["lng"], "L0541");?>" maxlength="30" value="<?php echo $custprt; ?>" /></td>
    </tr>
	<tr class="arial11blackbold">
    	<td><label class="control-label"><?php echo get_lng($_SESSION["lng"], "L0510");//Supplier Genuine P/NO?></label></td>
        <td><input class="arial11blackbold" style="width: 250px" type="text" name="prtnumber" placeholder="<?php echo get_lng($_SESSION["lng"], "L0542");?>" maxlength="30" value="<?php echo $prtno; ?>" /></td>
    </tr>
	<tr class="arial11blackbold">
    	<td colspan="3"><hr/></td>
    </tr>
	<tr class="arial11blackbold">
		<td rowspan="7"><?php echo get_lng($_SESSION["lng"], "L0500");//Order PN Information?></td>
		<td><label class="control-label"><?php echo get_lng($_SESSION["lng"], "L0513");//Order P/NO?> * </label></td>
        <td><input class="arial11blackbold" style="width: 250px" type="text" name="ordprtnumber" placeholder="<?php echo get_lng($_SESSION["lng"], "L0545");?>" maxlength="30" value="<?php echo $ordprtno; ?>" /></td> <!--Edit by CTC-->
    </tr>
	<tr class="arial11blackbold">
    	<td><label class="control-label"><?php echo get_lng($_SESSION["lng"], "L0511");//Part Name ?>*</label></td>
        <td><input class="arial11blackbold" style="width: 250px" type="text" name="partname" placeholder="<?php echo get_lng($_SESSION["lng"], "L0543");?>" maxlength="60" value="<?php echo $prtnam; ?>" /></td>
    </tr>
	<tr class="arial11blackbold"> 
		<td><label class="control-label"><?php echo get_lng($_SESSION["lng"], "L0512");//Product Brand?> *</label></td>
        <td><input class="arial11blackbold" style="width: 250px" type="text" name="brand" placeholder="<?php echo get_lng($_SESSION["lng"], "L0544");?>" maxlength="60" value="<?php echo $pbrand; ?>" /></td>
    </tr>
	<tr class="arial11blackbold">
    	<td><label class="control-label"><?php echo get_lng($_SESSION["lng"], "L0514");//Lot size?> *</label></td>
        <td><input class="arial11blackbold" style="width: 250px" type="number"  min="1" step="any" name="lotsize" placeholder="<?php echo get_lng($_SESSION["lng"], "L0546");?>" maxlength="60" value="<?php echo $lot_size; ?>" /></td>
    </tr>
	<!--<tr class="arial11blackbold">
    	<td><label class="control-label"><?php echo get_lng($_SESSION["lng"], "L0515");//Stock info ?></label></td>
        <td><input class="arial11blackbold" style="width: 250px" type="text" name="stockinfo" placeholder="Enter Stock" maxlength="60" value="<?php echo $stock_info; ?>" /></td>
    </tr>-->
	<tr class="arial11blackbold">
    	<td><label class="control-label"><?php echo get_lng($_SESSION["lng"], "L0516");//Remark?></label></td>
        <td><input class="arial11blackbold" style="width: 400px" type="text" name="remark" placeholder="<?php echo get_lng($_SESSION["lng"], "L0547");?>" maxlength="200" value="<?php echo $rmk; ?>" /></td>
    </tr>
	<tr class="arial11blackbold">
    	<td><label class="control-label"><?php echo get_lng($_SESSION["lng"], "L0517");//MTO?></label></td>
        <td>
		<select name="mto" id="mto" style="width:200px">
			<option value="0">No</option>
			<option value="1">Yes</option>
		</select>
		</td>
    </tr>
    <tr class="arial11blackbold">
    	<td><label class="control-label"><?php echo get_lng($_SESSION["lng"], "L0518");//Part Picture ?>*</label></td>
        <td><input class="input-group" type="file" name="user_image" accept="image/*" /></td>
    </tr>
    <tr class="arial11blackbold">
    	<td>&nbsp;</td>
        <td>&nbsp;</td>
    </tr>
    <tr class="arial11blackbold">
	    <td><label class="control-label">&nbsp;</label></td>
        <td colspan="2"><button type="submit" name="btnsave" class="arial11blackbold">&nbsp; <?php echo get_lng($_SESSION["lng"], "L0487");//Save?>&nbsp;</button></td>
    </tr>
	<tr class="arial11blackbold">
    	<td><font color="red">* = required </font></td>
        <td>&nbsp;</td>
    </tr>
    
    </table>
    
</form>
		  

</div>
<script src="bootstrap/js/bootstrap.min.js"></script>
              
	
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
