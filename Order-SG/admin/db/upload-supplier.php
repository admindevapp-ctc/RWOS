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
			  	$_GET['current']="sup_mainadm";
				include("navAdm.php");
			  ?>
              </div>
            
        <div id="twocolRight">
       	  <h3>Supplier Upload</h3>
       	  
       	    
            <?php
               include "../../db/conn.inc";
			   include "Quick_CSV_import.php";
			   $query='delete from supmastemp';
			   mysqli_query($msqlcon,$query);
			   if(isset($_POST['submit'])){
                $rdfirstrow=$_POST['rdfirstrow'];
                $rdreplace=$_POST['rdreplace'];
                $userfile=$_FILES['userfile']['name'];
                $ext = strtolower(end(explode('.', $userfile)));
                if($ext!='csv'){
                    Echo " Error File Type, Only allow CSV File";	
                }else{
                   //Create Table
                    $csv = new Quick_CSV_import();
                    $csv->file_name = $_FILES['userfile']['tmp_name'];
                    $csv->table_name='supmastemp';            
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
                     $ctc_csv->table_name='supmastemp';     
                     $ctc_csv->import();


                        //Check Duplicate all column

                     $qc2="SELECT * FROM supmastemp group by 1,2,3,4,5,6,7,8,9,10,11 having count(*) > 1";
                     //echo $qc2;
                     $sqlqc2=mysqli_query($msqlcon,$qc2);
                     $row=mysqli_num_rows($sqlqc2);
                     //echo "row". $row;
                     if($row > 0){ // duplicate all

                        echo '<form method="POST" enctype="multipart/form-data" action="../sup_import.php">';
                        echo '<input type="hidden" name="replace" value="'.$rdreplace.'">'; 
                        echo '<input type="submit" name="nobtn" value="Upload again">';
                        
                        echo '</form>';
                        echo '<br>';
                        
                        echo '<table class="tbl1" >';
                        echo '<tr class="arial11grey" bgcolor="#AD1D36" >';
                        echo '<th width="10%" align="center">SupplierNo</th><th width="10%" align="center">SupplierName</th>';
                        echo '<th  width="10%" align="center">Address1</th><th width="10%" align="center">Address2</th>';
                        echo '<th width="10%" align="center">Address3</th> <th width="10%" align="center">Email1</th>';
                        echo '<th width="10%" align="center">Email2</th><th width="10%" align="center">Website</th>';
                        echo '<th width="10%" align="center">logo</th><th width="5%" align="center">Duedate</th>';
                        echo '<th width="5%" align="center">Holidayck</th></tr>';
                        
                        $supmastemp="SELECT * FROM supmastemp  LIMIT 10";
                        $rensupmastemp=mysqli_query($msqlcon,$supmastemp);

                        while($arrqa2=mysqli_fetch_array($rensupmastemp)){
                            $SupplierNo     =trim($arrqa2['SupplierNo']);
                            $SupplierName   =trim($arrqa2['SupplierName']);
                            $Address1       =trim($arrqa2['Address1']);
                            $Address2       =trim($arrqa2['Address2']);
                            $Address3       =trim($arrqa2['Address3']);
                            $Email1         =trim($arrqa2['Email1']);
                            $Email2         =trim($arrqa2['Email2']);
                            $Website        =trim($arrqa2['Website']);
                            $logo           =trim($arrqa2['logo']);
                            $Duedate        =trim($arrqa2['Duedate']);
                            $holichk        =trim($arrqa2['Holidayck']);
                            echo "<tr class=\"arial11black\">
                                    <td>$SupplierNo</td>
                                    <td>$SupplierName</td>
                                    <td>$Address1</td>
                                    <td>$Address2</td>
                                    <td>$Address3</td>
                                    <td>$Email1</td>
                                    <td>$Email2</td>
                                    <td>$Website</td>
                                    <td>$logo</td>
                                    <td>$Duedate</td>
                                    <td>$holichk</td>
                            </tr>";
                        }
                        echo "</table>"; 
                        echo "<br/><span class='arial21redbold' width='200px'>Duplicate Data, Please Recheck again</span><br/>";
                        echo "<br/><span class='arial21redbold' width='200px'>Note : Data not upload to system</span>";
                     }
                     else{
                           //check dup by key 
                            $qckey="SELECT * FROM supmastemp group by 1 having count(*) > 1";
                            //echo $qc2;
                            $sqlqckey=mysqli_query($msqlcon,$qckey);
                            $arqckey=mysqli_num_rows($sqlqckey);
                            if($arqckey){//found duplicate by key
                                echo '<form method="POST" enctype="multipart/form-data" action="../sup_import.php">';
                                echo '<input type="hidden" name="replace" value="'.$rdreplace.'">'; 
                                echo '<input type="submit" name="nobtn" value="Upload again">';
                                
                                echo '</form>';
                                echo '<br>';
                                
                                echo '<table class="tbl1" >';
                                echo '<tr class="arial11grey" bgcolor="#AD1D36" >';
                                echo '<th width="10%" align="center">SupplierNo</th><th width="10%" align="center">SupplierName</th>';
                                echo '<th  width="10%" align="center">Address1</th><th width="10%" align="center">Address2</th>';
                                echo '<th width="10%" align="center">Address3</th> <th width="10%" align="center">Email1</th>';
                                echo '<th width="10%" align="center">Email2</th><th width="10%" align="center">Website</th>';
                                echo '<th width="10%" align="center">logo</th><th width="5%" align="center">Duedate</th>';
                                echo '<th width="5%" align="center">Holidayck</th></tr>';
                                $qa2="SELECT * FROM supmastemp  LIMIT 10";
                                $sqlqa2=mysqli_query($msqlcon,$qa2);
                                while($arrqa2=mysqli_fetch_array($sqlqa2)){
                                    $SupplierNo=trim($arrqa2['SupplierNo']);
                                    $SupplierName=trim($arrqa2['SupplierName']);
                                    $Address1=trim($arrqa2['Address1']);
                                    $Address2=trim($arrqa2['Address2']);
                                    $Address3=trim($arrqa2['Address3']);
                                    $Email1=trim($arrqa2['Email1']);
                                    $Email2=trim($arrqa2['Email2']);
                                    $Website=trim($arrqa2['Website']);
                                    $logo=trim($arrqa2['logo']);
                                    $Duedate=trim($arrqa2['Duedate']);
                                    $holichk=trim($arrqa2['Holidayck']);
                                    echo "<tr class=\"arial11black\">
                                            <td>$SupplierNo</td>
                                            <td>$SupplierName</td>
                                            <td>$Address1</td>
                                            <td>$Address2</td>
                                            <td>$Address3</td>
                                            <td>$Email1</td>
                                            <td>$Email2</td>
                                            <td>$Website</td>
                                            <td>$logo</td>
                                            <td>$Duedate</td>
                                            <td>$holichk</td>
                                        </tr>";
                                }
                                echo "</table>"; 
                                echo "<br/><span class='arial21redbold' width='200px'>Duplicate SupplierNo, Please Recheck again </span><br/>";
                                echo "<br/><span class='arial21redbold' width='200px'>Note : Data not upload to system</span>";
                            }
                            else{
                                //check null key
                                $qcnullkey="SELECT * FROM supmastemp where `SupplierName` = '' or `SupplierNo` ='' or `Duedate` ='' ";
                                //echo $qc2;
                                $sqlqcnullkey=mysqli_query($msqlcon,$qcnullkey);
                                $chkrow=mysqli_num_rows($sqlqcnullkey);
                                if($chkrow > 0){//found null key
                                    echo '<form method="POST" enctype="multipart/form-data" action="../sup_import.php">';
                                    echo '<input type="hidden" name="replace" value="'.$rdreplace.'">'; 
                                    echo '<input type="submit" name="nobtn" value="Upload again">';
                                    
                                    echo '</form>';
                                    echo '<br>';
                                    
                                    echo '<table class="tbl1" >';
                                    echo '<tr class="arial11grey" bgcolor="#AD1D36" >';
                                    echo '<th width="10%" align="center">SupplierNo</th><th width="10%" align="center">SupplierName</th>';
                                    echo '<th  width="10%" align="center">Address1</th><th width="10%" align="center">Address2</th>';
                                    echo '<th width="10%" align="center">Address3</th> <th width="10%" align="center">Email1</th>';
                                    echo '<th width="10%" align="center">Email2</th><th width="10%" align="center">Website</th>';
                                    echo '<th width="10%" align="center">logo</th><th width="5%" align="center">Duedate</th>';
                                    echo '<th width="5%" align="center">Holidayck</th></tr>';
                                    $qa2="SELECT * FROM supmastemp  LIMIT 10";
                                    $sqlqa2=mysqli_query($msqlcon,$qa2);
                                    while($arrqa2=mysqli_fetch_array($sqlqa2)){
                                        $SupplierNo=trim($arrqa2['SupplierNo']);
                                        $SupplierName=trim($arrqa2['SupplierName']);
                                        $Address1=trim($arrqa2['Address1']);
                                        $Address2=trim($arrqa2['Address2']);
                                        $Address3=trim($arrqa2['Address3']);
                                        $Email1=trim($arrqa2['Email1']);
                                        $Email2=trim($arrqa2['Email2']);
                                        $Website=trim($arrqa2['Website']);
                                        $logo=trim($arrqa2['logo']);
                                        $Duedate=trim($arrqa2['Duedate']);
                                        $holichk=trim($arrqa2['Holidayck']);
                                        echo "<tr class=\"arial11black\">
                                                <td>$SupplierNo</td>
                                                <td>$SupplierName</td>
                                                <td>$Address1</td>
                                                <td>$Address2</td>
                                                <td>$Address3</td>
                                                <td>$Email1</td>
                                                <td>$Email2</td>
                                                <td>$Website</td>
                                                <td>$logo</td>
                                                <td>$Duedate</td>
                                                <td>$holichk</td>
                                            </tr>";
                                    }
                                    echo "</table>"; 
                                    echo "<br/><span class='arial21redbold' width='200px'>SupplierNo, SupplierName, Duedate cannot be null Please Recheck again </span><br/>";
                                    echo "<br/><span class='arial21redbold' width='200px'>Note : Data not upload to system</span>";
                                }
                                else{ // wait for upload
                                    $qc1="SELECT count(*) FROM supmastemp ";
                                    $sqlqc1=mysqli_query($msqlcon,$qc1);
                                    $arqc1=mysqli_fetch_array($sqlqc1);
                                    $tmpcount=$arqc1[0];
                                    
                                    
                                    echo "<div class='arial21redbold'> Do you want to Upload data?";
                                    echo '<form method="POST" enctype="multipart/form-data" action="replace-all-supplier.php">';
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
                                    echo '<th width="10%" align="center">SupplierNo</th><th width="10%" align="center">SupplierName</th>';
                                    echo '<th  width="10%" align="center">Address1</th><th width="10%" align="center">Address2</th>';
                                    echo '<th width="10%" align="center">Address3</th> <th width="10%" align="center">Email1</th>';
                                    echo '<th width="10%" align="center">Email2</th><th width="10%" align="center">Website</th>';
                                    echo '<th width="10%" align="center">logo</th><th width="5%" align="center">Duedate</th>';
                                    echo '<th width="5%" align="center">Holidayck</th></tr>';
                                    $qa2="SELECT * FROM supmastemp  LIMIT 10";
                                    $sqlqa2=mysqli_query($msqlcon,$qa2);
                                    while($arrqa2=mysqli_fetch_array($sqlqa2)){
                                        $SupplierNo=trim($arrqa2['SupplierNo']);
                                        $SupplierName=trim($arrqa2['SupplierName']);
                                        $Address1=trim($arrqa2['Address1']);
                                        $Address2=trim($arrqa2['Address2']);
                                        $Address3=trim($arrqa2['Address3']);
                                        $Email1=trim($arrqa2['Email1']);
                                        $Email2=trim($arrqa2['Email2']);
                                        $Website=trim($arrqa2['Website']);
                                        $logo=trim($arrqa2['logo']);
                                        $Duedate=trim($arrqa2['Duedate']);
                                        $holichk=trim($arrqa2['Holidayck']);
                                        echo "<tr class=\"arial11black\">
                                                <td>$SupplierNo</td>
                                                <td>$SupplierName</td>
                                                <td>$Address1</td>
                                                <td>$Address2</td>
                                                <td>$Address3</td>
                                                <td>$Email1</td>
                                                <td>$Email2</td>
                                                <td>$Website</td>
                                                <td>$logo</td>
                                                <td>$Duedate</td>
                                                <td>$holichk</td>
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



