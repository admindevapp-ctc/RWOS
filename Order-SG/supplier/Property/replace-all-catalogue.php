<?php 
session_start();
require_once('./../../../core/ctc_init.php'); // add by CTC

require_once('../../../language/Lang_Lib.php');
if(isset($_SESSION['cusno']))
{       
	if($_SESSION['redir']=='Order-SG'){
		$cusno=	$_SESSION['cusno'];
		$type=$_SESSION['type'];
		$user=$_SESSION['user'];
	 }else{
		   echo "<script> document.location.href='../".redir."'; </script>";
	 }
}else{	
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
			  	$_GET['step']="2";
				include("../supnavhoriz.php");
			?>
			
		</div> 
    	<div id="isi">
        
        <div id="twocolLeft">
        	<div class="hmenu">
           	 <?
			  	$MYROOT=$_SERVER['DOCUMENT_ROOT'];
			  	$_GET['current']="supmainSlsAdm";
				include("supnavAdm.php");
			  ?>
              </div>
        </div>
            
        <div id="twocolRight">
<?php
session_start();
include "../../db/conn.inc";

$comp = ctc_get_session_comp(); // add by CTC
$supno=$_SESSION['supno'];
if(isset($_POST['yesbtn'])){
	
	$table = createtemp_supcatalogue();
		// Insert data temp
	$qa2="INSERT INTO $table(CarMaker,ModelName,PrtPic,ModelCode,EngineCode,Cc
    ,Start,End,Cprtn,Prtno,ordprtno,Supcd,Prtnm, Vincode,Lotsize,Brand,Remark,Owner_Comp,MTO)";
	$qa2=$qa2." SELECT CarMaker,ModelName,PrtPic,ModelCode,EngineCode,CC
    ,StartDate,EndDate,`Genuine Part No`,`SupplierGenuine Part No`,`Order Part No`,'$supno' 
	,PartName, VINCode,LotSize,ProductBrand,Remark,'$comp',MTO FROM supcataloguetemp ";
//echo $qa2;
$result = mysqli_query($msqlcon,$qa2) ; 
if (!$result)  {   
	$error_msg = $msqlcon->error;
?>
<form method="POST" enctype="multipart/form-data" action="../supimportPartCate.php">
<input type="submit" id="submitButton" value="<?php echo get_lng($_SESSION["lng"], "L0521") ?>" style="margin-bottom:20px;"/><br/>
<span class="arial21redbold" style="width:200px"><?php echo $error_msg ; ?></span>

</form>
<?
 		echo '<br>';
		echo '<table class="tbl1" >';
		echo '<tr class="arial11grey" bgcolor="#AD1D36" >';
		echo '<th width="5%" align="center">'. get_lng($_SESSION["lng"], "L0526") .'</th><th width="5%" align="center">'. get_lng($_SESSION["lng"], "L0527") .'</th>';
		echo '<th width="5%" align="center">'. get_lng($_SESSION["lng"], "L0528") .'</th><th width="5%" align="center">'. get_lng($_SESSION["lng"], "L0529") .'</th>';
		echo '<th width="5%" align="center">'. get_lng($_SESSION["lng"], "L0530") .'</th><th width="5%" align="center">'. get_lng($_SESSION["lng"], "L0531") .'</th>';
		echo '<th width="5%" align="center">'. get_lng($_SESSION["lng"], "L0532") .'</th><th width="5%" align="center">'. get_lng($_SESSION["lng"], "L0533") .'</th>';
		echo '<th width="5%" align="center">'. get_lng($_SESSION["lng"], "L0534") .'</th> <th width="5%" align="center">'. get_lng($_SESSION["lng"], "L0535") .'</th>' ;
		echo '<th width="5%" align="center">'. get_lng($_SESSION["lng"], "L0536") .'</th> <th width="5%" align="center">'. get_lng($_SESSION["lng"], "L0537") .'</th> ';
		echo '<th width="5%" align="center">'. get_lng($_SESSION["lng"], "L0538") .'</th> <th width="5%" align="center">'. get_lng($_SESSION["lng"], "L0539") .'</th>' ;
		echo '<th width="5%" align="center">'. get_lng($_SESSION["lng"], "L0540") .'</th> <th width="5%" align="center">'. get_lng($_SESSION["lng"], "L0541") .'</th>' ;
		echo '<th width="5%" align="center">'. get_lng($_SESSION["lng"], "L0542") .'</th></tr>';
	   
		$qa2="SELECT * FROM supcataloguetemp  LIMIT 10";
		//echo $qa2."<br/><br/>";
		$sqlqa2=mysqli_query($msqlcon,$qa2);
		while($arrqa2=mysqli_fetch_array($sqlqa2)){
			$vcarmaker      =$arrqa2['CarMaker'];
			$vmodelname     =$arrqa2['ModelName'];
			$vprtpic        =$arrqa2['PrtPic'];
			$vmodelcode     =$arrqa2['ModelCode'];
			$venginecode    =$arrqa2['EngineCode'];
			$vcc            =$arrqa2['CC'];
			$vstart         =$arrqa2['StartDate'];
			$vend           =$arrqa2['EndDate'];
			$vcprtn         =$arrqa2['Genuine Part No'];
			$vprtno         =$arrqa2['SupplierGenuine Part No'];
			$vordprtno      =$arrqa2['Order Part No'];
			$vprtnm         =$arrqa2['PartName'];
			$vvincode       =$arrqa2['VINCode'];
			$vlotsize       =$arrqa2['LotSize'];
			$vbrand         =$arrqa2['ProductBrand'];
			$vmto           =$arrqa2['MTO'];
			$vremark        =$arrqa2['Remark'];
			$supno          =$arrqa2['SupplierCode'];
			echo "<tr class=\"arial11black\">
				<td>$vcarmaker</td>
				<td>$vmodelname</td>
				<td>$vprtpic</td>
				<td>$vmodelcode</td>
				<td>$venginecode</td>
				<td>$vcc</td>
				<td>$vstart</td>
				<td>$vend</td>
				<td>$vcprtn</td>
				<td>$vprtno</td>
				<td>$vordprtno</td>
				<td>$vprtnm</td>
				<td>$vvincode</td>
				<td>$vlotsize</td>
				<td>$vbrand</td>
				<td>$vmto</td>
				<td>$vremark</td>
			</tr>";
		}
		echo "</table>"; 
		echo "<br/><span class='arial21redbold' style='width:200px'>". $error_msg . "</span>";

		}
		else{
			$qd="DELETE FROM supcatalogue where Owner_Comp='$comp' and Supcd = '$supno'";
			mysqli_query($msqlcon,$qd);
			$qa="SELECT CarMaker,ModelName,PrtPic,ModelCode,EngineCode,CC
			,StartDate,EndDate,`Genuine Part No`,`SupplierGenuine Part No`,`Order Part No`,'$supno' ,PartName, VINCode,LotSize,ProductBrand,Remark,'$comp',MTO 
				FROM supcataloguetemp   ";
			//echo $qa;
			$sqlqa=mysqli_query($msqlcon,$qa);
			while($arrqa=mysqli_fetch_array($sqlqa)){
				$search=array("'","�");
				$replace=array("\\'","A");
		
				$vcarmaker      =$arrqa['CarMaker'];
				$vmodelname     =$arrqa['ModelName'];
				$vprtpic        =$arrqa['PrtPic'];
				$vmodelcode     =$arrqa['ModelCode'];
				$venginecode    =$arrqa['EngineCode'];
				$vcc            =$arrqa['CC'];
				//$vstart         =date_formating($arrqa['StartDate']);
				//$vend           =date_formating($arrqa['EndDate']);
				$vstart         =$arrqa['StartDate'];
				$vend           =$arrqa['EndDate'];
				$vcprtn         =$arrqa['Genuine Part No'];
				$vprtno         =$arrqa['SupplierGenuine Part No'];
				$vordprtno      =$arrqa['Order Part No'];
				$vprtnm         =$arrqa['PartName'];
				$vvincode       =$arrqa['VINCode'];
				$vlotsize       =$arrqa['LotSize'];
				$vbrand         =$arrqa['ProductBrand'];
				$vmto           =$arrqa['MTO'];
				$vremark        =$arrqa['Remark'];
				$stdprice       =$arrqa['StdPrice'];

				$qi2="INSERT INTO supcatalogue(CarMaker,ModelName,PrtPic,ModelCode,EngineCode,Cc
				,Start,End,Cprtn,Prtno,ordprtno,Supcd,Prtnm, Vincode,Lotsize,Brand,Remark,Owner_Comp,MTO)  
				VALUES('$vcarmaker','$vmodelname','$vprtpic','$vmodelcode'
					,'$venginecode','$vcc','$vstart','$vend','$vcprtn','$vprtno','$vordprtno','$supno'
					,'$vprtnm','$vvincode','$vlotsize','$vbrand','$vremark','$comp','$vmto')"; 
				mysqli_query($msqlcon,$qi2);
//,'$venginecode','$vcc','" .date_formating($vstart)."','" .date_formating($vend)."','$vcprtn','$vprtno','$vordprtno','$supno'
				//echo $qi2 ."<br/>";
			 }
			// Insert data from temp
			//$qa2="INSERT INTO supcatalogue(CarMaker,ModelName,PrtPic,ModelCode,EngineCode,Cc
			//,Start,End,Cprtn,Prtno,ordprtno,Supcd,Prtnm, Vincode,Lotsize,Brand,Remark,Owner_Comp,MTO)";
			//$qa2=$qa2." ";
			//mysqli_query($msqlcon,$qa2);

			$qd3="DELETE FROM supcatalogue_temp2 where Owner_Comp='$comp' and Supcd = '$supno'";
			mysqli_query($msqlcon,$qd3);

			echo "<SCRIPT type=text/javascript>document.location.href='../supcata_cataloguemain.php'</SCRIPT>";
		}
 }
 else{
	$qd="DELETE FROM supcataloguetemp where Owner_Comp='$comp' and Supcd = '$supno'";
	mysqli_query($msqlcon,$qd);
	echo "<SCRIPT type=text/javascript>document.location.href='../supcata_cataloguemain.php'</SCRIPT>";
 }
?>

</body>
</html>


<?php
function createtemp_supcatalogue(){

	include "../../db/conn.inc";

	$tblname = "supcatalogue_temp2";
	$sql = "DESC " . $tblname;
	mysqli_query($msqlcon,$sql);

	if ($msqlcon->errno == 1146) {
		$query2 = "CREATE TABLE " . $tblname . "  (
			`Owner_Comp` varchar(3) CHARACTER SET utf8 NOT NULL,
			`CarMaker` varchar(30) CHARACTER SET utf8 NOT NULL DEFAULT '0',
			`ModelName` varchar(30) CHARACTER SET utf8 NOT NULL DEFAULT '0',
			`PrtPic` varchar(200) CHARACTER SET utf8 NOT NULL DEFAULT '0',
			`ModelCode` varchar(30) CHARACTER SET utf8 NOT NULL DEFAULT '0',
			`EngineCode` varchar(30) CHARACTER SET utf8 NOT NULL DEFAULT '0',
			`Cc` varchar(30) CHARACTER SET utf8 NOT NULL DEFAULT '0',
			`Start` varchar(10) CHARACTER SET utf8 NOT NULL DEFAULT '0',
			`End` varchar(10) CHARACTER SET utf8 NOT NULL DEFAULT '0',
			`Cprtn` varchar(30) CHARACTER SET utf8 NOT NULL DEFAULT '0' COMMENT 'Genuine P/NO',
			`Prtno` varchar(30) CHARACTER SET utf8 NOT NULL DEFAULT '0' COMMENT 'Supplier Genuine P/NO',
			`Prtnm` mediumtext CHARACTER SET utf8 NOT NULL COMMENT 'Part Name',
			`Remark` mediumtext CHARACTER SET utf8 DEFAULT NULL,
			`ordprtno` varchar(30) CHARACTER SET utf8 NOT NULL DEFAULT '0' COMMENT 'Order P/NO',
			`Supcd` varchar(8) CHARACTER SET utf8 NOT NULL DEFAULT '0',
			`Vincode` varchar(50) CHARACTER SET utf8 DEFAULT NULL,
			`LotSize` decimal(10,3) NOT NULL DEFAULT 0.000,
			`Brand` varchar(50) CHARACTER SET utf8 NOT NULL DEFAULT '0',
			`MTO` varchar(1) CHARACTER SET utf8 NOT NULL DEFAULT '0',
			`Extra1` varchar(150) CHARACTER SET utf8 NOT NULL DEFAULT '0',
			`Extra2` varchar(150) CHARACTER SET utf8 NOT NULL DEFAULT '0',
			`Extra3` varchar(150) CHARACTER SET utf8 NOT NULL DEFAULT '0'
			) ENGINE=InnoDB DEFAULT CHARSET=utf8";
		mysqli_query($msqlcon,$query2);
	} 

	return $tblname;
}

function date_extract_format( $d, $null = '' ) {
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
    $d = preg_replace( array_keys( $patterns ), array_values( $patterns ), $d );
    return preg_match( '/\d/', $d ) ? $null : $d;
}


//function date_formating( $date, $format = 'd/m/Y H:i', $in_format = false, $f = '' ) {
function date_formating( $date, $format = 'Y-m-d', $in_format = false, $f = '' ) {
    $isformat = date_extract_format( $date );
    $d = DateTime::createFromFormat( $isformat, $date );
    $format = $in_format ? $isformat : $format;
    if ( $format ) {
        if ( in_array( $format, [ 'Y-m-d\TH:i:s.u\Z', 'DATE_ISO8601', 'ISO8601' ] ) ) {
            $f = $d ? $d->format( 'Y-m-d\TH:i:s.' ) . substr( $d->format( 'u' ), 0, 3 ) . 'Z': '';
        } else {
            $f = $d ? $d->format( $format ) : '';
        }
    }
    return $f;
} // end function


function date_convert_format( $old = '' ) {
    $old = trim( $old );
    if ( preg_match( '/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/', $old ) ) { // MySQL-compatible YYYY-MM-DD format
        $new = $old;
    } elseif ( preg_match( '/^[0-9]{4}-(0[1-9]|[1-2][0-9]|3[0-1])-(0[1-9]|1[0-2])$/', $old ) ) { // DD-MM-YYYY format
        $new = substr( $old, 0, 4 ) . '-' . substr( $old, 5, 2 ) . '-' . substr( $old, 8, 2 );
    } elseif ( preg_match( '/^(0[1-9]|[1-2][0-9]|3[0-1])-(0[1-9]|1[0-2])-[0-9]{4}$/', $old ) ) { // DD-MM-YYYY format
        $new = substr( $old, 6, 4 ) . '-' . substr( $old, 3, 2 ) . '-' . substr( $old, 0, 2 );
    } elseif ( preg_match( '/^(0[1-9]|[1-2][0-9]|3[0-1])-(0[1-9]|1[0-2])-[0-9]{2}$/', $old ) ) { // DD-MM-YY format
        $new = substr( $old, 6, 4 ) . '-' . substr( $old, 3, 2 ) . '-20' . substr( $old, 0, 2 );
    } else { // Any other format. Set it as an empty date.
        $new = '0000-00-00';
    }
    return $new;
}
?>