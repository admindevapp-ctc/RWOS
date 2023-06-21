<?php

session_start();
require_once('./../core/ctc_init.php'); // add by CTC

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
		$owner_comp = ctc_get_session_comp(); // add by CTC
	} else {
		echo "<script> document.location.href='../" . redir . "'; </script>";
	}
} else {
	header("Location:../login.php");
}

include('chklogin.php');
include "crypt.php";

?>

<html>

<head>
	<title>Denso Ordering System</title>
	<link rel="stylesheet" type="text/css" href="css/dnia.css">
	</style><!--[if IE]>
<style type="text/css"> 
#twocolLeft{ padding-top: 0px; }
#twocolRight { zoom: 1; padding-top:10px; }
</style>	
<![endif]-->

	<style type="text/css">
		#pagination a {
			list-style: none;
			margin-right: 5px;
			padding: 5px;
			color: #333;
			text-decoration: none;
			background-color: #F3F3F3;
			font-family: Verdana, Geneva, sans-serif;
			font-size: 10px;
		}

		#pagination a:hover {
			color: #FF0084;
			cursor: pointer;
		}

		#pagination a.current {
			list-style: none;
			margin-right: 5px;
			padding: 5px;
			color: #FFF;
			background-color: #000;
		}

		#pagination1 a {
			list-style: none;
			margin-right: 5px;
			padding: 5px;
			color: #333;
			text-decoration: none;
			background-color: #F3F3F3;
			font-family: Verdana, Geneva, sans-serif;
			font-size: 10px;
		}

		#pagination1 a:hover {
			color: #FF0084;
			cursor: pointer;
		}

		#pagination1 a.current {
			list-style: none;
			margin-right: 5px;
			padding: 5px;
			color: #FFF;
			background-color: #000;
		}
	</style>

	<link rel="stylesheet" href="themes/ui-lightness/jquery-ui.css">

	<script src="lib/jquery-1.4.2.min.js"></script>

	<script src="lib/jquery.ui.core.js"></script>
	<script src="lib/jquery.ui.widget.js"></script>
	<script src="lib/jquery.ui.mouse.js"></script>
	<script src="lib/jquery.ui.button.js"></script>
	<script src="lib/jquery.ui.draggable.js"></script>
	<script src="lib/jquery.ui.position.js"></script>
	<script src="lib/jquery.ui.resizable.js"></script>
	<script src="lib/jquery.ui.dialog.js"></script>
	<script src="lib/jquery.effects.core.js"></script>
	<script src="lib/jquery.ui.datepicker.js"></script>
	<script src="lib/jquery.ui.autocomplete.js"></script>
	<link rel="stylesheet" href="css/demos.css">
	<style>
		body {
			font-size: 62.5%;
		}

		label,
		input {
			display: block;
		}

		input.text {
			margin-bottom: 12px;
			width: 95%;
			padding: .4em;
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



		#data tr {
			display: none;
		}

		.page {
			margin-top: 50px;
		}

		#data {
			font-family: Arial, Helvetica, sans-serif;
			border-collapse: collapse;
			width: 100%;
		}

		#data td,
		#data th {
			border: 1px solid #ddd;
			padding: 8px;
		}

		#data tr:nth-child(even) {
			background-color: #f2f2f2;
		}

		#data tr:hover {
			background-color: #ddd;
		}

		#data th {
			#padding-top: 12px;
			#padding-bottom: 12px;
			#text-align: left;
			background-color: #f6a828;
			color: white;
		}
		
		.data td,
		.data th {
			border: 1px solid #ddd;
			padding: 8px;
		}

		.data tr:nth-child(even) {
			background-color: #f2f2f2;
		}

		.data tr:hover {
			background-color: #ddd;
		}

		.data th {
			#padding-top: 12px;
			#padding-bottom: 12px;
			#text-align: left;
			background-color: #f6a828;
			color: white;
		}

		#nav {
			margin-bottom: 1em;
			margin-top: 1em;
		}

		#nav a {
			font-size: 10px;
			margin-top: 22px;
			font-weight: 600;
			padding-top: 30px;
			padding-bottom: 10px;
			text-align: center;
			background-color: #e78f08;
			color: white;
			padding: 6px;

		}

		a:hover,
		a:visited,
		a:link,
		a:active {
			text-decoration: none;
		}
	</style>

	<script type="text/javascript">
		$(document).ready(function() {
			$.urlParam = function(name) {
				var results = new RegExp('[\?&]' + name + '=([^&#]*)').exec(window.location.href);
				if (results == null) {
					return null;
				} else {
					return decodeURI(results[1]);
				}
			}

			$('#dialog-form').dialog({
				autoOpen: false
			});
			$(".view").click(function() {

				pos = $(this).attr("id");

				console.log(pos);
				$("#dialog-form").attr("title", "Part Detail");
				//id='".$vcusno1."||".$vitnbr."||".$vitdsc."
				var xdata = pos.split("||");
				var xcusno1 = xdata[0];
				var xpartno = xdata[1];
				var xpartnm = xdata[2];
				var xcusgrp = xdata[3];
				$("#p_cusno").val(xcusno1);
				$.ajax({
					type: 'POST',
					url: 'aws_saledenso_partdetail.php',
					data: {
						vCusno1: xcusno1,
						vPartno: xpartno,
						vCusgrp: xcusgrp
					},
					success: function(data) {
						// alert(data);
						$("#p_partno").text(xpartno);
						$("#p_partname").text(xpartnm);
						//$("#part_detail").html(data);
						$("#part_detail").html(data);


						$('#data').after('<div id="nav" align="right"></div>');
						var rowsShown = 5;
						var rowsTotal = $('#data tbody tr').length;

						var numPages = rowsTotal / rowsShown;
						for (i = 0; i < numPages; i++) {
							var pageNum = i + 1;
							$('#nav').append('<a href="#" rel="' + i + '">' + pageNum + '</a> ');
						}
						$('#data thead tr').show();
						$('#data tbody tr').hide();
						$('#data tbody tr').slice(0, rowsShown).show();
						$('#nav a:first').addClass('active');
						$('#nav a').bind('click', function() {
							$('#nav a').removeClass('active');
							$(this).addClass('active');
							var currPage = $(this).attr('rel');
							var startItem = currPage * rowsShown;
							var endItem = startItem + rowsShown;
							$('#data tbody tr').css('opacity', '0.0').hide().slice(startItem, endItem).
							css('display', 'table-row').animate({
								opacity: 1
							}, 300);
						});

						$(this).dialog("close");
						$("#dialog-form").dialog({
							autoOpen: true,
							width: 600,
							modal: true,
							position: {
								my: "center",
								at: "center",
								of: $("body"),
								within: $("body")
							},
							buttons: {
								"Save change": function() {
									// view
									var i = 1;
									var insertdata = new Array();
									if ($('#checkall').is(":checked") == true) {
										if ($("#p_currencyall").val().length <= 0 || $("#p_priceall").val().length <= 0) {
											alert("Please key the value!");
											return false;
										} else {
											insertdata = [];
											var detail = new Array();
											detail.push($("#p_cusall").val(), $("#p_currencyall").val(), $("#p_priceall").val(), $("#p_cusno").val(), $("#p_partno").text(), $("#conditionall").is(":checked"));
											insertdata.push(detail);
										}
									} else {
										$("#data tbody tr").each(function() {
											//  if($(this).find("input").is(":checked") == true){
											var detail = new Array();
											//console.log($("#p_cusgroup" + i).val() + $("#p_currency" + i).val() + $("#p_price" + i).val() );
											detail.push($("#p_cusgroup" + i).val(), $("#p_currency" + i).val(), $("#p_price" + i).val(), $("#p_cusno").val(), $("#p_partno").text(), $("#p_condotion" + i).is(":checked"));
											insertdata.push(detail);
											// }
											i = i + 1;
										});
									}
									if (insertdata.length <= 0) {
										alert("Please select value!");
									}
									console.log(insertdata);
									$.ajax({
										type: 'POST',
										url: 'aws_saledenso_updprice.php',
										data: {
											"data": insertdata
										},
										success: function(response) {
											$("#dialog-form").dialog("close");
											window.location.reload();
											//  alert(response);
										}
									});
								},
								Cancel: function() {
									$("#dialog-form").dialog("close");
									// window.location.reload();
								}
							}
						});
					}
				});
			});


			$('#ConvExcel').click(function() {
				let s_cusno1 = $.urlParam('s_cusno');
				let s_product = $.urlParam('s_product');
				let s_partnumber = $.urlParam('s_partnumber');
				let s_condition = $.urlParam('s_condition');
				let s_partname = $.urlParam('s_partname');
				url = 'aws_saledenso_export.php?s_cusno=' + s_cusno1 +
					'&s_partnumber=' + s_partnumber + '&s_product=' + s_product +
					'&s_partname=' + s_partname + '&s_condition=' + s_condition;

				window.open(url);
			});

			$('#Importfile').click(function() {
				url = 'aws_saledenso_import.php';
				window.location.replace(url);
			});

			$('#checkall').click(function(event) {
				if (this.checked) {
					// Iterate each checkbox
					$(':checkbox').each(function() {
						this.checked = true;
						$(".p_cusgroup").attr("disabled", "disabled");
						$(".p_currency").attr("disabled", "disabled");
						$(".p_price").attr("disabled", "disabled");
						$(".chk_boxes").attr("disabled", "disabled");
						$(".p_condotion").attr("disabled", "disabled");
						$("#p_cusall").attr("disabled", "disabled");

						$('#p_currencyall').removeAttr('disabled');
						$('#p_priceall').removeAttr('disabled');
						$("#conditionall").removeAttr('disabled');
					});
				} else {
					$(':checkbox').each(function() {
						this.checked = false;
						$(".p_cusgroup").removeAttr('disabled');
						$(".p_currency").removeAttr('disabled');
						$(".p_price").removeAttr('disabled');
						$(".chk_boxes").removeAttr('disabled');
						$(".p_condotion").removeAttr('disabled');
						$("#p_cusall").removeAttr('disabled');

						$('#p_currencyall').attr("disabled", "disabled");
						$('#p_priceall').attr("disabled", "disabled");
						$("#conditionall").attr("disabled", "disabled");
						$("#p_cusall").attr("disabled", "disabled");

					});
				}
			});

			$('#button-search').click(function() {
				$('.search_text').hide();

				$('.tbl1 .data_res1').empty().html('<tr><td colspan="6"><div align="center" width="100%"><img src="images/35.gif" height="20"/></div></td></tr>');
				updatetbl();
				paging(1);

			});

			async function updatetbl() {
				let cusgrp = $('#s_cusno').val();
				let product = $('#s_product').val();
				let part_no = $('#s_partnumber').val();
				let condition = $('#s_condition').val();
				let part_name = $('#s_partname').val();
				let data = {
					'cusgrp': cusgrp,
					'product': product,
					'part_no': part_no,
					'condition': condition,
					'part_name': part_name,
				};
				$.ajax({
					type: 'POST',
					url: 'aws_saledenso_tblupdate.php',
					data: data,
					success: await
					function(data) {
						//alert(data);
						// console.log(data);
						$('.tbl1 .data_res1').html('');
						$('.tbl1 .data_res1').html(data);
					}
				})
			}





		});
		async function paging(x = 1) {
			let cusgrp = $('#s_cusno').val();
			let product = $('#s_product').val();
			let part_no = $('#s_partnumber').val();
			let condition = $('#s_condition').val();
			let part_name = $('#s_partname').val();
			let page = x;
			let data = {
				'cusgrp': cusgrp,
				'product': product,
				'part_no': part_no,
				'condition': condition,
				'part_name': part_name,
				'page': page,
			};
			$('.tbl1 .data_res1').empty().html('<tr><td colspan="6"><div align="center" width="100%"><img src="images/35.gif" height="20"/></div></td></tr>');

			$.ajax({
				type: 'POST',
				url: 'aws_saledenso_tblupdate.php',
				data: data,
				success: await
				function(data) {
					$('.tbl1 .data_res1').html('');
					$('.tbl1 .data_res1').html(data);
				}
			})
			$.ajax({
				type: 'POST',
				url: 'aws_saledenso_tblpage.php',
				data: data,
				success: function(data) {
					console.log(data);
					$('#pagination').html(data);
				}
			});
		}

		function view_edit(e = null) {
			pos = $(e).attr("id");

			console.log(pos);
			$("#dialog-form").attr("title", "Part Detail");
			//id='".$vcusno1."||".$vitnbr."||".$vitdsc."
			var xdata = pos.split("||");
			var xcusno1 = xdata[0];
			var xpartno = xdata[1];
			var xpartnm = xdata[2];
			var xcusgrp = xdata[3];
			$("#p_cusno").val(xcusno1);
			$.ajax({
				type: 'POST',
				url: 'aws_saledenso_partdetail.php',
				data: {
					vCusno1: xcusno1,
					vPartno: xpartno,
					vCusgrp: xcusgrp
				},
				success: function(data) {
					// alert(data);
					$("#p_partno").text(xpartno);
					$("#p_partname").text(xpartnm);
					//$("#part_detail").html(data);
					$("#part_detail").html(data);


					$('#data').after('<div id="nav" align="right"></div>');
					var rowsShown = 5;
					var rowsTotal = $('#data tbody tr').length;

					var numPages = rowsTotal / rowsShown;
					for (i = 0; i < numPages; i++) {
						var pageNum = i + 1;
						$('#nav').append('<a href="#" rel="' + i + '">' + pageNum + '</a> ');
					}
					$('#data thead tr').show();
					$('#data tbody tr').hide();
					$('#data tbody tr').slice(0, rowsShown).show();
					$('#nav a:first').addClass('active');
					$('#nav a').bind('click', function() {
						$('#nav a').removeClass('active');
						$(this).addClass('active');
						var currPage = $(this).attr('rel');
						var startItem = currPage * rowsShown;
						var endItem = startItem + rowsShown;
						$('#data tbody tr').css('opacity', '0.0').hide().slice(startItem, endItem).
						css('display', 'table-row').animate({
							opacity: 1
						}, 300);
					});

					$(this).dialog("close");
					$("#dialog-form").dialog({
						autoOpen: true,
						width: 600,
						modal: true,
						position: {
							my: "center",
							at: "center",
							of: $("body"),
							within: $("body")
						},
						buttons: {
							"Save change": function() {
								//view_edit
								let text_err = '';
									$('.p_price').each(function (i, v) {
										// element == this
										var p_price1 = $(this).val();
										var regex = /^(\d{1,14}(\.\d{1,4})?|\.\d{1,4})$/; // regular expression to match a string with up to 14 digits and up to 4 decimal places
										if (p_price1 != '') {
											if (!regex.test(p_price1)) {
												text_err = '1';
											}
										}	
									});
									if(text_err != ''){
										alert('Please enter number 14 digits and 4 decimal place.');
										return
									}
									
									var value2 = $('#p_priceall').val();
									var regex = /^(\d{1,14}(\.\d{1,4})?|\.\d{1,4})$/; // regular expression to match a string with up to 14 digits and up to 4 decimal places
									if (value2 != '') {


										if (!regex.test(value2)) {
											alert('Please enter number 14 digits and 4 decimal place.');
											return
										}
									}

								var i = 1;
								var insertdata = new Array();
								if ($('#checkall').is(":checked") == true) {
									if ($("#p_currencyall").val().length <= 0 || $("#p_priceall").val().length <= 0) {
										alert("Please key the value!");
										return false;
									} else {
										insertdata = [];
										var detail = new Array();
										detail.push($("#p_cusall").val(), $("#p_currencyall").val(), $("#p_priceall").val(), $("#p_cusno").val(), $("#p_partno").text(), $("#conditionall").is(":checked"));
										insertdata.push(detail);
									}
								} else {
									$("#data tbody tr").each(function() {
										//  if($(this).find("input").is(":checked") == true){
										var detail = new Array();
										//console.log($("#p_cusgroup" + i).val() + $("#p_currency" + i).val() + $("#p_price" + i).val() );
										detail.push($("#p_cusgroup" + i).val(), $("#p_currency" + i).val(), $("#p_price" + i).val(), $("#p_cusno").val(), $("#p_partno").text(), $("#p_condotion" + i).is(":checked"));
										insertdata.push(detail);
										// }
										i = i + 1;
									});
								}
								if (insertdata.length <= 0) {
									alert("Please select value!");
								}
								console.log(insertdata);
								$.ajax({
									type: 'POST',
									url: 'aws_saledenso_updprice.php',
									data: {
										"data": insertdata
									},
									success: function(response) {
										$("#dialog-form").dialog("close");
										$('#button-search').click();
										// window.location.reload();
										//  alert(response);
									}
								});
							},
							Cancel: function() {
								$("#dialog-form").dialog("close");
								// window.location.reload();
							}
						}
					});
				}
			});
		}
	</script>
</head>

<body>
	<?php ctc_get_logo() ?> <!-- add by CTC -->
	<div id="mainNav">
		<?
		$_GET['selection'] = "main";
		include("navhoriz.php");

		?>
	</div>
	<div id="isi">

		<div id="twocolLeft">
			<?
			$_GET['current'] = "saledenso";
			include("navUser.php");
			?>
		</div>
		<div id="twocolRight">
			<?
			require('../db/conn.inc');
			$query = "DELETE FROM `awsexc_temp` WHERE `Owner_Comp` = '$comp';";
			mysqli_query($msqlcon, $query);


			$searchcusno = '<select name="s_cusno" id="s_cusno" class="arial11blue"  style="width: 100%">';
			$searchcusno = $searchcusno .  ' <option value="">Select option</option>';
			//$query1="select distinct cusno1 as cusno from awsexc where Owner_Comp='$comp' ";
			$query1 = "select distinct cusgrp from awsexc where awsexc.Owner_Comp = '$comp'  and awsexc.cusno1 = '$cusno'";
			$sql1 = mysqli_query($msqlcon, $query1);
			//echo $query1;
			while ($hasil = mysqli_fetch_array($sql1)) {
				$ycusno = $hasil['cusgrp'];
				$scusno = $_GET["s_cusno"];
				$selected2 = ($hasil["cusgrp"] == $scusno) ? "selected" : "";
				$searchcusno = $searchcusno .  ' <option value="' . $ycusno . '" ' . $selected2 . '>' . $ycusno . '</option>';
			}
			$searchcusno = $searchcusno . ' </select>';


			$product = '<select name="s_product" id="s_product" class="arial11blue"  style="width: 100%">';
			$product = $product .  ' <option value="">Select option</option>';
			$query1 = "select distinct bm008pr.Product from bm008pr join awsexc on trim(awsexc.itnbr) = trim(bm008pr.ITNBR)  and awsexc.Owner_Comp = bm008pr.Owner_Comp where awsexc.Owner_Comp='$comp' and cusno1 = '$cusno' ";
			$sql1 = mysqli_query($msqlcon, $query1);
			while ($hasil = mysqli_fetch_array($sql1)) {
				$yProduct = $hasil['Product'];
				$sproduct = $_GET['s_product'];
				$selected3 = ($hasil["Product"] == $sproduct) ? "selected" : "";
				$product = $product .  ' <option value="' . $yProduct . '" ' . $selected3 . '>' . $yProduct . '</option>';
			}
			$product = $product . ' </select>';
			$condition = '<select name="s_condition" id="s_condition" class="arial11blue"  style="width: 100%">';
			$condition = $condition .  ' <option value="">Select option</option>';
			$query1 = "select distinct sell,case when sell = 1 then 'Sell' else 'Not Sell' end selltext  from awsexc where Owner_Comp='$comp' and cusno1 = '$cusno'  ";
			$sql1 = mysqli_query($msqlcon, $query1);
			while ($hasil = mysqli_fetch_array($sql1)) {
				$ysell = $hasil['sell'];
				$yselltext = $hasil['selltext'];
				$scondition = $_GET['s_condition'];
				$selected4 = ($hasil["sell"] == $scondition) ? "selected" : "";
				$condition = $condition .  ' <option value="' . $ysell . '" ' . $selected4 . '>' . $yselltext . '</option>';
			}
			$condition = $condition . ' </select>';
			?>
			<table width="97%" border="0" cellspacing="0" cellpadding="0">
				<tr class="arial11blackbold">
					<td>&nbsp;</td>
					<td width="19%">&nbsp;</td>
					<td width="25%">&nbsp;</td>
					<td width="3%"></td>
					<td width="19%">&nbsp;</td>
					<td width="1%">&nbsp;</td>
					<td width="30%">&nbsp;</td>
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
					<td width="3%"><img src="../images/calendar.gif" width="16" height="15"></td>
					<td colspan="6" class="arial21redbold"> 2 <sup>nd</sup> Customer Sales Condition MA (DENSO)</td>
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
			</table>

			<form name="search" method="get">
				<fieldset style="width:98%">
					<legend> &nbsp;Search Information</legend>
					<table width="97%" border="0" cellspacing="0" cellpadding="0">
						<tr class="arial11blackbold">
							<td width="16%">
								<div align="right"><span class="arial12BoldGrey"><?php echo get_lng($_SESSION["lng"], "L0590"); ?><!--2 nd Customer Group--></span></div>
							</td>
							<td width="2%">
								<div align="center"><span class="arial12Bold">:</span></div>
							</td>
							<td width="15%"><span class="arial12Bold"><? echo $searchcusno ?></span></td>
							<td width="2%"></td>
							<td width="16%">
								<div align="right"><span class="arial12BoldGrey"><?php echo get_lng($_SESSION["lng"], "L0098"); ?><!--Product--></span></div>
							</td>
							<td width="2%">
								<div align="center"><span class="arial12Bold">:</span></div>
							</td>
							<td width="15%"><span class="arial12Bold"><? echo $product ?></span></td>
							<td colspan="3" width="32%">&nbsp;</td>
						</tr>
						<tr class="arial11blackbold">
							<td></td>
							<td>&nbsp;</td>
							<td>&nbsp;</td>
							<td></td>
							<td>&nbsp;</td>
							<td>&nbsp;</td>
							<td>&nbsp;</td>
							<td>&nbsp;</td>
							<td>&nbsp;</td>
							<td>&nbsp;</td>
						</tr>
						<tr class="arial11blackbold">
							<td width="16%">
								<div align="right"><span class="arial12BoldGrey"><?php echo get_lng($_SESSION["lng"], "L0144"); ?><!--Part Number--></span></div>
							</td>
							<td width="2%">
								<div align="center"><span class="arial12Bold">:</span></div>
							</td>
							<td width="15%"><span class="arial12Bold"><input type="text" name="s_partnumber" id="s_partnumber" <?php
																																if (isset($_GET["button"])) {
																																	$xpartnumber = $_GET["s_partnumber"];
																																	echo "value = '$xpartnumber'";
																																}
																																?>style="width: 100%" /></span></td>
							<td width="2%"></td>
							<td width="16%">
								<div align="right"><span class="arial12BoldGrey"><?php echo get_lng($_SESSION["lng"], "L0597"); ?><!--Condition--></span></div>
							</td>
							<td width="2%">
								<div align="center"><span class="arial12Bold">:</span></div>
							</td>
							<td width="15%"><span class="arial12Bold"><? echo $condition ?></span></td>
							<td colspan="3" width="32%">&nbsp;</td>
						</tr>
						<tr class="arial11blackbold">
							<td></td>
							<td>&nbsp;</td>
							<td>&nbsp;</td>
							<td></td>
							<td>&nbsp;</td>
							<td>&nbsp;</td>
							<td>&nbsp;</td>
							<td>&nbsp;</td>
							<td>&nbsp;</td>
							<td>&nbsp;</td>
						</tr>
						<tr class="arial11blackbold">
							<td width="16%">
								<div align="right"><span class="arial12BoldGrey"><?php echo get_lng($_SESSION["lng"], "L0097"); ?><!--Part Name--></span></div>
							</td>
							<td width="2%">
								<div align="center"><span class="arial12Bold">:</span></div>
							</td>
							<td width="15%"><span class="arial12Bold"><input type="text" name="s_partname" id="s_partname" <?php
																															if (isset($_GET["button"])) {
																																$xpartname = $_GET["s_partname"];
																																echo "value = '$xpartname'";
																															}
																															?>style="width: 100%" /></span></td>
							<td width="2%"></td>
							<td width="16%"></td>
							<td width="2%"></td>
							<td width="15%" </td>
							<td width="2%"></td>
							<td width="19%"><input type="submit" name="button" id="button" value="Search" class="arial11" style="display:None;></td>
							<td width=" 19%"><input type="button" name="button-search" id="button-search" value="<?php echo get_lng($_SESSION["lng"], "L0427"); ?>" class="arial11" style="display:;"></td>
							<td width="11%">&nbsp;</td>
						</tr>
						<tr class="arial11blackbold">
							<td></td>
							<td>&nbsp;</td>
							<td>&nbsp;</td>
							<td></td>
							<td>&nbsp;</td>
							<td>&nbsp;</td>
							<td>&nbsp;</td>
							<td>&nbsp;</td>
							<td>&nbsp;</td>
							<td>&nbsp;</td>
						</tr>
					</table>
				</fieldset>
			</form>

			<table width="100%" border="0" cellspacing="0" cellpadding="0">
				<tr valign="middle" class="arial11">
					<th scope="col">&nbsp;</th>
					<th width="90" scope="col">
					</th>
					<th width="10"></th>
					<th width="90" scope="col">
						<div id="Importfile" style='background-color: #AD1D36;font-size:9pt;color: #FFFFFF;height:22px;cursor:pointer;'>
							<img src="../images/excel.jpg" width="18" height="18" style='float:left;margin-left:4px;margin-top:1px;'>
							<font style='margin-right:18px;line-height:22px;'><?php echo get_lng($_SESSION["lng"], "L0450"); ?></font>
						</div>
					</th>
					<th width="10"></th>
					<th width="170" scope="col">
						<div id="ConvExcel" style='background-color: #AD1D36;font-size:9pt;color: #FFFFFF;height:22px;cursor:pointer;'>
							<img src="../images/excel.jpg" width="18" height="18" style='float:left;margin-left:4px;margin-top:1px;'>
							<font style='margin-right:18px;line-height:22px;'><?php echo get_lng($_SESSION["lng"], "L0346"); ?></font>
						</div>
					</th>
				</tr>
				<tr height="5">
					<td colspan="5"></td>
				<tr>
			</table>
			<table width="100%" class="tbl1" cellspacing="0" cellpadding="0">
				<thead>
					<tr class="arial11whitebold" bgcolor="#AD1D36">
						<th width="8%" height="30" scope="col"><?php echo get_lng($_SESSION["lng"], "L0457"); ?><!--Company Code--></th>
						<th width="20%" scope="col"><?php echo get_lng($_SESSION["lng"], "L0098"); ?><!--Product--></th>
						<th width="20%" scope="col"><?php echo get_lng($_SESSION["lng"], "L0144"); ?><!--Part Number--></th>
						<th width="20%" scope="col"><?php echo get_lng($_SESSION["lng"], "L0097"); ?><!--Part Name--></th>
						<th width="10%" scope="col"><?php echo get_lng($_SESSION["lng"], "L0597"); ?><!--Condition--></th>
						<th width="12%" height="30" scope="col" class=\"lasttd\"><?php echo get_lng($_SESSION["lng"], "L0590"); ?><!--2 ndCustomer Group--></th>
					</tr>
				</thead>
				<tbody class="data_res1">
				</tbody>
				<tr align="right" valign="middle" height="30" class="search_text">
					<td colspan="12" class="lastpg">
						<div class="search_text" style="width:100%; text-align:center;"><?php echo get_lng($_SESSION["lng"], "L0598"); ?><!-- Please Search Data --></div>
					</td>
				</tr>

				<?
				// require('../db/conn.inc');

				// if (isset($_GET['button'])) {
				// $per_page = 10;
				// $num = 5;
				// $criteria = " where awsexc.Owner_Comp='$comp'  and cusno1 = '$cusno' ";
				// if (isset($_GET["button"])) {
				// $xcusno1 = $_GET["s_cusno"];
				// $xproduct = $_GET["s_product"];
				// $xpartnumber = $_GET["s_partnumber"];
				// $xpartname = $_GET["s_partname"];
				// $xcondition = $_GET["s_condition"];
				// if (trim($xcusno1) != '') {
				// $criteria .= ' and cusgrp="' . $xcusno1 . '"';
				// }
				// if (trim($xpartnumber) != '') {
				// $criteria .= ' and trim(awsexc.itnbr)="' . $xpartnumber . '"';
				// }
				// if (trim($xproduct) != '') {
				// $criteria .= ' and Product="' . $xproduct . '"';
				// }
				// if (trim($xpartname) != '') {
				// $criteria .= ' and ITDSC like "%' . $xpartname . '%"';
				// }
				// if (trim($xcondition) != '') {
				// $criteria .= ' and sell="' . $xcondition . '"';
				// }
				// }
				// $criteria .= "  group by  awsexc.Owner_Comp, cusno1, trim(awsexc.itnbr) ";
				// $query = "SELECT awsexc.Owner_Comp, cusno1, Product, trim(awsexc.itnbr) as itnbr, ITDSC,   
				// case when (
				// select sell from awsexc a where awsexc.Owner_Comp=a.Owner_Comp and a.itnbr = awsexc.itnbr and awsexc.cusno1 = a.cusno1 order by sell desc limit 1
				// ) = 1 then 'Sell' else 'Not Sell' end sell
				// , group_concat(cusgrp ORDER BY cusgrp ASC) as cusgrp, price, curr
				// FROM awsexc left join bm008pr on trim(awsexc.itnbr) = trim(bm008pr.ITNBR) and awsexc.Owner_Comp = bm008pr.Owner_Comp " . $criteria;
				// //echo $query;
				// $sql = mysqli_query($msqlcon, $query);
				// $count = mysqli_num_rows($sql);

				// $pages = ceil($count / $per_page);
				// $page = $_GET['page'];
				// if ($page) {
				// $start = ($page - 1) * $per_page;
				// } else {
				// $start = 0;
				// $page = 1;
				// }

				// $query1 = "SELECT awsexc.Owner_Comp, cusno1, Product, trim(awsexc.itnbr) as itnbr, ITDSC,   
				// case when (
				// select sell from awsexc a where awsexc.Owner_Comp=a.Owner_Comp and awsexc.cusno1 = a.cusno1 and a.itnbr = awsexc.itnbr  order by sell desc limit 1
				// ) = 1 then 'Sell' else 'Not Sell' end sell
				// , group_concat(cusgrp ORDER BY cusgrp ASC) as cusgrp, price, curr
				// FROM awsexc left join bm008pr on trim(awsexc.itnbr) = trim(bm008pr.ITNBR) and awsexc.Owner_Comp = bm008pr.Owner_Comp " . $criteria . "order by cusno1" .
				// " LIMIT $start, $per_page";
				// //echo $query1;
				// $sql = mysqli_query($msqlcon, $query1);
				// if (!mysqli_num_rows($sql)) {
				// echo "<tr height=\"30\"><td colspan=\"12\" align=\"center\" class=\"arial12BoldGrey\">" . get_lng($_SESSION["lng"], "E0060") /*No Data Found.... ! */ . "</td></tr>";
				// }
				// while ($hasil = mysqli_fetch_array($sql)) {
				// $vowner = $hasil['Owner_Comp'];
				// $vcusno1 = $hasil['cusno1'];
				// $vproduct = $hasil['Product'];
				// $vitnbr = $hasil['itnbr'];
				// $vitdsc = $hasil['ITDSC'];
				// $vsell = $hasil['sell'];
				// $vcusgrp = $hasil['cusgrp'];
				// $vprice = $hasil['price'];
				// $vcurr = $hasil['curr'];
				// if (strlen($vcusgrp) > 12) {
				// $vcusgrp = substr($vcusgrp, 0, 12) . "...";
				// }

				// echo "<tr class=\"arial11black\" height=\"30\">";
				// echo "<td  align=\"center\" >" . $vowner . "</td>";
				// echo "<td>" . $vproduct . "</td>";
				// echo "<td><a href='#' class='view' id='" . $vcusno1 . "||" . $vitnbr . "||" . $vitdsc . "||" . $vcusgrp . "'>" . $vitnbr . "</a></td>";
				// echo "<td>" . $vitdsc . "</td>";
				// echo "<td align=\"center\" >" . $vsell . "</td>";
				// echo "<td class=\"lasttd\">" . $vcusgrp . "</td>";
				// echo "</tr>";
				// }

				// require('pager.php');
				// if ($count > $per_page) {
				// echo "<tr height=\"30\"><td colspan=\"9\" align=\"right\" class=\"lasttd\"><div id=\"pagination\" >";
				// //echo $query;
				// $fld = "page";
				// paging($query, $per_page, $num, $page);
				// echo "</div></td></tr>";
				// }
				// }



				?>

				<tr align="right" valign="middle" height="30">
					<td colspan="12" class="lastpg">
						<div id="pagination"> </div>
					</td>
				</tr>
			</table>

			<div id="footerMain1">
				<ul>


					<!--<li class="last"><a href="Footer/Terms.html">Legal and Policy</a></li>-->
				</ul>

				<div id="footerDesc">

					<p>
						Copyright © 2023 DENSO . All rights reserved

				</div>
			</div>
		</div>


		<div id="dialog-form" title="Part Detail">
			<form id="frm_customer">
				<fieldset>
					<span class="arial12Bold">Part Number </span> : <span class="arial12Grey" id="p_partno"></span>
					<span class="arial12Bold">Part Name </span>: <span class="arial12Grey" id="p_partname"></span>
					<input type="hidden" id="p_cusno" name="p_cusno">
					<br /><br />
					<table border="0" class="data"  style="font-family: Arial, Helvetica, sans-serif;border-collapse: collapse;color: white; " width="100%">
						<thead style="background-color: #f6a828;  padding-top: 6px; padding-bottom: 6px;">
							<tr>
								<th style="padding-top: 6px;  padding-bottom: 6px; "></th>
								<th style="padding-top: 6px; padding-bottom: 6px;" align="center"><span class="">Customer Group</span></th>
								<th><span class="" align="center">Currency</span></th>
								<th><span class=" lasttd" align="center">Price (Optional)</span></th>
								<th align="center">Condition</th>
							</tr>
						</thead>
						<tbody>
							<tr>
								<td align="center"><input type="checkbox" id="checkall" name="checkall"></td>
								<td align="center"><input type="text" id="p_cusall" value="All" style="text-align:center;" disabled /></td>
								<td align="center"><input type="text" id="p_currencyall" value="" style="text-align:center;" disabled maxlength="2" /></td>
								<td align="center"><input type="text" id="p_priceall" value="" style="text-align:right;" disabled /></td>
								<td align="center"><input type="checkbox" id="conditionall" name="conditionall" disabled /></td>
							</tr>
						</tbody>
					</table>
					<br /><br />
					<div id="part_detail"></div>
					<br />
					<?php echo get_lng($_SESSION["lng"], "W0030") ?>
				</fieldset>
			</form>

		</div>
</body>

</html>