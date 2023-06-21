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
<body>
   		
        <?php ctc_get_logo() ?>

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
			  	$_GET['current']="mainSpecialAwsAdm";
				include("navAdm.php");
			  ?>
              </div>
        <div id="twocolRight">
        	 <h3>AWS Price</h3>
             Download format excel here : <a href="prototype/specialpriceaws.xls" target="_blank" ><img src="../images/excel.jpg" width="16" height="16" border="0"></a>	
             <form method="POST" enctype="multipart/form-data" name="uploadForm" action="db/upload-specialaws.php">
            
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
                        <input type="radio" name="group1" value="add"> Add
                        <input type="radio" name="group1" value="edit"> Replace Partial
                        <input type="radio" name="group1" value="editall"> Replace All
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
               include "../db/conn.inc";
			   $msg=$_GET['msg'];
                
                // If error upload
                if($msg=='Error'){
                    $msgtbl="<table border=1 class='arial11blackbold'>
                        <tr>
                            <th align=center>CUSTOMER NUMBER</th>
                            <th align=center>ITEM NUMBER</th>
                            <th align=center>AWS PRICE</th>
                            <th align=center>CURCD AWS</th>
                            <th align=center>DLR PRICE</th>
                            <th align=center>DLR CURCD</th>
							 <th align=center>DLR CODE</th>
                            <th align=center>Error Message</th>
                        </tr>";
                    $qse="SELECT * FROM specialpriceawstmp WHERE StatusItem='E' AND Owner_Comp='$comp'";  // edit by CTC
					//echo $qse;
                    $sqlqse=mysqli_query($msqlcon,$qse);
                    while($arx=mysqli_fetch_array($sqlqse)){
                        $ecusno=$arx['Cusno'];
                        $eitnbr=$arx['Itnbr'];
                        $eprice=$arx['PriceAWS'];
                        $ecurcd=$arx['CurCDAWS'];
						$edprice=$arx['Price'];
                        $edcurcd=$arx['CurCD'];
						$edlrcd=$arx['DlrCD'];
                        $eketerangan=$arx['Keterangan'];
                        $msgtbl.="<tr class='arial11'>
                                    <td>$ecusno</td>
                                    <td>$eitnbr</td>
                                    <td>$eprice</td>
                                    <td>$ecurcd</td>
                                    <td>$edprice</td>
                                    <td>$edcurcd</td>
									<td>$edlrcd</td>
                                    <td>$eketerangan</td>
                                </tr>";
                    }
                    $msgtbl.="</table>";
                    $qd="DELETE FROM specialpriceawstmp";
                    mysqli_query($msqlcon,$qd);
                    $msg=$msgtbl;
                }
                
                // If succesfully add data
                if($msg=='Add'){
                    
                    $msgsuccess='Add data success';
                    $qi2="INSERT INTO specialpriceaws";
					$qi2.=" SELECT Owner_Comp,Cusno, Itnbr,PriceAWS,CurCDAWS,Price,CurCD,DlrCD FROM specialpriceawstmp WHERE StatusItem !='H' AND Owner_Comp='$comp' "; // edit by CTC
					mysqli_query($msqlcon,$qi2);
					$qd="DELETE FROM specialpriceawstmp";
                    mysqli_query($msqlcon,$qd);
                    $msg=$msgsuccess;
                }
                
                // If succesfully replace partial data
                if($msg=='Replace'){
                    $msgsuccess='Replace data success';
                     $qu3="SELECT Owner_Comp,Cusno, Itnbr,PriceAWS,CurCDAWS,Price,CurCD,DlrCD FROM specialpriceawstmp WHERE StatusItem !='H' AND Owner_Comp='$comp' ";  // edit by CTC
                    $sqlqu3=mysqli_query($msqlcon,$qu3);
                    while($arrqu3=mysqli_fetch_array($sqlqu3)){
                        $esearch=array("'","�");
                        $ereplace=array("\\'","A");
                        
                        $ucusno=$arrqu3['Cusno'];
                        $uitnbr=$arrqu3['Itnbr'];
                        $uprice=$arrqu3['PriceAWS'];
                        $ucurcd=$arrqu3['CurCDAWS'];
						$udprice=$arrqu3['Price'];
                        $udcurcd=$arrqu3['CurCD'];
                        $udlrcd=$arrqu3['DlrCD'];
                           
                        //Query Update
                        $qu="UPDATE specialpriceaws SET 
                                Cusno='$ucusno',
                                Itnbr='$uitnbr',
                                PriceAWS='$uprice',
                                CurCDAWS='$ucurcd',
                                Price='$udprice',
                                CurCD='$udcurcd',
								DlrCD='$udlrcd'
                                WHERE Cusno='$ucusno' AND Itnbr='$uitnbr' AND Owner_Comp='$comp' ";
								//echo $qu;
                        mysqli_query($msqlcon,$qu) OR die(mysqli_error());
					}
                    $qd="DELETE FROM specialpriceawstmp WHERE Owner_Comp='$comp' ";
                    mysqli_query($msqlcon,$qd);
                    
					$msg=$msgsuccess;	
                    
                }
                
                // If succesfully replace partial data
                if($msg=='Confirm'){
                    // Check count 
                    $qc1="SELECT COUNT(*) AS fcount FROM specialpriceaws WHERE Owner_Comp='$comp'";  // edit by CTC
                    $sqlqc1=mysqli_query($msqlcon,$qc1);
                    $arqc1=mysqli_fetch_array($sqlqc1);
                    $fcount=$arqc1['fcount'];
                        
                    $qc2="SELECT COUNT(*) AS tmpcount FROM specialpriceawstmp WHERE StatusItem!='H' AND Owner_Comp='$comp' "; // edit by CTC
                    $sqlqc2=mysqli_query($msqlcon,$qc2);
                    $arqc2=mysqli_fetch_array($sqlqc2);
                    $tmpcount=$arqc2['tmpcount'];
                        
                        echo "Do you want to replace $fcount with $tmpcount?";
                    ?>
                        <form method="POST" enctype="multipart/form-data" action="db/replace-all-specialaws.php">
                            <input type="submit" name="yesbtn" value="Yes">
                            <input type="submit" name="nobtn" value="No">
                        </form>
                        <table border="1" class="idtable">
                            <tr>
                               <th align=center>CUSTOMER NUMBER</th>
                            <th align=center>ITEM NUMBER</th>
                            <th align=center>AWS PRICE</th>
                            <th align=center>CURCD AWS</th>
                            <th align=center>DLR PRICE</th>
                            <th align=center>DLR CURCD</th>
							 <th align=center>DLR CODE</th>
                            <th align=center>Error Message</th>
                                    
                            </tr>
                    <?
                        $qa2="SELECT Cusno, Itnbr,PriceAWS,CurCDAWS,Price,CurCD,DlrCD FROM specialpriceawstmp WHERE StatusItem!='H' AND Owner_Comp='$comp' LIMIT 10";  // edit by CTC
                        $sqlqa2=mysqli_query($msqlcon,$qa2);
                        while($arrqa2=mysqli_fetch_array($sqlqa2)){
                            $acusno=$arrqa2['Cusno'];
                            $aitnbr=$arrqa2['Itnbr'];
                            $aprice=$arrqa2['PriceAWS'];
                            $acurcd=$arrqa2['CurCDAWS'];
                            $adprice=$arrqa2['Price'];
                            $adcurcd=$arrqa2['CurCD'];
							$adlrcd=$arrqa2['DlrCD'];
                        
                            echo "<tr>
                                    <td>$acusno</td>
                                    <td>$aitnbr</td>
                                    <td>$aprice</td>
                                    <td>$acurcd</td>
                                    <td>$adprice</td>
                                    <td>$adcurcd</td>
									<td>$adlrcd</td>
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
