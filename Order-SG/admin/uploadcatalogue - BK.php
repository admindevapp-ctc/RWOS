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
    <link rel="stylesheet" type="text/css" href="DataTables/dataTables.bootstrap.min.css">
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
</style>
    <script type="text/javascript" language="javascript" src="../lib/jquery-1.4.2.js"></script>
</head>

<body>

    <?php ctc_get_logo_new() ?>

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
                    echo get_lng($_SESSION["lng"], "E0068"); // Error File Type, Only allow CSV File";	
					$error_file_st = true;
                } else {
					
                    //Create Table
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
						  `Prtpic` varchar(200) NOT NULL,
						  `cgprtnohis` text DEFAULT NULL,
						  `Custprthis` text DEFAULT NULL,
						  `Prtnohis` text DEFAULT NULL
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
			$cul_null = array();
			$qc2 = "SELECT
						`CarMaker`,
						`ModelName`,
						`Vincode`,
						`ModelCode`,
						`EngineCode`,
						`Cc`,
						`Start`,
						`End`,
						`Cprtn`,
						`Prtno`,
						`Cgprtno`,
						`Prtnm`,
						`Brand`,
						`ordprtno`,
						`Remark`,
						`Prtpic`
					FROM
						`$temp_table_name`
						";
			$sqlqc2 = mysqli_query($msqlcon, $qc2);
			if ($sqlqc2) {
				$arr_col_null = array();
				$arr_data_null = array();
				$arr_col_char = array();
				$arr_data_char = array();
				$arr_data = array();

				while($result = mysqli_fetch_assoc($sqlqc2)){
					foreach($result as $k => $v){
						if($k != 'Remark'){
							//check null
							if($v == ''){
								$arr_col_null[$k] = '1';
								$result['Error'] = 'Colunm ' .$k.' can not be blank <br>';
								$arr_data_null[] = $result;
							}
							
							// check char
							if (preg_match('/"/', $v) || preg_match('/[,;`]/', $v) || preg_match("/'/", $v)) {
								$arr_col_char[$k] = '1';
								$result['Error'] = 'Data contain spacial character <br>';
								$arr_data_char[] = $result;
							}
						}
					}
					$arr_data[] = $result;
				}	
			} else {
				// echo "Error: " . mysqli_error($msqlcon);
			}
			
			
			$sql_dup = "SELECT
				`CarMaker`,
				`ModelName`,
				`Vincode`,
				`ModelCode`,
				`EngineCode`,
				`Cc`,
				`Start`,
				`End`,
				`Cprtn`,
				`Prtno`,
				`Cgprtno`,
				`Prtnm`,
				`Brand`,
				`ordprtno`,
				`Remark`,
				`Prtpic`
			FROM
				`$temp_table_name`
			group by 1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16 having count(*) > 1";
			$sql_duplicate = mysqli_query($msqlcon, $sql_dup);
			if ($sql_duplicate) {
					while($result2 = mysqli_fetch_assoc($sql_duplicate)){
					$result2['Error'] = 'Data duplicated ';
					$arr_dup[] = $result2;
				}
			}else {
				// echo "Error: " . mysqli_error($msqlcon);
			}
			
			$arr_error = array_merge($arr_dup, $arr_data_char, $arr_data_null);
			// echo '<pre>';
			// print_r($arr_error);
			// echo '</pre>';

			
			$html_error_row = "";
			$html_error_row.= '<form method="GET" enctype="multipart/form-data" action="admimportPartCate.php">
				<input type="submit" name="nobtn" value="' . get_lng($_SESSION["lng"], "L0521") . '">
				</form>
				<br>
				<table class="tbl1" >
					<tr class="arial11grey" bgcolor="#AD1D36">
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
					<th width="5%" align="center">Part Pic.</th>
					<th width="5%" align="center">Error</th>
				</tr>';
			$limit = 10;
			$i = 0;
			if(sizeof($arr_error) != 0){
				foreach($arr_error as $k => $v){
					
					$vcarmaker 		= $v['CarMaker'];
					$vmodelname 	= $v['ModelName'];
					$vVincode		= $v['Vincode'];
					$vmodelcode 	= $v['ModelCode'];
					$venginecode 	= $v['EngineCode'];
					$vcc			= $v['Cc'];
					$vstart 		= $v['Start'];
					$vend 			= $v['End'];
					$vCprtn			= $v['Cprtn'];
					$vPrtno			= $v['Prtno'];
					$vCgprtno		= $v['Cgprtno'];
					$vPrtnm 		= $v['Prtnm'];
					$vBrand			= $v['Brand'];
					$vordprtno 		= $v['ordprtno'];
					$vremark		= $v['Remark'];
					$vpartpic 		= $v['Prtpic'];
					$verror 		= $v['Error'];
					
					if($i < $limit){
						$i++;
						$html_error_row.= "<tr class=\"arial11black\">
						<td>$vcarmaker</td>
						<td>$vmodelname</td>
						<td>$vVincode</td>
						<td>$vmodelcode</td>
						<td>$venginecode</td>
						<td>$vcc</td>
						<td>$vstart</td>
						<td>$vend</td>
						<td>$vCprtn</td>
						<td>$vPrtno</td>
						<td>$vCgprtno</td>
						<td>$vPrtnm</td>
						<td>$vBrand</td>
						<td>$vordprtno</td>
						<td>$vremark</td>
						<td>$vpartpic</td>
						<td>$verror</td>
						</tr>
						";
					}
				
				}
				$html_error_row.= '</table>';
				$html_error_row.="<div class= \" paging\">  </div>";

				echo $html_error_row;
				echo "<br/><span class='arial21redbold' width='200px'>" . "Data Error Show " .$i. " Out of ".sizeof($arr_error)."</span>";
				echo "<br/><span class='arial21redbold' width='200px'>" . "Note : Data not upload to system"."</span>";
				
				
				$temp_table_name = 'cataloguetemp_'.$comp;
				$query = "delete from `$temp_table_name`";
				// mysqli_query($msqlcon, $query);	
				
			    // echo "<br/><span class='arial21redbold' width='200px'>" . get_lng($_SESSION["lng"], "E0070") . "</span>";

			}else{
				if(!$error_file_st){
					echo '<form method="POST" enctype="multipart/form-data" action="replace-all-catalogue.php">';
					echo '<input type="hidden" name="replace" value="' . $rdreplace . '">';

					echo '<input type="submit" class="yes_btn" name="yesbtn" value="' . get_lng($_SESSION["lng"], "L0473") . '">';
					echo '<input type="submit" name="nobtn" value="' . get_lng($_SESSION["lng"], "L0474") . '">';

					echo '</form>';
					$html_success_row = '';
					$html_success_row.= '
					<table class="tbl1" >
						<tr class="arial11grey" bgcolor="#AD1D36">
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
						<th width="5%" align="center">Part Pic.</th>
					</tr>';
					
					$slimit = 15;
					$si = 0;
					foreach($arr_data as $k => $v){
						
						$vcarmaker 		= $v['CarMaker'];
						$vmodelname 	= $v['ModelName'];
						$vVincode		= $v['Vincode'];
						$vmodelcode 	= $v['ModelCode'];
						$venginecode 	= $v['EngineCode'];
						$vcc			= $v['Cc'];
						$vstart 		= $v['Start'];
						$vend 			= $v['End'];
						$vCprtn			= $v['Cprtn'];
						$vPrtno			= $v['Prtno'];
						$vCgprtno		= $v['Cgprtno'];
						$vPrtnm 		= $v['Prtnm'];
						$vBrand			= $v['Brand'];
						$vordprtno 		= $v['ordprtno'];
						$vremark		= $v['Remark'];
						$vpartpic 		= $v['Prtpic'];
						
						if($si < $slimit){
							$si++;
							$html_success_row.= "<tr class=\"arial11black\">
							<td>$vcarmaker</td>
							<td>$vmodelname</td>
							<td>$vVincode</td>
							<td>$vmodelcode</td>
							<td>$venginecode</td>
							<td>$vcc</td>
							<td>$vstart</td>
							<td>$vend</td>
							<td>$vCprtn</td>
							<td>$vPrtno</td>
							<td>$vCgprtno</td>
							<td>$vPrtnm</td>
							<td>$vBrand</td>
							<td>$vordprtno</td>
							<td>$vremark</td>
							<td>$vpartpic</td>
							</tr>
							";
						}
					
					}
					
					
					
					$html_success_row.= '</table>';
					echo $html_success_row;
					
					echo "<br/><span class='arial21redbold' width='200px'>" . "Data Show " .$si. " Out of ".sizeof($arr_data)."</span>";
					echo "<br/><span class='arial21redbold' width='200px'>" . "Note : Data will replace all of the exist catalogue"."</span>";
				}
				
			}
			

				
            ?>

        

        <div id="footerMain1">
            <ul>

                
                <li class="last"><a href="../Footer/Terms.html">Legal and Policy</a></li>
            </ul>

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
	$(document).ready(function() {
		update_table();
		// alert('s');
		$('.yes_btn').click(function() {
			$('body').prepend('<div class="loading-screen"><div class="spinner"></div><span class="loading-text">Data validation ongoing. Please wait...</span></div>');
			// $(this).attr('disabled', 'disabled');
			
		});
		
	});
	async function update_table() {
		$.ajax({
			type: 'POST',
			url: 'uploadcatalogue_table.php',
			data: {},
			success: await function(data) {
				
				console.log(data);
	
			}
		})
	}
</script>