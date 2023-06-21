<?php session_start() ?>
<?php require_once('./../../core/ctc_init.php'); 
require_once('../../language/Lang_Lib.php');?> <!-- add by CTC -->
<?php
if(isset($_SESSION['cusno']))
{       
    if($_SESSION['redir']=='Order-SG'){
        $cusno= $_SESSION['cusno'];
        $cusnm= $_SESSION['cusnm'];
        $password=$_SESSION['password'];
        $alias=$_SESSION['alias'];
        $table=$_SESSION['tablename'];
        $type=$_SESSION['type'];
        $custype=$_SESSION['custype'];
        $user=$_SESSION['user'];
        $dealer=$_SESSION['dealer'];
        $group=$_SESSION['group'];
        $supno=$_SESSION['supno'];
        if($type!='s')header("Location:../main.php");
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
        $stmt_edit = $DB_con->prepare("SELECT CarMaker,ModelName,PrtPic,ModelCode,EngineCode,Cc,Start,End,Cprtn,Brand,Prtno,Lotsize,ordprtno,Prtnm,Remark,Vincode,MTO FROM supcatalogue WHERE ID =:uid"); //edit by ctc
        $stmt_edit->execute(array(':uid'=>$id));
        $edit_row = $stmt_edit->fetch(PDO::FETCH_ASSOC);
        extract($edit_row);
    }
    else
    {
        header("Location: supcata_cataloguemain.php");
    }
    
    
    
    if(isset($_POST['btn_save_updates']))
    {

        $car_maker1 = $_POST['carmaker'];
        $model_name1 = $_POST['modelname'];
		$vin_code = $_POST['vincode'];
        $model_code1 = $_POST['modelcode'];
        $engine_code1 = $_POST['enginecode'];
        $cc1 = $_POST['ccw'];
        $start1 = $_POST['startdate'];
        $end1 = $_POST['enddate'];
        $custprt1 = $_POST['custprtnumber'];
        $brand1 = $_POST['brand'];
        $dnprt1 = $_POST['dnprtnumber'];
		$prtnam1 = $_POST['prtnam'];
        $ordprtno1 = $_POST['ordprtnumber']; //edit by ctc
        $rmk1 = $_POST['remark'];
        $mto = $_POST['mto'];
        $mlotsize = $_POST['lotsize'];
        $imgFile = $_FILES['user_image']['name'];
        $tmp_dir = $_FILES['user_image']['tmp_name'];
        $imgSize = $_FILES['user_image']['size'];
        //    echo $mto;    
        if(empty($car_maker1)){
			$errMSG = get_lng($_SESSION["lng"], "W9016");// "Please Enter Car Maker";
		}
		else if(empty($model_name1)){
			$errMSG = get_lng($_SESSION["lng"], "W9017");//"Please Enter Model Name";
		}
		else if(empty($model_code1)){
			$errMSG = get_lng($_SESSION["lng"], "W9018");//"Please Enter Model Code";
		}
		
		else if(empty($prtnam1)){
			$errMSG = get_lng($_SESSION["lng"], "W9019");//"Please Enter Part Name";
		}
		else if(empty($brand1)){
			$errMSG = get_lng($_SESSION["lng"], "W9020");//"Please Enter Product Brand";
		}
		else if(empty($ordprtno1)){
			$errMSG = get_lng($_SESSION["lng"], "W9021");//"Please Enter Order P/NO";
		}  
		else if(empty($mlotsize)){
            if(((int) $mlotsize) <= 0){
                $errMSG = get_lng($_SESSION["lng"], "W9022");//"Please Enter Lot size more than 0";
            }
            else{
			    $errMSG =  get_lng($_SESSION["lng"], "W9023");//"Please Enter Lot size";
            }
		} 
        else if($mlotsize <= 0){
			$errMSG = get_lng($_SESSION["lng"], "W9022");//"Please Enter Lot size more than 0";
        }
        if($imgFile)
        {
			$upload_dir = '../sup_catalogue/'; // upload directory
			if (!file_exists($upload_dir)) {
				mkdir($upload_dir, 0777, true);
			} 
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
                    $errMSG = get_lng($_SESSION["lng"], "W9008");//"Sorry, your file is too large it should be less then 5MB";
                }
            }
            else
            {
                $errMSG = get_lng($_SESSION["lng"], "W9009");//"Sorry, only JPG, JPEG, PNG & GIF files are allowed.";        
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
                        $stmt = $DB_con->prepare('UPDATE supcatalogue
                        SET CarMaker=:ucar_maker,
                        ModelName=:umodel_name,
                        PrtPic=:upic,
                        ModelCode=:umodel_code,
                        EngineCode=:uengine_code,
                        Cc=:ucc,
                        Start=:ustart,
                        End=:uend,
                        Cprtn=:ucustprt,
                        Prtno=:udnprt,
                        ordprtno=:uordprtno,
                        Prtnm=:uprtnam,
						Brand=:ubrand,
                        Vincode=:vincode,
                        MTO=:umto,
                        lotsize=:ulotsize,
                        Remark=:urmk WHERE ID=:uid'); //edit by ctc
			
            $stmt->bindParam(':ucar_maker',$car_maker1);
            $stmt->bindParam(':umodel_name',$model_name1);
            $stmt->bindParam(':umodel_code',$model_code1);
            $stmt->bindParam(':uengine_code',$engine_code1);
            $stmt->bindParam(':ucc',$cc1);
            $stmt->bindParam(':ustart',$start1);
            $stmt->bindParam(':uend',$end1);
            $stmt->bindParam(':ucustprt',$custprt1);
            $stmt->bindParam(':udnprt',$dnprt1);
            $stmt->bindParam(':uordprtno',$ordprtno1); //edit by ctc
            $stmt->bindParam(':uprtnam',$prtnam1);
            $stmt->bindParam(':urmk',$rmk1);
            $stmt->bindParam(':upic',$userpic); 
			$stmt->bindParam(':ubrand',$brand1);
			$stmt->bindParam(':vincode',$vin_code);
            $stmt->bindParam(':uid',$id);
            $stmt->bindParam(':umto',$mto);
            $stmt->bindParam(':ulotsize',$mlotsize);
                
            if($stmt->execute()){
                ?>
                <script>
                alert('<? echo get_lng($_SESSION["lng"], "L0549")?>');//Successfully Updated ...');
                window.location.href='supcata_cataloguemain.php';
                </script>
                <?php
            }
            else{
                $errMSG = get_lng($_SESSION["lng"], "L0550");//"Sorry Data Could Not Updated !";
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
        <?php require_once('../../core/ctc_cookie.php');?>
        <?php ctc_get_logo_new(); ?>

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
        <a class="btn btn-default" href="supcata_cataloguemain.php"> <?php echo get_lng($_SESSION["lng"], "L0489");//View all?> </a>
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
	else if(isset($successMSG)){
		?>
        <div class="alert alert-success">
              <strong><span class="glyphicon glyphicon-info-sign"></span> <?php echo $successMSG; ?></strong>
        </div>
        <?php
	}
    ?>
   

    <!-- <table class="table table-bordered table-responsive"> -->
    <table width="90%" border="0" cellspacing="2" cellpadding="0">
	<tr class="arial11blackbold">
    	<td width="10%">&nbsp;</td>
        <td width="20%">&nbsp;</td>
        <td width="60%">&nbsp;</td>
    </tr>
    <tr class="arial11blackbold">
        <td colspan="2" class="arial21redbold"><img src="../images/calendar.gif" width="16" height="15">&nbsp; <?php  echo get_lng($_SESSION["lng"], "L0532")//Part Catalogue Maintenance (Update)?></td>
        <td >&nbsp;</td>
    </tr>
    <tr class="arial11blackbold">
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
    </tr>
    
    <tr class="arial11blackbold">
        <td rowspan="8"><?php echo get_lng($_SESSION["lng"], "L0498");//Car Information?></td>
        <td><label class="control-label"><?php echo get_lng($_SESSION["lng"], "L0501");//Car Maker?>  *</label></td>
        <td><input type="text" class="arial11blackbold" style="width: 150px"  name="carmaker" placeholder="Enter Car Maker" value="<?php echo $CarMaker; ?>" /></td>
    </tr>
    
    <tr class="arial11blackbold">
        <td><label class="control-label"><?php echo get_lng($_SESSION["lng"], "L0502");//Model Name?> *</label></td>
        <td><input  type="text" class="arial11blackbold" style="width: 250px" name="modelname" placeholder="Enter Model Name" value="<?php echo $ModelName; ?>" /></td>
    </tr>
	<tr class="arial11blackbold">
    	<td><label class="control-label"><?php echo get_lng($_SESSION["lng"], "L0503");//Vin Code ?> </label></td>
        <td><input class="arial11blackbold" style="width: 250px" type="text" name="vincode" placeholder="Enter Vin Code" maxlength="60" value="<?php echo $Vincode; ?>" /></td>
    </tr>
    <tr class="arial11blackbold">
        <td><label class="control-label"><?php echo get_lng($_SESSION["lng"], "L0504");//Model Code?> *</label></td>
        <td><input type="text" class="arial11blackbold" style="width: 250px" name="modelcode" placeholder="Enter Model Code" value="<?php echo $ModelCode; ?>" /></td>
    </tr>
    <tr class="arial11blackbold">
        <td><label class="control-label"><?php echo get_lng($_SESSION["lng"], "L0505");//Engine Code?></label></td>
        <td><input type="text" class="arial11blackbold" style="width: 250px" name="enginecode" placeholder="Enter Engine Code" value="<?php echo $EngineCode; ?>" /></td>
    </tr>
    <tr class="arial11blackbold">
        <td><label class="control-label"><?php echo get_lng($_SESSION["lng"], "L0506");//CC.?></label></td>
        <td><input class="arial11blackbold" style="width: 250px" type="text" name="ccw" placeholder="Enter Cc" value="<?php echo $Cc; ?>" /></td>
    </tr>
    <tr class="arial11blackbold">
        <td><label class="control-label"><?php echo get_lng($_SESSION["lng"], "L0507");//Start Date (yyyy/mm/dd)?></label></td>
        <td><input class="arial11blackbold" style="width: 150px" type="date" name="startdate" placeholder="Enter Start Date" value="<?php echo $Start; ?>" /></td>
    </tr>
    <tr class="arial11blackbold">
        <td><label class="control-label"><?php echo get_lng($_SESSION["lng"], "L0508");//End Date (yyyy/mm/dd)?></label></td>
        <td><input class="arial11blackbold" style="width: 150px" type="date" name="enddate" placeholder="Enter End Date" value="<?php echo $End; ?>" /></td>
     </tr>
	<tr class="arial11blackbold">
    	<td colspan="3"><hr/></td>
    </tr>
	<tr class="arial11blackbold">
        <td rowspan="2"><?php echo get_lng($_SESSION["lng"], "L0499");//Genuine PN Information?></td>
        <td><label class="control-label"> <?php echo get_lng($_SESSION["lng"], "L0509");//Genuine P/NO ?></label></td>
        <td><input class="arial11blackbold" style="width: 250px" type="text" name="custprtnumber" placeholder="Enter Genuine P/NO" value="<?php echo $Cprtn; ?>" /></td>
    </tr>
    <tr class="arial11blackbold">
        <td><label class="control-label"><?php echo get_lng($_SESSION["lng"], "L0510");//Supplier Genuine P/NO?></label></td>
        <td><input class="arial11blackbold" style="width: 250px" type="text" name="dnprtnumber" placeholder="Enter Supplier P/NO" value="<?php echo $Prtno; ?>" /></td>
    </tr>
	<tr class="arial11blackbold">
    	<td colspan="3"><hr/></td>
    </tr>
	<tr class="arial11blackbold">
        <td rowspan="7"><?php echo get_lng($_SESSION["lng"], "L0500");//Order PN Information?></td>
    	<td><label class="control-label"><?php echo get_lng($_SESSION["lng"], "L0513");//Order P/NO?> * </label></td> <!-- edit by ctc -->
        <td><input class="arial11blackbold" style="width: 250px" type="text" name="ordprtnumber" placeholder="Enter Order Part" value="<?php echo $ordprtno; ?>" /></td> <!-- edit by ctc -->
    </tr>
	<tr class="arial11blackbold">
    	<td><label class="control-label"><?php echo get_lng($_SESSION["lng"], "L0511");//Part Name ?>*</label></td>
        <td><input class="arial11blackbold" style="width: 250px" type="text" name="prtnam" placeholder="Enter Part Name" value="<?php echo $Prtnm; ?>" /></td>
    </tr> 
	<tr class="arial11blackbold">
        <td><label class="control-label"><?php echo get_lng($_SESSION["lng"], "L0512");//Product Brand?> *</label></td>
        <td><input class="arial11blackbold" style="width: 250px" type="text" name="brand" placeholder="Enter Product Brand" maxlength="60" value="<?php echo $Brand; ?>" /></td>
    </tr>
	<tr class="arial11blackbold">
    	<td><label class="control-label"><?php echo get_lng($_SESSION["lng"], "L0514");//Lot size?>  *</label></td>
        <td><input class="arial11blackbold" style="width: 250px" type="number" min="1" name="lotsize" step="any" placeholder="Enter Lot size" maxlength="60" value="<?php echo $Lotsize; ?>" /></td>
    </tr>
    <tr class="arial11blackbold">
        <td><label class="control-label"><?php echo get_lng($_SESSION["lng"], "L0516");//Remark?></label></td>
        <td><input class="arial11blackbold" style="width: 400px" type="text" name="remark" placeholder="Enter Remark" value="<?php echo $Remark; ?>" /></td>
    </tr>
	<tr class="arial11blackbold">
    	<td><label class="control-label"><?php echo get_lng($_SESSION["lng"], "L0517");//MTO?></label></td>
        <td>
		<select name="mto" id="mto" style="width:200px">
			<option value="" <? if($MTO=="") echo "selected" ?>><?php echo get_lng($_SESSION["lng"], "L0449");//Please Select?></option>
			<option value="0" <? if($MTO=="0") echo "selected" ?>>No</option>
			<option value="1" <? if($MTO=="1") echo "selected" ?>>Yes</option>
		</select>
		</td>
    </tr>
     <tr class="arial11blackbold">
        <td><label class="control-label"><?php echo get_lng($_SESSION["lng"], "L0518");//Part Picture ?></label></td>
        <td>
            <p><img src="../sup_catalogue/<?php echo $PrtPic; ?>" height="150" width="150" /></p>
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
        <td colspan="3"><button type="submit" name="btn_save_updates" class="arial11blackbold">&nbsp;<?php echo get_lng($_SESSION["lng"], "L0491");//Update?>&nbsp;</button></td>
  
    </tr>
    
	<tr class="arial11blackbold">
    	<td><font color="red"><?php echo get_lng($_SESSION["lng"], "L0488");//* = required ;?></font></td>
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

