<?php 

session_start();
require_once('../../core/ctc_init.php'); // add by CTC
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
		$_SESSION['supno'];
		$_SESSION['supnm'];
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
		$supnm=$_SESSION['supnm'];
		$comp = ctc_get_session_comp(); // add by CTC
		if($type!='s'){
			header("Location: ../main.php");
		}
		}else{
			echo "<script> document.location.href='../../".$redir."'; </script>";
		}
	}else{	
		header("Location:../../login.php");
	}

?>

<html>
	<head>
    <title>Denso Ordering System</title>
	<meta http-equiv="X-UA-Compatible" content="IE=9, IE=10, IE=11, IE=EDGE" />  <!--02/04/2018 P.Pawan CTC-->
   	<link rel="stylesheet" type="text/css" href="../css/dnia.css">
	</style><!--[if IE]>
<style type="text/css"> 
#twocolLeft{ padding-top: 0px; }
#twocolRight { zoom: 1; padding-top:10px; }
</style>	
<![endif]-->
 <style type="text/css">
<!--

#pagination a 
{
	list-style: none;
	margin-right: 5px;
	padding:5px;
	color:#333;
	text-decoration: none;
	background-color: #F3F3F3;
	font-family: Verdana, Geneva, sans-serif;
	font-size: 10px;
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

#pagination1 a 
{
	list-style: none;
	margin-right: 5px;
	padding:5px;
	color:#333;
	text-decoration: none;
	background-color: #F3F3F3;
	font-family: Verdana, Geneva, sans-serif;
	font-size: 10px;
}
#pagination1 a:hover 
{
color:#FF0084;
cursor: pointer;
}

#pagination1 a.current 
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
<script type="text/javascript">
	$(document).ready(function() {
		
	if (performance.navigation.type == performance.navigation.TYPE_RELOAD) {
		//alert( "This page is reloaded" );
		window.location = window.location.href.split("?")[0];
	} 
		$.urlParam = function(name){
			var results = new RegExp('[\?&]' + name + '=([^&#]*)').exec(window.location.href);
			if (results == null){
			return null;
			}
			else {
			return decodeURI(results[1]);
			}
		}

		$('#ConvExcel').click(function(){
			//let searchParams = new URLSearchParams(window.location.search)
			let cuscode = $.urlParam('cuscode');
			//alert(cuscode);
			url= 'supgettblcusmasXLS.php?cuscode='+cuscode,
			window.open(url);	
					
		})						   
   });
</script>



	</head>
	<body >
   		
		<?php ctc_get_logo(); ?> <!-- add by CTC -->
		
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
			  	$_GET['current']="supmainCusAdm";
				include("supnavAdm.php");
			  ?>
        </div>
		
        <div id="twocolRight">
        <?
		    require('../db/conn.inc');
			require_once('../../language/Lang_Lib.php');
            //Supplier
			$query="select * from supmas where Owner_Comp='$comp' and supno='$supno'  ";
			$sql=mysqli_query($msqlcon,$query);	
            //echo $query;
			while($hasil = mysqli_fetch_array ($sql)){
                
				$supno=$hasil['supno'];	
				$supnm=$hasil['supnm'];
				$_SESSION['supnm'] = $supnm;
                $suplogo= $hasil['logo'];
				$inpsupcode= $supno;	
				$inpsupname= $supnm;		
				
			}
			
            // Customer
            $inpcus= '<select name="cuscode" id="cuscode" class="arial11blue" style="width:200px;">';
			$inpcus= $inpcus .   ' <option value="" selected="selected">'.get_lng($_SESSION["lng"], "L0449").'</option>';
			$query="select * from supref where Owner_Comp='$comp' and supno='$supno'  ";
			//echo $query;
            $sql=mysqli_query($msqlcon,$query);	
			while($hasil = mysqli_fetch_array ($sql)){
                $cusno=$hasil['Cusno'];	
                $vcusno=$_GET['cuscode'];
                $selected = ($hasil["Cusno"] == $vcusno) ? "selected" : "";
				$inpcus= $inpcus .  ' <option  '.$selected.' value="'.$cusno.'">'.$cusno.'</option>';			
				
			}
        	$inpcus= $inpcus . ' </select>';

		 ?>

        <table width="100%" border="0" cellspacing="0" cellpadding="0" >
            <tr class="arial11blackbold" style="vertical-align: top;">
                <td width="3%"><img src="../images/calendar.gif" width="16" height="15"></td>
                <td width="10%" class="arial21redbold"><?php echo get_lng($_SESSION["lng"], "M005");?></td>
                <td width="10%">&nbsp;</td>
                <td width="10%">&nbsp;</td>
                <td width="20%">&nbsp;</td>
                <td rowspan="3" align="right" width="47%"><img src='<?php echo "../sup_logo/".$suplogo; ?>'  height="80" width="200" style=""/></td>
            </tr>
			<tr class="arial11blackbold">
				<td colspan="2">
					<span class="arial12BoldGrey"><?php echo get_lng($_SESSION["lng"], "L0451");?></span>
					<span class="arial12Bold">:</span>
				</td>
				<td>
					<span class="arial12Bold"><? echo $supno?></span>	
				</td>
				<td>
					<span class="arial12BoldGrey"><?php echo get_lng($_SESSION["lng"], "L0452");?></span>
					<span class="arial12Bold">:</span> 
				</td>
                <td>
					<span class="arial12Bold"align="left"><? echo $supnm?></span>	
				</td>
       	 	</tr>
            <tr class="arial11blackbold">
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
            </tr>
        </table>    
    <form name ="searchprice" method="get">
    <fieldset>
    <legend> &nbsp;<?php echo get_lng($_SESSION["lng"], "L0456");?></legend>
        <table width="100%" border="0" cellspacing="0" cellpadding="0">
        
        <tr class="arial11blackbold">
            <td width="10%"><div align="right"><span class="arial12BoldGrey"><?php echo get_lng($_SESSION["lng"], "L0003");?></span></div></td>
            <td width="2%"><div align="center"><span class="arial12Bold">:</span></div></td>
            <td width="18%"><span class="arial12Bold"><? echo $inpcus ?></span></td>
            <td width="7%"><input align="right" type="submit" name="button" id="button" value="<?php echo get_lng($_SESSION["lng"], "L0105");?>" class="arial11"></td>
            <td width="73%"></td>
        </tr>
        <tr class="arial11blackbold">
            <td >&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td></td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
        </tr>
        </table>
    </fieldset>
    </form>

<table width="100%" border="0" cellspacing="0" cellpadding="0">
	<tr >
		<th width="10%" height="30" scope="col"></th>
		<th width="10%" height="30" scope="col"></th>
		<th width="10%" height="30" scope="col"></th>
		<th width="10%" scope="col"></th>
		<th width="20%" scope="col"></th>
		<th width="20%" scope="col"></th>
		<th width="10%" scope="col" align="right">
			<!--<a href="#" id="ConvExcel"><img src="../images/export.png" width="101" height="25" border="0"></a>-->
			<div id="ConvExcel" style='background-color: #AD1D36;font-size:9pt;color: #FFFFFF;height:22px;cursor:pointer;width:150px;'>
				<img src="../images/excel.jpg" width="18" height="18" style='float:left;margin-left:20px;margin-top:1px;'>
				<font style='margin-right:18px;line-height:22px;'><?php echo get_lng($_SESSION["lng"], "L0346"); ?></font>
			</div>
		</th>
 	</tr>
    <tr class="arial11blackbold">
        <th width="10%" height="10" scope="col"></th>
		<th width="10%" height="10" scope="col"></th>
		<th width="10%" height="10" scope="col"></th>
		<th width="10%" scope="col"></th>
		<th width="20%" scope="col"></th>
		<th width="20%" scope="col"></th>
		<th width="10%" scope="col"></th>
    </tr>
</table>
<table width="100%"  class="tbl1" cellspacing="0" cellpadding="0">
  <tr class="arial11whitebold" bgcolor="#AD1D36" >
  	<th width="10%" height="30" scope="col"><?php echo get_lng($_SESSION["lng"], "L0457"); ?></th>
  	<th width="10%" height="30" scope="col"><?php echo get_lng($_SESSION["lng"], "L0458"); ?></th>
  	<th width="10%" height="30" scope="col"><?php echo get_lng($_SESSION["lng"], "L0459"); ?></th>
    <th width="10%" scope="col"><?php echo get_lng($_SESSION["lng"], "L0460"); ?></th>
    <th width="20%" scope="col"><?php echo get_lng($_SESSION["lng"], "L0461"); ?></th>
    <th width="20%" scope="col"><?php echo get_lng($_SESSION["lng"], "L0462"); ?></th>
    <th width="20%" scope="col"><?php echo get_lng($_SESSION["lng"], "L0463"); ?></th>
    </tr>
    
     <?
		  require('../db/conn.inc');
		  
		  $per_page=10;
		  $num=5;
		  
	$query="select supref.Owner_Comp, supref.cusno, shiptoma.ship_to_cd, cusmas.Cusnm, shiptoma.adrs1
    , shiptoma.adrs2, shiptoma.adrs3 from supref join cusmas on supref.cusno = cusmas.cusno 
	and supref.Owner_Comp = cusmas.Owner_Comp
    left join shiptoma on supref.cusno = shiptoma.cusno and shiptoma.Owner_Comp = supref.Owner_Comp";
    
    $condition = " where supref.Owner_Comp = '$comp' and supref.supno = '$supno'"; 

    $vcuscode=trim($_GET['cuscode']);
    if($vcuscode!=''){
        $condition .= "  and supref.Cusno = '$vcuscode'";
    }

    $condition .= " order by supref.cusno, shiptoma.ship_to_cd";
    $query = $query . $condition;
   //echo $query ."<br/><br/>";
	$sql=mysqli_query($msqlcon,$query);
	$count = mysqli_num_rows($sql);
	
	$pages = ceil($count/$per_page);
	$page = $_GET['page'];
	if($page){ 
		$start = ($page - 1) * $per_page; 			
	}else{
		$start = 0;	
		$page=1;
	}

	$query1="select supref.Owner_Comp, supref.cusno, shiptoma.ship_to_cd, cusmas.Cusnm, shiptoma.adrs1
    , shiptoma.adrs2, shiptoma.adrs3 from supref join cusmas on supref.cusno = cusmas.cusno 
	and supref.Owner_Comp = cusmas.Owner_Comp
    left join shiptoma on supref.cusno = shiptoma.cusno and shiptoma.Owner_Comp = supref.Owner_Comp ";
    
    $query1 = $query1 . $condition . " LIMIT $start, $per_page";
    //echo $query1;
	$sql=mysqli_query($msqlcon,$query1);	
		
	if( ! mysqli_num_rows($sql) ) {
		echo "<tr height=\"30\"><td colspan=\"7\" align=\"center\" class=\"arial12BoldGrey\">" . get_lng($_SESSION["lng"], "E0060") /*No Data Found.... */ . "</td></tr>";
	}
			while($hasil = mysqli_fetch_array ($sql)){
				$vowner=$hasil['Owner_Comp'];
				$vcusno=$hasil['cusno'];
				$vcusnm=$hasil['Cusnm'];
				$vshipto=$hasil['ship_to_cd'];
				$vaddr1=$hasil['adrs1'];
				$vaddr2=$hasil['adrs2'];
				$vaddr3=$hasil['adrs3'];
				
            echo "<tr class=\"arial11black\" align=\"center\" height=\"30\">";
            echo "<td>".$vowner."</td><td>".$vcusno."</td>";
            echo "<td>".$vshipto."</td><td>".$vcusnm."</td>" ;
			echo "<td>".$vaddr1."</td><td>".$vaddr2."</td>";
			echo "<td class=\"lasttd\">".$vaddr3."<td ></tr>";
			
			}
			
			require('pager.php');
		if($count>$per_page){		
		  	echo "<tr height=\"30\"><td colspan=\"7\" align=\"right\" class=\"lasttd\"><div id=\"pagination\" >";
		  	//echo $query;
		  	$fld="page";
		  	paging($query,$per_page,$num,$page);
		  	echo "</div></td></tr>";
		}
		
		
		  ?>
 
</table>
<div id="result" class="arial11redbold" align="center"> </div>
<p>



              
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
