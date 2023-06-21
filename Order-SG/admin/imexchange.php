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
			 	$_GET['current']="mainExcAdm";
				include("navAdm.php");
			  ?>
              </div>
            
        <div id="twocolRight">
        	  <h3>Master Exchange Rate</h3>	
        	 Download format excel here : <a href="prototype/ExchangeRate.xls" target="_blank" ><img src="../images/excel.jpg" width="16" height="16" border="0"></a>	
             <form method="POST" enctype="multipart/form-data" name="uploadForm" action="db/upload-exchange.php">
           
           
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
                       <input type="radio" name="group2" value="excel" checked> .xls
                    </td>
                </tr>
                <tr>
                    <td>First row for header</td>
                    <td>:</td>
                    <td>
                        <input type="radio" name="group3" value="yesrow" checked> Yes
                        <input type="radio" name="group3" value="norow"> No
                    </td>
                </tr>
                <tr>
                    <td></td>
                    <td></td>
                    <td>
                        <input type="radio" name="group1" value="add" checked> Add Only
                       
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
      <!--  <div id="isi" width='100%'>-->
            <?
              include "../db/conn.inc";
			   $msg=$_GET['msg'];
                
				// If error upload
				
                if($msg=='Error'){
                    $msgtbl="<H3>Error</H3><table width='100%'  class='tbl1' cellspacing='0' cellpadding='0'>
 						 <tr class='arial11grey' bgcolor='#AD1D36' >
                        
						
                            <th  width='20%' scope='col'>Cur. CD</th>
                            <th width='20%' scope='col'>Exchange Rate</th>
                            <th width='20%' scope='col'>Eff. Date From </th>
                            <th width='40%' scope='col'>Error Message</th>
                        </tr>";
                    $qse="SELECT * FROM excratetmp WHERE (StatusItem='E' or StatusItem='F') AND Owner_Comp='$comp' ";
					//echo $qse;
                    $sqlqse=mysqli_query($msqlcon,$qse);
                    while($arx=mysqli_fetch_array($sqlqse)){
                        $ecurcd=$arx['CurCD'];
						$erate=$arx['Rate'];
						$eeff=$arx['EfDateFr'];
						
                        $eketerangan=$arx['Keterangan'];
                        $msgtbl.="<tr class='arial11black'>
                                    <td>$ecurcd</td>
                                    <td>$erate</td>
                                    <td>$eeff</td>
                                    <td>$eketerangan</td>
                                </tr>";
                    }
                    $msgtbl.="</table>";
                    $qd="DELETE FROM excratetmp";
                    mysqli_query($msqlcon,$qd);
                    $msg=$msgtbl;
                }
                
                // If succesfully add data
                if($msg=='Add'){
                    
                    $msgsuccess='Add data success';
                    $qa="SELECT CurCD, Rate, EfDateFr, uptime, StatusItem, Keterangan,Owner_Comp FROM excratetmp WHERE StatusItem !='H' AND Owner_Comp='$comp' ";
                    $sqlqa=mysqli_query($msqlcon,$qa);
                    while($arrqa=mysqli_fetch_array($sqlqa)){
                        $search=array("'","�");
                        $replace=array("\\'","A");
                
                       
                        $acurcd=$arrqa['CurCD'];
                        $arate=$arrqa['Rate'];
                        $aeff=$arrqa['EfDateFr'];
						$auptime=$arrqa['uptime'];
						$ownerComp=$arrqa['Owner_Comp']; // add by CTC
                            
                        $qi2="Replace INTO  excrate(CurCD, Rate, EfDateFr, uptime,Owner_Comp) VALUES('$acurcd',$arate,$aeff,'$auptime','$ownerComp')"; // edit by CTC
                        mysqli_query($msqlcon,$qi2);
                     }
                    $qd="DELETE FROM excratetmp WHERE Owner_Comp='$comp'";
                    mysqli_query($msqlcon,$qd);
                    $msg=$msgsuccess;
				   //echo $qi2;
                }
                
                // If succesfully replace partial data
              
                echo '<table width="90%" border="0" align="center" bgcolor="#AD1D36">';
				echo '<tr  class="arial11whitebold"><td align="center">';
				echo $msg;
				echo '</td></tr></table>';
            ?>
       <!-- </div>-->
           

          
          
           
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
