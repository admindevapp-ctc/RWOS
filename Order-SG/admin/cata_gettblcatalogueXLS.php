<?php

session_start();

// Edit by ctc start
require_once('../../core/ctc_init.php');
require_once('../../core/ctc_permission.php');

ctc_checkadmin_permission('../../login.php');
// Edit by ctc end

$namaFile = "PartCatalogue.xlsx";

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
    echo $Value;
    return;
}

// header file excel
$namaFile = "PartCatalogue.xlsx";


header("Content-type: application/vnd.ms-excel");
header("Content-Disposition: attachment;filename=" . $namaFile . "");
header("Pragma: no-cache");
header("Expires: 0");
header("Cache-Control: must-revalidate, post-check=0,pre-check=0");
header("Content-Type: application/force-download");
header("Content-Type: application/octet-stream");
header("Content-Type: application/download");
header("Content-Transfer-Encoding: binary ");

xlsBOF();

// ------ membuat kolom pada excel --- //
// mengisi pada cell A1 (baris ke-0, kolom ke-0)
xlsWriteLabel(0, 0, "Car Maker");
// mengisi pada cell A2 (baris ke-0, kolom ke-1)
xlsWriteLabel(0, 1, "Model Name");
// mengisi pada cell A3 (baris ke-0, kolom ke-2)
xlsWriteLabel(0, 2, "Model Code");
// mengisi pada cell A4 (baris ke-0, kolom ke-3)
xlsWriteLabel(0, 3, "Engine Code");
// mengisi pada cell A5 (baris ke-0, kolom ke-4)
xlsWriteLabel(0, 4, "CC.");
// mengisi pada cell A6 (baris ke-0, kolom ke-5)
xlsWriteLabel(0, 5, "Start Date");
// mengisi pada cell A7 (baris ke-0, kolom ke-6)
xlsWriteLabel(0, 6, "End Date");
// mengisi pada cell A8 (baris ke-0, kolom ke-7)
xlsWriteLabel(0, 7, "Hist.Customer P/NO");
// mengisi pada cell A9 (baris ke-0, kolom ke-8)
xlsWriteLabel(0, 8, "Customer P/NO");
// mengisi pada cell A10 (baris ke-0, kolom ke-9)
xlsWriteLabel(0, 9, "Hist.DENSO P/NO");
// mengisi pada cell A10 (baris ke-0, kolom ke-10)
xlsWriteLabel(0, 10, "DENSO P/NO");
// mengisi pada cell A10 (baris ke-0, kolom ke-11)
xlsWriteLabel(0, 11, "Hist.CG P/NO");
// mengisi pada cell A10 (baris ke-0, kolom ke-12)
xlsWriteLabel(0, 12, "CG P/NO");
// mengisi pada cell A10 (baris ke-0, kolom ke-13)
xlsWriteLabel(0, 13, "Order P/NO"); // Edit by ctc
// mengisi pada cell A10 (baris ke-0, kolom ke-14)
xlsWriteLabel(0, 14, "Part Name"); // Edit by ctc
// mengisi pada cell A10 (baris ke-0, kolom ke-15)
xlsWriteLabel(0, 15, "Remark"); // Edit by ctc

// -------- menampilkan data --------- //
// Edit by ctc start
$catMaker = (string) filter_input(INPUT_GET, 'CatMaker');
$modelName = (string) filter_input(INPUT_GET, 'ModelName');
$modelCode = (string) filter_input(INPUT_GET, 'ModelCode');
$subCatMaker = (string) filter_input(INPUT_GET, 'SubCatMaker');
$subModelName = (string) filter_input(INPUT_GET, 'SubModelName');

$result = ctc_get_catalogue($catMaker, $modelName, $modelCode, $subCatMaker, $subModelName);

$noBarisCell = 1;
foreach ($result as $aRow) {
    $CarMkr = $aRow['CarMaker'];
    $MdlName = $aRow['ModelName'];
    $MdlCode = $aRow['ModelCode'];
    $EngCode = $aRow['EngineCode'];
    $cc = $aRow['Cc'];
    $strdate = $aRow['Start'];
    $enddate = $aRow['End'];
    $Custprt = $aRow['Custprthis'];
    $Cptn = $aRow['Cprtn'];
    $Prthis = $aRow['Prtnohis'];
    $dnPrt = $aRow['Prtno'];
    $cgprthis = $aRow['cgprtnohis'];
    $Cgprt = $aRow['Cgprtno'];
    $Ordprt = $aRow['ordprtno'];
    $Prtnam = $aRow['Prtnm'];
    $Remrk = $aRow['Remark'];

    xlsWriteLabel($noBarisCell, 0, $CarMkr);
    xlsWriteLabel($noBarisCell, 1, $MdlName);
    xlsWriteLabel($noBarisCell, 2, $MdlCode);
    xlsWriteLabel($noBarisCell, 3, $EngCode);
    xlsWriteNumber($noBarisCell, 4, $cc);
    xlsWriteLabel($noBarisCell, 5, $strdate);
    xlsWriteNumber($noBarisCell, 6, $enddate);
    xlsWriteLabel($noBarisCell, 7, $Custprt);
    xlsWriteLabel($noBarisCell, 8, $Cptn);
    xlsWriteLabel($noBarisCell, 9, $Prthis);
    xlsWriteLabel($noBarisCell, 10, $dnPrt);
    xlsWriteLabel($noBarisCell, 11, $cgprthis);
    xlsWriteLabel($noBarisCell, 12, $Cgprt);
    xlsWriteLabel($noBarisCell, 13, $Ordprt);
    xlsWriteLabel($noBarisCell, 14, $Prtnam);
    xlsWriteLabel($noBarisCell, 15, $Remrk);

    $noBarisCell++;
}
// Edit by ctc end
// memanggil function penanda akhir file excel
xlsEOF();
exit();
