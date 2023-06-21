<?php 
session_start();
require_once('../../language/Lang_Lib.php');
require_once('../../core/ctc_init.php'); // add by CTC

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
		echo "<script> document.location.href='../../" . redir . "'; </script>";
	}
} else {
	header("Location:../../login.php");
}
include('chklogin.php');
include('../../language/conn.inc');
$tblname = $alias . "awsregimp";
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
	partno varchar(27),
	partdes varchar(500),
	ordsts varchar(1),
	qty int(11),
	CurCD varchar(2),
	bprice decimal(18,4),
	SGCurCD varchar(2),
	SGPrice decimal(18,8),
	impgrp varchar(3),
	DlrCurCd varchar(2),
	DlrPrice decimal(18,4),
	OECus varchar(1),
	Shipment varchar(1),
	PRIMARY KEY  (corno, cusno, partno, ordsts,Owner_Comp)
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
		ADD PRIMARY KEY (corno, cusno, partno, ordsts,Owner_Comp)";
		mysqli_query($msqlcon,$sql_alter);
	}

	$query2 = "delete from " . $tblname . " where Owner_Comp='$comp' ";
	mysqli_query($msqlcon,$query2);
}

$imptable = $tblname;
$_SESSION['imptable'] = $tblname;

$ordertype = '';
require("crypt.php");
if (isset($_SERVER['REQUEST_URI'])) {
	$var = decode($_SERVER['REQUEST_URI']);
	$ordertype = trim($var['ordertype']);
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
$table1 = str_replace("awsregimp", 'ordtmp', $tblname);
$query2 = "delete from " . $table1 . " where cusno ='" . $cusno . "' and Owner_Comp='$comp' ";
//echo $query2;
mysqli_query($msqlcon,$query2);

$sc003Qry = "SELECT max(yrmon) as maxyr FROM sc003pr where Owner_Comp='$comp' ";
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
	<link rel="stylesheet" type="text/css" href="../css/dnia.css">
	<?php if ($ordertype == '' || $ordertype == 'Normal') { ?>
		<link rel="stylesheet" href="../themes/ui-lightness/jquery-ui-green.css">
	<?php } else if ($ordertype == 'Urgent') { ?>
		<link rel="stylesheet" href="../themes/ui-lightness/jquery-ui-red.css">
	<?php } else if ($ordertype == 'Request') { ?>
		<link rel="stylesheet" href="../themes/ui-lightness/jquery-ui.css">
	<?php } ?>
	</style>
	<!--[if IE]>
<style type="text/css">
#twocolLeft{ padding-top: 0px; }
#twocolRight { zoom: 1; padding-top:10px; }
</style>
<![endif]-->
	<script type="text/javascript" language="javascript" src="../lib/jquery-1.4.2.js"></script>
	<script src="../lib/jquery.ui.core.js"></script>
	<script src="../lib/jquery.ui.widget.js"></script>
	<script src="../lib/jquery.ui.mouse.js"></script>
	<script src="../lib/jquery.ui.button.js"></script>
	<script src="../lib/jquery.ui.draggable.js"></script>
	<script src="../lib/jquery.ui.position.js"></script>
	<script src="../lib/jquery.ui.resizable.js"></script>
	<script src="../lib/jquery.ui.button.js"></script>
	<script src="../lib/jquery.ui.dialog.js"></script>
	<script src="../lib/jquery.ui.datepicker.js"></script>
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
					var shipToAddress = ship_to_nm + "<br>" + adrs1 + "<br>" + adrs2 + "<br>" + adrs3 + " " + pstl_cd + "<br>" + comp_tel_no;
					document.getElementById("shipToAddress").innerHTML = shipToAddress;
				}
			});
		}
		$(function() {
			

			$("#file").change(function() {
				$.ajax({
					type: 'post',
					url: '../timeSev.php',

					success: function (data) {
					
						time = data;

					}
				});
			});
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
					text: "<?php echo get_lng($_SESSION["lng"], "L0317"); ?>",
					click: function() {
						$(this).dialog("close");
					}
				}
			],
			close: function() {
				window.location.href = "main.php";

			}

			});
			 //03/10/2019 Prachaya inphum CTC --end--
			$("#result_tr1").hide();
			$('#dateChecking').val('');
			//Start Add skip check requestDate2
			if ($("#requestDate2").val() == undefined) {
				//alert('undefined')
			} else {
				//alert('defined')
				var date = $("#requestDate2").val().split('-');
				var dateToday = new Date();
				var yrRange = dateToday.getFullYear() + ":" + $("#maxyr").val().substring(0, 4);
				$("#requestDate").datepicker({
					minDate: new Date(date[1] + "-" + date[0] + "-" + date[2]), //mm-dd-yyyy
					dateFormat: 'dd-mm-yy',
					changeMonth: true,
					changeYear: true,
					yearRange: yrRange,
					onSelect: function(data) {
						edata = "selected=" + data;
						//alert(edata);
						$.ajax({
							type: 'GET',
							url: 'checkIsHoliday.php',
							data: edata,
							success: function(data) {
								if (data.substr(0, 5) == 'Error') {
									alert(data);
									$("#requestDate").val($("#requestDate2").val());
									$('#dateChecking').val(data);
									return false;
								} else {
									$('#dateChecking').val('');
								}
							}
						});

					}
				});
			}
			//End Add skip check requestDate2

			var str2 = $("#requestDate").val();
			var para = "selected=" + str2;
			$.ajax({
				type: 'GET',
				url: 'checkIsHoliday.php',
				data: para,
				success: function(data) {
					if (data.substr(0, 5) == 'Error') {
						$('#dateChecking').val(data);
						return false;
					}
				}
			});

			$('#frmimport').submit(function() {
				// 03/10/2019 Prachaya inphum CTC --start
					timeObj1 = new Date();
					var hr = timeObj1.getHours();
					var mi = timeObj1.getMinutes(); 
					var se = timeObj1.getSeconds(); 
					var page ='<?php echo $_GET['selection']?>';  
					
					
					if(page== 'urgentOrder'){ //check page
						if(parseFloat(hr) == parseFloat(hour)){ //check hour
							if(parseFloat(mi) >= parseFloat(min)){ //check min
								$("#dialog-timeout").dialog("open");
							}  
						}else if(parseFloat(hr) > parseFloat(hour)){
						
						$("#dialog-timeout").dialog("open");
						}
					}
				// 03/10/2019 Prachaya inphum CTC --end--
				if ($('#shipToCd').val() == '') {
					alert('Ship to should be filled!');
					return false;
				}
				if ($('#file').val() == '') {
					alert('<?php echo get_lng($_SESSION["lng"], "W0017"); ?>' /*'Please attach upload file!'*/ );
					return false;
				}

				//Start Add skip check requestDate2
				if ($('#requestDate2').val() == undefined) {
					//alert('End')
				} else {
					var str1 = $("#requestDate2").val();
					var dueDt = Date.parse(str2);
					var selecteDt = Date.parse(str1);
					if (dueDt < selecteDt) {
						alert('Request Due Date should be greater than ' + str1);
						return false;
					}
				}
				// End Add skip check requestDate2
				if ($('#dateChecking').val() == undefined) {

				} else {
					if ($('#dateChecking').val() != '') {
						alert($('#dateChecking').val());
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
								.text(value.shipCd + ' : ' + value.shipNm));
					});
				}
			});
			
			check_duedate_err();
		})
		function check_duedate_err(){
			if($("#requestDate").val() == ''){
				$("#requestDate").attr("disabled", "disabled"); 
			}
			if($('#dateErr').val() == '1'){
				alert($('#dateErrtxt').val());				
				$("[name='upload']").attr("disabled", "disabled"); 

			}
		}
	</script>

	<style>
		.ui-dialog .ui-state-error {
			padding: .3em;
		}
	</style>
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
			if ($ordertype == 'Normal') {
				$formtitle = get_lng($_SESSION["lng"], "L0164")/*'Normal Order Upload'*/;
				$_GET['current'] = "main";
			} else if ($ordertype == 'Urgent') {
				$formtitle = get_lng($_SESSION["lng"], "L0291")/*'Urgent Order Upload'*/;
				$_GET['current'] = "urgentOrder";
			} else if ($ordertype == 'Request') {
				$formtitle = get_lng($_SESSION["lng"], "L0290")/*'Requested Due Date Order Upload'*/;
				$_GET['current'] = "requestDueDate";
			}
			include("navUser.php");
			?>


		</div>
		<div id="twocolRight">
			<form id="frmimport" method="post" enctype="multipart/form-data" action="import_new.php">
				<table width="97%" border="0" cellspacing="0" cellpadding="0">
					
					<tr class="arial21redbold">
						<td><?php echo $formtitle ?></td>
						<td>&nbsp;</td>
						<td colspan="5">
                            <?php 
                            if ($ordertype == 'Urgent') {
									require('../countdown.php');
								} ?>
                        </td>
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
						<td><input type="text" name="ordertype" id="ordertype" readonly="true" class="arial11grey" value="<?php echo $ordertype; ?>"></td>
						<td colspan="4" class="arial11blackbold">&nbsp;</td>
					</tr>
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
							<td><?php echo get_lng($_SESSION["lng"], "L0565"); ?>
								<!-- Shipping Day (ETD)-->
							</td>
							<td>:</td>
							<td colspan="5" class="arial11blackbold">
								<input name="requestDate" id="requestDate" type="text" size="12" maxlength="12" value="<?php echo  $requestDateArr[2]; ?>">
								<input name="requestDate2" id="requestDate2" type="hidden" size="12" maxlength="12" value="<?php echo  $requestDateArr[2]; ?>">
								<input name="dateChecking" id="dateChecking" type="hidden">
								<input name="" id="dateErr" type="hidden" value="<?php echo  $requestDateArr[3]; ?>">
								<input name="" id="dateErrtxt" type="hidden" value="<?php echo  $requestDateArr[4]; ?>">
							</td>
						</tr>
					<?php } ?>
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
					
					<tr class="arial11blackbold" id="result_tr1">
						<td>Shipment Mode</td>
						<td>:</td>
						<td colspan="5" class="arial11blackbold">
							<select name="shipment" id="shipment" style="width: 200px; font-size:11px" class="arialgrey">
								<option value="S" selected>Sea</option>
								<option value="A">Air</option>
							</select>
						</td>
					</tr>


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
				<blockquote>

					<input name="file" id="file" type="file" size="50">
					<input name="upload" type="submit" value="<?php echo get_lng($_SESSION["lng"], "L0172")/*Import*/; ?>">


				</blockquote>
				<?php echo get_lng($_SESSION["lng"], "L0170"); ?>
				<!--Download format excel here :--><a href="../db/order.xls" target="_blank"><img src="../images/excel.jpg" width="16" height="16" border="0"></a>

			</form>
			
		</div>

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

</body>

<?php //include('../timecheck.php'); ?> <!--03/10/2019 Prachaya inphum CTC-->
</html>

