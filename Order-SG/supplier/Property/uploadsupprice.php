<?php 
session_start();
require_once('./../../../core/ctc_init.php'); // add by CTC

require_once('../../../language/Lang_Lib.php');
$comp = ctc_get_session_comp(); // add by CTC
$supno=$_SESSION['supno'];
if(isset($_SESSION['cusno']))
{       
	if($_SESSION['redir']=='Order-SG'){
		$cusno=	$_SESSION['cusno'];
		$type=$_SESSION['type'];
		$user=$_SESSION['user'];
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
<script type="text/javascript" language="javascript" src="../../lib/jquery-1.4.2.js"></script>

</head>
<body>

		<?php ctc_get_logo(); ?> <!-- add by CTC -->

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
			  	$_GET['current']="supmainSlsAdm";
				include("supnavAdm.php");
			  ?>
              </div>
        </div>
        <div id="twocolRight">
       	  <h3><?php echo get_lng($_SESSION["lng"], "L0528"); ?></h3>
       	  
       	    
            <?php
               include "../../db/conn.inc";
			   include "../../admin/db/Quick_CSV_import.php";
			   $query='delete from suppricetemp';
			   mysqli_query($msqlcon,$query);
			   if(isset($_POST['submit'])){
                $rdfirstrow=$_POST['rdfirstrow'];
                $rdreplace=$_POST['rdreplace'];
                $userfile=$_FILES['userfile']['name'];
                $ext = strtolower(end(explode('.', $userfile)));
                if($ext!='csv'){
                    Echo get_lng($_SESSION["lng"], "E0068");// " Error File Type, Only allow CSV File";	
                }else{
                    //Create Table
                    $csv = new Quick_CSV_import();
                    $csv->file_name = $_FILES['userfile']['tmp_name'];
                    $csv->table_name='suppricetemp';            
                    $csv->create_importtable();
                  
                    $ctc_csv = new CTC_CSV();
                    $ctc_csv->file_name = $_FILES['userfile']['tmp_name'];
                    $ctc_csv->use_csv_header = isset($_POST["use_csv_header"]);
                    $ctc_csv->line_enclose_char="'\\n'";
                    if($rdfirstrow=='yesrow'){
                        $ctc_csv->use_csv_header = true;
                    }else{
                        $ctc_csv->use_csv_header = false;
                    }                      
                     $ctc_csv->table_name='suppricetemp';     
                     $ctc_csv->import();

                        //Check Duplicate
                     $qc2="SELECT  * FROM suppricetemp group by 1,2,3,4,5 having count(*) > 1";
                     $sqlqc2=mysqli_query($msqlcon,$qc2);
                     $arqc2=mysqli_fetch_array($sqlqc2);
                    if($arqc2[0]){
                        echo '<form method="POST" enctype="multipart/form-data" action="../supimslsprice.php">';
                        echo '<input type="submit" name="nobtn" value="'. get_lng($_SESSION["lng"], "L0521") .'">';
                        echo '</form>';
                        echo '<br>';
                        
                        echo '<table class="tbl1" width="30%">';
                        echo '<tr class="arial11grey" bgcolor="#AD1D36" >';
                        echo '<th width="10%" align="center">'. get_lng($_SESSION["lng"], "L0522").'</th><th width="20%" align="center">'. get_lng($_SESSION["lng"], "L0520").'</th>';
                        echo '<th width="10%" align="center">'. get_lng($_SESSION["lng"], "L0523").'</th><th width="10%" align="center">'. get_lng($_SESSION["lng"], "L0524").'</th>';
                        echo '<th width="10%" align="center">'. get_lng($_SESSION["lng"], "L0525").'</th> </tr>';
                        $qa2="SELECT * FROM suppricetemp  LIMIT 10";
                        //echo $qa2."<br/><br/>";
                        $sqlqa2=mysqli_query($msqlcon,$qa2);
                        while($arrqa2=mysqli_fetch_array($sqlqa2)){
                           
                            $vcusno     =$arrqa2['Cusno'];
                            $vpartno    =$arrqa2['partno'];
                            $vcurr      =$arrqa2['curr'];
                            $vprice     =$arrqa2['price'];
                            $vshipto    =$arrqa2['shipto'];
                            echo "<tr class=\"arial11black\">
                                <td>$vcusno</td>
                                <td>$vpartno</td>
                                <td>$vcurr</td>
                                <td>$vprice</td>
                                <td>$vshipto</td>
                            </tr>";
                        }
                        echo "</table>"; 
                        echo "<br/><span class='arial21redbold' width='200px'>".get_lng($_SESSION["lng"], "E0069")."</span><br/>";
                        echo "<br/><span class='arial21redbold' width='200px'>".get_lng($_SESSION["lng"], "E0070")."</span>";
                    }
                    else{
                        $qcdup="SELECT  * FROM suppricetemp group by 1,2 having count(*) > 1";
                        $sqlqcdup=mysqli_query($msqlcon,$qcdup);
                        $arqcdup=mysqli_fetch_array($sqlqcdup);
                        if($arqcdup[0]){
                            echo '<form method="POST" enctype="multipart/form-data" action="../supimslsprice.php">';
                            echo '<input type="submit" name="nobtn" value="'. get_lng($_SESSION["lng"], "L0521") .'">';
                            echo '</form>';
                            echo '<br>';
                            
                            echo '<table class="tbl1" width="30%">';
                            echo '<tr class="arial11grey" bgcolor="#AD1D36" >';
                            echo '<th width="10%" align="center">'. get_lng($_SESSION["lng"], "L0522").'</th><th width="20%" align="center">'. get_lng($_SESSION["lng"], "L0520").'</th>';
                            echo '<th width="10%" align="center">'. get_lng($_SESSION["lng"], "L0523").'</th><th width="10%" align="center">'. get_lng($_SESSION["lng"], "L0524").'</th>';
                            echo '<th width="10%" align="center">'. get_lng($_SESSION["lng"], "L0525").'</th> </tr>';
                            $qa2="SELECT * FROM suppricetemp  LIMIT 10";
                            //echo $qa2."<br/><br/>";
                            $sqlqa2=mysqli_query($msqlcon,$qa2);
                            while($arrqa2=mysqli_fetch_array($sqlqa2)){
                               
                                $vcusno     =$arrqa2['Cusno'];
                                $vpartno    =$arrqa2['partno'];
                                $vcurr      =$arrqa2['curr'];
                                $vprice     =$arrqa2['price'];
                                $vshipto    =$arrqa2['shipto'];
                                echo "<tr class=\"arial11black\">
                                    <td>$vcusno</td>
                                    <td>$vpartno</td>
                                    <td>$vcurr</td>
                                    <td>$vprice</td>
                                    <td>$vshipto</td>
                                </tr>";
                            }
                            echo "</table>"; 
                            echo "<br/><span class='arial21redbold' width='200px'>".get_lng($_SESSION["lng"], "E0073") ." </span><br/>";
                            echo "<br/><span class='arial21redbold' width='200px'>".get_lng($_SESSION["lng"], "E0070")."</span>";
                        }
                        else{
                            // check null key
                            $sqlchknull="SELECT   CASE WHEN  `Cusno` = '' THEN NULL ELSE `Cusno` END 'Cusno'
                            , CASE WHEN `partno` = '' THEN NULL ELSE `partno` END 'partno'
                            , CASE WHEN `curr` = '' THEN NULL ELSE `curr` END 'curr'
                            , CASE WHEN `price` = '' THEN NULL ELSE  `price` END 'price'
                            , CASE WHEN  replace(`shipto`,'\r','') = '' THEN NULL ELSE replace(`shipto`,'\r','')  END 'shipto'
                                FROM suppricetemp 
                                where `Cusno` = '' or `partno` = ''  or  `curr` = '' or  `price` = '' or replace(`shipto`,'\r','') = ''";
                            $querynotnull=mysqli_query($msqlcon,$sqlchknull);
                            
                            $rowcount=mysqli_num_rows($querynotnull);
                            $Columnnull = "";
                            if($rowcount>0){

                                echo '<form method="POST" enctype="multipart/form-data" action="../supimslsprice.php">';
                                echo '<input type="submit" name="nobtn" value="'. get_lng($_SESSION["lng"], "L0521") .'">';
                                echo '</form>';
                                echo '<br>';
                                
                                echo '<table class="tbl1" width="30%">';
                                echo '<tr class="arial11grey" bgcolor="#AD1D36" >';
                                echo '<th width="10%" align="center">'. get_lng($_SESSION["lng"], "L0522").'</th><th width="20%" align="center">'. get_lng($_SESSION["lng"], "L0520").'</th>';
                                echo '<th width="10%" align="center">'. get_lng($_SESSION["lng"], "L0523").'</th><th width="10%" align="center">'. get_lng($_SESSION["lng"], "L0524").'</th>';
                                echo '<th width="10%" align="center">'. get_lng($_SESSION["lng"], "L0525").'</th> </tr>';
                                $qa2="SELECT  CASE WHEN `Cusno` = '' THEN 'Cusno, ' 
                                WHEN `partno` = '' THEN 'partno, ' 
                                WHEN `curr` = '' THEN 'curr, ' 
                                WHEN `price` = '' THEN 'price, ' 
                                WHEN  replace(`Shipto`,'\r','')  = '' THEN 'Shipto, ' 
                                ELSE '' END as 'Columnname', suppricetemp.* FROM suppricetemp  LIMIT 10";
                                //echo $qa2."<br/><br/>";
                                $sqlqa2=mysqli_query($msqlcon,$qa2);
                                while($arrqa2=mysqli_fetch_array($sqlqa2)){
                                    $Columnnull .= $arrqa2['Columnname'] . ' ';
                                    $vcusno     =$arrqa2['Cusno'];
                                    $vpartno    =$arrqa2['partno'];
                                    $vcurr      =$arrqa2['curr'];
                                    $vprice     =$arrqa2['price'];
                                    $vshipto    =$arrqa2['shipto'];
                                    echo "<tr class=\"arial11black\">
                                        <td>$vcusno</td>
                                        <td>$vpartno</td>
                                        <td>$vcurr</td>
                                        <td>$vprice</td>
                                        <td>$vshipto</td>
                                    </tr>";
                                }
                                echo "</table>"; 
                                echo "<br/><span class='arial21redbold' width='200px'>".$Columnnull ." ".get_lng($_SESSION["lng"], "E0071")." </span><br/>";
                                echo "<br/><span class='arial21redbold' width='200px'>".get_lng($_SESSION["lng"], "E0070")."</span>";

                            }
                            else{
                                $qc1="SELECT  count(*) FROM suppricetemp";
                                $sqlqc1=mysqli_query($msqlcon,$qc1);
                                $arqc1=mysqli_fetch_array($sqlqc1);
                                $tmpcount=$arqc1[0];
                            
                                echo "<div class='arial21redbold'> " . get_lng($_SESSION["lng"], "L0527");
                                echo '<form method="POST" enctype="multipart/form-data" action="replace-all-supprice.php">';
                                echo '<input type="hidden" name="replace" value="'.$rdreplace.'">'; 
                                echo '<input type="submit" name="yesbtn" value="'.get_lng($_SESSION["lng"], "L0473").'">';
                                echo '<input type="submit" name="nobtn" value="'.get_lng($_SESSION["lng"], "L0474").'">';
                                
                                echo '</form>';
                                echo '<br>';
                                if($tmpcount <= 10){
                                    echo ' Displayed '. $tmpcount . ' from '. $tmpcount . '  records!';
                                }
                                else{
                                    echo ' Displayed 10 from '. $tmpcount . '  records!';
                                }
                                
                                echo '<table class="tbl1" >';
                                echo '<tr class="arial11grey" bgcolor="#AD1D36" >';
                                echo '<th width="10%" align="center">'. get_lng($_SESSION["lng"], "L0522").'</th><th width="20%" align="center">'. get_lng($_SESSION["lng"], "L0520").'</th>';
                                echo '<th width="10%" align="center">'. get_lng($_SESSION["lng"], "L0523").'</th><th width="10%" align="center">'. get_lng($_SESSION["lng"], "L0524").'</th>';
                                echo '<th width="10%" align="center">'. get_lng($_SESSION["lng"], "L0525").'</th> </tr>';
                                $qa2="SELECT * FROM suppricetemp  LIMIT 10";
                                //echo $qa2."<br/><br/>";
                                $sqlqa2=mysqli_query($msqlcon,$qa2);
                                while($arrqa2=mysqli_fetch_array($sqlqa2)){
                                    $vcusno     =$arrqa2['Cusno'];
                                    $vpartno    =$arrqa2['partno'];
                                    $vcurr      =$arrqa2['curr'];
                                    $vprice     =$arrqa2['price'];
                                    $vshipto    =$arrqa2['shipto'];
                                    echo "<tr class=\"arial11black\">
                                        <td>$vcusno</td>
                                        <td>$vpartno</td>
                                        <td>$vcurr</td>
                                        <td>$vprice</td>
                                        <td>$vshipto</td>
                                    </tr>";
                                }
                                echo "</table>";
                            } 
                        }

                    }
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
