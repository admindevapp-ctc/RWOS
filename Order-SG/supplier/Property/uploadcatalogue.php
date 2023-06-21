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
			  	$_GET['current']="supCatalogue";
				include("supnavAdm.php");
			  ?>
              </div>
        </div>
        <div id="twocolRight">
       	  <h3>Supplier Upload</h3>
       	  
       	    
            <?php
               include "../../db/conn.inc";
			   include "../../admin/db/Quick_CSV_import.php";
			   $query='delete from supcataloguetemp';
			   mysqli_query($msqlcon,$query);
			   if(isset($_POST['submit'])){
                $rdfirstrow=$_POST['rdfirstrow'];
                $rdreplace=$_POST['rdreplace'];
                $userfile=$_FILES['userfile']['name'];
                $ext = strtolower(end(explode('.', $userfile)));
                if($ext!='csv'){
                    Echo get_lng($_SESSION["lng"], "E0068");// Error File Type, Only allow CSV File";	
                }else{
                    //Create Table
                    $csv = new Quick_CSV_import();
                    $csv->file_name = $_FILES['userfile']['tmp_name'];
                    $csv->table_name='supcataloguetemp';            
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
                     $ctc_csv->table_name='supcataloguetemp';     
                     $ctc_csv->import();

                     $qc2="SELECT * FROM supcataloguetemp "
                        . " group by 1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17  having count(*) > 1";
                     $sqlqc2=mysqli_query($msqlcon,$qc2);
                     $arqc2=mysqli_fetch_array($sqlqc2);
                     if($arqc2[0]){
                        echo '<form method="POST" enctype="multipart/form-data" action="../supimportPartCate.php">';
                        echo '<input type="submit" name="nobtn" value="'. get_lng($_SESSION["lng"], "L0521") .'">';
                        echo '</form>';
                        echo '<br>';
                        echo '<table class="tbl1" >';
                        echo '<tr class="arial11grey" bgcolor="#AD1D36" >';
                        echo '<th width="5%" align="center">'. get_lng($_SESSION["lng"], "L0501") .'</th><th width="5%" align="center">'. get_lng($_SESSION["lng"], "L0502") .'</th>';
                        echo '<th width="5%" align="center">'. get_lng($_SESSION["lng"], "L0518") .'</th><th width="5%" align="center">'. get_lng($_SESSION["lng"], "L0504") .'</th>';
                        echo '<th width="5%" align="center">'. get_lng($_SESSION["lng"], "L0505") .'</th><th width="5%" align="center">'. get_lng($_SESSION["lng"], "L0506") .'</th>';
                        echo '<th width="5%" align="center">'. get_lng($_SESSION["lng"], "L0352") .'</th><th width="5%" align="center">'. get_lng($_SESSION["lng"], "L0353") .'</th>';
                        echo '<th width="5%" align="center">'. get_lng($_SESSION["lng"], "L0509") .'</th> <th width="5%" align="center">'. get_lng($_SESSION["lng"], "L0510") .'</th>' ;
                        echo '<th width="5%" align="center">'. get_lng($_SESSION["lng"], "L0513") .'</th> <th width="5%" align="center">'. get_lng($_SESSION["lng"], "L0511") .'</th> ';
                        echo '<th width="5%" align="center">'. get_lng($_SESSION["lng"], "L0503") .'</th> <th width="5%" align="center">'. get_lng($_SESSION["lng"], "L0514") .'</th>' ;
                        echo '<th width="5%" align="center">'. get_lng($_SESSION["lng"], "L0512") .'</th> <th width="5%" align="center">'. get_lng($_SESSION["lng"], "L0517") .'</th>' ;
                        echo '<th width="5%" align="center">'. get_lng($_SESSION["lng"], "L0516") .'</th></tr>';
                        $qa2="SELECT * FROM supcataloguetemp  LIMIT 10";
                        //echo $qa2."<br/><br/>";
                        $sqlqa2=mysqli_query($msqlcon,$qa2);
                        while($arrqa2=mysqli_fetch_array($sqlqa2)){
                            $vcarmaker      =$arrqa2['CarMaker'];
                            $vmodelname     =$arrqa2['ModelName'];
                            $vprtpic        =$arrqa2['PrtPic'];
                            $vmodelcode     =$arrqa2['ModelCode'];
                            $venginecode    =$arrqa2['EngineCode'];
                            $vcc            =$arrqa2['CC'];
                            $vstart         =$arrqa2['StartDate'];
                            $vend           =$arrqa2['EndDate'];
                            $vcprtn         =$arrqa2['Genuine Part No'];
                            $vprtno         =$arrqa2['SupplierGenuine  Part No'];
                            $vordprtno      =$arrqa2['Order Part No'];
                            $vprtnm         =$arrqa2['PartName'];
                            $vvincode        =$arrqa2['VINCode'];
                            $vlotsize       =$arrqa2['LotSize'];
                            $vbrand         =$arrqa2['ProductBrand'];
                            $vmto           =$arrqa2['MTO'];
                            $vremark        =$arrqa2['Remark'];
                            echo "<tr class=\"arial11black\">
                                <td>$vcarmaker</td>
                                <td>$vmodelname</td>
                                <td>$vprtpic</td>
                                <td>$vmodelcode</td>
                                <td>$venginecode</td>
                                <td>$vcc</td>
                                <td>$vstart</td>
                                <td>$vend</td>
                                <td>$vcprtn</td>
                                <td>$vprtno</td>
                                <td>$vordprtno</td>
                                <td>$vprtnm</td>
                                <td>$vvincode</td>
                                <td>$vlotsize</td>
                                <td>$vbrand</td>
                                <td>$vmto</td>
                                <td>$vremark</td>
                            </tr>";
                        }
                        echo "</table>"; 
                        echo "<br/><span class='arial21redbold' width='200px'> ".get_lng($_SESSION["lng"], "E0069")."</span><br/>";
                        echo "<br/><span class='arial21redbold' width='200px'>".get_lng($_SESSION["lng"], "E0070")."</span>";
                    }
                    else{
                        /*
                        // Check Dup by partno 
                        $qcdup="SELECT * FROM supcataloguetemp "
                            . " group by `SupplierGenuine P/NO` having count(*) > 1";
                        $sqlqcdup=mysqli_query($msqlcon,$qcdup);
                        $arqcdup=mysqli_fetch_array($sqlqcdup);

                        if($arqcdup[0]){
                            echo '<form method="POST" enctype="multipart/form-data" action="../supimportPartCate.php">';
                            echo '<input type="submit" name="nobtn" value="Upload again">';
                            echo '</form>';
                            echo '<br>';
                            echo '<table class="tbl1" >';
                            echo '<tr class="arial11grey" bgcolor="#AD1D36" >';
                            echo '<th width="5%" align="center">CarMaker</th><th width="5%" align="center">ModelName</th>';
                            echo '<th width="5%" align="center">PrtPic</th><th width="5%" align="center">ModelCode</th>';
                            echo '<th width="5%" align="center">EngineCode</th><th width="5%" align="center">Cc</th>';
                            echo '<th width="5%" align="center">StartDate</th><th width="5%" align="center">EndDate</th>';
                            echo '<th width="5%" align="center">GenuineP/NO</th> <th width="5%" align="center">SupplierGenuine P/NO</th>' ;
                            echo '<th width="5%" align="center">OrderP/NO</th> <th width="5%" align="center">PartName</th> ';
                            echo '<th width="5%" align="center">Vincode</th> <th width="5%" align="center">Lotsize</th>' ;
                            echo '<th width="5%" align="center">Brand</th> <th width="5%" align="center">MTO</th>' ;
                            echo '<th width="5%" align="center">Remark</th></tr>';
                            $qa2="SELECT * FROM supcataloguetemp  LIMIT 10";
                            //echo $qa2."<br/><br/>";
                            $sqlqa2=mysqli_query($msqlcon,$qa2);
                            while($arrqa2=mysqli_fetch_array($sqlqa2)){
                                $vcarmaker      =$arrqa2['CarMaker'];
                                $vmodelname     =$arrqa2['ModelName'];
                                $vprtpic        =$arrqa2['PrtPic'];
                                $vmodelcode     =$arrqa2['ModelCode'];
                                $venginecode    =$arrqa2['EngineCode'];
                                $vcc            =$arrqa2['CC'];
                                $vstart         =$arrqa2['StartDate'];
                                $vend           =$arrqa2['EndDate'];
                                $vcprtn         =$arrqa2['GenuineP/NO'];
                                $vprtno         =$arrqa2['SupplierGenuine P/NO'];
                                $vordprtno      =$arrqa2['OrderP/NO'];
                                $vprtnm         =$arrqa2['PartName'];
                                $vvincode        =$arrqa2['VinCode'];
                                $vlotsize       =$arrqa2['LotSize'];
                                $vbrand         =$arrqa2['ProductBrand'];
                                $vmto           =$arrqa2['MTO'];
                                $vremark        =$arrqa2['Remark'];
                                echo "<tr class=\"arial11black\">
                                    <td>$vcarmaker</td>
                                    <td>$vmodelname</td>
                                    <td>$vprtpic</td>
                                    <td>$vmodelcode</td>
                                    <td>$venginecode</td>
                                    <td>$vcc</td>
                                    <td>$vstart</td>
                                    <td>$vend</td>
                                    <td>$vcprtn</td>
                                    <td>$vprtno</td>
                                    <td>$vordprtno</td>
                                    <td>$vprtnm</td>
                                    <td>$vvincode</td>
                                    <td>$vlotsize</td>
                                    <td>$vbrand</td>
                                    <td>$vmto</td>
                                    <td>$vremark</td>
                                </tr>";
                            }
                            echo "</table>"; 
                            echo "<br/><span class='arial21redbold' width='200px'> ".str_replace("<br/>","", get_lng($_SESSION["lng"], "E0011")).", Please Recheck again</span><br/>";
                            echo "<br/><span class='arial21redbold' width='200px'>Note : Data not upload to system</span>";
                        }
                        else{
                        */
                            //Check key cannot null
                            // Check Dup by partno 
                            $sqlchknull="SELECT * FROM supcataloguetemp "
                                . " where `CarMaker` = ''  or `ModelName` = '' "
                                . " or `ModelCode` = ''  or `PartName` = ''"
                                . " or `ProductBrand` = '' or `Order Part No` = '' or `LotSize` = ''  ";
                            $querynotnull=mysqli_query($msqlcon,$sqlchknull);
                            
                            $rowcount=mysqli_num_rows($querynotnull);
                            if($rowcount>0){

                                echo '<form method="POST" enctype="multipart/form-data" action="../supimportPartCate.php">';
                                echo '<input type="submit" name="nobtn" value="'. get_lng($_SESSION["lng"], "L0521") .'">';
                                echo '</form>';
                                echo '<br>';
                                echo '<table class="tbl1" >';
                                echo '<tr class="arial11grey" bgcolor="#AD1D36" >';
                                echo '<th width="5%" align="center">'. get_lng($_SESSION["lng"], "L0501") .'</th><th width="5%" align="center">'. get_lng($_SESSION["lng"], "L0502") .'</th>';
                                echo '<th width="5%" align="center">'. get_lng($_SESSION["lng"], "L0518") .'</th><th width="5%" align="center">'. get_lng($_SESSION["lng"], "L0504") .'</th>';
                                echo '<th width="5%" align="center">'. get_lng($_SESSION["lng"], "L0505") .'</th><th width="5%" align="center">'. get_lng($_SESSION["lng"], "L0506") .'</th>';
                                echo '<th width="5%" align="center">'. get_lng($_SESSION["lng"], "L0352") .'</th><th width="5%" align="center">'. get_lng($_SESSION["lng"], "L0353") .'</th>';
                                echo '<th width="5%" align="center">'. get_lng($_SESSION["lng"], "L0509") .'</th> <th width="5%" align="center">'. get_lng($_SESSION["lng"], "L0510") .'</th>' ;
                                echo '<th width="5%" align="center">'. get_lng($_SESSION["lng"], "L0513") .'</th> <th width="5%" align="center">'. get_lng($_SESSION["lng"], "L0511") .'</th> ';
                                echo '<th width="5%" align="center">'. get_lng($_SESSION["lng"], "L0503") .'</th> <th width="5%" align="center">'. get_lng($_SESSION["lng"], "L0514") .'</th>' ;
                                echo '<th width="5%" align="center">'. get_lng($_SESSION["lng"], "L0512") .'</th> <th width="5%" align="center">'. get_lng($_SESSION["lng"], "L0517") .'</th>' ;
                                echo '<th width="5%" align="center">'. get_lng($_SESSION["lng"], "L0516") .'</th></tr>';
                                $qa2="SELECT  CASE WHEN `CarMaker` = '' THEN 'CarMaker, ' 
                                WHEN `ModelName` = '' THEN 'ModelName, ' 
                                WHEN `ModelCode` = '' THEN 'ModelCode, ' 
                                WHEN `PartName` = '' THEN 'PartName, ' 
                                WHEN `ProductBrand` = '' THEN 'ProductBrand, ' 
                                WHEN `Order Part No` = '' THEN 'Order Part No, '
                                WHEN `LotSize` = '' THEN 'LotSize, '
                                ELSE '' END as 'Columnname',supcataloguetemp.* FROM supcataloguetemp  LIMIT 10";
                                //echo $qa2."<br/><br/>";
                                $sqlqa2=mysqli_query($msqlcon,$qa2);
                                $Columnnull = "";
                                while($arrqa2=mysqli_fetch_array($sqlqa2)){
                                    $Columnnull .= $arrqa2['Columnname'] . ' ';
                                    $vcarmaker      =$arrqa2['CarMaker'];
                                    $vmodelname     =$arrqa2['ModelName'];
                                    $vprtpic        =$arrqa2['PrtPic'];
                                    $vmodelcode     =$arrqa2['ModelCode'];
                                    $venginecode    =$arrqa2['EngineCode'];
                                    $vcc            =$arrqa2['CC'];
                                    $vstart         =$arrqa2['StartDate'];
                                    $vend           =$arrqa2['EndDate'];
                                    $vcprtn         =$arrqa2['Genuine Part No'];
                                    $vprtno         =$arrqa2['SupplierGenuine Part No'];
                                    $vordprtno      =$arrqa2['Order Part No'];
                                    $vprtnm         =$arrqa2['PartName'];
                                    $vvincode        =$arrqa2['VINCode'];
                                    $vlotsize       =$arrqa2['LotSize'];
                                    $vbrand         =$arrqa2['ProductBrand'];
                                    $vmto           =$arrqa2['MTO'];
                                    $vremark        =$arrqa2['Remark'];
                                    echo "<tr class=\"arial11black\">
                                        <td>$vcarmaker</td>
                                        <td>$vmodelname</td>
                                        <td>$vprtpic</td>
                                        <td>$vmodelcode</td>
                                        <td>$venginecode</td>
                                        <td>$vcc</td>
                                        <td>$vstart</td>
                                        <td>$vend</td>
                                        <td>$vcprtn</td>
                                        <td>$vprtno</td>
                                        <td>$vordprtno</td>
                                        <td>$vprtnm</td>
                                        <td>$vvincode</td>
                                        <td>$vlotsize</td>
                                        <td>$vbrand</td>
                                        <td>$vmto</td>
                                        <td>$vremark</td>
                                    </tr>";
                                }
                                echo "</table>"; 
                                echo "<br/><span class='arial21redbold' width='200px'> Column ". $Columnnull ." ".get_lng($_SESSION["lng"], "E0071")."</span><br/>";
                                echo "<br/><span class='arial21redbold' width='200px'>".get_lng($_SESSION["lng"], "E0070")."</span>";
                            }
                            else{
                                $sqlchklotsize = "SELECT * FROM supcataloguetemp  where `LotSize` <= 0  ";
                                $querylotsize=mysqli_query($msqlcon,$sqlchklotsize);
                                
                                $rowcount=mysqli_num_rows($querylotsize);
                                if($rowcount>0){

                                    echo '<form method="POST" enctype="multipart/form-data" action="../supimportPartCate.php">';
                                    echo '<input type="submit" name="nobtn" value="'. get_lng($_SESSION["lng"], "L0521") .'">';
                                    echo '</form>';
                                    echo '<br>';
                                    echo '<table class="tbl1" >';
                                    echo '<tr class="arial11grey" bgcolor="#AD1D36" >';

                                    echo '<th width="5%" align="center">'. get_lng($_SESSION["lng"], "L0501") .'</th><th width="5%" align="center">'. get_lng($_SESSION["lng"], "L0502") .'</th>';
                                    echo '<th width="5%" align="center">'. get_lng($_SESSION["lng"], "L0518") .'</th><th width="5%" align="center">'. get_lng($_SESSION["lng"], "L0504") .'</th>';
                                    echo '<th width="5%" align="center">'. get_lng($_SESSION["lng"], "L0505") .'</th><th width="5%" align="center">'. get_lng($_SESSION["lng"], "L0506") .'</th>';
                                    echo '<th width="5%" align="center">'. get_lng($_SESSION["lng"], "L0352") .'</th><th width="5%" align="center">'. get_lng($_SESSION["lng"], "L0353") .'</th>';
                                    echo '<th width="5%" align="center">'. get_lng($_SESSION["lng"], "L0509") .'</th> <th width="5%" align="center">'. get_lng($_SESSION["lng"], "L0510") .'</th>' ;
                                    echo '<th width="5%" align="center">'. get_lng($_SESSION["lng"], "L0513") .'</th> <th width="5%" align="center">'. get_lng($_SESSION["lng"], "L0511") .'</th> ';
                                    echo '<th width="5%" align="center">'. get_lng($_SESSION["lng"], "L0503") .'</th> <th width="5%" align="center">'. get_lng($_SESSION["lng"], "L0514") .'</th>' ;
                                    echo '<th width="5%" align="center">'. get_lng($_SESSION["lng"], "L0512") .'</th> <th width="5%" align="center">'. get_lng($_SESSION["lng"], "L0517") .'</th>' ;
                                    echo '<th width="5%" align="center">'. get_lng($_SESSION["lng"], "L0516") .'</th></tr>';
                                    $qa2="SELECT supcataloguetemp.* FROM supcataloguetemp  LIMIT 10";
                                    //echo $qa2."<br/><br/>";
                                    $sqlqa2=mysqli_query($msqlcon,$qa2);
                                    $Columnnull = "";
                                    while($arrqa2=mysqli_fetch_array($sqlqa2)){
                                        $vcarmaker      =$arrqa2['CarMaker'];
                                        $vmodelname     =$arrqa2['ModelName'];
                                        $vprtpic        =$arrqa2['PrtPic'];
                                        $vmodelcode     =$arrqa2['ModelCode'];
                                        $venginecode    =$arrqa2['EngineCode'];
                                        $vcc            =$arrqa2['CC'];
                                        $vstart         =$arrqa2['StartDate'];
                                        $vend           =$arrqa2['EndDate'];
                                        $vcprtn         =$arrqa2['Genuine Part No'];
                                        $vprtno         =$arrqa2['SupplierGenuine Part No'];
                                        $vordprtno      =$arrqa2['Order Part No'];
                                        $vprtnm         =$arrqa2['PartName'];
                                        $vvincode        =$arrqa2['VINCode'];
                                        $vlotsize       =$arrqa2['LotSize'];
                                        $vbrand         =$arrqa2['ProductBrand'];
                                        $vmto           =$arrqa2['MTO'];
                                        $vremark        =$arrqa2['Remark'];
                                        echo "<tr class=\"arial11black\">
                                            <td>$vcarmaker</td>
                                            <td>$vmodelname</td>
                                            <td>$vprtpic</td>
                                            <td>$vmodelcode</td>
                                            <td>$venginecode</td>
                                            <td>$vcc</td>
                                            <td>$vstart</td>
                                            <td>$vend</td>
                                            <td>$vcprtn</td>
                                            <td>$vprtno</td>
                                            <td>$vordprtno</td>
                                            <td>$vprtnm</td>
                                            <td>$vvincode</td>
                                            <td>$vlotsize</td>
                                            <td>$vbrand</td>
                                            <td>$vmto</td>
                                            <td>$vremark</td>
                                        </tr>";
                                    }
                                    echo "</table>"; 
                                    echo "<br/><span class='arial21redbold' width='200px'>".get_lng($_SESSION["lng"], "E0074")."</span><br/>";
                                    echo "<br/><span class='arial21redbold' width='200px'>".get_lng($_SESSION["lng"], "E0070")."</span>";
                                }
                                else{
                                    $qc1="SELECT count(*) FROM supcataloguetemp ";
                                    $sqlqc1=mysqli_query($msqlcon,$qc1);
                                    $arqc1=mysqli_fetch_array($sqlqc1);
                                    $tmpcount=$arqc1[0];
                                    echo "<div class='arial21redbold'>". get_lng($_SESSION["lng"], "L0527");
                                    echo '<form method="POST" enctype="multipart/form-data" action="replace-all-catalogue.php">';
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
                                    echo '<th width="5%" align="center">'. get_lng($_SESSION["lng"], "L0501") .'</th><th width="5%" align="center">'. get_lng($_SESSION["lng"], "L0502") .'</th>';
                                    echo '<th width="5%" align="center">'. get_lng($_SESSION["lng"], "L0518") .'</th><th width="5%" align="center">'. get_lng($_SESSION["lng"], "L0504") .'</th>';
                                    echo '<th width="5%" align="center">'. get_lng($_SESSION["lng"], "L0505") .'</th><th width="5%" align="center">'. get_lng($_SESSION["lng"], "L0506") .'</th>';
                                    echo '<th width="5%" align="center">'. get_lng($_SESSION["lng"], "L0352") .'</th><th width="5%" align="center">'. get_lng($_SESSION["lng"], "L0353") .'</th>';
                                    echo '<th width="5%" align="center">'. get_lng($_SESSION["lng"], "L0509") .'</th> <th width="5%" align="center">'. get_lng($_SESSION["lng"], "L0510") .'</th>' ;
                                    echo '<th width="5%" align="center">'. get_lng($_SESSION["lng"], "L0513") .'</th> <th width="5%" align="center">'. get_lng($_SESSION["lng"], "L0511") .'</th> ';
                                    echo '<th width="5%" align="center">'. get_lng($_SESSION["lng"], "L0503") .'</th> <th width="5%" align="center">'. get_lng($_SESSION["lng"], "L0514") .'</th>' ;
                                    echo '<th width="5%" align="center">'. get_lng($_SESSION["lng"], "L0512") .'</th> <th width="5%" align="center">'. get_lng($_SESSION["lng"], "L0517") .'</th>' ;
                                    echo '<th width="5%" align="center">'. get_lng($_SESSION["lng"], "L0516") .'</th></tr>';
                                    $qa2="SELECT * FROM supcataloguetemp  LIMIT 10";
                                    //echo $qa2."<br/><br/>";
                                    $sqlqa2=mysqli_query($msqlcon,$qa2);
                                    while($arrqa2=mysqli_fetch_array($sqlqa2)){
                                        $vcarmaker      =$arrqa2['CarMaker'];
                                        $vmodelname     =$arrqa2['ModelName'];
                                        $vprtpic        =$arrqa2['PrtPic'];
                                        $vmodelcode     =$arrqa2['ModelCode'];
                                        $venginecode    =$arrqa2['EngineCode'];
                                        $vcc            =$arrqa2['CC'];
                                        $vstart         =$arrqa2['StartDate'];
                                        $vend           =$arrqa2['EndDate'];
                                        $vcprtn         =$arrqa2['Genuine Part No'];
                                        $vprtno         =$arrqa2['SupplierGenuine Part No'];
                                        $vordprtno      =$arrqa2['Order Part No'];
                                        $vprtnm         =$arrqa2['PartName'];
                                        $vvincode        =$arrqa2['VINCode'];
                                        $vlotsize       =$arrqa2['LotSize'];
                                        $vbrand         =$arrqa2['ProductBrand'];
                                        $vmto           =$arrqa2['MTO'];
                                        $vremark        =$arrqa2['Remark'];
                                        $stdprice       =$arrqa2['StdPrice'];
                                        echo "<tr class=\"arial11black\">
                                            <td>$vcarmaker</td>
                                            <td>$vmodelname</td>
                                            <td>$vprtpic</td>
                                            <td>$vmodelcode</td>
                                            <td>$venginecode</td>
                                            <td>$vcc</td>
                                            <td>$vstart</td>
                                            <td>$vend</td>
                                            <td>$vcprtn</td>
                                            <td>$vprtno</td>
                                            <td>$vordprtno</td>
                                            <td>$vprtnm</td>
                                            <td>$vvincode</td>
                                            <td>$vlotsize</td>
                                            <td>$vbrand</td>
                                            <td>$vmto</td>
                                            <td>$vremark</td>
                                        </tr>";
                                    }
                                    echo "</table>"; 
                                }
                            }
                        }
                    //}
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



