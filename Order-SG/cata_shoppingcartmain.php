<?php /* Create by CTC */ ?>
<?php
session_start();
require_once('../core/ctc_init.php');
require_once('../core/ctc_timezone.php');
require_once('../language/Lang_Lib.php');
require_once('../core/ctc_permission.php');
require("crypt.php");

ctc_checkuser_permission('../login.php');

$cusno = ctc_get_session_cusno();
$cusnm = ctc_get_session_cusnm();

$ctcdb = new ctcdb();
$sc003Qry = "SELECT max(yrmon) as maxyr FROM sc003pr WHERE Owner_Comp='$comp'";

$sth = $ctcdb->db->prepare($sc003Qry);
$sth->execute();

$result = $sth->fetchAll(PDO::FETCH_ASSOC);
$maxyr = $result[0]['maxyr'];

include('chklogin.php');
?>
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
            var waitTyping = null;
            var table;
            var slideIndex = 0;
            var currentSideBar = 0;
            var tableRows;
            function searchKey() {
                if (waitTyping) {
                    clearTimeout(waitTyping);
                }

                waitTyping = setTimeout(function () {
                    table.ajax.reload();
                }, 500);
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

            function checkThai(event) {
                //Not allow thai language.
                var keysCode = event.keyCode || event.which;
                if ((keysCode >= 48 && keysCode <= 57) //0-9
                        || (keysCode >= 65 && keysCode <= 90) //a-z
                        || (keysCode >= 97 && keysCode <= 122) //A-Z
                        || (keysCode === 8)  //backspace
                        || (keysCode === 45) //-
                        || (keysCode === 95) //underscore
                        ) {
                    return true;
                } else {
                    event.preventDefault();
                    var errorTxt = '<?php echo get_lng($_SESSION["lng"], "E0053"); /* Data Invalid.Allow only 0-9,a-z,A-Z,-,_ */ ?>';
                    alert(errorTxt);
                    return false;
                }
            }

            function getShipToAddressAjax(val) {
                //Get Address by Ship to Code.
                table.ajax.reload();
                document.getElementById('shipToAddress').innerHTML = '';
                var shipToCd = val;
                var cusno = <?php echo $cusno; ?>;
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

            function checkShipTo(oShpCd) {
                //validation before submit.
                var vShpCd = oShpCd.val();
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

            function updateTips(t) {
                var element = document.getElementById('validateTips2');
                element.innerHTML = t;
                show('validateTips2');
            }
            
            $(document).ready(function () {
                /* Add by CTC Sippavit 02/10/2020 */
                window.onload = window.history.forward();
                
                $("#button-confirm").click(function () {
                    var bValid = true;
                    var oShpCd = $("#txtShpNo");
                    var shpCd = oShpCd.val();
                    var corno = $("#txtCorno");
                    var allFields = $([]).add(corno);
                    allFields.removeClass("has-error");
                    var isShipToValid = checkShipTo(oShpCd);
                    bValid = bValid && checkLength(corno, "PO Number", 2, 10) && isShipToValid;
                    bValid = bValid && checkRegexp(corno, /^[0-9a-zA-Z_\-]*$/g, '<?php echo get_lng($_SESSION["lng"], "E0045"); /* PO Number cannot contain space ,*|\":<>[\]{}#^`\\%().;@&$ and Thai language */ ?>');
                    bValid = bValid && checkspace(corno, /\s/, '<?php echo get_lng($_SESSION["lng"], "E0045"); /* PO Number cannot contain space ,*|\":<>[\]{}#^`\\%().;@&$ and Thai language */ ?>');
                    var str2 = $("#requestDate").val();

                    var edata;
                    var vcorno = corno.val();
                    var vorderno = $('#orderno').val();
                    var vorddate = $('#orddate').val();
                    var vordertype = $('#ordertype').val();
                    var requestDate = $("#requestDate").val();
                    var vshpno = shpCd;
                    var vtxtnote = $('#txtnote').val();

                    // zia added note function ..End
                    var vshipment = $('#shipment').val();
                    if (vshpno.length === 0) {
                        $('#txtShpNo').addClass("ui-state-highlight");
                        updateTips('<?php echo get_lng($_SESSION["lng"], "E0047"); /* Ship to code should be filled! */ ?>');
                        return false;
                    }
                    var vaction = "new";
                    edata = "ordno=" + vorderno + "&corno=" + vcorno + "&orddate=" + vorddate + "&shpno=" + vshpno + "&shipment=" + vshipment + "&action=" + vaction + "&ordertype=" + vordertype + "&requestDate=" + requestDate + "&shpCd=" + vshpno + "&txtnote=" + vtxtnote;
                    var para = "selected=" + str2;
                    if (requestDate === null || requestDate === undefined || requestDate === '') {
                        updateTips('<?php echo get_lng($_SESSION["lng"], "E0052"); /* invalid Due date */ ?>');
                        $('#requestDate').parent().addClass("has-error");
                        return false;
                    } else {
                        $('#requestDate').parent().removeClass("has-error");
                    }

                    $.ajax({
                        type: 'GET',
                        url: 'checkIsHolidayShoppingcart.php',
                        data: para,
                        success: function (data) {
                            if (data.substr(0, 5) === 'Error') {
                                updateTips(data);
                            } else {
                                if (bValid) {
                                    corno.parent().removeClass("has-error");
                                    var erShipto = $('.error-shipto', tableRows).length;
                                    if (erShipto !== 0) {
                                        show('validateTips2');
                                        updateTips('<?php echo get_lng($_SESSION["lng"], "E0055"); /* Error found, Please check item. */ ?>');
                                        $('.error-shipto', tableRows).map(function () {
                                            $(this).parent().parent().addClass('bg-danger-important');
                                        })
                                        return false;
                                    } else {
                                        $.ajax({
                                            type: 'GET',
                                            url: 'shoppingcartmain/ctc_validate_order_ajax.php',
                                            data: edata,
                                            success: function (data) {
                                                if (data.substr(0, 5) === 'Error') {
                                                    show('validateTips2');
                                                    updateTips(data);
                                                    corno.parent().addClass("has-error");
                                                    return false;
                                                } else {
                                                    window.location.href = data;
                                                }
                                            }
                                        });
                                    }
                                }
                            }
                        }
                    });
                });

                function setRequestOrder() {
                    var vordertype = 'Request';
                    var customerNo = "<?php echo $cusno ?>";
                    edata = "ordertype=" + vordertype;
                    $.ajax({
                        type: 'GET',
                        url: 'getordnoprd_new.php',
                        data: edata,
                        async: false,
                        success: function (data) {
                            if (data.substr(0, 5) !== 'Error') {
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
                        data: {customerNo: customerNo},
                        dataType: 'json',
                        success: function (res) {
                            var txtselect = '<?php echo get_lng($_SESSION["lng"], "E0050"); /* Please select ship code' */ ?>';
                            if (res.length > 1) {
                                $("#txtShpNo").append("<option value='' selected>-----" + txtselect + "-----</option>");
                            }
                            if (res.length > 0) {

                                for (var i = 0; i < res.length; i++) {
									shiNm = res[i].shipNm;
									if(shiNm.length > 50){shiNm = shiNm.substring(0, 49) + '...'};
                                    $("#txtShpNo").append("<option value='" + res[i].shipCd + "'>" + res[i].shipCd + ' : ' +shiNm + "</option>");
                                }
                            }
                            if (res.length === 1) {
                                getShipToAddressAjax($("#txtShpNo").val());
                            }
                        }
                    });

                    $.ajax({
                        type: 'POST',
                        url: 'setRequestDueDate.php',
                        success: function (data) {
                            var rcv = data.split(",");
                            $("#requestDate").val(rcv[2]);
                            $("#requestDate2").val(rcv[2]);
                            var date = $("#requestDate2").val().split('-');
                            var dateToday = new Date();
                            var minYear = dateToday.getFullYear();
                            var minMonth = date[1] || 1;
                            minMonth = minMonth - 1;
                            var minDay = date[0] || 1;
                            var maxYear = parseInt($("#maxyr").val().substring(0, 4)) || dateToday.getFullYear();
                            $("#requestDate").datepicker({
                                startDate: new Date(minYear, minMonth, minDay),
                                endDate: new Date(maxYear, 11, 31),
                                format: 'dd-mm-yyyy',
                                todayHighlight: true,
                                autoclose: true
                            }).on('changeDate', function (e) {
                                $.ajax({
                                    type: 'GET',
                                    url: 'checkIsHolidayShoppingcart.php',
                                    data: {selected: e.format(0, "dd-mm-yyyy")},
                                    success: function (data) {
                                        if (data) {
                                            alert(data);
                                            $("#requestDate").datepicker("update", $("#requestDate2").val());
                                            return false;
                                        } else {
                                            $("#requestDate2").val($("#requestDate").val());
                                            return true;
                                        }
                                    }
                                });
                            });
                        }
                    });
                    $(".validateTips").text('<?php echo get_lng($_SESSION["lng"], "E0051"); /* All form fields are required. */ ?>').removeClass("ui-state-highlight");
                    $("#result_tr1").hide();
                }
                
                /* Add by CTC Sippavit 02/10/2020 */
                //Call get request order and reset order Delay for disablePrev must run first
                setTimeout(function (){
                    setRequestOrder();
                }, 1000);
                
                function ReloadCartItemBadge() {
                    $('#cartBadge').empty();
                    $.ajax({
                        url: "cataloguemain/ctcGetCurrentCartItemAjax.php",
                        type: 'post',
                        success: function (response) {
                            $('#cartBadge').html(response);
                        }
                    });
                }

                //Default DropdownList Data
                ReloadCartItemBadge();

                $("#check-all").on('click', function () {
                    var ischeck = $(this).is(':checked');
                    $('.group-check', tableRows).prop('checked', ischeck);
                });

                table = $('#tablecatalog').removeAttr('width').DataTable({
                    searching: false,
                    pageLength: 10,
                    scrollY: true,
                    scrollX: true,
                    scrollCollapse: true,
                    info: false,
                    lengthChange: false,
                    retrieve: true,
                    serverSide: false,
                    ordering: false,
                    processing: true,
                    language: {
                        zeroRecords: 'Data not found', //Message
                        emptyTable: 'Data not found', //Message
                        paginate: {
                            previous: '&nbsp;<span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span>',
                            next: '&nbsp;<span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span>'
                        }
                    },
                    ajax: {
                        url: 'shoppingcartmain/ctc_get_shoppingcart_table_ajax.php',
                        dataType: 'json',
                        type: 'POST',
                        data: function (data) {
                            data.ShipNo = $('#txtShpNo').val();
                        },
                        beforeSend: function () {

                        },
                        complete: function (result) {
                            tableRows = table.rows({'search': 'applied'}).nodes();
                            $('#total-item').html(tableRows.length);
                            $(".group-check", tableRows).on('click', function () {
                                var totalAll = $('.group-check', tableRows).length;
                                var totalChecked = $('.group-check:checked', tableRows).length;
                                if (totalAll === totalChecked) {
                                    $('#check-all').prop('checked', true);
                                } else {
                                    $('#check-all').prop('checked', false);
                                }
                            });

                            $(".touchspinQuantity", tableRows).TouchSpin({
                                buttondown_class: "btn btn-default btn-xs",
                                buttonup_class: "btn btn-default btn-xs"
                            });

                            $(".touchspinQuantity", tableRows).on('change', function () {
                                var intervalStep = $(this).data('bts-step') || 0;
                                var currency = $('.touchspinQuantity:not([data-cur=""])', tableRows).data('cur') || '';
                                var numberCart = parseInt($(this).val()) || 0;
                                if ((numberCart % intervalStep) !== 0 || numberCart === 0) {
                                    if (intervalStep !== 0 || numberCart !== 0) {
                                        swal({
                                            title: '<?php echo get_lng($_SESSION["lng"], "E0040")/* Order Not in Lot Size!, Lot Size={lotsize} */; ?>'.replace('{lotsize}', intervalStep), //Message
                                            buttons: "<?php echo get_lng($_SESSION["lng"], "L0372")/* OK */; ?>"
                                        });
                                    }
                                }

                                if (numberCart === 0) {
                                    $(this).val(0);
                                }

                                var amountId = '#amount' + $(this).attr('id').replace('quantity', '');
                                var priceValue = parseFloat($(this).data('price'));
                                $(amountId, tableRows).html((priceValue * numberCart).toFixed(2));
                                UpdateTotalQuantity();
                                UpdateTotalAmount(currency);
                                
                            });

                            $(".touchspinQuantity", tableRows).trigger('change');
                            $(".touchspinQuantity").on("change", function() {

                                var qty = parseInt($(this).val()) ;
                                var id = $(this).attr('id').replace('quantity', '');
                                //alert(qty +">>"+ cusno + ">>"+ qty);
                                UpdateQTYonBase(id ,qty);
                            });
                        }
                    },
                    columns: [
                        {"data": "Checkbox"},
                        {"data": "PartNumber"},
                        {"data": "ModelCode"},
                        {"data": "Currency"},
                        {"data": "Price"},
                        {"data": "Quantity"},
                        {"data": "Amount"},
                        {"data": "Error"}
                    ],
                    'columnDefs': [
                        {"className": "text-center", "targets": [0, 2, 3, 4, 5, 6]},
                        {"className": "text-left", "targets": [1, 7]},
                    ]
                });

                $('#button-delete', tableRows).on('click', function () {
                    var checkedValues = $('.group-check:checked', tableRows).map(function () {
                        return $(this).val();
                    }).toArray();
                    if (checkedValues.length === 0) {
                        swal({
                            title: '<?php echo get_lng($_SESSION["lng"], "E0042")/* No Data Found....Please Try Again ! */; ?>', //Message
                            buttons: "<?php echo get_lng($_SESSION["lng"], "L0372")/* OK */; ?>"
                        });
                    } else {
                        swal({
                            title: '<?php echo get_lng($_SESSION["lng"], "L0368")/* Comfirm to Delete? */; ?>', //Message
                            buttons: ["<?php echo get_lng($_SESSION["lng"], "L0373")/* Cancel */; ?>", "<?php echo get_lng($_SESSION["lng"], "L0372")/* OK */; ?>"],
                            dangerMode: true
                        }).then(function (result) {
                            if (result) {
                                $.ajax({
                                    url: "shoppingcartmain/ctc_delete_shoppingcart_ajax.php",
                                    type: 'post',
                                    datatype: 'json',
                                    data: {
                                        shoppingidlist: checkedValues
                                    },
                                    success: function (response) {
                                        var responseJson = JSON.parse(response);
                                        if (responseJson.errorCode === '0000') {
                                            swal({
                                                title: '<?php echo get_lng($_SESSION["lng"], "L0369")/* Delete Success! */; ?>', //Message
                                                buttons: "<?php echo get_lng($_SESSION["lng"], "L0372")/* OK */; ?>"
                                            }).then(function () {
                                                table.ajax.reload();
                                                ReloadCartItemBadge();
                                            });
                                        } else if (responseJson.errorCode === '1111') {
                                            swal({
                                                title: '<?php echo get_lng($_SESSION["lng"], "E0042")/* No Data Found....Please Try Again ! */; ?>', //Message
                                                buttons: "<?php echo get_lng($_SESSION["lng"], "L0372")/* OK */; ?>"
                                            });
                                        } else {
                                            swal({
                                                title: '<?php echo get_lng($_SESSION["lng"], "L0369")/* Delete Success! */; ?>', //Message
                                                buttons: "<?php echo get_lng($_SESSION["lng"], "L0372")/* OK */; ?>"
                                            }).then(function () {
                                                table.ajax.reload();
                                                ReloadCartItemBadge();
                                            });
                                        }
                                    }
                                });
                            }
                        });
                    }
                });

                function UpdateTotalQuantity() {
                    var sumQuantity = 0;
                    $('td .touchspinQuantity', tableRows).each(function () {
                        sumQuantity += (parseInt($(this).val()) || 0);
                    });
                    $('#total-quantity').html(sumQuantity);
                }

                function UpdateTotalAmount(currency) {
                    var sumAmount = 0;
                    $('.amount', tableRows).each(function () {
                        sumAmount += (parseFloat($(this).html()) || 0);
                    });
                    $('#total-amount').html(parseFloat(sumAmount).toFixed(2) + ' ' + currency);
                    //$('#total-amount').html(numberWithCommas(parseFloat(sumAmount).toFixed(2)) + ' ' + currency);
                }

                function numberWithCommas(x) {
                    return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
                }

                function UpdateQTYonBase(id ,qty) {
                
               // alert("ID =" + id + ", QTY =" +qty+ ", Cusno =" +cusno);
                
                    $.ajax({
                        type: 'post',
                        url: 'shoppingcartmain/ctc_update_shoppingcart_ajax.php',
                        data:  {ID: id, Quantity: qty},
                        success: function (data) {
                            console.log(data);
                        }
                    });
                
                }
            });
        </script> 
    </head>
    <body>
        <input type="hidden" id="maxyr" name="maxyr" value="<?php echo $maxyr; ?>" />
        <?php ctc_get_logo_new(); ?>
        <div id="mainNav">
            <?php
            $_GET['selection'] = "main";
            include("navhoriz.php");
            ?>
        </div> 
        <div id="isi">
            <div class="col-md-2 margin-bottom-md" style="padding-left: 0px; padding-right: 0px; max-width: 171px;">
                <!--menu-->
                <?php
                include("navUser.php");
                ?>
            </div>
            <div class="col-md-10 margin-bottom-md">
                <div class="row">
                    <div class="col-md-6 arial21redbold">
                        <img src="../images/cata.png" width="17" height="15" alt="">&nbsp; <?php echo get_lng($_SESSION["lng"], "L0436")/* Shopping Cart */; ?>
                    </div>
                    <div class="col-md-6 text-right" style="font-size: 18px;">
                        <button class="btn btn-default btn-outline-primary btn-sm" onclick="window.location.href = 'cata_shoppingcartmain.php'" title="<?php echo get_lng($_SESSION["lng"], "L0339"); /* Shopping Cart */ ?>">
                            <span class="text-primary arial12Bold"><?php echo get_lng($_SESSION["lng"], "L0339"); /* Shopping Cart */ ?></span>
                            <span class="glyphicon glyphicon-shopping-cart text-primary" aria-hidden="true"></span>
                            <span class="badge badge-success" id="cartBadge">0</span>
                        </button>
                    </div>
                </div>
                <div class="row arial11blackbold">
                    <div class="col-md-3 col-sm-6">
                        <div class="padding-top-xs">
                            <?php echo get_lng($_SESSION["lng"], "L0375"); /* Customer Number */ ?>&nbsp;&nbsp;:&nbsp;&nbsp;<?php echo $cusno ?>
                        </div>
                    </div>
                    <div class="col-md-9 col-sm-6">
                        <div class="padding-top-xs">
                            <?php echo get_lng($_SESSION["lng"], "L0376"); /* Customer Name */ ?>&nbsp;&nbsp;:&nbsp;&nbsp;<?php echo $cusnm ?>
                        </div>
                    </div>
                </div>
                <div class="row margin-top-md">
                    <div class="col-md-12">
                        <div class="row">
                            <div class="col-md-12 margin-bottom-sm">
                                <span class="arial11blackbold"><?php echo get_lng($_SESSION["lng"], "E0051"); ?><!--All form fields are required.--></span>
                            </div>
                            <div class="col-sm-12"> 
                                <div class="alert alert-warning validateTips2 margin-bottom-md" id="validateTips2" role="alert" style="display: none;"></div>
                            </div>
                            <div class="col-md-6 margin-bottom-sm">
                                <div class="row">
                                    <div class="col-sm-5 arial11greybold margin-top-sm">
                                        <span class="arial11redbold"><?php echo get_lng($_SESSION["lng"], "L0393"); ?><!--Ship To--> <strong>*</strong></span>
                                    </div>
                                    <div class="hidden-xs col-sm-1 arial11redbold margin-top-sm">
                                        <span>:</span>
                                    </div>
                                    <div class="col-sm-6 arial11black">
                                        <div class="form-inline arial11black">
                                            <?php
                                            $result = ctc_get_cusmas();
                                            $hasil = $result[0];
                                            $bcusno = $hasil['Cusno'];
                                            $vremark = $hasil['remark'];
                                            $vcurcd = $hasil['curcd'];
                                            $voecus = $hasil['OECus'];
                                            if (strtoupper($voecus) != 'Y') {
                                                $voecus = 'N';
                                            }
                                            $gabung = $bcusno . ' - ' . $vremark . '  (' . $vcurcd . ')';
                                            echo '<input type="hidden" name="txtShpNoHidden" id="txtShpNoHidden" value="' . $voecus . '"/>';
                                            ?>
                                            <select class="form-control input-xs" name="txtShpNo" id="txtShpNo" style="width: 100%" onchange="getShipToAddressAjax(this.value)"></select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 margin-bottom-sm">
                                <div class="row">
                                    <div class="col-sm-5 arial11greybold margin-top-sm">
                                        <span class="arial11redbold"><?php echo get_lng($_SESSION["lng"], "L0389"); ?><!--Denso Order Number--> </span>
                                    </div>
                                    <div class="hidden-xs col-sm-1 arial11redbold margin-top-sm">
                                        <span>:</span>
                                    </div>
                                    <div class="col-sm-6 arial11black">
                                        <div class="form-inline arial11black">
                                            <input type="text" name="orderno" id="orderno" class="arial11grey form-control input-xs" style="width: 100%" readonly />
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 margin-bottom-sm">
                                <div class="row">
                                    <div class="col-sm-5 arial11greybold">
                                        <span class="arial11redbold"><?php echo get_lng($_SESSION["lng"], "L0394"); ?><!--Ship To Address--></span>
                                    </div>
                                    <div class="hidden-xs col-sm-1 arial11redbold">
                                        <span>:</span>
                                    </div>
                                    <div class="col-sm-6 arial11black">
                                        <div class="form-inline arial11black">
                                            <label id="shipToAddress"></label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="clearfix"></div>
                            <div class="col-md-6 margin-bottom-sm">
                                <div class="row">
                                    <div class="col-sm-5 arial11greybold margin-top-sm">
                                        <span class="arial11redbold"><?php echo get_lng($_SESSION["lng"], "L0392"); ?> <!--Po Number--> <strong>*</strong></span>
                                    </div>
                                    <div class="hidden-xs col-sm-1 arial11redbold margin-top-sm">
                                        <span>:</span>
                                    </div>
                                    <div class="col-sm-6 arial11black">
                                        <div class="form-inline arial11black">
                                            <input type="text" name="txtCorno" id="txtCorno" onkeypress="checkThai(event);" style="width: 100%" class="arial11black form-control input-xs" maxlength="20" size="20">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 margin-bottom-sm">
                                <div class="row">
                                    <div class="col-sm-5 arial11greybold margin-top-sm">
                                        <span class="arial11redbold"><?php echo get_lng($_SESSION["lng"], "L0391"); ?> <!--Order Type--> </span>
                                    </div>
                                    <div class="hidden-xs col-sm-1 arial11redbold margin-top-sm">
                                        <span>:</span>
                                    </div>
                                    <div class="col-sm-6 arial11black">
                                        <div class="form-inline arial11black">
                                            <input type="text" name="ordertype" id="ordertype" style="width: 100%" class="arial11grey form-control input-xs" value="<?php echo $ordertype; ?>" readonly />
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 margin-bottom-sm">
                                <div class="row">
                                    <div class="col-sm-5 arial11redbold margin-top-sm">
                                        <span class="arial11redbold"><?php echo get_lng($_SESSION["lng"], "L0388"); ?><!--Order Date--></span>
                                    </div>
                                    <div class="hidden-xs col-sm-1 arial11redbold margin-top-sm">
                                        <span>:</span>
                                    </div>
                                    <div class="col-sm-6 arial11black">
                                        <div class="form-inline">
                                            <?php
                                            $orddt = date("d-m-Y");
                                            echo "<input name=\"orddate\" type=\"text\"  id=\"orddate\" class=\"arial11black form-control input-xs\" readonly=\"true\" style=\"width: 100%\"  maxlength=\"10\" size=\"10\" value=" . $orddt . ">";
                                            ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="clearfix"></div>
                            <div class="col-md-6 margin-bottom-sm">
                                <div class="row">
                                    <div class="col-sm-5 arial11greybold margin-top-sm">
                                        <span class="arial11redbold"><?php echo get_lng($_SESSION["lng"], "L0390"); ?><!--DENSO Shipping Day (ETD)--> <strong>*</strong></span>
                                    </div>
                                    <div class="hidden-xs col-sm-1 arial11redbold margin-top-sm">
                                        <span>:</span>
                                    </div>
                                    <div class="col-sm-6 arial11black">
                                        <div class="form-inline arial11black">
                                            <input name="requestDate" class="form-control input-xs" id="requestDate" type="text" size="12" maxlength="12" style="width: 100%; background-color: white !important;" readonly>
                                            <input name="requestDate2" id="requestDate2" type="hidden">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="clearfix"></div>
                            <div class="col-md-6 margin-bottom-sm">
                                <div class="row">
                                    <div class="col-sm-5 arial11greybold margin-top-sm">
                                        <span class="arial11redbold"><?php echo get_lng($_SESSION["lng"], "L0395"); ?><!--Note--></span>
                                    </div>
                                    <div class="hidden-xs col-sm-1 arial11redbold margin-top-sm">
                                        <span>:</span>
                                    </div>
                                    <div class="col-sm-6 arial11black">
                                        <div class="form-inline arial11black">
                                            <textarea name="txtnote" id="txtnote" class="arial11black form-control" maxlength="100" placeholder="Optional" style="width: 100%; font-size: 11px !important;"></textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="clearfix"></div>
                        </div>
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="row">
                                    <div class="col-sm-3 margin-bottom-xs" style="margin-top: 7px;">
                                        <span class="glyphicon glyphicon-shopping-cart arial12Bold" aria-hidden="true"></span>&nbsp;<button type="button" class="btn btn-default btn-outline-primary btn-sm" id="button-confirm" title="<?php echo get_lng($_SESSION["lng"], "L0377")/* Create an order */; ?>"><span class="glyphicon glyphicon-plus text-primary" aria-hidden="true"></span>&nbsp;<span class="text-primary arial12Bold"><?php echo get_lng($_SESSION["lng"], "L0377")/* Save Order Header */; ?></span></button>
                                    </div>
                                    <div class="col-sm-7 margin-top-sm margin-bottom-xs text-right arial12Bold text-primary" style="font-size: 14px;margin-bottom: 0;padding-top: 10px;">
                                        <?php echo get_lng($_SESSION["lng"], "L0383"); /* Total Item */ ?> : <span id="total-item"></span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                        <?php echo get_lng($_SESSION["lng"], "L0384"); /* Total Qty */ ?> : <span id="total-quantity"></span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                        <?php echo get_lng($_SESSION["lng"], "L0385"); /* Total Amount */ ?> : <span id="total-amount"></span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                    </div>
                                    <div class="col-sm-2 form-inline margin-bottom-sm text-right">
                                        <button type="button" class="btn btn-default btn-sm margin-right-sm btn-outline-primary"  style="padding:6px 12px" onclick="window.location.href = 'cata_cataloguemain.php'" title="<?php echo get_lng($_SESSION["lng"], "L0432")/* Add */; ?>"><span class="glyphicon glyphicon-plus text-primary arial12Bold" style="line-height: 2;" aria-hidden="true"></span></button>
                                        <button type="button" class="btn btn-default btn-sm btn-outline-primary"  style="padding:6px 12px" id="button-delete" title="<?php echo get_lng($_SESSION["lng"], "L0433")/* Remove */; ?>"><span class="glyphicon glyphicon-trash text-primary arial12Bold" style="line-height: 2;" aria-hidden="true"></span></button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12" style="margin-top: -12px;">
                                <table id="tablecatalog" class="table table-bordered table-hover table-striped display" style="overflow-x: auto; width: 100%;" cellspacing="0">
                                    <thead>
                                        <tr class="bg-maroon">
                                            <th class="text-center" style="width: 5%;"><input type="checkbox" id="check-all" /></th>
                                            <th class="text-center" style="width: 30%;"><?php echo get_lng($_SESSION["lng"], "L0378"); /* Part Number */ ?></th>
                                            <th class="text-center" style="width: 10%;"><?php echo get_lng($_SESSION["lng"], "L0437"); /* Model Code */ ?></th>
                                            <th class="text-center" style="width: 5%;"><?php echo get_lng($_SESSION["lng"], "L0379"); /* Curr */ ?></th>
                                            <th class="text-center" style="width: 10%;"><?php echo get_lng($_SESSION["lng"], "L0380"); /* Price */ ?></th>
                                            <th class="text-center" style="width: 8%; min-width: 70px;"><?php echo get_lng($_SESSION["lng"], "L0381"); /* Qty */ ?></th>
                                            <th class="text-center" style="width: 10%;"><?php echo get_lng($_SESSION["lng"], "L0382"); /* Amount */ ?></th>
                                            <th class="text-center" style="width: 25%;"><?php echo get_lng($_SESSION["lng"], "L0422"); /* Error Message */ ?></th>
                                        </tr>
                                    </thead>
                                </table>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <span class="arial11redbold" style="color:#B30000"><?php echo get_lng($_SESSION["lng"], "L0421"); /* Note: It's not allowed to select multiple ship to part in one PO. Please divide PO by each \"Ship to\". */ ?></span>
                            </div>
                        </div>
                    </div>
                </div>
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
    </body>	
</html>