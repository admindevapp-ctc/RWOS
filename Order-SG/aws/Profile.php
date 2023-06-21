<?php 

session_start();
require_once('../../core/ctc_init.php'); // add by CTC
require_once('../../language/Lang_Lib.php');

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
		   echo "<script> document.location.href='../../".redir."'; </script>";
	 }
}else{	
header("Location:../../login.php");
}
?>
<html>
	<head>
    <title>Denso Ordering System</title>
	<meta http-equiv="X-UA-Compatible" content="IE=9, IE=10, IE=11, IE=EDGE" />  <!--02/04/2018 P.Pawan CTC-->
   	<link rel="stylesheet" type="text/css" href="../css/dnia.css">
	<script src="../lib/jquery-1.3.2.js" type="text/javascript" charset="utf-8"></script>
<script type="text/javascript" charset="utf-8"> 
$(document).ready(function() {
	var bi='';
	$('.button').click(function(){
	bi='';
	var err='';
	$('#result').text('');
	$('#result').css('background-color','#fff');
	 if($('#OldPassword').attr('value')==''){
	  	$('#OldPassword').css('background-color','#e50429');
		$('#pwd1').text('<?php echo get_lng($_SESSION["lng"], "W0012")/*'  * required field'*/; ?>');
	    bi='1';
	 }else{
		 $('#OldPassword').css('background-color','#fff');
		$('#pwd1').text('');
       	var oldpwd=$('#OldPassword').attr('value');
		}
	 if($('#ReoldPassword').attr('value')==''){
	  	$('#ReoldPassword').css('background-color','#e50429');
		$('#pwd2').text('<?php echo get_lng($_SESSION["lng"], "W0012")/*'  * required field'*/; ?>');
	    bi='2';
	 }else{
	 	$('#ReoldPassword').css('background-color','#fff');
		$('#pwd2').text('');
       	var oldpwd2=$('#ReoldPassword').attr('value');
	   }	
	if(bi==''){
		if(oldpwd!=oldpwd2){
			$('#OldPassword').css('background-color','#e50429');
			$('#ReoldPassword').css('background-color','#e50429');
			$('#pwd2').text('<?php echo get_lng($_SESSION["lng"], "W0013")/*'Old Password and Confirmed doesnot match'*/; ?>');
			bi='3';
		}else{
	 		$('#OldPassword').css('background-color','#fff');
			$('#ReoldPassword').css('background-color','#fff');
			$('#pwd2').text('');
           }	
	}
	
	if($('#NewPassword').attr('value')==''){
	  		$('#NewPassword').css('background-color','#e50429');
			$('#pwd3').text('<?php echo get_lng($_SESSION["lng"], "W0012")/*'  * required field'*/; ?>');
	    	bi='4';
	 	}else{
		 	$('#NewPassword').css('background-color','#fff');
			$('#pwd3').text('');
	    	var newpwd=$('#NewPassword').attr('value');
    }	

    
    if(bi!=''){
	  return false;
		
	}else{
		
        var dataString ='OldPassword='+ oldpwd + '&ReoldPassword=' + oldpwd2 + '&NewPassword=' + newpwd;
		//alert(dataString);
      $.ajax({
	  type: "POST",
      url: "../chgpwd.php",
      data: dataString,
      success: function(data) {
		//alert(data);  
		var valid=data.substr(3,5);
		if(valid=='Error'){
			$('#result').html(data);
			$('#result').css('background-color','#fff');
		}else{
	    $('#myForm').html("<div id='message'></div>");
        $('#message').html("<h3><?php echo get_lng($_SESSION["lng"], "G0014")/*'Request for Change Password!'*/; ?></h3>")
        .append("<p> <?php echo get_lng($_SESSION["lng"], "G0015")/*'Your Password has been change'*/; ?>" + data +"</p>")
		.append("<p class='Arial11Grey'> <a href='logout.php' ><?php echo get_lng($_SESSION["lng"], "G0018")/*'Return to login Screen'*/; ?></a> or <a href='main.php' ><?php echo get_lng($_SESSION["lng"], "G0016")/*'Return to Main screen'*/; ?></a>")
		
        }  //else
	  }
    });
	return false;
	}
});	
	
});

            function validatePassword(password) {
                
                // Do not show anything when the length of password is zero.
                if (password.length === 0) {
                    document.getElementById("msg").innerHTML = "";
                    return;
                }
                // Create an array and push all possible values that you want in password
                var matchedCase = new Array();
                matchedCase.push("[$@$!%*#?&]"); // Special Charector
                matchedCase.push("[A-Z]");      // Uppercase Alpabates
                matchedCase.push("[0-9]");      // Numbers
                matchedCase.push("[a-z]");     // Lowercase Alphabates

                // Check the conditions
                var ctr = 0;
                for (var i = 0; i < matchedCase.length; i++) {
                    if (new RegExp(matchedCase[i]).test(password)) {
                        ctr++;
                    }
                }
                // Display it
                var color = "";
                var strength = "";
                switch (ctr) {
                    case 0:
                    case 1:
                    case 2:
                        strength = "Very Weak";
                        color = "red";
                        break;
                    case 3:
                        strength = "Medium";
                        color = "orange";
                        break;
                    case 4:
                        strength = "Strong";
                        color = "green";
                        break;
                }
                document.getElementById("msg").innerHTML = strength;
                document.getElementById("msg").style.color = color;
            }
			
			
window.onload = function() {
 const myInput = document.getElementById('NewPassword');
 myInput.onpaste = function(e) {
   e.preventDefault();
 }
}	
			
        </script>

	
	</head>
	<body>
    
    <?php ctc_get_logo() ?> <!-- add by CTC -->

		<div id="mainNav">
			<?
				$_GET['selection']="profile";
				include("navhoriz.php");
			
			?>
	</div> 
    	<div id="isi">
        <form id= "myForm" action="../password.php" method="post">
              <p>&nbsp;</p>
              <table  align="center" width="90%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td><table width="100%" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td width="4%"><img src="../images/Profile.png" width="20" height="20"></td>
        <td width="96%" class="function"> <?php echo get_lng($_SESSION["lng"], "L0079"); ?><!-- User Profile--></td>
      </tr>
    </table>      <table width="100%" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td  height="30" width="4%" class="arial11blackbold">&nbsp;</td>
        <td width="25%" class="arial11greybold"> <?php echo get_lng($_SESSION["lng"], "L0083"); ?><!--Customer Number--></td>
        <td width="2%" class="arial12Bold">:</td>
        <td width="69%" class="arial11black"><? echo $cusno ?></td>
      </tr>
      <tr>
        <td height="30" class="arial11blackbold">&nbsp;</td>
        <td class="arial11greybold"><?php echo get_lng($_SESSION["lng"], "L0084"); ?><!--Customer Name--></td>
        <td class="arial12Bold">:</td>
        <td class="arial11black"><? echo $cusnm ?></td>
      </tr>
      <!-- start add country by CTC -->
        <tr>
        <td height="30" class="arial11blackbold">&nbsp;</td>
        <td class="arial11greybold"><?php echo get_lng($_SESSION["lng"], "L0416"); ?></td>
        <td class="arial12Bold">:</td>
        <td class="arial11black"><? echo $_SESSION['county'] ?></td>
      </tr>
      <!-- end add country by CTC -->
      <tr>
        <td height="30" class="arial11blackbold">&nbsp;</td>
        <td class="arial11greybold">&nbsp;</td>
        <td class="arial12Bold">&nbsp;</td>
        <td class="arial11black">&nbsp;</td>
      </tr>
    </table> </td>
  </tr>
  <tr>
    <td>
    
    
    <table width="100%" border="0" cellspacing="0" cellpadding="0">
<tr>
<td width="4%"><img src="../images/password.png" width="20" height="20"></td>
<td width="96%" class="function"> <?php echo get_lng($_SESSION["lng"], "L0085"); ?><!--Change Password--></td>
</tr>
<tr>
  <td>&nbsp;</td>
  <td class="function">&nbsp;</td>
</tr>
<tr>
  <td>&nbsp;</td>
  <td class="function">
  
  <table width="96%" border="0" cellspacing="0" cellpadding="0" >
            <tr align="left" >
              <td width="27%" class="arial11greybold" ><?php echo get_lng($_SESSION["lng"], "L0086"); ?><!--Old Password--></td>
              <td width="2%">:</td>
              <td width="26%"><input name="OldPassword" type="password" id="OldPassword" size="25" maxlength="20" /></td>
              <td width="45%" class="arial11redbold"><div  id="pwd1"></div></td>
            </tr >
            <tr align="left">
              <td class="arial11greybold">&nbsp;</td>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
              <td></td>
            </tr>
            <tr align="left">
              <td class="arial11greybold"><?php echo get_lng($_SESSION["lng"], "L0087"); ?><!--Old Password Confirmation--></td>
              <td>:</td>
              <td><input name="ReoldPassword" type="password" id="ReoldPassword" size="25" maxlength="20" /></td>
              <td class="arial11redbold"><div id="pwd2"></div></td>
            </tr>
            <tr>
              <td class="arial11greybold">&nbsp;</td>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
              <td></td>
            </tr>
            <tr align="left">
              <td class="arial11greybold"><?php echo get_lng($_SESSION["lng"], "L0088"); ?><!--New Password--></td>
              <td>:</td>
              <td><input name="NewPassword" type="password" id="NewPassword" size="25" maxlength="20" onkeyup="validatePassword(this.value);" /><span id="msg" class="arial11greybold"></span></td>
              <td class="arial11redbold"><div id="pwd3"></div></td>
            </tr>
            <tr align="left">
              <td class="arial11greybold">&nbsp;</td>
              <td>&nbsp;</td>
              <td colspan="2"><div class="arial11redbold" id="result"></div></td>
              </tr>
            <tr align="left">
              <td class="arial11greybold">&nbsp;</td>
              <td>&nbsp;</td>
              <td><input type="submit" name="btnChange" id="btnChange" value="<?php echo get_lng($_SESSION["lng"], "L0089")/*Change Password*/; ?>" class="button" /></td>
              <td>&nbsp;</td>
            </tr>
            <tr align="left">
              <td class="arial11greybold">&nbsp;</td>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
            </tr>
            <tr align="left">
              <td class="arial11greybold">&nbsp;</td>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
            </tr>
          </table>
  
  
  
  </td>
</tr>
    </table></td>
  </tr>
</table>
</form>
<div id="footerMain1">
	<ul>
       <!-- Disabled by zia
     
          -->
	  </ul>

    <div id="footerDesc">

	<p>
	Copyright &copy; 2023 DENSO . All rights reserved  
	
  </div>
</div>

	</body>
</html>
