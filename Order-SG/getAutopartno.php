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

	if(ctc_get_session_erp() =='0'){

		if(strtoupper($route)=='N'){
			$query="select distinct a.itnbr as itnbr from sellpriceaws a, qbm008pr b where a.itnbr=b.prtno and b.salavl='YES' and a.itnbr not in (select prtno from mto where Owner_Comp='$comp') and a.cusno=". $shpno." and Owner_Comp='$comp' order by a.itnbr";
		}else{
			if($ordertype=='Normal'){
				$query="select distinct a.itnbr as itnbr,b.ITDSC from sellprice a, qbm008pr b where a.itnbr=b.prtno and a.cusno=b.cusno and a.Owner_Comp=b.Owner_Comp and b.salavl='YES' ";
				$query.="and a.itnbr not in (select prtno from mto where Owner_Comp='$comp') and a.cusno=". $shpno." and a.Shipto='$shipto' order by a.itnbr";

			}else if($ordertype=='Urgent'){
				$query="select distinct a.itnbr as itnbr,b.ITDSC from sellprice a, qbm008pr b where a.itnbr=b.prtno and a.cusno=b.cusno and b.salavl='YES' and a.Owner_Comp='$comp' and a.Shipto='$shipto' ";
				$query.="and a.itnbr not in (select prtno from mto where Owner_Comp='$comp') and a.cusno=". $shpno." and ";
				$query.="(a.itnbr in (SELECT prtno from hd100pr where l1awqy+l2awqy>0  and prtno not in (select prtno from availablestock where qty>0 and Owner_Comp='$comp') and Owner_Comp='$comp') or a.itnbr in(select prtno from availablestock where qty>0 and Owner_Comp='$comp')) order by a.itnbr";
			}else if($ordertype=='Request'){
				$query="select distinct a.itnbr as itnbr,b.ITDSC from sellprice a, qbm008pr b where a.itnbr=b.prtno and a.cusno=b.cusno and b.salavl='YES' and a.cusno=". $shpno." and a.Owner_Comp='$comp' and a.Shipto='$shipto' order by a.itnbr";
			}
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
			$partno =$array['itnbr'];
			$itdsc =$array['ITDSC'];
			$html=$html."<option value='".$partno."'>".$partno.">>".$itdsc."</option>";
		}

	}else{

		if(strtoupper($route)=='N'){
		  $query="select distinct a.itnbr as itnbr from sellpriceaws a, qbm008pr b where a.itnbr=b.prtno and b.salavl='YES' and a.itnbr not in (select prtno from mto and Owner_Comp='$comp') and a.cusno=". $shpno." and a.Owner_Comp=b.Owner_Comp and a.Owner_Comp='$comp' order by a.itnbr";
				 
				 
		}else{
			if($ordertype=='Normal'){
				$query="select distinct a.itnbr as itnbr,b.ITDSC  from sellprice a, bm008pr b where a.itnbr=b.itnbr and a.itnbr not in (select ITNBR from bm008pr where MTO='1' and Owner_Comp='$comp') and a.cusno=". $shpno." and a.Owner_Comp=b.Owner_Comp and a.Owner_Comp='$comp' and a.Shipto='$shipto' order by a.itnbr";
			}
			else if($ordertype=='Urgent'){
	
				$query="select distinct a.itnbr as itnbr,b.ITDSC from sellprice a, bm008pr b where a.itnbr=b.itnbr ";
				$query.="and a.itnbr not in (select ITNBR from bm008pr where MTO='1' and Owner_Comp='$comp') and a.cusno=". $shpno." and a.Owner_Comp=b.Owner_Comp and a.Owner_Comp='$comp' and a.Shipto='$shipto' and ";
				$query.="(a.itnbr in (SELECT prtno from hd100pr where l1awqy+l2awqy>0 and prtno not in (select prtno from availablestock where qty>0 and Owner_Comp='$comp')) or a.itnbr in(select prtno from availablestock where qty>0 and Owner_Comp='$comp')) order by a.itnbr";
			}
			else if($ordertype=='Request'){
				$query="select distinct a.itnbr as itnbr,b.ITDSC from sellprice a, bm008pr b where a.itnbr=b.itnbr and a.cusno=". $shpno." and a.Owner_Comp=b.Owner_Comp and a.Owner_Comp='$comp' and a.Shipto='$shipto' order by a.itnbr";
			}
		}
	
		$sql=mysqli_query($msqlcon,$query);
		$html="<option value=''></option>";
		while($array = mysqli_fetch_array ($sql)){
			$html.="<option value='".$array['itnbr']."'>".$array['itnbr'].">>".$array['ITDSC']."</option>";
		}
		
	}

	return $html;
}

	
?>
