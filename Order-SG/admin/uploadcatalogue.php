<?php
session_start();
require_once('./../../core/ctc_init.php'); // add by CTC

require_once('../../language/Lang_Lib.php');
$comp = ctc_get_session_comp(); // add by CTC
$supno = $_SESSION['supno'];
if (isset($_SESSION['cusno'])) {
    if ($_SESSION['redir'] == 'Order-SG') {
        $cusno =  $_SESSION['cusno'];
        $type = $_SESSION['type'];
        $user = $_SESSION['user'];
    } else {
        echo "<script> document.location.href='../" . redir . "'; </script>";
    }
} else {
    header("Location:../login.php");
}
?>
<html>

<head>
    <title>Denso Ordering System</title>
    <link rel="stylesheet" type="text/css" href="../css/dnia.css">
    <!--[if IE]>
<style type="text/css"> 
#twocolLeft{ padding-top: 0px; }
#twocolRight { zoom: 1; padding-top:10px; }
</style>	
<![endif]-->
<style>
	.loading-screen {
		position: absolute;
		width: 100%;
		height: 100%;
		background-color: rgba(0, 0, 0, 0.5);
		display: flex;
		justify-content: center;
		align-items: center;
		z-index: 9999;
		color: white;
		font-size: 24px;
	}

	.loading-text {
		margin-left: 10px;
	}
	.pageing button {
	  background-color: #ffffff;
	  color: #ad1d36;
	  border: 1px solid #ad1d36;
	  padding: 4px 8px;
	  margin: 0 2px;
	  font-size: 12px;
	  font-weight: bold;
	  cursor: pointer;
	  transition: all 0.2s ease-in-out;
	}

	.pageing button:hover:not(.active) {
	  background-color: #ad1d36;
	  color: #ffffff;
	  border: none;
	}

	.pageing button.active {
	  background-color: #ad1d36;
	  color: #ffffff;
	  border: none;
	}

	.pageing button.active:hover {
	  background-color: #ffffff;
	  color: #ad1d36;
	  border: 1px solid #ad1d36;
	}



</style>
    <script type="text/javascript" language="javascript" src="../lib/jquery-1.4.2.js"></script>
</head>

<body>

    <?php ctc_get_logo() ?>

    <div id="mainNav">
        <ul>
            <li id="current"><a href="maincusadm.php" target="_self">Administration</a></li>
            <li><a href="Profile.php" target="_self">User Profile</a></li>
            <li><a href="../logout.php" target="_self">Log out</a></li>

        </ul>
    </div>
    <div id="isi">

        <div id="twocolLeft">
            <div class="hmenu">
                <div class="headerbar">Administration</div>
                <?
                $MYROOT = $_SERVER['DOCUMENT_ROOT'];
                $_GET['current'] = "partcatalogue";
                include("navAdm.php");
                ?>
            </div>
		
        
        <div id="twocolRight">

            <h3>Catalogue Upload</h3>


            <?php
            include "../db/conn.inc";
			ini_set('memory_limit', '-1');
			
			
			
			
            include "../admin/db/Quick_CSV_import.php";
            if (isset($_POST['submit'])) {
                $rdfirstrow = $_POST['rdfirstrow'];
                $rdreplace = $_POST['rdreplace'];
                $userfile = $_FILES['userfile']['name'];
                $ext = strtolower(end(explode('.', $userfile)));
				$error_file_st = false;
                if ($ext != 'csv') {
                    // echo get_lng($_SESSION["lng"], "E0068"); // Error File Type, Only allow CSV File";	
                    echo '<h3 style=" color: #ad1d36;" class="err_txt">'.get_lng($_SESSION["lng"], "E0068").'</h3>'; // Error File Type, Only allow CSV File";
					$error_file_st = true;
                } else {
					
                    //Create Table
                   // Define the expected number of table columns
					$table_columns_count = 16;

					// Open the CSV file
					$handle = fopen($_FILES['userfile']['tmp_name'], 'r');

					// Read the first row of the CSV file
					$csv_columns = fgetcsv($handle);

					// Check if the number of CSV columns matches the number of table columns
					if (count($csv_columns) !== $table_columns_count) {
						// Display an error message and exit
						// die('Error: CSV file does not have the correct number of columns');
						die('<h3 style=" color: #ad1d36;">Error: CSV file does not have the correct number of columns</h3>');
						
					}

					// Close the CSV file
					fclose($handle);

					// If the number of columns match, proceed with importing the data

					// If the headers match, proceed with importing the data
					$csv = new Quick_CSV_import();
					$temp_table_name = 'cataloguetemp_'.$comp;
					// create if table not exist
					$sql_create = "
					CREATE TABLE IF NOT EXISTS `$temp_table_name` (
						  `CarMaker` text NOT NULL,
						  `ModelName` text DEFAULT NULL,
						  `Vincode` text DEFAULT NULL,
						  `ModelCode` text DEFAULT NULL,
						  `EngineCode` text DEFAULT NULL,
						  `Cc` text DEFAULT NULL,
						  `Start` text DEFAULT NULL,
						  `End` text DEFAULT NULL,
						  `Cprtn` text DEFAULT NULL,
						  `Prtno` text DEFAULT NULL,
						  `Cgprtno` text DEFAULT NULL,
						  `Prtnm` text DEFAULT NULL,
						  `Brand` text DEFAULT NULL,
						  `ordprtno` text DEFAULT NULL,
						  `Remark` text DEFAULT NULL,
						  `Prtpic` varchar(200) NOT NULL
					)
					";
					$create_result = mysqli_query($msqlcon, $sql_create);

					$query = "delete from `$temp_table_name`";
					mysqli_query($msqlcon, $query);

					$ctc_csv = new CTC_CSV();
					$ctc_csv->file_name = $_FILES['userfile']['tmp_name'];
					$ctc_csv->use_csv_header = isset($_POST["use_csv_header"]);
					$ctc_csv->line_enclose_char = "'\\n'";
					if ($rdfirstrow == 'yesrow') {
						$ctc_csv->use_csv_header = true;
					} else {
						$ctc_csv->use_csv_header = false;
					}
					$ctc_csv->table_name = $temp_table_name;
					$ctc_csv->import();

                }
            }
				
            ?>
		<div class="form_btn_pass" style="display:none;">
		<?php
			echo '<h3>Would you like to preceed upload ?</h3>';
			echo '<form method="POST" enctype="multipart/form-data" action="replace-all-catalogue.php">';
			echo '<input type="hidden" name="replace"  value="' . $rdreplace . '">';

			echo '<input type="submit" class="yes_btn" onclick="set_loading();" name="yesbtn" value="' . get_lng($_SESSION["lng"], "L0473") . '">';
			echo '<input type="submit" name="nobtn" value="' . get_lng($_SESSION["lng"], "L0474") . '">';

			echo '</form>';
			
			echo '<h3 style=" color: #ad1d36;">No Error found</h3>';

		?>
		</div>
		<div class="form_btn_error" style="display:none;">
		<?php
			echo '<form method="GET" enctype="multipart/form-data" action="admimportPartCate.php">';
			echo '<input type="submit" name="nobtn" value="' . get_lng($_SESSION["lng"], "L0521") . '">';
			echo '</form>';
			echo '<h3 style=" color: #ad1d36;">Error found. Can not proceed</h3>';
		?>
		</div>
		<div class="result">
		</div>
		<div class="pageing" style="width:97%;text-align:right;margin-top: 13px;">
		</div>
        

        <div id="footerMain1">
            <div id="footerDesc">

                <p>
                    Copyright © 2023 DENSO . All rights reserved

            </div>
		</div>
	</div>
</body>

</html>
<script src="DataTables/datatables.min.js"></script>
<script>
	var obj_data = {};
	let current_page = 1;
	$(document).ready(function() {
		// alert('s');
		$('.yes_btn').click(function() {
			// $(this).attr('disabled', 'disabled');

		});
		update_table();
		
	});
	function set_loading(){
		$('body').prepend('<div class="loading-screen"><div class="spinner"></div><span class="loading-text">Data Uploading. Please wait...</span></div>');
	}
	async function update_table() {
		$('.result').html('<tr><td colspan="6"><div align="center" width="100%">Validating data please wait...<img src="../images/35.gif" height="20"/></div></td></tr>');
		if($('.err_txt').length >= 1){
			$('.result').html('');
		}
		$.ajax({
			type: 'POST',
			url: 'uploadcatalogue_table.php',
			data: {},
			dataType: 'json',
			success:await function(data) {
				$('.result').html('');
				html_table(data);
				obj_data = data;
				html_pg(data);

			}
		});
	}
	function paging(page){
		current_page = page;
		console.log(current_page);
		html_table(obj_data,page);
		html_pg(obj_data,page)
	}
	function html_pg(data, page = 1) {
	  $('.pageing').html('');
	  let html = '';
	  html += `<button class="pgback" onclick="paging(${page - 1})">Back</button>`;

	  let numPages = Object.keys(data).length;
	  let maxPages = 3;
	  let halfMaxPages = Math.floor(maxPages / 2);

	  let startPage = Math.max(page - halfMaxPages, 1);
	  let endPage = Math.min(startPage + maxPages - 1, numPages);

	  if (startPage > 1) {
		html += '<button onclick="paging(1)">1</button>';
		if (startPage > 2) {
		  html += ' << ';
		}
	  }

	  for (let i = startPage; i <= endPage; i++) {
		let activeClass = i === page ? 'active' : '';
		html += `<button onclick="paging(${i})" class="${activeClass}">${i}</button>`;
	  }

	  if (endPage < numPages) {
		if (endPage < numPages - 1) {
		  html += ' >> ';
		}
		html += `<button onclick="paging(${numPages})">${numPages}</button>`;
	  }

	  html += `<button class="pgnext" onclick="paging(${page + 1})">Next</button>`;

	  $('.pageing').html(html);
	  if (page == 1) {
		$('.pgback').prop('disabled', true);
	  } else {
		$('.pgback').prop('disabled', false);
	  }
		  
	  if (page == numPages) {
		$('.pgnext').prop('disabled', true);
	  }else{
		$('.pgnext').prop('disabled', false);

	  }
	}


	function html_table(data={},page='1'){
		 console.log(data[page]);
		let html_result = '';
		let html_paging = '';
		let html_div_page = '<div class="pagenav">';
		let html_td_error = '';
		$.each( data, function( k, v ) {
			html_paging+=`
				<button onclick="paging(${k})">${k}</button>
			`;
			$.each( v, function( k1, v1 ) {
				if (typeof v1['Error'] !== 'undefined' && v1['Error'] !== null) {
					let verror 		= v1['Error'];
					html_td_error =`<td style=" color: #ad1d36;">${verror}</td>`;
				}
				if(k == page){
					let vcarmaker 	= v1['CarMaker'];
					let vmodelname 	= v1['ModelName'];
					let vVincode	= v1['Vincode'];
					let vmodelcode 	= v1['ModelCode'];
					let venginecode = v1['EngineCode'];
					let vcc			= v1['Cc'];
					let vstart 		= v1['Start'];
					let vend 		= v1['End'];
					let vCprtn		= v1['Cprtn'];
					let vPrtno		= v1['Prtno'];
					let vCgprtno	= v1['Cgprtno'];
					let vPrtnm 		= v1['Prtnm'];
					let vBrand		= v1['Brand'];
					let vordprtno 	= v1['ordprtno'];
					let vremark		= v1['Remark'];
					let vpartpic 	= v1['Prtpic'];
					
					
					
					html_result += `
					<tr class=\"arial11black\">
					<td>${vcarmaker}</td>
					<td>${vmodelname}</td>
					<td>${vVincode}</td>
					<td>${vmodelcode}</td>
					<td>${venginecode}</td>
					<td>${vcc}</td>
					<td>${vstart}</td>
					<td>${vend}</td>
					<td>${vCprtn}</td>
					<td>${vPrtno}</td>
					<td>${vCgprtno}</td>
					<td>${vPrtnm}</td>
					<td>${vBrand}</td>
					<td>${vordprtno}</td>
					<td>${vremark}</td>
					<td>${vpartpic}</td>
					`;
					html_result += html_td_error;
					html_result +=`</tr>
					`;
				}
			});
		});
		let html = "";
		html += `
			<table class="tbl1">
				<tr class="arial11white" bgcolor="#AD1D36">
				<th width="5%" align="center">CarMaker</th>
				<th width="5%" align="center">ModelName</th>
				<th width="5%" align="center">Vincode</th>
				<th width="5%" align="center">ModelCode</th>
				<th width="5%" align="center">EngineCode</th>
				<th width="5%" align="center">Cc</th>
				<th width="5%" align="center">Start</th>
				<th width="5%" align="center">End</th>
				<th width="5%" align="center">Genuine Part No.</th>
				<th width="5%" align="center">DENSO Part No.</th>
				<th width="5%" align="center">CG Part No.</th>
				<th width="5%" align="center">Part Name</th>
				<th width="5%" align="center">Brand</th>
				<th width="5%" align="center">Order Part No.</th>
				<th width="5%" align="center">Remark</th>
				<th width="5%" align="center">Part Pic.</th>`;
				
				if(html_td_error != ''){
					html += `<th width="5%" align="center">Error</th>`;
				}else{
				}
				html += `</tr>`;
		html +=html_result;
		html += '</table>';
		
		
		
		$('.result').html(html);
		if(html_td_error != ''){
			html += `<th width="5%" align="center">Error</th>`;
			$('.form_btn_error').show();
		}else{
			$('.form_btn_pass').show();
		}
		
		if(html_result == ''){
			$('.form_btn_error').hide();
			$('.form_btn_pass').hide();
			$('.result').html('');
		}
	}
</script>