<?php 
session_start();

require_once('./../core/ctc_init.php'); // add by CTC

$comp = ctc_get_session_comp(); // add by CTC

//print_r($_SESSION);
require_once('../language/Lang_Lib.php');
//if (session_is_registered('cusno'))
if (isset($_SESSION['cusno'])) {
    if ($_SESSION['redir'] == 'Order-SG') {
        $cusno = $_SESSION['cusno'];
        $cusnm = $_SESSION['cusnm'];
        $password = $_SESSION['password'];
        $alias = $_SESSION['alias'];
        $table = $_SESSION['tablename'];
        $type = $_SESSION['type'];
        $custype = $_SESSION['custype'];
        $user = $_SESSION['user'];
        $dealer = $_SESSION['dealer'];
        $group = $_SESSION['group'];
    } else {
        echo "<script> document.location.href='../" . redir . "'; </script>";
    }
} else {
    header("Location:../login.php");
}


include('chklogin.php');

$ordertype = '';

require("crypt.php");
if (isset($_SERVER['REQUEST_URI'])) {
	
    $var = decode($_SERVER['REQUEST_URI']);
	$ordertype = trim($var['ordertype']);
	$page = trim($var['page']);
}
//zia added to disable dnmy Normal and urgent Order
//if ($comp=='XM0'){//disable for DSMN
//}else{//disable for DSMN
if ($comp=='MA0'){

    $ordertype == '';
    $ordertype = 'Request';
    $_GET['selection'] = "requestDueDate";

}else{

if ($ordertype == '' || $ordertype == 'Normal') {
    $ordertype = 'Normal';
    $_GET['selection'] = "main";
} else if ($ordertype == 'Urgent') {
    $_GET['selection'] = "urgentOrder";
} else if ($ordertype == 'Request') {
    $_GET['selection'] = "requestDueDate";
}
}

$sc003Qry = "SELECT max(yrmon) as maxyr FROM sc003pr WHERE Owner_Comp='$comp'";
$scSql = mysqli_query($msqlcon,$sc003Qry);
if ($scArray = mysqli_fetch_array($scSql)) {
    $maxyr = $scArray['maxyr'];
}
?>

<html>
    <head>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
    <input type="hidden" id="maxyr" name="maxyr" value="<?php echo $maxyr; ?>">
	<meta http-equiv="X-UA-Compatible" content="IE=9, IE=10, IE=11, IE=EDGE" />  <!--02/04/2018 P.Pawan CTC-->
    <title>Denso Ordering System</title>
</style><!--[if IE]>
<style type="text/css">
#twocolLeftHeigth{ padding-top: 0px; }
#twocolRight { zoom: 1; padding-top:10px; }
</style>
<![endif]-->

<link rel="stylesheet" type="text/css" href="css/dnia.css">
<?php if ($ordertype == '' || $ordertype == 'Normal') { ?>
    <link rel="stylesheet" href="themes/ui-lightness/jquery-ui-green.css">
<?php } else if ($ordertype == 'Urgent') { ?>
    <link rel="stylesheet" href="themes/ui-lightness/jquery-ui-red.css">
<?php } else if ($ordertype == 'Request') { ?>
    <link rel="stylesheet" href="themes/ui-lightness/jquery-ui.css">
<?php } ?>

<script src="lib/jquery-1.4.2.min.js"></script>
<script src="lib/jquery.ui.core.js"></script>
<script src="lib/jquery.ui.widget.js"></script>
<script src="lib/jquery.ui.mouse.js"></script>
<script src="lib/jquery.ui.button.js"></script>
<script src="lib/jquery.ui.draggable.js"></script>
<script src="lib/jquery.ui.position.js"></script>
<script src="lib/jquery.ui.resizable.js"></script>
<script src="lib/jquery.ui.button.js"></script>
<script src="lib/jquery.ui.dialog.js"></script>
<script src="lib/jquery.ui.effect.js"></script>
<script src="lib/jquery.ui.datepicker.js"></script>

<link rel="stylesheet" href="css/demos.css">
<style>
    .ui-dialog .ui-state-error { padding: .3em; }
    .validateTips2 { border: 1px solid transparent; padding: 0.3em; }
    .validateTips { border: 1px solid transparent; padding: 0.3em; }
    
</style>
<script type="text/javascript">
    function checkThai(event) {//12/20/2018 P.Pawan CTC
        //Not allow thai language.
      var keysCode = event.keyCode ||event.which;
      if( (keysCode>=48 && keysCode<=57) //0-9
        || (keysCode>=65 && keysCode<=90) //a-z
        || (keysCode>=97 && keysCode<=122 ) //A-Z
        || (keysCode==8)  //backspace
        || (keysCode==45) //-
        || (keysCode==95) //underscore
        ){
        return true;
      }else{
        event.preventDefault();
        var errorTxt = '<?php echo get_lng($_SESSION["lng"], "L0312"); ?>';
        alert(errorTxt);
        return false;
      }
    }

    function getShipToAddressAjax(val){//12/20/2018 P.Pawan CTC
        //Get Address by Ship to Code.
        document.getElementById('shipToAddress').innerHTML = '';
        var oShpCd = $("#txtShpNo");
        oShpCd.removeClass("ui-state-error");
        var shipToCd = val;
        var cusno = <?echo $cusno;?>;
		console.log({shipToCd:shipToCd,cusno:cusno});
        $.ajax({
            type: 'POST',
            url: 'getShipToAddressAjax.php',
            async : false,
            data: {shipToCd:shipToCd,
                    cusno:cusno},
            dataType: 'json',
            success: function(res) {
                var ship_to_nm = res['ship_to_nm'];
                var adrs1 = res['adrs1'];
                var adrs2 = res['adrs2'];
                var adrs3 = res['adrs3'];
                var comp_tel_no = res['comp_tel_no'];
                var pstl_cd = res['pstl_cd'];
                var shipToAddress = "";
                if(ship_to_nm!=''&&ship_to_nm!=null&&ship_to_nm!=undefined&&ship_to_nm.toLowerCase()!='null'&&ship_to_nm.toLowerCase()!='undefined'){
                    shipToAddress += ship_to_nm+"<br>";
                }
                if(adrs1!=''&&adrs1!=null&&adrs1!=undefined&&adrs1.toLowerCase()!='null'&&adrs1.toLowerCase()!='undefined'){
                    shipToAddress += adrs1+"<br>";
                }
                if(adrs2!=''&&adrs2!=null&&adrs2!=undefined&&adrs2.toLowerCase()!='null'&&adrs2.toLowerCase()!='undefined'){
                    shipToAddress += adrs2+"<br>";
                }
                if(adrs3!=''&&adrs3!=null&&adrs3!=undefined&&adrs3.toLowerCase()!='null'&&adrs3.toLowerCase()!='undefined'){
                    shipToAddress += adrs3+" ,";
                }
                if(pstl_cd!=''&&pstl_cd!=null&&pstl_cd!=undefined&&pstl_cd.toLowerCase()!='null'&&pstl_cd.toLowerCase()!='undefined'){
                    shipToAddress += pstl_cd+"<br>";
                }
                if(comp_tel_no!=''&&comp_tel_no!=null&&comp_tel_no!=undefined&&comp_tel_no.toLowerCase()!='null'&&comp_tel_no.toLowerCase()!='undefined'){
                    shipToAddress += comp_tel_no;
                }
                document.getElementById("shipToAddress").innerHTML = shipToAddress;
            }
        });
    }

    $(function() {




    $("#result_tr1").hide()
            //dialog message
            var vaction = "";
    //$( "#dialog:ui-dialog" ).dialog( "destroy" );
    var res = "";
    var corno = $("#txtCorno"),
            allFields = $([]).add(corno),
            tips = $(".validateTips2");
    function updateTips(t) {
    tips
            .text(t)
            .addClass("ui-state-highlight");
    /*setTimeout(function() {
     tips.removeClass( "ui-state-highlight", 1500 );
     }, 500 );*/
    }

    function checkLength(o, n, min, max) {
    if (o.val().length > max || o.val().length < min) {
    o.addClass("ui-state-error");
    if (max != min){
    updateTips("<?php echo get_lng($_SESSION["lng"], "W0008"); ?>");
    return false;
    } else{
    updateTips("invalid Due date");
    return false;
    }
    } else {
    return true;
    }
    }

    function checkShipTo(shpCd){//12/20/2018 P.Pawan CTC
        //validation before submit.
        var oShpCd = $("#txtShpNo");
        if(shpCd != '' && shpCd !=  null && shpCd != undefined && shpCd != 'undefined'){
            return true;
        }else{
            oShpCd.addClass("ui-state-error");
            oShpCd.focus();
            updateTips("<?php echo get_lng($_SESSION["lng"], "G0011"); ?>");
            return false;
        }
    }

    /** check order type and Transport **/

    function checkVal(o, n) {
    if (o.val().length == 0) {
    o.addClass("ui-state-error");
    if (max != min){
    updateTips(n + " should be selected!");
    return false;
    }
    } else {
    return true;
    }
    }




    function checkRegexp(o, regexp, n) {
    if (!(regexp.test(o.val()))) {
    o.addClass("ui-state-error");
    updateTips(n);
    return false;
    } else {
    return true;
    }
    }
    function checkspace(o, regexp, n) {
    if ((regexp.test(o.val()))) {
    o.addClass("ui-state-error");
    updateTips(n);
    return false;
    } else {
    return true;
    }
    }


    $("#dialog-form").dialog({
    autoOpen: false,
            width: 700,
<?php if ($ordertype == 'Request') { ?>
        height:'auto',
<?php } else { ?>
        height:'auto',
<?php } ?>
    modal: true,
            position: {
            my: "center",
                    at: "center",
                    of: $("body"),
                    within: $("body")
            },
            buttons: {
            "<?php echo get_lng($_SESSION["lng"], "L0044"); ?>": function() {

            var bValid = true;
            var shpCd = $("#txtShpNo").val();
            allFields.removeClass("ui-state-error");
            bValid = bValid && checkLength(corno, "PO Number", 2, 10) && checkShipTo(shpCd);
            //bValid = bValid && checkRegexp(corno, /^((?!\/|\\).)*$/g, '<?php echo get_lng($_SESSION["lng"], "E0034");/*Error: PO Number can not contain space, / , \\*/ ?>');
			//bValid = bValid && checkRegexp(corno, /^[^*|\":<>[\]{}^#`\\%().';@&$]*$/g, '<?php echo get_lng($_SESSION["lng"], "E0034");/*Error: PO Number can not contain space, / , \\*/ ?>');
			//bValid = bValid && checkRegexp(corno, /^[^ก-๙*|\"/,!+=~?฿:<>[\]{}^#`\\%().';@&$]*$/g, '<?php echo get_lng($_SESSION["lng"], "E0034");/*Error: PO Number can not contain space, / , \\ and Thai language*/ ?>');//02/13/2019 P.Pawan CTC
			bValid = bValid && checkRegexp(corno, /^[0-9a-zA-Z_\-]*$/g, '<?php echo get_lng($_SESSION["lng"], "E0034");?>');
			bValid = bValid && checkspace(corno, /\s/, '<?php echo get_lng($_SESSION["lng"], "E0034");/*Error: PO Number can not contain space, / , \\*/ ?>');
            var str1 = $("#requestDate2").val();
            var str2 = $("#requestDate").val();
            var dueDt = Date.parse(str2);
            var selecteDt = Date.parse(str1);
			/*Zia disabled because no need in Request Due Date
            if (dueDt < selecteDt) {
            updateTips('Request Due Date should be greater than ' + str1);
            return false;
            }*/

            var edata;
            var vcorno = corno.val();
            var vorderno = $('#orderno').val();
            var vorddate = $('#orddate').val();
            var vordertype = $('#ordertype').val();
            // var rcv = $("#txtShpNo").val().split("|");
            var requestDate = $("#requestDate").val();
            //var rcv=data.split("||");
            // var oecus = $("#txtShpNoHidden").val().toUpperCase();
            // var vshpno = rcv[0];
            var vshpno = <? echo $cusno?>;
// Zia added note function..end
			var vtxtnote = $('#txtnote').val();
			//alert (vordnote);
			
// zia added note function ..End
            var vshipment = $('#shipment').val();
            if (vshpno.length == 0){
            $('#txtShpNo').addClass("ui-state-highlight");
            updateTips('Ship To Should be filled!');
            return false;
            }
            var vaction = "new";
            edata = "ordno=" + vorderno + "&corno=" + vcorno + "&orddate=" + vorddate + "&shpno=" + vshpno + "&shipment=" + vshipment + "&action=" + vaction + "&ordertype=" + vordertype + "&requestDate=" + requestDate + "&shpCd=" + shpCd+ "&txtnote=" + vtxtnote;
            //alert (edata);
			if (vordertype == 'Request'){
            var para = "selected=" + str2
                    $.ajax({
                    type: 'GET',
                            url: 'checkIsHoliday.php',
                            data: para,
                            success: function(data) {
                            if (data.substr(0, 5) == 'Error'){
                            updateTips(data);
                            }
                            else{
                            if (bValid) {
                            $.ajax({
                            type: 'GET',
                                    url: 'Orderadd.php',
                                    data: edata,
                                    success: function(data) {
                                    if (data.substr(0, 5) == 'Error'){
                                    $(".validateTips2").html(data).addClass("ui-state-highlight");
                                    corno.addClass("ui-state-error");
                                    return false;
                                    } else{
                                    //alert(data);
                                    //$('#result').html(data);
                                    window.location.href = data;
                                    }


                                    $("#dialog-form").dialog("close");
                                    }

                            });
                            }
                            }
                            }
                    });
            }
            else {
            if (bValid) {
            $.ajax({
            type: 'GET',
                    url: 'Orderadd.php',
                    data: edata,
                    success: function(data) {
                    if (data.substr(0, 5) == 'Error'){
                    $(".validateTips2").html(data).addClass("ui-state-highlight");
                    corno.addClass("ui-state-error");
                    return false;
                    } else{
                    // alert(data);
                    //$('#result').html(data);
                    window.location.href = data;
                    }


                    $("#dialog-form").dialog("close");
                    }

            });
            }
            }



            },
                    "<?php echo get_lng($_SESSION["lng"], "L0045"); ?>": function() {

                    $(this).dialog("close");
                    }
            },
            close: function() {

            allFields.val("").removeClass("ui-state-error");
            }
    });
    //03/10/2019 Prachaya inphum CTC --start--
    $("#dialog-timeout").dialog({
    autoOpen: false,
            width: 300,
<?php if ($ordertype == 'Request') { ?>
        height:'auto',
<?php } else { ?>
        height:'auto',
<?php } ?>
    modal: true,
            position: {
            my: "center",
                    at: "center",
                    of: $("body"),
                    within: $("body")
            },
            buttons: [
            
                        {
                            text:  "<?php echo get_lng($_SESSION["lng"], "L0317"); ?>",
                            click: function() {
                            $( this ).dialog( "close" );
                            }
                        }
                    ],
            close: function() {
                window.location.href = "main.php";
               
            }
    });
     //03/10/2019 Prachaya inphum CTC --end--
    $("#new").click(function() {
		var vordertype = $('#ordertype').val();
		var customerNo = "customerNo="+<?php echo $cusno?>;
		edata = "ordertype=" + vordertype;
		$.ajax({
		type: 'GET',
				url: 'getordnoprd_new.php',
				data: edata,
				async: false,
				success: function(data) {
				if (data.substr(0, 5) == 'Error'){
				} else{
				var rcv = data.split("||");
				var prdyear = rcv[0];
				var prdmonth = rcv[1];
				var ord = rcv[2];
				var ordertype = rcv[3];
				$('#orderno').val(ord);
				$('#ordertype').val(ordertype);
				$('#prdmonth').val(prdmonth);
				$('#prdyear').val(prdyear);
				}
				}
		});
		$("#txtShpNo").find('option').remove().end();
		document.getElementById('shipToAddress').innerHTML = '';
		//get ship code for append on dropdown.
		$.ajax({
        type: 'POST',
        url: 'getShipToCodeAjax.php',
        async : false,
        data: customerNo,
        dataType: 'json',
        success: function(res) {
            var txtselect = '<? echo get_lng($_SESSION["lng"], "L0313") ;?>'/* Please select ship code' */;
            if(res.length>1){
                $("#txtShpNo").append("<option value='' selected>-----"+txtselect+"-----</option>");
            }
            if(res.length>0){
                for(var i=0;i<res.length;i++){
					shipname = res[i].shipNm;
					if(shipname.length > 50) shipname = shipname.substring(0,49) + '...';
					txt_result = res[i].shipCd+"'>"+res[i].shipCd+"  :  "+shipname;
					
                    $("#txtShpNo").append("<option value='"+txt_result+"</option>");
                }
            }
            if(res.length==1){
                getShipToAddressAjax($("#txtShpNo").val());
            }
        }
    });
    $.ajax({
    type: 'GET',
            url: 'setRequestDueDate.php',
            success: function(data) {
            //alert(data);
            var rcv = data.split(",");
            $("#requestDate").val(rcv[2]);
            $("#requestDate2").val(rcv[2]);
            var date = $("#requestDate2").val().split('-');
            var dateToday = new Date();
            var yrRange = dateToday.getFullYear() + ":" + $("#maxyr").val().substring(0, 4);
            $("#requestDate").datepicker({
            minDate:new Date(date[1] + "-" + date[0] + "-" + date[2]), //mm-dd-yyyy
                    dateFormat:'dd-mm-yy',
                    changeMonth: true,
                    changeYear: true,
                    yearRange: yrRange,
                    onSelect: function(data){
                    edata = "selected=" + data;
                    $.ajax({
                    type: 'GET',
                            url: 'checkIsHoliday.php',
                            data: edata,
                            success: function(data) {
                            if (data.substr(0, 5) == 'Error'){
                            alert(data);
                            $("#requestDate").val($("#requestDate2").val());
                            return false;
                            }
                            }
                    });
                    }
            });
    }
    });
    $(".validateTips").text('All form fields are required.').removeClass("ui-state-highlight");
    $("#dialog-form").dialog("open");
    });
    /*$( "#requestDate" ).datepicker({
     onSelect: function(data){
     alert(data);
     $.ajax({
     type: 'GET',
     url: 'checkIsHoliday.php',
     data: edata,
     async: false,
     success: function(data) {
     if(data.substr(0,5)=='Error'){
     }else{
     
     
     }
     }
     });
     }
     });*/


    $("#txtShpNo").change(function() {
        var oecus = $("#txtShpNoHidden").val().toUpperCase();
        if (oecus == 'Y'){
            $("#result_tr1").show();
        } else{
            $("#result_tr1").hide()
        }
    });
    });
</script>
<!--Start : Zia Added for Marquee-->
<script>  
function newPopup(url) {
	popupWindow = window.open(
		url,'popUpWindow','height=500,width=500,left=500,top=300,resizable=yes,scrollbars=yes,toolbar=no,menubar=no,location=no,directories=no,status=no,replace=true')

		}
function closeWindow()
{
   if(false == popupWindow.closed)
   {
      popupWindow.close ();
   }
   else
   {
      alert('That window is already closed. Open the window first and try again!');
   }
}		
</script>
<!--End : Zia Added for Marquee-->

</head>
<body >
    
    <?php ctc_get_logo() ?> <!-- add by CTC -->

    <div id="mainNav">
        <?php
        include("navhoriz.php");
        ?>
    </div>
    <div id="isi">

        <div id="twocolLeft">
            <?php
            if ($ordertype == '' || $ordertype == 'Normal') {
                $formtitle = get_lng($_SESSION["lng"], "L0032")/* 'New Normal Order' */;
                $_GET['current'] = "main";
            } else if ($ordertype == 'Urgent') {
                $formtitle = get_lng($_SESSION["lng"], "L0034")/* 'New Urgent Order' */;
                $_GET['current'] = "urgentOrder";
            } else if ($ordertype == 'Request') {
                $formtitle = get_lng($_SESSION["lng"], "L0033")/* 'New Requested Due Date Order' */;
                $_GET['current'] = "requestDueDate";
            }
            include("navUser.php");
            ?>
        </div>
        <div id="twocolRight">
            <table width="97%" border="0" cellspacing="0" cellpadding="0">
                <tr class="arial11blackbold">
                    <td colspan="7">
		
					
			<!--Start: Zia added Marquee Start-->

			<marquee style="font-size:9pt;color:red;" direction="left"  scrolldelay="50" scrollamount="3" onmouseout="this.start()" onmouseover="this.stop()">
				<?php
					require 'conn_marquee.php';
					$sql = "SELECT * FROM `announce` WHERE `start`<=CURRENT_DATE and `end`>=CURRENT_DATE AND Owner_Comp='$comp'  ORDER BY `ID` DESC";
					$result = mysqli_query($msqlcon,$sql);
				          if(mysqli_num_rows($result) > 0)  
                     {  
                          while($row = mysqli_fetch_array($result))  
                          {  
							   echo '<img src="images/marquee.gif" width="20" height="10" />';
							   //echo '<a href="anna_details.php?edit_id='.$row[0].'"> '.$row['title'].'</a>'; 
                               //echo '<label><a href="JavaScript:newPopup('.$row['0'].')" target="_blank">'.$row['title'].'</a></label>';  
							    echo'<label><a href=JavaScript:newPopup("anna_details.php?edit_id='.$row['id'].'")>'.$row['title'].'</a></label>';
								
                          }  
                     } 
				?>
				
			</marquee>
					
		
			<!--End: Zia added Marquee end-->
					
					</td>
                </tr>
                <tr align="center">
                    <td colspan="7" class="arial11blackbold">
                        <?php
                        if ($ordertype == 'Urgent') {
                            require('countdown.php');
                        }
                        ?>
                    </td>
                </tr>
                <tr class="arial11blackbold">
                    <td colspan="7">&nbsp;</td>
                </tr>
                <tr class="arial11blackbold">
                    <td width="22%"><?php echo get_lng($_SESSION["lng"], "L0003"); ?></td>
                    <td width="2%">:</td>
                    <td width="26%"><?php echo $cusno ?></td>
                    <td width="4%"></td>
                    <td width="20%"><?php echo get_lng($_SESSION["lng"], "L0004"); ?></td>
                    <td width="2%">:</td>
                    <td width="25%"><?php echo $cusnm ?></td>
                </tr>
                <tr class="arial11blackbold">
                    <td colspan="7">&nbsp;</td>
                </tr>
                <table width="100%" border="0" cellspacing="0" cellpadding="0">
                    <tr valign="middle" class="arial11">
                        <th  scope="col">&nbsp;</th>
                        <th width="90" scope="col"></th>
                        <th width="90" scope="col" align="right"></th>

                    </tr>
                    <tr height="5"><td colspan="5"></td><tr>
                </table>

            </table>
            <table width="100%" border="0" cellspacing="0" cellpadding="0">
                <tr valign="middle" class="arial11">
                    <th  scope="col">&nbsp;</th>
                    <th width="90" scope="col">
                        <?php
                        $url = "import_order_new.php";
                        if ($ordertype == 'Normal') {
                            $url = "import_order_new.php?" . paramEncrypt("ordertype=Normal");
                        } else if ($ordertype == 'Urgent') {
                            $url = "import_order_new.php?" . paramEncrypt("ordertype=Urgent");
                        } else if ($ordertype == 'Request') {
                            $url = "import_order_new.php?" . paramEncrypt("ordertype=Request");
                        }
                        if ($type != "v")
                            //echo "<a href=\"$url\"><img src=\"images/importxls.gif\" width=\"80\" height=\"20\"></a>";
                            echo "<a href=\"$url\" style='text-decoration-line: none;'>
                                    <div style='background-color: #AD1D36;font-size:9pt;color: #FFFFFF;height:22px;'>
                                        <img src=\"images/excel.jpg\" width=\"18\" height=\"18\" style='float:left;margin-left:4px;margin-top:1px;'>
                                        <font style='margin-right:18px;line-height:22px;'>". get_lng($_SESSION["lng"], "L0005")."</font>
                                    </div>
                                </a>";
                        ?>
                    </th>
					<th width="10"></th>
                    <th width="90" scope="col" align="right">
                        <?php
                        if ($type != "v")
                            // echo "<a href=\"#\" id=\"new\"><img src=\"images/newtran.png\" width=\"80\" height=\"20\"></a>";
                            echo "<a href=\"#\" id=\"new\" style='text-decoration-line: none;'>
                                    <div style='background-color: #AD1D36;font-size:9pt;color: #FFFFFF;width: 80px;height:22px;'>
                                        <img src=\"images/new.png\" width=\"18\" height=\"18\" style='float:left;margin-left:4px;margin-top:1px;'>
                                        <font style='margin-right:18px;line-height:22px;'>". get_lng($_SESSION["lng"], "L0006")."</font>
                                    </div>
                                </a>";
                        ?>
                    </th>
                </tr>
                <tr height="5"><td colspan="5"></td><tr>
            </table>


            <table width="100%" class="tbl1" cellspacing="0" cellpadding="0">
                <tr class="arial11white" bgcolor="#AD1D36" >
                    <?php
                    if ($custype == "A") {
                        $colnum = 7;
                        echo '<th width="12%" height="30">' . get_lng($_SESSION["lng"], "L0007") . '</th>';
                        echo '<th width="9%" height="30" scope="col">' . get_lng($_SESSION["lng"], "L0008") . '</th>';
                        echo '<th width="20%" >' . get_lng($_SESSION["lng"], "L0009") . '</th>';
                        echo '<th width="20%" >' . get_lng($_SESSION["lng"], "L0010") . '</th>';
                        echo ' <th width="13%" >' . get_lng($_SESSION["lng"], "L0011") . '</th>';
                        echo '<th width="9%" >' . get_lng($_SESSION["lng"], "L0012") . '</th>';
                        echo '<th width="18%" class="lastth">' . get_lng($_SESSION["lng"], "L0013") . '</th>';
                    } else {
                        echo '<th width="9%" height="30" scope="col">' . get_lng($_SESSION["lng"], "L0008") . '</th>';
                        echo '<th width="23%" >' . get_lng($_SESSION["lng"], "L0009") . '</th>';
                        echo '<th width="23%" >' . get_lng($_SESSION["lng"], "L0010") . '</th>';
                        echo ' <th width="17%" >' . get_lng($_SESSION["lng"], "L0011") . '</th>';
                        echo '<th width="10%" >' . get_lng($_SESSION["lng"], "L0012") . '</th>';
                        echo '<th width="18%" class="lastth">' . get_lng($_SESSION["lng"], "L0013") . '</th>';
                        $colnum = 6;
                    }

                    echo "</tr>";
                    require('db/conn.inc');
                    //include "crypt.php";
				
                    
                    $mpage = trim($page);
                    $mper_page = 10;
                    $num = 10;

                    if ($mpage) {
                        $start = ($mpage - 1) * $mper_page;
                    } else {
                        $start = 0;
                        $mpage = 1;
                    }


                    $query = "select * from orderhdr  where ordtype='" . substr($ordertype, 0, 1) . "' and trim(CUST3) ='" . $cusno . "' and Trflg!='1' and Owner_Comp='$comp'";
                    $query = $query . " order by orderno, orderdate";  // edit by CTC
                    // echo $query;
                    $sql = mysqli_query($msqlcon,$query);
                    $mcount = mysqli_num_rows($sql);
                    $query1 = $query . " LIMIT $start, $mper_page";
                    $sql = mysqli_query($msqlcon,$query1);
                    while ($hasil = mysqli_fetch_array($sql)) {
                        $ordno = $hasil['orderno'];
                        $corno = $hasil['Corno'];
                        $shpno = $hasil['cusno'];
                        $shpCd = $hasil['shipto'];
                        if ($corno == "")
                            $corno = "-";
                        $orderstatus = $hasil['ordtype'];
                        $ordflg = $hasil['ordflg'];
                        $orderdate = $hasil['orderdate'];
                        $periode = $hasil['orderprd'];
                        $orddate = substr($orderdate, -2) . "/" . substr($orderdate, 4, 2) . "/" . substr($orderdate, 0, 4);
                        if ($orderstatus == substr($ordertype, 0, 1)) {
                            $ordsts = $ordertype;
                        }
                        $oecus = $hasil['OECus'];
                        $shipment = $hasil['Shipment'];
                        $trflg = $hasil['Trflg'];
                        $querycus = "select route from cusmas where cusno='$shpno' and Owner_Comp='$comp'";  // edit by CTC
                        $sqlcus = mysqli_query($msqlcon,$querycus);
                        if ($hasilcus = mysqli_fetch_array($sqlcus)) {
                            $route = $hasilcus['route'];
                        }
/* zia Added */				
                        $querynote = "select notes from ordernts where cusno='$shpno' and orderno='$ordno' and Owner_Comp='$comp'"; /// edit by CTC
                        $sqlcus = mysqli_query($msqlcon,$querynote);
                        if ($hasilnote = mysqli_fetch_array($sqlcus)) {
                            $txtnote = $hasilnote['notes'];
                        }		
						//echo "<script type='text/javascript'>alert('$txtnote');</script>";
/* zia Added */							
						
                        $urlprint = "<a href='prtorderpdf.php?ordno=" . $ordno . "&corno=" . $corno . "' target=\"new\"> <img src=\"images/print.png\" width=\"20\" height=\"20\" border=\"0\"></a>";

                        echo "<tr class=\"arial11black\" align=\"center\" height=\"25\">";

                        if ($custype == "A") {
                            switch (trim($ordflg)) {
                                case "":
                                    $sts = "Pending";
                                    break;
                                case "1":
                                    $sts = "Completed";
                                    break;
                                case "U":
                                    $sts = "Uncompleted";
                                    break;
                            }
                            echo "<td class=\"arial11redbold\">" . $sts . "</td>";
                        }

                        echo "<td>" . $orddate . "</td><td>" . $corno . "</td>";

                        echo "<td>" . $ordno . "</td>";
                        echo "<td>" . $shpCd . "</td><td>" . $ordsts . "</td>";
                        echo "<td class=\"lasttd\">";
                        if ($type != "v") {

                            if (($custype == "A" & $ordflg == "") || $custype != "A" || ($custype == "A" & $ordflg != "" & $route == 'D')) {
                                $edit = paramEncrypt("action=edit&ordno=$ordno&shpno=$shpno&corno=$corno&ordertype=$ordertype&shpCd=$shpCd&txtnote=$txtnote");   //zia added txtnote
                                $delete = paramEncrypt("action=delete&ordno=$ordno&shpno=$shpno&corno=$corno&ordertype=$ordertype");
                                if ($ordflg != "N") {
                                   // echo "<a href='orderreg.php?" . $edit . "' > <img src=\"images/edit.png\" width=\"20\" height=\"20\" border=\"0\"></a>"; // Disabled Edit button base on DSTH request. 
                                }
                                echo "<a href='orderreg.php?" . $delete . "' onclick=\"return confirm('" . get_lng($_SESSION["lng"], "W0007") . "')\"> <img src=\"images/delete.png\" width=\"20\" height=\"20\" border=\"0\"></a>";
                            }
                        }

                        if ($custype == "A" & $ordflg != "") {
                            if ($route != 'D') {
                                $view = paramEncrypt("action=View&ordno=$ordno&shpno=$shpno&corno=$corno&orddate=$orderdate");
                                echo "<a href='orderreg.php?" . $view . "' > <img src=\"images/view.png\" width=\"20\" height=\"20\" border=\"0\"></a>";
                            }
                        }

                        if ($type == "v" && $custype != "A") {
                            $view = paramEncrypt("action=ViewDlr&ordno=$ordno&shpno=$shpno&periode=$periode&corno=$corno&orddate=$orderdate");
                            echo "<a href='orderreg.php?" . $view . "' > <img src=\"images/view.png\" width=\"20\" height=\"20\" border=\"0\"></a>";
                        }
                        echo $urlprint;
                        echo "<td >";
                    }
                    include("pager.php");
					
                    if ($mcount > $mper_page) {
                        echo "<tr height=\"30\"><td colspan=\"" . $colnum . "\" align=\"right\" class=\"lasttd\"><div id=\"pagination\" >";
                        $fld = "mpage";
                        // pagingfld($query, $mper_page, $num, $mpage, $fld);
                        pagingst($query, $mper_page, $num, $mpage, $fld , $ordertype);
                        echo "</div></td></tr>";
                    }

                    echo "<tr>";
                    //echo '<td colspan="' . $colnum . '" class="lasttd" align="right"><img src="images/print.png" width="20" height="20"> <span class="arial11redbold">= ' . get_lng($_SESSION["lng"], "L0014") . '</span>,<img src="images/edit.png" width="20" height="20"><span class="arial11redbold"> = ' . get_lng($_SESSION["lng"], "L0015") . '</span>,  <img src="images/delete.png" width="20" height="20"><span class="arial11redbold">= ' . get_lng($_SESSION["lng"], "L0016") . '</span></td>';
                    echo '<td colspan="' . $colnum . '" class="lasttd" align="right"><img src="images/print.png" width="20" height="20"> <span class="arial11redbold">= ' . get_lng($_SESSION["lng"], "L0014") . '</span>,  <img src="images/delete.png" width="20" height="20"><span class="arial11redbold">= ' . get_lng($_SESSION["lng"], "L0016") . '</span></td>';
					?>
                </tr>
            </table>
	
			
            <div id="dialog-form" title="<?php echo $formtitle ?>"  style="display: none;" >
                 <p class="validateTips"><?php echo get_lng($_SESSION["lng"], "L0036"); ?><!--All form fields are required.--></p>
                <p class="validateTips2"></p>

                <form>
                    <table width="97%" border="0"   cellspacing="0" cellpadding="0">
                        <tr class="arial11blackbold">
                            <td width="3%">&nbsp;</td>
                            <td width="27%" class="arial11redbold"><?php echo get_lng($_SESSION["lng"], "L0003"); ?><!--Customer Number--></td>
                            <td width="3%">:</td>
                            <td width="23%"><?php echo $cusno ?></td>
                            <td width="3%"></td>
                            <td width="16%" class="arial11redbold"><?php echo get_lng($_SESSION["lng"], "L0004"); ?><!--Customer Name--></td>
                            <td width="1%">:</td>
                            <td width="24%"><?php echo $cusnm ?></td>
                        </tr>
                        <tr class="arial11blackbold">
                            <td colspan="8">&nbsp;</td>
                        </tr>
                        <tr class="arial11blackbold">
                            <td>&nbsp;</td>
                            <td class="arial11redbold"><?php echo get_lng($_SESSION["lng"], "L0040"); ?><!--Order Date--></td>
                            <td>:</td>
                            <td>
                                <?php
                                $orddt = date("d-m-Y");
                                echo "<input name=\"orddate\" type=\"text\"  id=\"orddate\" class=\"arial11blackbold\" readonly=\"true\"  maxlength=\"10\" size=\"10\" value=" . $orddt . ">";
                                ?>
                            </td>
                            <td></td>
                            <td><span class="arial11greybold"><?php echo get_lng($_SESSION["lng"], "L0039"); ?><!--Denso Order Number</span>--></td>
                            <td>&nbsp;</td>
                            <td><input type="text" name="orderno" id="orderno" readonly="true" class="arial11grey"></td>
                        </tr>
                        <?php if ($ordertype == 'Request') { ?>
                            <tr class="arial11blackbold">
                                <td colspan="8">&nbsp;</td>
                            </tr>
                            <tr class="arial11blackbold">
                                <td>&nbsp;</td>
                                <td><span class="arial11redbold"><?php echo get_lng($_SESSION["lng"], "L0350"); ?><!--DENSO Shipping Day (ETD)--></span></td>
                                <td>:</td>
                                <td  class="arial11blackbold"><input name="requestDate" id="requestDate" type="text" size="12" maxlength="12" readonly></td>
                                <td  class="arial11blackbold"><input name="requestDate2" id="requestDate2" type="hidden"></td>
                                <td  class="arial11blackbold">&nbsp;</td>
                                <td  class="arial11blackbold">&nbsp;</td>
                                <td  class="arial11blackbold">&nbsp;</td>
                            </tr>
                        <?php } ?>
                        <tr class="arial11blackbold">
                            <td colspan="8">&nbsp;</td>
                        </tr>
                        <tr class="arial11blackbold">
                            <td>&nbsp;</td>
                            <td><span class="arial11redbold"><?php echo get_lng($_SESSION["lng"], "L0041"); ?><!--Order Type--></span></td>
                            <td>:</td>
                            <td  class="arial11blackbold"><input type="text" name="ordertype" id="ordertype" readonly="true" class="arial11grey" value="<?php echo $ordertype; ?>"></td>
                            <td  class="arial11blackbold">&nbsp;</td>
                            <td  class="arial11blackbold">&nbsp;</td>
                            <td  class="arial11blackbold">&nbsp;</td>
                            <td  class="arial11blackbold">&nbsp;</td>
                        </tr>
                        <tr class="arial11blackbold">
                            <td colspan="8">&nbsp;</td>
                        </tr>
                        <tr class="arial11blackbold">
                            <td>&nbsp;</td>
                            <td><span class="arial11redbold"><?php echo get_lng($_SESSION["lng"], "L0042"); ?><!--Po Number--></span></td>
                            <td>:</td>
                            <td  class="arial11blackbold"><input type="text" name="txtCorno" id="txtCorno" onkeypress="checkThai(event);" class="arial11blackbold" maxlength="20" size="20"></td>
                            <td  class="arial11blackbold">&nbsp;</td>
                            <td  class="arial11blackbold">&nbsp;</td>
                            <td  class="arial11blackbold">&nbsp;</td>
                            <td  class="arial11blackbold">&nbsp;</td>
                        </tr>
                        <tr class="arial11blackbold">
                            <td colspan="8">&nbsp;</td>
                        </tr>
                        <tr class="arial11blackbold">
                            <td>&nbsp;</td>
                            <td><span class="arial11redbold"><?php echo get_lng($_SESSION["lng"], "L0043"); ?><!--Ship To--></span></td>
                            <td>:</td>
                            <td colspan="5"  class="arial11blackbold">
                            <?
                            $qryshp="SELECT cusmas.Cusno, cusmas.ESCA1, cusmas.ESCA2, cusmas.ESCA3, cusmas.OECus, cusrem.curcd, cusrem.remark FROM `cusmas` LEFT JOIN cusrem ON cusmas.cusno = cusrem.cusno AND cusmas.Owner_Comp = cusrem.Owner_Comp  where  trim(cusmas.cust3) ='$cusno' and cusmas.Owner_Comp='$comp' order by cusmas.Cusno" ;
                            $sqlshp=mysqli_query($msqlcon,$qryshp);
                            $mcount = mysqli_num_rows($sqlshp);
                                while($hasil = mysqli_fetch_array ($sqlshp)){
                                    $bcusno=$hasil['Cusno'];
                                    $vremark=$hasil['remark'];
                                    $vcurcd=$hasil['curcd'];
                                    $voecus=$hasil['OECus'];
                                    if(strtoupper($voecus)!='Y'){
                                        $voecus='N';
                                    }
                                    $gabung=$bcusno . ' - '. $vremark . '  (' .$vcurcd.')' ;
                                    echo '<input type="hidden" name="txtShpNoHidden" id="txtShpNoHidden" value="'.$voecus .'"/>';
                            }
                            ?>
                                <select name="txtShpNo" id="txtShpNo" style="width: 300px" onchange="getShipToAddressAjax(this.value)"></select>
                            </td>
                        </tr>
                        <tr class="arial11blackbold">
                            <td colspan="8">&nbsp;</td>
                        </tr>
                        <tr class="arial11blackbold">
                            <td>&nbsp;</td>
                            <td style="vertical-align:top;"><span class="arial11redbold"><?php echo get_lng($_SESSION["lng"], "L0311"); ?><!--Ship To Address--></span></td>
                            <td style="vertical-align:top;">:</td>
                            <td colspan="5"  class="arial11blackbold">
                                <label id="shipToAddress"></label>
                            </td>
                        </tr>
<!-- Zia Added Note-->						
						<tr class="arial11blackbold">
                            <td>&nbsp;</td>
                            <td><span class="arial11redbold"><?php echo get_lng($_SESSION["lng"], "L0334"); ?><!--Note--></span></td>
                            <td>:</td>
                            <td  class="arial11blackbold" colspan="5"><input type="textarea " rows="8" cols="50" name="txtnote" id="txtnote"  class="arial11blackbold" maxlength="100" size="80" placeholder="Optional"></td>
                            
							<!--
							<td  class="arial11blackbold">&nbsp;</td>
                            <td  class="arial11blackbold">&nbsp;</td>
                            <td  class="arial11blackbold">&nbsp;</td>
                            <td  class="arial11blackbold">&nbsp;</td>
							-->
                        </tr>
						
<!-- Zia Added Note-->							
						
                        <tr class="arial11blackbold" id="result_tr">
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td colspan="5"  class="arial11blackbold">&nbsp;</td>
                        </tr>
                        <tr class="arial11blackbold" id="result_tr1" >
                            <td>&nbsp;</td>
                            <td><span class="arial11redbold">Shipment Mode</span></td>
                            <td>:</td>
                            <td colspan="5"  class="arial11blackbold"><label>
                                    <select name="shipment" id="shipment">
                                        <option value="S" selected>Sea</option>
                                        <option value="A">Air</option>
                                    </select>
                                </label></td>
                        </tr>


                    </table>
                </form>


            </div>
            
<?php  //} ?> <!--DSMN Price disable-->      
            
        <div id="footerMain1">
            <ul>
                <!-- Disable by Zia
                     
                          
                -->
            </ul>

            <div id="footerDesc">

                <p>
                    Copyright &copy; 2023 DENSO . All rights reserved

            </div>
        </div>

        </div>


</body>
<?php include('timecheck.php');?><!--03/10/2019 Prachaya inphum CTC-->

</html>
