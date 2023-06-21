<?php

session_start();
require_once('./../core/ctc_init.php'); // add by CTC

require_once('../language/Lang_Lib.php');

if (isset($_SESSION['cusno'])) {
    if ($_SESSION['redir'] == 'Order-SG') {
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
        $cusno =    $_SESSION['cusno'];
        $cusnm =    $_SESSION['cusnm'];
        $password = $_SESSION['password'];
        $alias = $_SESSION['alias'];
        $table = $_SESSION['tablename'];
        $type = $_SESSION['type'];
        $custype = $_SESSION['custype'];
        $user = $_SESSION['user'];
        $dealer = $_SESSION['dealer'];
        $group = $_SESSION['group'];
        $comp = ctc_get_session_comp();
        $erp = $_SESSION['erp'];
    } else {
        echo "<script> document.location.href='../" . redir . "'; </script>";
    }
} else {
    header("Location:../login.php");
}

include('chklogin.php');

$ordertype = '';

include "crypt.php";
$var = decode($_SERVER['REQUEST_URI']);
$xordno = trim($var['ordno']);
$vcusno = trim($var['cusno']);
$vcdate = trim($var['orddate']);
$vcorno = trim($var['corno']);
$vshpno = trim($var['shpno']);
$shpCd = trim($var['shpCd']); //12/20/2018 P.Pawan CTC
$cyear = trim($var['prdyear']);
$action = trim($var['action']);
$oecus = trim($var['oecus']);
$shipment = trim($var['shipment']);
$ordertype = trim($var['ordertype']);
$requestDate = $var['requestDate'] != null ? trim($var['requestDate']) : "";
$txtnote = trim($var['txtnote']); //09/11/2019 Zia Added (order note)


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
    <meta http-equiv="X-UA-Compatible" content="IE=9, IE=10, IE=11, IE=EDGE" />
    <!--02/04/2018 P.Pawan CTC-->
    <link rel="stylesheet" type="text/css" href="css/dnia.css">
    <!--	 <link rel="stylesheet" href="themes/ui-lightness/jquery-ui.css">-->

    <!--[if IE]>
<style type="text/css">
#twocolLeft{ padding-top: 0px; }
#twocolRight { zoom: 1; padding-top:10px; }
</style>
<![endif]-->
    <!--<script src="lib/jquery-1.4.2.js"></script>
 <link rel="stylesheet" href="themes/ui-lightness/jquery-ui-green.css">
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
    <script src="lib/jquery.ui.autocomplete.js"></script>


   <script type="text/javascript" src="lib/jquery.tipsy.js"></script>
    <link rel="stylesheet" href="css/tipsy.css" type="text/css" />
	<link rel="stylesheet" href="css/tipsy-docs.css" type="text/css" />
	<link rel="stylesheet" href="css/demos.css">   -->

    <link rel="stylesheet" href="css/ui/jquery.ui.base.css">
    <?php if ($ordertype == '' || $ordertype == 'Normal') { ?>
        <link rel="stylesheet" href="themes/ui-lightness/jquery-ui-green.css">
    <?php } else if ($ordertype == 'Urgent') { ?>
        <link rel="stylesheet" href="themes/ui-lightness/jquery-ui-red.css">
    <?php } else if ($ordertype == 'Request') { ?>
        <link rel="stylesheet" href="themes/ui-lightness/jquery-ui.css">
    <?php } ?>
    <script src="lib/ui/jquery-1.8.2.js"></script>
    <script src="lib/ui/jquery.ui.core.js"></script>
    <script src="lib/ui/jquery.ui.widget.js"></script>
    <script src="lib/ui/jquery.ui.button.js"></script>
    <script src="lib/ui/jquery.ui.position.js"></script>
    <script src="lib/ui/jquery.ui.menu.js"></script>
    <script src="lib/ui/jquery.ui.autocomplete.js"></script>
    <script src="lib/ui/jquery.ui.tooltip.js"></script>

    <script src="lib/ui/jquery.ui.resizable.js"></script>
    <script src="lib/ui/jquery.ui.dialog.js"></script>
    <!--<script src="lib/jquery.effects.core.js"></script>-->
    <link rel="stylesheet" href="css/ui/demos.css">


    <style>
        .disabled {
            pointer-events: none;
            opacity: 0.4;
        }

        body {
            font-size: 62.5%;
        }

        input.text {
            margin-bottom: 12px;
            width: 95%;
            padding: .4em;
        }

        input[type=radio] {
            display: inline;
            margin-top: 0px;
        }

        fieldset {
            padding: 0;
            border: 0;
            margin-top: 25px;
        }

        h1 {
            font-size: 1.2em;
            margin: .6em 0;
        }

        div#users-contain {
            width: 350px;
            margin: 20px 0;
        }

        div#users-contain table {
            margin: 1em 0;
            border-collapse: collapse;
            width: 100%;
        }

        div#users-contain table td,
        div#users-contain table th {
            border: 1px solid #eee;
            padding: .6em 10px;
            text-align: left;
        }

        .ui-dialog .ui-state-error {
            padding: .3em;
        }

        .validateTips {
            border: 1px solid transparent;
            padding: 0.3em;
        }

        button .btn {
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
            width: 380px;
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
        var btnAction = 'add';
		function grandtotal() {
            var sumqty = 0;
            var sumttl = 0;
            $('#myTable').find('td.qty').each(function() {
                sumqty += parseFloat($(this).text().replace(/,/g, ''));
            });
            $('#myTable').find('td.ttl').each(function() {
                sumttl += parseFloat($(this).text().replace(/,/g, ''));
            });
            $('input.amt-txt').val((sumqty));
            $('input.ttl-txt').val(number_format(sumttl));
        }
        function number_format(nStr) {
			nStr = parseFloat(nStr).toFixed(2);
			nStr += '';
			x = nStr.split('.');
			x1 = x[0];
			x2 = x.length > 1 ? '.' + x[1] : '.00';
			var rgx = /(\d+)(\d{3})/;
			while (rgx.test(x1)) {
				x1 = x1.replace(rgx, '$1' + ',' + '$2');
			}
			return x1 + x2;
		}
		
        $(function() {
			grandtotal();
            // 03/10/2019 Prachaya inphum CTC --start--
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
                        text: "OK",
                        click: function() {
                            $(this).dialog("close");
                        }
                    }
                ],
                close: function() {
                    $('#dialog-form').dialog("close");
                    $("#cmdSave").attr("disabled", true);
                    $("#cmdSave").addClass('disabled');
                    jQuery('#cmdClose').unbind('click');
                    jQuery('#cmdClose').click(function() {
                        window.location.href = "main.php";
                    });



                }
            });
            // 03/10/2019 Prachaya inphum CTC --end--
            $().ajaxStart(function() {
                $('#loading').show();
                $('#result').hide();
            }).ajaxStop(function() {
                $('#loading').hide();
                $('#result').fadeIn('slow');
            })

            $("#partno").combobox();
            $("#toggle").click(function() {
                $("#combobox").toggle();
            });
            /**$('.add').click(function(){
            	$('#myTable tr:last').clone(true).insertAfter('#myTable tr:last');
            });**/
            var vaction = "";
            //$( "#dialog:ui-dialog" ).dialog( "destroy" );
            var res = "";
            var partno = $(".ui-combobox-input"),
                qty = $("#qty"),
                allFields = $([]).add(partno).add(qty),
                tips = $(".validateTips");

            function updateTips(t) {
                tips
                    .text(t)
                    .addClass("ui-state-highlight");
                setTimeout(function() {
                    tips.removeClass("ui-state-highlight", 1500);
                }, 500);
            }

            function checkLength(o, n, min, max) {

                //alert('checkLength' + o);

                if (o.val().length > max || o.val().length < min) {
                    o.addClass("ui-state-error");
                    /*updateTips( "Lengthss of " + n + " must be between " +
                    	min + " and " + max + "." );*/
                    updateTips('<?php echo get_lng($_SESSION["lng"], "G0009"); ?>');
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


            /* check part number
            function checkPartno(o) {
            	var partno=o.val();
            	$.get('getpartno.php?partno='+partno, function(res){
            		alert(res);
            		if(res.substr(0,5)=='Error'){
            			o.addClass( "ui-state-error" );
            			updateTips(res );
            			return res.substr(0,5);
            		}else{
            		return res;

            		}
            	  });

            	}


            */

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
                    '<?php echo get_lng($_SESSION["lng"], "L0074");/*Check Part Info*/ ?>': function(event) {
                        var epartno = partno.val();
                        var edata;
                        var quantity = qty.val();

                        /** Zia autopart list description */
                        if (vaction == 'add') {
                            var sign = '>>'
                            var endpos = partno.val().indexOf(sign);
                            var epartno = partno.val().substring(0, endpos);
                        } else {
                            epartno = partno.val();
                        }
                        /** Zia autopart list description */

                        var ordertype = $('#ordstatus').val();
                        edata = "partno=" + epartno + "&qty=" + quantity + "&action=" + vaction + "&ordertype=" + ordertype;
                        $.ajax({
                            type: 'GET',
                            url: 'checkPartInfo.php',
                            data: edata,
                            success: function(data) {
                                $(".validateTips2").html(data)

                            }
                        });

                    },
                    '<?php echo get_lng($_SESSION["lng"], "L0075");/*Save Order Detail*/ ?>': function() {

                        /** Zia autopart list description */
                        //alert (vaction);
                        var oldPart = partno.val();
                        if (vaction == 'add') {
                            var sign = '>>';
                            var endpos = partno.val().indexOf(sign);
                            var epartno = partno.val().substring(0, endpos);
                            partno.val(epartno);
                        } else {
                            epartno = partno.val();
                        }
                        /** Zia autopart list description */

                        var bValid = true;
                        allFields.removeClass("ui-state-error");
                        bValid = bValid && checkLength(partno, "Part Number", 2, 27);
                        bValid = bValid && checkRegexp(partno, /([0-9a-z_-])+$/i, "Part number may consist of a-z,-, 0-9");
                        bValid = bValid && checkRegexp(qty, /^[1-9][0-9]*$/, '<?php echo get_lng($_SESSION["lng"], "W0010");/*"Qty should be greater than 0" */ ?>');


                        var edata;


                        var orderno = $('#orderno').val();
                        var corno = $('#corno').val();
                        var quantity = qty.val();
                        var shpno = $('#shpno').val();
                        var oecus = $('#oecus').val();
                        var shipment = $('#shipment').val();
                        var ordertype = $('#ordstatus').val();
                        var shpCd = $("#shpCd").val();
                        if (ordertype == 'Request') {
                            parts = $('#requestDate').val().split("-");
                            var passDueDate = parts[2] + parts[1] + parts[0];
                        }
                        //var shipment=$('input:radio[name=shipment]:checked').val();
                        //alert(oecus);
                        //alert(shipment);
                        edata = "partno=" + epartno + "&orderno=" + orderno + "&corno=" + corno + "&shpno=" + shpno + "&oecus=" + oecus + "&shipment=" + shipment + "&qty=" + quantity + "&action=" + vaction + "&ordertype=" + ordertype + "&passDueDate=" + passDueDate + "&shpCd=" + shpCd + "&chktf=" + $('#hid_chk_tf').val();

                        if (bValid) {

                            $.ajax({
                                type: 'GET',
                                url: 'getpartno_new.php',
                                data: edata,
                                success: function(data) {
                                    if (data.substr(0, 5) == 'Error' || data.substr(0, 7) == 'ผิดพลาด') {
                                        partno.addClass("ui-state-error");
                                        $(".validateTips").text(data).addClass("ui-state-highlight");
                                        partno.val(oldPart);
                                        return false;
                                    } else {
										if($('#hid_chk_tf').val() == '1'){
											var xdata = data.split("||");
											var itdsc = xdata[0];
											var curcd = xdata[5];
											var bprice = xdata[1];
											var discratio = xdata[2];
											var acsale = xdata[3];
											var ttlprice = xdata[6];
											var ttlex = xdata[8];
											var duedt = xdata[7];
											if (ordertype == 'Request') {
												parts = $('#requestDate').val().split("-");
												var duedt = parts[0] + "/" + parts[1] + "/" + parts[2];
											}
											if (vaction == 'add') {
												$('#myTable > tbody:last').append("<tr>" +
													"<td align=\"center\"><input  name=\"chkaction[]\" type=\"checkbox\" class=\"chkaction\" value=" + partno.val() + "></td>" +
													"<td>" + partno.val().toUpperCase() + " - " + itdsc + "</td>" +
													"<td class=\"price\" align=\"center\">" + bprice + "</td>" +
													"<td class=\"discount_price\" align=\"center\">" + discratio + "</td>" +
													"<td class=\"acsaleprice\" align=\"center\">" + acsale + "</td>" +
													"<td class=\"qty\" align=\"center\">" + qty.val() + "</td>" +
													"<td class=\"curcd\" align=\"center\">" + curcd + "</td>" +
													"<td class=\"ttl\" align=\"center\">" + ttlprice + "</td>" +
													"<td id=\"duedt\" align=\"center\" class=\"lasttd\">" + duedt + "</td>" +
													"</tr>");
											} else {

												$.each($('.chkaction:checked'), function() {
													$(this).closest('tr').children('td[class=qty]').text(quantity);
													//alert(bprice);
													$(this).closest('tr').children('td[class=price]').text(bprice);
													//alert(ttlprice);
													$(this).closest('tr').children('td[class=ttl]').text(ttlprice);
													$(this).closest('tr').children('td[class=ttlex]').text(ttlex);
													$(this).closest('tr').children('td[id=duedt]').text(duedt);
												});
												$('.chkaction:checked').attr('checked', false);
											}
										}else{
											var xdata = data.split("||");
											var itdsc = xdata[0];
											var curcd = xdata[1];
											var bprice = xdata[2];
											var ttlprice = xdata[3];
											var ttlex = xdata[4];
											var duedt = xdata[5];
											if (ordertype == 'Request') {
												parts = $('#requestDate').val().split("-");
												var duedt = parts[0] + "/" + parts[1] + "/" + parts[2];
											}
											if (vaction == 'add') {
												$('#myTable > tbody:last').append("<tr>" +
													"<td align=\"center\"><input name=\"chkaction[]\" type=\"checkbox\" class=\"chkaction\" value=" + partno.val() + "></td>" +
													"<td>" + partno.val().toUpperCase() + " - " + itdsc + "</td>" +
													"<td class=\"qty\" align=\"center\">" + qty.val() + "</td>" +
													"<td class=\"curcd\" align=\"center\">" + curcd + "</td>" +
													"<td class=\"price\" align=\"right\">" + bprice + "</td>" +
													"<td class=\"ttl\" align=\"right\">" + ttlprice + "</td>" +

													"<td id=\"duedt\" align=\"center\" class=\"lasttd\">" + duedt + "</td>" +
													"</tr>");
											} else {

												$.each($('.chkaction:checked'), function() {
													$(this).closest('tr').children('td[class=qty]').text(quantity);
													$(this).closest('tr').children('td[class=discount_price]').text(discount_price);
													//alert(bprice);
													$(this).closest('tr').children('td[class=price]').text(bprice);
													$(this).closest('tr').children('td[class=acsaleprice]').text(acsale);
													//alert(ttlprice);
													$(this).closest('tr').children('td[class=ttl]').text(ttlprice);
													$(this).closest('tr').children('td[class=ttlex]').text(ttlex);
													$(this).closest('tr').children('td[id=duedt]').text(duedt);
												});
												$('.chkaction:checked').attr('checked', false);
											}
										}
											$("#dialog-form").dialog("close");
											grandtotal();
                                    }
                                }
                            });



                        } else {
                            partno.val(oldPart);
                        }
                    },
                    '<?php echo get_lng($_SESSION["lng"], "L0076");/*Cancel*/ ?>': function() {

                        $(this).dialog("close");
                    }

                },
                close: function() {

                    allFields.val("").removeClass("ui-state-error");
                }
            });

            $("#dialog-progress").dialog({ //CTC P.Pawan Add dialog show result email sent -- start --
                autoOpen: false,
                width: 'auto',
                height: 'auto',
                modal: true,
                open: function(event, ui) {
                    $(".ui-dialog-titlebar-close", ui.dialog | ui).click(function() {
                        var url = "main.php";
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
                    '<?php echo get_lng($_SESSION["lng"], "L0317");/*OK*/ ?>': function() {
                        var url = "main.php";
                        $(location).attr('href', url);
                        $(this).dialog("close");
                    }
                },
                beforeClose: function(event, ui) {
                    // var url = "main.php";
                    // $(location).attr('href',url);
                }
            }); //CTC P.Pawan Add dialog show result email sent -- end --

            $("#progress-bar").click(function() {
                $(this).hide();
            });


            $("#add").click(function() {
                $(".ui-combobox-input").val("");
                $(".ui-combobox-input").removeAttr("disabled", 'disabled');
                //alert($(".ui-button").text());
                //alert($(".ui-button").text());
                $('a#prtlist').removeAttr("disabled", 'disabled');
                btnAction = 'add';

                //$("#prtlist").attr("disabled", true);
                $(".validateTips").text('').removeClass("ui-state-highlight");
                $(".validateTips2").text('').removeClass("ui-state-highlight");
                $(".ui-combobox-input").removeClass("ui-state-error");
                if ($('#ordstatus').val() == 'Normal') {
                    $("span.ui-dialog-title").text('<?php echo get_lng($_SESSION["lng"], "L0066");/*New Normal Order*/ ?>');
                } else if ($('#ordstatus').val() == 'Urgent') {
                    $("span.ui-dialog-title").text('<?php echo get_lng($_SESSION["lng"], "L0068");/*New Urgent Order*/ ?>');
                } else if ($('#ordstatus').val() == 'Request') {
                    $("span.ui-dialog-title").text('<?php echo get_lng($_SESSION["lng"], "L0067");/*New Requested Due Date Order*/ ?>');
                }
                $("#dialog-form").dialog("open");
                vaction = 'add';
            });


            $("#dlt").click(function() {

                mpart = $('input[name="chkaction[]"]:checked').map(function() {
                    return $(this).val();
                }).get().join(",");
                if (mpart !== '') {

                    //alert(mpart);

                    vaction = 'delete';
                    $("#dialog-confirm").dialog('open');

                } else {
                    $("p[id=message]").text('<?php echo get_lng($_SESSION["lng"], "G0003");/*"please at least select 1 document to delete!"*/; ?>');
                    $("#dialog-message").dialog("open");
                    //alert('please at least select 1 document to delete!');

                }

            });


            $("#chg").click(function() {
                var x = $('input:checked').length;
                if (x > 0) {
                    if (x > 1) {
                        alert('please choose only one row to edit');
                    } else {

                        $(".validateTips").text('').removeClass("ui-state-highlight");

                        var epart = $('.chkaction:checked').val();
                        //alert(epart);
                        $.each($('.chkaction:checked'), function() {
                            eqty = ($(this).closest('tr').children('td[class=qty]').text());

                        });

                        $('#partno').val(epart);
                        $(".ui-combobox-input").val(epart);
                        $(".ui-combobox-input").attr("disabled", true);
                        $("a#prtlist").attr("disabled", 'disabled');
                        btnAction = 'edit';
                        //$("a#prtlist").off("click");

                        $('#qty').val(eqty);
                        vaction = 'edit';
                        $("span.ui-dialog-title").text('<?php echo get_lng($_SESSION["lng"], "L0305")/*Edit Record*/; ?>');
                        $("#dialog-form").dialog("open");
                    }
                } else {
                    $("p[id=message]").text('<?php echo get_lng($_SESSION["lng"], "G0010"); ?>' /*'please at least select 1 document to edit!'*/ );
                    $("#dialog-message").dialog("open");
                }
            });

            $("#cmdSave").click(function() {
                document.getElementById("progress-bar").style.display = "block"; //CTC P.Pawan 04/03/19 show progress bar for wating response from ajax.
                var rowCount = $('#myTable >tbody >tr').length;
                // console.log(rowCount);
                if (rowCount == 1) {
                    $("p[id=message]").text('<?php echo get_lng($_SESSION["lng"], "G0004");/*"There are no Transaction to save, Please use close button!"*/; ?>');
                    $("#dialog-message").dialog("open");
                    return;
                }

                var edata;
                var shpno = $('#shpno').val();
                var orderno = $('#orderno').val();
                var corno = $('#corno').val();
                var action = $('#action').val();
                var ordertype = $('#ordstatus').val();
                var shpCd = $("#shpCd").val(); //12/20/2018 P.Pawan CTC
                var txtnote = $("#txtnote").val(); //12/20/2018 P.Pawan CTC
                edata = "orderno=" + orderno + "&corno=" + corno + "&shpno=" + shpno + "&action=" + action + "&ordertype=" + ordertype + "&shpCd=" + shpCd + "&txtnote=" + txtnote; //12/20/2018 P.Pawan CTC add parameter shpCd
                <?php //CTC Pawan.P 04/03/19  get email List and check duplicate email -- start --
                $notiTxt = '';
                $emailTo = [];
                $tmpEmail = [];

                $query = "select * from `shiptoma` st,`orderhdr` od where  trim(st.Cusno) ='" . $cusno . "' and st.ship_to_cd = '" . $shpCd . "' and st.Owner_Comp='$comp'";
                //$query .= "and od.cusno = st.Cusno and od.orderno = '".$xordno."'";

                $sql = mysqli_query($msqlcon, $query);

                while ($axData = mysqli_fetch_array($sql)) {
                    $compMail = $axData['comp_mail_add'];
                    $prsnMail1 = $axData['prsn_mail_add1'];
                    $prsnMail2 = $axData['prsn_mail_add2'];
                    $prsnMail3 = $axData['prsn_mail_add3'];
                }
                array_push($tmpEmail, $compMail, $prsnMail1, $prsnMail2, $prsnMail3);
                for ($index = 0; $index < count($tmpEmail); $index++) {
                    if (!in_array($tmpEmail[$index], $emailTo)) {
                        array_push($emailTo, $tmpEmail[$index]);
                        $notiTxt .= "<br>";
                        $notiTxt .= $tmpEmail[$index];
                    }
                }
                ?> //CTC Pawan.P 04/03/19  get email List and check duplicate email -- end --
                $.ajax({
                    type: 'GET',
                    url: 'saveorder_new.php',
                    data: edata,
                    success: function(data) {
                        //CTC P.Pawan 04/03/19 show message after email sent -- start --
                        $("#progress-bar").hide();
                        var div = $("#dialog-progress").prev();
                        if (data.indexOf("success") !== -1) {
                            //$("span#ui-id-3.ui-dialog-title").text('<?php echo get_lng($_SESSION["lng"], "L0314")/*Order successfully saved*/ ?>');
                            div.children("span.ui-dialog-title").text('<?php echo get_lng($_SESSION["lng"], "L0314")/*Order successfully saved*/ ?>');
                            $("p[id=notification]").html('');
                            $("p[id=notification]").append('<b><?php echo get_lng($_SESSION["lng"], "L0315");/*PO has been sent via email to : */ ?></b><br>');
                            $("p[id=notification]").append('<b"><?php echo $notiTxt ?></b>');
                        } else if (data.indexOf("failPDF") !== -1) {
                            //$("span#ui-id-3.ui-dialog-title").text('<?php echo get_lng($_SESSION["lng"], "L0316")/*Email send failed*/ ?>');
                            div.children("span.ui-dialog-title").text('<?php echo get_lng($_SESSION["lng"], "L0316")/*Email send failed*/ ?>');
                            $("p[id=notification]").html('');
                            $("p[id=notification]").append('<b><?php echo get_lng($_SESSION["lng"], "E0039");/*No PDF found. Please contact to DSTH or go to History Menu to request to send PO manually*/ ?></b><br>');
                        } else {
                            //$("span#ui-id-3.ui-dialog-title").text('<?php echo get_lng($_SESSION["lng"], "L0316")/*Email send failed*/ ?>');
                            div.children("span.ui-dialog-title").text('<?php echo get_lng($_SESSION["lng"], "L0316")/*Email send failed*/ ?>');
                            $("p[id=notification]").html('');
                            $("p[id=notification]").append('<b><?php echo get_lng($_SESSION["lng"], "E0037");/*No email address found. Please contact to DSTH or go to History Menu to request to send PO manually*/ ?></b><br>');
                        }
                        $("#dialog-progress").dialog("open");
                        //CTC P.Pawan 04/03/19 show message after email sent -- end --
                        // $('#result').html(data);
                    }
                });
            });


            $("#cmdClose").click(function() {
                var answer = confirm("<?php echo get_lng($_SESSION["lng"], "G0002"); ?>");
                if (answer) {

                    var edata;
                    var shpno = $('#shpno').val();
                    var orderno = $('#orderno').val();
                    var corno = $('#corno').val();
                    var ordertype = $('#ordstatus').val();
                    edata = "shpno=" + shpno + "&orderno=" + orderno + "&corno=" + corno + "&action=close&ordertype=" + ordertype;
                    //alert(edata);
                    $.ajax({
                        type: 'GET',
                        url: 'saveorder_new.php',
                        data: edata,
                        success: function(data) {
                            //alert(data);
                            $('#result').html(data);
                        }
                    });

                }

            });






            // dialog confirm
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
                    "<?php echo get_lng($_SESSION["lng"], "L0077")/*Delete selected*/; ?>": function() {
                        $.each($('.chkaction:checked'), function() {
                            $(this).parent().parent().remove();

                        });
                        //==================================================

                        var edata;
                        var shpno = $('#shpno').val();
                        var orderno = $('#orderno').val();
                        edata = "shpno=" + shpno + "&orderno=" + orderno + "&partno=" + mpart;
                        //alert(edata);
                        $.ajax({
                            type: 'GET',
                            url: 'delpartno.php',
                            data: edata,
                            success: function(data) {
                                //alert(data);
                            }
                        });

                        //-------------------------------------------------------



                        $(this).dialog("close");
						grandtotal();
                    },
                    <?php echo get_lng($_SESSION["lng"], "L0076")/*Cancel*/; ?>: function() {
                        $(this).dialog("close");
                    }
                }
            });
            //end dialog confirm

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
                    Ok: function() {
                        //$("#dialog-message"�).dialog( "close" );
                        $(this).dialog("close");
                    }
                }
            });

            //var ok=loadPartno(partno.val());
            //create config object

            /*var shpno=$('#shpno').val();
            //alert(shpno);
            	$("#partno").autocomplete({

            			source: "getAutopartno.php?shpno="+shpno,
            			minLength: 1

            	 });*/
            //	turn specified element into an auto-complete

            /*function loadPartno(prtno){
		//	alert(prtno);
			//if(prtno.length!=0){
				var shpno=$('#shpno').val();
				//alert(shpno);
				var edata="q=&shpno="+shpno;
				//alert(partno.val().length);
				$.ajax({
					type: 'GET',
					url: 'getAutopartno.php?shpno='+shpno,
					data: edata,
					success: function(data) {
						$("#partno").html(data);
						//alert(data);
						}
					});
					$("#partno").autocomplete({

			source: "getAutopartno.php?shpno="+shpno,
			minLength: 1

	 });
	}*/

            // Tool Tips
            //$(".btn img[title]").tipsy({gravity: 'e'});


        });
        (function($) {
            $.widget("ui.combobox", {
                _create: function() {
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
                        select.children("option").each(function() {
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
                            setTimeout(function() {
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
                            source: function(request, response) {
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
                            select: function(event, ui) {

                                ui.item.option.selected = true;
                                that._trigger("selected", event, {
                                    item: ui.item.option
                                });
                            },
                            change: function(event, ui) {
                                if (!ui.item)
                                    return removeIfInvalid(this);
                            },
                            focus: function(event, ui) {
                                if (ui.item.value == "maxRepSizeReached") {
                                    return false;
                                }
                            },
                            search: function(event, ui) {
                                $("#buffer").show("slow");
                            }
                        })
                        .addClass("ui-widget ui-widget-content ui-corner-left");

                    input.data("autocomplete")._renderItem = function(ul, item) {
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
                        .click(function() {
                            if (btnAction == 'add') {
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
                            } else {
                                input.autocomplete("close");
                                return;
                            }
                        });
                    input
                        .tooltip({
                            position: {
                                my: "center",
                                at: "center",
                                of: $("body"),
                                within: $("body")
                            },
                            tooltipClass: "ui-state-highlight"
                        });
                },

                destroy: function() {
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

    require_once('../core/ctc_cookie.php');

    if ($shipment == 'A') {
        $vshipment = 'by Air';
    } else {
        $vshipment = 'by Sea';
    }

    $inpoecus = "<input name=\"oecus\" type=\"hidden\"  id=\"oecus\" class=\"arial11blackbold\"  maxlength=\"20\" size=\"20\" readonly=\"true\" value='$oecus'>";
    if (strtoupper($oecus) == 'Y') {

        $inpshipment = "<input name=\"shipment\" type=\"hidden\"  id=\"shipment\" class=\"arial11blackbold\"   maxlength=\"20\" size=\"20\" readonly=\"true\" value='$shipment'>";
        $inpshipmod = '<tr class="arial11blackbold"> <td>&nbsp;</td>  <td>&nbsp;</td> <td  class="arial11blackbold">&nbsp;</td>';
        $inpshipmod = $inpshipmod . ' <td></td> <td>&nbsp;</td> <td>&nbsp;</td> <td>&nbsp;</td> </tr><tr class="arial11blackbold">  <td>Shipment Mode</td>';
        $inpshipmod = $inpshipmod . ' <td>:</td> <td colspan="5"  >' . $vshipment . $inpshipment . '</td>    </tr>';
    } else {

        $inpshipment = "<input name=\"shipment\" type=\"hidden\"  id=\"shipment\" class=\"arial11blackbold\"  maxlength=\"20\" size=\"20\" readonly=\"true\" value='S'>";
        $inpshipmod = $inpshipment;
    }




    echo "<input type=\"hidden\" name=\"action\" id=\"action\" value=" . $action . ">";
    if ($cusno == $vshpno) {
    }
    $query = "select * from cusrem where cusno = '" . $vshpno . "' and Owner_Comp='$comp' ";

    $sql = mysqli_query($msqlcon, $query);
    $hasil = mysqli_fetch_array($sql);
    if ($hasil) {
        $vremark = $hasil['remark'];
        $vcurcd = $hasil['curcd'];
        $alamat = $vremark . '  (' . $vcurcd . ')';
    }
    $inputshpno = "<input type=\"hidden\" name=\"shpno\" type=\"text\"  id=\"shpno\" class=\"arial11blackbold\"  value=" . $vshpno . ">";

    $txtcorno = "<input name=\"corno\" type=\"text\"  id=\"corno\" class=\"arial11blackbold\"  maxlength=\"20\" size=\"20\" readonly=\"true\" value='" . $vcorno . "'>";

    $inputordno = "<input name=\"orderno\" type=\"text\"  id=\"orderno\" class=\"arial11blackbold\" readonly=\"true\" value=" . $xordno . ">";


    ?>

    <?php ctc_get_logo() ?>
    <!-- add by CTC -->

    <div id="mainNav">
        <ul>
            <li id="current"><a href="#" onClick="alert('<?php echo get_lng($_SESSION["lng"], "G0012");/*'Please use Close button to move from transaction menu! '*/ ?>')"><?php echo get_lng($_SESSION["lng"], "L0046"); ?>
                    <!--Ordering</a>-->
            </li>
            <li><a href="#" onClick="alert('<?php echo get_lng($_SESSION["lng"], "G0012");/*'Please use Close button to move from transaction menu! '*/ ?>')"><?php echo get_lng($_SESSION["lng"], "L0047"); ?>
                    <!--User Profile-->
                </a></li>
            <li><a href="#" onClick="alert('<?php echo get_lng($_SESSION["lng"], "G0012");/*'Please use Close button to move from transaction menu! '*/ ?>')"><?php echo get_lng($_SESSION["lng"], "L0048"); ?>
                    <!--Table Part-->
                </a></li>
            <li><a href="#" onClick="alert('<?php echo get_lng($_SESSION["lng"], "G0012");/*'Please use Close button to move from transaction menu! '*/ ?>')"><?php echo get_lng($_SESSION["lng"], "L0049"); ?>
                    <!--Log out-->
                </a></li>

        </ul>


    </div>
    <div id="isi">
        <div id="twocolRight1">



            <table width="97%" border="0" cellspacing="0" cellpadding="0">

                <tr class="arial11blackbold">
                <tr align="center">
                    <td colspan="7" class="arial11blackbold">
                        <?php if ($ordertype == 'Urgent') {
                            require('countdown.php');
                        } ?>
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
                    <td width="22%"><?php echo get_lng($_SESSION["lng"], "L0050"); ?>
                        <!--Customer Number-->
                    </td>
                    <td width="2%">:</td>
                    <td width="26%"><? echo $cusno ?></td>
                    <td width="4%"></td>
                    <td width="20%"><?php echo get_lng($_SESSION["lng"], "L0055"); ?>
                        <!--Customer Name-->
                    </td>
                    <td width="2%">:</td>
                    <td width="25%"><? echo $cusnm ?></td>
                </tr>
                <tr class="arial11blackbold">
                    <td><? echo $inputshpno ?></td>
                    <td></td>
                    <td>&nbsp;</td>
                    <td></td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                </tr>
                <tr class="arial11blackbold">
                    <td><?php echo get_lng($_SESSION["lng"], "L0051"); ?>
                        <!--Ship To-->
                    </td>
                    <td>:</td>
                    <td colspan="5">
                        <table width="97%" border="0" cellspacing="0" cellpadding="0">
                            <tr>
                                <td width="5%" class="arial11blackbold"><? echo  $shpCd ?><input type="hidden" id="shpCd" value="<? echo $shpCd ?>" />
                                    <!--//12/20/2018 P.Pawan CTC-->
                                </td>

                                <!-- <td width="95%"> <? echo  $alamat ?></td> -->
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
                    <td style="vertical-align:top;"><?php echo get_lng($_SESSION["lng"], "L0311"); ?>
                        <!--Ship To Address //12/20/2018 P.Pawan CTC-->
                    </td>
                    <td style="vertical-align:top;">:</td>
                    <td colspan="5">
                        <table width="97%" border="0" cellspacing="0" cellpadding="0">
                            <tr>
                                <? //12/20/2018 P.Pawan CTC
                                require('../language/conn.inc');
                                $query = "select ship_to_nm,adrs1,adrs2,adrs3,pstl_cd,comp_tel_no from `shiptoma` where trim(ship_to_cd) =trim('" . $shpCd . "') and trim(Cusno) = trim('" . $cusno . "') and Owner_Comp='$comp'";
                                $sqlResult = mysqli_query($msqlcon, $query);
                                while ($axQuery = mysqli_fetch_array($sqlResult)) {

                                    $ship_to_nm = $axQuery['ship_to_nm'];
                                    $adrs1 = $axQuery['adrs1'];
                                    $adrs2 = $axQuery['adrs2'];
                                    $adrs3 = $axQuery['adrs3'];
                                    $pstl_cd = $axQuery['pstl_cd'];
                                    $comp_tel_no = $axQuery['comp_tel_no'];
                                    echo "<td width=\"5%\" class=\"arial11blackbold\" id=\"shipToAddress\">" . $ship_to_nm . "<br>" . $adrs1 . "<br>" . $adrs2 . "<br>" . $adrs3 . " ," . $pstl_cd . "<br>" . $comp_tel_no . "</td>";
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
                    <td><?php echo get_lng($_SESSION["lng"], "L0052"); ?>
                        <!--Order Date-->
                    </td>
                    <td>:</td>
                    <td><? echo date("d-m-Y") ?></td>
                    <td></td>
                    <td><?php echo get_lng($_SESSION["lng"], "L0056"); ?>
                        <!--Denso Order Number-->
                    </td>
                    <td>:</td>
                    <td class="arial11blackbold"><? echo $inputordno ?><? echo $inpoecus; ?></td>
                </tr>

                <tr class="arial11blackbold">
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td colspan="5">
                        <div id="errthn" class="arial11redbold"></div>
                    </td>
                </tr>
                <tr class="arial11blackbold">
                    <td><?php echo get_lng($_SESSION["lng"], "L0053"); ?>
                        <!--PO Number-->
                    </td>
                    <td>:</td>
                    <td class="arial11blackbold" id="testtest">
                        <? echo $txtcorno ?>
                    </td>
                    <td></td>
                    <?php if ($ordertype == 'Request') { ?>
                        <td>Request Due Date</td>
                        <td>:</td>
                        <td><input type="text" id="requestDate" value="<?php echo $requestDate ?>" readonly class="arial11blackbold"></td>
                    <?php } else { ?>
                        <td colspan="3">&nbsp;</td>
                    <?php } ?>
                </tr>
                <tr class="arial11blackbold">
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td class="arial11blackbold">&nbsp;</td>
                    <td></td>
                    <td colspan="3">&nbsp;</td>
                </tr>
                <tr class="arial11blackbold">
                    <td><?php echo get_lng($_SESSION["lng"], "L0054"); ?>
                        <!--Order Type-->
                    </td>
                    <td>:</td>
                    <td><input type="text" id="ordstatus" value="<?php echo $ordertype ?>" readonly class="arial11blackbold"></td>
                    <td></td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                </tr>
                <!--Note //09/11/2019 Zia Added Start-->
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
                    <td style="vertical-align:top;"><?php echo get_lng($_SESSION["lng"], "L0334"); ?>
                        <!--Note -->
                    </td>
                    <td style="vertical-align:top;">:</td>
                    <td colspan="5"><? echo  $txtnote ?><input type="hidden" id="txtnote" value="<? echo $txtnote ?>" /></td>
                </tr>
                <!--Note //09/11/2019 Zia Added E -->
                <? echo $inpshipmod;
                ?>
            </table>

            <p>&nbsp;</p>
            <table width="97%" border="0" cellspacing="0" cellpadding="0">
                <tr align="right">
                    <td width="10%" align="left"><input type="button" value="<?php echo get_lng($_SESSION["lng"], "L0057");/*Save order Entry*/ ?>" id="cmdSave" class="arial11blackbold"></input></td>
                    <td width="10%" align="left"><input type="button" class="arial11blackbold" id="cmdClose" value="<?php echo get_lng($_SESSION["lng"], "L0058");/*Close order Entry*/ ?>"></input></td>
                    <td>
                        <div style="width: 463px;background-color: white;border-radius: 5px;height: 44px;">
                            <div style="float:left; position:relative; width:20%; height: 40px;">
                                <div class="vertical-center" style="right:10px;font-size: 12px;font-weight: 700;"><?php echo get_lng($_SESSION["lng"], "L0567");/*Grand Total*/ ?></div>
                            </div>
                            <div style="width:80%;">
                                <div style="float:left; text-align:center;">
                                    <div style="font-size: 12px;font-weight: 600;color: #ad1d36;"><?php echo get_lng($_SESSION["lng"], "L0568");/*QTY*/ ?></div>
                                    <div><input style="text-align:right;border-radius: 5px;" type="text" class="amt-txt" readonly /></div>
                                </div>
                                <div style="text-align:center;">
                                    <div style="font-size: 12px;font-weight: 600;color: #ad1d36;"><?php echo get_lng($_SESSION["lng"], "L0569");/*Amount*/ ?></div>
                                    <div><input style="text-align:right;border-radius: 5px;" type="text" class="ttl-txt" readonly /></div>
                                </div>
                            </div>
                        </div>
                    </td>
                    <td width="12%" align="right"><button class="btn" id="add"><span class="arial11blackbold"><img src="images/add.png" title="add new record" width="18" height="18"></span></button>

                        <button class="btn" id="chg"><span class="arial11blackbold"><img src="images/edit.png" title="Change record" width="18" height="18"></span></button>
                        <button class="btn" id="dlt"><span class="arial11blackbold"> <img src="images/delete.png" title="Delete record" width="18" height="18"></span></button>
                    </td>
                </tr>
            </table>

            <table width="97%" border="0" cellspacing="0" cellpadding="0">
                <tr class="arial11redbold" align="center">
                    <td width="7%" height="10"></td>
                    <td width="37%"></td>
                    <td width="46%"></td>
                    <td width="10%"></td>
                </tr>
            </table>
            <table class="tblorder" width="97%" border="0" cellpadding="0" cellspacing="0" id="myTable">
                <tbody>

                    <?
                    require('db/conn.inc');
                    // Select 1 from table_name will return false if the table does not exist.
                    $val = mysqli_query($msqlcon, "select 1 from tf_snd_web_item_ma_$comp LIMIT 1");
                    $chk_tf = $val !== FALSE && $erp == 1 ? TRUE : FALSE; // TRUE is erp = 1 and table tf_snd_web_item_ma_XX0 is exists
					?>
						<input type='hidden' id='hid_chk_tf' value='<?= $chk_tf ?>'>
					<?
                    if ($chk_tf) {
                    ?>
                        <tr align="center" valign="middle" bgcolor="#990033" class="arial11whitebold">
                            <th width="3%" height="30"><?php echo get_lng($_SESSION["lng"], "L0059"); ?>
                                <!--Sel-->
                            </th>
                            <th width="18%"><?php echo get_lng($_SESSION["lng"], "L0060"); ?>
                                <!--Part Number-->
                            </th>
                            <th width="12%"><?php echo  get_lng($_SESSION["lng"], "L0561"); ?></th>
                            <th width="12%"><?php echo get_lng($_SESSION["lng"], "L0562")." (%) "; ?>
                                <!--Discount Ratio-->
                            </th>
                            <th width="12%"><?php echo get_lng($_SESSION["lng"], "L0563"); ?>
                                <!--Actual Sales Price-->
                            </th>
                            <th width="5%"><?php echo get_lng($_SESSION["lng"], "L0061"); ?>
                                <!--Qty-->
                            </th>
                            <th width="8%"><?php echo get_lng($_SESSION["lng"], "L0062"); ?>
                                <!--Curr-->
                            </th>
                            <th width="15%"><?php echo get_lng($_SESSION["lng"], "L0064"); //<!--Amount-->
                                            ?></th>
                            <th width="15%" class="lastth"><?php echo get_lng($_SESSION["lng"], "L0065"); ?>
                                <!--Due Date<br>(DD-MM-YYY)-->
                            </th>
                        </tr>
                    <?php
                    } else {
                        // print_r('NO its notexists');
                    ?>
                        <tr align="center" valign="middle" bgcolor="#990033" class="arial11whitebold">
                            <th width="3%" height="30"><?php echo get_lng($_SESSION["lng"], "L0059"); ?></th>
                            <!--Sel-->
                            <th width="25%"><?php echo get_lng($_SESSION["lng"], "L0060"); ?></th>
                            <!--Part Number-->
                            <th width="15%"><?php echo get_lng($_SESSION["lng"], "L0061"); ?></th>
                            <!--Qty-->
                            <th width="8%"><?php echo get_lng($_SESSION["lng"], "L0062"); ?></th>
                            <!--Curr-->
                            <th width="13%"><?php echo get_lng($_SESSION["lng"], "L0063"); ?></th>
                            <!--Price-->
                            <th width="15%"><?php echo get_lng($_SESSION["lng"], "L0064"); ?></th>
                            <!--Amount-->
                            <th width="15%" class="lastth"><?php echo get_lng($_SESSION["lng"], "L0065"); ?></th>
                            <!--Due Date<br>(DD-MM-YYY)-->
                        </tr>

                    <?php
                    }
                    if ($chk_tf) {
                        $table1 = str_replace("ordtmp", 'regimp', $table);
                        $query = "select a.* from  $table a where trim(a.cusno) ='" . $vshpno . "' and trim(a.orderno)='" . $xordno . "' and Owner_Comp='$comp' order by a.partno";
                        $query = "SELECT a.*, b.SLS_PRCE, b.SLS_AMNT, b.BS_PRCE, b.DSCNT_RATIO  FROM $table a LEFT JOIN tf_snd_web_item_ma_tk0 b on a.Owner_Comp = b.OWNER_COMP and a.partno = b.CST_ORDR_ITEM_NO_DSP AND a.cusno = b.CST_CD WHERE TRIM(a.cusno) = '$vshpno' AND TRIM(a.orderno) = '$xordno' AND a.Owner_Comp = '$comp' AND b.SHP_TO_CD LIKE '$shpCd' ORDER BY a.partno ";
                        $sql = mysqli_query($msqlcon, $query);
                        while ($hasil = mysqli_fetch_array($sql)) {
                            $partno = $hasil['partno'];
                            $partdes = $hasil['partdes'];
                            $curcd = $hasil['CurCD'];
                            $qty = $hasil['qty'];
                            $disc = $hasil['disc'];
                            $disc_ratio = $hasil['DSCNT_RATIO'];
                            $bprice = $hasil['bprice'];
                            $lbprice = $hasil['bprice'];
                            $slsprice = $hasil['slsprice'];
                            $ac_slsprice = $hasil['SLS_AMNT'];
                            $duedt = substr($hasil['DueDate'], -2) . "/" . substr($hasil['DueDate'], 4, 2) . "/" . substr($hasil['DueDate'], 0, 4);
                            $disco = number_format(($lbprice * $disc_ratio) / 100, 0, ".", ",");
                            $ttl = number_format($ac_slsprice * $qty, 2, ".", ",");
                            $vbprice = number_format($bprice, 2, ".", ",");
                            $exrate = $hasil['SGPrice'];

                            //$exttl=$slsprice*$qty*exrate;
                            $ttlex = number_format($slsprice * $qty * $exrate, 2, ".", ",");
                            echo "<tr>";
                            echo "<td  align=\"center\"> <input name=\"chkaction[]\" type=\"checkbox\" class=\"chkaction\" value='" . $partno . "'></td>";
                            echo "<td>" . $partno . " - " . $partdes . "</td>";
                            echo "<td class=\"price\" align=\"center\">" . number_format($lbprice, 2, ".", ",") . "</td>";
                            echo "<td class=\"disc\" align=\"center\">" . number_format($disc_ratio, 0, ".", ",") . " % "."</td>";
                            echo "<td class=\"acsaleprice\" align=\"center\">" . number_format($ac_slsprice, 2, ".", ",") . "</td>";
                            echo "<td class=\"qty\" align=\"center\">" . $qty . "</td>";
                            echo "<td class=\"curcd\" align=\"center\">" . $curcd . "</td>";
                            // echo "<td class=\"price\" align=\"right\">" . $vbprice . "</td>" ;
                            echo "<td class=\"ttl\" align=\"center\">" . $ttl . "</td>";
                            echo "<td id=\"duedt\" class=\"lasttd\" align=\"center\">" . $duedt . "</td>";
                            echo "</tr>";
                        }
                    } else {
                        $table1 = str_replace("ordtmp", 'regimp', $table);
                        $query = "select a.* from  $table a where trim(a.cusno) ='" . $vshpno . "' and trim(a.orderno)='" . $xordno . "' and Owner_Comp='$comp' order by a.partno";
                        $sql = mysqli_query($msqlcon, $query);
                        while ($hasil = mysqli_fetch_array($sql)) {
                            $partno = $hasil['partno'];
                            $partdes = $hasil['partdes'];
                            $curcd = $hasil['CurCD'];
                            $qty = $hasil['qty'];
                            $disc = $hasil['disc'];
                            $bprice = $hasil['bprice'];
                            $slsprice = $hasil['slsprice'];
                            $duedt = substr($hasil['DueDate'], -2) . "/" . substr($hasil['DueDate'], 4, 2) . "/" . substr($hasil['DueDate'], 0, 4);
                            $disco = number_format(($bprice * $disc) / 100, 0, ".", ",");
                            $ttl = number_format($slsprice * $qty, 2, ".", ",");
                            $vbprice = number_format($bprice, 2, ".", ",");
                            $exrate = $hasil['SGPrice'];

                            //$exttl=$slsprice*$qty*exrate;
                            $ttlex = number_format($slsprice * $qty * $exrate, 2, ".", ",");
                            echo "<tr>";
                            echo "<td align=\"center\"><input  name=\"chkaction[]\" type=\"checkbox\" class=\"chkaction\" value='" . $partno . "'></td>";
                            echo "<td>" . $partno . " - " . $partdes . "</td>";
                            echo "<td class=\"qty\" align=\"center\">" . $qty . "</td>";
                            echo "<td class=\"curcd\" align=\"center\">" . $curcd . "</td>";
                            echo "<td class=\"price\" align=\"right\">" . $vbprice . "</td>";
                            echo "<td class=\"ttl\" align=\"right\">" . $ttl . "</td>";
                            echo "<td id=\"duedt\" class=\"lasttd\" align=\"center\">" . $duedt . "</td>";
                            echo "</tr>";
                        }
                    }



                    ?>
                </tbody>
            </table>
            <p>
            <div id="result"></div>
            </p>





            <div id="footerMain1">
                <ul>
                    <!-- Disabled by Zia
     
          
      -->
                </ul>

                <div id="footerDesc">

                    <p>
                        Copyright &copy; 2023 DENSO . All rights reserved

                </div>
            </div>

</body>

</html>

<div class="demo">

    <div id="dialog-form" title="" style="display: none;">
        <p class="validateTips"><?php echo get_lng($_SESSION["lng"], "L0036"); ?>
            <!--All form fields are required.-->
        </p>


        <form>
            <fieldset>
                <label for="partno"><?php echo get_lng($_SESSION["lng"], "L0069"); ?>
                    <!--Part Number-->
                </label><img id="buffer" src="images/ui-anim_basic_16x16.gif" style="display:none"></img>
                <div class="ui-widget">
                    <select id="partno">
                        <?php
                        require('getAutopartno.php');
                        echo getpartno($vshpno, $ordertype, $shpCd);
                        ?>
                    </select>
                </div>
                <p>
                    <label for="qty"><?php echo get_lng($_SESSION["lng"], "L0070"); ?>
                        <!--Order Qty-->
                    </label>
                    <input type="text" name="qty" id="qty" value="" class="text ui-widget-content ui-corner-all" />



            </fieldset>
        </form>
        <p class="validateTips2"></p>

        <div id="dialog-confirm" title="Delete Selected Record?" style="display: none;">
            <p id="confirm" class="arial11blackbold"><span class="ui-icon ui-icon-alert" style="float:left; margin:0 7px 20px 0;"></span><?php echo get_lng($_SESSION["lng"], "G0001"); ?>
                <!--Selected  items will be permanently deleted and cannot be recovered. Are you sure?-->
            </p>
        </div>

        <div id="dialog-message" title="<?php echo get_lng($_SESSION["lng"], "L0295")/*Error Message!ss*/; ?>" style="display: none;">
            <p id="message" class="arial11blackbold"><span class="ui-icon ui-icon-alert" style="float:left; margin:0 7px 20px 0;"></span>Please fill required item before add Transaction detail!</p>
        </div>
    </div>
</div>
<!-- CTC P.Pawan 04/03/19 Add progress bar for response email sent and dialog message for show email sent -->
<div id="dialog-progress" style="word-break: break-all;">
    <p id="notification"></p>
</div>
<div id="progress-bar" style="position: fixed;
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
    <div style="position: absolute;
  top: 50%;
  left: 50%;
  font-size: 50px;
  color: white;
  transform: translate(-50%,-50%);
  -ms-transform: translate(-50%,-50%);">
        <img src="images/loading.gif" width="50" height="50" />
    </div>
</div>
<?php include('timecheck.php'); ?>
<!-- 03/10/2019 Prachaya inphum CTC-->