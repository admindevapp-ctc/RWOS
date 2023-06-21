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
		$comp = ctc_get_session_comp(); // add by CTC
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

<html>
	<head>
    <title>Denso Ordering System</title>
	<meta http-equiv="X-UA-Compatible" content="IE=9, IE=10, IE=11, IE=EDGE" />  <!--02/04/2018 P.Pawan CTC-->
   
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

	<link rel="stylesheet" type="text/css" href="../css/dnia.css">
    <link rel="stylesheet" href="../themes/ui-lightness/jquery-ui.css">

	<script src="../lib/jquery-1.4.2.min.js"></script>

	<script src="../lib/jquery.ui.core.js"></script>
 	<script src="../lib/jquery.ui.widget.js"></script>
     <script src="../lib/jquery.ui.mouse.js"></script>
	<script src="../lib/jquery.ui.button.js"></script>
	<script src="../lib/jquery.ui.draggable.js"></script>
	<script src="../lib/jquery.ui.position.js"></script>
	<script src="../lib/jquery.ui.resizable.js"></script>
	<script src="../lib/jquery.ui.dialog.js"></script>
	<script src="../lib/jquery.effects.core.js"></script>
     <script src="../lib/jquery.ui.datepicker.js"></script>
   <script src="../lib/jquery.ui.autocomplete.js"></script>

<script type="text/javascript" language="javascript" src="../lib/jquery-1.4.2.js"></script>
<script type="text/javascript">
$(function() {
		   

		   $('#frmimport').submit(function(){
			   if($('#userfile').val()==''){
				   alert("<?php echo get_lng($_SESSION["lng"], "E0062"); ?>");
				   return false;
			   }
	   
			   var ext = $('#userfile').val().split('.');
			   
			   if(ext[1] != "csv"){
				   alert("<?php echo get_lng($_SESSION["lng"], "E0061"); ?>");
				   return false;
			   }  
		   });
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
			  	$_GET['current']="supstockAdm";
				include("supnavAdm.php");
			  ?>
        </div>
        <div id="twocolRight">
        <?
		  require('../db/conn.inc');

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

		 $xcusno='';
		 $xprtno='';
		  if(isset($_GET["vcusno"])){
				$xcusno=$_GET["vcusno"];
				$xprtno=$_GET["vprtno"];
		  }
		 	$inpcusno= '<select name="vcusno" id="vcusno" class="arial11blue">';
			$inpcusno= $inpcusno .  ' <option value=""></option>';
			$query="select Cusno, Cusnm from cusmas where Owner_Comp='$comp' order by cusno ";
			$sql=mysqli_query($msqlcon,$query);	
			while($hasil = mysqli_fetch_array ($sql)){
				$ycusno=$hasil['Cusno'];
				$ycusnm=$hasil['Cusnm'];
				
				if(trim($ycusno)==trim($xcusno)){
				   	 $inpcusno= $inpcusno .  ' <option value="'.$ycusno.'" selected>'.$ycusno.' - '. $ycusnm. ' </option>';
				}else{
					  	 $inpcusno= $inpcusno .  ' <option value="'.$ycusno.'">'.$ycusno.' - '. $ycusnm. ' </option>';		
				}
				
			}
        			$inpcusno= $inpcusno . ' </select>';
			
			$inpprtno="<input type=\"text\" name=\"vprtno\"  value ='" . $xprtno. "' class=\"arial11black\" maxlength=\"30\" size=\"30\" ></input>";
		  ?>
		
        
		<table width="97%" border="0" cellspacing="0" cellpadding="0">
            <tr class="arial11blackbold">
                <td>&nbsp;</td>
                <td width="19%">&nbsp;</td>
                <td width="25%">&nbsp;</td>
                <td width="3%"></td>
                <td width="19%">&nbsp;</td>
                <td width="1%">&nbsp;</td>
                <td width="30%">&nbsp;</td>
            </tr>
            <tr class="arial11blackbold">
                <td width="3%"><img src="../images/calendar.gif" width="16" height="15"></td>
                <td colspan="5" class="arial21redbold"><?php echo get_lng($_SESSION["lng"], "M008"); // Stock Maintenance ?></td>
                <td align="right"><img src='<?php echo "../sup_logo/".$suplogo; ?>' height="80" width="200" /></td>
            </tr>
            <tr class="arial11blackbold">
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td></td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
            </tr>
        </table> 

        <?php echo get_lng($_SESSION["lng"], "L0468"); // Download format csv here :?>  <a href="Property/supstock.csv" target="_blank" download ><img src="../images/csv.jpg" width="16" height="16" border="0"></a>	
             <form method="POST" enctype="multipart/form-data" name="uploadForm" action='Property/uploadsupstock.php' id="frmimport">
            <table class='arial11'>
				<tr>
                    <td><?php echo get_lng($_SESSION["lng"], "L0450"); // Upload ?></td>
                    <td>:</td>
                    <td><input type="file" size="45" name="userfile" id= "userfile"></td>
                </tr>
                <tr>
                    <td><?php echo get_lng($_SESSION["lng"], "L0470"); // File type?> </td>
                    <td>:</td>
                    <td>
                        <b><?php echo get_lng($_SESSION["lng"], "L0469"); // Only CSV file Allowed! ?></b>
                    </td>
                </tr>
                <tr>
                    <td><?php echo get_lng($_SESSION["lng"], "L0471"); // First row for header ?></td>
                    <td>:</td>
                    <td>
                        <input type="radio" name="rdfirstrow" value="yesrow"> <?php echo get_lng($_SESSION["lng"], "L0473"); // Yes?>
                        <input type="radio" name="rdfirstrow" value="norow">  <?php echo get_lng($_SESSION["lng"], "L0474"); // No ?>
                    </td>
                </tr>
                <tr>
                    <td></td>
                    <td></td>
                    <td>
                        <input type="radio" name="rdreplace" value="add"> <?php echo get_lng($_SESSION["lng"], "L0475"); // Add and Replace Partial ?>
                        <input type="radio" name="rdreplace" value="editall"> <?php echo get_lng($_SESSION["lng"], "L0476"); // Replace All (Delete All first)?>
                    </td>
                </tr>
                <tr>
                    <td></td>
                    <td></td>
                    <td>
                        <input name="submit" type="submit" value="<?php echo get_lng($_SESSION["lng"], "L0477"); // Submit ?>"> 
                        <input type="reset" value="<?php echo get_lng($_SESSION["lng"], "L0478"); // Reset ?>">
                    </td>
                </tr>
            </table><br/>
        </form>

			<?
              include "../db/conn.inc";
			   $msg=$_GET['msg'];
			   $result=$_GET['result'];
				// If error upload
				
                if($result=='Error'){
                    $msgtbl="<H3>Error ".$msg."</H3><table width='100%'  class='tbl1' cellspacing='0' cellpadding='0'>
 						 <tr class='arial11grey' bgcolor='#AD1D36' >
                            <th width='15%' scope='col'>OwnerComp</th>
                            <th width='15%' scope='col'>Supno</th>
                            <th width='20%' scope='col'>Partno</th>
                            <th width='20%' scope='col'>StockQTY</th>
                        </tr>";
                    $qse="SELECT `Item Number` as partno, Qty as stockqty  FROM supstocktemp WHERE Owner_Comp='$comp' and supno '$supno' ";
					//echo $qse;
                    $sqlqse=mysqli_query($msqlcon,$qse);

					$arqc2=mysqli_fetch_array($sqlqse);
					if($arqc2[0].count > 0){
						while($arx=mysqli_fetch_array($sqlqse)){
							$partno=$arx['partno'];
							$qty=$arx['stockqty'];
							$msgtbl.="<tr class='arial11black'>
										<td>$comp</td>
										<td>$supno</td>
										<td>$partno</td>
										<td>$qty</td>
									</tr>";
						}
					}
					$msgtbl.="</table>";
					$qd="DELETE FROM supstocktemp";
					mysqli_query($msqlcon,$qd);
                    $msg=$msgtbl;
                }
                
                // If succesfully add data
                if($result=='Add'){
                    
                    $msgsuccess='Add data success';

					
                    $qa="SELECT `Item Number` as partno, Qty as stockqty  FROM supstocktemp WHERE Owner_Comp='$comp'and supno '$supno'  ";
					//echo $qa;
                    $sqlqa=mysqli_query($msqlcon,$qa);
                    while($arrqa=mysqli_fetch_array($sqlqa)){
                        $search=array("'","�");
                        $replace=array("\\'","A");
                
						$partno=$arrqa['partno'];
						$qty=$arrqa['stockqty'];
                        $qi2="Replace INTO supstock(Owner_comp, supno, partno, StockQty
                        )  VALUES('$comp', '$supno', '$partno' , '$qty')"; 
                        mysqli_query($msqlcon,$qi2);

						//echo $qi2 ."<br/>";
                     }
                    $qd="DELETE FROM supstocktemp WHERE Owner_Comp='$comp' and supno '$supno' ";
                    mysqli_query($msqlcon,$qd);
                    $msg=$msgsuccess;
				  // echo $qd;
                }
                
                // If succesfully replace partial data
              
                echo '<table width="90%" border="0" align="center" bgcolor="#AD1D36">';
				echo '<tr  class="arial11whitebold"><td align="center">';
				echo $msg;
				echo '</td></tr></table>';
            ?>
        
        </div>

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
