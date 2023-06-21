<?php 
session_start();
require_once('../language/Lang_Lib.php');
require_once('./../core/ctc_init.php'); // add by CTC

$comp = ctc_get_session_comp(); // add by CTC

//if (session_is_registered('cusno'))
ini_set('session.bug_compat_warn', 0);
ini_set('session.bug_compat_42', 0);
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
		$cusno =	$_SESSION['cusno'];
		$cusnm =	$_SESSION['cusnm'];
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
include('../language/conn.inc');
$tblname = $alias . "regimp_supplier";
$sql = "DESC " . $tblname;
//echo $tblname;
mysqli_query($msqlcon,$sql);
//echo $sql;
//echo mysqli_errno();
if ($msqlcon->errno == 1146) {
	$query2 = "CREATE TABLE " . $tblname . " (
	Owner_Comp varchar(3),
	CUST3 varchar(45),
	Corno varchar(20),
	orderdate varchar(8),
	duedate varchar(8),
	ordprd varchar(6),
	cusno varchar(8),
	partno varchar(15),
	partdes varchar(500),
	ordsts varchar(1),
	qty int(11),
	CurCD varchar(2),
	bprice decimal(18,4) NOT NULL,
	SGCurCD varchar(2),
	SGPrice decimal(18,8),
	impgrp varchar(3),
	DlrCurCd varchar(2),
	DlrPrice decimal(18,4),
	OECus varchar(1),
	Shipment varchar(1), 
	supno varchar(8),
	PRIMARY KEY  (corno, cusno, partno, ordsts,supno ,Owner_Comp)
	) ENGINE=MyISAM DEFAULT CHARSET=utf8;";
	mysqli_query($msqlcon,$query2);
} else {
	// check column Owner_Comp
	$sql_column = "SHOW COLUMNS FROM $tblname LIKE 'Owner_Comp'";
	$query_column = mysqli_query($msqlcon,$sql_column);
	if($query_column->num_rows ==0){
		$sql_alter = "ALTER TABLE $tblname
		ADD COLUMN Owner_Comp  varchar(3) NOT NULL FIRST ,
		DROP PRIMARY KEY,
		ADD PRIMARY KEY (corno, cusno, partno, ordsts,Owner_Comp, supno)";
		mysqli_query($msqlcon,$sql_alter);
	}

	$query2 = "delete from " . $tblname . " where Owner_Comp='$comp'";
	mysqli_query($msqlcon,$query2);
}

$imptable = $tblname;
//session_register['imptable'];
$_SESSION['imptable'] = $tblname;
// echo "session imptable =" . $_SESSION['imptable'];

$ordertype = '';
/*$action_del='';
	$ordno_del='';
	$cusno_del='';*/

require("crypt.php");
if (isset($_SERVER['REQUEST_URI'])) {
	$var = decode($_SERVER['REQUEST_URI']);
	$ordertype = trim($var['ordertype']);
	/*$action_del=trim($var['action']);
		$ordno_del=trim($var['ordno']);
		$cusno_del=trim($var['cusno']);
		$corno_del=trim($var['corno']);*/
}

if ($ordertype == 'Normal') {
	$ordertype = 'Normal';
	$_GET['selection'] = "main";
} else if ($ordertype == 'Urgent') {
	$_GET['selection'] = "urgentOrder";
} else if ($ordertype == 'Request') {
	$_GET['selection'] = "requestDueDate";
}

$query = "delete from " . $tblname . " where cusno ='" . $cusno . "' and Owner_Comp='$comp' ";
//echo $query;
mysqli_query($msqlcon,$query);
$table1 = str_replace("regimp", 'ordtmp', $tblname);
$query2 = "delete from " . $table1 . " where cusno ='" . $cusno . "' and Owner_Comp='$comp' ";
//echo $query2;
mysqli_query($msqlcon,$query2);

$sc003Qry = "SELECT max(yrmon) as maxyr FROM supsc003pr where Owner_Comp='$comp' ";
$scSql = mysqli_query($msqlcon,$sc003Qry);
if ($scArray = mysqli_fetch_array($scSql)) {
	$maxyr = $scArray['maxyr'];
}
?>

<html>

<head>
	<meta http-equiv="X-UA-Compatible" content="IE=9, IE=10, IE=11, IE=EDGE" />
	<!--18/05/2019 P.Pawan CTC-->
	<input type="hidden" id="maxyr" name="maxyr" value="<?php echo $maxyr; ?>">
	<title>Denso Ordering System</title>
	<link rel="stylesheet" type="text/css" href="css/dnia.css">
	<?php if ($ordertype == '' || $ordertype == 'Normal') { ?>
		<link rel="stylesheet" href="themes/ui-lightness/jquery-ui-green.css">
	<?php } else if ($ordertype == 'Urgent') { ?>
		<link rel="stylesheet" href="themes/ui-lightness/jquery-ui-red.css">
	<?php } else if ($ordertype == 'Request') { ?>
		<link rel="stylesheet" href="themes/ui-lightness/jquery-ui.css">
	<?php } ?>
    
    <link rel="stylesheet" type="text/css" href="admin/bootstrap3.3.7/css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="admin/Carousel/carousel.css">
	<style>
		img{
			vertical-align:top;
		}
	</style>
	<!--[if IE]>
<style type="text/css">
#twocolLeft{ padding-top: 0px; }
#twocolRight { zoom: 1; padding-top:10px; }
</style>
<![endif]-->
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
<!--	 <script src="lib/jquery.ui.effect.js"></script> -->
	<script src="lib/jquery.ui.datepicker.js"></script>

    <script type="text/javascript" src="lib/jquery-3.5.1.min.js"></script>
    <script type="text/javascript" src="admin/bootstrap3.3.7/js/bootstrap.min.js"></script>
    <script type="text/javascript" src="admin/Carousel/carousel.js"></script>
	<script>
		function getShipToAddressAjax(val) {
			document.getElementById("shipToAddress").innerHTML = '';
			var shipToCd = val + "";
			var cusno = '<? echo $cusno; ?>';
			$.ajax({
				type: 'POST',
				url: 'getShipToAddressAjax.php',
				async: false,
				data: {
					shipToCd: shipToCd,
					cusno: cusno
				},
				dataType: 'json',
				success: function(res) {
					var ship_to_nm = res['ship_to_nm'];
					var adrs1 = res['adrs1'];
					var adrs2 = res['adrs2'];
					var adrs3 = res['adrs3'];
					var comp_tel_no = res['comp_tel_no'];
					var pstl_cd = res['pstl_cd'];
					var shipToAddress = ship_to_nm + "<br>" + adrs1 + "<br>" + adrs2 + "<br>" + adrs3 + " ," + pstl_cd + "<br>" + comp_tel_no;
					document.getElementById("shipToAddress").innerHTML = shipToAddress;
				}
			});
		}

		$(function() {
			//commect for test
			//$.noConflict(true);
			//jQuery = window.jQuery.noConflict(true);
			 //03/10/2019 Prachaya inphum CTC --start--
			$("#file").change(function() {
				$.ajax({
					type: 'post',
					url: 'timeSev.php',

					success: function (data) {
					
						time = data;

					}
				});
			});
			
			$('#frmimport').submit(function() {
				// 03/10/2019 Prachaya inphum CTC --start
					timeObj1 = new Date();
					var hr = timeObj1.getHours();
					var mi = timeObj1.getMinutes(); 
					var se = timeObj1.getSeconds(); 
					var page ='<?php echo $_GET['selection']?>';  
					
				// 03/10/2019 Prachaya inphum CTC --end--
				if ($('#shipToCd').val() == '') {
					alert('Ship to should be filled!');
					return false;
				}
				if ($('#file').val() == '') {
					alert('<?php echo get_lng($_SESSION["lng"], "W0017"); ?>' /*'Please attach upload file!'*/ );
					return false;
				}
				else{
					var ext = $('#file').val().split('.');
			
					if(ext[1] != "xls"){
						alert('<?php echo get_lng($_SESSION["lng"], "E0079"); ?>'); /*"Error : Only excel file allowed,Please download correct template");*/
						return false;
					}  

				}

			})
			$("#shipToCd").change(function() {
				var oecus = $("#txtShpNoHidden").val().toUpperCase();
				if (oecus == 'Y') {
					$("#result_tr1").show();
				} else {
					$("#result_tr1").hide()
				}
			});

			var customerNo = "customerNo=" + <?php echo $cusno ?>;
			//alert(customerNo);
			$.ajax({
				type: 'POST',
				url: 'getShipToCodeAjax.php',
				async: false,
				data: customerNo,
				dataType: 'json',
				success: function(res) {
					if (res.length > 1) {
						$("#shipToCd").append("<option value='' selected>-----Please select ship code-----</option>");
					}
					$.each(res, function(key, value) {
						$('#shipToCd')
							.append($("<option></option>")
								.attr("value", value.shipCd)
								.text(value.shipCd));
					});
				}
			});
		
		
		})

		SupplierCarousel();
        function SupplierCarousel() {
            $.ajax({
                url: "supcataloguemain/ctcGetSupplierAjax.php",
                type: 'post',
                success: function (response) {

                    var decodeJson = JSON.parse(response);
                    $('#slideShowSupplier').empty();

                    var imageHtml = '<div class="carousel-inner" id="theCarousel">';
                    var dotButtonHtml = '';
                    $.each(decodeJson, function (index, item) {
                        if(index == 0){
                            imageHtml = imageHtml + '<div class="item active">';
                        }
                        else{
                            imageHtml = imageHtml + '<div class="item">';
                        }
                        imageHtml = imageHtml
                            + '<div class="col-xs-4">'
                            + '<img src="sup_logo/' + item.logo + '" class="img-responsive" style="width:200px; height: 80px; " alt="' + item.supno + '">'
                            + '</div>'
                            + '</div>';
                    });
                    imageHtml = imageHtml +'</div>';
                    var resultHtml = '<div class="carousel slide multi-item-carousel">'
                        + imageHtml
                        +'<a class="left carousel-control" href="#theCarousel" data-slide="prev"><i class="glyphicon glyphicon-chevron-left"></i></a>'
                        +'<a class="right carousel-control" href="#theCarousel" data-slide="next"><i class="glyphicon glyphicon-chevron-right"></i></a>'
                        + '</div>';
                    $('#slideShowSupplier').append(resultHtml);
                    setupslidesup();
                }
            });
        }

        function setupslidesup(){
            // Instantiate the Bootstrap carousel
            $('.multi-item-carousel').carousel({
                interval: false
            });

            // for every slide in carousel, copy the next slide's item in the slide.
            // Do the same for the next, next item.
            $('.multi-item-carousel .item').each(function(){
                var next = $(this).next();
                if (!next.length) {
                    next = $(this).siblings(':first');
                }
                next.children(':first-child').clone().appendTo($(this));
                if($('.multi-item-carousel .item').length >= 3){
                    if (next.next().length>0) {
                        next.next().children(':first-child').clone().appendTo($(this));
                    } else {
                        $(this).siblings(':first').children(':first-child').clone().appendTo($(this));
                    }
                }
            });

            $(".img-responsive").click(function () {
                var cbocatsup = $(this).attr("alt");
                //alert("oncilck" + cbocatsup);
            });
        }
	</script>

</head>

<body onload="getShipToAddressAjax(document.getElementById('shipToCd').value)">

	<?php ctc_get_logo() ?> <!-- add by CTC -->
	
	<div id="mainNav">
		<?
		//$_GET['selection']="main";
		include("navhoriz.php");

		?>


	</div>
	<div id="isi">

		<div id="twocolLeft">
			<?
				$_GET['current'] = "supcata_cataloguemain";
			
			    include("navUser.php");
				if ($ordertype == 'Normal') {
					$formtitle = get_lng($_SESSION["lng"], "L0164")/*'Normal Order Upload'*/;
					//$_GET['current'] = "main";
				} else if ($ordertype == 'Urgent') {
					$formtitle = get_lng($_SESSION["lng"], "L0291")/*'Urgent Order Upload'*/;
					//$_GET['current'] = "urgentOrder";
				} else if ($ordertype == 'Request') {
					$formtitle = get_lng($_SESSION["lng"], "L0290")/*'Requested Due Date Order Upload'*/;
					//$_GET['current'] = "requestDueDate";
				}
			?>


		</div>
		<div id="twocolRight">
			<form id="frmimport" method="post" enctype="multipart/form-data" action="supimport_new.php">
				<table width="97%" border="0" cellspacing="0" cellpadding="0">
					<tr class="arial11blackbold">
						<td>&nbsp;</td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
						<td colspan="4">
                        <div class="col-md-12" style="height:80">
                            <div class="carousel slide multi-item-carousel" id="theCarousel">
                                <div class="carousel-inner" id="slideShowSupplier"></div>
                            </div>
                        </div>
                        </td>
					</tr>
					<tr class="arial21redbold">
						<td><?php echo $formtitle ?></td>
						<td>&nbsp;</td>
						<td colspan="5"></td>
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
						<td>&nbsp;</td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
						<td></td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
					</tr>
					<tr class="arial11blackbold">
						<td width="22%"><?php echo get_lng($_SESSION["lng"], "L0165"); ?>
							<!--Customer Number-->
						</td>
						<td width="2%">:</td>
						<td width="26%"><? echo $cusno ?></td>
						<td width="4%"></td>
						<td width="20%"><?php echo get_lng($_SESSION["lng"], "L0168"); ?>
							<!--Customer Name-->
						</td>
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
						<td><?php echo get_lng($_SESSION["lng"], "L0166"); ?>
							<!--Ship To -->
						</td>
						<td>:</td>
						<td colspan="5" class="arial11blackbold">
							<?
							$qryshp = "SELECT cusmas.Cusno, cusmas.ESCA1, cusmas.ESCA2, cusmas.ESCA3, cusmas.OECus, cusrem.curcd, cusrem.remark FROM `cusmas` LEFT JOIN cusrem ON cusmas.cusno = cusrem.cusno and cusmas.Owner_Comp = cusrem.Owner_Comp  where  trim(cusmas.cust3) ='$cusno' and cusmas.Owner_Comp='$comp' order by cusmas.Cusno";
							$sqlshp = mysqli_query($msqlcon,$qryshp);
							$mcount = mysqli_num_rows($sqlshp);
							while ($hasil = mysqli_fetch_array($sqlshp)) {
								$bcusno = $hasil['Cusno'];
								$vremark = $hasil['remark'];
								$vcurcd = $hasil['curcd'];
								$voecus = $hasil['OECus'];
								if (strtoupper($voecus) != 'Y') {
									$voecus = 'N';
								}
								$gabung = $bcusno . ' - ' . $vremark . '  (' . $vcurcd . ')';
								echo '<input type="hidden" id="txtShpNoHidden" name="txtShpNoHidden" value="' . $voecus . '"/>';
								echo '<input type="hidden" id="txtShpNo" name="txtShpNo" value="' . $bcusno . '"/>';
							}
							?>
							<select name="shipToCd" id="shipToCd" style="width: 300px" onchange="getShipToAddressAjax(this.value)"></select>
						</td>
					</tr>

					<tr class="arial11blackbold">
						<td colspan="8">&nbsp;</td>
					</tr>
					<tr class="arial11blackbold">
						<td><span class="arial11blackbold"><?php echo get_lng($_SESSION["lng"], "L0311"); ?>
								<!--Ship To Address--></span></td>
						<td>:</td>
						<td colspan="5" class="arial11blackbold">
							<label id="shipToAddress"></label>
						</td>
					</tr>
					<tr class="arial11blackbold">
						<td>&nbsp;</td>
						<td>&nbsp;</td>
						<td colspan="5" class="arial11blackbold">&nbsp;</td>
					</tr>
					<tr class="arial11blackbold">
						<td><?php echo get_lng($_SESSION["lng"], "L0167"); ?>
							<!--Order Type-->
						</td>
						<td>:</td>
						<td><input type="text" name="ordertype" id="ordertype" readonly="true" class="arial11grey" value="<?php echo "Request";//echo $ordertype; ?>"></td>
						<td colspan="4" class="arial11blackbold">&nbsp;</td>
					</tr>
					<!--
					<?php if ($ordertype == 'Request') {
						require('getRequestDueDate.php');
						$requestDateArr = getRequestedDueDate();
						?>
						<tr class="arial11blackbold">
							<td>&nbsp;</td>
							<td>&nbsp;</td>
							<td colspan="5" class="arial11blackbold">&nbsp;</td>
						</tr>
						<tr class="arial11blackbold">
							<td><?php echo get_lng($_SESSION["lng"], "L0454"); //Shipping Day (ETD)?>
							</td>
							<td>:</td>
							<td colspan="5" class="arial11blackbold">
								<input name="requestDate" id="requestDate" type="text" size="12" maxlength="12" value="<?php echo  $requestDateArr[2]; ?>">
								<input name="requestDate2" id="requestDate2" type="hidden" size="12" maxlength="12" value="<?php echo  $requestDateArr[2]; ?>">
								<input name="dateChecking" id="dateChecking" type="hidden">
							</td>
						</tr>
					<?php } ?>
					-->
<!-- Zia Added Note --Stert -->
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
						<td><?php echo get_lng($_SESSION["lng"], "L0334"); ?></td>
						<td>:</td>
						<td colspan="5"><input type="textarea" rows="8" cols="50" name="txtnote" id="txtnote"  class="arial11blackbold" maxlength="100" size="80"></td>
					</tr>
<!-- Zia Added Note --End -->					
					


				</table>
				<table width="100%" border="0" cellspacing="0" cellpadding="0">
					<tr valign="middle" class="arial11">
						<th scope="col">&nbsp;</th>
						<th width="90" scope="col">&nbsp;</th>
						<th width="90" scope="col" align="right">&nbsp;</th>
					</tr>
					<tr height="5">
						<td colspan="5"></td>
					<tr>
				</table>

				<p class="arial21redbold">
					<?php echo get_lng($_SESSION["lng"], "L0169"); ?>
					<!--Please upload your excel file (.xls) - if you are using excel 2007 and up, you should
        convert to excel 2003 or above (xls format)
        :-->
				</p>

				<table width="50%" border="0" cellspacing="0" cellpadding="0">
					<tr valign="middle" class="arial11">
						<th scope="col" width="30%"><input name="file" id="file" type="file" size="50" height="20px"></th>
						<th scope="col" width="20%"><input name="upload" type="submit" style="margin-left:10px;" value="<?php echo get_lng($_SESSION["lng"], "L0172")/*Import*/; ?>"></th>
						
					</tr>
				</table>
                <!--<div class="row">
                    <div class="col-sm-3"><input name="file" id="file" type="file" size="20"></div>
                    <div class="col-sm-3"><input name="upload" type="submit" value="<?php echo get_lng($_SESSION["lng"], "L0172")/*Import*/; ?>"></div>
					<input name="file" id="file" type="file" size="20">
					<input name="upload" type="submit" value="<?php echo get_lng($_SESSION["lng"], "L0172")/*Import*/; ?>">
				</div>-->
				<?php echo get_lng($_SESSION["lng"], "L0170"); ?>
				<!--Download format excel here :--><a href="db/orderSup.xls" target="_blank"><img src="images/excel.jpg" width="16" height="16" border="0"></a>

			</form>
			
		<div id="footerMain1">
			<ul>
				<!-- disable by zia
     
          

	  -->
			</ul>

			<div id="footerDesc">

				<p>
					Copyright &copy; 2023 DENSO . All rights reserved

			</div>
		</div>
		</div>


</body>

<?php include('timecheck.php'); ?> <!--03/10/2019 Prachaya inphum CTC-->
</html>

