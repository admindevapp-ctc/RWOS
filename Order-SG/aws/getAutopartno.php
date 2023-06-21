<?php 

require_once('../../core/ctc_init.php'); // add by CTC


function getpartno($shpno,$ordertype,$shipto){
	require('../db/conn.inc');
	
	$comp = ctc_get_session_comp(); // add by CTC

   // select from awsexc left join awscusmas on awscusmas.cusgrp = awsexc.cusgrp 
   // where Owner_comp = '$comp' and awsexc.sell = '1' and awscusmas.cusno2 = '$shpno' 
	$qrycusmas="select * from cusmas where cusno='$shpno' 
	and Owner_Comp='$comp' ";
	//echo $qrycusmas;
	$sqlcusmas=mysqli_query($msqlcon,$qrycusmas);		
	if($hslcusmas = mysqli_fetch_array ($sqlcusmas)){
		$route=$hslcusmas['route'];
	}
	$query='';
	//echo $shpno;
	
		$query="select distinct a.itnbr as itnbr, bm008pr.itdsc  as ITDSC 
			from awsexc a  
				left join awscusmas on a.cusgrp  = awscusmas.cusgrp
					and a.Owner_comp = awscusmas.Owner_comp and a.cusno1  = awscusmas.cusno1
				left join bm008pr on trim(a.itnbr) = trim(bm008pr.ITNBR)
					and a.Owner_comp = bm008pr.Owner_comp 
			where awscusmas.cusno2='". $shpno."' and a.Owner_Comp='$comp' 
				and a.sell = '1' order by a.itnbr";
		
	
		$x=0;
		
		$sql=mysqli_query($msqlcon,$query);	
		$count = mysqli_num_rows($sql);
		$html="";
		while($array = mysqli_fetch_array ($sql)){
			if($x==0){ 
				 $html="<option value=''></option>";
			}
			$x++;
			$partno =$array['itnbr'];
			$itdsc =$array['ITDSC'];
			$html=$html."<option value='".$partno."'>".$partno.">>".$itdsc."</option>";
		}
		
	//return $query;
	return $html;
	}
	
?>