<?php 

session_start();
require_once('./../core/ctc_init.php'); // add by CTC
require_once('../language/Lang_Lib.php');

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
	</style><!--[if IE]>
<style type="text/css"> 
#twocolLeft{ padding-top: 0px; }
#twocolRight { zoom: 1; padding-top:10px; }
</style>	
<![endif]-->

<script src="lib/jquery-1.4.2.js"></script>
<link rel="stylesheet" href="css/base/jquery.ui.all.css">
	<script src="lib/jquery.bgiframe-2.1.2.js"></script>
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
		$('#ConvExcel').click(function(){
			let cusno1 = $('.hid_cus1').val();
			let cusno2 = $.urlParam('cusno2');
			let cusname = $.urlParam('cusname');
			let cusgroup = $.urlParam('cusgroup');
			url= 'aws_exportgroupma.php?cusno1='+ cusno1 + '&cusno2=' +cusno2 + '&cusname=' + cusname + '&cusgroup=' +cusgroup;
            //url= 'admin/aws_cusmas_gettblXLS.php?cusno1='+ cusno1 + '&cusno2=' +cusno2 + '&cusname=' + cusname + '&cusgroup=' +cusgroup;
			window.open(url);	
		 });	
     });
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
		  	$_GET['current']="aws_groupma";
			include("navUser.php");
		?>
    </div>
    <div id="twocolRight">
	<input type="hidden" class="hid_cus1" value="<?=$cusno?>">
    <?
		  require('../db/conn.inc');
		 
		  //$cusno1= '<select name="sslcusno1" id="sslcusno1" class="arial11blue"  onchange="changecuscode1()" style="width: 100%" >';
		  $cusno1= '<select name="cusno1" id="cusno1" class="arial11blue" style="width: 100%" >';
		  $cusno1= $cusno1 .  ' <option value="">select option</option>';
		  //$query="select Cusno, Cusnm from cusmas cm where cusmas.Custype = 'D'  and Owner_Comp='$comp'";
		  $query="SELECT distinct cusmas.Cusno, cusmas.Cusnm
		  	FROM awscusmas join cusmas on awscusmas.cusno1 = cusmas.Cusno  and awscusmas.Owner_Comp = cusmas.Owner_Comp
			where awscusmas.Owner_Comp='$comp' AND cusno1 = '$cusno'";
		//echo $query;
		  $sql=mysqli_query($msqlcon,$query);	
		  while($hasil = mysqli_fetch_array ($sql)){
			$ycusno=$hasil['Cusno'];
			$ycusnm=$hasil['Cusnm'];
			$ycusno1=$_GET['cusno1'];
			$selected1 = ($hasil["Cusno"] == $ycusno1) ? "selected" : "";
			$cusno1= $cusno1 .  ' <option  value="'.$ycusno.'" '.$selected1.'>'.$ycusno. '</option>';
		  }
		  $cusno1= $cusno1 . ' </select>';

		  //$cusno2= '<select name="sslcusno2" id="sslcusno2" class="arial11blue" onchange="changecuscode2()"  style="width: 100%">';
		  $cusno2= '<select name="cusno2" id="cusno2" class="arial11blue"  style="width: 100%">';
		  $cusno2= $cusno2 .  ' <option value="">select option</option>';
		  $query1="select distinct cusno2, cusgrp from awscusmas where Owner_Comp='$comp' AND cusno1 = '$cusno'";
         // echo $query;
		  $sql1=mysqli_query($msqlcon,$query1);	
		  while($hasil = mysqli_fetch_array ($sql1)){
			$ycusno2=$hasil['cusno2'];
			$ycusgrp2=$hasil['cusgrp'];
			$ycusno_2=$_GET['cusno2'];
			$selected2 = ($hasil["cusno2"] == $ycusno_2) ? "selected" : "";
			$cusno2= $cusno2 .  ' <option value="'.$ycusno2.'" '.$selected2.'>'.$ycusno2. '</option>';
		  }
		  $cusno2= $cusno2 . ' </select>';
	 ?>
        <table width="97%" border="0" cellspacing="0" cellpadding="0">
            <tr class="arial11blackbold">
                <td>&nbsp;</td>
                <td width="19%">&nbsp;</td>
                <td width="25%">&nbsp;</td>
                <td width="3%"></td>
                <td width="19%">&nbsp;</td>
                <td width="1%">&nbsp;</td>
                <td width="30%">&nbsp;</td>
            </tr>
            <tr class="arial11blackbold">
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td></td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
            </tr>
            <tr class="arial11blackbold">
                <td width="3%"><img src="../images/calendar.gif" width="16" height="15"></td>
                <td colspan="6" class="arial21redbold"> 2 <sup>nd</sup> Customer Group MA</td>
                </tr>
            <tr class="arial11blackbold">
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td></td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
            </tr>
        </table>    
        <?php
        if(isset($_GET["button"])){
            $xcusno1=$_GET["cusno1"];
            $xcusno2=$_GET["cusno2"];
            $xcusname=$_GET["cusname"];
            $xcusgrp=$_GET["cusgroup"];
        }
        ?>
        <form name ="search" method="get">
            <fieldset style="width:98%">
            <legend> &nbsp;Search Information</legend>
            <table width="97%" border="0" cellspacing="0" cellpadding="0">
                <tr class="arial11blackbold">
                    <td width="16%"><div align="right"><span class="arial12BoldGrey"><?php echo get_lng($_SESSION["lng"], "L0588"); ?></div></td>
                    <td width="2%"><div align="center"><span class="arial12Bold">:</span></div></td>
                    <td width="15%"><span class="arial12Bold"><? echo $cusno2 ?></span></td>
                    <td width="2%"></td>
                    <td width="16%"><div align="right"><span class="arial12BoldGrey"><?php echo get_lng($_SESSION["lng"], "L0004"); ?><!--Customer Name --></div></td>
                    <td width="2%"><div align="center"><span class="arial12Bold">:</span></div></td>
                    <td width="15%"><span class="arial12Bold"></span>
                        <?php 
                        if($xcusname != ""){
                            echo '<input type="text" name="cusname" id="cusname" value="'.$xcusname.'" style="width: 100%" />';
                        }
                        else{
                            echo '<input type="text" name="cusname" id="cusname" style="width: 100%" />';
                        }
                        
                        ?>
                    </td>
                    <td colspan="3" width="32%">&nbsp;</td>
                </tr>
                <tr class="arial11blackbold">
                    <td width="16%"><div align="right"><span class="arial12BoldGrey"><?php echo get_lng($_SESSION["lng"], "L0589"); ?><!-- Customer Group --></span></div></td>
                    <td width="2%"><div align="center"><span class="arial12Bold">:</span></div></td>
                    <td width="15%"><span class="arial12Bold"></span>
                        <?php 
                        if($xcusgrp != ""){
                            echo '<input type="text" name="cusgroup" id="cusgroup" value="'.$xcusgrp.'" style="width: 100%" />';
                        }
                        else{
                            echo '<input type="text" name="cusgroup" id="cusgroup" style="width: 100%"/>';
                        }
                        
                        ?>
                    </td>
                    <td width="2%"></td>
                    <td width="16%"></td>
                    <td width="2%"></td>
                    <td width="15%"></td>
                    <td width="2%"></td>
                    <td width="19%"><input type="submit" name="button" id="button" value="Search" class="arial11"></td>
                    <td width="11%">&nbsp;</td>
                </tr>
                <tr class="arial11blackbold">
                    <td></td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td></td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                </tr>
            </table>
            </fieldset>
        </form>
        <table width="100%" border="0" cellspacing="0" cellpadding="0">
            <tr valign="middle" class="arial11">
            	<th scope="col">&nbsp;</th>
                <th width="90" scope="col">
                </th>
				<th width="10"></th>
                <th width="90" scope="col">
                </th>
				<th width="10"></th>
                <th width="170" scope="col">
                    <div id="ConvExcel" style='background-color: #AD1D36;font-size:9pt;color: #FFFFFF;height:22px;cursor:pointer;'>
                        <img src="../images/excel.jpg" width="18" height="18" style='float:left;margin-left:4px;margin-top:1px;'>
                        <font style='margin-right:18px;line-height:22px;'><?php echo get_lng($_SESSION["lng"], "L0346"); ?></font>
                    </div>
                </th>
            </tr>
            <tr height="5"><td colspan="5"></td><tr>
        </table>
        <table width="100%"  class="tbl1" cellspacing="0" cellpadding="0">
            <tr class="arial11whitebold" bgcolor="#AD1D36" >
                <th width="7%" height="30" scope="col" rowspan="2"><?php echo get_lng($_SESSION["lng"], "L0457"); ?><!--Company Code--></th>
                <!--<th width="15%" height="30" scope="col" colspan="2">1 <sup>st </sup> Customer</th>-->
                <th width="15%" height="30" scope="col" colspan="2"><?php echo get_lng($_SESSION["lng"], "L0588"); ?><!--2nd customer code--></th>
                <th width="20%" scope="col" rowspan="2"><?php echo get_lng($_SESSION["lng"], "L0398"); ?><!--Customer Name--></th>
                <th width="7%" scope="col" rowspan="2"><?php echo get_lng($_SESSION["lng"], "L0590"); ?><!--2nd customer group--></th>
                <th width="10%" scope="col" rowspan="2"><?php echo get_lng($_SESSION["lng"], "L0400"); ?><!--ship to address--></th>
                <th width="10%" scope="col" rowspan="2"><?php echo get_lng($_SESSION["lng"], "L0323"); ?><!--Email--> 1</th>
                <th width="10%" scope="col" rowspan="2"><?php echo get_lng($_SESSION["lng"], "L0323"); ?><!--Email--> 2</th>
                <th width="10%" scope="col" rowspan="2"><?php echo get_lng($_SESSION["lng"], "L0323"); ?><!--Email--> 3</th>
                <th width="7%" scope="col" rowspan="2"><?php echo get_lng($_SESSION["lng"], "L0467"); ?><!--Action--></th>
            </tr>
            <tr class="arial11whitebold" bgcolor="#AD1D36" >
             <!--   <th width="10%" height="30" scope="col">Customer CD</th>
                <th width="5%" height="30" scope="col">Ship to</th>-->
                <th width="10%" style="    height: 25px;" scope="col"><?php echo get_lng($_SESSION["lng"], "L0591"); ?><!--customer CD--></th>
                <th width="10%" style="    height: 25px;" scope="col"><?php echo get_lng($_SESSION["lng"], "L0043"); ?><!--Ship to--></th>
            </tr>

    <?
        require('../db/conn.inc');
            
        $per_page=10;
        $num=5;
        $criteria=" where awscusmas.Owner_Comp='$comp' AND cusno1 = '$cusno'";
        if(isset($_GET["button"])){
            if(trim($xcusno1)!=''){
                $criteria .= ' and cusno1="'.$xcusno1.'"';	
            }
            if(trim($xcusno2)!=''){
                $criteria .= ' and cusno2="'.$xcusno2.'"';
            }
            if(trim($xcusname)!=''){
                $criteria .= ' and Cusnm like "%'.$xcusname.'%"';
            }
            if(trim($xcusgrp)!=''){
                $criteria .= ' and cusgrp="'.$xcusgrp.'"';
            }
        }
        $query="select  awscusmas.* ,cusmas.Cusnm from awscusmas join cusmas on awscusmas.cusno2 = cusmas.Cusno and awscusmas.Owner_Comp = cusmas.Owner_Comp". $criteria;
        // echo $query;
        $sql=mysqli_query($msqlcon,$query);
        $count = mysqli_num_rows($sql);
        
        $pages = ceil($count/$per_page);
        $page = $_GET['page'];
        if($page){ 
            $start = ($page - 1) * $per_page; 			
        }else{
            $start = 0;	
            $page=1;
        }
            
        $query1="select  awscusmas.* ,cusmas.Cusnm from awscusmas join cusmas on awscusmas.cusno2 = cusmas.Cusno and awscusmas.Owner_Comp = cusmas.Owner_Comp". $criteria . " order by cusno1".		
            " LIMIT $start, $per_page";
        //echo $query1;
        $sql=mysqli_query($msqlcon,$query1);	
                if( ! mysqli_num_rows($sql) ) {
                    echo "<tr height=\"30\"><td colspan=\"10\" align=\"center\" class=\"arial12BoldGrey\">" . get_lng($_SESSION["lng"], "E0060") /*No Data Found.... ! */ . "</td></tr>";
                }
                while($hasil = mysqli_fetch_array ($sql)){
                    $vowner=$hasil['Owner_Comp'];
                    $vcusno1=$hasil['cusno1'];
                    $vshpto1=$hasil['ship_to_cd1'];
                    $vcusno2=$hasil['cusno2'];
                    $vshpto2=$hasil['ship_to_cd2'];
                    $vcusnm2=$hasil['Cusnm'];
                    $vcusgrp=$hasil['cusgrp'];
                    $vshpaddr1=$hasil['ship_to_adrs1'];
                    $vshpaddr2=$hasil['ship_to_adrs2'];
                    $vshpaddr3=$hasil['ship_to_adrs3'];
                    $vmail1=$hasil['mail_add1'];
                    $vmail2=$hasil['mail_add2'];
                    $vmail3=$hasil['mail_add3'];
				
				$addres_all = $vshpaddr1." ".$vshpaddr2." ".$vshpaddr3;
				
				if (mb_strlen($addres_all, 'UTF-8') > 20) {
					$title_vshpaddr1 = $addres_all;
					$addres_all = mb_substr($addres_all, 0, 20, 'UTF-8') . "...";
				} else {
					$title_vshpaddr1 = '';
				}
				if(strlen($vmail1) > 20){
					$title_vmail1 = $vmail1;
                    $vmail1 = substr($vmail1,0,20) ."...";
                }else{
					$title_vmail1 = '';
				}
				if(strlen($vmail2) > 20){
					$title_vmail2 = $vmail2;
                    $vmail2 = substr($vmail2,0,20) ."...";
                }else{
					$title_vmail2 = '';
				}
				if(strlen($vmail3) > 20){
					$title_vmail3 = $vmail3;
                    $vmail3 = substr($vmail3,0,20) ."...";
                }else{
					$title_vmail3 = '';
				}
				echo "<tr class=\"arial11black\" align=\"center\" height=\"30\">";
                echo "<td>".$vowner."</td>";
               // echo "<td>".$vcusno1."</td>";
              //  echo "<td>".$vshpto1."</td>";
                echo "<td>".$vcusno2."</td>" ;
				echo "<td>".$vshpto2."</td>";
				echo "<td>".$vcusnm2."</td>";
				echo "<td>".$vcusgrp."</td>";
				echo "<td title='$title_vshpaddr1'>".$addres_all."</td>";
				echo "<td align=\"center\" title='$title_vmail1'>".$vmail1."</td>";
				echo "<td align=\"center\" title='$title_vmail2'>".$vmail2."</td>";
				echo "<td align=\"center\" title='$title_vmail3'>".$vmail3."</td>";
				echo "<td class=\"lasttd\">";
				echo "<a href='aws_updgroupma.php?cusno1=".$vcusno1."&cusno2=".$vcusno2."&shp1=".$vshpto1."&shp2=".$vshpto2."' > <img src=\"../images/edit.png\" width=\"20\" height=\"20\" border=\"0\"></a> ";
				echo "<td ></tr>";
			
			}
			
			require('pager.php');
            if($count>$per_page){		
                echo "<tr height=\"30\"><td colspan=\"10\" align=\"right\" class=\"lasttd\"><div id=\"pagination\" >";
                //echo $query;
                $fld="page";
                paging($query,$per_page,$num,$page);
                echo "</div></td></tr>";

            }   
		
		
	?>

        </table>

<div id="footerMain1">
	<ul>
      
     
          
      </ul>

    <div id="footerDesc">

	<p>
	Copyright © 2023 DENSO . All rights reserved  
	
  </div>
</div>
          </div>

	</body>
</html>

