<? session_start() ?>
<?
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
   		<div id="header">
        <img src="../images/denso.jpg" width="206" height="54" />
        </div>
		<div id="mainNav">
         
			<ul>  
  				<li id="current"><a href="mainRFQ.php" target="_self">Marketing</a></li>
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
			  	$_GET['current']="mainItem";
				include("navAdm.php");
			  ?>
              </div>
        <div id="twocolRight">
           <form method="POST" enctype="multipart/form-data" name="uploadForm" action="db/upload-bm008.php">
            <h3>Upload Item Master (BM0008PR)</h3>
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
                        <input type="radio" name="group2" value="csv"> 
                        .csv (separator should ', '
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
                        <input type="radio" name="group1" value="edit"> Add and Replace Partial
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
                $msg=$_GET['msg'];
                require('../db/conn.inc');
                // If error upload
                if($msg=='Error'){
                    $msgtbl="<table border=1 class=idtable>
                        <tr>
                            <th align=center>ITEM NUMBER</th>
                            <th align=center>ASSYCD</th>
                            <th align=center>ITDSC</th>
                            <th align=center>ITCLS</th>
                            <th align=center>PLANN</th>
                            <th align=center>PRODUCT</th>
                            <th align=center>SUB PRODUCT</th>
                            <th align=center>LOT SIZE</th>
                            <th align=center>ITEM CATEGORY</th>
                            <th align=center>ITEM TYPE</th>
                            <th align=center>Error Message</th>
                        </tr>";
                    $qse="SELECT * FROM bm008prtmp WHERE StatusItem='E'";
                    $sqlqse=mysqli_query($msqlcon,$qse);
                    while($arx=mysqli_fetch_array($sqlqse)){
                        $eitnbr=$arx['ITNBR'];
                        $eassycd=$arx['ASSYCD'];
                        $eitdsc=$arx['ITDSC'];
                        $eitcls=$arx['ITCLS'];
                        $eplann=$arx['PLANN'];
                        $eproduct=$arx['Product'];
                        $esubproduct=$arx['SubProd'];
                        $elotsize=$arx['Lotsize'];
                        $eitcat=$arx['ITCAT'];
                        $eittyp=$arx['ITTYP'];
                        $eketerangan=$arx['Keterangan'];
                        $msgtbl.="<tr>
                                    <td>$eitnbr</td>
                                    <td>$eassycd</td>
                                    <td>$eitdsc</td>
                                    <td>$eitcls</td>
                                    <td>$eplann</td>
                                    <td>$eproduct</td>
                                    <td>$esubproduct</td>
                                    <td>$elotsize</td>
                                    <td>$eitcat</td>
                                    <td>$eittyp</td>
                                    <td>$eketerangan</td>
                                </tr>";
                    }
                    $msgtbl.="</table>";
                    $qd="DELETE FROM bm008prtmp";
                    mysqli_query($msqlcon,$qd);
                    $msg=$msgtbl;
                }
                
                 
                // If succesfully replace partial data
                if($msg=='Replace'){
                    
                    $msgsuccess='Add and Replace data partial success';
                    $qu3="Replace Into bm008pr ";
		    $qu3=$qu3." SELECT ITNBR, ASSYCD, ITDSC, ITCLS, PLANN, Product, SubProd, Lotsize, ITCAT, ITTYP FROM bm008prtmp WHERE StatusItem !='H'";
                    mysqli_query($msqlcon,$qu3) OR die(mysqli_error());
					//echo $qu3."<br>";
                    $qd="DELETE FROM bm008prtmp";
                    mysqli_query($msqlcon,$qd);
                    $msg=$msgsuccess;
                }
                
                // If succesfully replace partial data
                if($msg=='Confirm'){
                    // Check count 
                    $qc1="SELECT COUNT(*) AS fcount FROM bm008pr";
                    $sqlqc1=mysqli_query($msqlcon,$qc1);
                    $arqc1=mysqli_fetch_array($sqlqc1);
                    $fcount=$arqc1['fcount'];
                        
                    $qc2="SELECT COUNT(*) AS tmpcount FROM bm008prtmp WHERE StatusItem!='H'";
                    $sqlqc2=mysqli_query($msqlcon,$qc2);
                    $arqc2=mysqli_fetch_array($sqlqc2);
                    $tmpcount=$arqc2['tmpcount'];
                        
                        echo "Do you want to replace $fcount with $tmpcount?";
                    ?>
                        <form method="POST" enctype="multipart/form-data" action="db/replace-all.php">
                            <input type="submit" name="yesbtn" value="Yes">
                            <input type="submit" name="nobtn" value="No">
                        </form>
                        <table border="1" class="idtable">
                            <tr>
                                <th align="center">ITEM NUMBER</th>
                                <th align="center">ASSYCD</th>
                                <th align="center">ITEM DESCRIPTION</th>
                                <th align="center">ITCLS</th>
                                <th align="center">PLANN</th>
                                <th align="center">PRODUCT</th>
                                <th align="center">SUB PRODUCT</th>
                                <th align="center">LOT SIZE</th>
                                <th align="center">ITEM CATEGORY</th>
                                <th align="center">ITEM TYPE</th>
                            </tr>
                    <?
                        $qa2="SELECT ITNBR, ASSYCD, ITDSC, ITCLS, PLANN, Product, SubProd, Lotsize, ITCAT, ITTYP FROM bm008prtmp WHERE StatusItem!='H' LIMIT 10";
                        $sqlqa2=mysqli_query($msqlcon,$qa2);
                        while($arrqa2=mysqli_fetch_array($sqlqa2)){
                            $aitnbr=$arrqa2['ITNBR'];
                            $aassycd=$arrqa2['ASSYCD'];
                            $aitdsc=$arrqa2['ITDSC'];
                            $aitcls=$arrqa2['ITCLS'];
                            $aplann=$arrqa2['PLANN'];
                            $aproduct=$arrqa2['Product'];
                            $asubprod=$arrqa2['SubProd'];
                            $alotsize=$arrqa2['Lotsize'];
                            $aitcat=$arrqa2['ITCAT'];
                            $aittyp=$arrqa2['ITTYP'];
                            echo "<tr>
                                    <td>$aitnbr</td>
                                    <td>$aassycd</td>
                                    <td>$aitdsc</td>
                                    <td>$aitcls</td>
                                    <td>$aplann</td>
                                    <td>$aproduct</td>
                                    <td>$asubprod</td>
                                    <td>$alotsize</td>
                                    <td>$aitcat</td>
                                    <td>$aittyp</td>
                                </tr>";
                        }
                        echo "</table>";
                    
                    $msg=$msgsuccess;
                }
				echo '<table width="60%" border="0" align="center" bgcolor="#AD1D36">';
				echo '<tr  class="arial11whitebold"><td align="center">';
				echo $msg;
echo '</td></tr></table>';
                
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
