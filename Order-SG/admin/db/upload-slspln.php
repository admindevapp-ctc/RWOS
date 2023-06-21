<?php 
session_start();
require_once('./../../../core/ctc_init.php'); // add by CTC

//if (session_is_registered('cusno'))
if(isset($_SESSION['cusno']))
{       
	if($_SESSION['redir']=='Order-SG'){
		$cusno=	$_SESSION['cusno'];
		//$cusnm=	$_SESSION['cusnm'];
		//$password=$_SESSION['password'];
		//$alias=$_SESSION['alias'];
		//$table=$_SESSION['tablename'];
		$type=$_SESSION['type'];
		//$custype=$_SESSION['custype'];
		$user=$_SESSION['user'];
		//$dealer=$_SESSION['dealer'];
		//$group=$_SESSION['group'];
	 }else{
		   echo "<script> document.location.href='../".redir."'; </script>";
	 }
}else{	
header("Location:../../login.php");
}
?>
<html>
	<head>
    <title>Denso Ordering System</title>
   	<link rel="stylesheet" type="text/css" href="../../css/dnia.css">
	</style><!--[if IE]>
<style type="text/css"> 
#twocolLeft{ padding-top: 0px; }
#twocolRight { zoom: 1; padding-top:10px; }
</style>	
<![endif]-->
<script type="text/javascript" language="javascript" src="../lib/jquery-1.4.2.js"></script>
<script>
$(function() {
		   
		   $('#frmimport').submit(function(){
			if($('#txtShpNo').val()==''){
				alert('Ship to should be filled!');
			 			return false;
				}
									 })
		   
		   })
</script>

</head>
<body>

		<?php ctc_get_logo(); ?> <!-- add by CTC -->

		<div id="mainNav">
         
			<ul>  
  				<li id="current"><a href="../maincusadm.php" target="_self">Administration</a></li>
				<li><a href="../../Profile.php" target="_self">User Profile</a></li>
  				<li ><a href="../../logout.php" target="_self">Log out</a></li>
  				  				
			</ul>
	
			
		</div> 
    	<div id="isi">
        
        <div id="twocolLeft">
        	<div class="hmenu">
        	  <div class="headerbar">Administration</div>
           	 <?
			  	$MYROOT=$_SERVER['DOCUMENT_ROOT'];
			  	$_GET['current']="mainSlsAdm";
				include("navAdm.php");
			  ?>
              </div>
            
        <div id="twocolRight">
       	  <h3>Sales Plan Upload</h3>
       	  
       	    
            <?php
               include "../../db/conn.inc";
			   include "Quick_CSV_import.php";
			   $query='delete from slsplantmp';
			   mysqli_query($msqlcon,$query);
			   if(isset($_POST['submit'])){
    				$rdfirstrow=$_POST['rdfirstrow'];
    				$rdreplace=$_POST['rdreplace'];
    				$userfile=$_FILES['userfile']['name'];
    				$ext = strtolower(end(explode('.', $userfile)));
					if($ext!='csv'){
            			Echo " Error File Type, Only allow CSV File";	
					}else{
						$ctc_csv = new CTC_CSV();

						// $csv = new Quick_CSV_import();
						// $csv->file_name = $_FILES['userfile']['tmp_name'];
						$ctc_csv->file_name = $_FILES['userfile']['tmp_name'];
						// $_FILES['userfile']['tmp_name'];  
						 //echo $csv->file_name;
						 
						//  $csv->use_csv_header = isset($_POST["use_csv_header"]);
						 $ctc_csv->use_csv_header = isset($_POST["use_csv_header"]);
  						//  $csv->field_separate_char = ',';
  						 if($rdfirstrow=='yesrow'){
							//  $csv->use_csv_header = true;
							 $ctc_csv->use_csv_header = true;
						 }else{
							//   $csv->use_csv_header = false;
							  $ctc_csv->use_csv_header = false;
						 }
						//  $csv->table_name='slsplantmp'; 						 
						 $ctc_csv->table_name='slsplantmp'; 						 
						//  $csv->import();
						 $ctc_csv->import();
						 $qc2="SELECT COUNT(*) AS tmpcount FROM slsplantmp";
                    	 $sqlqc2=mysqli_query($msqlcon,$qc2);
                    	 $arqc2=mysqli_fetch_array($sqlqc2);
                    	 $tmpcount=$arqc2['tmpcount'];
						 
						 
						 echo "<div class='arial21redbold'> Do you want to Upload data?";
						 echo '<form method="POST" enctype="multipart/form-data" action="replace-all-slsplan.php">';
                         echo '<input type="hidden" name="replace" value="'.$rdreplace.'">'; 
					     echo '<input type="submit" name="yesbtn" value="Yes">';
                    	 echo '<input type="submit" name="nobtn" value="No">';
						
                         echo '</form>';
						 echo '<br>';
						 echo ' Displayed 10 from '. $tmpcount . '  records!';
						 
                        echo '<table class="tbl1" >';
                        echo '<tr class="arial11grey" bgcolor="#AD1D36" ><th width="15%" align="center">PRODUCT</th><th width="15%" align="center">SUB PRODUCT</th>';
                        echo '<th  width="10%" align="center">BIZ Type</th><th width="10%" align="center">CUSTOMER NAME</th>';
                        echo '<th width="10%" align="center">CUST3</th> <th width="10%" align="center">Month</th>';
                        echo '<th width="15%" align="center">QTY</th>';
                        echo '<th width="15%" align="center">AMOUNT</th>';
                       echo '</tr>';
                        $qa2="SELECT * FROM slsplantmp  LIMIT 10";
                        $sqlqa2=mysqli_query($msqlcon,$qa2);
                        while($arrqa2=mysqli_fetch_array($sqlqa2)){
                            $aprod=$arrqa2['PROD'];
                            $asubprod=$arrqa2['SUBPROD'];
                            $abiztyp=$arrqa2['BIZTYP'];
                            $acusnm=$arrqa2['CUSNM'];
                            $acust3=$arrqa2['CUST3'];
                            $ayyyymm=$arrqa2['YYYYMM'];
                            $aqty=$arrqa2['QTY'];
                            $aamount=$arrqa2['AMOUNT'];
                            echo "<tr class=\"arial11black\">
                                    <td>$aprod</td>
                                    <td>$asubprod</td>
                                    <td>$abiztyp</td>
                                    <td>$acusnm</td>
                                    <td>$acust3</td>
                                    <td>$ayyyymm</td>
                                    <td align='right'>$aqty</td>
                                    <td align='right'>$aamount</td>
                                </tr>";
                        }
                        echo "</table>";
                    
                    
              
						 
						 
						 
						 
					
			   }
			   
			   
			

   
}
?>

            
   	       

          
          
           
        </div>
          
<div id="footerMain1">
	<ul>
      
     
     
      </ul>

    <div id="footerDesc">

	<p>
	Copyright © 2023 DENSO . All rights reserved  
	
  </div>
</div>

	</body>
</html>



