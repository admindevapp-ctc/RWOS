<? session_start() ?>
 <?
 require_once('../../language/Lang_Lib.php');
 if(isset($_SESSION['cusno']))
{       
	if($_SESSION['redir']=='Order-SG'){
		$_SESSION['cusno'];
		$_SESSION['cusnm'];
		$_SESSION['redir'];
		$_SESSION['type'];
		$_SESSION['com'];
		$_SESSION['user'];
		$_SESSION['alias'];
		$_SESSION['tablename'];
    	$_SESSION['custype'];
		$_SESSION['dealer'];
		$_SESSION['group'];		
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
	 }
}else{	
header("Location:../login.php");
}

?>


<?php

	//error_reporting( ~E_NOTICE );
	
	require_once 'cata_dbconfig.php';
	
	if(isset($_GET['edit_id']) && !empty($_GET['edit_id']))
	{
		$id = $_GET['edit_id'];
		$stmt_edit = $DB_con->prepare('SELECT CarMaker,ModelName,PrtPic,ModelCode,EngineCode,Cc,Start,End,Custprthis,Cprtn,Prtnohis,Prtno,cgprtnohis,Cgprtno,Prtnm,Remark FROM catalogue WHERE ID =:uid');
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
	
	
	
	
<!--[if IE]></style>
<style type="text/css"> 
#twocolLeft{ padding-top: 0px; }
#twocolRight { zoom: 1; padding-top:10px; }
</style>	
<![endif]-->
<style type="text/css">
gfg { 
		width:auto; 
		text-align:left; 
		padding:20px; 
			} 
			img { 
				max-width:100%; 
				height:auto; 
			} 
 </style>
</head>
	
	
<body >

<table cellpadding="0" cellspacing="2" border="0"  width="100%">
	<tr>
	<td width="3%"><img src="../images/cata.png" width="15" height="15"></td>	
	<td colspan="6" class="arial21redbold">Part Details Informaiton</td>
	</tr>
	<tr class="arial11blackbold">
		<td width="2%">&nbsp;</td>
		<td width="35%">Car Maker</td>
		<td width="1%">:</td>
		<td width="62%" class="arial11"><?php echo $CarMaker; ?></td>
	</tr>
	<tr class="arial11blackbold">
		<td>&nbsp;</td>
		<td>Model Name</td>
		<td>:</td>
		<td class="arial11"><?php echo $ModelName; ?></td>
	</tr>
	<tr class="arial11blackbold">
		<td>&nbsp;</td>
		<td>Model Code</td>
		<td>:</td>
		<td class="arial11"><?php echo $ModelCode; ?></td>
	</tr>
    </tr>
	<tr class="arial11blackbold">
		<td>&nbsp;</td>
		<td>Engine Code</td>
		<td>:</td>
		<td class="arial11"><?php echo $EngineCode; ?></td>
  </tr>
  	<tr class="arial11blackbold">
		<td>&nbsp;</td>
		<td>CC.</td>
		<td>:</td>
		<td class="arial11"><?php echo $Cc; ?></td>
  </tr>
  	<tr class="arial11blackbold">
		<td>&nbsp;</td>
		<td>Start Date</td>
		<td>:</td>
		<td class="arial11"><?php echo $Start; ?></td>
  </tr>
  	<tr class="arial11blackbold">
		<td>&nbsp;</td>
		<td>End Date</td>
		<td>:</td>
		<td class="arial11"><?php echo $End; ?></td>
  </tr>
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
    </tr>
      	<tr class="arial11blackbold">
		<td>&nbsp;</td>
		<td>Part Picture</td>
		<td>:</td>
		<td class="arial11"></td>
  </tr>
   </table>
   
   <table cellpadding="0" cellspacing="0" border="0"  width="100%">
    <tr class="arial11blackbold">
		<td>&nbsp;</td>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
		<td> 
		<div class = "gfg">
		<p><img draggable="false" src="../user_images/<?php echo $PrtPic;?>" align="left"/></p>
		</div> 
		<td>
	</tr>
      <tr>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
		<td>
		<div id="footerMain1">
		<p style="font-size:8px" align="left">Copyright &copy; 2023 DENSO . All rights reserved</P></td>
		</div>
   </tr>
 </table>
 
	</body>
</html>
