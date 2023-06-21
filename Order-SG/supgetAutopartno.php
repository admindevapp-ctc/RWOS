<?php 

require_once('./../core/ctc_init.php'); // add by CTC


function getpartno($shpno,$ordertype,$shipto){
	require('db/conn.inc');
	
	$comp = ctc_get_session_comp(); // add by CTC

	$qrycusmas="select * from cusmas where cusno='$shpno' and Owner_Comp='$comp' ";
	//echo $qrycusmas;
	$sqlcusmas=mysqli_query($msqlcon,$qrycusmas);		
	if($hslcusmas = mysqli_fetch_array ($sqlcusmas)){
		$route=$hslcusmas['route'];
	}
	$query='';

	if($ordertype=='Request'){
		$query="SELECT ordprtno, prtnm 
            FROM supcatalogue 
                join supref on supcatalogue.Supcd = supref.supno and supcatalogue.Owner_Comp = supref.Owner_Comp 
				join supprice on supcatalogue.Supcd = supprice.supno
					and supcatalogue.ordprtno = supprice.partno
					and supprice.Cusno = supref.Cusno
					and supprice.shipto = supref.shipto and supcatalogue.Owner_Comp = supprice.Owner_Comp 
            WHERE supref.Cusno = '$shpno' and supcatalogue.Owner_Comp = '$comp'
            and supref.shipto = '$shipto' and supcatalogue.lotsize > 0
            order by ordprtno";
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
		$partno =$array['ordprtno'];
		$itdsc =$array['prtnm'];
		$html=$html."<option value='".$partno."'>".$partno.">>".$itdsc."</option>";
	}

	return $html;
}

	
?>
