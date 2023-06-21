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
		//$table=$_SESSION['tablename'];
		$table = ctc_get_session_awstablenamesup();
		$type=$_SESSION['type'];
		$custype=$_SESSION['custype'];
		$user=$_SESSION['user'];
		//$dealer=$_SESSION['dealer'];
		$group=$_SESSION['group'];
		$comp = ctc_get_session_comp(); // add by CTC
	 }else{
		echo "<script> document.location.href='../".redir."'; </script>";
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
	$supno=trim($_GET['supno']);
	$txtnote=trim($_GET['txtnote']);
	require('../db/conn.inc');
	require('supsendemail.php');
	//require('supgetDueDate.php');

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
		$dealer_no=$tmphsl['Cusno'];
	}
	if($action=='edit'){
		/*
		// select * from order detail inner join qty
		$query8="select suporderdtl.*, supstock.partno as prtno   "
		." from suporderdtl  inner join supstock on suporderdtl.partno=supstock.partno and suporderdtl.Owner_Comp=supstock.Owner_Comp  "
		." where trim(orderno)='".$orderno."' and trim(cusno)='".$shpno."' and corno='".$corno."' and suporderdtl.Owner_Comp='".$comp."'  and suporderdtl.supno = '".$supno."'";
		$sql8=mysqli_query($msqlcon,$query8);
		while($tmphsl8=mysqli_fetch_array($sql8)){
			$subqty=$tmphsl8['qty'];
			$part=$tmphsl8['partno'];
			$query9="update supstock set qty=qty +$subqty where partno='$part' and Owner_Comp='".$comp."' and supno = '".$supno."'";
			mysqli_query($msqlcon,$query9);
			//echo $query9;
		}
		*/
		// delete header and detail
		$query5="delete from suporderhdr where trim(orderno)='".$orderno."' and trim(cusno)='".$shpno."' and corno='".$corno."' and Owner_Comp='".$comp."' ";
		mysqli_query($msqlcon,$query5);
		$query5="delete from suporderdtl where trim(orderno)='".$orderno."' and trim(cusno)='".$shpno."' and corno='".$corno."' and Owner_Comp='".$comp."' ";
		mysqli_query($msqlcon,$query5);
		$query6="delete from supordernts where trim(orderno)='".$orderno."' and trim(cusno)='".$shpno."' and corno='".$corno."' and Owner_Comp='".$comp."' ";
		mysqli_query($msqlcon,$query6);

	}else{
		//update Order Number if not close
		if($action!='close'){
			$query10 ="";
			//update last order number
			$type=substr($orderno,-1);
			if($type==substr($ordertype,0,1)){
				$newordno=substr(substr($orderno,-8),0,7);
				//$query10="update suptc000pr set ROrdno='".$newordno. "', Lastdate =  DATE_FORMAT(now(), '%Y%m%d') "
			//	."where trim(cusno)='".$cusno."' and Owner_Comp='".$comp."' ";
				$querycheck = "select * from suptc000pr where trim(Owner_Comp)='".$comp."' ";
				$sql=mysqli_query($msqlcon,$querycheck);	
				$count = mysqli_num_rows($sql);
				if($count > 0){
					$query10="UPDATE suptc000pr SET ROrdno = '".$newordno. "', Lastdate = DATE_FORMAT(now(), '%Y%m%d')" 
					."where Owner_Comp ='".$comp."'";
					
				} 
				else{
					$query10="INSERT into suptc000pr (Owner_Comp, cusno, Ordno, ROrdno, Lastdate)
					values('".$comp."' ,'".$cusno."', '', '".$newordno. "', DATE_FORMAT(now(), '%Y%m%d'))";
				}

			}
			//echo $query10;
			mysqli_query($msqlcon,$query10);
		}

	}
	if($action!='close'){
        require('../db/conn.inc');
		//$query="select * from ".$table. " where trim(orderno)='".$orderno."' and trim(cusno)='".$shpno."' and Owner_Comp='".$comp."' ";
		
		// $query="select a.*, b.Supcd as supno, (select supnm from supmas where supno =  b.Supcd and Owner_comp = b.Owner_Comp) as supnm  
        // from ".$table. " a join supcatalogue b on a.partno =  b.prtno and a.supno  = b.Supcd  and a.Owner_Comp = b.Owner_Comp "
			// ."where trim(a.orderno)='".$orderno."' and trim(a.cusno)='".$shpno."' and a.Owner_Comp='".$comp."'order by b.Supcd ";
		
		$query = "
			SELECT DISTINCT
				a.*,
				b.Supcd AS supno,
				(
				SELECT
					supnm
				FROM
					supmas
				WHERE
					supno = b.Supcd AND Owner_comp = b.Owner_Comp
			) AS supname
			FROM
				".$table." a
			JOIN supcatalogue b ON
				a.partno = b.ordprtno AND a.Owner_Comp = b.Owner_Comp AND a.supno = b.Supcd
			WHERE
				TRIM(a.cusno) = '".$shpno."' AND TRIM(a.orderno) = '".$orderno."' AND a.Owner_Comp = '".$comp."'
			ORDER BY
				a.partno
		";
		
		$x="1";
		//echo $query."<br/>";
		$sql=mysqli_query($msqlcon,$query);
		$count=0;
		$shortStockLit;
		$count2=0;
		$count3=0;
		$mtoList;
		$sendsupno = array();
		$setdayQry="select * from duedate where ordtype='U' and Owner_Comp='".$comp."' ";
		$setdaysql=mysqli_query($msqlcon,$setdayQry);
		$time="";
		if($result = mysqli_fetch_array ($setdaysql)){
			$time=$result['setday'];
		}
		$checksupno = "";
		while($tmphsl=mysqli_fetch_array($sql)){
			$partno=$tmphsl['partno'];
			$partdes=addslashes($tmphsl['partdes']);
			$qty=$tmphsl['qty'];
			$orderdate=$tmphsl['orderdate'];
			$duedate=$tmphsl['DueDate'];
			$orderstatus=$tmphsl['ordstatus'];
			$supno=$tmphsl['supno'];
			$supnm=$tmphsl['supnm'];
			$bprice=$tmphsl['bprice'];
			$SGPrice=$tmphsl['SGPrice'];
			$curcd=$tmphsl['CurCD'];
			$disc=$tmphsl['disc'];
			$dlrdisc=$tmphsl['dlrdisc'];
			$slsprice=$tmphsl['slsprice'];
			$dlrcurcd=$tmphsl['DlrCurCD'];
			$dlrprice=$tmphsl['DlrPrice'];
			$oecus=$tmphsl['OECus'];
			$shipment=$tmphsl['Shipment'];


			/////////////////////Stock ////////////////////////////////
			$stockqty1=0;
			$stockqty2=0;
			$stockqty=0;
			/*
			$qry1="select * from supstock where partno='".$partno."' and Owner_Comp='".$comp."'  supno = '".$supno."' ";
			$qry1Result=mysqli_query($msqlcon,$qry1);
			if($stockArray = mysqli_fetch_array ($qry1Result)){
				$stockqty=$stockArray['qty'];
				if($stockqty < $qty){
					$shortStockLit[$count]=$partno;
					$count++;
				}
			}
			*/
			$mtoQry="select * from supcatalogue where MTO='1' and ordprtno='".$partno."' and Owner_Comp='$comp' and Supcd = '".$supno."'";

			$mtoResult=mysqli_query($msqlcon,$mtoQry);
			While($mtoArray = mysqli_fetch_array ($mtoResult)){
				$mtoList[$count3]=$partno;
				$count3++;

			}

			$bprice = !empty($bprice) ? $bprice : "NULL";
			$slsprice = !empty($slsprice) ? $slsprice : "NULL";
			$dlrprice = !empty($dlrprice) ? $dlrprice : "NULL";
			$query3="insert into supawsorderdtl (CUST3, orderno, cusno, partno,itdsc, orderdate
			,qty,CurCD, bprice, SGCurCD,SGPrice, disc, dlrdisc, slsprice,Corno, DueDate, ordflg, DlrCurCD
			, DlrPrice,Owner_Comp, supno, AWSDueDate) 
			values('$cusno','$orderno','$shpno', '$partno','$partdes','$orderdate'
			, $qty,'$curcd',  $bprice,'SD', $SGPrice,  $disc, $dlrdisc, $slsprice, '$corno', '$duedate', '$ordflg','$dlrcurcd'
			, $dlrprice,'$comp','$supno', '$duedate')";
			mysqli_query($msqlcon,$query3);
			//echo $query3."<br>";

			if($checksupno != $supno){
                //echo $supno;
                // add order header
				if($dealer==$cusno){
                    $ordflg="1";
                }else{
                    $ordflg="";
                };
				array_push($sendsupno, $supno);

				$ordflagdate=date('Ymd');
                $ordflagtime=date('Hi');
               
				$query2="insert into supawsorderhdr (CUST3, orderno, cusno, ordtype,orderdate,orderprd, Corno, ordflg
                , Dealer, ordflagdate, ordflagtime, OECus, Shipment, shipto,Owner_Comp, supno,Dlvby,Trflg,ordflaguser,tranflagdate,tranflagtime,tranflaguser) 
                values('$cusno', '$orderno','$cusno', '$orderstatus','$orderdate','$periode'
                ,'$corno','','$dealer', '$ordflagdate', '$ordflagtime','$oecus'
                , '$shipment', '$shpCd','$comp','$supno','','','','','','')";
                mysqli_query($msqlcon,$query2);
               // echo $query2."<br>";
                //mysqli_query($msqlcon,"SET NAMES UTF8");


				$query7="insert into supawsordernts (CUST3, orderno, cusno, ordtype,orderdate,orderprd, corno, ordflg, Dealer, ordflagdate, ordflagtime, OECus, Shipment, shipto, notes,Owner_Comp, supno,DlvBy,Trflg,ordflaguser,tranflagdate,tranflagtime,tranflaguser) values('$cusno', '$orderno','$cusno', '$orderstatus','$orderdate','$periode','$corno','$ordflg','$dealer', '$ordflagdate', '$ordflagtime','$oecus', '$shipment', '$shpCd', '$txtnote','$comp','$supno','','','','','','')";//12/20/2018 P.Pawan
               // echo $query7."<br>";
                mysqli_query($msqlcon,$query7);
                $x='2';

				$checksupno = $supno;


			}
			/*
            // Update stock
            $query9="update supstock set qty=qty +$subqty where partno='$part' and Owner_Comp='".$comp."'  and supno = '".$supno."'";
            mysqli_query($msqlcon,$query9);
			*/

		}
		
		//sendmail
		require_once('sup_config/sup_Web_Lip.php');
		//check send stock email
		$to1 = get_sup_config("to");
		$to = get_sup_config("to1");
		$from="noreply@denso.co.th";
			
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
			supsendmail($to, $to1, $subject2, $content2, $from);
		}
				
		$emailTo=[];
		$tmpEmail=[];
					
		$query = "select *
		from awscusmas st
		INNER JOIN shiptoma ON shiptoma.Cusno  = st.cusno1 AND shiptoma.ship_to_cd = st.ship_to_cd1  and shiptoma.Owner_Comp = st.Owner_Comp
		where  trim(st.cusno2) ='" . $cusno . "' and st.ship_to_cd2 = '" . $shpCd . "' 
		and st.Owner_Comp='$comp'";

		$sql=mysqli_query($msqlcon,$query);
		
		//echo $query4 ;
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
		$bcc = get_sup_config("bcc");
		$bcc = explode(";",$bcc);
		//Loop send mail
		foreach ($sendsupno as $sup) {
			// get supname
			$query4="select supnm,email1,email2 from supmas where supno='".$sup."' and Owner_Comp='".$comp."'  ";
			$sql1 = mysqli_query($msqlcon,$query4);
			$suprun=mysqli_fetch_array($sql1);

			array_push($tmpEmailTo,$suprun[1],$suprun[2]);
			for($index=0;$index<count($tmpEmailTo);$index++){
				if(
					!in_array($tmpEmailTo[$index],$emailsupTo) && !empty($tmpEmailTo[$index]) ){
					array_push($emailsupTo,$tmpEmailTo[$index]);
				}
			}

			supsendmailAttachFile($emailTo, $orderno, $corno , $cusno , $cusnm , $bcc, $sup, $suprun[0], null,$dealer_nm,$dealer_no);
		}		
    }

    if($x==2 || $action=='close' || ($action=='edit' && $x!=2)){
        //echo $ordertype;
        $query4="delete from ".$table. " where orderno='".$orderno."' and trim(cusno)='".$shpno."' and Owner_Comp='".$comp."' ";
        //echo $query;
        mysqli_query($msqlcon,$query4);
        $query4="delete from ".$table. " where Corno='".$corno."' and trim(cusno)='".$shpno."' and Owner_Comp='".$comp."' ";
        //echo $query;
        mysqli_query($msqlcon,$query4);

       if($ordertype=='R'){
            $url="supcata_cataloguemain.php?".paramEncrypt("ordertype=Request");
        }

        //echo "<script> document.location.href='$url'; </script>";
       
    }

    if($action=="Reject"){


        $spartno = explode(',',$mpartno);
        for($i=0;$i<count($spartno);$i++){
            $vpartno=$spartno[$i];
            $query="update suporderdtl set ordflg='$ordertype' where trim(orderno)='".$orderno."' and trim(partno) ='".$vpartno. "' and Owner_Comp='".$comp."' ";
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
            $query="update suporderdtl set ordflg='1' where trim(orderno)='$orderno' and trim(partno) ='$vpartno' and Owner_Comp='$comp' ";
            //echo $query;
            mysqli_query($msqlcon,$query) or die(mysqli_error()); ;
        }

    }

    if($action=="Reject" || $action=="Approve"){
        $query2="select * from suporderdtl where trim(orderno)='".$orderno."' and Owner_Comp='".$comp."'" ;
        $result2 = mysqli_query($msqlcon,$query2);
        $count2 = mysqli_num_rows($result2);

        $query="select * from suporderdtl where trim(ordflg)='' and trim(orderno)='".$orderno."' and Owner_Comp='".$comp."' " ;
        $result = mysqli_query($msqlcon,$query);
        $count = mysqli_num_rows($result);
        $YMD= date("Ymd");
        $Jam=date("Hi");
        if($count<=0){
                $query1="update suporderhdr set ordflg='1', ordflagdate='$YMD', ordflagtime='$Jam', ordflaguser='$user' where trim(orderno)='".$orderno."' and Owner_Comp='".$comp."' " ;
            $result = mysqli_query($msqlcon,$query1);
        }else{
            if($count2>$count){
                $query1="update suporderhdr set ordflg='U', ordflagdate='$YMD', ordflagtime='$Jam', ordflaguser='$user' where trim(orderno)='".$orderno."' and Owner_Comp='".$comp."' " ;
            $result = mysqli_query($msqlcon,$query1);

            }
        }
    }

	
}
?>
