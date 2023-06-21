<?php 
session_start();
require_once('./../../core/ctc_init.php'); // add by CTC

require_once('../../language/Lang_Lib.php');
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
			  	$_GET['current']="partcatalogue";
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
	
	$table = createtemp_catalogue();
	
	
		// Insert data temp
	// $qa2="INSERT INTO $table(CarMaker,ModelName,ModelCode,EngineCode,Cc
    // ,Start,End,Cprtn,Prtno,ordprtno,Supcd,Prtnm, Vincode,Lotsize,Brand,Remark,Owner_Comp,MTO)";
	// $qa2=$qa2." SELECT CarMaker,ModelName,ModelCode,EngineCode,CC
    // ,StartDate,EndDate,`Genuine Part No`,`SupplierGenuine Part No`,`Order Part No`,'$supno' 
	// ,PartName, VINCode,LotSize,ProductBrand,Remark,'$comp',MTO FROM cataloguetemp ";
	
	// echo $table;
	
	$qa2 = "
		INSERT INTO $table(
			`CarMaker`,
			`ModelName`,
			`ModelCode`,
			`EngineCode`,
			`Cc`,
			`Start`,
			`End`,
			`Custprthis`,
			`Cprtn`,
			`Prtnohis`,
			`Prtno`,
			`cgprtnohis`,
			`Cgprtno`,
			`Prtnm`,
			`Remark`,
			`ordprtno`,
			`Vincode`,
			`Brand`
			)
			
		";
	$qa2=$qa2." 
		SELECT
			`CarMaker`,
			`ModelName`,
			`ModelCode`,
			`EngineCode`,
			`Cc`,
			`Start`,
			`End`,
			`Custprthis`,
			`Cprtn`,
			`Prtnohis`,
			`Prtno`,
			`cgprtnohis`,
			`Cgprtno`,
			`Prtnm`,
			`Remark`,
			`ordprtno`,
			`Vincode`,
			`Brand`
		FROM cataloguetemp ";
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
			echo '<tr class="arial11grey" bgcolor="#AD1D36">';
				echo '<th width="5%" align="center">CarMaker</th>';
				echo '<th width="5%" align="center">ModelName</th>';
				echo '<th width="5%" align="center">ModelCode</th>';
				echo '<th width="5%" align="center">EngineCode</th>';
				echo '<th width="5%" align="center">Cc</th>';
				echo '<th width="5%" align="center">Start</th>';
				echo '<th width="5%" align="center">End</th>';
				echo '<th width="5%" align="center">Custprthis</th>';
				echo '<th width="5%" align="center">Cprtn</th>';
				echo '<th width="5%" align="center">Prtnohis</th>';
				echo '<th width="5%" align="center">Prtno</th>';
				echo '<th width="5%" align="center">cgprtnohis</th>';
				echo '<th width="5%" align="center">Cgprtno</th>';
				echo '<th width="5%" align="center">Prtnm</th>';
				echo '<th width="5%" align="center">ordprtno</th>';
				echo '<th width="5%" align="center">Vincode</th>';
				echo '<th width="5%" align="center">Brand</th>';
				echo '<th width="5%" align="center">Remark</th>';
			echo '</tr>';
	   
		$qa2="SELECT * FROM cataloguetemp LIMIT 10";
		// echo $qa2."<br/><br/>";
		
		$sqlqa2=mysqli_query($msqlcon,$qa2);
		while($arrqa2=mysqli_fetch_array($sqlqa2)){
			$vcarmaker      =$arrqa2['CarMaker'];
			$vmodelname     =$arrqa2['ModelName'];
			$vmodelcode     =$arrqa2['ModelCode'];
			$venginecode    =$arrqa2['EngineCode'];
			$vcc            =$arrqa2['Cc'];
			$vstart         =$arrqa2['Start'];
			$vend           =$arrqa2['End'];
			$vCustprthis	=$arrqa2['Custprthis'];
			$vCprtn			=$arrqa2['Cprtn'];
			$vPrtnohis		=$arrqa2['Prtnohis'];
			$vPrtno			=$arrqa2['Prtno'];
			$vcgprtnohis	=$arrqa2['cgprtnohis'];
			$vCgprtno		=$arrqa2['Cgprtno'];
			$vPrtnm			=$arrqa2['Prtnm'];
			$vordprtno		=$arrqa2['ordprtno'];
			$vVincode		=$arrqa2['Vincode'];
			$vBrand			=$arrqa2['Brand'];
			$vremark        =$arrqa2['Remark'];
			
			
			echo "<tr class=\"arial11black\">
				<td>$vcarmaker</td>
				<td>$vmodelname</td>
				<td>$vmodelcode</td>
				<td>$venginecode</td>
				<td>$vcc</td>
				<td>$vstart</td>
				<td>$vend</td>
				<td>$vCustprthis</td>
				<td>$vCprtn</td>
				<td>$vPrtnohis</td>
				<td>$vPrtno</td>
				<td>$vcgprtnohis</td>
				<td>$vCgprtno</td>
				<td>$vPrtnm</td>
				<td>$vordprtno</td>
				<td>$vVincode</td>
				<td>$vBrand</td>
				<td>$vremark</td>
			</tr>";
		}
		echo "</table>"; 
		echo "<br/><span class='arial21redbold' style='width:200px'>". $error_msg . "</span>";
		}
		else{
			$qd="DELETE FROM catalogue where Owner_Comp =  '$comp'";
			mysqli_query($msqlcon,$qd);
			$qa="SELECT CarMaker,ModelName,PrtPic,ModelCode,EngineCode,CC
			,StartDate,EndDate,`Genuine Part No`,`SupplierGenuine Part No`,`Order Part No`,PartName, VINCode,LotSize,ProductBrand,Remark,'$comp',MTO 
				FROM cataloguetemp where 1";
			$qa_new = "SELECT     
				`CarMaker`,
				`ModelName`,
				`ModelCode`,
				`EngineCode`,
				`Cc`,
				`Start`,
				`End`,
				`Custprthis`,
				`Cprtn`,
				`Prtnohis`,
				`Prtno`,
				`cgprtnohis`,
				`Cgprtno`,
				`Prtnm`,
				`Remark`,
				`ordprtno`,
				`Vincode`,
				`Brand` FROM `cataloguetemp` WHERE 1 ";
			$sqlqa=mysqli_query($msqlcon,$qa_new);
			while($arrqa=mysqli_fetch_array($sqlqa)){
				$search=array("'","�");
				$replace=array("\\'","A");
				
				// $vCarMaker		 	=	$arrqa['CarMaker'];
				// $vModelName 		=	$arrqa['ModelName'];
				// $vVINCode 			=	$arrqa['VINCode'];
				// $vModelCode 		=	$arrqa['ModelCode'];
				// $vEngineCode 		=	$arrqa['EngineCode'];
				// $vCC 				=	$arrqa['CC'];
				// $vStartDate 		=	$arrqa['StartDate'];
				// $vEndDate 			=	$arrqa['EndDate'];
				// $vGenuine 			=	$arrqa['Genuine Part No'];
				// $vDENSO 			=	$arrqa['DENSO Part No'];
				// $vCG 				=	$arrqa['CG Part No'];
				// $vPartName 			=	$arrqa['PartName'];
				// $vProductBrand 		=	$arrqa['ProductBrand'];
				// $vOrder 			=	$arrqa['Order Part No'];
				// $vPrice 			=	$arrqa['Standard Price'];
				// $vLotSize 			=	$arrqa['LotSize'];
				// $vStock 			=	$arrqa['Stock'];
				// $vRemark			=	$arrqa['Remark'];
				
				
				$vCarMaker 		= $arrqa['CarMaker'];
				$vModelName		= $arrqa['ModelName'];
				$vModelCode		= $arrqa['ModelCode'];
				$vEngineCode	= $arrqa['EngineCode'];
				$vCc			= $arrqa['Cc'];
				$vStart			= $arrqa['Start'];
				$vEnd			= $arrqa['End'];
				$vCustprthis	= $arrqa['Custprthis'];
				$vCprtn			= $arrqa['Cprtn'];
				$vPrtnohis		= $arrqa['Prtnohis'];
				$vPrtno			= $arrqa['Prtno'];
				$vcgprtnohis	= $arrqa['cgprtnohis'];
				$vCgprtno		= $arrqa['Cgprtno'];
				$vPrtnm			= $arrqa['Prtnm'];
				$vRemark		= $arrqa['Remark'];
				$vordprtno		= $arrqa['ordprtno'];
				$vVincode		= $arrqa['Vincode'];
				$vBrand			= $arrqa['Brand'];
				
				// $qi2="INSERT INTO catalogue(CarMaker,ModelName,PrtPic,ModelCode,EngineCode,Cc
				// ,Start,End,Cprtn,Prtno,ordprtno,Prtnm, Vincode,Lotsize,Brand,Remark,Owner_Comp,MTO)  
				// VALUES('$vcarmaker','$vmodelname','$vprtpic','$vmodelcode'
					// ,'$venginecode','$vcc','$vstart','$vend','$vcprtn','$vprtno','$vordprtno'
					// ,'$vprtnm','$vvincode','$vlotsize','$vbrand','$vremark','$comp','$vmto')"; 
					
				$qi2 = "
					INSERT INTO `catalogue`(
						`Owner_Comp`,
						`CarMaker`,
						`ModelName`,
						`ModelCode`,
						`EngineCode`,
						`Cc`,
						`Start`,
						`End`,
						`Custprthis`,
						`Cprtn`,
						`Prtnohis`,
						`Prtno`,
						`cgprtnohis`,
						`Cgprtno`,
						`Prtnm`,
						`Remark`,
						`ordprtno`,
						`Vincode`,
						`Brand`
					)
					VALUES(
						'$comp',
						'$vCarMaker', 	
						'$vModelName',	
						'$vModelCode',	
						'$vEngineCode',
						'$vCc',		
						'$vStart',		
						'$vEnd',		
						'$vCustprthis',
						'$vCprtn',		
						'$vPrtnohis',
						'$vPrtno',		
						'$vcgprtnohis',
						'$vCgprtno',	
						'$vPrtnm',		
						'$vRemark',	
						'$vordprtno',	
						'$vVincode',	
						'$vBrand'		
					);
				";
				// $qi2_new = "
				// INSERT INTO `catalogue`(`Owner_Comp`, `CarMaker`, `ModelName`, `PrtPic`, `ModelCode`, `EngineCode`, `Cc`, `Start`, `End`, `Custprthis`, `Cprtn`, `Prtnohis`, `Prtno`, `cgprtnohis`, `Cgprtno`, `Prtnm`, `Remark`, `ordprtno`, `Vincode`, `Brand`) VALUES ('$comp','$vcarmaker' ,'$vmodelname','$vprtpic','$vmodelcode','$venginecode','$vcc','$vstart','$vend','','','',)
				// ";
				mysqli_query($msqlcon,$qi2);
				
//,'$venginecode','$vcc','" .date_formating($vstart)."','" .date_formating($vend)."','$vcprtn','$vprtno','$vordprtno','$supno'
				//echo $qi2 ."<br/>";
			}
			
			// Insert data from temp
			//$qa2="INSERT INTO catalogue(CarMaker,ModelName,PrtPic,ModelCode,EngineCode,Cc
			//,Start,End,Cprtn,Prtno,ordprtno,Supcd,Prtnm, Vincode,Lotsize,Brand,Remark,Owner_Comp,MTO)";
			//$qa2=$qa2." ";
			//mysqli_query($msqlcon,$qa2);

			$qd3="DELETE FROM catalogue_temp2";
			mysqli_query($msqlcon,$qd3);

			echo "<SCRIPT type=text/javascript>document.location.href='cata_partcatalogue.php'</SCRIPT>";
		}
 }
 else{
	$qd="DELETE FROM cataloguetemp";
	mysqli_query($msqlcon,$qd);
	echo "<SCRIPT type=text/javascript>document.location.href='cata_partcatalogue.php'</SCRIPT>";
 }
?>

</body>
</html>


<?php
function createtemp_catalogue(){

	include "../../db/conn.inc";

	$tblname = "catalogue_temp2";
	$sql = "DESC " . $tblname;
	mysqli_query($msqlcon,$sql);

	if ($msqlcon->errno == 1146) {
		// $query2 = "CREATE TABLE " . $tblname . "  (
			// `Owner_Comp` varchar(3) CHARACTER SET utf8 NOT NULL,
			// `CarMaker` varchar(30) CHARACTER SET utf8 NOT NULL DEFAULT '0',
			// `ModelName` varchar(30) CHARACTER SET utf8 NOT NULL DEFAULT '0',
			// `ModelCode` varchar(30) CHARACTER SET utf8 NOT NULL DEFAULT '0',
			// `EngineCode` varchar(30) CHARACTER SET utf8 NOT NULL DEFAULT '0',
			// `Cc` varchar(30) CHARACTER SET utf8 NOT NULL DEFAULT '0',
			// `Start` varchar(10) CHARACTER SET utf8 NOT NULL DEFAULT '0',
			// `End` varchar(10) CHARACTER SET utf8 NOT NULL DEFAULT '0',
			// `Cprtn` varchar(30) CHARACTER SET utf8 NOT NULL DEFAULT '0' COMMENT 'Genuine P/NO',
			// `Prtno` varchar(30) CHARACTER SET utf8 NOT NULL DEFAULT '0' COMMENT 'Supplier Genuine P/NO',
			// `Prtnm` mediumtext CHARACTER SET utf8 NOT NULL COMMENT 'Part Name',
			// `Remark` mediumtext CHARACTER SET utf8 DEFAULT NULL,
			// `ordprtno` varchar(30) CHARACTER SET utf8 NOT NULL DEFAULT '0' COMMENT 'Order P/NO',
			// `Supcd` varchar(8) CHARACTER SET utf8 NOT NULL DEFAULT '0',
			// `Vincode` varchar(50) CHARACTER SET utf8 DEFAULT NULL,
			// `LotSize` decimal(10,3) NOT NULL DEFAULT 0.000,
			// `Brand` varchar(50) CHARACTER SET utf8 NOT NULL DEFAULT '0',
			// `MTO` varchar(1) CHARACTER SET utf8 NOT NULL DEFAULT '0',
			// `Extra1` varchar(150) CHARACTER SET utf8 NOT NULL DEFAULT '0',
			// `Extra2` varchar(150) CHARACTER SET utf8 NOT NULL DEFAULT '0',
			// `Extra3` varchar(150) CHARACTER SET utf8 NOT NULL DEFAULT '0'
			// ) ENGINE=InnoDB DEFAULT CHARSET=utf8";
		// mysqli_query($msqlcon,$query2);
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
		mysqli_query($msqlcon,$query_create);

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