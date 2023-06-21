<?php 

require_once('../../core/ctc_init.php'); // add by CTC


function getpartno($shpno,$ordertype,$shipto){
	require('../db/conn.inc');
	
	$comp = ctc_get_session_comp(); // add by CTC

	$qrycusmas="select * from cusmas where cusno='$shpno' and Owner_Comp='$comp' ";
	//echo $qrycusmas;
	$sqlcusmas=mysqli_query($msqlcon,$qrycusmas);		
	if($hslcusmas = mysqli_fetch_array ($sqlcusmas)){
		$route=$hslcusmas['route'];
	}
	$query='';

	if($ordertype=='Request'){
        $query="select supcatalogue.*, supawsexc.*
        from (
            SELECT *
            FROM  awscusmas  
            WHERE cusno2 = '$shpno' and Owner_Comp = '$comp' and ship_to_cd2 = '$shipto'
        ) a
        join supref on a.Owner_Comp = supref.Owner_comp and a.cusno1 = supref.Cusno
        join supcatalogue on supref.supno = supcatalogue.Supcd and supref.Owner_comp = supcatalogue.Owner_Comp 
            and supref.supno = supcatalogue.Supcd and supcatalogue.lotsize > 0
        left join supawsexc on supawsexc.cusno1 = a.cusno1 and  supawsexc.Owner_comp  = a.Owner_Comp 
            and supawsexc.supcode = supcatalogue.Supcd and supawsexc.prtno = supcatalogue.Prtno
        ";
	}
		
	$x=0;
	$sql=mysqli_query($msqlcon,$query);	
	$count = mysqli_num_rows($sql);
	$html="";
	while($array = mysqli_fetch_array ($sql)){
		if($x==0){ 
			 $html="<option value=''></option>";
		}
		$x++;
		$partno =$array['Prtno'];
		$itdsc =$array['Prtnm'];
		$html=$html."<option value='".$partno."'>".$partno.">>".$itdsc."</option>";
	}

	return $html;
}

	
?>
