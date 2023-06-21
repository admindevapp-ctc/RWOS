<?php 

session_start();
require_once('./../core/ctc_init.php'); // add by CTC

if(isset($_SESSION['cusno']))
{       
	if($_SESSION['redir']=='Order-SG'){
		$_SESSION['cusno'];
		$_SESSION['cusnm'];
		$_SESSION['redir'];
		$_SESSION['type'];
		$_SESSION['com'];
		$_SESSION['user'];
		$_SESSION['alias'];
		$_SESSION['tablename'];
    	$_SESSION['custype'];
		$_SESSION['dealer'];
		$_SESSION['group'];
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
		$owner_comp = ctc_get_session_comp(); // add by CTC
	 }else{
		echo "<script> document.location.href='../".redir."'; </script>";
	 }
}else{	
	header("Location:../login.php");
}

include('chklogin.php');
include "crypt.php";

?>

<html>
	<head>
    <title>Denso Ordering System</title>
   	<link rel="stylesheet" type="text/css" href="css/dnia.css">
	<style>
	.loading-screen {
		position: absolute;
		width: 100%;
		height: 100%;
		background-color: rgba(0, 0, 0, 0.5);
		display: flex;
		justify-content: center;
		align-items: center;
		z-index: 9999;
		color: white;
		font-size: 24px;
	}

	.loading-text {
		margin-left: 10px;
	 
	}</style><!--[if IE]>
<style type="text/css"> 
#twocolLeft{ padding-top: 0px; }
#twocolRight { zoom: 1; padding-top:10px; }
</style>	
<![endif]-->


 <link rel="stylesheet" href="themes/ui-lightness/jquery-ui.css">

<script src="lib/jquery-1.4.2.min.js"></script>

<script src="lib/jquery.ui.core.js"></script>
 <script src="lib/jquery.ui.widget.js"></script>
 <script src="lib/jquery.ui.mouse.js"></script>
<script src="lib/jquery.ui.button.js"></script>
<script src="lib/jquery.ui.draggable.js"></script>
<script src="lib/jquery.ui.position.js"></script>
<script src="lib/jquery.ui.resizable.js"></script>
<script src="lib/jquery.ui.dialog.js"></script>
<script src="lib/jquery.effects.core.js"></script>
 <script src="lib/jquery.ui.datepicker.js"></script>
<script src="lib/jquery.ui.autocomplete.js"></script>
<link rel="stylesheet" href="css/demos.css">   
<script type="text/javascript">
	$(document).ready(function() {
       

        $.urlParam = function(name){
			var results = new RegExp('[\?&]' + name + '=([^&#]*)').exec(window.location.href);
			if (results == null){
			return null;
			}
			else {
			return decodeURI(results[1]);
			}
		}

        var msg = $.urlParam('msg');
        if(msg != null){
            $("#frmimport").hide();
            $("#formatexcel").hide();
        }

        $('#frmimport').submit(function(){
			

            if($('#userfile').val()==''){
                alert('Please choose file!');
                return false;
            }
            var filepath = $('#userfile').val();
            var ext = $('#userfile').val().split('.');
            //alert(ext[1]);
            if(ext[1].toLowerCase() != "xls"){
                alert("<?php echo get_lng($_SESSION["lng"], "E0079"); ?>");
                return false;
            }else{
				$('body').prepend('<div class="loading-screen"><div class="spinner"></div><span class="loading-text">Data validation ongoing. Please wait...</span></div>');
			}
        });

        $('#upagain').click(function(){
            $.ajax({
                type: 'POST',
                url: 'db/excel/replace-all-saledenso.php',
                data: {"action" : "delete"},
                success:  function(response){
                    //alert(response);
                    url = "aws_saledenso_import.php";
			        window.location.replace(url);	
                }
            });

		 });

    });

function hide(){
    window.location.href = "aws_saledenso_import.php";
    
}
</script>
</head>
<body>
<?php ctc_get_logo() ?> <!-- add by CTC -->
<div id="mainNav">
    <?
        $_GET['selection']="main";
        include("navhoriz.php");
			
    ?>
</div> 
<div id="isi">
        
    <div id="twocolLeft">
       	<?
		  	$_GET['current']="saledenso";
			include("navUser.php");
			
			

		?>
    </div>
    <div id="twocolRight">
    <span class="arial12Bold" height="40">2 <sup>nd</sup> Customer Sales Condition MA(DENSO) </span><br/><br/>
            <span height="40" id="formatexcel">Download format excel here : <a href="prototype/customersaledenso.xls" target="_blank" download ><img src="images/csv.jpg" width="16" height="16" border="0"></a></span>
            <form method="POST" enctype="multipart/form-data" name="uploadForm" id="frmimport" action='db/excel/upload-saledenso.php' >
            <table class='arial11'>
                <tr height="40">
                    <td>Upload</td>
                    <td>:</td>
                    <td><input type="file" size="45"  id= "userfile" name="userfile"></td>
                </tr>
                <tr height="40">
                    <td colspan="3">
                        <span class="arial21redbold">Please upload your excel file(.xls)</span>
                    </td>
                </tr>
                <tr height="40">
                    <td colspan="3">
                        <span class="arial21redbold">Note : the data will delete all and replace all</span>
                    </td>
                </tr>
                <tr height="40">
                    <td></td>
                    <td></td>
                    <td>
                        <input name="submit" type="submit" value="Submit"> 
                        <input type="reset" value="Reset">
                    </td>
                </tr>
            </table><br/>
            </form>

			<?
				include "../db/conn.inc";
				$flag=$_GET['status'];
				$msg=$_GET['msg'];
				$result=$_GET['result'];
				
				$query = "DELETE FROM `awsexc_temp` WHERE `Owner_Comp` = '$comp';";
				//mysqli_query($msqlcon,$query);
				// If error upload
				
				
				
                if($flag=='E'){
                    $msgtbl="";
                    echo "<br/><br/><input type ='button' id='upagain' onclick='hide()' value='Upload again'>";
             

                    $msgtbl.="<table width='100%' border='1' class='tbl1' cellspacing='0' cellpadding='0'>
 						 <tr class='arial11whitebold' bgcolor='#AD1D36'>
                            <th width='20%' scope='col'>Part Number</th>
                            <th width='15%' scope='col'>2<sup>nd</sup> Customer Group</th>
                            <th width='10%' scope='col'>Condition</th>
                            <th width='15%' scope='col'>Currency Code</th>
                            <th width='15%' scope='col'>2<sup>nd</sup>Customer Price (Optional)</th>
                            <th width='25%' scope='col'>Error Message</th>
                        </tr>";
                    $qse="SELECT * FROM awsexc_temp WHERE Owner_Comp='$owner_comp' and cusno1='$cusno' ";
					//echo $qse;
                    $sqlqse=mysqli_query($msqlcon,$qse);
                    while($arx=mysqli_fetch_array($sqlqse)){
                        $cusno1=$arx['cusno1'];
						$itnbr=$arx['itnbr'];
						$sell=$arx['sell'];
						$cusgrp=$arx['cusgrp'];
						$price=$arx['price'];
						$curr=$arx['curr'];
						$error=$arx['error'];


                        switch ($sell) {
                            case "1":
                              $selltext =  "Sell";
                              break;
                            case "0":
                              $selltext =  "Not sell";
                              break;
                            default:
                              $selltext =  "Not sell";
                          }


                        $msgtbl.="<tr class='arial11black'>
                                    <td align=\"Center\">$itnbr</td>
                                    <td align=\"Center\">$cusgrp</td>
                                    <td align=\"Center\">$selltext</td>
                                    <td align=\"Center\">$curr</td>
                                    <td align=\"Right\" style=\"padding-right: 5px;\">$price</td>
                                    <td>$error</td>
                                </tr>";
                    }
                    $msgtbl.="</table>";
                    $msgtbl.="</table><br/><br/>";
                    if( $msg!=''){
					    $msgtbl.="<span class=\"arial21redbold\">Error : ".$msg."</span>";
                    }
					$msgtbl.="<br/><br/><span class=\"arial21redbold\">Note : Data not upload to system</span>";
                    // /$qd="DELETE FROM awsexc_temp WHERE Owner_Comp='$comp' and cusno1='$cusno'";
                    //mysqli_query($msqlcon,$qd);
                    $msg=$msgtbl;
                }
                if($flag=='X'){
                    echo "<input type ='button' id='upagain' onclick='hide()' value='Upload again'>";
                   
                    echo "<br/><br/><span class=\"arial21redbold\">Note : Data not upload to system</span>";
                    
                }
                if($msg=='Confirm'){
                    // Check count 
                    $qc1="SELECT COUNT(*) AS fcount FROM awsexc_temp WHERE Owner_Comp='$comp' and cusno1='$cusno'";
                    $sqlqc1=mysqli_query($msqlcon,$qc1);
                    $arqc1=mysqli_fetch_array($sqlqc1);
                    $fcount=$arqc1['fcount'];

                        echo "<span class=\"arial21redbold\">Do you want to Upload data?</span>";
                    ?>
                        <form method="POST" enctype="multipart/form-data" action="db/excel/replace-all-saledenso.php">
                            <input type="submit" name="yesbtn" value="Yes">
                            <input type="submit" name="nobtn" value="No">
                        </form>
						<br>
						<?php  echo "<span class=\"arial21redbold\">Displayed 10 from ". $fcount . "  records!</span>"; ?>
                        <table width='100%' border='1' class='idtable' cellspacing='0' cellpadding='0'>
                            <tr class='arial11whitebold' bgcolor='#AD1D36'>
                                <th width='20%' scope='col'>Part Number</th>
                                <th width='15%' scope='col'>2<sup>nd</sup> Customer Group</th>
                                <th width='10%' scope='col'>Condition</th>
                                <th width='15%' scope='col'>Currency Code</th>
                                <th width='15%' scope='col'>2<sup>nd</sup>Customer Price (Optional)</th>
                                <th width='25%' scope='col'>Error Message</th>
                            </tr>
                    <?
                    $qa2="SELECT * FROM awsexc_temp WHERE Owner_Comp='$owner_comp' and cusno1='$cusno' LIMIT 10";
                        //echo $qa2;
                        $sqlqa2=mysqli_query($msqlcon,$qa2);
                        while($arrqa2=mysqli_fetch_array($sqlqa2)){
                            $cusno1=$arrqa2['cusno1'];
                            $itnbr=$arrqa2['itnbr'];
                            $sell=$arrqa2['sell'];
                            $cusgrp=$arrqa2['cusgrp'];
                            $price=$arrqa2['price'];
                            $curr=$arrqa2['curr'];
                            $error=$arrqa2['error'];

                            switch ($sell) {
                                case "1":
                                  $selltext =  "Sell";
                                  break;
                                case "0":
                                  $selltext =  "Not sell";
                                  break;
                                default:
                                  $selltext =  "Not sell";
                              }

                           echo "<tr class='arial11black'>
                                        <td align=\"Center\">$itnbr</td>
                                        <td align=\"Center\">$cusgrp</td>
                                        <td align=\"Center\">$selltext</td>
                                        <td align=\"Center\">$curr</td>
                                        <td align=\"Right\"  style=\"padding-right: 5px;\">$price</td>
                                        <td>$error</td>
                                    </tr>";
                        }
                        echo "</table>";
                    
                    $msg=$msgsuccess;
                }
              
                if($msg!='Error' && $msg!=''){
					echo "<p>";
                    echo '<table width="90%" border="0" align="center">';
                    echo '<tr  class="arial11whitebold">';
 					echo '<td  align="center">'.$msg.'</td>';
 					echo '</tr></table>';
					
				}
                
            ?>

<div id="footerMain1">
	<ul>
     
          
    </ul>
    <div id="footerDesc">
        <p>
        Copyright © 2023 DENSO . All rights reserved  
        </p>
    </div>
</div>
</div>

	</body>
</html>

