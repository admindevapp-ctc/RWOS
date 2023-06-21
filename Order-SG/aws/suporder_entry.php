<?php 

session_start();
require_once('../../core/ctc_init.php');
require_once('../../language/Lang_Lib.php');
require_once('../../core/ctc_permission.php');

ctc_checkuser_permission('../../login.php');

$comp = ctc_get_session_comp();
$cusno = ctc_get_session_cusno();
$cusnm = ctc_get_session_cusnm();
$table = ctc_get_session_awstablenamesup();
include('chklogin.php');
include "crypt.php";
$var = decode($_SERVER['REQUEST_URI']);
$xordno = trim($var['ordno']);
$vcusno = trim($var['cusno']);
$vcdate = trim($var['orddate']);
$vcorno = trim($var['corno']);
$vshpno = trim($var['shpno']);
$shpCd = trim($var['shpCd']);
$cyear = trim($var['prdyear']);
$action = trim($var['action']);
$oecus = trim($var['oecus']);
$shipment = trim($var['shipment']);
$ordertype = trim($var['ordertype']);
$requestDate = $var['requestDate'] != null ? trim($var['requestDate']) : "";
$txtnote = trim($var['txtnote']);

if ($ordertype == '' || $ordertype == 'Normal') {
    $ordertype = 'Normal';
    $_GET['selection'] = "main";
} else if ($ordertype == 'Urgent') {
    $_GET['selection'] = "urgentOrder";
} else if ($ordertype == 'Request') {
    $_GET['selection'] = "requestDueDate";
}

include('chklogin.php');
?>

<html>
    <head>
    <title>Denso Ordering System</title>
	<meta http-equiv="X-UA-Compatible" content="IE=9, IE=10, IE=11, IE=EDGE" />  <!--02/04/2018 P.Pawan CTC-->
   <link rel="stylesheet" type="text/css" href="../css/dnia.css">
      <!--	 <link rel="stylesheet" href="themes/ui-lightness/jquery-ui.css">-->

<!--[if IE]>
<style type="text/css">
#twocolLeft{ padding-top: 0px; }
#twocolRight { zoom: 1; padding-top:10px; }
</style>
<![endif]-->
<script src="lib/jquery-1.4.2.js"></script>
 <link rel="stylesheet" href="../themes/ui-lightness/jquery-ui-green.css">
	<script src="../lib/jquery.bgiframe-2.1.2.js"></script>
	<script src="../lib/jquery.ui.core.js"></script>
 	<script src="../lib/jquery.ui.widget.js"></script>
    <script src="../lib/jquery.ui.mouse.js"></script>
	<script src="../lib/jquery.ui.button.js"></script>
	<script src="../lib/jquery.ui.draggable.js"></script>
	<script src="../lib/jquery.ui.position.js"></script>
	<script src="../lib/jquery.ui.resizable.js"></script>
	<script src="../lib/jquery.ui.dialog.js"></script>
	<script src="../lib/jquery.effects.core.js"></script>
    <script src="../lib/jquery.ui.autocomplete.js"></script>


   <script type="text/javascript" src="../lib/jquery.tipsy.js"></script>
    <link rel="stylesheet" href="../css/tipsy.css" type="text/css" />
	<link rel="stylesheet" href="../css/tipsy-docs.css" type="text/css" />
	<link rel="stylesheet" href="../css/demos.css"> 

	<link rel="stylesheet" href="../css/ui/jquery.ui.base.css">
	<?php if($ordertype=='' || $ordertype=='Normal'){?>
    <link rel="stylesheet" href="../themes/ui-lightness/jquery-ui-green.css">
	<?php }else if($ordertype=='Urgent'){?>
	<link rel="stylesheet" href="../themes/ui-lightness/jquery-ui-red.css">
	<?php }else if($ordertype=='Request'){?>
	<link rel="stylesheet" href="../themes/ui-lightness/jquery-ui.css">
	<?php }?>
	<script src="../lib/ui/jquery-1.8.2.js"></script>
	<script src="../lib/ui/jquery.ui.core.js"></script>
	<script src="../lib/ui/jquery.ui.widget.js"></script>
	<script src="../lib/ui/jquery.ui.button.js"></script>
	<script src="../lib/ui/jquery.ui.position.js"></script>
	<script src="../lib/ui/jquery.ui.menu.js"></script>
	<script src="../lib/ui/jquery.ui.autocomplete.js"></script>
	<script src="../lib/ui/jquery.ui.tooltip.js"></script>

	<script src="../lib/ui/jquery.ui.resizable.js"></script>
	<script src="../lib/ui/jquery.ui.dialog.js"></script>
	<link rel="stylesheet" href="../css/ui/demos.css">


    <style>
		.disabled{ pointer-events:none;opacity:0.4;}
			body { font-size: 62.5%; }
		input.text { margin-bottom:12px; width:95%; padding: .4em; }
		input[type=radio]{
			display:inline;
			margin-top:0px;
		}

		fieldset { padding:0; border:0; margin-top:25px; }

		h1 { font-size: 1.2em; margin: .6em 0; }
		div#users-contain { width: 350px; margin: 20px 0; }
		div#users-contain table { margin: 1em 0; border-collapse: collapse; width: 100%; }
		div#users-contain table td, div#users-contain table th { border: 1px solid #eee; padding: .6em 10px; text-align: left; }
		.ui-dialog .ui-state-error { padding: .3em; }
		.validateTips { border: 1px solid transparent; padding: 0.3em; }

		button .btn{
			height: 18px;
			width: 18px;
		}
	.ui-combobox {
		position: relative;
		display: inline-block;
	}
	.ui-combobox-toggle {
		position: absolute;
		top: 0;
		bottom: 0;
		margin-left: -1px;
		padding: 0;
		/* adjust styles for IE 6/7 */
		*height: 1.7em;
		*top: 0.1em;
	}
	.ui-combobox-input {
		margin: 0;
		padding: 0.3em;
		width:380px;
	}

.ui-autocomplete {
max-height: 350px;
overflow-y: auto;
/* prevent horizontal scrollbar */
overflow-x: hidden;
/* add padding to account for vertical scrollbar */
padding-right: 0px;
}
/* IE 6 doesn�t support max-height
* we use height instead, but this forces the menu to always be this tall
*/
* html .ui-autocomplete {
height: 350px;
}
.vertical-center {
            margin: 0;
            position: absolute;
            top: 24px;
            -ms-transform: translateY(-50%);
            transform: translateY(-50%);
        }

	</style>


<script type="text/javascript">
		function grandtotal() {
            var sumqty = 0;
            var sumttl = 0;
            $('#myTable').find('td.qty').each(function() {
                sumqty += parseFloat($(this).text().replace(/,/g, ''));
            });
            $('#myTable').find('td.ttl').each(function() {
				let ttl_amt = isNaN(parseFloat($(this).text().replace(/,/g, ''))) ? 0 :parseFloat($(this).text().replace(/,/g, ''));

                sumttl += ttl_amt;
            });
			
			sumttl = sumttl.toLocaleString('en-US', {
					minimumFractionDigits: 2,
					maximumFractionDigits: 2
				});
			sumqty = sumqty.toLocaleString('en-US', {
				minimumFractionDigits: 0,
				maximumFractionDigits: 0
			});
				

            $('input.amt-txt').val(number_format(sumqty));
            $('input.ttl-txt').val(number_format(sumttl));
        }
        function number_format(nStr) {
            // nStr += '';
            // x = nStr.split('.');
            // x1 = x[0];
            // x2 = x.length > 1 ? '.' + x[1] : '';
            // var rgx = /(\d+)(\d{3})/;
            // while (rgx.test(x1)) {
                // x1 = x1.replace(rgx, '$1' + ',' + '$2');
            // }
            // return x1 + x2;
			const actualNumber = nStr;
			const formatted = actualNumber.toLocaleString('en-US', {maximumFractionDigits: 2, minimumFrationDigits:2})
			
			return formatted;
        }
            var btnAction = 'add';

            $(function () {
				grandtotal();
                $("#dialog-timeout").dialog({
                    autoOpen: false,
                    width: 300,
                    height: 'auto',
                    modal: true,
                    position: {
                        my: "center",
                        at: "center",
                        of: $("body"),
                        within: $("body")
                    },
                    buttons: [
                        {
                            text: "<?php echo get_lng($_SESSION["lng"], "L0372"); /* OK */ ?>", //Message
                            click: function () {
                                $(this).dialog("close");
                            }
                        }
                    ],
                    close: function () {
                        $('#dialog-form').dialog("close");
                        $("#cmdSave").attr("disabled", true);
                        $("#cmdSave").addClass('disabled');
                        jQuery('#cmdClose').unbind('click');
                        jQuery('#cmdClose').click(function () {
                            window.location.href = "supcata_cataloguemain.php";
                        });
                    }
                });
                $().ajaxStart(function () {
                    $('#loading').show();
                    $('#result').hide();
                }).ajaxStop(function () {
                    $('#loading').hide();
                    $('#result').fadeIn('slow');
                });

                $("#partno").combobox();
                $("#toggle").click(function () {
                    $("#combobox").toggle();
                });
                var vaction = "";
                var partno = $(".ui-combobox-input"),
                        qty = $("#qty"),
                        allFields = $([]).add(partno).add(qty),
                        tips = $(".validateTips");

                function updateTips(t) {
                    tips
                            .text(t)
                            .addClass("ui-state-highlight");
                    setTimeout(function () {
                        tips.removeClass("ui-state-highlight", 1500);
                    }, 500);
                }

                function checkLength(o, n, min, max) {
                    if (o.val().length > max || o.val().length < min) {
                        o.addClass("ui-state-error");
                        updateTips('<?php echo get_lng($_SESSION["lng"], "G0009"); /* Lengthss of Part Number must be between 2 and 15. */ ?>');
                        return false;
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

                $("#dialog-form").dialog({
                    autoOpen: false,
                    width: 450,
                    height: 550,
                    modal: true,
                    position: {
                        my: "center",
                        at: "center",
                        of: $("body"),
                        within: $("body")
                    },
                    buttons: {
                        '<?php echo get_lng($_SESSION["lng"], "L0074"); /* Check Part Info */ ?>': function (event) {
                            var epartno = partno.val();
                            var edata;
                            var quantity = qty.val();

                            if (vaction === 'add') {
                                var sign = '>>';
                                var endpos = partno.val().indexOf(sign);
                                var epartno = partno.val().substring(0, endpos);
                            } else {
                                epartno = partno.val();
                            }
                            var ordertype = $('#ordstatus').val();
                            edata = "partno=" + epartno + "&qty=" + quantity + "&action=" + vaction + "&ordertype=" + ordertype;
                            
                            $.ajax({
                                type: 'GET',
                                url: '../supcheckPartInfo.php',
                                data: edata,
                                success: function (data) {
                                    //console.log(data);
                                    $(".validateTips2").html(data);
                                }
                            });

                        },
                        '<?php echo get_lng($_SESSION["lng"], "L0075"); /* Save Order Detail */ ?>': function () {
                            var oldPart = partno.val();
                            if (vaction === 'add') {
                                var sign = '>>';
                                var endpos = partno.val().indexOf(sign);
                                var epartno = partno.val().substring(0, endpos);
                                partno.val(epartno);
                            } else {
                                epartno = partno.val();
                            }
                            //alert(partno);
                            var bValid = true;
                            allFields.removeClass("ui-state-error");
                            bValid = bValid && checkLength(partno, "Part Number", 2, 15);
                            bValid = bValid && checkRegexp(partno, /([0-9a-z_-])+$/i, "Part number may consist of a-z,-, 0-9"); //Message
                            bValid = bValid && checkRegexp(qty, /^[1-9][0-9]*$/, '<?php echo get_lng($_SESSION["lng"], "W0010"); /* "Qty should be greater than 0" */ ?>');

                            var edata;
                            var orderno = $('#orderno').val();
                            var corno = $('#corno').val();
                            var quantity = qty.val();
                            var vcusno = $('#shpno').val();
                            var oecus = $('#oecus').val();
                            var shipment = '';//$('#shipment').val();
                            var ordertype = $('#ordstatus').val();
                            if (ordertype === 'Request') {
                                parts = $('#requestDate').val().split("-");
                                var passDueDate = parts[2] + parts[1] + parts[0];
                            }
                            var shpCd = $('#shpCd').val();
                            edata = "partno=" + epartno + "&orderno=" + orderno + "&corno=" + corno + "&shpno=" + vcusno + "&oecus=" + oecus + "&shipment=" + shipment + "&qty=" + quantity + "&action=" + vaction + "&ordertype=" + ordertype + "&passDueDate=" + passDueDate + "&shpCd=" + shpCd;
                             console.log(edata);
                            // alert(edata);
                            if (bValid) {
                                $.ajax({
                                    type: 'GET',
                                    url: 'supgetpartno_new.php',
                                    data: edata,
                                    success: function (data) {
                                       //console.log(data);
                                      // alert(data);
                                        if (data.substr(0, 5) === 'Error' || data.substr(0, 7) === 'ผิดพลาด') {
                                            partno.addClass("ui-state-error");
                                            $(".validateTips").text(data).addClass("ui-state-highlight");
                                            partno.val(oldPart);
                                            return false;
                                        } else {
                                            var xdata = data.split("||");
                                            var itdsc = xdata[0];
                                            var curcd = xdata[1];
                                            var bprice = xdata[2];
                                            if(bprice == "NULL"){
                                                bprice="";
                                                ttlprice="";
                                            }
                                            else{
                                                 var ttlprice = xdata[3];
                                            }
                                            var ttlex = xdata[4];
                                            var duedt = xdata[5];
                                            var supno = xdata[6];
                                            var supnm = xdata[7];
                                            if (vaction === 'add') {
                                                $('#myTable > tbody:last').append("<tr>" +
                                                        "<td align=\"center\"><input name=\"chkaction[]\" type=\"checkbox\" class=\"chkaction\" value=" + partno.val() + "|"+ supno +" /></td>" +
                                                        "<td align=\"center\" id=\"supno\">" + supno + "</td>" + 
                                                        "<td align=\"center\" id=\"supnm\">" + supnm + "</td>" +
                                                        "<td align=\"center\">" + partno.val().toUpperCase() + " - " + itdsc + "</td>" +
                                                        "<td class=\"qty\" align=\"center\">" + qty.val() + "</td>" +
                                                        "<td class=\"curcd\" align=\"right\">" + curcd + "</td>" +
                                                        "<td class=\"price\" align=\"right\">" + bprice + "</td>" +
                                                        "<td class=\"ttl\" align=\"right\">" + ttlprice + "</td>" +
                                                        "<td id=\"duedt\" align=\"center\">" + duedt + "</td>" +
                                                        "</tr>");
                                            } else {

                                            var duedt =duedt.substring(6,8) + "/"+duedt.substring(4,6) + "/"+ duedt.substring(0,4);
                                                $.each($('.chkaction:checked'), function () {
                                                    $(this).closest('tr').children('td[class=qty]').text(quantity);
                                                    $(this).closest('tr').children('td[class=price]').text(bprice);
                                                    $(this).closest('tr').children('td[class=ttl]').text(ttlprice);
                                                    $(this).closest('tr').children('td[class=ttlex]').text(ttlex);
                                                    $(this).closest('tr').children('td[id=duedt]').text(duedt);
                                                    $(this).closest('tr').children('td[id=supno]').text(supno);
                                                    $(this).closest('tr').children('td[id=supnm]').text(supnm);
                                                });
                                                $('.chkaction:checked').attr('checked', false);
                                            }
                                            $("#dialog-form").dialog("close");
											grandtotal();
                                        }
                                    }
                                });
                            }else{
                                partno.val(oldPart);
                            }
                        },
                        '<?php echo get_lng($_SESSION["lng"], "L0373"); /* Cancel */ ?>': function () {
                            $(this).dialog("close");
                        }
                    },
                    close: function () {
                        allFields.val("").removeClass("ui-state-error");
                    }
                });

                $("#dialog-progress").dialog({
                    autoOpen: false,
                    width: 450,
                    height: 'auto',
                    modal: true,
                    open: function (event, ui) {
                        $(".ui-dialog-titlebar-close", ui.dialog | ui).click(function () {
                            var url = "suphistory.php";
                            $(location).attr('href', url);
                        });
                    },
                    position: {
                        my: "center",
                        at: "center",
                        of: $("body"),
                        within: $("body")
                    },
                    buttons: {
                        '<?php echo get_lng($_SESSION["lng"], "L0372"); /* OK */ ?>': function () {
                            var url = "<?php echo "suphistory.php?" . paramEncrypt("ordertype=Request") ?>"; /* Edit by CTC Sippavit 01/10/2020 */
                            $(location).attr('href', url);
                            $(this).dialog("close");
                        }
                    },
                    beforeClose: function (event, ui) {
                    }
                });

                $("#add").click(function () {

                    $(".ui-combobox-input").val("");
                    $(".ui-combobox-input").removeAttr("disabled");
                    $('#prtlist').removeAttr("disabled");
                    $(".validateTips").text('').removeClass("ui-state-highlight");
                    $(".validateTips2").text('').removeClass("ui-state-highlight");
                    $(".ui-combobox-input").removeClass("ui-state-error");
                    $("span.ui-dialog-title").text('<?php echo get_lng($_SESSION["lng"], "L0418"); /* New Request Due Date Order */ ?>');
                    $("#dialog-form").dialog("open");
                    vaction = 'add';
                    btnAction = 'add'
                });

                $("#dlt").click(function () {
                    mpart = $('input[name="chkaction[]"]:checked').map(function () {
                        return $(this).val();
                    }).get().join(",");
                    
                    if (mpart !== '') {
                        vaction = 'delete';
                        $("#dialog-confirm").dialog('open');
                    } else {
                        $("p[id=message]").text('<?php echo get_lng($_SESSION["lng"], "G0003"); /* "please at least select 1 document to delete!" */ ?>');
                        $("#dialog-message").dialog("open");
                    }
                });

                $("#chg").click(function () {
                    var x = $('input:checked').length;
                    if (x > 0) {
                        if (x > 1) {
                            alert('please choose only one row to edit');
                        } else {
                            
                            $(".validateTips").text('').removeClass("ui-state-highlight");

                            var epart = $('.chkaction:checked').val();
                            $.each($('.chkaction:checked'), function () {
                                eqty = ($(this).closest('tr').children('td[class=qty]').text());
                            });
                            var array = epart.split("|");

                            $('#partno').val(array[0]);

                            $(".ui-combobox-input").val(array[0]);
                            $(".ui-combobox-input").attr("disabled", true);
                            $("#prtlist").attr("disabled", true);

                            $('#qty').val(eqty);
                            vaction = 'edit';
                            btnAction = 'edit';
                            $("span.ui-dialog-title").text('<?php echo get_lng($_SESSION["lng"], "L0305")/* Edit Record */; ?>');
                            $("#dialog-form").dialog("open");
                        }
                    } else {
                        $("p[id=message]").text('<?php echo get_lng($_SESSION["lng"], "G0010"); ?>'/*'please at least select 1 document to edit!'*/);
                        $("#dialog-message").dialog("open");
						
                    }
                });

                $("#cmdSave").click(function () {
                    document.getElementById("progress-bar").style.display = "block";
                    var rowCount = $('#myTable >tbody >tr').length;
                    if (rowCount === 1) {
                        $("p[id=message]").text('<?php echo get_lng($_SESSION["lng"], "G0004"); /* "There are no Transaction to save, Please use close button!" */ ?>');
                        $("#dialog-message").dialog("open");
                        $("#progress-bar").hide();
                        return;
                    }

                    var edata;
                    var shpno = $('#shpno').val();
                    var orderno = $('#orderno').val();
                    var corno = $('#corno').val();
                    var action = $('#action').val();
                    var ordertype = $('#ordstatus').val();
                    var shpCd = $("#shpCd").val();
                    var txtnote = $("#txtnote").val();
                    edata = "orderno=" + orderno + "&corno=" + corno + "&shpno=" + shpno + "&action=" + action + "&ordertype=" + ordertype + "&shpCd=" + shpCd + "&txtnote=" + txtnote;//12/20/2018 P.Pawan CTC add parameter shpCd
//alert(edata);
        
<?php
$notiTxt = '';
$emailTo = [];
$tmpEmail = [];

 $query = "select *
        from awscusmas st
		INNER JOIN shiptoma ON shiptoma.Cusno  = st.cusno1 AND shiptoma.ship_to_cd = st.ship_to_cd1  and shiptoma.Owner_Comp = st.Owner_Comp
        where  trim(st.cusno2) ='" . $vcusno . "' and st.ship_to_cd2 = '" . $shpCd . "' 
        and st.Owner_Comp='$comp'";

$sql = mysqli_query($msqlcon, $query);

while ($axData = mysqli_fetch_array($sql)) {
    $comp_mail_add = $axData['comp_mail_add'];
    $pers_mail_add1 = $axData['prsn_mail_add1'];
    $pers_mail_add2 = $axData['prsn_mail_add2'];
    $pers_mail_add3 = $axData['prsn_mail_add3'];
    $prsnMail1 = $axData['mail_add1'];
    $prsnMail2 = $axData['mail_add2'];
    $prsnMail3 = $axData['mail_add3'];
}
array_push($tmpEmail,$comp_mail_add, $prsnMail1,$prsnMail2,$prsnMail3,$pers_mail_add1,$pers_mail_add2,$pers_mail_add3);
for ($index = 0; $index < count($tmpEmail); $index++) {
    if (!in_array($tmpEmail[$index], $emailTo)) {
        array_push($emailTo, $tmpEmail[$index]);
        $notiTxt .= "<br>";
        $notiTxt .= $tmpEmail[$index];
    }
}
?>
                    $.ajax({
                        type: 'GET',
                        url: 'supsaveorder_new.php',
                        data: edata,
                        success: function (data) {
                                console.log(data);
                               
                                $("#progress-bar").hide();
								var div = $("#dialog-progress").prev();
								if(data.indexOf("success")!==-1){
									//$("span#ui-id-3.ui-dialog-title").text('<?php echo get_lng($_SESSION["lng"], "L0314")/*Order successfully saved*/?>');
									div.children("span.ui-dialog-title").text('<?php echo get_lng($_SESSION["lng"], "L0314")/*Order successfully saved*/?>');
									$("p[id=notification]").html('');
									$("p[id=notification]").append('<b><?php echo get_lng($_SESSION["lng"], "L0315");/*PO has been sent via email to : */ ?></b><br>');
									$("p[id=notification]").append('<b"><?php echo $notiTxt ?></b>');
								}else if(data.indexOf("failPDF")!==-1){
									//$("span#ui-id-3.ui-dialog-title").text('<?php echo get_lng($_SESSION["lng"], "L0316")/*Email send failed*/?>');
									div.children("span.ui-dialog-title").text('<?php echo get_lng($_SESSION["lng"], "L0316")/*Email send failed*/?>');
									$("p[id=notification]").html('');
									$("p[id=notification]").append('<b><?php echo get_lng($_SESSION["lng"], "E0039");/*No PDF found. Please contact to DSTH or go to History Menu to request to send PO manually*/ ?></b><br>');
								}else if(data.indexOf("PDF error")!==-1){
									//$("span#ui-id-3.ui-dialog-title").text('<?php echo get_lng($_SESSION["lng"], "L0316")/*Email send failed*/?>');
									div.children("span.ui-dialog-title").text('<?php echo get_lng($_SESSION["lng"], "L0316")/*Email send failed*/?>');
									$("p[id=notification]").html('');
									$("p[id=notification]").append('<b><?php echo get_lng($_SESSION["lng"], "E0039");/*No PDF found. Please contact to DSTH or go to History Menu to request to send PO manually*/ ?></b><br>');
								}else{
									//$("span#ui-id-3.ui-dialog-title").text('<?php echo get_lng($_SESSION["lng"], "L0316")/*Email send failed*/?>');
									div.children("span.ui-dialog-title").text('<?php echo get_lng($_SESSION["lng"], "L0316")/*Email send failed*/?>');
									$("p[id=notification]").html('');
									$("p[id=notification]").append('<b><?php echo get_lng($_SESSION["lng"], "E0037");/*No email address found. Please contact to DSTH or go to History Menu to request to send PO manually*/ ?></b><br>');
								}
								$( "#dialog-progress" ).dialog( "open" );
                                
                          
                        }
                    });
                });


                $("#cmdClose").click(function () {
                    var answer = confirm("<?php echo get_lng($_SESSION["lng"], "G0002"); ?>");
                    if (answer) {
                        var edata;
                        var vcusno = $('#shpno').val();
                        var orderno = $('#orderno').val();
                        var corno = $('#corno').val();
                        var ordertype = $('#ordstatus').val();
                        edata = "shpno=" + vcusno + "&orderno=" + orderno + "&corno=" + corno + "&action=close&ordertype=" + ordertype;
                        $.ajax({
                            type: 'GET',
                            url: 'supsaveorder_new.php',
                            data: edata,
                            success: function (data) {
                                console.log(data);
                                $('#result').html(data);
                               window.location.href = 'supcata_cataloguemain.php'; /* Edit by CTC Sippavit 09/10/2020 */
                            }
                        });
                    }
                });

                $("#dialog-confirm").dialog({
                    autoOpen: false,
                    resizable: false,
                    height: 200,
                    position: {
                        my: "center",
                        at: "center",
                        of: $("body"),
                        within: $("body")
                    },
                    modal: true,
                    buttons: {
                        "<?php echo get_lng($_SESSION["lng"], "L0077")/* Delete selected */; ?>": function () {
                            $.each($('.chkaction:checked'), function () {
                                $(this).parent().parent().remove();
                            });
                            var edata;
                            var vcusno = $('#shpno').val();
                            var orderno = $('#orderno').val();
                            edata = "shpno=" + vcusno + "&orderno=" + orderno + "&partno=" + mpart;
                            //alert(edata);
                            $.ajax({
                                type: 'GET',
                                url: 'supdelpartno.php',
                                data: edata,
                                success: function (data) {
                                    console.log(data);
                                   // alert(data);
                                }
                            });
                            $(this).dialog("close");
                            grandtotal();
                        },
                            <?php echo get_lng($_SESSION["lng"], "L0373")/* Cancel */; ?>: function () {
                            $(this).dialog("close");
							
                        }
						
                    }
                });

                $("#dialog-message").dialog({
                    autoOpen: false,
                    modal: true,
                    position: {
                        my: "center",
                        at: "center",
                        of: $("body"),
                        within: $("body")
                    },
                    buttons: {
                            <?php echo get_lng($_SESSION["lng"], "L0372")/* Ok */; ?>: function () {
                            $(this).dialog("close");
							grandtotal();
                        }
                    }
                });
            });
            (function ($) {
                $.widget("ui.combobox", {
                    _create: function () {
                        var input,
                                that = this,
                                select = this.element.hide(),
                                selected = select.children(":selected"),
                                value = selected.val() ? selected.text() : "",
                                wrapper = this.wrapper = $("<span>")
                                .addClass("ui-combobox")
                                .insertAfter(select);

                        function removeIfInvalid(element) {
                            var value = $(element).val(),
                                    matcher = new RegExp("^" + $.ui.autocomplete.escapeRegex(value) + "$", "i"),
                                    valid = false;
                            select.children("option").each(function () {
                                if ($(this).text().match(matcher)) {
                                    this.selected = valid = true;
                                    return false;
                                }
                            });
                            if (!valid) {
                                // remove invalid value, as it didn't match anything
                                $(element)
                                        .val("")
                                        .attr("title", value + " <?php echo get_lng($_SESSION["lng"], "G0017"); ?>")
                                        .tooltip({
                                            position: {
                                                my: "center",
                                                at: "center",
                                                of: $("body"),
                                                within: $("body")
                                            }
                                        })
                                        .tooltip("open");
                                select.val("");
                                setTimeout(function () {
                                    input.tooltip("close").attr("title", "");
                                }, 2000);
                                input.data("autocomplete").term = "";
                                return false;
                            }
                        }

                        input = $("<input>")
                                .appendTo(wrapper)
                                .val(value)
                                .addClass("ui-state-default ui-combobox-input")
                                .autocomplete({
                                    delay: 0,
                                    minLength: 0,
                                    source: function (request, response) {
                                        $("#buffer").hide("slow");
                                        var matcher = new RegExp($.ui.autocomplete.escapeRegex(request.term), "i");
                                        var select_el = select.get(0); // get dom element
                                        var rep = new Array(); // response array
                                        var maxRepSize = 2500; // maximum response size
                                        // simple loop for the options
                                        for (var i = 0; i < select_el.length; i++) {
                                            var text = select_el.options[i].text;
                                            if (select_el.options[i].value && (!request.term || matcher.test(text)))
                                                // add element to result array
                                                rep.push({
                                                    label: text, // no more bold
                                                    value: text,
                                                    option: select_el.options[i]
                                                });
                                            if (rep.length > maxRepSize) {
                                                rep.push({
                                                    label: "... More Available",
                                                    value: "maxRepSizeReached",
                                                    option: ""
                                                });
                                                break;
                                            }
                                        }
                                        // send response
                                        response(rep);

                                    },
                                    select: function (event, ui) {
                                        ui.item.option.selected = true;
                                        that._trigger("selected", event, {
                                            item: ui.item.option
                                        });
                                    },
                                    change: function (event, ui) {
                                        if (!ui.item)
                                            return removeIfInvalid(this);
                                    },
                                    focus: function (event, ui) {
                                        if (ui.item.value === "maxRepSizeReached") {
                                            return false;
                                        }
                                    },
                                    search: function (event, ui) {
                                        $("#buffer").show("slow");
                                    }
                                })
                                .addClass("ui-widget ui-widget-content ui-corner-left");

                        input.data("autocomplete")._renderItem = function (ul, item) {
                            return $("<li>")
                                    .data("item.autocomplete", item)
                                    .append("<a>" + item.label + "</a>")
                                    .appendTo(ul);
                        };

                        $("<a>")
                                .attr("tabIndex", -1)
                                .attr("title", "Press here to load Partno")
                                .attr("id", "prtlist")
                                .attr("name", "prtlist")
                                .tooltip({
                                    position: {
                                        my: "center",
                                        at: "center",
                                        of: $("body"),
                                        within: $("body")
                                    }
                                })
                                .appendTo(wrapper)
                                .button({
                                    icons: {
                                        primary: "ui-icon-triangle-1-s"
                                    },
                                    text: false
                                })
                                .removeClass("ui-corner-all")
                                .addClass("ui-corner-right ui-combobox-toggle")
                                .click(function () {
                                    if(btnAction == 'add'){
                                        // close if already visible
                                        if (input.autocomplete("widget").is(":visible")) {
                                            input.autocomplete("close");
                                            removeIfInvalid(input);
                                            return;
                                        }

                                        // work around a bug (likely same cause as #5265)
                                        $(this).blur();

                                        // pass empty string as value to search for, displaying all results
                                        input.autocomplete("search", "");
                                        input.focus();
                                    }else{
                                        input.autocomplete( "close" );
							            return;
                                    }
                                });
                        input.tooltip({
                            position: {
                                my: "center",
                                at: "center",
                                of: $("body"),
                                within: $("body")
                            },
                            tooltipClass: "ui-state-highlight"
                        });
                    },

                    destroy: function () {
                        this.wrapper.remove();
                        this.element.show();
                        $.Widget.prototype.destroy.call(this);
                    }
                });
            })(jQuery);
        </script>
    </head>
    <body>
        <?php
        if ($shipment == 'A') {
            $vshipment = 'by Air';
        } else {
            $vshipment = 'by Sea';
        }

        $inpoecus = "<input name=\"oecus\" type=\"hidden\"  id=\"oecus\" class=\"arial11blackbold\"  maxlength=\"20\" size=\"20\" readonly=\"true\" value='$oecus' />";
        if (strtoupper($oecus) == 'Y') {
            $inpshipment = "<input name=\"shipment\" type=\"hidden\"  id=\"shipment\" class=\"arial11blackbold\"   maxlength=\"20\" size=\"20\" readonly=\"true\" value='$shipment' />";
            $inpshipmod = '<tr class="arial11blackbold"> <td>&nbsp;</td>  <td>&nbsp;</td> <td  class="arial11blackbold">&nbsp;</td>';
            $inpshipmod = $inpshipmod . ' <td></td> <td>&nbsp;</td> <td>&nbsp;</td> <td>&nbsp;</td> </tr><tr class="arial11blackbold">  <td>Shipment Mode</td>';
            $inpshipmod = $inpshipmod . ' <td>:</td> <td colspan="5"  >' . $vshipment . $inpshipment . '</td>    </tr>';
        } else {
            $inpshipment = "<input name=\"shipment\" type=\"hidden\"  id=\"shipment\" class=\"arial11blackbold\"  maxlength=\"20\" size=\"20\" readonly=\"true\" value='S' />";
            $inpshipmod = $inpshipment;
        }

        echo "<input type=\"hidden\" name=\"action\" id=\"action\" value=" . $action . ">";

        $query = "select * from cusrem where cusno = '" . $vcusno . "' and Owner_Comp='$comp' ";
        $sql = mysqli_query($msqlcon, $query);
        $hasil = mysqli_fetch_array($sql);
        if ($hasil) {
            $vremark = $hasil['remark'];
            $vcurcd = $hasil['curcd'];
            $alamat = $vremark . '  (' . $vcurcd . ')';
        }
        $inputshpno = "<input type=\"hidden\" name=\"shpno\" type=\"text\"  id=\"shpno\" class=\"arial11blackbold\"  value=" . $vcusno . ">";

        $txtcorno = "<input name=\"corno\" type=\"text\"  id=\"corno\" class=\"arial11blackbold\"  maxlength=\"20\" size=\"20\" readonly=\"true\" value='" . $vcorno . "'>";

        $inputordno = "<input name=\"orderno\" type=\"text\"  id=\"orderno\" class=\"arial11blackbold\" readonly=\"true\" value=" . $xordno . ">";
        ?>
        <?php ctc_get_logo() ?>
        <div id="mainNav">
            <ul>
                <li id="current"><a href="#" onClick="alert('<?php echo get_lng($_SESSION["lng"], "G0012"); /* 'Please use Close button to move from transaction menu! ' */ ?>')"><?php echo get_lng($_SESSION["lng"], "L0046"); ?><!--Ordering--></a></li>
                <li><a href="#" onClick="alert('<?php echo get_lng($_SESSION["lng"], "G0012"); /* 'Please use Close button to move from transaction menu! ' */ ?>')"><?php echo get_lng($_SESSION["lng"], "L0047"); ?><!--User Profile--></a></li>
                <li><a href="#" onClick="alert('<?php echo get_lng($_SESSION["lng"], "G0012"); /* 'Please use Close button to move from transaction menu! ' */ ?>')"><?php echo get_lng($_SESSION["lng"], "L0048"); ?><!--Table Part--></a></li>
                <li><a href="#" onClick="alert('<?php echo get_lng($_SESSION["lng"], "G0012"); /* 'Please use Close button to move from transaction menu! ' */ ?>')"><?php echo get_lng($_SESSION["lng"], "L0049"); ?><!--Log out--></a></li>
            </ul>
        </div>
        <div id="isi">
            <div id="twocolRight1">
                <table width="97%" border="0" cellspacing="0" cellpadding="0">
                    <tr class="arial11blackbold">
                    <tr align="center">
                        <td colspan="7" class="arial11blackbold">
                            <?php
                            if ($ordertype == 'Urgent') {
                                require('countdown.php');
                            }
                            ?>
                        </td>
                    </tr>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td></td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    </tr>
                    <tr class="arial11blackbold">
                        <td width="22%"><?php echo get_lng($_SESSION["lng"], "L0397"); ?><!--Customer Number--></td>
                        <td width="2%">:</td>
                        <td width="26%"><?php echo $vcusno ?></td>
                        <td width="4%"></td>
                        <td width="20%"><?php echo get_lng($_SESSION["lng"], "L0398"); ?><!--Customer Name--></td>
                        <td width="2%">:</td>
                        <td width="25%"><?php echo $cusnm ?></td>
                    </tr>
                    <tr class="arial11blackbold">
                        <td><?php echo $inputshpno ?></td>
                        <td></td>
                        <td>&nbsp;</td>
                        <td></td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                    </tr>
                    <tr class="arial11blackbold">
                        <td><?php echo get_lng($_SESSION["lng"], "L0399"); ?><!--Ship To--></td>
                        <td>:</td>
                        <td colspan="5">
                            <table width="97%" border="0" cellspacing="0" cellpadding="0">
                                <tr>
                                    <td width="5%" class="arial11blackbold"><?php echo $shpCd ?><input type="hidden" id="shpCd" value="<?php echo $shpCd ?>"/><!--//12/20/2018 P.Pawan CTC--></td>
                                </tr>
                            </table>
                        <td>
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
                        <td style="vertical-align:top;"><?php echo get_lng($_SESSION["lng"], "L0400"); ?><!-- Ship To Address --></td>
                        <td style="vertical-align:top;">:</td>
                        <td colspan="5">
                            <table width="97%" border="0" cellspacing="0" cellpadding="0">
                                <tr>
                                    <?php
                                    //12/20/2018 P.Pawan CTC
                                    require('../../language/conn.inc');
                                    $query = "select ship_to_cd2 as ship_to_nm,ship_to_adrs1 adrs1 , ship_to_adrs2 adrs2
                                        ,ship_to_adrs3 adrs3, '' pstl_cd, '' comp_tel_no 
                                        from awscusmas
                                        where trim(ship_to_cd2) =trim('".$shpCd."') and trim(cusno2) = trim('".$vcusno."') 
                                        and Owner_Comp='$comp'"; 
                                  
                                    $sqlResult = mysqli_query($msqlcon, $query);
                                    while ($axQuery = mysqli_fetch_array($sqlResult)) {
                                        $ship_to_nm = $axQuery['ship_to_nm'];
                                        $adrs1 = $axQuery['adrs1'];
                                        $adrs2 = $axQuery['adrs2'];
                                        $adrs3 = $axQuery['adrs3'];
                                        $pstl_cd = $axQuery['pstl_cd'];
                                        $comp_tel_no = $axQuery['comp_tel_no'];
                                        echo "<td width=\"5%\" class=\"arial11blackbold\" id=\"shipToAddress\">" . $adrs1 . "<br>" . $adrs2 . "<br>" . $adrs3 . " " . $pstl_cd . "<br>" . $comp_tel_no . "</td>";
                                    }
                                    ?>
                                </tr>
                            </table>
                        <td>
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
                        <td><?php echo get_lng($_SESSION["lng"], "L0401"); ?><!--Order Date--></td>
                        <td>:</td>
                        <td><? echo date("d-m-Y") ?></td>
                        <td></td>
                        <td><?php echo get_lng($_SESSION["lng"], "L0402"); ?><!--Denso Order Number--></td>
                        <td>:</td>
                        <td class="arial11blackbold"><?php echo $inputordno ?><?php echo $inpoecus; ?></td>
                    </tr>
                    <tr class="arial11blackbold">
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td colspan="5"><div id="errthn" class="arial11redbold"></div></td>
                    </tr>
                    <tr class="arial11blackbold">
                        <td><?php echo get_lng($_SESSION["lng"], "L0403"); ?><!--PO Number--></td>
                        <td>:</td>
                        <td  class="arial11blackbold" id="testtest">
                            <?php echo $txtcorno ?>
                        </td>
                        <td></td>
                        
                        <?php //if ($ordertype == 'Request') { ?>
                            <td><?php //echo get_lng($_SESSION["lng"], "L0404"); // Request Due Date ?></td>
                            <td></td>
                            <td><input type="text" id="requestDate" value="<?php echo $requestDate ?>" readonly class="arial11blackbold" style="display:none;" /></td>
                        <?php //} else {
                            ?>
                        
                            <td colspan="3">&nbsp;</td>
                        <?php// } ?>
                    </tr>
                    <tr class="arial11blackbold">
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td class="arial11blackbold">&nbsp;</td>
                        <td></td>
                        <td colspan="3">&nbsp;</td>
                    </tr>
                    <tr class="arial11blackbold">
                        <td><?php echo get_lng($_SESSION["lng"], "L0405"); ?><!--Order Type--></td>
                        <td>:</td>
                        <td><input type="text" id="ordstatus" value="<?php echo $ordertype ?>" readonly class="arial11blackbold" /></td>
                        <td></td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
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
                        <td style="vertical-align:top;"><?php echo get_lng($_SESSION["lng"], "L0406"); ?><!-- Note --></td>
                        <td style="vertical-align:top;">:</td>
                        <td colspan="5"><?php echo $txtnote ?><input type="hidden" id="txtnote" value="<?php echo $txtnote ?>" /></td>
                    </tr>
                    <?php echo $inpshipmod; ?>
                </table>
                <p>&nbsp;</p>
                <table width="97%" border="0" cellspacing="0" cellpadding="0">
                    <tr align="right">
                        <td width="10%" ><input type="button" value="<?php echo get_lng($_SESSION["lng"], "L0413"); /* Save Order Entry */ ?>" id="cmdSave" class="arial11blackbold" style="margin-right: 5px;"/></td>
                        <td width="8%"><input type="button" class="arial11blackbold" id="cmdClose" value="<?php echo get_lng($_SESSION["lng"], "L0414"); /* Close Order Entry */ ?>" /></td>
                        <td width="10%">&nbsp;</td>
                        <td width="4%">&nbsp;</td>
                        <td width="50%"></td>
						<td>
							<div style="width: 463px;background-color: white;border-radius: 5px;height: 44px;">
								<div style="float:left; position:relative; width:20%; height: 40px;">
									<div class="vertical-center" style="right:10px;font-size: 12px;font-weight: 700;"><?php echo get_lng($_SESSION["lng"], "L0237");/*Grand Total*/ ?></div>
								</div>
								<div style="width:80%;">
									<div style="float:left; text-align:center;">
										<div style="font-size: 12px;font-weight: 600;color: #ad1d36;"><?php echo get_lng($_SESSION["lng"], "L0235");/*QTY*/ ?></div>
										<div><input style="text-align:right;border-radius: 5px;" type="text" class="amt-txt" readonly /></div>
									</div>
									<div style="text-align:center;">
										<div style="font-size: 12px;font-weight: 600;color: #ad1d36;"><?php echo get_lng($_SESSION["lng"], "L0236");/*Amount*/ ?></div>
										<div><input style="text-align:right;border-radius: 5px;" type="text" class="ttl-txt" readonly /></div>
									</div>
								</div>
							</div>
						</td>
                        <td width="37%">
                            <button class="btn" id="add"><span class="arial11blackbold"><img src="../images/add.png" title="add new record" width="18" height="18"></span></button>
                            <button class="btn" id="chg"><span class="arial11blackbold"><img src="../images/edit.png" title="Change record" width="18" height="18"></span></button>
                            <button class="btn" id="dlt"><span class="arial11blackbold"> <img src="../images/delete.png" title="Delete record" width="18" height="18"></span></button>
                        </td>
                    </tr>
                </table>
                <table width="97%" border="0" cellspacing="0" cellpadding="0">
                    <tr class="arial11redbold"  align="center" >
                        <td width="7%" height="10"></td>
                        <td width="37%"></td>
                        <td width="46%"></td>
                        <td width="10%"></td>
                    </tr>
                </table>
                <table class="tblorder"  width="97%" border="0" cellpadding="0" cellspacing="0"  id="myTable">
                    <tbody>
                        <tr align="center" valign="middle"  bgcolor="#990033" class="arial11whitebold" >
                            <th width="3%" height="30"><?php echo get_lng($_SESSION["lng"], "L0059"); ?><!--Set--></th>
                            <th width="5%" ><?php echo get_lng($_SESSION["lng"], "L0442"); ?> <!--Supplier Code --> </th>
                            <th width="11%" ><?php echo get_lng($_SESSION["lng"], "L0452"); ?> <!--Supplier Name --> </th>
                            <th width="15%" ><?php echo get_lng($_SESSION["lng"], "L0407"); ?><!--Supplier Number--></th>
                            <th width="15%" ><?php echo get_lng($_SESSION["lng"], "L0381"); ?><!--Qty--></th>
                            <th width="8%" ><?php echo get_lng($_SESSION["lng"], "L0379"); ?><!--Curr--></th>
                            <th width="13%" ><?php echo get_lng($_SESSION["lng"], "L0409"); ?><!--Price--></th>
                            <th width="15%" ><?php echo get_lng($_SESSION["lng"], "L0411"); ?><!--Amount--></th>
                            <th width="15%" class="lastth"><?php echo get_lng($_SESSION["lng"], "L0412"); ?><!--Due Date (DD-MM-YYYY)--></th>
                        </tr>
                        <?php
                        $ctcdb = new ctcdb();
                        $sql = "select distinct a.*,b.Supcd as supno 
                            , (select supnm from supmas where supno = b.Supcd and Owner_comp = b.Owner_Comp)as supname
                            from $table a 
                                join supcatalogue b on a.partno = b.ordprtno 
                                    and a.Owner_Comp = b.Owner_Comp and a.supno = b.Supcd 
                            where trim(a.cusno) ='" . $vcusno . "' 
                            and trim(a.orderno)='" . $xordno . "' 
                            and a.Owner_Comp='$comp' order by a.partno";
                        // echo $sql;
                        $sth = $ctcdb->db->prepare($sql);
                        $sth->execute();
                        $result = $sth->fetchAll(PDO::FETCH_ASSOC);

                        foreach ($result as $hasil) {
                            $partno = $hasil['partno'];
                            $partdes = $hasil['partdes'];
                            $supno = $hasil['supno'];
                            $supname = $hasil['supname'];
                            $curcd = $hasil['CurCD'];
                            $qty = $hasil['qty'];
                            $disc = $hasil['disc'];
                            $bprice = $hasil['bprice'];
                            $slsprice = $hasil['slsprice'];
                            $duedt = substr($hasil['DueDate'], -2) . "/" . substr($hasil['DueDate'], 4, 2) . "/" . substr($hasil['DueDate'], 0, 4);
                           
                            if($bprice == "" || $bprice == "NULL"){
                                $vbprice = "";
                                $ttl ="";
                                $ttlex ="";
                            }
                            else{
                                $vbprice = number_format($bprice, 2, ".", ",");
                                $ttl = number_format($slsprice * $qty, 2, ".", ",");
                                $ttlex = number_format($slsprice * $qty * $exrate, 2, ".", ",");

                            }
                            $exrate = $hasil['SGPrice'];


                            echo "<tr>";
                            echo "<td align=\"center\"><input name=\"chkaction[]\" type=\"checkbox\" class=\"chkaction\" value='" . $partno . "|".$supno."'></td>";
                            echo "<td align=\"center\">" . $supno . "</td>";
                            echo "<td align=\"center\">" . $supname . "</td>";
                            echo "<td align=\"center\">" . $partno . " - " . $partdes . "</td>";
                            echo "<td class=\"qty\" align=\"center\">" . $qty . "</td>";
                            echo "<td class=\"curcd\" align=\"center\">" . $curcd . "</td>";
                            echo "<td class=\"price\" align=\"right\">" . $vbprice . "</td>";
                            echo "<td class=\"ttl\" align=\"right\">" . $ttl . "</td>";
                            echo "<td id=\"duedt\" class=\"lasttd\" align=\"center\">" . $duedt . "</td>";
                            echo "</tr>";
                        }
                        ?>
                    </tbody>
                </table>
                <p></p><div id="result"></div>
                <div id="footerMain1">
                    <div id="footerDesc">
                        <p>Copyright &copy; 2023 DENSO . All rights reserved</p>
                    </div>
                </div>
	</body>

</html>
                <div class="demo">
                    <div id="dialog-form" title="" style="display: none;">
                        <p class="validateTips"><?php echo get_lng($_SESSION["lng"], "E0051"); ?><!--All form fields are required.--></p>
                        <form>
                            <fieldset>
                                <label for="partno"><?php echo get_lng($_SESSION["lng"], "L0407"); ?><!--Part Number--></label><img id="buffer" src="images/ui-anim_basic_16x16.gif" style="display:none" />
                                <div class="ui-widget">
                                    <select id="partno">
                                        <?php
                                        require('supgetAutopartno.php');
                                        echo getpartno($vcusno,'Request',$shpCd);
                                        // echo getpartno($vcusno, $ordertype,$shpCd);
                                        ?>
                                    </select>
                                </div><p>
                                    <label for="qty"><?php echo get_lng($_SESSION["lng"], "L0070"); ?><!--Order Qty--></label>
                                    <input type="text" name="qty" id="qty" value="" class="text ui-widget-content ui-corner-all" />
                            </fieldset>
                        </form>
                        <p class="validateTips2"></p>
                        <div id="dialog-confirm" title="Delete Selected Record?" style="display: none;">
                            <p id="confirm" class="arial11blackbold"><span class="ui-icon ui-icon-alert" style="float:left; margin:0 7px 20px 0;"></span><?php echo get_lng($_SESSION["lng"], "G0001"); ?><!--Selected  items will be permanently deleted and cannot be recovered. Are you sure?--></p>
                        </div>
                        <div id="dialog-message" title="<?php echo get_lng($_SESSION["lng"], "L0295")/* Error Message! */; ?>" style="display: none;">
                            <p id="message" class="arial11blackbold"><span class="ui-icon ui-icon-alert" style="float:left; margin:0 7px 20px 0;"></span>Please fill required item before add Transaction detail!</p>
                        </div>
                    </div>
                </div>
                <div id="dialog-progress" style="word-break: break-all;">
                    <p id="notification"></p>
                </div>
                <div id="progress-bar"
                     style="position: fixed;
                     display: none;
                     width: 100%;
                     height: 100%;
                     top: 0;
                     left: 0;
                     right: 0;
                     bottom: 0;
                     background-color: rgba(0,0,0,0.5);
                     z-index: 2;
                     cursor: pointer;">
                    <div
                        style="position: absolute;
                        top: 50%;
                        left: 50%;
                        font-size: 50px;
                        color: white;
                        transform: translate(-50%,-50%);
                        -ms-transform: translate(-50%,-50%);">
                        <img src="../images/loading.gif" width="50" height="50" />
                    </div>
                </div>
            </div>
