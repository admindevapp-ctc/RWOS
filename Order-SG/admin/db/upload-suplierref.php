<?php 
session_start();
require_once('./../../../core/ctc_init.php'); // add by CTC

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
			  	$_GET['current']="sup_mainref";
				include("navAdm.php");
			  ?>
              </div>
            
        <div id="twocolRight">
       	  <h3>Supplier Upload</h3>
       	  
       	    
            <?php
               include "../../db/conn.inc";
			   include "Quick_CSV_import.php";
			   $query='delete from supreftemp';
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


                   //Create Table
                    $csv = new Quick_CSV_import();
                    $csv->file_name = $_FILES['userfile']['tmp_name'];
                    $csv->table_name='supreftemp';            
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
                     $ctc_csv->table_name='supreftemp';     
                     $ctc_csv->import();

                     //Check Duplicate

                     $qc2="SELECT * FROM supreftemp group by 1,2,3 having count(*) > 1";
                     $sqlqc2=mysqli_query($msqlcon,$qc2);
                     $arqc2=mysqli_fetch_array($sqlqc2);

                     if($arqc2[0]){
                        echo '<form method="POST" enctype="multipart/form-data" action="../supref_import.php">';
                        echo '<input type="hidden" name="replace" value="'.$rdreplace.'">'; 
                        echo '<input type="submit" name="nobtn" value="Upload again">';
                        
                        echo '</form>';
                        echo '<br>';
                        
                        echo '<table class="tbl1" >';
                        echo '<tr class="arial11grey" bgcolor="#AD1D36" >';
                        echo '<th width="20%" align="center">SupplierNo</th>';
                        echo '<th width="20%" align="center">CustomerNo</th>';
                        echo '<th  width="20%" align="center">Shipto</th></tr>';
                        $qa2="SELECT `Customer Number` as CustomerNo ,SupplierNo, Shipto FROM supreftemp  LIMIT 10";
                        //echo $qa2;
                        $sqlqa2=mysqli_query($msqlcon,$qa2);
                        while($arrqa2=mysqli_fetch_array($sqlqa2)){
                            $SupplierNo=$arrqa2['SupplierNo'];
                            $CustomerNo=$arrqa2['CustomerNo'];
                            $Shipto=$arrqa2['Shipto'];
                            echo "<tr class=\"arial11black\">
                                    <td>$SupplierNo</td>
                                    <td>$CustomerNo</td>
                                    <td>$Shipto</td>
                                </tr>";
                        }
                        echo "</table>"; 
                        echo "<br/><span class='arial21redbold' width='200px'>Duplicate Data , Please Recheck again</span><br/>";
                        echo "<br/><span class='arial21redbold' width='200px'>Note : Data not upload to system</span>";
                        
                     }
                     else{
                         // check Dup by key
                        $qcdup="SELECT * FROM supreftemp group by 1,2 having count(*) > 1";
                        $sqlqcdup=mysqli_query($msqlcon,$qcdup);
                        $arqcdup=mysqli_fetch_array($sqlqcdup);
                        if($arqcdup[0]){
                            echo '<form method="POST" enctype="multipart/form-data" action="../supref_import.php">';
                            echo '<input type="hidden" name="replace" value="'.$rdreplace.'">'; 
                            echo '<input type="submit" name="nobtn" value="Upload again">';
                            
                            echo '</form>';
                            echo '<br>';
                            
                            echo '<table class="tbl1" >';
                            echo '<tr class="arial11grey" bgcolor="#AD1D36" >';
                            echo '<th width="20%" align="center">SupplierNo</th>';
                            echo '<th width="20%" align="center">CustomerNo</th>';
                            echo '<th  width="20%" align="center">Shipto</th></tr>';
                            $qa2="SELECT `Customer Number` as CustomerNo ,SupplierNo, Shipto FROM supreftemp  LIMIT 10";
                            //echo $qa2;
                            $sqlqa2=mysqli_query($msqlcon,$qa2);
                            while($arrqa2=mysqli_fetch_array($sqlqa2)){
                                $SupplierNo=$arrqa2['SupplierNo'];
                                $CustomerNo=$arrqa2['CustomerNo'];
                                $Shipto=$arrqa2['Shipto'];
                                echo "<tr class=\"arial11black\">
                                        <td>$SupplierNo</td>
                                        <td>$CustomerNo</td>
                                        <td>$Shipto</td>
                                    </tr>";
                            }
                            echo "</table>"; 
                            echo "<br/><span class='arial21redbold' width='200px'>Duplicate SupplierNo and CustomerNo, Please Recheck again</span><br/>";
                            echo "<br/><span class='arial21redbold' width='200px'>Note : Data not upload to system</span>";
                        }
                        else {
                            //Join shiptoma
                            $sql="SELECT `Customer Number` as CustomerNo ,SupplierNo, Shipto ,shiptoma.Cusno
                            FROM supreftemp  
                                left join shiptoma on supreftemp.`Customer Number` = shiptoma.Cusno
                                and replace(supreftemp.Shipto,'\r','') = shiptoma.ship_to_cd
                            where shiptoma.Cusno is null";
                            $query=mysqli_query($msqlcon,$sql);
                            $row=mysqli_num_rows($query);
                            if( $row > 0){
                                
                                echo '<form method="POST" enctype="multipart/form-data" action="../supref_import.php">';
                                echo '<input type="hidden" name="replace" value="'.$rdreplace.'">'; 
                                echo '<input type="submit" name="nobtn" value="Upload again">';
                                echo '</form>';
                                echo '<br>';
                                $cusnonotinshipto = "";
                                echo '<table class="tbl1" >';
                                echo '<tr class="arial11grey" bgcolor="#AD1D36" >';
                                echo '<th width="20%" align="center">SupplierNo</th>';
                                echo '<th width="20%" align="center">CustomerNo</th>';
                                echo '<th  width="20%" align="center">Shipto</th></tr>';
                                while($fetchdata1=mysqli_fetch_array($query)){
                                    $SupplierNo=$fetchdata1['SupplierNo'];
                                    $CustomerNo=$fetchdata1['CustomerNo'];
                                    $Shipto=$fetchdata1['Shipto'];
                                    $cusnonotinshipto .=  $CustomerNo .",";
                                    echo "<tr class=\"arial11black\">
                                            <td>$SupplierNo</td>
                                            <td>$CustomerNo</td>
                                            <td>$Shipto</td>
                                        </tr>";
                                }
                                echo "</table>"; 
                                echo "<br/><span class='arial21redbold' width='200px'>Customer number ".substr_replace($cusnonotinshipto ,"",-1)." Don’t have shipto,Please Recheck Again </span><br/>";
                                echo "<br/><span class='arial21redbold' width='200px'>Note : Data not upload to system</span>";
                            }
                            else{

                                //check null key 
                                $checknull="SELECT  CASE WHEN `SupplierNo` = '' THEN 'SupplierNo, ' 
                                WHEN `Customer Number` = '' THEN 'Customer Number, ' 
                                WHEN  replace(`Shipto`,'\r','')  = '' THEN 'Shipto, ' 
                                ELSE '' END as 'Columnname'
                                , CASE WHEN `SupplierNo` = '' THEN NULL ELSE `SupplierNo` END 'SupplierNo'
                                , CASE WHEN `Customer Number` = '' THEN NULL ELSE `Customer Number` END 'CustomerNumber'
                                , CASE WHEN replace(`Shipto`,'\r','') = '' THEN NULL ELSE replace(`Shipto`,'\r','') END 'Shipto'
                                        from supreftemp
                                        where `SupplierNo` = '' or `Customer Number` ='' or replace(`Shipto`,'\r','') = '' ";

                                $datanull=mysqli_query($msqlcon,$checknull);
                                $rowcount=mysqli_num_rows($datanull);
                                if($rowcount > 0){
                                    echo '<form method="POST" enctype="multipart/form-data" action="../supref_import.php">';
                                    echo '<input type="hidden" name="replace" value="'.$rdreplace.'">'; 
                                    echo '<input type="submit" name="nobtn" value="Upload again">';
                                    echo '</form>';
                                    echo '<br>';
                                    echo '<table class="tbl1" >';
                                    echo '<tr class="arial11grey" bgcolor="#AD1D36" >';
                                    echo '<th width="20%" align="center">SupplierNo</th>';
                                    echo '<th width="20%" align="center">CustomerNo</th>';
                                    echo '<th  width="20%" align="center">Shipto</th></tr>';
                                    
                                    $qa2=" SELECT  CASE WHEN `SupplierNo` = '' THEN 'SupplierNo, ' 
                                    WHEN `Customer Number` = '' THEN 'Customer Number, ' 
                                    WHEN  replace(`Shipto`,'\r','')  = '' THEN 'Shipto, ' 
                                    ELSE '' END as 'Columnname', `Customer Number` as CustomerNo ,SupplierNo, Shipto 
                                    FROM supreftemp  LIMIT 10";
                                    $Columnnull = "";
                                    //echo $qa2;
                                    $sqlqa2=mysqli_query($msqlcon,$qa2);
                                    while($arrqa2=mysqli_fetch_array($sqlqa2)){
                                        $Columnnull .= $arrqa2['Columnname'] . ' ';
                                        $SupplierNo=$arrqa2['SupplierNo'];
                                        $CustomerNo=$arrqa2['CustomerNo'];
                                        $Shipto=$arrqa2['Shipto'];
                                        echo "<tr class=\"arial11black\">
                                                <td>$SupplierNo</td>
                                                <td>$CustomerNo</td>
                                                <td>$Shipto</td>
                                            </tr>";
                                    }
                                    echo "</table>"; 
                                    echo "<br/><span class='arial21redbold' width='200px'>Customer number ".$Columnnull ." connot be null, Please Recheck Again </span><br/>";
                                    echo "<br/><span class='arial21redbold' width='200px'>Note : Data not upload to system</span>";
                                }
                                else{
                                    $qc1="SELECT count(*) FROM supreftemp ";
                                    $sqlqc1=mysqli_query($msqlcon,$qc1);
                                    $arqc1=mysqli_fetch_array($sqlqc1);
                                    $tmpcount=$arqc1[0];
                                    
                                    
                                    echo "<div class='arial21redbold'> Do you want to Upload data?";
                                    echo '<form method="POST" enctype="multipart/form-data" action="replace-all-supplierref.php">';
                                    echo '<input type="hidden" name="replace" value="'.$rdreplace.'">'; 
                                    echo '<input type="submit" name="yesbtn" value="Yes">';
                                    echo '<input type="submit" name="nobtn" value="No">';
                                    
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
                                    echo '<th width="20%" align="center">SupplierNo</th>';
                                    echo '<th width="20%" align="center">CustomerNo</th>';
                                    echo '<th  width="20%" align="center">Shipto</th></tr>';
                                    $qa2="SELECT `Customer Number` as CustomerNo ,SupplierNo, Shipto FROM supreftemp  LIMIT 10";
                                    $sqlqa2=mysqli_query($msqlcon,$qa2);
                                    while($arrqa2=mysqli_fetch_array($sqlqa2)){
                                        $SupplierNo=$arrqa2['SupplierNo'];
                                        $CustomerNo=$arrqa2['CustomerNo'];
                                        $Shipto=$arrqa2['Shipto'];
                                        echo "<tr class=\"arial11black\">
                                                <td>$SupplierNo</td>
                                                <td>$CustomerNo</td>
                                                <td>$Shipto</td>
                                            </tr>";
                                    }
                                    echo "</table>"; 
                                }
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



