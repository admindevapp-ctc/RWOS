<?php 

session_start();
require_once('./../../core/ctc_init.php'); // add by CTC

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
  header("Location:../../login.php");
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


      //let searchParams = new URLSearchParams(window.location.search);
			let action = $.urlParam('action');
			let supno = $.urlParam('supno');
      if(action == "edit"){
        window.location = url + "?action="+ action +"&supno=" + supno;
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
		
	

  $('input[name="vholiday"]').click(function(){
    var check;
    var ele = document.getElementsByName('vholiday');
    for(i = 0; i < ele.length; i++) {
      if(ele[i].checked)
      check = ele[i].value;
    }
    
    //alert(check);
    //if(check == '0'){
      $.ajax({
					type: 'GET',
					url: 'supgetdate.php',
					success: function(data) {
            $("#textmsg").empty();
            //alert(data);
						if(data.length >0){
              $("#textmsg").append("<span class='arial21redbold'>"+data+"</span>");
              $("#Submit").hide();
              return false;
            }
					}
			});
    //}
    //else{
    //  $("#textmsg").text('');
    //  $("#Submit").show();
    //}

  })

	$('#frmupdsupmas').submit(function() {
      var file_data = $('#sup_image').prop('files')[0]; 
      data.append('file', file_data);    
    

		$.ajax({
			type: 'POST',
			url: $(this).attr('action'),
			data: $(this).serialize(),
			success: function(data) {
        alert("Hi");
				$('#result').html(data);
			}
		})
		return false;
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
			  	$_GET['current']="sup_mainadm";
				include("navAdm.php");
			  ?>
        </div>
        <div id="twocolRight">
        <?
		require('../db/conn.inc');
    /* commect ERP
		/// check Check ERP  if ERP not 0 or 1 disable to selected 'Check Holiday' and 'Not Check Holiday' ///
    $query="SELECT * FROM tm_country where Owner_Comp = '".$comp."'";
    
    $ERP = "";
    $sql=mysqli_query($msqlcon,$query);	
    if($result = mysqli_fetch_array ($sql)){
      $ERP=$result['ERP'];
    }
  */
		$action=trim($_GET['action']);
		//echo $action;
		if(trim($_GET['action'])=='add'){
			$inpsupno="<input type=\"text\" name=\"vsupno\" class=\"arial11blackbold\" maxlength=\"8\" size=\"10\" required></input>";
			$inpsupname="<input type=\"text\" name=\"vsupnm\" class=\"arial11black\" maxlength=\"45\" size=\"45\" required></input>";
			$inpaddr1="<input type=\"text\"  name=\"vaddr1\" class=\"arial11black\" maxlength=\"256\" size=\"45\" ></input>";
			$inpaddr2="<input type=\"text\"  name=\"vaddr2\" class=\"arial11black\" maxlength=\"256\" size=\"45\"></input>";
			$inpaddr3="<input type=\"text\"  name=\"vaddr3\" class=\"arial11black\" maxlength=\"256\" size=\"45\"></input>";
			$inpemail1="<input type=\"text\"  name=\"vemail1\" class=\"arial11black\" maxlength=\"256\" size=\"45\" ></input>";
			$inpemail2="<input type=\"text\" name=\"vemail2\"  class=\"arial11black\" maxlength=\"256\" size=\"45\"></input>";
			$inpweb="<input type=\"text\"  name=\"vweb\" class=\"arial11black\" maxlength=\"500\" size=\"45\"></input>";
			$inplogo="<input class=\"input-group\" type=\"file\" id=\"sup_image\" name=\"sup_image\" accept=\"image\" />";
			$inpduedate="<input type=\"number\"  name=\"vduedate\" class=\"arial11black\"  min=\"0\" max=\"732\" size=\"3\"required/> ";
      /* commect ERP
      
      // Check ERP if ERP not 0 or 1 disable to selected 'Check Holiday' and 'Not Check Holiday' //
      if($ERP != '0' && $ERP != '1'){
        $inpcheckholi="";
      }
      else{
        $inpcheckholi="Check Holiday <input type=\"radio\"  name=\"vholiday\" class=\"arial11black\" value='0' required/>"
        . "No Check Holiday <input type=\"radio\"  name=\"vholiday\" class=\"arial11black\"  value='1' required/>";
      }  
      */
      $inpcheckholi="<input type=\"radio\"  name=\"vholiday\" class=\"arial11black\" value='1' required Onclick=\"checkVal(this)\" /> Check Holiday "
      . "<input type=\"radio\"  name=\"vholiday\" class=\"arial11black\"  value='0' required  Onclick=\"checkVal(this)\" /> No Check Holiday ";
      $inpaction="<input type=\"hidden\" name=\"vaction\" value=".$action."></input>";
    }
    else {

      $vsupno=trim($_GET['supno']);
      $query="select * from supmas where supno='". $vsupno."' and Owner_comp='".$comp."'";
      //echo $query;
      $sql=mysqli_query($msqlcon,$query);	
      if($hasil = mysqli_fetch_array ($sql)){

        $supno=$hasil['supno'];
        $supname=$hasil['supnm'];
        $add1=$hasil['add1'];
        $add2=$hasil['add2'];
        $add3=$hasil['add3'];
        $email1=$hasil['email1'];
        $email2=$hasil['email2'];
        $website=$hasil['website'];
        $logo=$hasil['logo'];
        $duedate=$hasil['duedate'];
        $holidayck=$hasil['holidayck'];
        
        if($holidayck == '1'){
          $vholiday = "checked";
        }
        else if ($holidayck == '0'){
          $vnoholiday = "checked";
        }
        


        if(trim($_GET['action'])=='view'){
          $inpsupno= $supno;
          $inpsupname=$supname;
          $inpaddr1=$add1;
          $inpaddr2=$add2;
          $inpaddr3=$add3;
          $inpemail1=$email1;
          $inpemail2=$email2;
          $inpweb=$website;
          if($logo == ''){
            $inplogo = "No image uploaded";
          }
          else{
            $inplogo="<img src='../sup_logo/".$logo."' width='30%' height='100' style=\"object-fit:env()\"/><br/>";
          }
          
          $inpduedate=$duedate;

          /* commect ERP
          if($ERP != '0' && $ERP != '1'){
            $inpcheckholi="";
          }
          else{
            $inpcheckholi=" Check Holiday <input type=\"radio\"  name=\"vholiday\" class=\"arial11black\"  ". $vholiday ." value='0' Disabled/>"
            ." No Check Holiday <input type=\"radio\"  name=\"vholiday\" class=\"arial11black\" ". $vnoholiday ." value='1' Disabled/>";
          }
          */
          $inpcheckholi="<input type=\"radio\"  name=\"vholiday\" class=\"arial11black\"  ". $vholiday ." value='1' Disabled/> Check Holiday "
          ."<input type=\"radio\"  name=\"vholiday\" class=\"arial11black\" ". $vnoholiday ." value='0' Disabled/> No Check Holiday ";
          $inpaction="<input type=\"hidden\" name=\"vaction\" value=".$action."></input>";

        }else {
          $inpsupno="<input type=\"text\" name=\"supno\" class=\"arial11blackbold\" maxlength=\"8\" size=\"10\" value='".$supno."' Disabled ></input>";
          $inpsupno.="<input type=\"text\" name=\"vsupno\" class=\"arial11blackbold\" maxlength=\"8\" size=\"10\" value='".$supno."' hidden ></input>";
          $inpsupname="<input type=\"text\" name=\"vsupnm\" class=\"arial11black\" maxlength=\"45\" size=\"45\" value='".$supname."' ></input>";
          $inpaddr1="<input type=\"text\"  name=\"vaddr1\" class=\"arial11black\" maxlength=\"256\" size=\"45\" value='".$add1."'></input>";
          $inpaddr2="<input type=\"text\"  name=\"vaddr2\" class=\"arial11black\" maxlength=\"256\" size=\"45\" value='".$add2."' ></input>";
          $inpaddr3="<input type=\"text\"  name=\"vaddr3\" class=\"arial11black\" maxlength=\"256\" size=\"45\" value='".$add3."' ></input>";
          $inpemail1="<input type=\"text\"  name=\"vemail1\" class=\"arial11black\" maxlength=\"256\" size=\"45\" value='".$email1."' ></input>";
          $inpemail2="<input type=\"text\" name=\"vemail2\"  class=\"arial11black\" maxlength=\"256\" size=\"45\" value='".$email2."' ></input>";
          $inpweb="<input type=\"text\"  name=\"vweb\" class=\"arial11black\" maxlength=\"500\" size=\"45\" value='".$website."' ></input>";
          if($logo == ''){
              $inplogo="<input class=\"input-group\" type=\"file\" id=\"sup_image\" name=\"sup_image\"   accept=\"image\" />";
          }
          else{
              $inplogo="<img src='../sup_logo/".$logo."' width='30%' height='100'  style=\"object-fit:env()\"/><br/>";
              $inplogo.="<input type=\"hidden\" name=\"vsupimage\" value='".$logo."' ></input><br/>";
              $inplogo.="<input class=\"input-group\" type=\"file\" id=\"sup_image\" name=\"sup_image\"  accept=\"image\" />";
          }
          $inpduedate="<input type=\"text\"  name=\"vduedate\" class=\"arial11black\" maxlength=\"11\" min=\"0\" max=\"732\" size=\"5\" value='".$duedate."' name=\"vduedate\" ></input>";
          
          /* commect ERP
          // Check ERP if ERP not 0 or 1 disable to selected 'Check Holiday' and 'Not Check Holiday' //
          if($ERP != '0' && $ERP != '1'){
            $inpcheckholi="";
          }
          else{
            $inpcheckholi="Check Holiday <input type=\"radio\"  name=\"vholiday\" class=\"arial11black\"  ". $vholiday ." value='0' ".$view."/>"
              ." No Check Holiday <input type=\"radio\"  name=\"vholiday\" class=\"arial11black\" ". $vnoholiday ." value='1' ".$view."/>";
          }
          */
          $inpcheckholi="<input type=\"radio\"  name=\"vholiday\" class=\"arial11black\"  ". $vholiday ." value='1' ".$view."/> Check Holiday "
          ."<input type=\"radio\"  name=\"vholiday\" class=\"arial11black\" ". $vnoholiday ." value='0' ".$view."/> No Check Holiday ";
          $inpaction="<input type=\"hidden\" name=\"vaction\" value=".$action."></input>";
          
        }
      }
		}
		
		?>

<form id="frmupdsupmas" name="frmupdsupmas" method="post" action="sup_updmas.php" enctype="multipart/form-data" >

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
    <td width="3%"><img src="../images/calendar.gif" width="16" height="15" ></td>
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
    <td>Supplier Number 
      <?php if($action != "view") { ?> 
        <span class="arial11redbold">*</span>
      <?php } ?>
    </td>
    <td>:</td>
    <td colspan="2"> <? echo $inpsupno ?></td>
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
    <td>Supplier Name 
      <?php if($action != "view") { ?> 
        <span class="arial11redbold">*</span>
      <?php } ?>
    </td>
    <td>:</td>
    <td colspan="2"><? echo $inpsupname ?></td>
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
    <td>Address1</td>
    <td>:</td>
    <td colspan="2"><? echo $inpaddr1 ?></td>
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
    <td>Address2</td>
    <td>:</td>
    <td colspan="2"><? echo $inpaddr2 ?></td>
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
    <td>Address 3</td>
    <td>:</td>
    <td colspan="2"><? echo $inpaddr3 ?></td>
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
    <td>Email - 1</td>
    <td>:</td>
    <td colspan="2"><? echo $inpemail1 ?></td>
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
    <td>Email - 2</td>
    <td>:</td>
    <td colspan="2"><? echo $inpemail2 ?></td>
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
    <td>Supplier website</td>
    <td>:</td>
    <td colspan="2"><? echo $inpweb ?></td>
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
    <td>Supplier logo Picture(Size: 250x100 pixel)</td>
    <td>&nbsp;</td>
    <td colspan="2"><? echo $inplogo ?></td>
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
    <td>Supplier standard lead time (days)
    <?php if($action != "view") { ?> 
        <span class="arial11redbold">*</span>
      <?php } ?>
    </td>
    <td>:</td>
    <td colspan="2"><? echo $inpduedate ?></td>
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
    <td><? echo $inpcheckholi ?></td>
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
    <td colspan="3" id="textmsg">
    <?php
    if(trim($_GET['result'])=='error')
    {
      echo '<span class="arial21redbold">** ' .$_GET['msg'] . ' </span>';
    }
    ?></td>
    <? 
        if(trim($_GET['action'])=='view'){
          echo '<td></td>';
        }
        else{
          echo '<td><input type="submit" name="Submit" class="arial11blackbold" id="Submit" value="Save Change"></td>';
         
        }
    ?>
  </tr>
</table>
       <p><div id="result"></div>
       <table width="100%" border="0" cellspacing="0" cellpadding="0">
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
  </tr>
</table>

    </form>   	
		  



              
<div id="footerMain1">
	<ul>
      <!--
     
     -->
      </ul>
		
    <div id="footerDesc">

	<p>
	Copyright &copy; 2023 DENSO . All rights reserved    
	
  </div>
</div>
</div>
	</body>
</html>
