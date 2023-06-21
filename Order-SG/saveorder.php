<?php

session_start();
require_once('./../core/ctc_init.php'); // add by CTC

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
		//$dealer=$_SESSION['dealer'];
		$group=$_SESSION['group'];
		$comp = ctc_get_session_comp(); // add by CTC
		$erp = ctc_get_session_erp();
	 }else{
		echo "<script> document.location.href='../".redir."'; </script>";
	 }
}else{	
	header("Location:../login.php");
}

include('chkloginajax.php');
if (trim($_GET['orderno']) == '') {
	$error = 'Error : Order No  should be filled';
}


if ($error) {
	echo $error;
} else {
	$vcusno=trim($_GET['vcusno']);
	$shpno=trim($_GET['shpno']);
	$periode=trim($_GET['periode']);
	$orderno=trim($_GET['orderno']);
	$corno=trim($_GET['corno']);
	$action=trim($_GET['action']);
	$reason=trim($_GET['reason']);
	$mpartno=trim($_GET['mpartno']);
	$vordtype=trim($_GET['vordtype']);
	$table =trim($_GET['table']);
	$shipto1 = trim($_GET['shipto1']);
	//echo $table;

	require('db/conn.inc');

	// get dealer
	$query="select * from Cusmas  where trim(cusno)='".$shpno."' and Owner_Comp='$comp'"; 
	$sql=mysqli_query($msqlcon,$query);
	if($tmphsl=mysqli_fetch_array($sql)){
		$dealer=$tmphsl['xDealer'];
		$route=$tmphsl['route'];
	}
	
	
	if($action=='edit'){
		// select * from order detail inner join qty
		$query8="select orderdtl.*, freeinventory.prtno  
		from orderdtl inner join freeinventory on orderdtl.partno=freeinventory.prtno and orderdtl.Owner_Comp=freeinventory.Owner_Comp
		where trim(orderno)='".$orderno."' and trim(cusno)='".$shpno."' and corno='".$corno."' and orderdtl.Owner_Comp='$comp'"; 
		$sql8=mysqli_query($msqlcon,$query8);
		while($tmphsl8=mysqli_fetch_array($sql8)){
			$subqty=$tmphsl8['qty'];
			$part=$tmphsl8['partno'];
			$query9="update freeinventory set qty=qty +$subqty where prtno='$part' and Owner_Comp='$comp'"; 
			mysqli_query($msqlcon,$query9);
			//echo $query9;
		}
		
		// delete header and detail
		$query5="delete from orderhdr where trim(orderno)='".$orderno."' and trim(cusno)='".$shpno."' and corno='".$corno."' and Owner_Comp='$comp' "; 
		mysqli_query($msqlcon,$query5);
		$query5="delete from orderdtl where trim(orderno)='".$orderno."' and trim(cusno)='".$shpno."' and corno='".$corno."' and Owner_Comp='$comp' "; 
		mysqli_query($msqlcon,$query5);
	}else{
		//update Order Number if not close
		if($action!='close'){
			$type=substr($orderno,-1);
			if($type=='R'){
				$newordno=substr(substr($orderno,-8),0,7);
				$query10="update tc000pr set rordno='".$newordno. "' where trim(cusno)='".$cusno."' and Owner_Comp='$comp' "; 			
			}
				
	//echo $query10;
			mysqli_query($msqlcon,$query10);	
		}
		
	}
	if($action!='close'){
		$approvedate=date('Ymd');
		
		$query="select * from ".$table. " where trim(orderno)='".$orderno."' and trim(cusno)='".$shpno."' and Owner_Comp='$comp' "; 
		$x="1";
		$sql=mysqli_query($msqlcon,$query);
		while($tmphsl=mysqli_fetch_array($sql)){
			
			$partno=$tmphsl['partno'];	
			$partdes=addslashes($tmphsl['partdes']);	
			$qty=$tmphsl['qty'];
			$orderdate=$tmphsl['orderdate'];
			$duedate=$tmphsl['DueDate'];
			$orderstatus=$tmphsl['ordstatus'];
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
			if($x=='1'){
					
				// add order header 
				if($dealer==$shpno){
					$ordflg="1";
				}else{
					$ordflg="";
				};
				$query2="insert into orderhdr (CUST3, orderno, cusno, ordtype,orderdate,orderprd, corno, ordflg, Dealer, OECus, Shipment,Owner_Comp) 
				values('$cusno', '$orderno','$shpno', '$orderstatus','$approvedate','$periode','$corno','$ordflg','$dealer', '$oecus', '$shipment','$comp')";
				mysqli_query($msqlcon,$query2);
				//echo $query2."<br>";
				
				
				
				$x='2';
			}			
				$query3="insert into orderdtl (CUST3, orderno, cusno, partno,itdsc, orderdate,qty,CurCD, bprice, SGCurCD,SGPrice, disc
				, dlrdisc, slsprice,Corno, DueDate, ordflg, DlrCurCD, DlrPrice,Owner_Comp) 
				values('$cusno','$orderno','$shpno', '$partno','$partdes','$approvedate', $qty,'$curcd',  $bprice,'SD', $SGPrice
				,  $disc, $dlrdisc, $slsprice, '$corno', '$duedate', '$ordflg','$dlrcurcd', $dlrprice,'$comp')";
				mysqli_query($msqlcon,$query3);
				//echo $query3;
				
				$query9="update freeinventory set qty=qty-$qty where qty>=$qty and  prtno='$partno' and Owner_Comp='$comp'"; 
				mysqli_query($msqlcon,$query9);
				//echo $query9;
		
		}
		
	}
		if($x==2 || $action=='close' || ($action=='edit' && $x!=2)){	
				$query4="delete from ".$table. " where trim(orderno)='".$orderno."' and trim(cusno)='".$shpno."' and Owner_Comp='$comp' ";  
			//echo $query;
			mysqli_query($msqlcon,$query4);
		
		echo "<script> document.location.href='mainen_advance.php'; </script>"; 
		}
if($action=="Reject"){
	
        
	$spartno = explode(',',$mpartno);
	for($i=0;$i<count($spartno);$i++){
		$vpartno=$spartno[$i];
		$query="update orderdtl set ordflg='R' where trim(orderno)='".$orderno."' and trim(partno) ='".$vpartno. "' and Owner_Comp='$comp' ";
		mysqli_query($msqlcon,$query);
	
		//========================================================================
		// check message
		//========================================================================
		$query1="select * from rejectorder where trim(orderno)='".$orderno."' and trim(partno) ='".$vpartno. "'  and Owner_Comp='$comp' "; 
		//echo $query1;
		$sql=mysqli_query($msqlcon,$query1);
			if($tmphsl=mysqli_fetch_array($sql)){
				$query2="update rejectorder set message='". $reason. "' where trim(orderno) ='".$orderno."' and trim(partno) ='".$vpartno. "' and Owner_Comp ='".$comp. "' ";
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
		$query="update orderdtl set ordflg='1' where trim(orderno)='$orderno' and trim(partno) ='$vpartno' and Owner_Comp ='".$comp. "' ";
		//echo $query;
		mysqli_query($msqlcon,$query) or die(mysqli_error()); ;
	}
	
}

if($action=="Reject" || $action=="Approve"){
	$query2="select * from orderdtl where trim(orderno)='".$orderno."' and Owner_Comp ='".$comp. "' " ;
	$result2 = mysqli_query($msqlcon,$query2);
	$count2 = mysqli_num_rows($result2);
	
	$query="select * from orderdtl where trim(ordflg)='' and trim(orderno)='".$orderno."' and Owner_Comp ='".$comp. "' " ;
	$result = mysqli_query($msqlcon,$query);
	$count = mysqli_num_rows($result);
	$YMD= date("Ymd");
    $Jam=date("Hi");
	if($count<=0){
	   		$query1="update orderhdr set ordflg='1', ordflagdate='$YMD', ordflagtime='$Jam', ordflaguser='$user' where trim(orderno)='".$orderno."' and Owner_Comp ='".$comp. "' " ;
		$result = mysqli_query($msqlcon,$query1);
	}else{
		if($count2>$count){
			$query1="update orderhdr set ordflg='U', ordflagdate='$YMD', ordflagtime='$Jam', ordflaguser='$user' where trim(orderno)='".$orderno."' and Owner_Comp ='".$comp. "' " ;
		$result = mysqli_query($msqlcon,$query1);
			
		}
	}
}
/** COMMENT change for AWS project Jan 2023**/
if($action=="Approve1"){
	
	if($table == "awsorderhdr"){//Denso
		//update AWS
		//check exiting
		$query="SELECT 1 FROM orderdtl
			LEFT JOIN orderhdr ON orderhdr.Owner_Comp = orderdtl.Owner_Comp AND orderhdr.orderno = orderdtl.orderno 
				AND orderhdr.cusno = orderdtl.cusno AND orderhdr.Corno = orderdtl.Corno
			WHERE orderhdr.Owner_Comp = '$comp' AND orderhdr.orderno = '$orderno' AND orderhdr.cusno = '$vcusno' 
				AND orderhdr.Corno = '$corno'";
			
		$result = mysqli_query($msqlcon,$query);
		$count = mysqli_num_rows($result);

		if($count>=1){
			echo "Data dulicate, Please contact Denso PIC.";
		}else{
			//update Details
			$spartno = explode(',',$mpartno);
			$Trflg = "1";
			if($comp == "IN0"){
				$Trflg = "";
			}
			for($i=0;$i<count($spartno);$i++){
				$vpartno=$spartno[$i];
				$query1="update awsorderdtl set ordflg='1', tranflg='$Trflg'
				where partno = '$vpartno' and trim(orderno)='$orderno' and Owner_Comp ='$comp' 
					and Corno = '$corno' ";
				if($comp != "IN0"){
					$query1 .=	" and cusno = '$vcusno' " ;
				}
				$result = mysqli_query($msqlcon,$query1);

			//	echo $query1;
			}
		}
	}
	else{//Non Denso supawsorderhdr
		
		//update AWS
		//check exiting
		$query="
		SELECT 1 
		FROM suporderdtl
		JOIN suporderhdr ON suporderhdr.Owner_Comp = suporderdtl.Owner_Comp AND suporderhdr.orderno = suporderdtl.orderno 
			AND suporderhdr.cusno = suporderdtl.cusno AND suporderhdr.Corno = suporderdtl.Corno
		WHERE suporderhdr.Owner_Comp = '$comp' AND suporderhdr.orderno = '$orderno'
			AND suporderhdr.cusno =  '$vcusno' AND suporderhdr.Corno = '$corno' and suporderhdr.supno =  '$shpno'
		";
		//echo $query;
		$result = mysqli_query($msqlcon,$query);
		$count = mysqli_num_rows($result);

		if($count>=1){
			echo "Data dulicate, Please contact Denso PIC.";
		}else{
			//update Details
			$spartno = explode(',',$mpartno);
			$Trflg = "1";
			if($comp == "IN0"){
				$Trflg = "";
			}
			for($i=0;$i<count($spartno);$i++){
				$vpartno=$spartno[$i];
				$query1="update supawsorderdtl set ordflg='1', tranflg='$Trflg'
				where partno = '$vpartno' and trim(orderno)='$orderno' and Owner_Comp ='$comp' 
					and Corno = '$corno' and supno =  '$shpno' and cusno = '$vcusno' " ;
			
				$result = mysqli_query($msqlcon,$query1);

				//echo $query1;
			}
		}
	}
	
}
		

if($action=="Approve2"){
	
	$xduedate = '';
	if($table == "awsorderhdr"){//Denso

		//getduedate
		$query="SELECT distinct DueDate,orderdate
		FROM awsorderdtl 
		WHERE Owner_Comp = '$comp' AND orderno = '$orderno' 
			AND cusno = '$vcusno'  AND Corno = '$corno'";
			
		//echo $query;
		$sql=mysqli_query($msqlcon,$query);		
		while($hasil = mysqli_fetch_array ($sql)){
			$DueDate=$hasil['DueDate'];
			$OrderDate=$hasil['orderdate'];
		}
		//update Details
		$spartno = explode(',',$mpartno);
		for($i=0;$i<count($spartno);$i++){
			$vpartno=$spartno[$i];
			$query1="update awsorderdtl set ordflg='2', tranflg='2' 
			where partno = '$vpartno' and trim(orderno)='$orderno' and Owner_Comp ='$comp' 
				and Corno = '$corno' ";
			
			if($comp != "IN0"){
				$query1 .=	" and cusno = '$vcusno' " ;
			}
			$result = mysqli_query($msqlcon,$query1);

			//echo $query1."<br/>";
		}
	}
	else{//Non Denso supawsorderhdr

		 //getduedate
		 $DueDate = "";
		 $query="SELECT distinct DueDate,orderdate
		 FROM supawsorderdtl 
		 WHERE Owner_Comp = '$comp' AND orderno = '$orderno' 
			 AND cusno = '$vcusno'  AND Corno = '$corno'";
		 $sql=mysqli_query($msqlcon,$query);		
		 while($hasil = mysqli_fetch_array ($sql)){
			 $DueDate=$hasil['DueDate'];
			 $OrderDate=$hasil['orderdate'];
		}

		//update Details
		$spartno = explode(',',$mpartno);
		for($i=0;$i<count($spartno);$i++){
			$vpartno=$spartno[$i];
			$approvedate=date('Ymd');

			$query1="update supawsorderdtl set ordflg='2', tranflg='2' ,ApproveDate='$approvedate'
			where partno = '$vpartno' and trim(orderno)='$orderno' and Owner_Comp ='$comp' 
				and Corno = '$corno' ";
			
			if($comp != "IN0"){
				$query1 .=	" and cusno = '$vcusno' " ;
			}
			$result = mysqli_query($msqlcon,$query1);

			//echo $query1."<br/>";
		}
	}
}


if($action=="RejectAWS"){
	//update detaail
	$Trflg = "R";
	if($comp == "IN0"){
		$Trflg = "";
	}
	if($table == "awsorderhdr"){//Denso
		$spartno = explode(',',$mpartno);
		for($i=0;$i<count($spartno);$i++){
			$vpartno=$spartno[$i];
			$query1="update awsorderdtl set ordflg='R', tranflg='$Trflg'
			where partno = '$vpartno' and trim(orderno)='$orderno' and Owner_Comp ='$comp' 
				and Corno = '$corno'";
			
			if($comp != "IN0"){
				$query1 .=	" and cusno = '$vcusno' " ;
			}
			$result = mysqli_query($msqlcon,$query1);

			//echo $query1."<br/>";

			//	update remark
			$queryreject="select * from rejectorder where trim(orderno)='".$orderno."' and trim(partno) ='".$vpartno. "'  and Owner_Comp='$comp' "; 
			//echo $query1;
			$sqlreject=mysqli_query($msqlcon,$queryreject);
			if(!mysqli_num_rows($sqlreject)){
					$query2="
					INSERT INTO  rejectorder (Owner_Comp,orderno,partno,message)
					values('".$comp. "' , '".$orderno."','$vpartno' ,'$reason')" ;
			}else{
				$query2="update rejectorder set message='". $reason. 
				"' where trim(orderno) ='".$orderno."' and trim(partno) ='".$vpartno. 
				"' and Owner_Comp ='".$comp. "' ";
			}
			$result = mysqli_query($msqlcon,$query2);
			//echo $query."<br/>";
		}
	}
	
    else{//Non Denso supawsorderhdr
		$spartno = explode(',',$mpartno);
		for($i=0;$i<count($spartno);$i++){
			$vpartno=$spartno[$i];
			$query1="update supawsorderdtl set ordflg='R', tranflg='$Trflg'
			where partno = '$vpartno' and trim(orderno)='$orderno' and Owner_Comp ='$comp' 
				and Corno = '$corno'";
			
			if($comp != "IN0"){
				$query1 .=	" and cusno = '$vcusno' " ;
			}
			$result = mysqli_query($msqlcon,$query1);

			//echo $query1."<br/>";
		}

		//	update remark
		$queryreject="select * from rejectorder where trim(orderno)='".$orderno."' and trim(partno) ='".$vpartno. "'  and Owner_Comp='$comp' "; 
		//echo $query1;
		$sqlreject=mysqli_query($msqlcon,$queryreject);
		if(!mysqli_num_rows($sqlreject)){
				$query2="
				INSERT INTO  rejectorder (Owner_Comp,orderno,partno,message)
				values('".$comp. "' , '".$orderno."','$vpartno' ,'$reason')" ;
		}else{
			$query2="update rejectorder set message='". $reason. 
			"' where trim(orderno) ='".$orderno."' and trim(partno) ='".$vpartno. 
			"' and Owner_Comp ='".$comp. "' ";
		}
		$result = mysqli_query($msqlcon,$query2);
		//echo $query."<br/>";
	}
}


if($action=="Save"){
	$Trflg = "";
	$cust3=$cusno;
	if($comp == "IN0"){
		$Trflg = "";
		$cust3=$vcusno;
	}
	
	$xduedate = "";
	if($table == "awsorderhdr"){//Denso
		//getduedate
		$query="SELECT distinct DueDate,orderdate
		FROM awsorderdtl 
		WHERE Owner_Comp = '$comp' AND orderno = '$orderno' 
			AND cusno = '$vcusno'  AND Corno = '$corno'";
			
		//echo $query;
		$sql=mysqli_query($msqlcon,$query);		
		while($hasil = mysqli_fetch_array ($sql)){
			$DueDate=$hasil['DueDate'];
			$OrderDate=$hasil['orderdate'];
		}
		
		//get detail
		$query="SELECT *
		FROM awsorderdtl
		WHERE Owner_Comp = '$comp' AND orderno = '$orderno' 
			AND cusno = '$vcusno'  AND Corno = '$corno'";
		//echo $query;
		$sql=mysqli_query($msqlcon,$query);		
		$mpartno = array();
		$ordflg= array();
		while($hasil = mysqli_fetch_array ($sql)){
			$mpartno[] =$hasil['partno'];
			$ordflg[] =$hasil['ordflg'];
		}
		//print_r($mpartno);

		//header
		$query="
			INSERT INTO `orderhdr`
			SELECT awsorderhdr.Owner_Comp, '".$cust3."', awsorderhdr.orderno, '".$cust3."', awsorderhdr.ordtype
				, '$approvedate', awsorderhdr.orderprd, awsorderhdr.Corno, awsorderhdr.DlvBy,'1', '',  '".$cusno."', 
				awsorderhdr.ordflagdate, awsorderhdr.ordflagtime, awsorderhdr.ordflaguser, '', 
				ifnull(awsorderhdr.tranflagtime,0), awsorderhdr.tranflaguser, awsorderhdr.OECus, awsorderhdr.Shipment, awscusmas.ship_to_cd1
			FROM awsorderhdr join awsorderdtl ON awsorderhdr.Corno = awsorderdtl.Corno AND awsorderhdr.cusno = awsorderdtl.cusno 
			and awsorderhdr.orderno = awsorderdtl.orderno and awsorderhdr.Owner_Comp = awsorderdtl.Owner_Comp
			INNER JOIN awscusmas on awscusmas.ship_to_cd2 = awsorderhdr.shipto and awsorderhdr.cusno = awscusmas.cusno2 and awscusmas.Owner_Comp = awsorderhdr.Owner_Comp and awscusmas.cusno1 = awsorderhdr.Dealer
			WHERE trim(awsorderhdr.orderno)='$orderno' and awsorderhdr.Owner_Comp ='".$comp. "' 
				and awsorderhdr.Corno = '$corno' and awsorderhdr.cusno = '$vcusno'  and awsorderdtl.ordflg = '1' limit 1
		";
		//echo $query . "<br/>";
		$result = mysqli_query($msqlcon,$query);

		$sql_order_nts = "
			INSERT INTO `ordernts`
			SELECT
				awsordernts.`Owner_Comp`,
				'$cust3',
				`orderno`,
				'$cust3',
				`ordtype`,
				'$approvedate',
				`orderprd`,
				`Corno`,
				`DlvBy`,
				'1',
				'',
				'$cust3',
				`ordflagdate`,
				`ordflagtime`,
				`ordflaguser`,
				`tranflagdate`,
				`tranflagtime`,
				`tranflaguser`,
				`OECus`,
				`Shipment`,
				awscusmas.ship_to_cd1,
				`notes`
			FROM
				awsordernts
			INNER JOIN awscusmas ON awscusmas.ship_to_cd2 = awsordernts.shipto AND awsordernts.cusno = awscusmas.cusno2 AND awscusmas.Owner_Comp = awsordernts.Owner_Comp AND awscusmas.cusno1 = awsordernts.Dealer
			WHERE
				TRIM(awsordernts.orderno) = '$orderno' AND awsordernts.Owner_Comp = '$comp' AND awsordernts.Corno = '$corno' AND awsordernts.cusno = '$vcusno'
			LIMIT 1
		";
		$result_nts = mysqli_query($msqlcon,$sql_order_nts);

		//check duedate
		require('getRequestDueDate.php');
		$no_flag=0;
		//detail
		$spartno = explode(',',$mpartno);
		foreach ($mpartno as $value){
		
			if( $ordflg[$no_flag] == "1"){
				$requestDateArr = AWSgetRequestedDueDateApp1();
				// print_r($requestDateArr);
				if($requestDateArr[3] == '1'){ //error
					echo "failed";
					exit;
				}
				
				//[0]20230116>>[1]16/01/2023>>[2]16-01-2023
				//check approvedate and duedate
				if($DueDate >= $requestDateArr[0]){ //cusno2 >= cusno1
					$xduedate = $DueDate; //cusno2
				}
				else{
					$xduedate = $requestDateArr[0]; //cusno1
				}
				//echo "App 1" . $xduedate;
			}
			else{
				$requestDateArr = AWSgetRequestedDueDateApp2();
				// print_r($requestDateArr);
				if($requestDateArr[3] == '1'){ //error
					echo "failed";
					exit;
				}
				//[0]20230116>>[1]16/01/2023>>[2]16-01-2023
				//check approvedate and duedate
				if($DueDate >= $requestDateArr[0]){ //cusno2 >= cusno1
					$xduedate = $DueDate; //cusno2
				}
				else{
					$xduedate = $requestDateArr[0]; //cusno1
				}
				//print_r($requestDateArr);

			}
			// print_r($requestDateArr);
			 // echo ';Order saved';
			//check price
			if ($erp == '1' && $comp !='IN0') {
				$query="
				select a.* 
				from tf_snd_web_item_ma_".strtolower($comp)." a 
					join awsorderdtl b on a.OWNER_COMP = b.Owner_Comp
					and a.CST_ORDR_ITEM_NO_DSP = b.partno 
                    JOIN awsorderhdr c on c.Corno = b.Corno and c.orderno = b.orderno and c.Owner_Comp = b.Owner_Comp AND c.cusno = b.cusno
                    INNER JOIN awscusmas d on d.cusno2 = c.cusno and d.ship_to_cd2 = c.shipto AND d.Owner_Comp = c.Owner_Comp AND d.cusno1 = c.Dealer AND a.SHP_TO_CD = d.ship_to_cd1 AND a.CST_CD = d.cusno1
				where b.partno = '$value' and b.Owner_Comp = '$comp'
					and c.Dealer = '$cusno' and trim(b.orderno)='$orderno'
					and b.Corno = '$corno' 
					
					";
					
				$query="
					SELECT
						a.*,
						sellprice.CurCD,
						sellprice.Price
					FROM
						sellprice 

					left JOIN awsorderdtl b ON
						sellprice.Owner_Comp = b.Owner_Comp AND sellprice.Itnbr = b.partno
					LEFT JOIN tf_snd_web_item_ma_tk0 a ON a.OWNER_COMP = sellprice.Owner_Comp and a.CST_CD = sellprice.Cusno and sellprice.Itnbr = a.CST_ORDR_ITEM_NO_DSP AND sellprice.Shipto = a.SHP_TO_CD
					left JOIN awsorderhdr c ON
						c.Corno = b.Corno AND c.orderno = b.orderno AND c.Owner_Comp = b.Owner_Comp AND c.cusno = b.cusno
					INNER JOIN awscusmas d ON
						d.cusno2 = c.cusno AND d.ship_to_cd2 = c.shipto AND d.Owner_Comp = c.Owner_Comp AND d.cusno1 = c.Dealer AND sellprice.Shipto = d.ship_to_cd1 AND sellprice.Cusno = d.cusno1
					where b.partno = '$value' and b.Owner_Comp = '$comp'
					and c.Dealer = '$cusno' and trim(b.orderno)='$orderno'
					and b.Corno = '$corno' 
					
					";
				//echo $erp . ">>" .$query;
				$sql=mysqli_query($msqlcon,$query);		
				while($hasil = mysqli_fetch_array ($sql)){
					$slsprice= $hasil['SLS_AMNT'] == null ? $hasil['Price'] : $hasil['SLS_AMNT'];
					$bprice=$hasil['BS_PRCE'] == null ? $hasil['Price'] : $hasil['BS_PRCE'];
					$disc=$hasil['DSCNT_RATIO'] == null ? 0 : $hasil['DSCNT_RATIO'];
					$Dlrprice=$hasil['SLS_AMNT'] == null ? $hasil['Price'] : $hasil['SLS_AMNT'];
					$DlrCD=$hasil['CRNCY_CD'] == null ? $hasil['CurCD'] : $hasil['CRNCY_CD'];

				}
			/*
				bprice :-> Change to 1st customer price get from tf_snd_web_item_ma_XX0 join with awsorderdtl where partno = 'xxxxxxxxx'
				slsprice :-> Change to 1st customer price get from tf_snd_web_item_ma_XX0 join with awsorderdtl where partno = 'xxxxxxxxx'
				Dlrprice :-> Change to 1st customer price get from tf_snd_web_item_ma_XX0 join with awsorderdtl where partno = 'xxxxxxxxx'
				disc :->  Change to 1st customer discount get from tf_snd_web_item_ma_XX0 join with awsorderdtl where partno = 'xxxxxxxxx'
			*/
			} else if($comp == 'IN0'){
				$query="
				select *
				FROM awsorderdtl 
				JOIN awsorderhdr ON awsorderhdr.Corno = awsorderdtl.Corno AND awsorderhdr.cusno = awsorderdtl.cusno 
					and awsorderhdr.orderno = awsorderdtl.orderno and awsorderhdr.Owner_Comp = awsorderdtl.Owner_Comp
                INNER JOIN awscusmas awc ON awc.cusno2 = awsorderhdr.cusno and awc.Owner_Comp = awsorderhdr.Owner_Comp
				-- JOIN sellprice on sellprice.Shipto = awc.ship_to_cd1 and sellprice.Cusno = awsorderhdr.Dealer and  sellprice.Itnbr = awsorderdtl.partno  and awsorderdtl.Owner_Comp = sellprice.Owner_Comp
				WHERE awsorderdtl.Orderno = '$orderno' and awsorderdtl.Corno = '$corno'
					and awsorderdtl.partno = '$value'  and awsorderdtl.cusno = '$vcusno'
					and awsorderdtl.Owner_comp = '$comp'";
				// echo $erp . ">>" .$query;
				$sql=mysqli_query($msqlcon,$query);		
				while($hasil = mysqli_fetch_array ($sql)){
					$slsprice=$hasil['bprice'];
					$bprice=$hasil['bprice'];
					$disc = 0;
					$Dlrprice=$hasil['bprice'];
					$DlrCD=$hasil['DlrCurCd'];
				}
				
			}else{
				$query="
				select *
				FROM awsorderdtl 
				JOIN awsorderhdr ON awsorderhdr.Corno = awsorderdtl.Corno AND awsorderhdr.cusno = awsorderdtl.cusno 
					and awsorderhdr.orderno = awsorderdtl.orderno and awsorderhdr.Owner_Comp = awsorderdtl.Owner_Comp
                INNER JOIN awscusmas awc ON awc.cusno2 = awsorderhdr.cusno and awc.Owner_Comp = awsorderhdr.Owner_Comp
				JOIN sellprice on sellprice.Shipto = awc.ship_to_cd1 and sellprice.Cusno = awsorderhdr.Dealer
					 and  sellprice.Itnbr = awsorderdtl.partno  and awsorderdtl.Owner_Comp = sellprice.Owner_Comp
				WHERE awsorderdtl.Orderno = '$orderno' and awsorderdtl.Corno = '$corno'
					and awsorderdtl.partno = '$value'  and awsorderdtl.cusno = '$vcusno'
					and awsorderdtl.Owner_comp = '$comp'";
				// echo $erp . ">>" .$query;
				$sql=mysqli_query($msqlcon,$query);		
				while($hasil = mysqli_fetch_array ($sql)){
					$slsprice=$hasil['Price'];
					$bprice=$hasil['Price'];
					$disc = 0;
					$Dlrprice=$hasil['Price'];
					$DlrCD=$hasil['CurCD'];
				}
				/*
				bprice :-> Change to 1st customer price get from sellprice where partno = 'xxxxxxxxx' and Owner_comp = 'XX0' and cusno = '[1st customer number]'
				slsprice :-> Change to 1st customer price get from sellprice where partno = 'xxxxxxxxx' and Owner_comp = 'XX0' and cusno = '[1st customer number]'
				Dlrprice :-> Change to 1st customer price get from sellprice where partno = 'xxxxxxxxx' and Owner_comp = 'XX0' and cusno = '[1st customer number]'
				disc :->  Change to 1st customer price get from sellprice where partno = 'xxxxxxxxx' and Owner_comp = 'XX0' and cusno = '[1st customer number]'
				*/
			}
			
			
			if($comp == 'IN0'){
				$query1="INSERT INTO `orderdtl`(`Owner_Comp`, `CUST3`, `orderno`, `cusno`
				, `partno`, `itdsc`, `orderdate`, `qty`, `CurCD`, `bprice`, `SGCurCd`, `SGPrice`
				, `disc`, `dlrdisc`, `slsprice`, `Corno`, `DueDate`,tranflg,`ordflg`, `DlrCurCd`, `DlrPrice`)   
				SELECT '$comp', '".$cust3."' ,awsorderdtl.Orderno , '".$cust3."', partno, itdsc, '$approvedate'
					, qty, awsexc.curr, '$bprice', SGCurCd, ifnull(SGPrice,0), '$disc', dlrdisc
					,ifnull('$slsprice',0),awsorderdtl.Corno, '$xduedate','$Trflg',awsorderdtl.ordflg, '$DlrCD', ifnull('$Dlrprice',0)
				FROM awsorderdtl 
				JOIN awsorderhdr ON awsorderhdr.Corno = awsorderdtl.Corno AND awsorderhdr.cusno = awsorderdtl.cusno 
					and awsorderhdr.orderno = awsorderdtl.orderno and awsorderhdr.Owner_Comp = awsorderdtl.Owner_Comp
					
				JOIN awscusmas on awscusmas.Owner_Comp = awsorderdtl.Owner_Comp and awscusmas.cusno1 = awsorderdtl.Dealer and awscusmas.ship_to_cd2 = awsorderhdr.shipto
				JOIN awsexc on awsexc.itnbr = awsorderdtl.partno and awsexc.Owner_Comp = awsorderdtl.Owner_Comp AND awsexc.cusgrp = awscusmas.cusgrp
				WHERE awsorderdtl.Orderno = '$orderno' and awsorderdtl.Corno = '$corno'
					and awsorderdtl.partno = '$value'  and awsorderdtl.cusno = '$vcusno'
					and awsorderdtl.Owner_comp = '$comp' and awsorderdtl.ordflg = '1'";
			}else{
				$query1="INSERT INTO `orderdtl`(`Owner_Comp`, `CUST3`, `orderno`, `cusno`
				, `partno`, `itdsc`, `orderdate`, `qty`, `CurCD`, `bprice`, `SGCurCd`, `SGPrice`
				, `disc`, `dlrdisc`, `slsprice`, `Corno`, `DueDate`,tranflg,`ordflg`, `DlrCurCd`, `DlrPrice`)   
				SELECT '$comp', '".$cust3."' ,awsorderdtl.Orderno , '".$cust3."', partno, itdsc, '$approvedate'
					, qty, sellprice.CurCd, ifnull('$bprice',0), SGCurCd, ifnull(SGPrice,0), '$disc', dlrdisc
					,ifnull('$slsprice',0),awsorderdtl.Corno, '$xduedate','$Trflg',awsorderdtl.ordflg, '$DlrCD', ifnull('$Dlrprice',0)
				FROM awsorderdtl 
				JOIN awsorderhdr ON awsorderhdr.Corno = awsorderdtl.Corno AND awsorderhdr.cusno = awsorderdtl.cusno 
					and awsorderhdr.orderno = awsorderdtl.orderno and awsorderhdr.Owner_Comp = awsorderdtl.Owner_Comp
				JOIN sellprice on sellprice.Shipto = '$shipto1' and sellprice.Cusno = '$cust3' and  sellprice.Itnbr = awsorderdtl.partno 
				WHERE awsorderdtl.Orderno = '$orderno' and awsorderdtl.Corno = '$corno'
					and awsorderdtl.partno = '$value'  and awsorderdtl.cusno = '$vcusno'
					and awsorderdtl.Owner_comp = '$comp' and awsorderdtl.ordflg = '1'";
			}
			
			$result = mysqli_query($msqlcon,$query1);

			// echo $query1. "<br/>";
	
			$approvedate=date('Ymd');
			//update approvedate aws table
			$query2="update awsorderdtl set ApproveDate='$approvedate',DueDate='$xduedate'
			where partno = '$value' and trim(orderno)='$orderno' and Owner_Comp ='$comp' 
				and Corno = '$corno'  and cusno = '$vcusno' " ;
			$result2 = mysqli_query($msqlcon,$query2);
			//echo $query2. "<br/>";
			$no_flag++;
		}

		//update header
		//check has approve on the order no and corno
		$query="SELECT * 
		FROM awsorderdtl 
		WHERE ordflg in ('1','2') 
			and orderno =  '$orderno'  and corno = '$corno'
			and Owner_Comp = '$comp' and cusno ='$vcusno'";
		$result = mysqli_query($msqlcon,$query);
		$count = mysqli_num_rows($result);
		$query2 = "";
		if($count>=1){
			$query2="
			update awsorderhdr set ordflg='1', Trflg='1'
			where trim(orderno)='".$orderno."' and Owner_Comp ='".$comp. "' 
				and Corno = '$corno' and cusno = '$vcusno'  and ordtype = 'R'" ;
		}
		else{
			$query2="
				update awsorderhdr set ordflg='R', Trflg='R'
				where trim(orderno)='".$orderno."' and Owner_Comp ='".$comp. "' 
					and Corno = '$corno' and cusno = '$vcusno'  and ordtype = 'R'" ;
		}
		$result = mysqli_query($msqlcon,$query2);


	}
    else{//Non Denso supawsorderhdr
		
		require('supgetDueDate.php');
		 //getduedate
		 $DueDate = "";
		 

		//get detail
		$querydetail="SELECT *
		FROM supawsorderdtl
		WHERE Owner_Comp = '$comp' AND orderno = '$orderno' 
			AND cusno = '$vcusno'  AND Corno = '$corno'  and supno =  '$shpno' ";
		//echo $querydetail;
		$sqldetail=mysqli_query($msqlcon,$querydetail);		
		$mpartno = array();
		$ordflg= array();
		while($hasil = mysqli_fetch_array ($sqldetail)){
			$mpartno[] =$hasil['partno'];
			$ordflg[] =$hasil['ordflg'];
			$DueDate=$hasil['DueDate'];
			$OrderDate=$hasil['orderdate'];
		}
		//$spartno = explode(',',$mpartno);
		$no_flag=0;
		//print_r($mpartno);
		// echo "Duedate = "  .$DueDate;
		foreach ($mpartno as $vpartno){
			//echo "orderflag = " .$ordflg[$no_flag] ."<br>";
			if( $ordflg[$no_flag] == "1"){
				//GetSupplierDueDate
				$requestDateArr = AWSGetApp1SupplierDueDate($shpno);
				// print_r($requestDateArr);
				if($requestDateArr[3] == '1'){ //error
					echo "failed";
					exit;
				}
				if($DueDate > $requestDateArr[0] ){  	// cusno1 >= cusno2 ??
					$xduedate = $DueDate ; 	// cusno1
				}
				else{
					$xduedate = $requestDateArr[0]; 	// cusno2
				}
			 }
			 else{
				//GetSupplierDueDate
				$requestDateArr = AWSGetApp2SupplierDueDate($shpno);
				// print_r($requestDateArr);
				if($requestDateArr[3] == '1'){ //error
					echo "failed";
					exit;
				}
				if($DueDate > $requestDateArr[0] ){  	// cusno1 >= cusno2 ??
					$xduedate = $DueDate ; 	// cusno1
				}
				else{
					$xduedate = $requestDateArr[0]; 	// cusno2
				}
			 }
			 // print_r($requestDateArr);
			 // echo ';Order saved';
			//check price
			if($comp == 'IN0'){
				$price_chk_cusno = $cust3;
			}else{
				$price_chk_cusno = $cust3;
			}
			
			$slsprice=0;
			$bprice=0;
			$disc = 0;
			$Dlrprice=0;
			$Dlrcurr=0;
			
			
			$query="
			select * 
			from awscusmas 
			inner join supprice on awscusmas.cusno1 = supprice.Cusno and  
				awscusmas.Owner_Comp = supprice.Owner_comp and  awscusmas.ship_to_cd1 = supprice.shipto
			where supprice.cusno =  '$price_chk_cusno' and supprice.partno = '$vpartno'  
				and supprice.supno = '$shpno' and supprice.Owner_comp = '$comp'";
			 // echo $erp . ">>" .$query;
			// echo $cust3 ." << : >> ".$cust3;
			$sql=mysqli_query($msqlcon,$query);		
			while($hasil = mysqli_fetch_array ($sql)){
				$slsprice=intval($hasil['price']);
				$bprice=intval($hasil['price']);
				$disc = 0;
				$Dlrprice=intval($hasil['price']);
				$Dlrcurr=intval($hasil['curr']);
			}
			$query1="INSERT INTO `suporderdtl`(`Owner_Comp`, `supno`, `CUST3`, `orderno`, `cusno`
				, `partno`, `itdsc`, `orderdate`
				, `qty`, `CurCD`, `bprice`, `SGCurCd`, `SGPrice`, `disc`, `dlrdisc`, `slsprice`
				, `Corno`, `DueDate`,`ordflg`,tranflg, `DlrCurCd`, `DlrPrice`) 
				SELECT '$comp',supawsorderhdr.supno, '".$cust3."' ,supawsorderdtl.Orderno , '".$cust3."'
					, partno, itdsc, '$approvedate'
					, qty, supawsorderdtl.CurCd, '".intval($bprice)."', SGCurCd, SGPrice, '".intval($disc)."', dlrdisc,'".intval($slsprice)."'
					,supawsorderdtl.Corno,'$xduedate',supawsorderdtl.ordflg,'$Trflg', '$Dlrcurr', '".intval($Dlrprice)."'
				FROM supawsorderdtl 
				JOIN supawsorderhdr ON supawsorderhdr.Corno = supawsorderdtl.Corno AND supawsorderhdr.cusno = supawsorderdtl.cusno 
					and supawsorderhdr.orderno = supawsorderdtl.orderno and supawsorderhdr.Owner_Comp = supawsorderdtl.Owner_Comp
				WHERE supawsorderdtl.Orderno = '$orderno' and supawsorderdtl.Corno = '$corno' and supawsorderdtl.supno =  '$shpno'
					and supawsorderdtl.cusno = '$vcusno' and supawsorderdtl.Owner_comp = '$comp' and supawsorderdtl.ordflg = '1' AND supawsorderdtl.partno = '$vpartno'";
			 $result = mysqli_query($msqlcon,$query1);
			  // echo $query1;
			  
			
			$approvedate=date('Ymd');

			//update approvedate aws table
			$query2="update supawsorderdtl set ApproveDate='$approvedate', DueDate='$xduedate'
			where partno = '$vpartno' and trim(orderno)='$orderno' and Owner_Comp ='$comp' 
				and Corno = '$corno' and supno =  '$shpno' and cusno = '$vcusno' " ;
			$result2 = mysqli_query($msqlcon,$query2);
			//echo $query2. "<br/>";
			$no_flag++;
		}

		//update header
		//check has approve on the order no and corno
		$query="SELECT * 
		FROM supawsorderdtl 
		WHERE ordflg in ('1','2') 
			and orderno =  '$orderno'  and corno = '$corno'
			and Owner_Comp = '$comp' and cusno ='$vcusno'";
		$result = mysqli_query($msqlcon,$query);
		$count = mysqli_num_rows($result);
		$query2 = "";
		if($count>=1){
			$query2="
			update supawsorderhdr set ordflg='1', Trflg='1'
			where trim(orderno)='".$orderno."' and Owner_Comp ='".$comp. "' 
				and Corno = '$corno' and cusno = '$vcusno'  and ordtype = '$vordtype'" ;
		}
		else{
			$query2="
				update supawsorderhdr set ordflg='R', Trflg='R'
				where trim(orderno)='".$orderno."' and Owner_Comp ='".$comp. "' 
					and Corno = '$corno' and cusno = '$vcusno'  and ordtype = '$vordtype'" ;
		}
		$result = mysqli_query($msqlcon,$query2);

		
		//header
		$query3="
		INSERT INTO suporderhdr
		SELECT supawsorderhdr.Owner_Comp, supawsorderhdr.supno, '".$cust3."', supawsorderhdr.orderno, '".$cust3."', supawsorderhdr.ordtype
		, '$approvedate', supawsorderhdr.orderprd, supawsorderhdr.Corno, supawsorderhdr.DlvBy, '1', '1', '".$cust3."'
		, supawsorderhdr.ordflagdate, supawsorderhdr.ordflagtime, supawsorderhdr.ordflaguser, '', supawsorderhdr.tranflagtime
		, supawsorderhdr.tranflaguser, supawsorderhdr.OECus, supawsorderhdr.Shipment, awscusmas.ship_to_cd1 
			FROM supawsorderdtl 
			JOIN supawsorderhdr ON supawsorderhdr.Corno = supawsorderdtl.Corno AND supawsorderhdr.cusno = supawsorderdtl.cusno 
				and supawsorderhdr.orderno = supawsorderdtl.orderno and supawsorderhdr.Owner_Comp = supawsorderdtl.Owner_Comp 
			INNER JOIN awscusmas on awscusmas.ship_to_cd2 = supawsorderhdr.shipto and supawsorderhdr.cusno = awscusmas.cusno2 and awscusmas.Owner_Comp = supawsorderhdr.Owner_Comp and awscusmas.cusno1 = supawsorderhdr.Dealer
		WHERE supawsorderhdr.Corno ='$corno' and supawsorderhdr.Owner_Comp = '$comp' and supawsorderhdr.orderno='$orderno'  
		and supawsorderhdr.cusno='$vcusno'  and  supawsorderhdr.ordtype='$vordtype'
		and supawsorderhdr.supno = '$shpno' and supawsorderdtl.ordflg = '1' limit 1
		";
		//add trn flg 1 after DLV by
		$result3 = mysqli_query($msqlcon,$query3);
		//echo $query."<br/>";
		
		$query4="
		INSERT INTO supordernts
		SELECT supawsorderhdr.Owner_Comp, supawsorderhdr.supno, '".$cust3."', supawsorderhdr.orderno, '".$cust3."', supawsorderhdr.ordtype
		, '$approvedate', supawsorderhdr.orderprd, supawsorderhdr.Corno, supawsorderhdr.DlvBy, '1', '', '".$cust3."'
		, supawsorderhdr.ordflagdate, supawsorderhdr.ordflagtime, supawsorderhdr.ordflaguser, '', supawsorderhdr.tranflagtime
		, supawsorderhdr.tranflaguser, supawsorderhdr.OECus, supawsorderhdr.Shipment, awscusmas.ship_to_cd1 ,'',''
			FROM supawsorderdtl 
			JOIN supawsorderhdr ON supawsorderhdr.Corno = supawsorderdtl.Corno AND supawsorderhdr.cusno = supawsorderdtl.cusno 
				and supawsorderhdr.orderno = supawsorderdtl.orderno and supawsorderhdr.Owner_Comp = supawsorderdtl.Owner_Comp 
			INNER JOIN awscusmas on awscusmas.ship_to_cd2 = supawsorderhdr.shipto and supawsorderhdr.cusno = awscusmas.cusno2 and awscusmas.Owner_Comp = supawsorderhdr.Owner_Comp and awscusmas.cusno1 = supawsorderhdr.Dealer
		WHERE supawsorderhdr.Corno ='$corno' and supawsorderhdr.Owner_Comp = '$comp' and supawsorderhdr.orderno='$orderno'  
		and supawsorderhdr.cusno='$vcusno'  and  supawsorderhdr.ordtype='$vordtype'
		and supawsorderhdr.supno = '$shpno' and supawsorderdtl.ordflg = '1' limit 1
		";
		//echo $query3 . "<br/>";
		$result3 = mysqli_query($msqlcon,$query4);
		//echo $query."<br/>";
	}
	echo "success";
}

}

?>

