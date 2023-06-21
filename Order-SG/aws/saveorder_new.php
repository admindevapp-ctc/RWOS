<?php 
session_start();

require_once('./../../core/ctc_init.php'); // add by CTC

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
		$_SESSION['awstable'];
		$awstable=$_SESSION['awstable'];
		$cusno=	$_SESSION['cusno'];
		$cusnm=	$_SESSION['cusnm'];
		$password=$_SESSION['password'];
		$alias=$_SESSION['alias'];
		$table=$_SESSION['tablename'];
		$type=$_SESSION['type'];
		$custype=$_SESSION['custype'];
		$user=$_SESSION['user'];
		$erp = $_SESSION['erp'];
		//$dealer=$_SESSION['dealer'];
		$group=$_SESSION['group'];
		$comp = ctc_get_session_comp(); // add by CTC
		
	 }else{
		echo "<script> document.location.href='../../".redir."'; </script>";
	 }
}else{
	header("Location:../../login.php");
}

include('chklogin.php');
if (trim($_GET['orderno']) == '') {
	$error = 'Error : Order No  should be filled';
}
if ($error) {
	echo $error;
} else {
	$vcusno=trim($_GET['vcusno']);
	$shpno=trim($_GET['shpno']);
	$shpCd=trim($_GET['shpCd']);
	$periode=trim($_GET['periode']);
	$orderno=trim($_GET['orderno']);
	$corno=trim($_GET['corno']);
	$action=trim($_GET['action']);
	$reason=trim($_GET['reason']);
	$mpartno=trim($_GET['mpartno']);
	$ordertype=trim($_GET['ordertype']);
	$txtnote=trim($_GET['txtnote']);
	require('../db/conn.inc');
	require('sendemail.php');
	require('getDueDate.php');
	require('getUrgentDueDate.php');

	// get dealer
	$query="select * from cusmas  where trim(cusno)='".$shpno."' and Owner_Comp='$comp'";
	$sql=mysqli_query($msqlcon,$query);
	if($tmphsl=mysqli_fetch_array($sql)){
		$dealer=$tmphsl['xDealer'];
		$route=$tmphsl['route'];
	}
	
	$query_dnm="SELECT * FROM `cusmas` WHERE `Owner_Comp` LIKE '$comp' AND `Cusno` LIKE '$dealer'";
	$sql_dnm=mysqli_query($msqlcon,$query_dnm);
	if($tmphsl=mysqli_fetch_array($sql_dnm)){
		$dealer_nm=$tmphsl['Cusnm'];
	}
	if($action=='edit'){
		// select * from order detail inner join qty
		$query8="select awsorderdtl.*, availablestock.prtno  from awsorderdtl inner join availablestock on awsorderdtl.partno=availablestock.prtno and awsorderdtl.Owner_Comp=availablestock.Owner_Comp where trim(orderno)='".$orderno."' and trim(cusno)='".$shpno."' and corno='".$corno."' and awsorderdtl.Owner_Comp='".$comp."' ";
		$sql8=mysqli_query($msqlcon,$query8);
		while($tmphsl8=mysqli_fetch_array($sql8)){
			$subqty=$tmphsl8['qty'];
			$part=$tmphsl8['partno'];
			$query9="update availablestock set qty=qty +$subqty where prtno='$part' and Owner_Comp='".$comp."' ";
			mysqli_query($msqlcon,$query9);
			//echo $query9;
		}

		// delete header and detail
		$query5="delete from awsorderhdr where trim(orderno)='".$orderno."' and trim(cusno)='".$shpno."' and corno='".$corno."' and Owner_Comp='".$comp."' ";
		mysqli_query($msqlcon,$query5);
		$query5="delete from awsorderdtl where trim(orderno)='".$orderno."' and trim(cusno)='".$shpno."' and corno='".$corno."' and Owner_Comp='".$comp."' ";
		mysqli_query($msqlcon,$query5);
		$query6="delete from awsordernts where trim(orderno)='".$orderno."' and trim(cusno)='".$shpno."' and corno='".$corno."' and Owner_Comp='".$comp."' ";
		mysqli_query($msqlcon,$query6);

	}else{
		//update Order Number if not close
		if($action!='close'){

			$type=substr($orderno,-1);
			$newordno=substr(substr($orderno,-8),0,7);

			$querycheck = "select * from tc000pr 
			where trim(cusno)='".$shpno."' and trim(Owner_Comp)='".$comp."' ";
			$sql=mysqli_query($msqlcon,$querycheck);	
			$count = mysqli_num_rows($sql);
			if($count > 0){
				$query10="update tc000pr set rordno='".$newordno. "' where trim(cusno)='".$shpno."' and Owner_Comp='".$comp."' ";
				
			}else{
				$query10="INSERT into tc000pr (Owner_Comp, cusno,Ordno, ROrdno)
				values('".$comp."' ,'".$shpno."','', '".$newordno. "')";
			}

			mysqli_query($msqlcon,$query10);
		}

	}
	
	
	
	if($action!='close'){
		$x="1";
		$count=0;
		$shortStockLit;
		$count2=0;
		$count3=0;
		$mtoList;
		$setdayQry="select * from duedate where ordtype='U' and Owner_Comp='".$comp."' ";
		$setdaysql=mysqli_query($msqlcon,$setdayQry);
		$time="";
		if($result = mysqli_fetch_array ($setdaysql)){
			$time=$result['setday'];
		}
		
		$val = mysqli_query($msqlcon,"select 1 from tf_snd_web_item_ma_$comp LIMIT 1");
		
			$query="select * from ".$awstable. 
			" where trim(orderno)='".$orderno."' and trim(cusno)='".$shpno."' and Owner_Comp='".$comp."' ";
		
       
		$sql=mysqli_query($msqlcon,$query);
		while($tmphsl=mysqli_fetch_array($sql)){
			$partno=$tmphsl['partno'];
			$partdes=addslashes($tmphsl['partdes']);
			$qty=$tmphsl['qty'];
			$orderdate=$tmphsl['orderdate'];
			$duedate=$tmphsl['DueDate'];
			$orderstatus='R';
			$bprice=$tmphsl['bprice'];
			$SGPrice=$tmphsl['SGPrice'];
			$curcd=$tmphsl['CurCD'];
			$disc= isset($tmphsl['DSCNT_RATIO']) ? $tmphsl['DSCNT_RATIO'] : $tmphsl['disc'];
			$dlrdisc=$tmphsl['dlrdisc'];
			$slsprice= (isset($tmphsl['SLS_AMNT']) && $tmphsl['SLS_AMNT'] >= 0 )? $tmphsl['SLS_AMNT'] : $tmphsl['slsprice'];
			$dlrcurcd=$tmphsl['DlrCurCD'];
			$dlrprice= (isset($tmphsl['SLS_AMNT']) && $tmphsl['SLS_AMNT'] >= 0 )? $tmphsl['SLS_AMNT'] : $tmphsl['DlrPrice'];
			$oecus=$tmphsl['OECus'];
			$shipment=$tmphsl['Shipment'];

			/////////////////////Stock ////////////////////////////////


			$stockqty1=0;
			$stockqty2=0;
			$stockqty=0;

			$qry1="select * from availablestock where prtno='".$partno."' and Owner_Comp='".$comp."' ";
			$qry1Result=mysqli_query($msqlcon,$qry1);
			if($stockArray = mysqli_fetch_array ($qry1Result)){
				$stockqty=$stockArray['qty'];
				if($stockqty < $qty){
					$shortStockLit[$count]=$partno;
					$count++;
				}
			}
			else{
				$qry2="select * from hd100pr where Owner_Comp='".$comp."' and prtno='".$partno."' and (l1awqy+l2awqy)>=".$qty;
				$qry2Result = mysqli_query($msqlcon,$qry2);
				$count2 = mysqli_num_rows($qry2Result);
				//echo $qry2;
				//echo $count2;
				if($count2<=0){
					$shortStockLit[$count]=$partno;
					$count++;
				}

			}
			/////////////////////////////MTO///////////////////////////////
			// check erp by CTC
			if(ctc_get_session_erp() == '0'){
				// old source code from denso check mto from mto table , we think Should check from bm008pr.MT0=1
				$mtoQry="select * from mto where prtno='".$partno."' and Owner_Comp='".$comp."' ";
			}else{
				$mtoQry="select * from bm008pr where MTO='1' and ITNBR='".$partno."' and Owner_Comp='$comp'";
			}

			$mtoResult=mysqli_query($msqlcon,$mtoQry);
			While($mtoArray = mysqli_fetch_array ($mtoResult)){
				$mtoList[$count3]=$partno;
				$count3++;

			}
			////////////////////////////////////////////////////////////
			if($x=='1'){

				// add order header
				// if($dealer==$shpno){
				$ordflg="";

				$ordflagdate=date('Ymd');
				$ordflagtime=date('H:i:s');
				// $query2="insert into awsorderhdr (CUST3, orderno, cusno, ordtype,orderdate,orderprd, corno, ordflg, Dealer, ordflagdate, ordflagtime, OECus, Shipment) values('$cusno', '$orderno','$shpno', '$orderstatus','$orderdate','$periode','$corno','$ordflg','$dealer', '$ordflagdate', '$ordflagtime','$oecus', '$shipment')";
				$query2="insert into awsorderhdr (CUST3, orderno, cusno, ordtype,orderdate,orderprd, corno
                    , ordflg, Dealer, ordflagdate, ordflagtime, OECus, Shipment, shipto,Owner_Comp) 
                    values('$cusno', '$orderno','$cusno', '$orderstatus','$orderdate','$periode','$corno'
                    ,'$ordflg','$dealer', '$ordflagdate', '$ordflagtime','$oecus', '$shipment', '$shpCd','$comp')";
				mysqli_query($msqlcon,$query2);
               // echo $query2;
				mysqli_query($msqlcon,"SET NAMES UTF8");

				$query7="insert into awsordernts (CUST3, orderno, cusno, ordtype,orderdate,orderprd, corno
                    , ordflg, Dealer, ordflagdate, ordflagtime, OECus, Shipment, shipto, notes,Owner_Comp,DlvBy,Trflg
                    ,tranflagdate,tranflagtime) 
                    values('$cusno', '$orderno','$cusno', '$orderstatus','$orderdate','$periode','$corno'
                    ,'$ordflg','$dealer', '$ordflagdate', '$ordflagtime','$oecus', '$shipment', '$shpCd', '$txtnote','$comp','',''
                    ,'$ordflagdate', '". date('Hi') ."')";
				mysqli_query($msqlcon,$query7);

				//echo $query7."<br>";
				$x='2';
			}
			if ($ordertype=='Urgent'){
				$urgentDueArray=getUrgentDueDate($time);
				$duedate_format1=$urgentDueArray[0];
				$duedate_format2=$urgentDueArray[1];
				$duedate_new=$duedate_format1;

			}
			if($duedate_new!=''){
				$duedate=$duedate_new;
			}

            $SGPrice = !empty($SGPrice) ? $SGPrice : "NULL";
            $bprice = !empty($bprice) ? $bprice : "NULL";
            $slsprice = !empty($slsprice) ? $slsprice : "NULL";
            $dlrprice = !empty($dlrprice) ? $dlrprice : "NULL";
			$query3="insert into awsorderdtl (CUST3, orderno, cusno, partno,itdsc, orderdate,qty,CurCD, bprice
                , SGCurCD,SGPrice, disc, dlrdisc, slsprice,Corno, DueDate, ordflg, DlrCurCd, DlrPrice,Owner_Comp, tranflg
				,Dealer,AWSDueDate) 
            values('$cusno','$orderno','$shpno', '$partno','$partdes','$orderdate', $qty,'$curcd',  $bprice
                ,'SD', $SGPrice,  $disc, $dlrdisc, $slsprice, '$corno', '$duedate', '$ordflg','$dlrcurcd', $dlrprice,'$comp',''
				,'$dealer', '$duedate')";
			mysqli_query($msqlcon,$query3);
			// echo $query3;

			if ($ordertype=='Urgent'){
				$qry="select * from availablestock where prtno='$partno' and Owner_Comp='$comp' ";
				$exesql=mysqli_query($msqlcon,$qry);
				$newCount=mysqli_num_rows($exesql);
				if($newCount<=0){
					$query9="insert into availablestock(prtno,qty,Owner_Comp) 
                    values('".$partno."',((select (l1awqy+l2awqy) from hd100pr where prtno='".$partno."' 
                    and Owner_Comp='".$comp."')-$qty),'$comp');";

				}
				else {
					$query9="update availablestock set qty=qty-$qty where prtno='$partno' and Owner_Comp='$comp' ";
				}
				mysqli_query($msqlcon,$query9);
			//echo $query9;
			}

		}

		require_once('config/Web_Lib.php'); // add by CTC
		
		//check send stock email
		$to1 = get_config("to");
		$to = get_config("to1");
		$from="noreply@denso.co.th";
		if(count($shortStockLit) > 0 ){
			$subject="Warning ! Non-stock item ordered";
			$content="Dear Concern,<br>";
			$content.="Non-stock items have been ordered from web order system. <br>";
			$content.="Customer Number: ".$cusno ."<br>";
			$content.="Customer Name: ".$cusnm ."<br>";
			$content.="Order Type: ".$ordertype ."<br>";
			for ($a=0;$a<count($shortStockLit); $a++){
				$content.=$shortStockLit[$a]."<br>";
			}
			$content.="*** This is an automatically generated email, please do not reply ***";
			sendmail($to, $to1, $subject, $content, $from);
		}
		//check send mto email
		if(count($mtoList) > 0 ){
			$subject2="Warning ! MTO item ordered";
			$content2="Dear Concern,<br>";
			$content2.="MTO items have been ordered from web order system. <br>";
			$content.="Customer Number: ".$cusno ."<br>";
			$content.="Customer Name: ".$cusnm ."<br>";
			$content2.="Order Type: ".$ordertype."<br>";
			for ($j=0;$j<count($mtoList); $j++){
				$content2.=$mtoList[$j]."<br>";
			}
			$content2.="*** This is an automatically generated email, please do not reply ***";
			sendmail($to, $to1, $subject2, $content2, $from);
		}

		$emailTo=[];
		$tmpEmail=[];
		$query = "select *
        from awscusmas st
		INNER JOIN shiptoma ON shiptoma.Cusno  = st.cusno1 AND shiptoma.ship_to_cd = st.ship_to_cd1  and shiptoma.Owner_Comp = st.Owner_Comp
        where  trim(st.cusno2) ='" . $cusno . "' and st.ship_to_cd2 = '" . $shpCd . "' 
        and st.Owner_Comp='$comp'";
		$sql=mysqli_query($msqlcon,$query);
		while($axData=mysqli_fetch_array($sql)){
			$comp_mail_add = $axData['comp_mail_add'];
			$pers_mail_add1 = $axData['prsn_mail_add1'];
			$pers_mail_add2 = $axData['prsn_mail_add2'];
			$pers_mail_add3 = $axData['prsn_mail_add3'];
			$prsnMail1= $axData['mail_add1'];
			$prsnMail2= $axData['mail_add2'];
			$prsnMail3= $axData['mail_add3'];
		}
		array_push($tmpEmail,$comp_mail_add, $prsnMail1,$prsnMail2,$prsnMail3,$pers_mail_add1,$pers_mail_add2,$pers_mail_add3);
		for($index=0;$index<count($tmpEmail);$index++){
			if(
				 !in_array($tmpEmail[$index],$emailTo) && !empty($tmpEmail[$index]) ){
				array_push($emailTo,$tmpEmail[$index]);
			}
		}
		
		$bcc = get_config("bcc");
		$bcc = explode(";",$bcc);
		sendmailAttachFile($emailTo, $orderno, $corno , $cusno , $cusnm , $bcc , $dealer ,$dealer_nm);
		
		require('../db/conn.inc');

	}

	if($x==2 || $action=='close' || ($action=='edit' && $x!=2)){
		$query4="delete from ".$awstable. " where orderno='".$orderno."' and trim(cusno)='".$shpno."' and Owner_Comp='".$comp."' ";
		//echo $query;
		mysqli_query($msqlcon,$query4);
		$table1=str_replace("ordtmp",'regimp',$awstable);
		$query4="delete from ".$table1. " where Corno='".$corno."' and trim(cusno)='".$shpno."' and Owner_Comp='".$comp."' ";
		//echo $query;
		mysqli_query($msqlcon,$query4);

		include "crypt.php";
		if($ordertype=='Normal'){
			$url="main.php?".paramEncrypt("ordertype=Normal");
		}
		else if($ordertype=='Urgent'){
			$url="main.php?".paramEncrypt("ordertype=Urgent");
		}
		else if($ordertype=='Request'){
			$url="main.php?".paramEncrypt("ordertype=Request");
		}

		echo "<script> document.location.href='$url'; </script>";
	}

	if($action=="Reject"){


		$spartno = explode(',',$mpartno);
		for($i=0;$i<count($spartno);$i++){
			$vpartno=$spartno[$i];
			$query="update awsorderdtl set ordflg='$ordertype' where trim(orderno)='".$orderno."' and trim(partno) ='".$vpartno. "' and Owner_Comp='".$comp."' ";
			mysqli_query($msqlcon,$query);

			//========================================================================
			// check message
			//========================================================================
			$query1="select * from rejectorder where trim(orderno)='".$orderno."' and trim(partno) ='".$vpartno. "' and Owner_Comp='".$comp."' ";
			//echo $query1;
			$sql=mysqli_query($msqlcon,$query1);
				if($tmphsl=mysqli_fetch_array($sql)){
					$query2="update rejectorder set message='". $reason. "' where trim(orderno) ='".$orderno."' and trim(partno) ='".$vpartno. "' and Owner_Comp='".$comp."' ";
				}else{
					$query2="insert into rejectorder(orderno, partno, message,Owner_Comp) values( '$orderno', '$vpartno', '$reason','$comp')";
				}


		mysqli_query($msqlcon,$query2);
		//echo $query2;


		}


	}

	if($action=="Approve"){
		$spartno = explode(',',$mpartno);
		for($i=0;$i<count($spartno);$i++){
			$vpartno=$spartno[$i];
			$query="update awsorderdtl set ordflg='1' where trim(orderno)='$orderno' and trim(partno) ='$vpartno' and Owner_Comp='$comp' ";
			//echo $query;
			mysqli_query($msqlcon,$query) or die(mysqli_error()); ;
		}

	}

	if($action=="Reject" || $action=="Approve"){
		$query2="select * from awsorderdtl where trim(orderno)='".$orderno."' and Owner_Comp='".$comp."'" ;
		$result2 = mysqli_query($msqlcon,$query2);
		$count2 = mysqli_num_rows($result2);

		$query="select * from awsorderdtl where trim(ordflg)='' and trim(orderno)='".$orderno."' and Owner_Comp='".$comp."' " ;
		$result = mysqli_query($msqlcon,$query);
		$count = mysqli_num_rows($result);
		$YMD= date("Ymd");
		$Jam=date("Hi");
		if($count<=0){
				$query1="update awsorderhdr set ordflg='1', ordflagdate='$YMD', ordflagtime='$Jam', ordflaguser='$user' where trim(orderno)='".$orderno."' and Owner_Comp='".$comp."' " ;
			$result = mysqli_query($msqlcon,$query1);
		}else{
			if($count2>$count){
				$query1="update awsorderhdr set ordflg='U', ordflagdate='$YMD', ordflagtime='$Jam', ordflaguser='$user' where trim(orderno)='".$orderno."' and Owner_Comp='".$comp."' " ;
			$result = mysqli_query($msqlcon,$query1);

			}
		}
	}

}
?>