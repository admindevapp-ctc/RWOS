<?php 
session_start();
require_once('../language/Lang_Lib.php');
//if (session_is_registered('cusno'))
require_once('./../core/ctc_init.php'); // add by CTC

$comp = ctc_get_session_comp(); // add by CTC

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
		$imptable=$_SESSION['imptable'];
	 }else{
		   echo "<script> document.location.href='../".redir."'; </script>";
	 }
}else{	
header("Location:../login.php");
}
include('chklogin.php');


// $arrdata=explode('|', $_POST['txtShpNo']);
// $shpno=$arrdata[0];
// $oecus=$arrdata[1];
$fcusno=$_GET['cusno'];
$action=$_GET['action'];
$ordertype=$_GET['ordertype'];
$orderDate=$_GET['orddate']!=null?$_GET['orddate']:date('d');
$txtnote=$_GET['txtnote'];
$ordno=$_GET['ordno'];
$oecus=$_GET['oecus'];
$shipto=$_GET['shipto'];
$corno=$_GET['corno'];
//echo $shpno."<br/>";
require("crypt.php");
if($ordertype=='Normal'){
	$_GET['selection']="main";
}
else if($ordertype=='Urgent'){
	$_GET['selection']="urgentOrder";
}
else if($ordertype=='Request'){
	$_GET['selection']="requestDueDate";
}
?>
<link rel="stylesheet" type="text/css" href="css/dnia.css">
<link rel="stylesheet" href="themes/ui-lightness/jquery-ui-red.css">
<script type="text/javascript" language="javascript" src="lib/jquery-1.4.2.js"></script>
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

    

</style>
<html>
<head>
        <title>Denso Ordering System</title>
        <meta http-equiv="X-UA-Compatible" content="IE=9, IE=10, IE=11, IE=EDGE" /> 

        <link rel="stylesheet" type="text/css" href="css/dnia.css">
        <link rel="stylesheet" type="text/css" href="admin/bootstrap3.3.7/css/bootstrap.min.css">
        <link rel="stylesheet" type="text/css" href="admin/bootstrap-datepicker/bootstrap-datepicker3.standalone.min.css">
        <link rel="stylesheet" type="text/css" href="css/custom-bootstrap.css">
        <link rel="stylesheet" type="text/css" href="css/demos.css">
        <link rel="stylesheet" type="text/css" href="admin/Carousel/carousel.css">
        <link rel="stylesheet" type="text/css" href="admin/DataTables/dataTables.bootstrap.min.css">
        <link rel="stylesheet" type="text/css" href="admin/bootstrap-touchspin/jquery.bootstrap-touchspin.min.css">
        <link rel="stylesheet" type="text/css" href="admin/lightbox2-2.11.3/css/lightbox.min.css">
        <link rel="stylesheet" type="text/css" href="admin/sweetalert/custom-sweetalert-ie9.css">
        <link rel="stylesheet" type="text/css" href="admin/sweetalert/custom-sweetalert.css">
        <style>
            th.custom-nowrap-table {
                white-space: nowrap;
            }

            .hmenu {
                padding-top: 20px;
                background-image: url(../images/HBG.png);
            }
            .headerbar {
                margin-top: 0px;
            }

            .alert {
                padding: 10px;
            }

            .datepicker table tr td.disabled, .datepicker table tr td.disabled:hover {
                background: #c1c1c1;
            }

            .arial11redbold {
                color: #171616;
            }

            .arial11redbold > strong {
                color: #B30000;
            }

        </style>

        <script type="text/javascript" src="lib/jquery-3.5.1.min.js"></script>
        <script type="text/javascript" src="admin/DataTables/datatables.min.js"></script>
        <script type="text/javascript" src="admin/DataTables/dataTables.bootstrap.min.js"></script>
        <script type="text/javascript" src="admin/DataTables/dataTables.buttons.min.js"></script>
        <script type="text/javascript" src="admin/bootstrap3.3.7/js/bootstrap.min.js"></script>
        <script type="text/javascript" src="admin/bootstrap-datepicker/bootstrap-datepicker.min.js"></script>
        <script type="text/javascript" src="admin/sweetalert/sweetalert.min.js"></script>
        <script type="text/javascript" src="admin/sweetalert/classList.min.js"></script>
        <script type="text/javascript" src="admin/promise-polyfill/promise-polyfill.js"></script>
        <script type="text/javascript" src="admin/lightbox2-2.11.3/js/lightbox.min.js"></script>
        <script type="text/javascript" src="admin/Carousel/carousel.js"></script>
        <script type="text/javascript" src="admin/bootstrap-touchspin/jquery.bootstrap-touchspin.min.js"></script>
        <script>
            
            $(document).ready(function () {
                LoadTable();
                getShipToAddressAjax('<?php echo $shipto; ?>');
                
                function LoadTable(){
                	$.ajax({
                        type: 'POST',
                        url: 'sup_load_order_import.php',
                        dataType: 'json',
                        data: function (data) {
                            data.cusno = '<?php echo $fcusno; ?>'
                        },
                        success: function (result) {
                           //console.log("test" + result);
                            var htmlval = "";
                            var dataarray = [];

                            var supcheck = "";
                            var bodytable = "";
                            var header = " <div class='row'>"
                                + "  <div class='col-md-12'>"
                                + "      <table id='tablecatalog'  class='table table-bordered table-hover table-striped display' style='overflow-x: auto; width: 100%;' cellspacing='0'>"
                                + "          <thead>"
                                + "              <tr class='bg-maroon'>"
                                + "                  <th class='text-center' style='width: 10%;'><?php echo get_lng($_SESSION["lng"], "L0442");/*Supplier No*/   ?></th>"
                                + "                  <th class='text-center' style='width: 10%;'><?php echo get_lng($_SESSION["lng"], "L0452"); /*Supplier Name*/   ?></th>"
                                + "                  <th class='text-center' style='width: 10%;'><?php echo get_lng($_SESSION["lng"], "L0378"); /* Part Number */  ?></th>"
                                //+ "                  <th class='text-center' style='width: 10%;'><?php echo get_lng($_SESSION["lng"], "L0437"); /* Model Code */  ?></th>"
                                + "                  <th class='text-center' style='width: 5%;'><?php echo get_lng($_SESSION["lng"], "L0379"); /* Curr */  ?></th>"
                                + "                  <th class='text-center' style='width: 10%;'><?php echo get_lng($_SESSION["lng"], "L0380"); /* Price */  ?></th>"
                                + "                  <th class='text-center' style='width: 8%;'><?php echo get_lng($_SESSION["lng"], "L0381"); /* Qty */  ?></th>"
                                + "                  <th class='text-center' style='width: 10%;'><?php echo get_lng($_SESSION["lng"], "L0382"); /* Amount */  ?></th>"
                                + "                  <th class='text-center' style='width: 35%;'><?php echo get_lng($_SESSION["lng"], "L0422"); /* Error Message */  ?></th>"
                                + "               </tr>"
                                + "           </thead>"
                                + "           <tbody>";
                            $.each(result.data, function(k, v) {
                                    // same supno
                                   if (supcheck != v.Supno){
                                       //console.log("supno" + v.Supno)
                                       var  element = {};
                                        element.supno = v.Supno;
                                        element.requestDate = 'requestDate' + v.Supno;
                                        dataarray.push(element);
                                       if(supcheck !=  ""){
                                            htmlval = htmlval +"  </tbody></table><br/><br/>";
                                       }
                                        //alert(duedate);
                                        htmlval = htmlval + "<div class='col-md-12'><div class='col-md-3'>" + '<?php echo get_lng($_SESSION["lng"], "L0451"); ?>' + " : " + v.Supno + "</div>";
                                        htmlval = htmlval +  "<div class='col-md-9'><div class='col-md-3'>" + '<?php echo get_lng($_SESSION["lng"], "L0454"); ?>' + " : </div>" 
                                        +  "<div class='col-md-3'>"
                                        + "<input name='requestDate" +v.Supno+"' class='form-control input-xs' id='requestDate" +v.Supno+"' type='text' size='12' maxlength='12' style='width: 100%; background-color: white !important;' readonly>"
                                        + " <input name='requestDate" +v.Supno+"2' id='requestDate" +v.Supno+"2' type='hidden'></div></div></div><br/><br/>";
                                        
                                        bodytable = "<tr>"
                                        + "<td>"+ v.Supno + "</td>"
                                        + "<td>"+ v.Supnm + "</td>"
                                        + "<td>"+ v.PartNumber + "</td>"
                                        //+ "<td>"+ v.ModelCode + "</td>"
                                        + "<td>"+ v.Currency + "</td>"
                                        + "<td>"+ v.Price + "</td>"
                                        + "<td>"+ v.Quantity + "</td>"
                                        + "<td>"+ v.Amount + "</td>"
                                        + "<td>"+ v.Error + "</td>"
                                        + "</tr>";
                                        bodytable =  header +bodytable;


                                        supcheck = v.Supno;
                                   }
                                   else{
                                     bodytable = "<tr>"
                                        + "<td>"+ v.Supno + "</td>"
                                        + "<td>"+ v.Supnm + "</td>"
                                        + "<td>"+ v.PartNumber + "</td>"
                                       // + "<td>"+ v.ModelCode + "</td>"
                                        + "<td>"+ v.Currency + "</td>"
                                        + "<td>"+ v.Price + "</td>"
                                        + "<td>"+ v.Quantity + "</td>"
                                        + "<td>"+ v.Amount + "</td>"
                                        + "<td>"+ v.Error + "</td>"
                                        + "</tr>";
                                   }
                                   htmlval =  htmlval + bodytable;
                                   //htmlval =  htmlval + header +"  </tbody></table>"
                                            + "   </div>"
                                            + " </div>";

                                   
                            });

                            htmlval =  htmlval + "</div> </div>";
                            //console.log(htmlval);
                            $("#tbresult").html(htmlval);
                            getDuedate(dataarray);
                        }
                    });
                }

				function getDuedate(arraydata){
                    //console.log(arraydata);

                    $.each(arraydata, function(index, value) {
                            //console.log("Supno: " + value.supno + "ID: " + value.requestDate);
                            $.ajax({
                            type: 'POST',
                            url: 'supRequestDueDate.php',
                            data: {Supno : value.supno},
                            success: function (response) {
                                
                                var rcv = response.split(",");
                                $("#" + value.requestDate).val(rcv[2]);
                                $("#" +value.requestDate + "2").val(rcv[2]);
                                var sumsuppiler = $("#supnosum").val();
                                sumsuppiler = sumsuppiler + "," + value.supno;
                                $("#supnosum").val(sumsuppiler);
                                var date = rcv[2].split('-');
                                var dateToday = new Date();
                                var minYear = dateToday.getFullYear();
                                var minMonth = date[1] || 1;
                                minMonth = minMonth - 1;
                                var minDay = date[0] || 1;
                                var maxYear = parseInt($("#maxyr").val().substring(0, 4)) || dateToday.getFullYear();
                                $("#" + value.requestDate).datepicker({
                                    startDate: new Date(minYear, minMonth, minDay),
                                    endDate: new Date(maxYear, 11, 31),
                                    format: 'dd-mm-yyyy',
                                    todayHighlight: true,
                                    autoclose: true
                                }).on('changeDate', function (e) {
                                    $.ajax({
                                        type: 'GET',
                                        url: 'supcheckIsHolidayShoppingcart.php',
                                        data: {selected: e.format(0, "dd-mm-yyyy"), supno : value.supno},
                                        success: function (data) {
                                           // alert(data);
                                            if (data.substr(0, 5) === 'Error') {
                                                updateTips(data);
                                            } else {
                                                //alert(value.requestDate );
                                                hide('validateTips2');
                                                $("#" + value.requestDate).datepicker("update", $("#" + value.requestDate).val());
                                                $("#" + value.requestDate + "2").val($("#" + value.requestDate).val());
                                                return true;
                                                /*
                                                if (data) {
                                                    alert(data);
                                                    $("#requestDate").datepicker("update", $("#requestDate2").val());
                                                    return false;
                                                } else {
                                                    $("#requestDate2").val($("#requestDate").val());
                                                    return true;
                                                }
                                                */
                                            }
                                        }
                                    });
                                });
                            }
                        });
                    });
                }
                
                function getShipToAddressAjax(shipto) {
                    document.getElementById('shipToAddress').innerHTML = '';
                    var shipToCd = shipto;
                    var cusno = <?php echo $fcusno; ?>;
                    $.ajax({
                        type: 'POST',
                        url: 'getShipToAddressAjax.php',
                        async: false,
                        data: {
                            shipToCd: shipToCd,
                            cusno: cusno
                        },
                        dataType: 'json',
                        success: function (res) {
                            var ship_to_nm = res['ship_to_nm'];
                            var adrs1 = res['adrs1'];
                            var adrs2 = res['adrs2'];
                            var adrs3 = res['adrs3'];
                            var comp_tel_no = res['comp_tel_no'];
                            var pstl_cd = res['pstl_cd'];
                            var shipToAddress = "";
                            if (ship_to_nm !== '' && ship_to_nm !== null && ship_to_nm !== undefined && ship_to_nm.toLowerCase() !== 'null' && ship_to_nm.toLowerCase() !== 'undefined') {
                                shipToAddress += ship_to_nm + "<br>";
                            }
                            if (adrs1 !== '' && adrs1 !== null && adrs1 !== undefined && adrs1.toLowerCase() !== 'null' && adrs1.toLowerCase() !== 'undefined') {
                                shipToAddress += adrs1 + "<br>";
                            }
                            if (adrs2 !== '' && adrs2 !== null && adrs2 !== undefined && adrs2.toLowerCase() !== 'null' && adrs2.toLowerCase() !== 'undefined') {
                                shipToAddress += adrs2 + "<br>";
                            }
                            if (adrs3 !== '' && adrs3 !== null && adrs3 !== undefined && adrs3.toLowerCase() !== 'null' && adrs3.toLowerCase() !== 'undefined') {
                                shipToAddress += adrs3 + " ,";
                            }
                            if (pstl_cd !== '' && pstl_cd !== null && pstl_cd !== undefined && pstl_cd.toLowerCase() !== 'null' && pstl_cd.toLowerCase() !== 'undefined') {
                                shipToAddress += pstl_cd + "<br>";
                            }
                            if (comp_tel_no !== '' && comp_tel_no !== null && comp_tel_no !== undefined && comp_tel_no.toLowerCase() !== 'null' && comp_tel_no.toLowerCase() !== 'undefined') {
                                shipToAddress += comp_tel_no;
                            }
                            document.getElementById("shipToAddress").innerHTML = shipToAddress;
                        }
                    });
                }

                //$url="suporder2_new.php?".paramEncrypt("action=$action&ordno=$xordno&cusno=$cusno&shpno=$cusno&orddate=$cdate&corno=$corno&oecus=$oecus&shipment=$shipment&ordertype=$ordertype&requestDate=$requestDate&shpCd=$shpCd&txtnote=$txtnote&supno=$supno"); //Zia added tetnote

				$("#btnproceed").click(function () {
                    //ciclk on create an order
                    var bValid = true;
                    var edata;
                    var orderdate = $("#inp_orderdate").val();
                    var ordertype = $("#inp_ordertype").val();
                    var ordernum = $("#inp_ordernum").val();
                    var shipto = $('#inp_shipto').val();
                    var note = $('#inp_note').val();
                    var shipaddr = $('#shipToAddress').val();
                    
                    var corno = $('#inp_ponum');
                    var allFields = $([]).add(corno);
                    allFields.removeClass("has-error");
                    var oShpCd = $("#inp_shipto");
                    var shpCd = oShpCd.val();
                    var vcorno = corno.val();
                    var isShipToValid = checkShipTo(oShpCd);
                    bValid = bValid && checkLength(corno, "PO Number", 2, 10) && isShipToValid;
                    bValid = bValid && checkRegexp(corno, /^[0-9a-zA-Z_\-]*$/g, '<?php echo get_lng($_SESSION["lng"], "E0045"); /* PO Number cannot contain space ,*|\":<>[\]{}#^`\\%().;@&$ and Thai language */ ?>');
                    bValid = bValid && checkspace(corno, /\s/, '<?php echo get_lng($_SESSION["lng"], "E0045"); /* PO Number cannot contain space ,*|\":<>[\]{}#^`\\%().;@&$ and Thai language */ ?>');
                   

                    //Get date
                    var requestDate = "";
                    var supno = "";
                    var sumsup = $("#supnosum").val();
                    var sum = sumsup.split(',');
                    for (i = 1; i < sum.length; i++) {
                        requestDate += $("#requestDate"+ sum[i]).val() + ",";
                        supno += sum[i] + ",";
                    }
                    var para = "selected=" +requestDate + "&supno=" +supno;
                    if (requestDate === null || requestDate === undefined || requestDate === '') {
                        updateTips('<?php echo get_lng($_SESSION["lng"], "E0052"); /* invalid Due date */ ?>');
                        $('#requestDate').parent().addClass("has-error");
                        return false;
                    } else {
                        $('#requestDate').parent().removeClass("has-error");
                    }
                    $.ajax({
                        type: 'GET',
                        url: 'supcheckDateShoppingcart.php',
                        data: para,
                        success: function (data) {
                            
                            if (data.substr(0, 5) === 'Error') {
                                updateTips(data);
                                bValid = false;
                            } else {
                                // zia added note function ..End
                                //var vshipment = $('#shipment').val();
                                var vshipment =  '';
                                var vaction = "new";
                                edata = "ordno=" + ordernum + "&corno=" + vcorno + "&orddate=" + orderdate + "&shipto=" + shipto + "&shipment=&action=" + vaction + "&ordertype=" + ordertype + "&requestDate=" + requestDate  + "&txtnote=" + note + "&supno=" + supno;
                                            
                                //alert(edata);
                                if (bValid) {
                                    $.ajax({
                                        type: 'GET',
                                        url: 'supimport/validate_order_ajax.php',
                                        data: edata,
                                        success: function (data) {
                                            if (data.substr(0, 5) === 'Error') {
                                                show('validateTips2');
                                                updateTips(data);
                                                return false;
                                            } else {
                                                window.location.href = data;
                                            }  
                                        }
                                    });
                                }
                                
                            }
                        }
                    });
                     

                    
				});
					
            });

            function checkLength(o, n, min, max) {
                if (o.val().length > max || o.val().length < min) {
                    o.parent().addClass("has-error");
                    if (max !== min) {
                        updateTips("<?php echo get_lng($_SESSION["lng"], "E0044"); /* Length of PO number must be between 2 and 10 */ ?>");
                        return false;
                    } else {
                        updateTips("invalid Due date");
                        return false;
                    }
                } else {
                    return true;
                }
            }

            function checkRegexp(o, regexp, n) {
                if (!(regexp.test(o.val()))) {
                    o.parent().addClass("has-error");
                    updateTips(n);
                    return false;
                } else {
                    return true;
                }
            }

            function checkspace(o, regexp, n) {
                if ((regexp.test(o.val()))) {
                    o.parent().addClass("has-error");
                    updateTips(n);
                    return false;
                } else {
                    return true;
                }
            }

            function checkShipTo(oShpCd) {
                //validation before submit.
                var vShpCd = oShpCd;
                if (vShpCd !== '' && vShpCd !== null && vShpCd !== undefined && vShpCd !== 'undefined') {
                    oShpCd.parent().removeClass("has-error");
                    return true;
                } else {
                    oShpCd.parent().addClass("has-error");
                    oShpCd.focus();
                    updateTips("<?php echo get_lng($_SESSION["lng"], "E0047"); /* Ship to code should be filled! */ ?>");
                    return false;
                }
            }

            function updateTips(t) {
                var element = document.getElementById('validateTips2');
                element.innerHTML = t;
                show('validateTips2');
            }

            function show() {
                if (document.getElementById('validateTips2').style.display === 'none') {
                    document.getElementById('validateTips2').style.display = 'block';
                }
                return false;
            }
            function hide() {
                if (document.getElementById('validateTips2').style.display === 'block') {
                    document.getElementById('validateTips2').style.display = 'none';
                }
                return false;
            }
        </script> 
    </head>
	<body>
    <input type="hidden" id="maxyr" name="maxyr" value="<?php echo $maxyr; ?>" />
    <?php ctc_get_logo_new(); ?>
    <div id="mainNav">
        <?php
			
            include("navhoriz.php");
        ?>
    </div> 
    <div id="isi">
		<div id="twocolLeft">
           		<?
			  	//$_GET['current']="main";
				if($ordertype=='Normal'){
					$formtitle=get_lng($_SESSION["lng"], "L0164")/*'Normal Order Upload'*/;
					//$_GET['current']="main";
				}
				else if($ordertype=='Urgent'){
					$formtitle=get_lng($_SESSION["lng"], "L0291")/*'Urgent Order Upload'*/;
					//$_GET['current']="urgentOrder";
				}
				else if($ordertype=='Request'){
					$formtitle=get_lng($_SESSION["lng"], "L0290")/*'Requested Due Date Order Upload'*/;
					//$_GET['current']="requestDueDate";
				}
				$_GET['current'] = "supcata_cataloguemain";
				include("navUser.php");
			  ?>
        </div>
        <div id="twocolRight">
			<table width="97%" border="0" cellspacing="0" cellpadding="0">
				<tr class="arial21redbold">
					<td><?php echo $formtitle;?></td>
					<td>&nbsp;</td>
				    <td colspan="7"><?php if($ordertype=='Urgent'){include('countdown.php');}?></td>
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
				<tr class="arial21redbold">
					<td colspan="7"><div class="alert alert-warning validateTips2 margin-bottom-md" id="validateTips2" role="alert" style="display: none;"></div></td>
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
					<td width="22%"><?php echo get_lng($_SESSION["lng"], "L0191"); ?><!--Customer Number--></td>
					<td width="2%">:</td>
					<td width="26%"><? echo $cusno ?></td>
					<td width="4%"></td>
					<td width="20%"><?php echo get_lng($_SESSION["lng"], "L0192"); ?><!--Customer Name--></td>
					<td width="2%">:</td>
					<td width="25%"><? echo $cusnm ?></td>
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
					<td width="22%"><?php echo get_lng($_SESSION["lng"], "L0388"); ?><!--Order Date--></td>
					<td width="2%">:</td>
					<td width="26%"><? echo $orderDate ?></td>
					<td width="4%"></td>
					<td width="20%"><?php echo get_lng($_SESSION["lng"], "L0391"); ?><!--Order Type--></td>
					<td width="2%">:</td>
					<td width="25%"><? echo $ordertype ?></td>
				</tr>
				<tr class="arial11blackbold">
					<td>
                        <input name="inp_orderdate" id="inp_orderdate" type="hidden" value='<? echo $orderDate ?>'>
                        <input name="inp_ordertype" id="inp_ordertype" type="hidden" value='<? echo $ordertype ?>'>
                    </td>
					<td>
                        <div class="form-inline">
                            <?php
                                $orddt = date("d-m-Y");
                                echo "<input name=\"orddate\" type=\"text\"  id=\"orddate\" class=\"hidden arial11black form-control input-xs\" readonly=\"true\" style=\"width: 100%\"  maxlength=\"10\" size=\"10\" value=" . $orddt . ">";
                            ?>
                            <input name="supnosum" id="supnosum" type="hidden">
                        </div>    
                    </td>
					<td>&nbsp;</td>
					<td></td>
					<td>&nbsp;</td>
					<td>&nbsp;</td>
					<td>&nbsp;</td>
				</tr>
				<tr class="arial11blackbold">
					<td width="22%"><?php echo get_lng($_SESSION["lng"], "L0389");//Denso Order Number ?></td>
					<td width="2%">:</td>
					<td width="26%"><? echo $ordno ?></td>
					<td width="4%"></td>
					<td width="20%"><?php echo get_lng($_SESSION["lng"], "L0392");//PO Number ?></td>
					<td width="2%">:</td>
					<td width="25%"><? echo $corno ?></td>
				</tr>
				<tr class="arial11blackbold">
					<td>
                        <input name="inp_ordernum" id="inp_ordernum" type="hidden" value='<? echo $ordno ?>'>
                        <input name="inp_ponum" id="inp_ponum" type="hidden" value='<? echo $corno ?>'>
                    </td>
					<td>&nbsp;</td>
					<td>&nbsp;</td>
					<td></td>
					<td>&nbsp;</td>
					<td>&nbsp;</td>
					<td>&nbsp;</td>
				</tr>
				<tr class="arial11blackbold">
					<td width="22%"><?php echo get_lng($_SESSION["lng"], "L0393");//Shipto ?></td>
					<td width="2%">:</td>
					<td width="26%"><? echo $shipto ?></td>
					<td width="4%"></td>
					<td width="20%"></td>
					<td width="2%"></td>
					<td width="25%"></td>
				</tr>
				<tr class="arial11blackbold">
					<td></td>
					<td>&nbsp;</td>
					<td>&nbsp;</td>
					<td></td>
					<td>&nbsp;</td>
					<td>&nbsp;</td>
					<td>&nbsp;</td>
				</tr>
				<tr class="arial11blackbold">
					<td width="22%"><?php echo get_lng($_SESSION["lng"], "L0394");//Ship To Address ?></td>
					<td width="2%">:</td>
					<td width="26%"><label id="shipToAddress"></label></td>
					<td width="4%"></td>
					<td width="20%"></td>
					<td width="2%"></td>
					<td width="25%"></td>
				</tr>
				<tr class="arial11blackbold">
					<td>
                        <input name="inp_shipto" id="inp_shipto" type="hidden" value='<? echo $shipto ?>'>
                        <input name="inp_note" id="inp_note" type="hidden" value='<? echo $txtnote ?>'>
                    </td>
					<td>&nbsp;</td>
					<td>&nbsp;</td>
					<td></td>
					<td>&nbsp;</td>
					<td>&nbsp;</td>
					<td>&nbsp;</td>
				</tr>
				<tr class="arial11blackbold">
					<td width="22%"><?php echo get_lng($_SESSION["lng"], "L0334"); ?><!--Note--></td>
					<td width="2%">:</td>
					<td width="26%"><? echo $txtnote ?></td>
					<td width="4%"></td>
					<td width="20%"></td>
					<td width="2%"></td>
					<td width="25%"></td>
				</tr>
			</table>     
			<table width="97%" border="0" cellspacing="0" cellpadding="0">
				<tr>
					<td>&nbsp;</td>
					<td>&nbsp;</td>
					<td>&nbsp;</td>
				</tr>
				<tr>
					<td>&nbsp;</td>
					<td>&nbsp;</td>
					<td>&nbsp;</td>
				</tr>
				<tr>
					<?php 

					$action="add";
					$url2="supimport_order_new.php?".paramEncrypt("ordertype=$ordertype&action=delete&ordno=$xordno&cusno=$cusno&corno=$corno");
					
					?>
					<td width="85%"></td>
					<td align="right">
						<a href='#' id='btnproceed' style='text-decoration-line: none;'>
							<div style='background-color: #AD1D36;color: #FFFFFF;width: 140px;height:22px;'>
								<font style='font-size:10pt;line-height:22px;margin-right:8px;'><?php echo get_lng($_SESSION["lng"], "L0202"); ?></font> 
							</div>
						</a> 
					</td>
					<td width="9%" align="right">
						<a href='<?php echo $url2; ?>' style='text-decoration-line: none;'>
							<div style='background-color: #AD1D36;color: #FFFFFF;width: 80px;height:22px;'>
								<font style='font-size:10pt;line-height:22px;margin-right:22px;'><?php echo get_lng($_SESSION["lng"], "L0203"); ?></font>
							</div>
						</a>
					</td>
				<tr>
				<tr>
					<td>&nbsp;</td>
					<td>&nbsp;</td>
					<td>&nbsp;</td>
				</tr>
			</table>
			<div class="col-md-12 margin-bottom-md">
				<div class="col-md-12" id="tbresult">
				</div>
			</div>
			<div class="row">
				<div class="col-md-12">
					<span class="arial11redbold" style="color:#B30000"><?php echo get_lng($_SESSION["lng"], "L0421"); /* Note: It's not allowed to select multiple ship to part in one PO. Please divide PO by each \"Ship to\". */ ?></span>
				</div>
			</div>
           
            <div id="footerMain1">
                <ul>
                    <li>
                        <div id="footerDesc">
                            <p>Copyright &copy; 2023 DENSO . All rights reserved</p>
                        </div>
                    </li>
                </ul>
            </div>    
        </div>
    </div>
    </body>	
</html>