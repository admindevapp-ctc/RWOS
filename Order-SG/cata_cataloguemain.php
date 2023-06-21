<?php /* Create by CTC */ ?>
<?php session_start() ?>
<?php
require_once('../core/ctc_init.php');
require_once('../core/ctc_timezone.php');
require_once('../language/Lang_Lib.php');
require_once('../core/ctc_permission.php');

ctc_checkuser_permission('../login.php');

$cusno = ctc_get_session_cusno();
$cusnm = ctc_get_session_cusnm();
?>
<html>
    <head>
        <title>Denso Ordering System</title>
        <meta http-equiv="X-UA-Compatible" content="IE=9, IE=10, IE=11, IE=EDGE" /> 

        <link rel="stylesheet" type="text/css" href="css/dnia.css">
        <link rel="stylesheet" type="text/css" href="admin/bootstrap3.3.7/css/bootstrap.min.css">
        <link rel="stylesheet" type="text/css" href="css/custom-bootstrap.css">
        <link rel="stylesheet" type="text/css" href="css/demos.css">
        <link rel="stylesheet" type="text/css" href="admin/Carousel/carousel.css">
        <link rel="stylesheet" type="text/css" href="admin/DataTables/dataTables.bootstrap.min.css">
        <link rel="stylesheet" type="text/css" href="admin/DataTables/fixedColumns.bootstrap.min.css">
        <link rel="stylesheet" type="text/css" href="admin/bootstrap-touchspin/jquery.bootstrap-touchspin.min.css">
        <link rel="stylesheet" type="text/css" href="admin/lightbox2-2.11.3/css/lightbox.min.css">
        <link rel="stylesheet" type="text/css" href="admin/sweetalert/custom-sweetalert-ie9.css">
        <link rel="stylesheet" type="text/css" href="admin/sweetalert/custom-sweetalert.css">
        <style>
            th.custom-nowrap-table {
                white-space: nowrap;
            }

            .list-group a:hover{
                cursor: pointer;
            }

            .text_info{
                text-overflow: ellipsis;
                overflow: hidden;
                white-space: nowrap;
                display: inline-block;
                width: calc(100% - 45px);
                vertical-align: middle;
            }

            tr { 
                overflow: hidden;
            }
            #groupModel>a>span.badge {
                font-size: 10px;
            }
            .table>thead>tr>th{
                vertical-align: middle!important;
            }
            
        </style>

        <script type="text/javascript" src="lib/jquery-3.5.1.min.js"></script>
        <script type="text/javascript" src="admin/DataTables/datatables.min.js"></script>
        <script type="text/javascript" src="admin/DataTables/dataTables.bootstrap.min.js"></script>
        <script type="text/javascript" src="admin/DataTables/dataTables.fixedColumns.min.js"></script>
        <script type="text/javascript" src="admin/DataTables/dataTables.buttons.min.js"></script>
        <script type="text/javascript" src="admin/bootstrap3.3.7/js/bootstrap.min.js"></script>
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
            function searchKey() {
                if (waitTyping) {
                    clearTimeout(waitTyping);
                }

                waitTyping = setTimeout(function () {
                    table.ajax.reload();
                }, 500);
            }

            $(document).ready(function () {
                $('#ConvExcel').click(function () {
                    var CatMaker = $("#searchCatMaker").val();
                    var ModelName = $("#searchCatModel").val();
                    var ModelCode = $('#searchCatName').val();
                    var SubCatMaker = $('#searchSubCat').val();
                    var SubModelName = $('#searchSubCatName').val();
                    var Brand = $("#searchBrand").val();
                    var SearchTable = $('#searchTable').val();
                    url = 'cata_gettblcatalogueXLSX.php?CatMaker=' + CatMaker + '&ModelName=' + ModelName
                            + '&ModelCode=' + ModelCode + '&SubCatMaker=' + SubCatMaker + '&SubModelName=' + SubModelName + '&Brand=' + Brand + '&SearchTable='+ SearchTable,
                            window.open(url);
                })

                function showSlides() {
                    var i;
                    var slides = document.getElementsByClassName("mySlides");
                    var dots = document.getElementsByClassName("dot");
                    for (i = 0; i < slides.length; i++) {
                        slides[i].style.display = "none";
                    }
                    for (i = 0; i < dots.length; i++) {
                        dots[i].className = dots[i].className.replace(" active", "");
                    }
                    slideIndex++;
                    if (slideIndex > slides.length) {
                        slideIndex = 1
                    }
                    slides[slideIndex - 1].style.display = "block";
                    dots[slideIndex - 1].className += " active";
                    setTimeout(showSlides, 10000); // Change image every 10 seconds
                }

                function UpdateMakerList() {
                    $.ajax({
                        url: "cataloguemain/ctcGetCalalogueCarmakerAjax.php",
                        type: 'post',
                        success: function (response) {
                            var decodeJson = JSON.parse(response);
                            $("#cbocatmaker").empty();
                            $("#cbocatmaker").append('<option value="0" disabled="disabled" selected="selected"><?php echo get_lng($_SESSION["lng"], "L0340")/* Select Maker */; ?></option>');
                            $.each(decodeJson, function (key, value)
                            {
                                $("#cbocatmaker").append('<option value="' + value.CarMaker + '">' + value.CarMaker + '</option>');
                            });
                        }
                    });
                }

                function UpdateModelNameList() {
                    $.ajax({
                        url: "cataloguemain/ctcGetCalalogueModelNameAjax.php",
                        type: 'post',
                        data: {CarMaker: $('#cbocatmaker').val()},
                        success: function (response) {
                            var decodeJson = JSON.parse(response);
                            $("#cbocatmodel").empty();
                            $("#cbocatmodel").append('<option value="0" disabled="disabled" selected="selected"><?php echo get_lng($_SESSION["lng"], "L0341")/* Select Models */; ?></option>');
                            $.each(decodeJson, function (key, value)
                            {
                                $("#cbocatmodel").append('<option value="' + value.ModelName + '">' + value.ModelName + '</option>');
                            });
                        }
                    });
                }

                function UpdateModelCodeList() {
                    $.ajax({
                        url: "cataloguemain/ctcGetCalalogueModelCodeAjax.php",
                        type: 'post',
                        data: {CarMaker: $('#cbocatmaker').val(), ModelName: $('#cbocatmodel').val()},
                        success: function (response) {
                            var decodeJson = JSON.parse(response);
                            $("#cbocatname").empty();
                            $("#cbocatname").append('<option value="0" disabled="disabled" selected="selected"><?php echo get_lng($_SESSION["lng"], "L0342")/* Select Type */; ?></option>');
                            $.each(decodeJson, function (key, value)
                            {
                                $("#cbocatname").append('<option value="' + value.ModelCode + '">' + value.ModelCode + '</option>');
                            });
                        }
                    })
                }

                function UpdateBrandList() {
                    $.ajax({
                        url: "cataloguemain/ctcGetBrandAjax.php",
                        type: 'post',
                        data: {CarMaker: $('#cbocatmaker').val(), ModelName: $('#cbocatmodel').val()},
                        success: function (response) {
                            var decodeJson = JSON.parse(response);
                            $("#cbocatbrand").empty();
                            $("#cbocatbrand").append('<option value="0" disabled="disabled" selected="selected"><?php echo get_lng($_SESSION["lng"], "L0440")/* Select Brand */; ?></option>');
                            $.each(decodeJson, function (key, value)
                            {
                                $("#cbocatbrand").append('<option value="' + value.Brand + '">' + value.Brand + '</option>');
                            });
                        }
                    })
                }

                function ResetSearch() {
                    $('#searchCatMaker').val('');
                    $('#searchCatModel').val('');
                    $('#searchCatName').val('');
                    $('#searchSubCat').val('');
                    $('#searchBrand').val('');
                    $('#searchSubCatName').val('');
                    $('#searchSubGroup').val('');
                }

                function UpdateSubCategory() {
                    $.ajax({
                        url: "cataloguemain/ctcGetCalalogueSubCatagoryAjax.php",
                        type: 'post',
                        data: {
                            CatMaker: $("#searchCatMaker").val(),
                            ModelName: $("#searchCatModel").val(),
                            ModelCode: $('#searchCatName').val(),
                            SubCatMaker: $('#searchSubCat').val(),
                            SubModelName: $('#searchSubCatName').val(),
                            Brand : $('#searchBrand').val(),
                        },
                        success: function (response) {
                            var decodeJson = JSON.parse(response);
                            var htmlDetail = '';
                            var total = 0;
                            $('#groupModel').empty();
                            $.each(decodeJson, function (index, item) {
                                htmlDetail = htmlDetail + '<a class="list-group-item input-xs arial11black bg-skyblue" data-model="' + item.ModelName + '"><span class="text_info" title="' + item.ModelName + '">' + item.ModelName + '</span><span class="badge">' + item.totalRow + '</span></a>';
                                total = total + (parseInt(item.totalRow) || 0);
                            });
                            $('#groupModel').append('<a class="list-group-item input-xs arial11black bg-skyblue" data-model="">All<span class="badge">' + total + '</span></a>');
                            $('#groupModel').append(htmlDetail);
                            $('.list-group-item').on('click', function () {
                                document.getElementById("searchSubGroup").value = $(this).data('model');
                                table.ajax.reload();
                            });
                            $("#groupModel a").click(function () {
                                $('#groupModel a.active').removeClass('active');
                                $(this).addClass("active");
                            });
                        }
                    });
                }

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

                function ReloadCarousel() {
                    $('#cartBadge').empty();
                    $.ajax({
                        url: "cataloguemain/ctcGetAnnounceAjax.php",
                        type: 'post',
                        success: function (response) {
                            var decodeJson = JSON.parse(response);
                            $('#slideShowAnnounce').empty();
                            var imageHtml = '';
                            var dotButtonHtml = '';
                            $.each(decodeJson, function (index, item) {
                                imageHtml = imageHtml
                                        + '<div class="mySlides">'
                                        + '<img src="./anna_images/' + item.PrtPic + '" style="width:100%; max-height: 153px;">'
                                        + '</div>';
                                dotButtonHtml = dotButtonHtml + '<span class="dot" onclick="currentSlide(' + (index + 1) + ')"></span>';
                            });
                            var resultHtml = '<div class="slideshow-container custom-box" style="height: 155px; margin-bottom: 5px;">'
                                    + imageHtml
                                    + '<a class="prev" onclick="plusSlides(-1)"><span class="glyphicon glyphicon-chevron-left"></span></a>'
                                    + '<a class="next" onclick="plusSlides(1)"><span class="glyphicon glyphicon-chevron-right"></span></a>'
                                    + '</div>'
                                    + '<div style="text-align:center">'
                                    + dotButtonHtml
                                    + '</div>';
                            $('#slideShowAnnounce').append(resultHtml);
                            showSlides();
                        }
                    });
                }

                //Default DropdownList Data
                UpdateMakerList();
                UpdateModelNameList();
                UpdateModelCodeList();
                UpdateBrandList();
                UpdateSubCategory();
                ReloadCartItemBadge();
                ReloadCarousel();
                $('#cbocatmaker').on('change', function () {
                    UpdateModelNameList();
                    UpdateModelCodeList();
                    UpdateBrandList();
                });
                $('#cbocatmodel').on('change', function () {
                    UpdateModelCodeList();
                    UpdateBrandList();
                });
                $('#buttonClear').on('click', function () {
                    $("#cbocatmaker").empty();
                    $("#cbocatmodel").empty();
                    $("#cbocatbrand").empty();
                    UpdateMakerList();
                    UpdateModelNameList();
                    UpdateModelCodeList();
                    UpdateBrandList();
                });
                $('#buttonSearch').on('click', function () {
                    ResetSearch();
                    $('#searchCatMaker').val($('#cbocatmaker').val());
                    $('#searchCatModel').val($('#cbocatmodel').val());
                    $('#searchCatName').val($('#cbocatname').val());
                    $('#searchBrand').val($('#cbocatbrand').val());
                    $('#cbosubcatmaker').val('0');
                    $('#submodelname').val('');
                    table.ajax.reload();
                    UpdateSubCategory();
                });
                $('#buttonSubSearch').on('click', function () {
                    var cbosubcatmaker = $('#cbosubcatmaker').val();
                    var submodelname = $('#submodelname').val();
                    var subbrandname = $('#searchBrand').val();
                    if (cbosubcatmaker !== null && submodelname !== null && submodelname !== '') {
                        ResetSearch();
                        $('#searchSubCat').val(cbosubcatmaker);
                        $('#searchSubCatName').val(submodelname);
                        $('#searchBrand').val(subbrandname);
                        $('#cbocatmaker').val('0');
                        $('#cbocatmodel').val('0');
                        $('#cbocatname').val('0');
                        table.ajax.reload();
                        UpdateSubCategory();
                    } else {
                        swal({
                            title: '<?php echo get_lng($_SESSION["lng"], "E0043")/* Please select search condition */; ?>', //Message
                            buttons: "<?php echo get_lng($_SESSION["lng"], "L0372")/* OK */; ?>"
                        });
                    }
                });
                $(".buttonAddToCartSub").on('click', function () {
                    var quantity = $('#quantity' + $(this).val()).val();
                    var id = $(this).data('id');
                //alert(quantity);
                    $.ajax({
                        url: "cataloguemain/ctcSubmitAddToCartAjax.php",
                        type: 'post',
                        data: {ID: id, Quantity: quantity},
                        success: function (response) {
                            var decodeJson = JSON.parse(response);
                            if (decodeJson.errorCode === '0000') {
                                swal({
                                    title: '<?php echo get_lng($_SESSION["lng"], "L0366")/* Add to cart success */; ?>', //Message
                                    buttons: "<?php echo get_lng($_SESSION["lng"], "L0372")/* OK */; ?>"
                                });
                            } else {
                                swal({
                                    title: '<?php echo get_lng($_SESSION["lng"], "E0042")/* No Data Found....Please Try Again ! */; ?>', //Message
                                    buttons: "<?php echo get_lng($_SESSION["lng"], "L0372")/* OK */; ?>"
                                });
                            }
                            ReloadCartItemBadge();
                        }
                    });
                });

                function UpdateSideDetail(box) {
                    var edit_id = $($(box).parent()).find('.button-datail').val()
                    var lotSize = $($(box).parent()).find('.button-datail').data('lot-size');
                    var first = $(box).parents("tr").find("td:first").text();
                    
                    if (edit_id === undefined) {
                        edit_id = first;
                    }
                   // alert(edit_id);
                    if (currentSideBar !== edit_id) {
                        currentSideBar = edit_id;
                        $.ajax({
                            url: "_cata_details_sidebar.php",
                            type: 'post',
                            data: {edit_id: edit_id, lotsize: lotSize},
                            success: function (data) {
                                $('#sideDetail').empty();
                                $('#sideDetail').html(data);
                            }
                        });
                    }
                }
                table = $('#tablecatalog').removeAttr('width').DataTable({
                    searching: false,
                    pageLength: 10,
                    scrollY: true,
                    scrollX: true,
                    scrollCollapse: true,
                    info: false,
                    lengthChange: false,
                    retrieve: true,
                    serverSide: true,
                    ordering: true,
                    order: [[0, "asc"]],
                    processing: true,
                    fixedColumns: {
                        leftColumns: 3,
                        rightColumns: 8,
                    },
                    language: {
                        zeroRecords: 'Data not found', //Message
                        emptyTable: 'Data not found', //Message                        
                        paginate: {
                            previous: '&nbsp;<span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span>',
                            next: '&nbsp;<span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span>'
                        }
                    },
                    ajax:{
                        url: 'cataloguemain/ctcGetCalalogueTableAjax.php',
                        dataType: "json",
                        type: "POST",
                        data: function (data) {
                            data.CatMaker = $("#searchCatMaker").val();
                            data.ModelName = $("#searchCatModel").val();
                            data.ModelCode = $('#searchCatName').val();
                            data.SubCatMaker = $('#searchSubCat').val();
                            data.SubModelName = $('#searchSubCatName').val();
                            data.SearchTable = $('#searchTable').val();
                            data.SubGroup = $('#searchSubGroup').val();
                            data.Brand = $('#searchBrand').val();
                        },
                        beforeSend: function () {

                        },
                        complete: function (result) {
                            if(result.responseJSON.recordsTotal === 0){
                                swal({
                                    title: '<?php echo get_lng($_SESSION["lng"], "E0042")/* No Data Found....Please Try Again ! */; ?>', //Message
                                    buttons: "<?php echo get_lng($_SESSION["lng"], "L0372")/* OK */; ?>"
                                });
                                UpdateSideDetail();
                            }
                            $("tr td.toSide").on('click', function () {
                                UpdateSideDetail(this);
                            });
                            $("tr td.toSide").first().trigger('click');
                            $(".touchspinQuantity").TouchSpin({
                                buttondown_class: "btn btn-default btn-xs",
                                buttonup_class: "btn btn-default btn-xs"
                            });
                            $(".touchspinQuantity").on('change', function () {
                                var intervalStep = $(this).data('bts-step');
                                var numberCart = parseInt($(this).val()) || 0;
                                if ((numberCart % intervalStep) !== 0 || numberCart === 0) {
                                    swal({
                                        title: '<?php echo get_lng($_SESSION["lng"], "E0040")/* Order Not in Lot Size!, Lot Size={lotsize} */; ?>'.replace('{lotsize}', intervalStep), //Message
                                        buttons: "<?php echo get_lng($_SESSION["lng"], "L0372")/* OK */; ?>"
                                    });
                                }

                                if (numberCart === 0) {
                                    $(this).val(0);
                                }
                            });
                            $(".buttonAddToCart").on('click', function () {
                                var quantity = $('#quantity' + $(this).val()).val();
                                var id = $(this).data('id');
                               // alert("quantity" + quantity);
                                $.ajax({
                                    url: "cataloguemain/ctcSubmitAddToCartAjax.php",
                                    type: 'post',
                                    data: {ID: id, Quantity: quantity},
                                    success: function (response) {
                                        var decodeJson = JSON.parse(response);
                                        if (decodeJson.errorCode === '0000') {
                                            /*ChangeCode-Comment by CTC Sippavit 30/09/2020 */
                                            //swal({
                                            //    title: '<?php echo get_lng($_SESSION["lng"], "L0366")/* Add to cart success */; ?>', //Message
                                            //    buttons: "<?php echo get_lng($_SESSION["lng"], "L0372")/* OK */; ?>"
                                            //});
                                        } else {
                                            swal({
                                                title: '<?php echo get_lng($_SESSION["lng"], "E0042")/* No Data Found....Please Try Again ! */; ?>', //Message
                                                buttons: "<?php echo get_lng($_SESSION["lng"], "L0372")/* OK */; ?>"
                                            });
                                        }
                                        ReloadCartItemBadge();
                                    }
                                });
                            });
                            $('.button-datail').on('click', function () {
                                var edit_id = $(this).val();
                                var lotSize = $(this).data('lot-size');
                                $.ajax({
                                    url: "_cata_details_modal.php",
                                    type: 'post',
                                    data: {edit_id: edit_id, lotsize: lotSize},
                                    success: function (data) {
                                        $('#modalDetail .modal-body').empty();
                                        $('#modalDetail .modal-body').html(data);
                                        $(".touchspinQuantitySub").TouchSpin({
                                            buttondown_class: "btn btn-default btn-xs",
                                            buttonup_class: "btn btn-default btn-xs"
                                        });
                                        $(".touchspinQuantitySub").on('change', function () {
                                            var intervalStep = $(this).data('bts-step');
                                            var numberCart = parseInt($(this).val()) || 0;
                                            if ((numberCart % intervalStep) !== 0 || numberCart === 0) {
                                                swal({
                                                    title: '<?php echo get_lng($_SESSION["lng"], "E0040")/* Order Not in Lot Size!, Lot Size={lotsize} */; ?>'.replace('{lotsize}', intervalStep), //Message
                                                    buttons: "<?php echo get_lng($_SESSION["lng"], "L0372")/* OK */; ?>"
                                                });
                                            }

                                            if (numberCart === 0) {
                                                $(this).val(0);
                                            }
                                        });
                                        $(".buttonAddToCartSub").on('click', function () {
                                            var quantity = $('#quantitySub' + $(this).val()).val();
                                            var id = $(this).data('id');
                                            $.ajax({
                                                url: "cataloguemain/ctcSubmitAddToCartAjax.php",
                                                type: 'post',
                                                data: {ID: id, Quantity: quantity},
                                                success: function (response) {
                                                    var decodeJson = JSON.parse(response);
                                                    if (decodeJson.errorCode === '0000') {
                                                        /*ChangeCode-Comment by CTC Sippavit 30/09/2020 */
                                                        //swal({
                                                        //    title: '<?php echo get_lng($_SESSION["lng"], "L0366")/* Add to cart success */; ?>', //Message
                                                        //    buttons: "<?php echo get_lng($_SESSION["lng"], "L0372")/* OK */; ?>"
                                                        //});
                                                    } else {
                                                        swal({
                                                            title: '<?php echo get_lng($_SESSION["lng"], "E0042")/* No Data Found....Please Try Again ! */; ?>', //Message
                                                            buttons: "<?php echo get_lng($_SESSION["lng"], "L0372")/* OK */; ?>"
                                                        });
                                                    }
                                                    ReloadCartItemBadge();
                                                }
                                            });
                                        });
                                        $('#modalDetail').modal('show');
                                    }
                                });
                            });
                            if ($('.dataTables_scrollBody table tbody tr td').length > 1) {
                                $('.dataTables_scrollBody table tbody tr td:last-child').empty();
                            }
                            //table.columns.adjust();
                        },
                        error: function (xhr, error, thrown) {
                        }
                    },
                    "columns": [
                        {"data": "ID"},
                        {"data": "CarMaker"},
                        {"data": "ModelName"},
                        {"data": "VinCode"},
                        {"data": "ModelCode"},
                        {"data": "EngineCode"},
                        {"data": "GenuinePartNo"},
                        {"data": "PartNo"},
                        {"data": "CGPartNo"},
                        //{"data": "Picture"},
                        {"data": "BrandName"},
                        {"data": "PartName"},
                        {"data": "OrderPartNo"},
                        {"data": "Detail"},
                        {"data": "STDPrice"},
                        {"data": "LotSize"},
                        {"data": "StockQty"},
                        {"data": "Quantity"}
                    ],
                    'columnDefs': [
                        {"className": "text-center", "targets": [11,15]},
                        {"className": "text-center toSide", "targets": [1,2,3,4,5,6,7,8,9,10,12,13,14]},
                        {"orderable": false, "targets": [3,4,5,12,13,14,15,16]},
                        {"className": "hidden", "targets": [0]},
                        {width: 10, targets: 0},//ID
                        {width: 50, targets: 1},//car marker
                        {width: 50, targets: 2},//model name
                        {width: 110, targets: 3},//vincode
                        {width: 50, targets: 4},//model code
                        {width: 60, targets: 5},//en code
                        {width: 90, targets: 6},//ge part
                        {width: 90, targets: 7},//den part
                       // {width: 60, targets: 8},
                        {width: 90, targets: 8},//cg
                        {width: 20, targets: 9},//brand
                        {width: 100, targets: 10},//part name
                        {width: 80, targets: 11},//order part
                        {width: 40, targets: 12},//detail
                        {width: 50, targets: 13},//std price
                        {width: 40, targets: 14},//lotsize
                        {width: 40, targets: 15},//stock
                        {width: 100, targets: 16},//Add to cart
                    ]
                });
            });
        </script> 
    </head>
    <body>
        <?php ctc_get_logo_new(); ?> <!-- add by CTC -->
        <div id="mainNav">
            <?php
            $_GET['selection'] = "main";
            include("navhoriz.php");
            ?>
        </div> 
        <div id="isi"> 
            <div class="col-md-12 margin-bottom-md" style="padding-right: 40px;">
                <div class="row">
                    <div class="col-md-6 arial21redbold">
                        <img src="../images/cata.png" width="17" height="15">&nbsp; <?php echo get_lng($_SESSION["lng"], "L0325")/* Part Calalogue */; ?>
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
                            <?php echo get_lng($_SESSION["lng"], "L0337")/* Customer Number */; ?>&nbsp;&nbsp;:&nbsp;&nbsp;<?php echo $cusno ?>
                        </div>
                    </div>
                    <div class="col-md-9 col-sm-6">
                        <div class="padding-top-xs">
                            <?php echo get_lng($_SESSION["lng"], "L0338")/* Customer Name */; ?>&nbsp;&nbsp;:&nbsp;&nbsp;<?php echo $cusnm ?>
                        </div>
                    </div>
                </div>
                <div class="row arial11black">
                    <div class="col-md-5 margin-top-md">
                        <div class="col-md-12 custom-box bg-skyblue">
                            <input type="hidden" id="searchCatMaker" />
                            <input type="hidden" id="searchCatModel" />
                            <input type="hidden" id="searchCatName" />
                            <input type="hidden" id="searchSubCat" />
                            <input type="hidden" id="searchBrand" />
                            <input type="hidden" id="searchSubCatName" />
                            <input type="hidden" id="searchSubGroup" />
                            <div class="row margin-top-sm">
                                <div class="col-md-12">
                                    <div class="row margin-bottom-xs">
                                        <div class="col-md-8">  
                                            <select class="form-control input-xs" id="cbocatmaker" name="cbocatmaker">
                                                <option disabled="disabled" selected="selected"><?php echo get_lng($_SESSION["lng"], "L0340")/* Select Maker */; ?></option>
                                            </select>
                                        </div>
                                        <div class="col-md-4">                                           
                                            <button type="button" class="btn btn-success btn-sm btn-w-m" id="buttonSearch" title="<?php echo get_lng($_SESSION["lng"], "L0427")/* Search */; ?>"><span class="glyphicon glyphicon-search" aria-hidden="true"></span></button>
                                        </div>
                                    </div>
                                    <div class="row margin-bottom-xs">
                                        <div class="col-md-8">
                                            <select class="form-control input-xs" id="cbocatmodel" name="cbocatmodel">
                                                <option disabled="disabled" selected="selected"><?php echo get_lng($_SESSION["lng"], "L0341")/* Select Models */; ?></option>
                                            </select>
                                        </div>
                                        <div class="col-md-4">                                           
                                            <button type="button" class="btn btn-default btn-sm btn-w-m" id="buttonClear" title="<?php echo get_lng($_SESSION["lng"], "L0428")/* Reset */; ?>"><span class="glyphicon glyphicon-trash" aria-hidden="true"></span></button>
                                        </div>
                                    </div>
                                    <div class="row margin-bottom-xs">
                                        <div class="col-md-8">
                                            <select class="form-control input-xs" id="cbocatname" name="cbocatname">
                                                <option disabled="disabled" selected="selected"><?php echo get_lng($_SESSION["lng"], "L0342")/* Select Type */; ?></option>
                                            </select>
                                        </div>
                                        <div class="col-md-4"></div>
                                    </div>
                                    <div class="row margin-bottom-xs">
                                        <div class="col-md-8">
                                            <select class="form-control input-xs" id="cbocatbrand" name="cbocatbrand">
                                                <option disabled="disabled" selected="selected"><?php echo get_lng($_SESSION["lng"], "L0440")/* Select Brand */; ?></option>
                                            </select>
                                        </div>
                                    </div>     
                                </div>
                            </div>
                            <div class="row margin-bottom-sm margin-top-sm">
                                <div class="col-md-12 arial11blackbold">
                                    <p><?php echo get_lng($_SESSION["lng"], "L0343")/* Search by brands and product groups */; ?></p>
                                </div>
                                <div class="col-md-12">
                                    <div class="row">
                                        <div class="col-md-4">
                                            <select class="form-control input-xs" id="cbosubcatmaker" name="cbosubcatmaker">
                                                <option value="0" disabled="disabled" selected="selected"><?php echo get_lng($_SESSION["lng"], "L0344")/* Search by.. */; ?></option>
                                                <option value="1"><?php echo get_lng($_SESSION["lng"], "L0424")/* DENSO P/NO */; ?></option>
                                                <option value="2"><?php echo get_lng($_SESSION["lng"], "L0347")/* Car Maker */; ?></option>
                                                <option value="3"><?php echo get_lng($_SESSION["lng"], "L0348")/* Model Name */; ?></option>
                                                <option value="4"><?php echo get_lng($_SESSION["lng"], "L0349")/* Model Code */; ?></option>
                                                <option value="5"><?php echo get_lng($_SESSION["lng"], "L0423")/* Genuine P/NO */; ?></option>
                                                <option value="6"><?php echo get_lng($_SESSION["lng"], "L0425")/* CG P/NO */; ?></option>
                                                <option value="7"><?php echo get_lng($_SESSION["lng"], "L0360")/* Part Name */; ?></option>
                                            </select>
                                        </div>
                                        <div class="col-md-4">
                                            <input type="text" class="form-control input-xs" id="submodelname" name="submodelname" placeholder="<?php echo get_lng($_SESSION["lng"], "L0345")/* Type to Search */; ?>" />
                                        </div>
                                        <div class="col-md-4">                                           
                                            <button type="button" class="btn btn-success btn-sm btn-w-m" id="buttonSubSearch" title="<?php echo get_lng($_SESSION["lng"], "L0427")/* Search */; ?>"><span class="glyphicon glyphicon-search" aria-hidden="true"></span></button>
                                        </div>
                                    </div>
                                </div>
                            </div>                            
                        </div>
                    </div>
                    <div class="col-md-7 margin-top-md" id="slideShowAnnounce" style="padding-right: 0px;"></div>
                </div>
                <div class="row margin-top-sm">
                    <div class="col-md-2 padding-right-xs">
                        <div class="list-group custom-box" id="groupModel" style="max-height: 555px; overflow: auto;"></div>
                    </div>
                    <div class="col-md-10 padding-left-xs padding-right-xs">
                        <div class="row">
                            <div class="col-md-12 form-inline">
                                <div class="input-group">
                                    <input type="text" class="form-control input-xs" id="searchTable" name="searchTable"  onkeyup="searchKey()"/>
                                    <span class="input-group-addon input-xs">
                                        <label for="searchTable"><span class="glyphicon glyphicon-search"></span></label>
                                    </span>
                                </div>
                                <button type="button" class="btn btn-maroon btn-xs arial11white pull-right" id="ConvExcel" title="<?php echo get_lng($_SESSION["lng"], "L0346")/* Export to XLS */; ?>"><span class="glyphicon glyphicon-save-file" aria-hidden="true"></span> <?php echo get_lng($_SESSION["lng"], "L0346")/* Export to XLS */; ?></button>
                            </div>
                            <div class="col-md-12">
                                <table id="tablecatalog" class="table table-bordered table-hover display" style="overflow-x: auto;" cellspacing="0">
                                    <thead>
                                        <tr class="bg-maroon">
                                            <th class="hidden"></th>
                                            <th class="text-center"><?php echo get_lng($_SESSION["lng"], "L0347")/* Car Maker */; ?></th>
                                            <th class="text-center"><?php echo get_lng($_SESSION["lng"], "L0348")/* Model Name */; ?></th>
                                            <th class="text-center"><?php echo get_lng($_SESSION["lng"], "L0446")/* Vin Code */; ?></th>
                                            <th class="text-center"><?php echo get_lng($_SESSION["lng"], "L0349")/* Model Code */; ?></th>
                                            <th class="text-center"><?php echo get_lng($_SESSION["lng"], "L0420")/* Engine Code */; ?></th>
                                            <th class="text-center"><?php echo get_lng($_SESSION["lng"], "L0423")/* Genuine P/NO */; ?></th>
                                            <th class="text-center"><?php echo get_lng($_SESSION["lng"], "L0424")/* DENSO P/NO */; ?></th>
                                            <th class="text-center"><?php echo get_lng($_SESSION["lng"], "L0425")/* CG P/NO */; ?></th>
                                           <!-- <th class="text-center"><?php echo get_lng($_SESSION["lng"], "L0363")/* Part Picture */; ?></th>-->
                                            <th class="text-center"><?php echo get_lng($_SESSION["lng"], "L0445")/* Brand */; ?></th>
                                            <th class="text-center" ><?php echo get_lng($_SESSION["lng"], "L0360")/* Part Name */; ?></th>
                                            <th class="text-center"><?php echo get_lng($_SESSION["lng"], "L0426")/* Order P/NO */; ?></th>
                                            <th class="text-center"><?php echo get_lng($_SESSION["lng"], "L0365")/* Details */; ?></th>
                                            <th class="text-center"><?php echo get_lng($_SESSION["lng"], "L0448")//* Std.Price(shipto) */; ?></th>
                                            <th class="text-center"><?php echo get_lng($_SESSION["lng"], "L0361")/* Lot Size */; ?></th>
                                            <th class="text-center"><?php echo get_lng($_SESSION["lng"], "L0447")/* Stock */; ?></th>
                                            <th class="text-center" style="width:100px!important"><?php echo get_lng($_SESSION["lng"], "L0364")/* Add to cart */; ?></th>
                                        </tr>
                                    </thead>
                                </table>
                            </div>
                        </div>
                    </div>
                   <!-- <div class="col-md-2 custom-box padding-left-xs" id="sideDetail" style="min-height: 555px; background-color: #f9f9f9;"></div>-->
                </div>

                <div class="row margin-top-sm">
                    <div class="col-md-2 padding-right-xs">
                    </div>
                    <div class="col-md-10 padding-left-xs padding-right-xs">
                        <div id="sideDetail" style="min-height:180px;background-color: #f9f9f9;">
                    </div>
                </div>
            </div>
        </div>
        <div class="modal" id="modalDetail" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-body"></div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal" title="<?php echo get_lng($_SESSION["lng"], "L0374")/* Close */; ?>"><?php echo get_lng($_SESSION["lng"], "L0374")/* Close */; ?></button>
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