<? session_start() ?>
<?
//if (session_is_registered('cusno'))


if(isset($_SESSION['cusno']))
{       
	if($_SESSION['redir']=='Order-SG'){
		$cusno=	$_SESSION['cusno'];
		$cusnm=	$_SESSION['cusnm'];
		$password=$_SESSION['password'];
		$alias=$_SESSION['alias'];
		$table=$_SESSION['tablename'];
		$type=$_SESSION['type'];
		$custype=$_SESSION['custype'];
		$user=$_SESSION['user'];
		$dealer=$_SESSION['dealer'];
		$group=$_SESSION['group'];
		$imptable=$_SESSION['imptable'];
	 }else{
		   echo "<script> document.location.href='../".redir."'; </script>";
	 }
}else{	
header("Location:../login.php");
}
include('chklogin.php');

?>

<html>
	<head>
    <title>Denso Ordering System</title>
   	<link rel="stylesheet" type="text/css" href="css/dnia.css">
	</style><!--[if IE]>
<style type="text/css"> 
#twocolLeft{ padding-top: 0px; }
#twocolRight { zoom: 1; padding-top:10px; }
</style>	
<![endif]-->


</head>
<body >
   		<div id="header">
        <img src="images/denso.jpg" width="206" height="54" />
        </div>
		<div id="mainNav">
       
        <?
				$_GET['selection']="main";
				include("navhoriz.php");
			
			?>
	</div> 
    	<div id="isi">
        
        <div id="twocolLeft">
           		<?
			  	$_GET['current']="RFQ";
				include("navUser.php");
			  ?>
        </div>
        <div id="twocolRight">
        <table width="97%" border="0" cellspacing="0" cellpadding="0">
  <tr class="arial11blackbold">
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td></td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr class="arial11blackbold">
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td></td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr class="arial11blackbold">
    <td width="22%">Customer Number</td>
    <td width="2%">:</td>
    <td width="26%"><? echo $cusno ?></td>
    <td width="4%"></td>
    <td width="20%">Customer Name</td>
    <td width="2%">:</td>
    <td width="25%"><? echo $cusnm ?></td>
  </tr>
  <tr class="arial11blackbold">
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td></td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
        
   </table>     
        <table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr valign="middle" class="arial11">
    <th  scope="col">&nbsp;</th>
    <th width="90" scope="col">&nbsp;</th>
    <th width="90" scope="col" align="right">&nbsp;</th>
  </tr>
  <tr height="5"><td colspan="5"></td><tr>
</table>

       
     <?
		if(basename($_FILES['file']['name'])==""){
			echo "<script>document.location.href='imRFQ.php';</script>";
		}
			
		if ($_FILES["file"]["error"] > 0)  		{
  			echo "Error: " . $_FILES["file"]["error"] . "<br />";
  		}else  {		
  			$filename=basename($_FILES['file']['name']);
  			$ext = substr($filename, strrpos($filename, '.') + 1);
			if (($ext == "xls") && ($_FILES["file"]["size"] < 2000000)) {
				$cyear=date('Y');
				$cmonth=date('m');
				$cdate=date('d');
				$cymd=date('Ymd');
				$YYYYmm=date('Ym');
				$tglorder=date('Ymd');
				$tglupl=$cdate .'-'.$cmonth.'-'.$cyear;
				require('db/conn.inc');
				include "excel_reader2.php";
				include "crypt.php";
			
				$data = new Spreadsheet_Excel_Reader($_FILES['file']['tmp_name']);
				//membaca jumlah baris dari data excel
				$baris = $data->rowcount($sheet_index=0);
				$sukses = 0;
				$gagal = 0;
				$error=0;
				$flag="";
				$fld=array();
				$flderr=array();
				$result=array();
				$resulterr=array();
				for ($i=2; $i<=$baris; $i++){
					if(trim($data->val($i, 1))==""){
				   		break;
					}
					$vpartno = trim($data->val($i, 1));
					$vdesc= $data->val($i, 2);
					
					$chk=chkpartno($vpartno, $cusno);
					if($chk!=''){
							$ordsts='E';
							$error++;
							$flderr['prtno']=$vpartno;
							$flderr['action']='Add';
							$flderr['desc']=$vdesc;
							$flderr['error']=$chk;
							$resulterr[]=$flderr;
					}else{
					
					if(trim($vpartno)!="" && strlen($vpartno)<=15 ){     //PO tidak sama debgan kosong
						$vpartno = strtoupper($data->val($i, 1));
						$vdesc= $data->val($i, 2);
						$idxprt=getkey($result, $vpartno);
						if($idxprt==false){
							$fld['prtno']=$vpartno;
							$fld['action']='Add';
							$fld['desc']=$vdesc;
							$fld['sts']='P';
							$fld['rpldt']='';
							$fld['diasrmk']='';
							$fld['diasans']='';
							$sukses++;
							$result[]=$fld;
						}else{
							$partdes="duplicate part number";
							$ordsts='E';
							$error++;
							$flderr['prtno']=$vpartno;
							$flderr['action']='Add';
							$flderr['desc']=$vdesc;
							$flderr['error']=$partdes;
							$resulterr[]=$flderr;
						}
					}else{
						$partdes="part number is too long";
						$ordsts='E';
						$error++;
						$flderr['prtno']=$vpartno;
						$flderr['action']='Add';
						$flderr['desc']=$vdesc;
						$flderr['error']=$partdes;
						$resulterr[]=$flderr; 
					}
				
					}
					$amend='1';
						
						
				}  // for 
				//print_r($result);
				//print_r($resulterr);
				//echo 'jumlah error =' .	count($resulterr);
				if(count($resulterr)>=1){
						$amend='2';
						echo "<h3>Import finished. (Failure Item)</h3>";
						echo "<p>Number Item can be imported : ".$sukses."<br>";
						echo "Number of Problem Record : ".$error."</p>";
						echo "<table width=\"100%\" class=\"tbl1\" cellspacing=\"0\" cellpadding=\"0\">";
  						echo "<tr class=\"arial11grey\" bgcolor=\"#AD1D36\">";
    					echo "<th width=\"9%\" height=\"30\" >Create Date</th>";
    					echo "<th width=\"23%\" >Part Number</th>";
		    			echo "<th width=\"17%\" >Description</th>";
   						echo "<th width=\"28%\" class=\"lastth\">Error Description</th>";
    					echo "</tr>";
						$jml=count($resulterr);	
						for($i = 0; $i < $jml; $i++) {
							$errprtno=$resulterr[$i]['prtno'];
							$errdesc=$resulterr[$i]['desc'];
							$errmsg=$resulterr[$i]['error'];
							echo "<tr class=\"arial11black\" align=\"center\" height=\"25\">";
							echo "<td>".$tglupl."</td>";
							echo "<td>".$errprtno."</td>";
							echo "<td>".$errdesc."</td>";
							echo "<td>".$errmsg."</td>";
							echo "</tr>";
						
						}
							//$rpldt= $_SESSION['sip'][$i]['rpldt'];		
					
					}else{
						if(isset($_SESSION['sip'])){
								unset($_SESSION['sip']);	
						}
						$action='new';
						$_SESSION['sip']=$result;
						$link= "RFQHdr.php?".paramEncrypt("action=$action&rfqno=$xrfqno");
						echo "<script> document.location.href='". $link . "'; </script>";
					}
			
		}else{
		echo "<h3>You should select excel file and not more than 2 mb</h3>";
		
  	}
  }
function getkey($products, $needle)	{
	
	$jml=count($products);
 	$found='';
 	for($i = 0; $i < $jml; $i++) {
		if($products[$i]['prtno']==$needle){
			$found='1';
			return true;
			echo 'ketemu di='.$i."<br>";
			break;
	 	}
 	}
   if($found!='1'){
	      return false;
   	}
}	

function chkpartno($prtno, $cusno){
  
  $pjg=strlen($prtno);
  if(substr($prtno,-1)=='0'){
	$prtno=substr($prtno, 0, $pjg-1);
  }
	require('db/conn.inc');
		$param = str_replace('-','',$prtno);
	$qrycusmas="select cusno from cusmas where cust3= '$cusno' ";
	$sqlcusmas=mysqli_query($msqlcon,$qrycusmas);		
	$comp='(';
	$flag='';		
	while($hslcusmas = mysqli_fetch_array ($sqlcusmas)){
	  $cros=$hslcusmas['cusno'];
	  if($flag==''){
	  	$comp=$comp .$cros;
		$flag='1';
	  }else{
		  	$comp=$comp .','.$cros;
	  }
	}
	$comp=$comp .')';
	
	/* protect against sql injection */
	//mysql_real_escape_string($param, $server);
	/* query the database */
	//$param='JK';
	
		$query=$query . "select sellprice.Itnbr, ITDSC, Price, CurCD from sellprice inner join bm008pr on sellprice.itnbr=bm008pr.ITNBR ";
		$query=$query . "where replace(sellprice.Itnbr, '-','') like '%$param%' and  cusno in  $comp";
		$query=$query ." union ";
		$query=$query . "select sellprice.Itnbr, ITDSC, Price, CurCD from sellprice inner join bm008pr on sellprice.itnbr=bm008pr.ITNBR ";
		$query=$query . "where replace(sellprice.Itnbr, '-','') like '%$param%' and  cusno in  $comp";

	//echo $query;
	$x=0;
	$sql=mysqli_query($msqlcon,$query);	
	$count = mysqli_num_rows($sql);
	if($count!=1){
		if($count>1){
			return false;
		}else{
			$qry="select * from phaseout where replace(Itnbr, '-','') like '%$param%' ";		
			//echo $qry;
			$sqlx=mysqli_query($msqlcon,$qry);
			if($hsl = mysqli_fetch_array ($sqlx)){
				if(trim($hsl['SUBITNBR'] )==''){
					return "Error :". $hsl['ITDSC'];
				}else{
					$ganti=$hsl['SUBITNBR'];
					
					$qry1=$qry1 . "select sellprice.Itnbr, ITDSC, Price, CurCD from sellprice inner join bm008pr on sellprice.itnbr=bm008pr.ITNBR ";
					$qry1=$qry1 . "where sellprice.Itnbr = '%$ganti%' and  cusno in  $comp";
					$qry1=$qry1 ." union ";
					$qry1=$qry1 . "select sellprice.Itnbr, ITDSC, Price, CurCD from sellprice inner join bm008pr on sellprice.itnbr=bm008pr.ITNBR ";
					$qry1=$qry1 . "where sellprice.Itnbr= '%ganti%' and  cusno in  $comp";
					$sql1=mysqli_query($msqlcon,$qry1);	
					if($hsl1 = mysqli_fetch_array ($sql1)){
							$semua="Error :  Phase Out Subtitution Part No =". $ganti ; 
					}
					
					
					
					
					
					
					
				}
			} 
			
		}
	}else{
		if($hasil = mysqli_fetch_array ($sql)){
			$itnbr=$hasil['Itnbr'];
			$desc=$hasil['ITDSC'];
			//$harga=$hasil['Price'];
			//$curcd=$hasil['CurCD'];
			$semua="Error :  Part No :" . $itnbr. "(". $desc . ") can be found in DB!" ; 
		}
		return $semua;
   	
	}


}

?>
		
		
		
 
 <tr>
    <td colspan="6" class="lasttd" align="right"></td>
    </tr> 
</table>
<?php
if($amend=='2'){
	echo "<p>";
 echo '<table width="90%" border="0" align="center" bgcolor="#AD1D36">';
 echo '<tr  class="arial11whitebold">';
 echo '<td width="80px" rowspan="3"><img src="images/Exclam.png"></td>';
 echo '<td>PLEASE AMEND YOUR RFQ BY REMOVING ABOVE PART NUMBER FROM EXCEL FILE AND TRY TO UPLOAD AGAIN</td>';
 echo '</tr>';
echo '<tr  class="arial11whitebold">';
echo '<td>SYSTEM WILL ONLY PROCESS IF THERE ARE NO ERROR FOUND IN YOUR EXCEL FILE</td>';
//echo '</tr><tr class="arial11whitebold"><td>LEASE TAKE NOTE, ONLY 1 PO NUMBER ALLOWED FOR 1 UPLOAD FILE</td>
echo '</tr></table>';
}
?>
        </div>
              
<div id="footerMain1">
	<ul>
      
     
          
      </ul>

    <div id="footerDesc">

	<p>
	Copyright &copy; 2023 DENSO . All rights reserved  
	
  </div>
</div>

	</body>
</html>
