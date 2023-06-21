<?php session_start() ; ?>
<?php require_once('core/ctc_init.php'); ?> <!-- add by CTC -->
<!--Force IE6 into quirks mode with this comment tag-->
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" lang="en" xml:lang="en">
    <head>
		<meta name="description" content="DENSO is one of the world's largest automotive suppliers of technology and components found in almost all vehicles around the globe."/>
		<meta name="keywords" content="DENSO,DENSO After Market System,Web Ordering System,DENSO Parts,DENSO JAPAN, DENSO Asia, DENSO Gloal, DENSO After Sales,DENSO Part" />
		<meta name="author" content="DENSO">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
		<meta http-equiv="X-UA-Compatible" content="IE=9, IE=10, IE=11, IE=EDGE" />  <!--02/04/2018 P.Pawan CTC-->
        <title>Dealer Ordering System</title>
        <style type="text/css">

            body{
                margin: 0;
                padding: 0;
                border: 1;
                overflow: hidden;
                height: 100%; 
                max-height: 100%; 
				
				background-image: url(images/bg.png);
				background-repeat: no-repeat;
				background-attachment: fixed;  
				background-size: 100% 100%;
            }

            #framecontentTop, #framecontentBottom{
                position: absolute;
                top: 0;
                left: 0;
                width: 100%;
                height: 115px; /*Height of top frame div*/
                overflow: hidden; /*Disable scrollbars. Set to "scroll" to enable*/
				  
            }

            #framecontentBottom{
                top: auto;
                bottom: 0;
                height:45px; /*Height of bottom frame div*/
                overflow: hidden; /*Disable scrollbars. Set to "scroll" to enable*/
				background-color:#FFF;
                color: white;	
				opacity: 0.5;
            }


            #maincontent{
                position: fixed;
                top: 0px; /*Set top value to HeightOfTopFrameDiv*/
                left: 0;
                right: 0;
                bottom: 0px; /*Set bottom value to HeightOfBottomFrameDiv*/
                overflow: hidden;
				
				<!--
				background-image: url(images/bg.png);
				background-repeat: no-repeat;
				background-size: cover;
			
				-->
	
            }
			


            * html body{ /*IE6 hack*/
                padding: 100px 0 57px 0; /*Set value to (HeightOfTopFrameDiv 0 HeightOfBottomFrameDiv 0)*/
            }

            * html #maincontent{ /*IE6 hack*/
                height: 100%; 
                width: 100%; 


            }
            /* footer Main */
            #footerMain1 {
                height: 57px;
                color: #A1A1A1;
                clear:both;
                padding-top: 0px;
                padding-right: 0;
                padding-bottom: 0px;
                padding-left: 0;
                font-family: Verdana, Geneva, sans-serif;
                font-size: 10px;
            }

            #footerMain1 ul {

                list-style: none;
                color: #AE0000;
                border-top-width: 1px;
                border-top-style: solid;
                border-top-color: #900;
                margin-top: 0;
                margin-right: 0;
                margin-bottom: 0;
                margin-left: 0px;
                padding-top: 3px;
                padding-right: 0;
                padding-bottom: 0;
                padding-left: 0;
            }

            #footerMain1 ul li {
                display: inline;
            }

            #footerMain1 ul li a {
                float: left;
                border-right: 1px solid #A1A1A1;
                padding: 0 5px;
                color: #900;
                line-height: 10px;
            }

            #footerMain1 ul li a:hover {
                color: #000;
            }

            #footerMain1 ul li.last a {
                border-right: none;
            }

            #footerMain1 #footerDesc p {
                font-weight: bold;
                text-align: left;
                padding-left: 5px;
				color: #900;
            }

            .Arial11Grey {
                font-family: Arial, Helvetica, sans-serif;
                font-size: 12px;
                color: 	660033;
                font-weight: bold;
            }
            .Arial11Grey a{
                font-family: Arial, Helvetica, sans-serif;
                font-size: 12px;
                color: #00F;
            }


	   @media screen and (max-width:800px){
	 

			#maincontent{
                position: fixed;
                top: 90px; /*Set top value to HeightOfTopFrameDiv*/
                left: 0;
                right: 0;
                bottom: 0px; /*Set bottom value to HeightOfBottomFrameDiv*/
                overflow: hidden;
				
            }
			
		}
		

		
        </style>

        <script src="jquery-1.3.2.js" type="text/javascript" charset="utf-8"></script>
        <script type="text/javascript">
            $(document).ready(function () {
                $().ajaxStart(function () {
                    $('#loading').show();
                    $('#result').hide();
                }).ajaxStop(function () {
                    $('#loading').hide();
                    $('#result').fadeIn('slow');
                });

                $('#myForm').submit(function () {
                    $.ajax({
                        type: 'POST',
                        url: $(this).attr('action'),
                        data: $(this).serialize(),
                        success: function (data) {
                            $('#result').html(data);
                        }
                    })
                    return false;
                });
                
                /*Page : login.php , Change Language*/
                $("#select_lang").change(function () {
                    var txt_lang = $(this).val();

                    var user = $("#UserName").val();
                    var pass = $("#Password").val();

                    $("#var_lang").val(txt_lang);
                    $("#hd_user").val(user);
                    $("#hd_pass").val(pass);
                    $("form#myForm_lang").submit();
                    
                });

               // $('#county option[value="'+getCookieOwnerComp()+'"]').attr("selected",true);

            })
        </script>

    </head>

    <body>

        <div id="framecontentTop">
         <img src="images/denso.jpg" width="350px" height="117px" alt="DENSO Logo" />

			   <div id="maincontent">

                  <table width="100%" border="0" cellspacing="0" cellpadding="0" align="center">
                    <tr>

                        <td style="height:0px">
       

                                    <?php
                                    /*Page : login.php , Set Default Language*/

                                    require_once('language/Lang_Lib.php');
                                    require_once('language/conn.inc');

									
                                    $mtoQry = "select * from language where usgflg = 1 AND ID = 1";
                                    $mtoResult = mysqli_query($msqlcon,$mtoQry);
                                    $objResult = mysqli_fetch_array($mtoResult);
		
                                    //echo $objResult["parameter"];

                                    if(isset($_POST["lg"])){
                                        $_SESSION['lng'] = $_POST["lg"];
                                    }else{
                                        $_SESSION['lng'] = $objResult["parameter"];
                                    }
                                    // print_r($_POST);
                                    
                                    require_once('core/ctc_cookie.php'); /* Edit by CTC Sippavit 01/09/2020 */
                                    ?>
                                    <form id= "myForm" action="mylogon.php" method="post">
                                        <table border="0" cellspacing="0" cellpadding="0" align="right">
										
											<tr>
												<td style="height:30px"></td>
												<td style="height:30px"></td>
												<td style="height:30px"></td>
												<td style="height:30px"></td>
												<td style="height:30px"></td>
												<td style="height:30px"></td>
												<td style="height:30px"></td>
												<td style="height:30px"></td>
												<td style="height:30px"></td>
											</tr>
                                            <tr>
                                                <td  class="Arial11Grey" ></td>
                                                <td width="80px" class="Arial11Grey" id="label_username">
													<?php echo get_lng($_SESSION["lng"], "L0001")/*Text : Dealer ID*/; ?>
												</td>
                                                <td width="5px">&nbsp;</td>
                                                <td width="60px">
												   <!--*Dealer ID input field-->
												    <input name="UserName" type="text" id="UserName" placeholder="<?php echo get_lng($_SESSION["lng"], "L0335"); ?>" size="30" maxlength="30" value="<?php echo isset($_POST['hd_user'])?$_POST['hd_user']:""; ?>"/>
                                                </td>
												<td>&nbsp;</td>
												<td class="Arial11Grey" id="label_password">
												    <!--*Language-->
													<?php echo get_lng($_SESSION["lng"], "L0302"); ?>
												</td>
												<td>
													<?php
                                                    /*include file fillter language*/
                                                    require_once('language/fillter_lang.php');
                                                    ?>

												</td>
												<td width="5px">&nbsp;</td>
												<td width="5px">&nbsp;</td>
                                            </tr>
											
											<tr>
												<td style="height:5px"></td>
												<td style="height:5px"></td>
												<td style="height:5px"></td>
												<td style="height:5px"></td>
												<td style="height:5px"></td>
												<td style="height:5px"></td>
												<td style="height:5px"></td>
												<td style="height:5px"></td>
												<td style="height:5px"></td>
											</tr>
                                            <tr>
                                                <td>&nbsp;</td>
                                                <td class="Arial11Grey" id="label_password">
														<?php echo get_lng($_SESSION["lng"], "L0002")/*ID Text : Password*/; ?>
												</td>
                                                <td>&nbsp;</td>
                                                <td>
														<!--*Password input field-->
														<input name="Password" type="password" id="Password" placeholder="<?php echo get_lng($_SESSION["lng"], "L0336"); ?>" size="30" maxlength="30" value="<?php echo isset($_POST['hd_pass'])?$_POST['hd_pass']:""; ?>"/>
                                                </td>
                                                <!-- start add county by CTC  -->
                                                <td>&nbsp;</td>
												<td class="Arial11Grey">
                                                    <?php echo get_lng($_SESSION["lng"], "L0415"); ?>
                                                </td>
                                                <td>
                                                    <select name="County" id="county">
                                                        <?php foreach (ctc_get_counrty() as $c)  { ?>
                                                        <option value="<?php echo $c['Owner_Comp'] ?>"><?php echo $c['Country'] ?></option>
                                                        <?php } ?>
                                                    </select>
                                                </td>
                                                <!-- end add county by CTC  -->
												<td>&nbsp;</td>
												<td>
													<input type="submit" id="btn_sub" value="<?php echo get_lng($_SESSION["lng"], "L0294")/*Text : Log in*/; ?>" />
												</td>
                                                <td>&nbsp;</td>
                                                <td>&nbsp;</td>
											    <td>&nbsp;</td>
											
											</tr>
											<tr>
												<td colspan="100%" align="center" >
														<div id="result" style="display:none;"></div> <!--*error message display-->
														<div id="loading" style="display:none;"><img src="images/35.gif" width="40" height="40" /></div> <!--*Loading image-->
												</td>
											</tr>												

                                        </table>
                                    </form>


                            </div>
                        </td>
                    </tr>
                </table>

        </div>
        <!-- Page : login.php , Form Hidden Change Language -->
        <form id= "myForm_lang" action="#" method="POST">
              <input name="lg" type="hidden" id="var_lang">    
              <input name="hd_user" type="hidden" id="hd_user">   
              <input name="hd_pass" type="hidden" id="hd_pass">                     
        </form>
			
			
			
			
         </div>


        <div id="framecontentBottom">

            <div id="footerMain1">
                <ul>
                    <!-- Disable by Zia 
                    <li><a href="#">Contact Us</a></li>
                    <li class="last" ><a href="#">Legal and Policy</a></li>
                    -->
                </ul>

                <div id="footerDesc">
                  <p>
                     Copyright &copy; 2023 DENSO. All rights reserved
				  </p>
                </div>
            </div>

        </div>

    </body>
</html>
