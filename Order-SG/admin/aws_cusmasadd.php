<?php 

session_start();
require_once('./../../core/ctc_init.php'); // add by CTC
require_once('./../../language/Lang_Lib.php'); /*Page : mylogon.php*/

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
    $comp = ctc_get_session_comp();
		if($type!='a'){
      header("Location:../main.php");
    }
	 }else{
      echo "<script> document.location.href='../../".redir."'; </script>";
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
 <style type="text/css">
<!--

#pagination a 
{
	list-style: none;
	margin-right: 5px;
	padding:5px;
	color:#333;
	text-decoration: none;
	background-color: #F3F3F3;
	font-family: Verdana, Geneva, sans-serif;
	font-size: 10px;
}
#pagination a:hover 
{
color:#FF0084;
cursor: pointer;
}

#pagination a.current 
{
	list-style: none;
	margin-right: 5px;
	padding:5px;
	color:#FFF;
	background-color: #000;
}

#pagination1 a 
{
	list-style: none;
	margin-right: 5px;
	padding:5px;
	color:#333;
	text-decoration: none;
	background-color: #F3F3F3;
	font-family: Verdana, Geneva, sans-serif;
	font-size: 10px;
}
#pagination1 a:hover 
{
color:#FF0084;
cursor: pointer;
}

#pagination1 a.current 
{
	list-style: none;
	margin-right: 5px;
	padding:5px;
	color:#FFF;
	background-color: #000;
}


-->
.vgroup{
	background-color: lightgray;
    border: 1px solid;
}
 </style>


<script type="text/javascript" language="javascript" src="../lib/jquery-1.4.2.js"></script>
<script type="text/javascript">

function changecuscode1(cusno) {
	
  if (cusno){
    var url_string = window.location.href; 
    var url = new URL(url_string);
    var p_shp1 = url.searchParams.get("shp1");
    var p_cusno2 = url.searchParams.get("cusno2");
    var cuscode1 = cusno;
    $.ajax({
      type: 'GET',
      url: 'aws_cusmasget.php',
      data: "curcd1="+cuscode1,
      success: function(data) {
		  		

        Array_data = data.split(",");
        //shipto
        shopto_cusno1 = Array_data[0].replace("cus1_shpto=", "");
        //alert(shopto_cusno1);
        Array_shpto = shopto_cusno1.split(":");
        var shLength = Array_shpto.length;
        $("#ssl_shpto1").empty().append("<option value = ''></option>");
        select = document.getElementById('ssl_shpto1');
        for (var i = 0; i < shLength; i++) {
            if(i<shLength-1){
              //alert(Array_shpto[i]);

              var opt = document.createElement('option');
              opt.value = Array_shpto[i];
              opt.innerHTML = Array_shpto[i];
              select.appendChild(opt);
            }
        }
        $("#ssl_shpto1").val(p_shp1);
        //cusno2
        cusno2 = Array_data[1].replace("cus2_cd=", "");
        Array_cusno2 = cusno2.split(":");
        var arrayLength = Array_cusno2.length;
        $("#ssl_cusno2").empty().append("<option value = ''></option>");
        select = document.getElementById('ssl_cusno2');
        for (var i = 0; i < arrayLength; i++) {
            if(i<arrayLength-1){
              //alert(Array_cusno2[i]);
              var datacusno2 = Array_cusno2[i].replace(" ", "").split("-");
              
              var opt = document.createElement('option');
              opt.value = datacusno2[0];
              opt.innerHTML = datacusno2[0] + "-" + datacusno2[1];
              select.appendChild(opt);
            }
        }
        $("#ssl_cusno2").val(p_cusno2);
      }
    });
  }
  else{
    var cuscode1 = $("#vcusno1").val();
    $.ajax({
      type: 'GET',
      url: 'aws_cusmasget.php',
      data: "curcd1="+cuscode1,
      success: function(data) {
        Array_data = data.split("||");
		console.log(Array_data);
        //shipto
        shopto_cusno1 = Array_data[0].replace("cus1_shpto=", "");
        //alert(shopto_cusno1);
        Array_shpto = shopto_cusno1.split(":");
        var shLength = Array_shpto.length;
        $("#ssl_shpto1").empty().append("<option value = ''></option>");
        select = document.getElementById('ssl_shpto1');
        for (var i = 0; i < shLength; i++) {
            if(i<shLength-1){
              //alert(Array_shpto[i]);

              var opt = document.createElement('option');
              opt.value = Array_shpto[i];
              opt.innerHTML = Array_shpto[i];
              select.appendChild(opt);
            }
        }
        
        //cusno2
        cusno2 = Array_data[1].replace("cus2_cd=", "");;
        //alert(cusno2);
        Array_cusno2 = cusno2.split(":");
        var arrayLength = Array_cusno2.length;
        $("#ssl_cusno2").empty().append("<option value = ''></option>");
        select = document.getElementById('ssl_cusno2');
        for (var i = 0; i < arrayLength; i++) {
            if(i<arrayLength-1){
              //alert(Array_cusno2[i]);
              var datacusno2 = Array_cusno2[i].replace(" ", "").split("-");
              
              var opt = document.createElement('option');
              opt.value = datacusno2[0];
              opt.innerHTML = datacusno2[0] + "-" + datacusno2[1];
              select.appendChild(opt);
            }
        }
      }
    });
  }			
}

function changecuscode2(){
  var cusnm2 = $("#ssl_cusno2 option:selected").text();
	var Array_cusno2 = cusnm2.split("-");
  $("#vcusnm2").val(Array_cusno2[1]);
}

	$(document).ready(function() {
	if($("#vcusno1").val() != ""){
    changecuscode1($("#vcusno1").val());
  }

	$().ajaxStart(function() {
			$('#result').empty().html('<img src="images/35.gif" />');
	}).ajaxStop(function() {
		//$('#loading').hide();
		$('#result').fadeIn('slow');
	});
		
	$('#frmupdawscusmas').submit(function() {

    var type = $("input[name=vtype]").val();
    var dealer = $("input[name=vdealer]").val();
    var cusno = $("input'[name=vcusno]").val()
    if(type == 'A'){
      if(dealer == cusno){
        var error = '<?php echo get_lng($_SESSION["lng"], "W0029"); ?>';
        alert(error);
        return false;
      }
    }else{
      $.ajax({
        type: 'POST',
        url: $(this).attr('action'),
        data: $(this).serialize(),
        success: function(data) {
          $('#result').html(data);
        }
      });
      
      return false;
    }
	});	
		
   });
		
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
			  	      $_GET['current']="awscusmas";
				        include("navAdm.php");
			        ?>
            </div>
        <div id="twocolRight">
        <?
		      require('../db/conn.inc');
		
		      $action=trim($_GET['action']);
		      //echo $action;
          if(trim($_GET['action'])=='add'){

            $inpcusno1= '<select name="vcusno1" id="vcusno1" class="arial11blue"  onchange="changecuscode1()" required>';
            $inpcusno1= $inpcusno1 .  ' <option value=""></option>';
            // $query="SELECT DISTINCT awscusmas.cusno1 as 'Cusno', cusmas.Cusnm from awscusmas INNER JOIN cusmas on cusmas.Cusno = awscusmas.cusno1 WHERE awscusmas.`Owner_Comp` = '$comp'";
			$query = "SELECT Cusno ,Cusnm FROM `cusmas` WHERE 1 AND `Custype` = 'D' AND xDealer IN (SELECT DISTINCT xDealer FROM cusmas WHERE xDealer != Cusno and Custype = 'A' AND `Owner_Comp` = '$comp') AND `Owner_Comp` = '$comp'";
            $sql=mysqli_query($msqlcon,$query);	
            while($hasil = mysqli_fetch_array ($sql)){
              $ycusno=$hasil['Cusno'];
              $ycusnm=$hasil['Cusnm'];
                $inpcusno1= $inpcusno1 .  ' <option value="'.$ycusno.'">'.$ycusno.' - '. $ycusnm. ' </option>';
            }
            $inpcusno1= $inpcusno1 . ' </select>';
            $inpshp1 = '<select id="ssl_shpto1" name="ssl_shpto1" class="arial11blue" required></select>';
            $inpcusshpto2 = "<input type=\"text\"  name=\"vcusshpto2\" class=\"arial11black\" maxlength=\"8\" size=\"10\" required></input>";
            $inpgroup="<input type=\"text\"  name=\"vgroup\" class=\"arial11black vgroup\" maxlength=\"3\" size=\"3\" readonly=\"true\" disabled></input>";
            $inpcusnm2="<input type=\"text\" name=\"vcusnm2\"  id=\"vcusnm2\"class=\"arial11blackbold\" readonly=\"true\" >";
            $inpshp2 = '<select id="ssl_cusno2" name="ssl_cusno2" class="arial11blue" onchange="changecuscode2()" required></select>';
            $inpaddr1="<input type=\"text\"  name=\"vaddr1\" class=\"arial11black\" maxlength=\"45\" size=\"45\" required></input>";
            $inpaddr2="<input type=\"text\"  name=\"vaddr2\" class=\"arial11black\" maxlength=\"45\" size=\"45\"></input>";
            $inpaddr3="<input type=\"text\"  name=\"vaddr3\" class=\"arial11black\" maxlength=\"45\" size=\"45\"></input>";
            $inpemail1="<input type=\"email\" name=\"vemail1\"  class=\"arial11black\" maxlength=\"256\" size=\"45\" required></input>";
            $inpemail2="<input type=\"email\" name=\"vemail2\"  class=\"arial11black\" maxlength=\"256\" size=\"45\"></input>";
            $inpemail3="<input type=\"email\" name=\"vemail3\"  class=\"arial11black\" maxlength=\"256\" size=\"45\"></input>";
            $inpaction="<input type=\"hidden\" name=\"vaction\" value=".$action."></input>";	
          }else{
            $vcusno=trim($_GET['cusno1']);
            $vcusno2=trim($_GET['cusno2']);
            $vcusshp1=trim($_GET['shp1']);
            $vcusshp2=trim($_GET['shp2']);
            
            $query="select awscusmas.* ,cusmas.Cusnm from awscusmas JOIN cusmas ON awscusmas.cusno2 = cusmas.Cusno AND awscusmas.Owner_Comp = cusmas.Owner_Comp where cusno1='". $vcusno."' and cusno2 = '". $vcusno2."' and ship_to_cd1 = '". $vcusshp1."' and ship_to_cd2 = '". $vcusshp2."' and awscusmas.Owner_Comp='".$comp."'";
            //echo $query;
            $sql=mysqli_query($msqlcon,$query);	
            if($hasil = mysqli_fetch_array ($sql)){
              $cusno1=$hasil['cusno1'];
              $shiptocd1=$hasil['ship_to_cd1'];
              $cusno2=$hasil['cusno2'];
              $cusnm2=$hasil['Cusnm'];
              $shiptocd2=$hasil['ship_to_cd2'];
              $cusgrp=$hasil['cusgrp'];
              $shipadrs1=$hasil['ship_to_adrs1'];
              $shipadrs2=$hasil['ship_to_adrs2'];
              $shipadrs3=$hasil['ship_to_adrs3'];
              $cusmail1=$hasil['mail_add1'];
              $cusmail2=$hasil['mail_add2'];
              $cusmail3=$hasil['mail_add3'];
              
              $inpcusno1="<input type=\"text\"  name=\"vcusno1\" id=\"vcusno1\"  class=\"arial11blackbold\" style=\"width: 100px\"  readonly=\"true\" value =".$cusno1." required>";
              $inpshp1 = "<input type=\"text\"  name=\"ssl_shpto1\" id=\"ssl_shpto1\"  class=\"arial11blackbold\" style=\"width: 100px\"  readonly=\"true\" value =".$shiptocd1." required>";
              $inpcusshpto2 = "<input type=\"text\"  name=\"vcusshpto2\" class=\"arial11black\" maxlength=\"8\" size=\"10\" value=".$shiptocd2." readonly=\"true\" required></input>";
            //  $inpshp2 = '<select id="ssl_cusno2" name="ssl_cusno2" class="arial11blue" onchange="changecuscode2()" disabled=\"true\"></select>';
              $inpshp2 =  "<input type=\"text\"  name=\"ssl_cusno2\" id=\"ssl_cusno2\"  class=\"arial11blackbold\" style=\"width: 100px\"  readonly=\"true\" value=".$cusno2." required >";
              $inpgroup="<input type=\"text\"  name=\"vgroup\" class=\"arial11black vgroup\" maxlength=\"3\" size=\"3\" value='".$cusgrp."' readonly=\"true\"></input>";
              $inpcusnm2="<input type=\"text\" name=\"vcusnm2\"  id=\"vcusnm2\" class=\"arial11blackbold\" value='".$cusnm2."' readonly=\"true\" >";
              $inpaddr1="<input type=\"text\"  name=\"vaddr1\" class=\"arial11black\" maxlength=\"45\" size=\"45\" value='".$shipadrs1."' required></input>";
              $inpaddr2="<input type=\"text\"  name=\"vaddr2\" class=\"arial11black\" maxlength=\"45\" size=\"45\" value='".$shipadrs2."'></input>";
              $inpaddr3="<input type=\"text\"  name=\"vaddr3\" class=\"arial11black\" maxlength=\"45\" size=\"45\" value='".$shipadrs3."'></input>";
              $inpemail1="<input type=\"email\" name=\"vemail1\"  class=\"arial11black\" maxlength=\"256\" size=\"45\" value='".$cusmail1."'required></input>";
              $inpemail2="<input type=\"email\" name=\"vemail2\"  class=\"arial11black\" maxlength=\"256\" size=\"45\" value='".$cusmail2."'></input>";
              $inpemail3="<input type=\"email\" name=\"vemail3\"  class=\"arial11black\" maxlength=\"256\" size=\"45\" value='".$cusmail3."'></input>";

              $inpaction="<input type=\"hidden\" name=\"vaction\" value=".$action."></input>";			
              
            }
          }
		
		    ?>
<form id="frmupdawscusmas" name="frmupdawscusmas" method="post" action="aws_updcusmas.php">
            
<table width="97%" border="0" cellspacing="0" cellpadding="0">
  <tr class="arial11blackbold">
    <td width="2%">&nbsp;</td>
    <td width="15%">&nbsp;</td>
    <td width="1%">&nbsp;</td>
    <td width="15%"></td>
    <td width="15%">&nbsp;</td>
    <td width="1%">&nbsp;</td>
    <td width="15%">&nbsp;</td>
  </tr>
  <tr class="arial11blackbold">
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr class="arial11blackbold">
    <td width="3%"><img src="../images/calendar.gif" width="16" height="15"></td>
    <td colspan="5" class="arial21redbold">2 <sup>nd</sup> Customer MA</td>
    </tr>
  <tr class="arial11blackbold">
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr class="arial11blackbold">
    <td>&nbsp;</td>
    <td>1 <sup>st</sup>Customer Code</td>
    <td>:</td>
    <td> <? echo $inpcusno1 ?></td>
    <td>Ship to address1</td>
    <td>:</td>
    <td><? echo $inpaddr1 ?></td>
  </tr>
  <tr class="arial11blackbold">
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr class="arial11blackbold">
    <td>&nbsp;</td>
    <td>1 <sup>st</sup>Customer Ship to Code</td>
    <td>:</td>
    <td><?php echo $inpshp1 ?></td>
    <td>Ship to address2</td>
    <td>:</td>
    <td colspan="4"><? echo $inpaddr2 ?></td>
  </tr>
  <tr class="arial11blackbold">
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr class="arial11blackbold">
    <td>&nbsp;</td>
    <td>2 <sup>nd</sup>Customer Code</td>
    <td>:</td>
    <td><?php echo $inpshp2 ?></td>
    <td>Ship to address3</td>
    <td>:</td>
    <td colspan="4"><? echo $inpaddr3 ?></td>
  </tr>
  <tr class="arial11blackbold">
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr class="arial11blackbold">
    <td>&nbsp;</td>
    <td>2 <sup>nd</sup>Customer Ship to Code</td>
    <td>:</td>
    <td> <? echo $inpcusshpto2 ?></td>
    <td>E-mail 1</td>
    <td>:</td>
    <td> <? echo $inpemail1 ?></td>
  </tr>
  <tr class="arial11blackbold">
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr class="arial11blackbold">
    <td>&nbsp;</td>
    <td>Customer Group</td>
    <td>:</td>
    <td> <? echo $inpgroup ?></td>
    <td>E-mail 2</td>
    <td>:</td>
    <td> <? echo $inpemail2 ?></td>
  </tr>
  <tr class="arial11blackbold">
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr class="arial11blackbold">
    <td>&nbsp;</td>
    <td>2 <sup>nd</sup>Customer Name</td>
    <td>:</td>
    <td> <? echo $inpcusnm2 ?></td>
    <td>E-mail 3</td>
    <td>:</td>
    <td> <? echo $inpemail3 ?></td>
  </tr>
  <tr class="arial11blackbold">
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr class="arial11blackbold">
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td><? echo $inpaction ?></td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td><input type="submit" name="Submit" class="arial11blackbold" id="Submit" value="Save Change"></td>
  </tr>
</table>
<p><div id="result"></div>

<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr valign="middle" class="arial11">
    <th width="20%" scope="col" height="24">&nbsp;</th>
    <th width="20%" scope="col"></th>
    <th width="20%" valign="middle" scope="col"></th>
    <th width="20%" scope="col"></th>
    <th width="20%" scope="col" align="right">
      </th>
  </tr>
</table>
</form>   	
         
<div id="footerMain1">
	<ul>
      <!--
     
     
      </ul>
		-->
    <div id="footerDesc">

	<p>
	Copyright &copy; 2023 DENSO . All rights reserved    
	
  </div>
</div>
</div>
	</body>
</html>
