<?php 

session_start();
require_once('../../../core/ctc_init.php');
require_once('../../../core/ctc_permission.php');
require_once('../../../language/Lang_Lib.php');

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
		$comp = ctc_get_session_comp(); // add by CTC
		if($type!='s'){
			header("Location:../../main.php");
		}
	 }else{
		echo "<script> document.location.href='../../../".redir."'; </script>";
	 }
}else{	
	header("Location:../../login.php");
}
?>
<link rel="stylesheet" type="text/css" href="../../css/dnia.css">
<link rel="stylesheet" type="text/css" href="../../css/custom_datatable.css">
<link rel="stylesheet" type="text/css" href="../../css/ui/jquery-ui-1.13.2.min.css">
<link rel="stylesheet" type="text/css" href="../../admin/bootstrap3.3.7/css/bootstrap.min.css">
<link rel="stylesheet" type="text/css" href="../../admin/DataTables/dataTables.bootstrap.min.css">
<link rel="stylesheet" type="text/css" href="../../admin/DataTables/fixedColumns.bootstrap.min.css">


<?php


	//error_reporting( ~E_NOTICE ); // avoid notice
	
	require_once '../cata_dbconfig.php';

	if(isset($_POST['btnsave']))
	{
		if( isset($_POST['check_cus']) && is_array($_POST['check_cus']) ) {
		$List = implode(', ', $_POST['check_cus']);
		}

		$title = $_POST['title'];
		$detail = $_POST['detail'];
		$start = date('Y-m-d', strtotime( str_replace('/', '-', $_POST['start']) ));
		$end = date('Y-m-d', strtotime( str_replace('/', '-', $_POST['end']) ));
		$updateby = $_POST['updateby'];
		$cusmas = $_POST['check_cus'];
		
		require_once('../../db/conn.inc');
		$query="SELECT title,detail,start,end,updateby,PrtPic,cusno as cusdb FROM supannounce WHERE title = '$title' AND detail = '$detail' AND start = '$start' AND end='$end' LIMIT 1";
		$sql=mysqli_query($msqlcon,$query);	
		while($hasil = mysqli_fetch_array($sql)){
			$errMSG = get_lng($_SESSION["lng"], "W9029"); //"Can not insert duplicate data.";
		}
		$imgFile = $_FILES['user_image']['name'];
		$tmp_dir = $_FILES['user_image']['tmp_name'];
		$imgSize = $_FILES['user_image']['size'];
		
		
		if(empty($title)){
			$errMSG = get_lng($_SESSION["lng"], "W9001"); //"Please Enter Title";
		}
		else if(empty($detail)){
			$errMSG =  get_lng($_SESSION["lng"], "W9002"); //"Please Enter Detail";
		}
		else if(empty($cusmas)){
			$errMSG =  get_lng($_SESSION["lng"], "W9003"); //"Please Input Customer";
		} 
		else if(empty($start)){
			$errMSG =  get_lng($_SESSION["lng"], "W9004"); //"Please Enter Start Date ";
		}
		
		else if(empty($end)){
			$errMSG =  get_lng($_SESSION["lng"], "W9005"); //"Please Enter End Date";
		}

		else if(empty($updateby)){
			$errMSG =  get_lng($_SESSION["lng"], "W9006"); //"Please Enter Update by";
		}
		else if(empty($imgFile)){
			$errMSG =  get_lng($_SESSION["lng"], "W9007"); //"Please Select Image File";
		} 
		else
		{
			$upload_dir = '../../sup_annaimages/'; // upload directory
			if (!file_exists($upload_dir)) {
				mkdir($upload_dir, 0777, true);
			}
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
					$errMSG = get_lng($_SESSION["lng"], "W9008"); //"Sorry, your file is too large.";
				}
			}
			else{
				$errMSG = get_lng($_SESSION["lng"], "W9009"); //"Sorry, only JPG, JPEG, PNG & GIF files are allowed.";		
			}
		}
		
		// if no error occured, continue ....
		if(!isset($errMSG))
		{
			$sql='';
			foreach ($cusmas as $v) {
			  $sql .= 'INSERT INTO supannounce(title,detail,start,end,updateby,PrtPic,Owner_Comp, supno, cusno) 
				VALUES("'.$title.'", "'.$detail.'", "'.$start.'","'.$end.'", "'.$updateby.'", "'.$userpic.'","'.$comp.'","'.$supno.'","'.$v.'");';
			}
			
			$DB_con->query($sql);
				$successMSG = get_lng($_SESSION["lng"], "W9010"); //"new record succesfully inserted ...";
				header("refresh:0;../supanna_mainadm.php"); // redirects image view page after 2 seconds.
			// if($DB_con->query($sql) === TRUE)
			// {
				// $successMSG = get_lng($_SESSION["lng"], "W9010"); //"new record succesfully inserted ...";
				// header("refresh:0;../supanna_mainadm.php"); // redirects image view page after 2 seconds.
			// }
			// else
			// {
				// $errMSG =  get_lng($_SESSION["lng"], "W9011"); //"error while inserting....";
			// }
		}
	}
?>




<html>
	<head>
    <title>Denso Ordering System</title>
	<meta http-equiv="X-UA-Compatible" content="IE=9, IE=10, IE=11, IE=EDGE" /> 
   	<link rel="stylesheet" type="text/css" href="../../css/dnia.css">
	<link rel="stylesheet" href="../../admin/bootstrap/css/bootstrap.min.css">

<!-- Optional theme -->
<link rel="stylesheet" href="../../admin/bootstrap/css/bootstrap-theme.min.css">
	
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
       
          
        <?php 
			  	$_GET['step']="2";
				include("../supnavhoriz.php");
			?>
	</div> 
    	<div id="isi">
        
        <div id="twocolLeft">
           	<div class="hmenu">
              <?
			  	$MYROOT=$_SERVER['DOCUMENT_ROOT'];
			  	$_GET['current']="supannouncement";
				include("supnavAdm.php");
			  ?>
        </div>
        <div id="twocolRight">
  
        <?
		  require('../../db/conn.inc');

		//Supplier
		$query="select * from supmas where Owner_Comp='$comp' and supno='$supno'  ";
		$sql=mysqli_query($msqlcon,$query);	
		//echo $query;
		while($hasil = mysqli_fetch_array ($sql)){
			
			$supno=$hasil['supno'];	
			$supnm=$hasil['supnm'];
			$suplogo= $hasil['logo'];
			$inpsupcode= $supno;	
			$inpsupname= $supnm;		
			
			
		}


            // Customer

			
			$query="select supref.*, cusmas.Cusnm from supref LEFT JOIN cusmas on cusmas.Cusno = supref.Cusno AND supref.Owner_comp = cusmas.Owner_Comp where 1 AND supref.Owner_Comp='$comp' and supref.supno='$supno' ORDER BY CAST(supref.Cusno AS INT) ASC";
			$new_inpcus = '';
			$new_inpcus .= '<table class="tbl_cus" border="1" style=" font-size:12px; width:100%;">';
			$new_inpcus .= '<p style="position:absolute; top:11px;">'.get_lng($_SESSION["lng"], "L0555").'</p>'; // Please select customer
			$new_inpcus .= '<thead style="background-color: #AD1D36; color:white;">';
			$new_inpcus .= '<th style="width: 70px;" class="no_sorting text-center"><input type="checkbox" id="chk_all_cus">'.get_lng($_SESSION["lng"], "L0553").'</th>'; //All
			$new_inpcus .= '<th class="text-center" style="width: 20%;">'.get_lng($_SESSION["lng"], "L0482").'</th>'; //Customer Number
			$new_inpcus .= '<th style="padding: 0% 3%;">'.get_lng($_SESSION["lng"], "L0552").'</th>'; //Name
			$new_inpcus .= '</thead>';
			$new_inpcus .= '<tbody>';
			$sql=mysqli_query($msqlcon,$query);	
			
			while($hasil = mysqli_fetch_array($sql)){
				$cusno=$hasil['Cusno'];
				$Cusnm=$hasil['Cusnm'];
				$checked = '';
			$new_inpcus .= '
			<tr>
				<td data-cusno="'.$cusno.'" class="text-center"><input type="checkbox" name="check_cus[]" class="check_cus " value="'.$cusno.'"></td>
				<td class="cus_no text-center" data-id="'.$cusno.'">'.$cusno.'</td>
				<td class="cus_name" style="padding: 0% 3%;">'.$Cusnm.'</td>
			</tr>';	
			}
				
			
				
			$new_inpcus .= '</tbody>';
			$new_inpcus .= '</table>';
        ?>

		
	<div class="page-header">
    	<a class="btn btn-default" href="../supanna_mainadm.php"> <span class="glyphicon glyphicon-eye-open"></span> &nbsp; <?php echo get_lng($_SESSION["lng"], "L0489"); //view all ?></a>

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
		 
		 
<form method="post" id="frm-sub" enctype="multipart/form-data" class="form-horizontal">

<!--<table class="table table-bordered table-responsive"> -->
<table width="80%" border="0" cellspacing="5" cellpadding="0">
	<tr class="arial11blackbold">
    	<td width="20%">&nbsp;</td>
        <td width="60%">&nbsp;</td>
    </tr>
    <tr class="arial11blackbold">
    <td class="arial21redbold"><img src="../../images/calendar.gif" width="16" height="15">&nbsp;<?php echo get_lng($_SESSION["lng"], "M009"); //  Announcement Maintenance ?></td>
    <td >&nbsp;</td>
    </tr>
	<tr class="arial11blackbold">
    	<td>&nbsp;</td>
        <td>&nbsp;</td>
    </tr>
	
    <tr class="arial11blackbold">
    	<td><label class="control-label"><?php echo get_lng($_SESSION["lng"], "L0480"); //Title?> *</label></td>
        <td><input class="arial11blackbold" style="width: 300px" type="text" name="title" placeholder="<?php echo get_lng($_SESSION["lng"], "W9012"); //Please type Title?>" maxlength="200" value="<?php echo $title; ?>"/></td>
    </tr>
    <tr class="arial11blackbold">
    	<td>&nbsp;</td>
        <td>&nbsp;</td>
    </tr>
    <tr class="arial11blackbold">
    	<td><label class="control-label"><?php echo get_lng($_SESSION["lng"], "L0481"); //Detail?>  *</label></td>
        <td><textarea class="arial11blackbold" style="width: 300px" rows="15" cols="40" type="text" name="detail" placeholder="<?php echo get_lng($_SESSION["lng"], "W9013"); //Please type detail?>" maxlength="500" ><?php echo $detail; ?></textarea></td>
    </tr>
    <tr class="arial11blackbold">
    	<td>&nbsp;</td>
        <td>&nbsp;</td>
    </tr>
    <tr class="arial11blackbold">
    	<td><label class="control-label"><?php echo get_lng($_SESSION["lng"], "L0482"); //Customer Number ?> *</label></td>

        <td style="position:relative;"><? echo $new_inpcus ?></td>
    </tr>
    <tr class="arial11blackbold">
    	<td>&nbsp;</td>
        <td>&nbsp;</td>
    </tr>
	<tr class="arial11blackbold">
    	<td><label class="control-label"><?php echo get_lng($_SESSION["lng"], "L0483"); //Effective Date From ?>   *</label></td>
        <td><input type="text" autocomplete="off" id="datepicker_start" style="width: 150px" name="start" placeholder="<?php echo get_lng($_SESSION["lng"], "W9014"); //yyyy/mm/dd?>" /></td>
		
    </tr>
    <tr class="arial11blackbold">
    	<td>&nbsp;</td>
        <td>&nbsp;</td>
    </tr>
	<tr class="arial11blackbold">
    	<td><label class="control-label"><?php echo get_lng($_SESSION["lng"], "L0484"); //EEffective Date To ?>  *</label></td>
        <td><input type="text" autocomplete="off" id="datepicker_end" style="width: 150px" name="end" placeholder="<?php echo get_lng($_SESSION["lng"], "W9014"); //yyyy/mm/dd?>" /></td>
    </tr>
    <tr class="arial11blackbold">
    	<td>&nbsp;</td>
        <td>&nbsp;</td>
    </tr>
	<tr class="arial11blackbold">
    	<td><label class="control-label"><?php echo get_lng($_SESSION["lng"], "L0485"); //Update By ?>   *</label></td>
        <td><input class="arial11blackbold" style="width: 250px" type="text" name="updateby" placeholder="<?php echo get_lng($_SESSION["lng"], "W9015"); //Enter Name?>" maxlength="30" value="<?php echo $updateby; ?>" /></td>
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
    	<td><label class="control-label"><?php echo get_lng($_SESSION["lng"], "L0486"); //Picture(Size: 855x153 pixel)?> * </label></td>
        <td><input class="input-group" type="file" name="user_image" accept="image/*" /></td>
    </tr>

	    <tr class="arial11blackbold">
    	<td>&nbsp;</td>
        <td>&nbsp;</td>
    </tr>
    <tr class="arial11blackbold">
	    <td><label class="control-label">&nbsp;</label></td>
        <td colspan="2"><button type="submit" name="btnsave" class="arial11blackbold">&nbsp;<?php echo get_lng($_SESSION["lng"], "L0487"); // Save)?> &nbsp;</button></td>
    </tr>
	<tr class="arial11blackbold">
    	<td><font color="red"><?php echo get_lng($_SESSION["lng"], "L0488"); //* = required)?>  </font></td>
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
	<script type="text/javascript" src="../../admin/DataTables/datatables.min.js"></script>
	<script type="text/javascript" src="../../admin/DataTables/dataTables.bootstrap.min.js"></script>
	<script type="text/javascript" src="../../admin/DataTables/dataTables.fixedColumns.min.js"></script>
	<script type="text/javascript" src="../../admin/DataTables/dataTables.buttons.min.js"></script>
	<script type="text/javascript" src="../../lib/ui/jquery-ui-1.13.2.min.js"></script>
	<script>
	function check_all_chk(table){
		var rows = table.rows().nodes();
		var all = $('input[type="checkbox"]', rows).length;
		var checked = $('input[type="checkbox"]:checked', rows).length;
		console.log(all+' '+checked);
		if(all == checked){
			$('#chk_all_cus').prop('checked', true);
		}else{
			$('#chk_all_cus').prop('checked', false);
		}
	}
$(document).ready(function (){
	$('#datepicker_end').datepicker({dateFormat: 'dd/mm/yy', changeMonth: true, changeYear: true, showOtherMonths: true, showStatus: true,selectOtherMonths: true, });
    $('#datepicker_start').datepicker({dateFormat: 'dd/mm/yy', changeMonth: true, changeYear: true, showOtherMonths: true, showStatus: true,selectOtherMonths: true, });
	var table = $('.tbl_cus').DataTable({
			'columnDefs': [{
			'targets': 0,
			'searchable': false,
			'orderable': false,
			'className': 'dt-body-center',
			}],			
			'pagingType': 'full_numbers',
			"bLengthChange": false,
			"searching": true,
			"order": [[1, 'desc']],
   });

   // Handle click on "Select all" control
	$('#chk_all_cus').on('click', function(){
		var rows = table.rows({ 'search': 'applied' }).nodes();
		$('input[type="checkbox"]', rows).prop('checked', this.checked);
		check_all_chk(table);
	});

	$('.tbl_cus tbody').on('change', 'input[type="checkbox"]', function(){
		check_all_chk(table);
	});
	

   // Handle form submission event
	$('#frm-sub').on('submit', function(e){
		var rows = table.rows().nodes();

		if ($('[name="title"]').val()==''){
			e.preventDefault();
			var err_msg = <?php echo json_encode( get_lng($_SESSION["lng"], "W9001"));?>;
			alert(err_msg);
		}else if($('[name="detail"]').val()==''){
			e.preventDefault();
			alert(<?php echo json_encode(get_lng($_SESSION["lng"], "W9002"));?>);
		}else if($('input[type="checkbox"]:checked', rows).length == 0){
			e.preventDefault();
			alert(<?php echo json_encode(get_lng($_SESSION["lng"], "W9003"));?>);
		}else if($('#datepicker_start').val() == '' ){
			e.preventDefault();
			alert(<?php echo json_encode(get_lng($_SESSION["lng"], "W9004"));?>);
		}else if($('#datepicker_end').val() == '' ){
			e.preventDefault();
			alert(<?php echo json_encode(get_lng($_SESSION["lng"], "W9005"));?>);
		}else if($('[name="updateby"]').val() == '' ){
			e.preventDefault();
			alert(<?php echo json_encode(get_lng($_SESSION["lng"], "W9006"));?>);
		}else if($('[name="user_image"]').val() == ''){
			e.preventDefault();
			alert(<?php echo json_encode(get_lng($_SESSION["lng"], "W9007"));?>);
		}
		var form = this;
		table.$('input[type="checkbox"]').each(function(){
			if(!$.contains(document, this)){
            // If checkbox is checked
				if(this.checked){
					$(form).append(
						$('<input>')
						 .attr('type', 'hidden')
						 .attr('name', this.name)
						 .attr('test','test')
						 .val(this.value)
					);
				}
			}
		});
	});
	$("#DataTables_Table_0_info").parent().attr('class', 'col-sm-3');
	$("#DataTables_Table_0_paginate").parent().attr('class', 'col-sm-9');
	check_all_chk(table);

});

	</script>
</html>


