<?php
session_start();
require_once('./../../core/ctc_init.php'); // add by CTC

require_once('../../language/Lang_Lib.php');
$comp = ctc_get_session_comp(); // add by CTC
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

<?php
            include "../db/conn.inc";
			$temp_table_name = 'cataloguetemp_'.$comp;
			
			
			$arr_error_set = array();
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
					$arr_data[] = $result;

					foreach($result as $k => $v){
						if($k != 'Remark' && $k != 'Prtnm' && $k != 'ModelName'){
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
				}	
			} else {
				// echo "Error: " . mysqli_error($msqlcon);
			}
			
			
			// $sql_dup = "SELECT
				// `CarMaker`,
				// `ModelName`,
				// `Vincode`,
				// `ModelCode`,
				// `EngineCode`,
				// `Cc`,
				// `Start`,
				// `End`,
				// `Cprtn`,
				// `Prtno`,
				// `Cgprtno`,
				// `Prtnm`,
				// `Brand`,
				// `ordprtno`,
				// `Remark`,
				// `Prtpic`
			// FROM
				// `$temp_table_name`
			// group by 1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16 having count(*) > 1";
			$sql_dup = "
			SELECT
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
			GROUP BY 
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
			HAVING 
				COUNT(*) > 1 AND COUNT(DISTINCT CONCAT_WS(',', `CarMaker`, `ModelName`, `Vincode`, `ModelCode`, `EngineCode`, `Cc`, `Start`, `End`, `Cprtn`, `Prtno`, `Cgprtno`, `Prtnm`, `Brand`, `ordprtno`, `Remark`, `Prtpic`)) = 1;
			";
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
			// print_r($arr_error);
			$query = "delete from `$temp_table_name`";
			// mysqli_query($msqlcon, $query);
			$html_error_row = "";
			$limit = 20;
			$num = 1;
			$i = 1;
			// echo sizeof($arr_error);
			if(sizeof($arr_error) != 0){
				foreach($arr_error as $k=>$v){
					if($i <= $limit){
						$i++;
						$arr_error_set[$num][] = $v;
					}else{
						$i = 1;
						$num++;
					}
					
				}
				// print_r($arr_error_set);
				echo json_encode($arr_error_set);
			}else{
				
				// echo '<form method="POST" enctype="multipart/form-data" action="replace-all-catalogue.php">';
				// echo '<input type="hidden" name="replace" value="' . $rdreplace . '">';

				// echo '<input type="submit" class="yes_btn" name="yesbtn" value="' . get_lng($_SESSION["lng"], "L0473") . '">';
				// echo '<input type="submit" name="nobtn" value="' . get_lng($_SESSION["lng"], "L0474") . '">';

				// echo '</form>';
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
				
				$slimit = 20;
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
				$limit = 20;
				$num = 1;
				$i = 1;
				
				foreach($arr_data as $k=>$v){
					if($i <= $limit){
						$i++;
						$arr_pass_set[$num][] = $v;
					}else{
						$i = 1;
						$num++;
					}
					
				}
				// print_r($arr_error_set);
				echo json_encode($arr_pass_set);
				// $html_success_row.= '</table>';
				// echo $html_success_row;
				
				// echo "<br/><span class='arial21redbold' width='200px'>" . "Data Show " .$si. " Out of ".sizeof($arr_data)."</span>";
				// echo "<br/><span class='arial21redbold' width='200px'>" . "Note : Data will replace all of the exist catalogue"."</span>";
			
				
			}
			

				
            ?>