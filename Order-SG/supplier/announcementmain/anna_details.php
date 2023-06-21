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


<?php

	//error_reporting( ~E_NOTICE );
	
	require_once '../cata_dbconfig.php';
	
	if(isset($_GET['edit_id']) && !empty($_GET['edit_id']))
	{
		$id = $_GET['edit_id'];
		$stmt_edit = $DB_con->prepare('SELECT title,detail,start,end,updateby,datetime,PrtPic FROM supannounce WHERE ID =:uid');
		$stmt_edit->execute(array(':uid'=>$id));
		$edit_row = $stmt_edit->fetch(PDO::FETCH_ASSOC);
		extract($edit_row);
	}
	else{
		
		echo "No record Found";
	}
?>



<html>
	<head>
    <title>Denso Ordering System</title>
	<meta http-equiv="X-UA-Compatible" content="IE=9, IE=10, IE=11, IE=EDGE" /> 
   	<link rel="stylesheet" type="text/css" href="../css/dnia.css">
	
	</style><!--[if IE]>
<style type="text/css"> 
#twocolLeft{ padding-top: 0px; }
#twocolRight { zoom: 1; padding-top:10px; }
</style>	
<![endif]-->
 <style type="text/css">

 </style>

</head>
	
	
<body >
<table cellpadding="0" cellspacing="0" border="0"  width="100%">
<tr>
<td width="50%" class="arial21redbold"><strong style="font-size: 30px;"> <?php echo get_lng($_SESSION["lng"], "M009"); //  Announcement Maintenance ?></strong></td>
<td width="50%"><img src="../../../images/anna.gif" width="100" height="70" align="right"></td>
<tr>
<td bgcolor="red">&nbsp; </td>
<td bgcolor="red">&nbsp; </td>
</tr>
</table>																

<table cellpadding="0" cellspacing="0" border="0"  width="100%">

  <tr class="arial11blackbold">
		<td width="2%">&nbsp;</td>
		<td width="17%"><strong><?php echo get_lng($_SESSION["lng"], "L0480"); //Title?> </strong></td>
		<td width="1%">:</td>
		<td width="80%" class="arial11"><strong><?php echo $title; ?></strong></td>
  </tr>
  <tr class="arial11blackbold">
		<td>&nbsp;</td>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
		<td class="arial11">&nbsp;</td>
  </tr>
  <tr class="arial11blackbold">
		<td>&nbsp;</td>
		<td><?php echo get_lng($_SESSION["lng"], "L0481"); //Detail?></td>
		<td>:</td>
		<td class="arial11"><?php echo $detail; ?></td>
  </tr>
  <tr class="arial11blackbold">
		<td>&nbsp;</td>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
		<td class="arial11">&nbsp;</td>
  </tr>
  <tr class="arial11blackbold">
		<td>&nbsp;</td>
		<td><?php echo get_lng($_SESSION["lng"], "L0483"); //Effective Date From ?> </td>
		<td>:</td>
		<td class="arial11"><?php echo $start; ?></td>
  </tr>

  <tr class="arial11blackbold">
		<td>&nbsp;</td>
		<td><?php echo get_lng($_SESSION["lng"], "L0484"); //EEffective Date To ?> </td>
		<td>:</td>
		<td class="arial11"><?php echo $end; ?></td>
  </tr>
  <tr class="arial11blackbold">
		<td>&nbsp;</td>
		<td><?php echo get_lng($_SESSION["lng"], "L0485"); //Update By ?> </td>
		<td>:</td>
		<td class="arial11"><?php echo $updateby; ?></td>
  </tr>
  	<tr class="arial11blackbold">
		<td>&nbsp;</td>
		<td><?php echo get_lng($_SESSION["lng"], "L0496"); //Update Date ?></td>
		<td>:</td>
		<td class="arial11"><?php echo $datetime; ?></td>
  </tr>

  <tr class="arial11blackbold">
		<td>&nbsp;</td>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
		<td class="arial11">&nbsp;</td>
  </tr>
  
    <!--
  	<tr class="arial11blackbold">
		<td>&nbsp;</td>
		<td>Genuine P/NO (Old)</td>
		<td>:</td>
		<td class="arial11"><?php echo $Custprthis; ?></td>
  </tr>
  	<tr class="arial11blackbold">
		<td>&nbsp;</td>
		<td>Genuine P/NO (Current)</td>
		<td>:</td>
		<td class="arial11"><?php echo $Cprtn; ?></td>
  </tr>
  	<tr class="arial11blackbold">
		<td>&nbsp;</td>
		<td>DENSO P/NO (Old)</td>
		<td>:</td>
		<td class="arial11"><?php echo $Prtnohis; ?></td>
  </tr>
    	<tr class="arial11blackbold">
		<td>&nbsp;</td>
		<td>DENSO P/NO (Current)</td>
		<td>:</td>
		<td class="arial11"><?php echo $Prtno; ?></td>
  </tr>
      	<tr class="arial11blackbold">
		<td>&nbsp;</td>
		<td>CG P/NO (Old)</td>
		<td>:</td>
		<td class="arial11"><?php echo $cgprtnohis; ?></td>
  </tr>
      	<tr class="arial11blackbold">
		<td>&nbsp;</td>
		<td>CG P/NO (Current)</td>
		<td>:</td>
		<td class="arial11"><?php echo $Cgprtno; ?></td>
  </tr>
      	<tr class="arial11blackbold">
		<td>&nbsp;</td>
		<td>Part Name</td>
		<td>:</td>
		<td class="arial11"><?php echo $Prtnm; ?></td>
  </tr>
      	<tr class="arial11blackbold">
		<td>&nbsp;</td>
		<td>Remark</td>
		<td>:</td>
		<td class="arial11"><?php echo $Remark; ?></td>
  </tr>
    <tr class="arial11blackbold">
	<td>&nbsp;</td>
	<td>Part Picture</td>
	<td>:</td>
	<td> <p><img src="../sup_annaimages/<?php echo $PrtPic; ?>" height="200" width="200" /></p><td>
	</tr>
	  </tr>
	    </tr>
		-->
 </table>
 
 <table cellpadding="0" cellspacing="0" border="0"  width="100%">
    <tr class="arial11blackbold">
		<td>&nbsp;</td>
		<td>&nbsp;</td>
		<td> 
		<div class = "gfg">
		<p><img draggable="false" src="../../sup_annaimages/<?php echo $PrtPic;?>" align="left"/></p>
		</div> 
		</td>
	</tr>
      <tr>
		<td bgcolor="red">&nbsp;</td>
		<td bgcolor="red">&nbsp;</td>
		<td bgcolor="red">&nbsp;</td>
   </tr>

   <tr>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
		<td><div id="footerMain1"><p style="font-size:8px" align="left">Copyright &copy; 2023 DENSO . All rights reserved</P></div></td>
   </tr>
 </table>
 
</html>
