<?php 

session_start();
require_once('./../../core/ctc_init.php'); // add by CTC

//if (session_is_registered('cusno'))
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
        $comp = ctc_get_session_comp(); // add by CTC
	 }else{
        echo "<script> document.location.href='../".redir."'; </script>";
	 }
}else{
    header("Location:../login.php");
}

?>
<html>
	<head>
    <title>Denso Ordering System</title>
   	<link rel="stylesheet" type="text/css" href="../css/dnia.css">
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
<body >
   		
        <?php ctc_get_logo(); ?> <!-- add by CTC -->

		<div id="mainNav">
         
			<ul>  
  				<li id="current"><a href="maincusadm.php" target="_self">Administration</a></li>
				<li><a href="Profile.php" target="_self">User Profile</a></li>
  				<li ><a href="../logout.php" target="_self">Log out</a></li>
  				  				
			</ul>
	
			
		</div> 
    	<div id="isi">
        
        <div id="twocolLeft">
        	<div class="hmenu">
        	  <div class="headerbar">Administration</div>
           	 <?
			  	$MYROOT=$_SERVER['DOCUMENT_ROOT'];
			  	$_GET['current']="mainSpecialAdm";
				include("navAdm.php");
			  ?>
              </div>
        <div id="twocolRight">
        	<h3>Special Price</h3>
             Download format excel here : <a href="prototype/SpecialPrice.xls" target="_blank" ><img src="../images/excel.jpg" width="16" height="16" border="0"></a>	
             <form method="POST" enctype="multipart/form-data" name="uploadForm" action="db/uploadspecial.php">
            
            <table class='arial11'>
                <tr>
                    <td>Upload</td>
                    <td>:</td>
                    <td><input type="file" size="45" name="userfile"></td>
                </tr>
                <tr>
                    <td>File type</td>
                    <td>:</td>
                    <td>
                        <input type="radio" name="group2" value="csv"> .csv
                        <input type="radio" name="group2" value="excel"> .xls
                    </td>
                </tr>
                <tr>
                    <td>First row for header</td>
                    <td>:</td>
                    <td>
                        <input type="radio" name="group3" value="yesrow"> Yes
                        <input type="radio" name="group3" value="norow"> No
                    </td>
                </tr>
                <tr>
                    <td></td>
                    <td></td>
                    <td>
                        <input type="radio" name="group1" value="add"> Add and Replace Partial
                        <input type="radio" name="group1" value="editall"> Replace All (Delete All first)
                    </td>
                </tr>
                <tr>
                    <td></td>
                    <td></td>
                    <td>
                        <input name="submit" type="submit" value="Submit"> 
                        <input type="reset" value="Reset">
                    </td>
                </tr>
            </table><br/>
        </form>
        <div>
            <?

              include ('../db/conn.inc');
			   $msg=$_GET['msg'];
                
                // If error upload
                if($msg=='Error'){
                    $msgtbl="<table border=1 class='arial11blackbold'>
                        <tr>
                            <th align=center>CUSTOMER NUMBER</th>
                            <th align=center>ITEM NUMBER</th>
                            <th align=center>PRICE</th>
                            <th align=center>CURRENCY CODE</th>
                            <th align=center>CUST2</th>
                            <th align=center>CUST3</th>
                            <th align=center>Error Message</th>
                        </tr>";
                    $qse="SELECT * FROM specialpricetmp WHERE StatusItem='E' AND Owner_Comp='$comp' ";  // edit by CTC
					//echo $qse;
                    $sqlqse=mysqli_query($msqlcon,$qse);
                    while($arx=mysqli_fetch_array($sqlqse)){
                        $ecusno=$arx['Cusno'];
                        $eitnbr=$arx['Itnbr'];
                        $eprice=$arx['Price'];
                        $ecurcd=$arx['CurCD'];
						if($arx['CUST2']==''){
							$ecust2='-';
                        	$ecust3='-';
						}else{
                        	$ecust2=$arx['CUST2'];
                        	$ecust3=$arx['CUST3'];
						}
                        $eketerangan=$arx['Keterangan'];
                        $msgtbl.="<tr class='arial11'>
                                    <td>$ecusno</td>
                                    <td>$eitnbr</td>
                                    <td>$eprice</td>
                                    <td>$ecurcd</td>
                                    <td>$ecust2</td>
                                    <td>$ecust3</td>
                                    <td>$eketerangan</td>
                                </tr>";
                    }
                    $msgtbl.="</table>";
                    $qd="DELETE FROM specialpricetmp WHERE Owner_Comp='$comp'";
                    mysqli_query($msqlcon,$qd);
                    $msg=$msgtbl;
                }
                
                // If succesfully add data
                if($msg=='Add'){
                    
                    $msgsuccess='Add data success';
                    $qa="SELECT Cusno, Itnbr, Price, CurCD, CUST2, CUST3 FROM specialpricetmp WHERE StatusItem !='H' AND Owner_Comp='$comp' "; // edit by CTC
                    $sqlqa=mysqli_query($msqlcon,$qa);
                    while($arrqa=mysqli_fetch_array($sqlqa)){
                        $search=array("'","�");
                        $replace=array("\\'","A");
                
                        $acusno=$arrqa['Cusno'];
                        $aitnbr=$arrqa['Itnbr'];
                        $aprice=$arrqa['Price'];
                        $acurcd=$arrqa['CurCD'];
                        $acust2=$arrqa['CUST2'];
                        $acust3=$arrqa['CUST3'];
                            
                        $qi2="INSERT INTO specialprice(Cusno, Itnbr, Price, CurCD, CUST2, CUST3,Owner_Comp) VALUES('$acusno','$aitnbr','$aprice','$acurcd','$acust2','$acust3','$comp')";   // edit by CTC
                        mysqli_query($msqlcon,$qi2);
                    }
                    $qd="DELETE FROM specialpricetmp";
                    mysqli_query($msqlcon,$qd);
                    $msg=$msgsuccess;
                }
                
                // If succesfully replace partial data
                if($msg=='Replace'){
                    
                    $msgsuccess='Replace data partial success';
                    $msgsuccess.="<table border=1 class=idtable>
                                    <tr>
                                        <th align=center>CUSTOMER NUMBER</th>
                                        <th align=center>ITEM NUMBER</th>
                                        <th align=center>PRICE</th>
                                        <th align=center>CURRENCY CODE</th>
                                        <th align=center>CUST2</th>
                                        <th align=center>CUST3</th>
                                    </tr>";
                    $qu3="SELECT Cusno, Itnbr, Price, CurCD, CUST2, CUST3 FROM specialpricetmp WHERE StatusItem !='H' AND Owner_Comp='$comp' "; // edit by CTC
                    $sqlqu3=mysqli_query($msqlcon,$qu3);
                    while($arrqu3=mysqli_fetch_array($sqlqu3)){
                        $esearch=array("'","�");
                        $ereplace=array("\\'","A");
                        
                        $ucusno=$arrqu3['Cusno'];
                        $uitnbr=$arrqu3['Itnbr'];
                        $uprice=$arrqu3['Price'];
                        $ucurcd=$arrqu3['CurCD'];
                        $ucust2=$arrqu3['CUST2'];
                        $ucust3=$arrqu3['CUST3'];
                           
                        //Query Update
                        $qu="UPDATE specialprice SET 
                                Cusno='$ucusno',
                                Itnbr='$uitnbr',
                                Price='$uprice',
                                CurCD='$ucurcd',
                                CUST2='$ucust2',
                                CUST3='$ucust3'
                                WHERE Cusno='$ucusno' AND Itnbr='$uitnbr'";
                        mysqli_query($msqlcon,$qu) OR die(mysqli_error());
                        
                        $msgsuccess.="<tr>
                                        <td>$ucusno</td>
                                        <td>$uitnbr</td>
                                        <td>$uprice</td>
                                        <td>$ucurcd</td>
                                        <td>$ucust2</td>
                                        <td>$ucust3</td>
                                    </tr>";
                    }
                    $msgsuccess.="</table>";
                    $qd="DELETE FROM specialpricetmp";
                    mysqli_query($msqlcon,$qd);
                    
                    $msg=$msgsuccess;
                }
                
                // If succesfully replace partial data
                if($msg=='Confirm'){
                    // Check count 
                    $qc1="SELECT COUNT(*) AS fcount FROM specialprice WHERE Owner_Comp='$comp' ";  // edit by CTC
                    $sqlqc1=mysqli_query($msqlcon,$qc1);
                    $arqc1=mysqli_fetch_array($sqlqc1);
                    $fcount=$arqc1['fcount'];
                        
                    $qc2="SELECT COUNT(*) AS tmpcount FROM specialpricetmp WHERE StatusItem!='H' AND Owner_Comp='$comp' ";  // edit by CTC
                    $sqlqc2=mysqli_query($msqlcon,$qc2);
                    $arqc2=mysqli_fetch_array($sqlqc2);
                    $tmpcount=$arqc2['tmpcount'];
                        
                        echo "Do you want to replace $fcount with $tmpcount?";
                    ?>
                        <form method="POST" enctype="multipart/form-data" action="db/rep-all-spec.php">
                            <input type="submit" name="yesbtn" value="Yes">
                            <input type="submit" name="nobtn" value="No">
                        </form>
                        <table border="1" class="idtable">
                            <tr>
                                <th align="center">CUSTOMER NUMBER</th>
                                    <th align="center">ITEM NUMBER</th>
                                    <th align="center">PRICE</th>
                                    <th align="center">CURRENCY CODE</th>
                                    <th align="center">CUST2</th>
                                    <th align="center">CUST3</th>
                            </tr>
                    <?
                        $qa2="SELECT Cusno, Itnbr, Price, CurCD, CUST2, CUST3 FROM specialpricetmp WHERE StatusItem!='H' AND Owner_Comp='$comp' LIMIT 10";  // edit by CTC
                        $sqlqa2=mysqli_query($msqlcon,$qa2);
                        while($arrqa2=mysqli_fetch_array($sqlqa2)){
                            $acusno=$arrqa2['Cusno'];
                            $aitnbr=$arrqa2['Itnbr'];
                            $aprice=$arrqa2['Price'];
                            $acurcd=$arrqa2['CurCD'];
                            $acust2=$arrqa2['CUST2'];
                            $acust3=$arrqa2['CUST3'];
                            echo "<tr>
                                    <td>$acusno</td>
                                    <td>$aitnbr</td>
                                    <td>$aprice</td>
                                    <td>$acurcd</td>
                                    <td>$acust2</td>
                                    <td>$acust3</td>
                                </tr>";
                        }
                        echo "</table>";
                    
                    $msg=$msgsuccess;
                }
               
				if($msg!='Error' && $msg!=''){
					echo "<p>";
 					echo '<table width="90%" border="0" align="center" bgcolor="#AD1D36">';
 					echo '<tr  class="arial11whitebold">';
 					echo '<td  rowspan="3"></td>';
 					echo '<td>'.$msg.'</td>';
 					echo '</tr></table>';
				}
            ?>
        </div>
           

          
          
           
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
