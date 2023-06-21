<?php
session_start();
require_once('../../core/ctc_init.php'); // add by CTC

if(isset($_SESSION['cusno']))
{       
	 if($_SESSION['redir']=='Order-SG'){
		$_SESSION['cusno'];
		$_SESSION['cusnm'];
		$_SESSION['redir'];
		$_SESSION['type'];
		$_SESSION['com'];
		$_SESSION['user'];
		$_SESSION['alias'];
		$_SESSION['tablename'];
    	$_SESSION['custype'];
		$_SESSION['dealer'];
		$_SESSION['group'];
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
		$comp = ctc_get_session_comp(); // add by CTC
	 }else{
		   echo "<script> document.location.href='../../".redir."'; </script>";
	 }
}else{	
header("Location:../../login.php");
}

include('chklogin.php');
require('../db/conn.inc');





//echo $action;
include "crypt.php";
$var = decode($_SERVER['REQUEST_URI']);
$vordno=trim($var['ordno']);
$vshpno=trim($var['shpno']);
$vshpCd=trim($var['shpCd']);
$vorddate=trim($var['orddate']);
$vcorno=trim($var['corno']);
$vaction=trim($var['action']);
$ordertype=trim($var['ordertype']);
$vdlvby=trim($var['dlvby']);
$vtxtnote=trim($var['txtnote']);
$table =trim($var['table']);


switch ($vaction)
{

case 'new':
	$vcusno=$cusno;
	$vcusnm=$cusnm;
	checknew($vcusno,$vcusnm,$vcorno,$vordno,$vorddate, $vshpno, $vaction);
	break;
case 'edit':
	
  //  echo $vcusno;
  $vcusno=$cusno;
	checkedit($vshpno, $vordno, $vcorno,$vaction, $table, $cusno, $ordertype, $vshpCd, $vtxtnote);  //zia added txt note 
	break;
case 'delete':
	//delete header 
	$vcusno=$xcusno;
	checkdelete($vshpno, $vordno, $vcorno,$ordertype);
	break;
case 'deleteAddition':
	//delete header 
	$vcusno=$xcusno;
	checkdeleteAdd($vshpno, $vordno, $vcorno);
	break;
    
case 'editAWS':
  $vcusno=$xcusno;
  checkeditAWS($vshpno, $vordno,  $vcorno, $vorddate, $table);
  break;
  
case 'editAWSadd':
  $vcusno=$xcusno;
  checkeditAWSadd($vshpno, $vordno, $vcorno, $vorddate, $vtype);
  break;  

case 'Approve':
	
  $vcusno;
  $vcusno=$xcusno;
  ApproveAWS($vshpno, $vordno, $vcorno, $vaction,$cusno);
  break;

case 'Reject':
	
  $vcusno;
  $vcusno=$xcusno;
  ApproveAWS($vshpno, $vordno, $vcorno,$vaction,$cusno);
  break;

case 'View':
  $vcusno=$xcusno;
  checkViewAWS($vshpno, $vordno,  $vcorno, $vorddate);
  break;

case 'ViewDlr':
  $vcusno=$xcusno;
  checkViewDLr($vshpno, $vordno, $vcorno, $vorddate);
  break;

case "editAddition":
	$vcusno=$cusno;
	checkeditAdd($vshpno, $vordno, $table, $vcorno, $vcusno);

case 'ViewAdd':
  $vcusno=$xcusno;
  checkViewAddAWS($vshpno, $vordno, $vcorno, $vorddate,  $vdlvby,$vtype);
  break;


}


		
function checknew($vcusno,$vcusnm,$vcorno,$vordno,$vorddate,$vshpno,$action){
	require('../db/conn.inc');
	$comp = ctc_get_session_comp(); // add by CTC

	//	include "crypt.php";
        $query="select * from awsorderhdr where Corno='".$vcorno."' and cusno='". $vshpno ."' and Owner_Comp='$comp' ";
        $sql=mysqli_query($msqlcon,$query);
   		$hasil = mysqli_fetch_array ($sql);
        
		if(!$hasil){
			$oecus=strtoupper($hasil['OECus']);
			$shipment=strtoupper($hasil['Shipment']);
        	echo "order2.php?".paramEncrypt("action=$action&ordno=$vordno&cusno=$vcusno&prdyear=$vprdyear&orddate=$vorddate&corno=$vcorno&prdmonth=$vprdmonth&oecus=$oecus&shipment=$vshipment");
  	
            
            }else{
              echo "Error - PO has already found, Use edit or new PO!";
            }
           
}

function checkedit($vshpno, $vordno, $vcorno,$action, $table, $cusno, $ordertype, $vshpCd, $vtxtnote){
	require('../db/conn.inc');
	$comp = ctc_get_session_comp(); // add by CTC
	
	$query="select * from awsorderhdr inner join awsorderdtl on awsorderhdr.orderno=awsorderdtl.orderno and awsorderhdr.cusno=awsorderdtl.cusno and awsorderhdr.Owner_Comp=awsorderdtl.Owner_Comp inner join bm008pr on awsorderdtl.partno=bm008pr.ITNBR and awsorderdtl.Owner_Comp=bm008pr.Owner_Comp where trim(awsorderhdr.orderno) = '$vordno' and trim(awsorderhdr.cusno)='".$vshpno."' and Owner_Comp='$comp' ";
    //echo "<br>". $query;
	$sql=mysqli_query($msqlcon,$query);		
	$x=0;
	while($hasil = mysqli_fetch_array ($sql)){
			$cdate=$hasil['orderdate'];
			$partno=$hasil['partno'];
			$partdesc=$hasil['ITDSC'];
			$odrstst=$hasil['ordtype'];
			$bprice=$hasil['bprice'];
			$SGPrice=$hasil['SGPrice'];
			$disc=$hasil['disc'];
			$dlrdisc=$hasil['dlrdisc'];
			$slsprice=$hasil['slsprice'];
			$qty=$hasil['qty'];
			$corno=$hasil['Corno'];
			$curcd=$hasil['CurCD'];
			$duedt=$hasil['DueDate'];
			$dlrcurcd=$hasil['DlrCurCD'];
			$dlrprice=$hasil['DlrPrice'];
			$oecus=strtoupper($hasil['OECus']);
			$shipment=strtoupper($hasil['Shipment']);
			
			$query2="insert into ".$table. " (CUST3, orderno, orderdate, cusno, partno, partdes, ordstatus,qty, CurCD,  bprice,SGPrice, disc, dlrdisc,  slsprice, Corno, DueDate, DlrCurCD, DlrPrice, OECus, Shipment,Owner_Comp) values('$cusno', '$vordno','$cdate','$vshpno','$partno','$partdesc','$odrstst',$qty,'$curcd', $bprice,$SGPrice, $disc, $dlrdisc, $slsprice, '$corno', '$duedt', '$dlrcurcd', $dlrprice, '$oecus', '$shipment','$comp')";
			mysqli_query($msqlcon,$query2);
			//echo $query2."<br>";
			$x=$x+1;
		}
	
	
	if($x==0){	
		echo "<script> document.location.href='main.php'; </script>";	
        //echo $query;
	}else{
		if($odrstst=='N' || $odrstst=='U' ){
			$url="order2_new.php?".paramEncrypt("action=$action&ordno=$vordno&cusno=$vcusno&prdyear=$vprdyear&orddate=$cdate&corno=$corno&shpno=$vshpno&oecus=$oecus&shipment=$vshipment&ordertype=$ordertype&shpCd=$vshpCd&txtnote=$vtxtnote");  // zia added txt note 
		}
		else if ($odrstst=='R'){
			$duedt=date('d-m-Y', strtotime(".$duedt."));
			$url="order2_new.php?".paramEncrypt("action=$action&ordno=$vordno&cusno=$vcusno&prdyear=$vprdyear&orddate=$cdate&corno=$corno&shpno=$vshpno&oecus=$oecus&shipment=$vshipment&ordertype=$ordertype&requestDate=$duedt&shpCd=$vshpCd&txtnote=$vtxtnote");  // zia added txtnote
		}
		else {
			$url="order2.php?".paramEncrypt("action=$action&ordno=$vordno&cusno=$vcusno&prdyear=$vprdyear&orddate=$cdate&corno=$corno&shpno=$vshpno&oecus=$oecus&shipment=$vshipment");
		} 
        echo "<script>document.location.href='".$url. "';</script>";
	//echo $query2;
	}
}



function checkdelete($vshpno, $vordno, $vcorno,$ordertype){
	//echo $action;
	require('../db/conn.inc');
	$comp = ctc_get_session_comp(); // add by CTC
	
	$query="delete  from awsorderhdr where trim(orderno) = '$vordno' and trim(cusno)='$vshpno' and trim(Corno)='$vcorno' and Owner_Comp='$comp' ";
	mysqli_query($msqlcon,$query);		
	$query1="delete  from awsorderdtl where trim(orderno) = '$vordno' and trim(cusno)='$vshpno' and trim(Corno)='$vcorno' and Owner_Comp='$comp' ";
	mysqli_query($msqlcon,$query1);
	/*Zia added Note start*/
	$query3="delete  from awsordernts where trim(orderno) = '$vordno' and trim(cusno)='$vshpno' and trim(Corno)='$vcorno' and Owner_Comp='$comp' ";   
	mysqli_query($msqlcon,$query3);
	/*Zia added Note end*/	
	$url="main.php?".paramEncrypt("ordertype=$ordertype");
	echo "<script> document.location.href='$url'; </script>";
	
}


function checkdeleteAdd($vshpno, $vordno,$vcorno){
	require('../db/conn.inc');
	$comp = ctc_get_session_comp(); // add by CTC

	//echo $action;
	$query="delete  from awsorderhdr where trim(orderno) = '$vordno' and trim(cusno)='$vshpno' and trim(corno)='$vcorno' and Owner_Comp='$comp' ";
	mysqli_query($msqlcon,$query);		
	$query1="delete  from awsorderdtl where trim(orderno) = '$vordno' and trim(cusno)='$vshpno' and trim(corno)='$vcorno' and Owner_Comp='$comp' ";
	mysqli_query($msqlcon,$query1);
	/*Zia added Note start*/
	$query4="delete  from awsordernts where trim(orderno) = '$vordno' and trim(cusno)='$vshpno' and trim(Corno)='$vcorno' and Owner_Comp='$comp' ";
	mysqli_query($msqlcon,$query4);	
	/*Zia added Note end*/
	echo "<script> document.location.href='mainAdd.php'; </script>";
	
}



function checkeditAWS($vshpno, $vordno,  $corno, $cdate, $table){
	require('../db/conn.inc');
	$action="approval";
    $vprdyear=substr($prd, 0,4);
    $vprdmonth=substr($prd,-2);
    //include "crypt.php";
    $url="OrderAwsdtl.php?".paramEncrypt("action=$action&ordno=$vordno&shpno=$vshpno&orddate=$cdate&corno=$corno&table=$table");
           echo "<script>document.location.href='".$url. "';</script>";
		//echo "action=$action&ordno=$vordno&shpno=$vshpno&orddate=$cdate&corno=$corno";
           
          // echo "action=$action&ordno=$vordno&cusno=$vcusno&prdyear=$vprdyear&orddate=$cdate&corno=$corno&prdmonth=$vprdmonth";
	
}


function checkeditAWSadd($vshpno, $vordno, $corno, $cdate,  $type){
	require('../db/conn.inc');
	$action="approval";
    $prd=$vperiode;
    //include "crypt.php";
    $url="OrderAwsdtladd.php?".paramEncrypt("action=$action&ordno=$vordno&shpno=$vshpno&ordtype=$type&orddate=$cdate&corno=$corno");
     //echo "action=$action&ordno=$vordno&shpno=$vshpno&ordtype=$type&orddate=$cdate&corno=$corno";
	 echo "<script>document.location.href='".$url. "';</script>";
           
          // echo "action=$action&ordno=$vordno&cusno=$vcusno&prdyear=$vprdyear&orddate=$cdate&corno=$corno&prdmonth=$vprdmonth";
	
}






function ApproveAWS($vshpno, $vordno, $vcorno, $vaction,$cusno){
	require('../db/conn.inc');
	$YMD= date("Ymd");
	$Jam=date("Hi");
	$comp = ctc_get_session_comp(); // add by CTC
	
    if($vaction=="Approve"){
    	$flag="1";
      }else{
      	$flag="R";
       } 
        
	$query="update awsorderhdr set ordflg='". $flag."', ordflagdate='".$YMD."', ordflagtime='".$Jam."', ordflaguser='".$cusno."' where trim(orderno) = '$vordno' and trim(cusno)='".$vshpno."' and trim(Owner_Comp)='".$comp."' ";
    mysqli_query($msqlcon,$query);
	echo "<script>document.location.href='mainAws.php';</script>";

}


function checkViewAWS($vshpno, $vordno,  $corno, $cdate){
	require('../db/conn.inc');
	$action="approval";
    $prd=$vperiode;
    $vprdyear=substr($prd, 0,4);
    $vprdmonth=substr($prd,-2);
    //include "crypt.php";
    $url="VworderAws.php?".paramEncrypt("action=$action&ordno=$vordno&shpno=$vshpno&orddate=$cdate&corno=$corno");
	
	
         echo "<script>document.location.href='".$url. "';</script>";            
          // echo "action=$action&ordno=$vordno&cusno=$vcusno&prdyear=$vprdyear&orddate=$cdate&corno=$corno&prdmonth=$vprdmonth";
	
}

function checkViewDlr($vshpno, $vordno, $corno, $cdate){
	require('../db/conn.inc');
	$action="approval";
    $prd=$vperiode;
    $vprdyear=substr($prd, 0,4);
    $vprdmonth=substr($prd,-2);
    //include "crypt.php";
    $url="VworderDlr.php?".paramEncrypt("action=$action&ordno=$vordno&shpno=$vshpno&orddate=$cdate&corno=$corno");
           echo "<script>document.location.href='".$url. "';</script>";
           
          // echo "action=$action&ordno=$vordno&cusno=$vcusno&prdyear=$vprdyear&orddate=$cdate&corno=$corno&prdmonth=$vprdmonth";
	
}


function checkeditAdd($vshpno, $vordno, $table, $vcorno, $vcusno){
	require('../db/conn.inc');
	$action="edit";
	$query="select * from awsorderhdr inner join awsorderdtl on awsorderhdr.orderno=awsorderdtl.orderno and awsorderhdr.cusno=awsorderdtl.cusno  inner join bm008pr on awsorderdtl.partno=bm008pr.ITNBR where trim(awsorderhdr.orderno) = '$vordno' and trim(awsorderhdr.cusno)='$vshpno' and  trim(awsorderhdr.corno)='$vcorno' and trim(awsorderhdr.Owner_Comp)='$comp'";
  // echo $query;
$sql=mysqli_query($msqlcon,$query);		
	$x=0;
	while($hasil = mysqli_fetch_array ($sql)){
			$cdate=$hasil['orderdate'];
			$partno=$hasil['partno'];
			$partdesc=$hasil['ITDSC'];
			$odrstst=$hasil['ordtype'];
			$curcd=$hasil['CurCD'];
			$bprice=$hasil['bprice'];
			$SGPrice=$hasil['SGPrice'];
			$disc=$hasil['disc'];
			$dlrdisc=$hasil['dlrdisc'];
			$slsprice=$hasil['slsprice'];
			$qty=$hasil['qty'];
			$corno=$hasil['Corno'];
            $dlvby=$hasil['DlvBy'];
            $duedate=$hasil['DueDate'];
			$dlrcurcd=$hasil['DlrCurCD'];
			$dlrprice=$hasil['DlrPrice'];
            
			$query2="insert into ".$table. " (Cust3, orderno, orderdate, cusno, partno, partdes, ordstatus,qty, CurCD, bprice,SGPrice, disc, dlrdisc, slsprice, Corno, DueDate, DlvBy, DlrCurCD, DlrPrice,Owner_Comp) values('$vcusno', '$vordno','$cdate','$vshpno','$partno','$partdesc','$odrstst',$qty,'$curcd', $bprice,$SGPrice, $disc, $dlrdisc, $slsprice, '$corno','$duedate', '$dlvby', '$dlrcurcd', $dlrprice,$comp)";
			mysqli_query($msqlcon,$query2);
			$x=$x+1;
		}

	
	if($x==0){	
		echo "<script> document.location.href='mainAdd.php'; </script>";	
  	}else{
            $url="Addorder.php?".paramEncrypt("action=$action&ordno=$vordno&cusno=$vcusno&orddate=$cdate&corno=$corno&shpno=$vshpno&ordtype=$odrstst");
          echo "<script>document.location.href='".$url. "';</script>";
	   
    
  
	} 
}

function checkViewAddAWS($vshpno, $vordno, $corno, $cdate,  $vdlvby,$vtype){
	require('../db/conn.inc');
	$action="approval";
    //include "crypt.php";
    $url="VworderAwsAdd.php?".paramEncrypt("action=$action&ordno=$vordno&shpno=$vshpno&dlvby=$vdlvby&orddate=$cdate&corno=$corno&ordtype=$vtype");
           echo "<script>document.location.href='".$url. "';</script>";
           
          // echo "action=$action&ordno=$vordno&cusno=$vcusno&prdyear=$vprdyear&orddate=$cdate&corno=$corno&prdmonth=$vprdmonth";
	
}



?>
