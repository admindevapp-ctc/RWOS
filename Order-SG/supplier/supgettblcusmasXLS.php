<?php

session_start();

// Edit by ctc start
require_once('../../core/ctc_init.php');
require_once('../../core/ctc_permission.php');
$supno=$_SESSION['supno'];

ctc_checkadmin_permission('../../login.php');
// Edit by ctc end

$comp = ctc_get_session_comp(); 

/*
/// Function penanda awal file (Begin Of File) Excel

function xlsBOF() {
    echo pack("ssssss", 0x809, 0x8, 0x0, 0x10, 0x0, 0x0);
    return;
}

// Function penanda akhir file (End Of File) Excel

function xlsEOF() {
    echo pack("ss", 0x0A, 0x00);
    return;
}

// Function untuk menulis data (angka) ke cell excel

function xlsWriteNumber($Row, $Col, $Value) {
    echo pack("sssss", 0x203, 14, $Row, $Col, 0x0);
    echo pack("d", $Value);
    return;
}

// Function untuk menulis data (text) ke cell excel

function xlsWriteLabel($Row, $Col, $Value) {
    $L = strlen($Value);
    echo pack("ssssss", 0x204, 8 + $L, $Row, $Col, 0x0, $L);
    echo $Value ;
   // echo $Value .";" ;
    return;
}

// header file excel
$namaFile = "customerlist.xls";


header("Content-type: application/vnd.ms-excel");
header("Content-Disposition: attachment;filename=" . $namaFile . "");
header("Pragma: no-cache");
header("Expires: 0");
header("Cache-Control: must-revalidate, post-check=0,pre-check=0");
header("Content-Type: application/force-download");
header("Content-Type: application/octet-stream");
header("Content-Type: application/download");
header("Content-Transfer-Encoding: binary ");
header('Content-type: text/html;charset=tis-620');
//print "\xEF\xBB\xBF"; // UTF-8 BOM
xlsBOF();

// ------ membuat kolom pada excel --- //
// mengisi pada cell A1 (baris ke-0, kolom ke-0)
xlsWriteLabel(0, 0, "Company Code");
// mengisi pada cell A2 (baris ke-0, kolom ke-1)
xlsWriteLabel(0, 1, "Customer");
// mengisi pada cell A3 (baris ke-0, kolom ke-2)
xlsWriteLabel(0, 2, "Shipto");
// mengisi pada cell A4 (baris ke-0, kolom ke-3)
xlsWriteLabel(0, 3, "Customer Name");
// mengisi pada cell A5 (baris ke-0, kolom ke-4)
xlsWriteLabel(0, 4, "Address1");
// mengisi pada cell A6 (baris ke-0, kolom ke-5)
xlsWriteLabel(0, 5, "Address2");
// mengisi pada cell A7 (baris ke-0, kolom ke-6)
xlsWriteLabel(0, 6, "Address3");
// -------- menampilkan data --------- //

*/

	/* Database connection information */
	require('../db/conn.inc');
	
		
	$query="select supref.Owner_Comp, supref.cusno, shiptoma.ship_to_cd, cusmas.Cusnm, shiptoma.adrs1
    , shiptoma.adrs2, shiptoma.adrs3 from supref join cusmas on supref.cusno = cusmas.cusno 
    and supref.Owner_Comp = cusmas.Owner_Comp
    left join shiptoma on supref.cusno = shiptoma.cusno and shiptoma.Owner_Comp = supref.Owner_Comp";
    
    $condition = " where supref.Owner_Comp = '$comp' and supref.supno = '$supno'"; 

    $vcuscode=trim($_GET['cuscode']);
    if($vcuscode!='' && $vcuscode!='null'){
        $condition .= "  and supref.Cusno = '$vcuscode'";
    }

    $condition .= " order by supref.cusno, shiptoma.ship_to_cd";
    $query = $query . $condition;
    // Header
$datas = [];
    $header = ['Customer Code', 'Customer Name',
    'Shipto','Address1','Address2','Address3'];
    $datas[0] = $header;
  //echo $query ."<br/><br/>";
	$sql=mysqli_query($msqlcon,$query);
//		echo $query;
	$noBarisCell = 1;
	$rResult = mysqli_query($msqlcon, $query ) or die(mysqli_error());
	while ( $aRow = mysqli_fetch_array( $rResult ) )
	{
        $vowner=$aRow['Owner_Comp'];
        $vcusno=$aRow['cusno'];
        $vcusnm=$aRow['Cusnm'];
        $vshipto=$aRow['ship_to_cd'];
        $vaddr1=$aRow['adrs1'];
        $vaddr2=$aRow['adrs2'];
        $vaddr3=$aRow['adrs3'];

        $data= [$vcusno,$vcusnm,$vshipto,$vaddr1,$vaddr2,$vaddr3];
	
        array_push($datas,$data);
	/*
		
	// menampilkan no. urut data
		xlsWriteLabel($noBarisCell,0,$vowner);
 
// menampilkan data nim
		xlsWriteLabel($noBarisCell,1,$vcusno);
		xlsWriteLabel($noBarisCell,2,$vshipto);
		xlsWriteLabel($noBarisCell,3,$vcusnm);
		xlsWriteLabel($noBarisCell,4,$vaddr1);
		xlsWriteLabel($noBarisCell,5,$vaddr2);
		xlsWriteLabel($noBarisCell,6,$vaddr3);
 		$noBarisCell++;

	
    }
// Edit by ctc end
// memanggil function penanda akhir file excel
xlsEOF();
exit();
*/
    }

$xlsx = SimpleXLSXGen::fromArray( $datas );
$xlsx->downloadAs('customerlist.xlsx');