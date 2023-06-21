<?php 
require_once('../../core/ctc_init.php'); // add by CTC

function checkPhaseOut($partno){
require('../db/conn.inc');
$sub='';
$jwb='';
$comp = ctc_get_session_comp(); // add by CTC

$query="select *  from phaseout where ITNBR='".$partno . "' and Owner_Comp='$comp' ";
$sql=mysqli_query($msqlcon,$query);	
if($hasil = mysqli_fetch_array ($sql)){
	if(trim($hasil['SUBITNBR'])!=''){
		$sub=$hasil['SUBITNBR'];
		$jwb='S';
	    for($i=1;$i<20;$i++){
			$query1="select *  from phaseout where ITNBR='".$sub."' and Owner_Comp='$comp' ";;
			$sql1=mysqli_query($msqlcon,$query1);	
			if($hasil1 = mysqli_fetch_array ($sql1)){
				$sub=$hasil1['SUBITNBR'];
			}else{
				break;
			}
		
		}
	}else{
			$sub=$hasil['ITDSC'];
			$jwb='E';
	 	
	}
}	
 return array($jwb, $sub);		
}
?>