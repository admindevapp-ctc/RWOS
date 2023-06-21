<?php
session_start();
require_once('./../../core/ctc_init.php'); // add by CTC

require_once('../../language/Lang_Lib.php');
if (isset($_SESSION['cusno'])) {
    if ($_SESSION['redir'] == 'Order-SG') {
        $cusno =    $_SESSION['cusno'];
        $type = $_SESSION['type'];
        $user = $_SESSION['user'];
        $comp = ctc_get_session_comp();
    } else {
        echo "<script> document.location.href='../" . redir . "'; </script>";
    }
} else {
    header("Location:../../login.php");
}
?>
<html>

<head>
    <title>Denso Ordering System</title>
    <link rel="stylesheet" type="text/css" href="../../css/dnia.css">
    </style><!--[if IE]>
<style type="text/css"> 
#twocolLeft{ padding-top: 0px; }
#twocolRight { zoom: 1; padding-top:10px; }
</style>	
<![endif]-->
    <script type="text/javascript" language="javascript" src="../../lib/jquery-1.4.2.js"></script>


</head>

<body>

    <?php ctc_get_logo(); ?> <!-- add by CTC -->

    <div id="mainNav">

        <?php
        $_GET['step'] = "2";
        // include("../supnavhoriz.php");
        ?>

    </div>
    <div id="isi">

        <div id="twocolLeft">
            <div class="hmenu">
                <?
                $MYROOT = $_SERVER['DOCUMENT_ROOT'];
                $_GET['current'] = "partcatalogue";
                // include("supnavAdm.php");
                ?>
            </div>
        </div>

        <div id="twocolRight">
            <?php
            session_start();
            include "../../db/conn.inc";

            $comp = ctc_get_session_comp(); // add by CTC
            $temp_table_name = 'cataloguetemp_' . $comp;
            // $table = createtemp_catalogue();
            if (isset($_POST['yesbtn'])) {
                    $qd = "DELETE FROM catalogue where Owner_Comp =  '$comp'";
                    mysqli_query($msqlcon, $qd);
                    //insert into catalogue table
					
					$sql = "
						INSERT INTO catalogue (
							`Owner_Comp`,
							CarMaker,
							ModelName,
							PrtPic,
							ModelCode,
							EngineCode,
							Cc,
							Start,
							End,
							Cprtn,
							Prtno,
							Cgprtno,
							Prtnm,
							Remark,
							ordprtno,
							Vincode,
							Brand
						)
						SELECT 
							'$comp',
							CarMaker,
							ModelName,
							PrtPic,
							ModelCode,
							EngineCode,
							Cc,
							Start,
							End,
							Cprtn,
							Prtno,
							Cgprtno,
							Prtnm,
							Remark,
							ordprtno,
							Vincode,
							Brand
						FROM `$temp_table_name`;
					";  
					mysqli_query($msqlcon, $sql);
					
                    $qd3 = "DELETE FROM `$table`";
                    mysqli_query($msqlcon, $qd3);
                    $query = "delete from `$temp_table_name`";
                    mysqli_query($msqlcon, $query);
                    echo "<SCRIPT type=text/javascript>document.location.href='cata_partcatalogue.php'</SCRIPT>";
                } else {
                $qd3 = "DELETE FROM `$table`";
                mysqli_query($msqlcon, $qd3);
                $query = "delete from `$temp_table_name`";
                mysqli_query($msqlcon, $query);
                echo "<SCRIPT type=text/javascript>document.location.href='cata_partcatalogue.php'</SCRIPT>";
            }
            ?>

</body>

</html>


<?php
function createtemp_catalogue()
{

    include "../../db/conn.inc";
    $comp = ctc_get_session_comp(); // add by CTC

    $tblname = "catalogue_temp2_" . $comp;
    $sql = "DESC " . $tblname;
    mysqli_query($msqlcon, $sql);

    if ($msqlcon->errno == 1146) {
        $query_create = "
			CREATE TABLE `$tblname`(
			`CarMaker` text DEFAULT NULL,
			`ModelName` text DEFAULT NULL,
			`ModelCode` text DEFAULT NULL,
			`EngineCode` text DEFAULT NULL,
			`Cc` text DEFAULT NULL,
			`Start` text DEFAULT NULL,
			`End` text DEFAULT NULL,
			`Custprthis` text DEFAULT NULL,
			`Cprtn` text DEFAULT NULL,
			`Prtnohis` text DEFAULT NULL,
			`Prtno` text DEFAULT NULL,
			`cgprtnohis` text DEFAULT NULL,
			`Cgprtno` text DEFAULT NULL,
			`Prtnm` text DEFAULT NULL,
			`Remark` text DEFAULT NULL,
			`ordprtno` text DEFAULT NULL,
			`Vincode` text DEFAULT NULL,
			`Brand` text DEFAULT NULL
			) ENGINE = INNODB DEFAULT CHARSET=utf8;
		";
        mysqli_query($msqlcon, $query_create);
    }

    return $tblname;
}

function date_extract_format($d, $null = '')
{
    // check Day -> (0[1-9]|[1-2][0-9]|3[0-1])
    // check Month -> (0[1-9]|1[0-2])
    // check Year -> [0-9]{4} or \d{4}
    $patterns = array(
        '/\b\d{4}-\d{2}-\d{2}T\d{2}:\d{2}:\d{2}.\d{3,8}Z\b/' => 'Y-m-d\TH:i:s.u\Z', // format DATE ISO 8601
        '/\b\d{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])\b/' => 'Y-m-d',
        '/\b\d{4}-(0[1-9]|[1-2][0-9]|3[0-1])-(0[1-9]|1[0-2])\b/' => 'Y-d-m',
        '/\b(0[1-9]|[1-2][0-9]|3[0-1])-(0[1-9]|1[0-2])-\d{4}\b/' => 'd-m-Y',
        '/\b(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])-\d{4}\b/' => 'm-d-Y',

        '/\b\d{4}\/(0[1-9]|[1-2][0-9]|3[0-1])\/(0[1-9]|1[0-2])\b/' => 'Y/d/m',
        '/\b\d{4}\/(0[1-9]|1[0-2])\/(0[1-9]|[1-2][0-9]|3[0-1])\b/' => 'Y/m/d',
        '/\b(0[1-9]|[1-2][0-9]|3[0-1])\/(0[1-9]|1[0-2])\/\d{4}\b/' => 'd/m/Y',
        '/\b(0[1-9]|1[0-2])\/(0[1-9]|[1-2][0-9]|3[0-1])\/\d{4}\b/' => 'm/d/Y',

        '/\b\d{4}\.(0[1-9]|1[0-2])\.(0[1-9]|[1-2][0-9]|3[0-1])\b/' => 'Y.m.d',
        '/\b\d{4}\.(0[1-9]|[1-2][0-9]|3[0-1])\.(0[1-9]|1[0-2])\b/' => 'Y.d.m',
        '/\b(0[1-9]|[1-2][0-9]|3[0-1])\.(0[1-9]|1[0-2])\.\d{4}\b/' => 'd.m.Y',
        '/\b(0[1-9]|1[0-2])\.(0[1-9]|[1-2][0-9]|3[0-1])\.\d{4}\b/' => 'm.d.Y',

        // for 24-hour | hours seconds
        '/\b(?:2[0-3]|[01][0-9]):[0-5][0-9](:[0-5][0-9])\.\d{3,6}\b/' => 'H:i:s.u',
        '/\b(?:2[0-3]|[01][0-9]):[0-5][0-9](:[0-5][0-9])\b/' => 'H:i:s',
        '/\b(?:2[0-3]|[01][0-9]):[0-5][0-9]\b/' => 'H:i',

        // for 12-hour | hours seconds
        '/\b(?:1[012]|0[0-9]):[0-5][0-9](:[0-5][0-9])\.\d{3,6}\b/' => 'h:i:s.u',
        '/\b(?:1[012]|0[0-9]):[0-5][0-9](:[0-5][0-9])\b/' => 'h:i:s',
        '/\b(?:1[012]|0[0-9]):[0-5][0-9]\b/' => 'h:i',

        '/\.\d{3}\b/' => '.v'
    );
    //$d = preg_replace('/\b\d{2}:\d{2}\b/', 'H:i',$d);
    $d = preg_replace(array_keys($patterns), array_values($patterns), $d);
    return preg_match('/\d/', $d) ? $null : $d;
}


//function date_formating( $date, $format = 'd/m/Y H:i', $in_format = false, $f = '' ) {
function date_formating($date, $format = 'Y-m-d', $in_format = false, $f = '')
{
    $isformat = date_extract_format($date);
    $d = DateTime::createFromFormat($isformat, $date);
    $format = $in_format ? $isformat : $format;
    if ($format) {
        if (in_array($format, ['Y-m-d\TH:i:s.u\Z', 'DATE_ISO8601', 'ISO8601'])) {
            $f = $d ? $d->format('Y-m-d\TH:i:s.') . substr($d->format('u'), 0, 3) . 'Z' : '';
        } else {
            $f = $d ? $d->format($format) : '';
        }
    }
    return $f;
} // end function


function date_convert_format($old = '')
{
    $old = trim($old);
    if (preg_match('/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/', $old)) { // MySQL-compatible YYYY-MM-DD format
        $new = $old;
    } elseif (preg_match('/^[0-9]{4}-(0[1-9]|[1-2][0-9]|3[0-1])-(0[1-9]|1[0-2])$/', $old)) { // DD-MM-YYYY format
        $new = substr($old, 0, 4) . '-' . substr($old, 5, 2) . '-' . substr($old, 8, 2);
    } elseif (preg_match('/^(0[1-9]|[1-2][0-9]|3[0-1])-(0[1-9]|1[0-2])-[0-9]{4}$/', $old)) { // DD-MM-YYYY format
        $new = substr($old, 6, 4) . '-' . substr($old, 3, 2) . '-' . substr($old, 0, 2);
    } elseif (preg_match('/^(0[1-9]|[1-2][0-9]|3[0-1])-(0[1-9]|1[0-2])-[0-9]{2}$/', $old)) { // DD-MM-YY format
        $new = substr($old, 6, 4) . '-' . substr($old, 3, 2) . '-20' . substr($old, 0, 2);
    } else { // Any other format. Set it as an empty date.
        $new = '0000-00-00';
    }
    return $new;
}
?>