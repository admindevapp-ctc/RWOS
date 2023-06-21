<?php 

session_start();
require_once('./../../core/ctc_init.php'); // add by CTC
require_once('../../language/Lang_Lib.php');

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
 </style>


<script type="text/javascript" language="javascript" src="../lib/jquery-1.4.2.js"></script>
<script type="text/javascript">
	$(document).ready(function() {
    if (performance.navigation.type == performance.navigation.TYPE_RELOAD) {
      //alert( "This page is reloaded" );
      var url = window.location.href.split("?")[0];
      
      document.getElementById("frmupdsupmas").reset();

      $.urlParam = function(name){
			var results = new RegExp('[\?&]' + name + '=([^&#]*)').exec(window.location.href);
			if (results == null){
			return null;
			}
			else {
			return decodeURI(results[1]);
			}
		}


       // let searchParams = new URLSearchParams(window.location.search);
                let action =  $.urlParam('action');
                let supno =  $.urlParam('supno');
                let cusno =  $.urlParam('cusno');
        //alert(action)
        if(action == "edit"){
            window.location = url + "?action="+ action +"&supno=" + supno +"&cusno=" + cusno;
        }
        else{
            window.location = url + "?action="+ action;
        }
    } 
    
	$().ajaxStart(function() {
			$('#result').empty().html('<img src="images/35.gif" />');
	}).ajaxStop(function() {
		//$('#loading').hide();
		$('#result').fadeIn('slow');
	});
		
	

		
  $( "#ddlcusno" ).change(function() {
      var cusid = $(this).val();
     //alert(cusid);

      $.get('sup_getshipto.php?cusid=' + cusid, function(data){
            var result = JSON.parse(data);
            $("#ddlshpto").empty();
         //   ' <option value="" selected="selected">'.get_lng($_SESSION["lng"], "L0449").'</option>'
            $("#ddlshpto").html('<option value="" > <?php echo get_lng($_SESSION["lng"], "L0449") ?>  </option>');
            $.each(result, function(index, item){
               // $("#ddlshpto").append($('<option></option>').val(item.id).html(item.id));
               //$("#ddlshpto").append('<option value="'+item.id+'">'+item.id+'</option>');
               $("<option></option>",{
                        value:item.id,
                        text:item.id
                   }).appendTo("#ddlshpto");
            });
        });

      
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
                // echo $cusid;
                $MYROOT=$_SERVER['DOCUMENT_ROOT'];
                $_GET['current']="sup_mainref";
                include("navAdm.php");
              ?>
        </div>
        <div id="twocolRight">

        <?
          require('../db/conn.inc');
        
          $action=trim($_GET['action']);
		//echo $action;
		if($action=='add'){
        // select supno
        $opsupref= '<select name="ddlsupno" id="ddlsupno" class="arial11blue" style="width:200px"  required>';
        $opsupref= $opsupref .  ' <option value="" disabled="disabled" selected="selected">'.get_lng($_SESSION["lng"], "L0449").'</option>';
        $query="select distinct supno from supmas where Owner_Comp='$comp' order by supno ";
        $sql=mysqli_query($msqlcon,$query);	
        while($hasil = mysqli_fetch_array ($sql)){
          $supno=$hasil['supno'];
          $opsupref= $opsupref .  ' <option value="'.$supno.'">'.$supno.'</option>';		
          
        }
        $opsupref= $opsupref . ' </select>';

        // select cusno
        $opcus= '<select name="ddlcusno" id="ddlcusno" class="arial11blue" style="width:200px"   required>';
        $opcus= $opcus .  ' <option value="" disabled="disabled" selected="selected">'.get_lng($_SESSION["lng"], "L0449").'</option>';
        $query="select cusno from cusmas where Owner_Comp='$comp' order by cusno ";
        $sql=mysqli_query($msqlcon,$query);	
        while($hasil = mysqli_fetch_array ($sql)){
          $cusno=$hasil['cusno'];
          $opcus= $opcus .  ' <option value="'.$cusno.'">'.$cusno.'</option>';		
          
        }
        $opcus= $opcus . ' </select>';
    
      // select shiptoma
        $opshp= '<select name="ddlshpto" id="ddlshpto" class="arial11blue" style="width:200px"  required>';
        $opshp= $opshp .  ' <option value="" selected="selected">'.get_lng($_SESSION["lng"], "L0449").'</option>';
        $opshp= $opshp . ' </select>';
        $inpsupref=$opsupref;
        $inpcus=$opcus;
        $inpshp= $opshp;
        $inpaction="<input type=\"hidden\" name=\"vaction\" value=".$action."></input>";
    }else{

      $vsupno=trim($_GET['supno']);
      $vcusno=trim($_GET['cusno']);
      //$vshipto=trim($_GET['shipto']);
      $query="select * from supref where supno='". $vsupno."' and Owner_comp='".$comp."' and Cusno='".$vcusno."'";
      //echo $query;
      $sql=mysqli_query($msqlcon,$query);	
      if($result = mysqli_fetch_array ($sql)){


      // select supno
      $opsupref= '<select name="ddlsupno" id="ddlsupno" class="arial11blue"  style="width:200px"  disabled="disabled"  >';
      $query="select distinct supno from supref where Owner_Comp='$comp' order by supno ";
      
      $sql=mysqli_query($msqlcon,$query);	
      while($hasil = mysqli_fetch_array ($sql)){
        $supno=$hasil['supno'];
        //echo $supno .">>". $vsupno ."<br/>";
        $selected = ($hasil["supno"] === $vsupno) ? "selected" : "";

        $opsupref= $opsupref .  ' <option '.$selected.' value="'.$supno.'">'.$supno.'</option>';
        
      }
      $opsupref= $opsupref . ' </select>';	
      $opsupref= $opsupref . ' <input type="hidden" name="ddlsupno" value="'.$vsupno.'" />';	

      // select cusno
      $opcus= '<select name="ddlcusno" id="ddlcusno" class="arial11blue" style="width:200px"  disabled="disabled"  >';
      $query="select cusno from cusmas where Owner_Comp='$comp' order by cusno ";
      $sql=mysqli_query($msqlcon,$query);	
      while($hasil = mysqli_fetch_array ($sql)){
        $dbcusno=$hasil['cusno'];
        $selected = ($hasil["cusno"] === $vcusno) ? "selected" : "";
        $opcus= $opcus .  ' <option '.$selected.' value="'.$dbcusno.'">'.$dbcusno.'</option>';	
        
      }
      $opcus= $opcus . ' </select>';
      $opcus= $opcus . ' <input type="hidden" name="ddlcusno" value="'.$vcusno.'" />';		

      // select shiptoma
      $opshp= '<select name="ddlshpto" id="ddlshpto" class="arial11blue" style="width:200px" required="" >';
      $opshp= $opshp .  ' <option value=""disabled="disabled" selected="selected">'.get_lng($_SESSION["lng"], "L0449").'</option>';
      $query="select ship_to_cd as shipto from shiptoma  where cusno =  '".$vcusno."' and Owner_comp='".$comp."' ";
//echo $query;
        $sql=mysqli_query($msqlcon,$query);	
        while($hasil = mysqli_fetch_array ($sql)){
          $shipto=$hasil['shipto'];
          $selected = ($result["shipto"] == $shipto) ? "selected" : "";
          $opshp= $opshp . ' <option '.$selected.' value="'.$shipto.'">'.$shipto.'</option>';	
        }
        $opshp= $opshp . ' </select>';

        $inpsupref=$opsupref;
        $inpcus=$opcus;
        $inpshp= $opshp;
        
        if($holidayck == '0'){
          $vholiday = "checked";
        }
        else if ($holidayck == '1'){
          $vnoholiday = "checked";
        }

        if(trim($_GET['action'])=='view'){
          $view = "disabled";
        }
        $inpsupref=$opsupref;
        $inpcus=$opcus;
        $inpshp= $opshp;
        $inpaction="<input type=\"hidden\" name=\"vaction\" value=".$action."></input>";
        
      }
		}
		
		?>

<form id="frmupdsupmas" name="frmupdsupmas" method="post" action="sup_updrefmas.php" enctype="multipart/form-data" >

<table width="97%" border="0" cellspacing="0" cellpadding="0">
  <tr class="arial11blackbold">
    <td>&nbsp;</td>
    <td width="20%">&nbsp;</td>
    <td width="3%">&nbsp;</td>
    <td width="24%"></td>
    <td width="30%">&nbsp;</td>
  </tr>
  <tr class="arial11blackbold">
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr class="arial11blackbold">
    <td width="3%"><img src="../images/calendar.gif" width="16" height="15"></td>
    <td colspan="6" class="arial21redbold">Supplier  Maintenance</td>
    </tr>
  <tr class="arial11blackbold">
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr class="arial11blackbold">
    <td>&nbsp;</td>
    <td>Supplier Number <span class="arial11redbold">*</span></td>
    <td>:</td>
    <td colspan="2"> <? echo $inpsupref ?></td>
    </tr>
  <tr class="arial11blackbold">
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr class="arial11blackbold">
    <td>&nbsp;</td>
    <td>Customer Number<span class="arial11redbold">*</span></td>
    <td>:</td>
    <td colspan="2"><? echo $inpcus ?></td>
  </tr>
  <tr class="arial11blackbold">
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr class="arial11blackbold">
    <td>&nbsp;</td>
    <td>Default Shipto<span class="arial11redbold">*</span></td>
    <td>:</td>
    <td colspan="2"><? echo $inpshp ?></td>
  </tr>
  <tr class="arial11blackbold">
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr class="arial11blackbold">
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr class="arial11blackbold">
    <td><? echo $inpaction ?></td>
    <td></td>
    <td></td>
    <? 
        if(trim($_GET['action'])=='view'){
          echo '<td></td>';
        }
        else{
          echo '<td><input type="submit" name="Submit" class="arial11blackbold" id="Submit" value="Save Change"></td>';
        }
    ?>
    <td></td>
    <td></td>
  </tr>
</table>
    <p><div id="result"></div>
    <table width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr valign="middle" class="arial11">
    <th width="3%" scope="col" height="24"></th>
    <th width="20%" scope="col" align="left">
    <?php
    if(trim($_GET['result'])=='error')
    {
        echo '<span class="arial21redbold">** ' .$_GET['msg'] . ' </span>';
    }
    ?>
    </th>
    <th width="20%" valign="middle" scope="col"></th>
    <th width="20%" scope="col"></th>
    <th width="20%" scope="col" align="right">
      </th>
  </tr>
  <tr valign="middle" class="arial11">
    <th width="3%" scope="col" height="24"></th>
    <th width="20%" scope="col" align="left">
    <?php if($action != "view") { ?> 
      <font color="red"><?php echo get_lng($_SESSION["lng"], "L0488"); //* = required)?>  </font>
    <?php } ?>
    </th>
    <th width="20%" valign="middle" scope="col"></th>
    <th width="20%" scope="col"></th>
    <th width="20%" scope="col" align="right">
      </th>
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
