<?php /* Create by CTC */ ?>
<?php session_start() ?>
<?php
require_once('../../core/ctc_init.php');
require_once('../../core/ctc_timezone.php');
require_once('../../language/Lang_Lib.php');
require_once('../../core/ctc_permission.php');

ctc_checkuser_permission('../login.php');

$cusno = ctc_get_session_cusno();
$cusnm = ctc_get_session_cusnm();
$supno=$_SESSION['supno'];
?>
<html>
    <head>
        <title>Denso Ordering System</title>
        <meta http-equiv="X-UA-Compatible" content="IE=9, IE=10, IE=11, IE=EDGE" /> 

        <link rel="stylesheet" type="text/css" href="../css/dnia.css">
        <link rel="stylesheet" type="text/css" href="../admin/bootstrap3.3.7/css/bootstrap.min.css">
        <link rel="stylesheet" type="text/css" href="../css/custom-bootstrap.css">
        <link rel="stylesheet" type="text/css" href="../css/demos.css">
        <link rel="stylesheet" type="text/css" href="../admin/Carousel/carousel.css">
        <link rel="stylesheet" type="text/css" href="../admin/DataTables/dataTables.bootstrap.min.css">
        <link rel="stylesheet" type="text/css" href="../admin/DataTables/fixedColumns.bootstrap.min.css">
        <link rel="stylesheet" type="text/css" href="../admin/bootstrap-touchspin/jquery.bootstrap-touchspin.min.css">
        <link rel="stylesheet" type="text/css" href="../admin/lightbox2-2.11.3/css/lightbox.min.css">
        <link rel="stylesheet" type="text/css" href="../admin/sweetalert/custom-sweetalert-ie9.css">
        <link rel="stylesheet" type="text/css" href="../admin/sweetalert/custom-sweetalert.css">
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

            .mySlides img{
                object-fit: contain;
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

        <script type="text/javascript" src="../lib/jquery-3.5.1.min.js"></script>
        <script type="text/javascript" src="../admin/DataTables/datatables.min.js"></script>
        <script type="text/javascript" src="../admin/DataTables/dataTables.bootstrap.min.js"></script>
        <script type="text/javascript" src="../admin/DataTables/dataTables.fixedColumns.min.js"></script>
        <script type="text/javascript" src="../admin/DataTables/dataTables.buttons.min.js"></script>
        <script type="text/javascript" src="../admin/bootstrap3.3.7/js/bootstrap.min.js"></script>
        <script type="text/javascript" src="../admin/sweetalert/sweetalert.min.js"></script>
        <script type="text/javascript" src="../admin/sweetalert/classList.min.js"></script>
        <script type="text/javascript" src="../admin/promise-polyfill/promise-polyfill.js"></script>
        <script type="text/javascript" src="../admin/lightbox2-2.11.3/js/lightbox.min.js"></script>
        <script type="text/javascript" src="../admin/Carousel/carousel.js"></script>
        <script type="text/javascript" src="../admin/bootstrap-touchspin/jquery.bootstrap-touchspin.min.js"></script>
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
                    var Brand = $("#searchCatBrand").val();
                    var SearchTable = $('#searchTable').val();
                    
                    url = 'cataloguemain/cata_gettblsupcatalogueXLSX.php?CatMaker=' + CatMaker + '&ModelName=' + ModelName
                            + '&ModelCode=' + ModelCode + '&SubCatMaker=' + SubCatMaker + '&SubModelName=' + SubModelName
                            + '&Brand=' + Brand+ '&SearchTable=' +SearchTable,
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
                            console.log(response);
                            var decodeJson = JSON.parse(response);
                            $("#cbocatbrand").empty();
                            $("#cbocatbrand").append('<option value="0" disabled="disabled" selected="selected"><?php echo get_lng($_SESSION["lng"], "L0440")/* Select Brand */; ?></option>');
                            $.each(decodeJson, function (key, value)
                            {
                                $("#cbocatbrand").append('<option value="' + value.brand + '">' + value.brand + '</option>');
                            });
                        }
                    })
                }

                function ResetSearch() {
                    $('#searchCatMaker').val('');
                    $('#searchCatModel').val('');
                    $('#searchCatBrand').val('');
                    $('#searchCatName').val('');
                    $('#searchSubCat').val('');
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
                            Brand: $("#searchCatBrand").val(),
                            ModelCode: $('#searchCatName').val(),
                            SubCatMaker: $('#searchSubCat').val(),
                            SubModelName: $('#searchSubCatName').val()
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
                                        + '<img src="../sup_annaimages/' + item.PrtPic + '" style="width:100%; max-height: 153px;">'
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
                UpdateSubCategory();
                UpdateBrandList();
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
                    $('#searchCatBrand').val($('#cbocatbrand').val());
                    $('#searchCatName').val($('#cbocatname').val());
                    $('#cbosubcatmaker').val('0');
                    $('#submodelname').val('');
                    table.ajax.reload();
                    UpdateSubCategory();
                });
                $('#buttonSubSearch').on('click', function () {
                    var cbosubcatmaker = $('#cbosubcatmaker').val();
                    var submodelname = $('#submodelname').val();
                    if (cbosubcatmaker !== null && submodelname !== null && submodelname !== '') {
                        ResetSearch();
                        $('#searchSubCat').val(cbosubcatmaker);
                        $('#searchSubCatName').val(submodelname);
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

                function UpdateSideDetail(box) {
                    var edit_id = $($(box).parent()).find('.button-datail').val()
                    var lotSize = $($(box).parent()).find('.button-datail').data('lot-size');
                    var first = $(box).parents("tr").find("td:first").text();
                    var cusno = "";
                    if (edit_id === undefined) {
                        var getid = first.split("|");
                        edit_id = getid[0];
                        cusno = getid[1];
                    }
                    else{
                        cusno = $($(box).parent()).find('.button-datail').data('cusno');
                       // $(box).data('data-cusno');
                    }
                    //alert(edit_id + " > " + cusno);
                    if (edit_id === undefined) {
                        edit_id = first;
                    }
                    if (currentSideBar !== edit_id +''+cusno) {
                        currentSideBar =  edit_id +''+cusno;
                        $.ajax({
                            url: "cataloguemain/_cata_supdetails_sidebar.php",
                            type: 'post',
                            data: {edit_id: edit_id, lotsize: lotSize ,cusno :cusno},
                            success: function (data) {
                                //console.log(box)
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
                        leftColumns:  3,
                        rightColumns: 8,
                        heightMatch: 'auto'
                    },
                    language: {
                        zeroRecords: 'Data not found', //Message
                        emptyTable: 'Data not found', //Message
                        paginate: {
                            previous: '&nbsp;<span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span>',
                            next: '&nbsp;<span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span>'
                        }
                    },
                    ajax: {
                        url: 'cataloguemain/ctcGetCalalogueTableAjax.php',
                        dataType: "json",
                        type: "POST",
                        data: function (data) {
                            data.CatMaker = $("#searchCatMaker").val();
                            data.ModelName = $("#searchCatModel").val();
                            data.BrandName = $("#searchCatBrand").val();
                            data.ModelCode = $('#searchCatName').val();
                            data.SubCatMaker = $('#searchSubCat').val();
                            data.SubModelName = $('#searchSubCatName').val();
                            data.SearchTable = $('#searchTable').val();
                            data.SubGroup = $('#searchSubGroup').val();
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
                            $('.button-datail').on('click', function () {
                                var edit_id = $(this).val();
                                var lotSize = $(this).data('lot-size');
                                var cusno = $(this).data('cusno');
                                $.ajax({
                                    url: "cataloguemain/_cata_supdetails_modal.php",
                                    type: 'post',
                                    data: {edit_id: edit_id, lotsize: lotSize,cusno :cusno},
                                    success: function (data) {
                                        $('#modalDetail .modal-body').empty();
                                        $('#modalDetail .modal-body').html(data);
                                        $('#modalDetail').modal('show');
                                    }
                                });
                            });


                            $('.button-edit').on('click', function () {
                                var edit_id = $(this).val();
                                swal({
                                    title: '<?php echo get_lng($_SESSION["lng"], "L0367")/* Confirm to Edit? */; ?>', //Message
                                    buttons: ["<?php echo get_lng($_SESSION["lng"], "L0373")/* Cancel */; ?>", "<?php echo get_lng($_SESSION["lng"], "L0372")/* OK */; ?>"],
                                    dangerMode: true
                                }).then(function (result) {
                                    if (result) {
                                        window.location.href = 'cata_editform.php?edit_id=' + edit_id;
                                    }
                                })
                            });

                            $('.button-delete').on('click', function () {
                                var delete_id = $(this).val();
                                swal({
                                    title: '<?php echo get_lng($_SESSION["lng"], "L0368")/* Comfirm to Delete? */; ?>', //Message
                                    buttons: ["<?php echo get_lng($_SESSION["lng"], "L0373")/* Cancel */; ?>", "<?php echo get_lng($_SESSION["lng"], "L0372")/* OK */; ?>"],
                                    dangerMode: true
                                }).then(function (result) {
                                    if (result) {
                                        $.ajax({
                                            url: "cataloguemain/cata_deleteform.php",
                                            type: 'post',
                                            data: {Delete_id: delete_id},
                                            success: function (response) {
                                                swal({
                                                    title: 'Delete success', //Message
                                                    buttons: "<?php echo get_lng($_SESSION["lng"], "L0372")/* OK */; ?>"
                                                }).then(function (result2) {
                                                    UpdateSubCategory();
                                                    UpdateSideDetail();
                                                    $('#searchSubGroup').val('');
                                                    table.ajax.reload();
                                                });
                                            }
                                        });
                                    }
                                });
                            });

                            if ($('.dataTables_scrollBody table tbody tr td').length > 1) {
                                $('.dataTables_scrollBody table tbody tr td:last-child').empty();
                            }
                            table.columns.adjust();
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
                       // {"data": "Picture"},
                        {"data": "BrandName"},
                        {"data": "PartName"},
                        {"data": "OrderPartNo"},
                        {"data": "Detail"},
                        {"data": "STDPrice"},
                        {"data": "LotSize"},
                        {"data": "StockQty"},
                        {"data": "Action"}
                    ],
                    'columnDefs': [
                        {"className": "text-center", "targets": [11,15]},
                        {"className": "text-center toSide", "targets": [1,2,3,4,5,6,7,8,9,10,12,13,14]},
                        {"orderable": false, "targets": [3,4,5,11,12,13,14,15]},
                        {"className": "hidden", "targets": [0]},
                        {width: 10, targets: 0},
                        {width: 50, targets: 1},//CarMaker
                        {width: 60, targets: 2},//ModelName
                        {width: 70, targets: 3},//Vincode
                        {width: 50, targets: 4},//ModelCode
                        {width: 50, targets: 5},//EngineCode
                        {width: 70, targets: 6},//GenuinePartNo
                        {width: 70, targets: 7},//Supno
                        //{width: 40, targets: 8},
                        {width: 50, targets: 8},//Brand
                        {width: 80, targets: 9},//PartName
                        {width: 70, targets: 10},//OrderPartNo
                        {width: 40, targets: 11},//Detail
                        {width: 40, targets: 12},//StdPrice
                        {width: 40, targets: 13},//LotSize
                        {width: 50, targets: 14},//Stock
                        {width: 70, targets: 15}//Action
                    ]
                });
         });
        </script> 
    </head>
    <body>
        <?php require_once('../../core/ctc_cookie.php');?>
        <?php ctc_get_logo_new(); ?>
        <div id="mainNav">
        <?php 
			  	$_GET['step']="1";
				include("supnavhoriz.php");
			?>
        </div> 

        <?
		  require('../db/conn.inc');
          $comp = ctc_get_session_comp();

		//Supplier
		$query="select * from supmas where Owner_Comp='$comp' and supno='$supno'  ";
		$sql=mysqli_query($msqlcon,$query);	
		//echo $query;
		while($hasil = mysqli_fetch_array ($sql)){
			
			$supno=$hasil['supno'];	
			$supnm=$hasil['supnm'];
			$suplogo= $hasil['logo'];
			$inpsupcode= $supno;	
			$inpsupname= $supnm;		
			
		}
        ?>
        

        <div id="isi"> 
        <table width="97%" border="0" cellspacing="0" cellpadding="0" align="center">
        <tr class="arial11blackbold" style="vertical-align: top;">
                <td width="3%"><img src="../images/calendar.gif" width="16" height="15"></td>
                <td width="10%" class="arial21redbold"><?php echo get_lng($_SESSION["lng"], "M007");?></td>
                <td width="10%">&nbsp;</td>
                <td width="10%">&nbsp;</td>
                <td width="20%">&nbsp;</td>
                <td rowspan="3" align="right" width="47%"><img src='<?php echo "../sup_logo/".$suplogo; ?>'  height="80" width="200" /></td>
            </tr>
			<tr class="arial11blackbold">
				<td colspan="2">
					<span class="arial12BoldGrey"><?php echo get_lng($_SESSION["lng"], "L0451");?></span>
					<span class="arial12Bold">:</span>
				</td>
				<td>
					<span class="arial12Bold"><? echo $supno?></span>	
				</td>
				<td>
					<span class="arial12BoldGrey"><?php echo get_lng($_SESSION["lng"], "L0452");?></span>
					<span class="arial12Bold">:</span> 
				</td>
                <td>
					<span class="arial12Bold"align="left"><? echo $supnm?></span>	
				</td>
       	 	</tr>
            <tr class="arial11blackbold">
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
            </tr>
        </table>    
            <div class="col-md-12 margin-bottom-md" style="padding-right: 40px;">
                <div class="row arial11black">
                    <div class="col-md-5 margin-top-md">
                        <div class="col-md-12 custom-box bg-skyblue">
                        
                            <input type="hidden" id="cbocatsup" value="<? echo $supno;?>"/>
                            <input type="hidden" id="searchCatMaker" />
                            <input type="hidden" id="searchCatModel" />
                            <input type="hidden" id="searchCatBrand" />
                            <input type="hidden" id="searchCatName" />
                            <input type="hidden" id="searchSubCat" />
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
                                                <option value="1"><?php echo get_lng($_SESSION["lng"], "L0455")/* Supplier P/NO */; ?></option>
                                                <option value="2"><?php echo get_lng($_SESSION["lng"], "L0347")/* Car Maker */; ?></option>
                                                <option value="3"><?php echo get_lng($_SESSION["lng"], "L0348")/* Model Name */; ?></option>
                                                <option value="4"><?php echo get_lng($_SESSION["lng"], "L0349")/* Model Code */; ?></option>
                                                <option value="5"><?php echo get_lng($_SESSION["lng"], "L0423")/* Genuine P/NO */; ?></option>
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
                            <div class="col-md-12">
                                <div class="col-md-6 form-inline">
                                <div class="input-group col-md-6">
                                    <input type="text" class="form-control input-xs" id="searchTable" name="searchTable"  onkeyup="searchKey()"/>
                                    <span class="input-group-addon input-xs">
                                        <label for="searchTable"><span class="glyphicon glyphicon-search"></span></label>
                                    </span>
                                </div>
                                </div>
                                <div class="col-md-6" align="right">
                                    <button type="button" class="btn btn-maroon btn-xs arial11white" onclick="window.location.href = 'supimportPartCate.php'" title="<?php echo get_lng($_SESSION["lng"], "L0371")/* Browe*/; ?>"><span class="glyphicon"></span> <?php echo get_lng($_SESSION["lng"], "L0172")/* Upload*/; ?> </button>
                                    <button type="button" class="btn btn-success btn-xs arial11white" onclick="window.location.href = 'supcata_addnew.php'" title="<?php echo get_lng($_SESSION["lng"], "L0371")/* Add New */; ?>"><span class="glyphicon glyphicon-plus"></span> <?php echo get_lng($_SESSION["lng"], "L0371")/* Add New */; ?> </button>
                                    <button type="button" class="btn btn-maroon btn-xs arial11white" id="ConvExcel" title="<?php echo get_lng($_SESSION["lng"], "L0346")/* Export to XLS */; ?>"><span class="glyphicon glyphicon-save-file" aria-hidden="true"></span> <?php echo get_lng($_SESSION["lng"], "L0346")/* Export to XLS */; ?></button>
                                </div>
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
                                            <th class="text-center"><?php echo get_lng($_SESSION["lng"], "L0453")/* Supplier Genuine  P/NO */; ?></th>
                                            <!--<th class="text-center"><?php echo get_lng($_SESSION["lng"], "L0363")/* Part Picture */; ?></th> -->
                                            <th class="text-center"><?php echo get_lng($_SESSION["lng"], "L0445")/* Brand */; ?></th>
                                            <th class="text-center"><?php echo get_lng($_SESSION["lng"], "L0360")/* Part Name */; ?></th>
                                            <th class="text-center"><?php echo get_lng($_SESSION["lng"], "L0426")/* Order P/NO */; ?></th>
                                            <th class="text-center"><?php echo get_lng($_SESSION["lng"], "L0365")/* Details */; ?></th>
                                            <th class="text-center"><?php echo get_lng($_SESSION["lng"], "L0448")//* Std.Price(shipto) */; ?></th>
                                            <th class="text-center"><?php echo get_lng($_SESSION["lng"], "L0361")/* Lot Size */; ?></th>
                                            <th class="text-center"><?php echo get_lng($_SESSION["lng"], "L0447")/* Stock */; ?></th>
                                            <th class="text-center"><?php echo get_lng($_SESSION["lng"], "L0135")/* Action*/;  ?></th>
                                        </tr>
                                    </thead>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row margin-top-sm">
                    <div class="col-md-2 padding-right-xs">
                    </div>
                    <div class="col-md-10 padding-left-xs padding-right-xs">
                        <div id="sideDetail" style="min-height: 180px; background-color: #f9f9f9;">
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