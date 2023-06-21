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
	require('db/conn.inc');

	if($table == "awsorderhdr"){//Denso
        require('aws_sendemail_denso.php');
        require_once('aws_config/aws_Web_Lip.php'); 
        $to = get_aws_config("to");
        $from=get_aws_config("from");
        $cc = explode(";",$emailcus);
        $bcc = get_aws_config("bcc");
        $bcc = explode(";",$bcc);
        // get all items of po to know how many status
        $query="
        select  awsorderdtl.ordflg, count(*) as status
        from awsorderhdr 
            join awsorderdtl on awsorderhdr.Owner_Comp = awsorderdtl.Owner_Comp 
                and awsorderhdr.orderno = awsorderdtl.orderno and awsorderhdr.Corno =  awsorderdtl.Corno
        where trim(awsorderdtl.orderno)= '$orderno' and awsorderdtl.Owner_Comp = '$comp' 
        and awsorderdtl.Corno =   '$corno'   and awsorderhdr.cusno = '$vcusno' 
        group by awsorderdtl.ordflg "; 
        // echo  $query;
        
        $sql=mysqli_query($msqlcon,$query);

        


        while($tmphsl=mysqli_fetch_array($sql)){
            $ordflg=$tmphsl['ordflg'];
            $status=$tmphsl['status'];
			if($ordflg != 'R'){
				// send Email cusno 2 one file
				$emailcusall=[];
				$tmpEmailall=[];
				$query1="
					SELECT DISTINCT
						awscusmas.mail_add1,
						awscusmas.mail_add2,
						awscusmas.mail_add3,
						shiptoma.comp_mail_add,
						shiptoma.prsn_mail_add1,
						shiptoma.prsn_mail_add2,
						shiptoma.prsn_mail_add3
					FROM
						awsorderhdr
					JOIN awsorderdtl ON awsorderhdr.Owner_Comp = awsorderdtl.Owner_Comp AND awsorderhdr.orderno = awsorderdtl.orderno AND awsorderhdr.Corno = awsorderdtl.Corno
					LEFT JOIN awscusmas ON awsorderhdr.Dealer = awscusmas.cusno1 AND awsorderhdr.cusno = awscusmas.cusno2 AND awsorderhdr.Owner_Comp = awscusmas.Owner_Comp -- cus2
					LEFT JOIN shiptoma ON awsorderhdr.Owner_Comp = shiptoma.Owner_Comp AND awsorderhdr.Dealer = shiptoma.Cusno AND shiptoma.ship_to_cd = awscusmas.ship_to_cd1 -- cus1
					where trim(awsorderdtl.orderno)= '$orderno' and awsorderdtl.Owner_Comp =  '$comp' 
						and awsorderdtl.Corno =  '$corno' and awsorderdtl.cusno ='$vcusno' 
				 "; 
				// echo $query1;
				$sql1=mysqli_query($msqlcon,$query1);
				while($tmphsl1=mysqli_fetch_array($sql1)){
					$mail_app1_add1=$tmphsl1['mail_add1'];
					$mail_app1_add2=$tmphsl1['mail_add2'];
					$mail_app1_add3=$tmphsl1['mail_add3'];
					$comp_app1_mail_add=$tmphsl1['comp_mail_add'];
					$mail_aap1_1st1 = $tmphsl1['prsn_mail_add1'];
					$mail_aap1_1st2 = $tmphsl1['prsn_mail_add2'];
					$mail_aap1_1st3 = $tmphsl1['prsn_mail_add3'];
				}
				array_push($tmpEmailall,$mail_app1_add1,$mail_app1_add2,$mail_app1_add3,$comp_app1_mail_add,$mail_aap1_1st1,$mail_aap1_1st2,$mail_aap1_1st3 );
				// print_r($tmpEmailall);
				for($index=0;$index<count($tmpEmailall);$index++){
					if(!in_array($tmpEmailall[$index],$emailcusall) && !empty($tmpEmailall[$index]) ){
						array_push($emailcusall,$tmpEmailall[$index]);
					}
				}
				awssendmaildensoAttachFile($emailcusall, $orderno, $corno , $cusno , $cusnm , $bcc , null, null, "all");

			}
            if(intval($status) >= 1){
                switch ($ordflg){
                    case "1":
                         // approve1 => send mail to cus1 denso
                        $emailcus1=[];
                        $tmpEmail1=[];
                        $query1="
                            select distinct awscusmas.mail_add1, awscusmas.mail_add2, awscusmas.mail_add3
                            , shiptoma.comp_mail_add,shiptoma.prsn_mail_add1,shiptoma.prsn_mail_add2,shiptoma.prsn_mail_add3
                            from orderhdr 
                                join orderdtl on orderhdr.Owner_Comp = orderdtl.Owner_Comp   and orderhdr.orderno = orderdtl.orderno and orderhdr.Corno =  orderdtl.Corno
                                left join awscusmas on orderhdr.Dealer = awscusmas.cusno1 and orderhdr.cusno = awscusmas.cusno1 and orderhdr.Owner_Comp = awscusmas.Owner_Comp -- cus2
                                left join shiptoma on orderhdr.Owner_Comp = shiptoma.Owner_Comp and orderhdr.Dealer = shiptoma.Cusno AND shiptoma.ship_to_cd = awscusmas.ship_to_cd1 -- cus1
                            where trim(orderdtl.orderno)= '$orderno' and orderdtl.Owner_Comp =  '$comp' 
                                and orderdtl.Corno =  '$corno' and awscusmas.cusno2 ='$vcusno' 
                        "; 
						
                        $sql1=mysqli_query($msqlcon,$query1);
                        while($tmphsl1=mysqli_fetch_array($sql1)){
							// print_r($tmphsl1);
                            $mail_app1_add1=$tmphsl1['mail_add1'];
                            $mail_app1_add2=$tmphsl1['mail_add2'];
                            $mail_app1_add3=$tmphsl1['mail_add3'];
                            $comp_app1_mail_add=$tmphsl1['comp_mail_add'];
							$mail_aap1_1st1 = $tmphsl1['prsn_mail_add1'];
							$mail_aap1_1st2 = $tmphsl1['prsn_mail_add2'];
							$mail_aap1_1st3 = $tmphsl1['prsn_mail_add3'];
                        }
                        // array_push($tmpEmail1, $to,$comp_app1_mail_add,$mail_aap1_1st1,$mail_aap1_1st2,$mail_aap1_1st3,$mail_app1_add1,$mail_app1_add2,$mail_app1_add3);
                        array_push($tmpEmail1, $to,$comp_app1_mail_add,$mail_aap1_1st1,$mail_aap1_1st2,$mail_aap1_1st3);
						for($index=0;$index<count($tmpEmail1);$index++){
                            if(
                                !in_array($tmpEmail1[$index],$emailcus1) && !empty($tmpEmail1[$index]) ){
                                array_push($emailcus1,$tmpEmail1[$index]);
                            }
                        }
                      //  echo "Approve1 >> ";
                      //  print_r($emailcus1)."<BR/>";
                        awssendmaildensoAttachFile($emailcus1, $orderno, $corno , $cusno , $cusnm , $bcc , null, null, "1");

                        break;
                    case "2":
                        // approve2 => send mail to cus2 cus1
                        $emailcus2=[];
                        $tmpEmail2=[];
                        $query2="
                            select distinct awscusmas.mail_add1, awscusmas.mail_add2, awscusmas.mail_add3
                            , shiptoma.comp_mail_add,shiptoma.prsn_mail_add1,shiptoma.prsn_mail_add2,shiptoma.prsn_mail_add3
                            from awsorderhdr 
                                join awsorderdtl on awsorderhdr.Owner_Comp = awsorderdtl.Owner_Comp   and awsorderhdr.orderno = awsorderdtl.orderno and awsorderhdr.Corno =  awsorderdtl.Corno
                                left join awscusmas on awsorderhdr.Dealer = awscusmas.cusno1 and awsorderhdr.cusno = awscusmas.cusno2 and awsorderhdr.Owner_Comp = awscusmas.Owner_Comp -- cus2
                                left join shiptoma on awsorderhdr.Owner_Comp = shiptoma.Owner_Comp and awsorderhdr.Dealer = shiptoma.Cusno AND shiptoma.ship_to_cd = awscusmas.ship_to_cd1 -- cus1
                            where trim(awsorderdtl.orderno)= '$orderno' and awsorderdtl.Owner_Comp =  '$comp' 
                                and awsorderdtl.Corno =  '$corno' and awsorderdtl.cusno ='$vcusno' 
                        "; 
                        $sql2=mysqli_query($msqlcon,$query2);
						
                        if($tmphsl2=mysqli_fetch_array($sql2)){
							// print_r($tmphsl2);

                            $mail_app2_add1=$tmphsl2['mail_add1'];
                            $mail_app2_add2=$tmphsl2['mail_add2'];
                            $mail_app2_add3=$tmphsl2['mail_add3'];
                            $comp_mail_app2_add=$tmphsl2['comp_mail_add'];
							$mail_aap2_1st1 = $tmphsl2['prsn_mail_add1'];
							$mail_aap2_1st2 = $tmphsl2['prsn_mail_add2'];
							$mail_aap2_1st3 = $tmphsl2['prsn_mail_add3'];
                        }
                        array_push($tmpEmail2,$comp_mail_app2_add,$mail_aap2_1st1,$mail_aap2_1st2,$mail_aap2_1st3,$mail_app2_add1,$mail_app2_add2,$mail_app2_add3);
                        for($index2=0;$index2<count($tmpEmail2);$index2++){
                            if(
                                !in_array($tmpEmail2[$index2],$emailcus2) && !empty($tmpEmail2[$index2]) ){
                                array_push($emailcus2,$tmpEmail2[$index2]);
                            }
                        }
                        
                        awssendmaildensoAttachFile($emailcus2, $orderno, $corno , $cusno , $cusnm , $bcc, null, null, "2");

                     //   echo "Approve2 >> ";
                     //   print_r($emailcus2)."<BR/>";
                        break;
                
					case "R":
                        // approve2 => send mail to cus2 cus1
                        $emailcusR=[];
                        $tmpEmailR=[];
                        $queryR="
                            select distinct awscusmas.mail_add1, awscusmas.mail_add2, awscusmas.mail_add3
                            , shiptoma.comp_mail_add,shiptoma.prsn_mail_add1,shiptoma.prsn_mail_add2,shiptoma.prsn_mail_add3
                            from awsorderhdr 
                                join awsorderdtl on awsorderhdr.Owner_Comp = awsorderdtl.Owner_Comp   and awsorderhdr.orderno = awsorderdtl.orderno and awsorderhdr.Corno =  awsorderdtl.Corno
                                left join awscusmas on awsorderhdr.Dealer = awscusmas.cusno1 and awsorderhdr.cusno = awscusmas.cusno2 and awsorderhdr.Owner_Comp = awscusmas.Owner_Comp 
								
                                left join shiptoma on awsorderhdr.Owner_Comp = shiptoma.Owner_Comp and awsorderhdr.Dealer = shiptoma.Cusno AND shiptoma.ship_to_cd = awscusmas.ship_to_cd1 
								
                            where trim(awsorderdtl.orderno)= '$orderno' and awsorderdtl.Owner_Comp =  '$comp' 
                                and awsorderdtl.Corno =  '$corno' and awsorderdtl.cusno ='$vcusno' 
                        "; 
                        $sqlR=mysqli_query($msqlcon,$queryR);
						
                        if($tmphslR=mysqli_fetch_array($sqlR)){
							// print_r($tmphsl2);

                            $mail_R_add1=$tmphslR['mail_add1'];
                            $mail_R_add2=$tmphslR['mail_add2'];
                            $mail_R_add3=$tmphslR['mail_add3'];
                            $comp_mail_R_add=$tmphslR['comp_mail_add'];
							$mail_R_1st1 = $tmphslR['prsn_mail_add1'];
							$mail_R_1st2 = $tmphslR['prsn_mail_add2'];
							$mail_R_1st3 = $tmphslR['prsn_mail_add3'];
                        }
                        array_push($tmpEmailR,$mail_R_add1,$mail_R_add2,$mail_R_add3);
                        for($indexR=0;$indexR<count($tmpEmailR);$indexR++){
                            if(
                                !in_array($tmpEmailR[$indexR],$emailcus2) && !empty($tmpEmailR[$indexR]) ){
                                array_push($emailcus2,$tmpEmailR[$indexR]);
                            }
                        }
                        // print_r($tmpEmailR);
                        awssendmaildensoAttachFile($tmpEmailR, $orderno, $corno , $cusno , $cusnm , $bcc, null, null, "all");

                     //   echo "Approve2 >> ";
                     //   print_r($emailcus2)."<BR/>";
                        break;
                
                }
            }
        }
       

    }
    else{//NON denso
        require('aws_sendemail_nondenso.php');
        require_once('aws_config/nondenso/aws_Web_Lip.php'); 
        $to = get_aws_sup_config("to");
        $from=get_aws_sup_config("from");
        $cc = explode(";",$emailcus);
        $bcc = get_aws_sup_config("bcc");
        $bcc = explode(";",$bcc);
         // get all items of po to know how many status
         $query="
         select  supawsorderdtl.ordflg, count(*) as status
         from supawsorderhdr 
             join supawsorderdtl on supawsorderhdr.Owner_Comp = supawsorderdtl.Owner_Comp 
                 and supawsorderhdr.orderno = supawsorderdtl.orderno and supawsorderhdr.Corno =  supawsorderdtl.Corno
         where trim(supawsorderdtl.orderno)= '$orderno' and supawsorderdtl.Owner_Comp = '$comp' 
         and supawsorderdtl.Corno =   '$corno'   and supawsorderhdr.cusno = '$vcusno' and supawsorderdtl.supno = '$shpno'
         group by supawsorderdtl.ordflg "; 
        // echo  $query;

       $sql=mysqli_query($msqlcon,$query);

        

       while($tmphsl=mysqli_fetch_array($sql)){
           $ordflg=$tmphsl['ordflg'];
           $status=$tmphsl['status'];
			if($ordflg != 'R'){
				// send Email cusno 2 one file
				$emailcusall=[];
				$tmpEmailall=[];
				$query1="
					SELECT DISTINCT
					awscusmas.mail_add1,
					awscusmas.mail_add2,
					awscusmas.mail_add3,
					supmas.supno,
					supmas.supnm,
					supmas.email1,
					supmas.email2,
					shiptoma.comp_mail_add,
					shiptoma.prsn_mail_add1,
					shiptoma.prsn_mail_add2,
					shiptoma.prsn_mail_add3
				FROM
					supawsorderhdr
				JOIN supawsorderdtl ON supawsorderhdr.Owner_Comp = supawsorderdtl.Owner_Comp AND supawsorderhdr.orderno = supawsorderdtl.orderno AND supawsorderhdr.Corno = supawsorderdtl.Corno
				LEFT JOIN awscusmas ON supawsorderhdr.Dealer = awscusmas.cusno1 AND supawsorderhdr.cusno = awscusmas.cusno2 AND supawsorderhdr.Owner_Comp = awscusmas.Owner_Comp AND awscusmas.ship_to_cd2 = supawsorderhdr.shipto -- cus2
				LEFT JOIN supmas ON supawsorderdtl.Owner_Comp = supmas.Owner_Comp AND supawsorderdtl.supno = supmas.supno -- supplier
				LEFT JOIN shiptoma ON supawsorderhdr.Owner_Comp = shiptoma.Owner_Comp AND supawsorderhdr.Dealer = shiptoma.Cusno AND shiptoma.ship_to_cd = awscusmas.ship_to_cd1 -- cus1
					where trim(supawsorderdtl.orderno)= '$orderno' and supawsorderdtl.Owner_Comp =  '$comp' 
						and supawsorderdtl.Corno =  '$corno' and supawsorderdtl.cusno ='$vcusno' 
					 "; 
				 // echo $query1;
				$sql1=mysqli_query($msqlcon,$query1);
				while($tmphsl1=mysqli_fetch_array($sql1)){
					$comp_app1_mail_add=$tmphsl1['comp_mail_add'];
					$pers_mail_app1_add1=$tmphsl1['prsn_mail_add1'];
					$pers_mail_app1_add2=$tmphsl1['prsn_mail_add2'];
					$pers_mail_app1_add3=$tmphsl1['prsn_mail_add3'];
					$mail_app1_add1=$tmphsl1['mail_add1'];
					$mail_app1_add2=$tmphsl1['mail_add2'];
					$mail_app1_add3=$tmphsl1['mail_add3'];
					$supno=$tmphsl1['supno'];
					$supnm=$tmphsl1['supnm'];
					$sum_mail1=$tmphsl1['email1'];
					$sum_mail2=$tmphsl1['email2'];
				}
				array_push($tmpEmailall,$mail_app1_add1,$mail_app1_add2,$mail_app1_add3,$comp_app1_mail_add,$pers_mail_app1_add1,$pers_mail_app1_add2,$pers_mail_app1_add3);
				for($index=0;$index<count($tmpEmailall);$index++){
					if(!in_array($tmpEmailall[$index],$emailcusall) && !empty($tmpEmailall[$index]) ){
						array_push($emailcusall,$tmpEmailall[$index]);
					}
				}
				awssendmailnondensoAttachFile($emailcusall, $orderno, $corno , $cusno , $cusnm , $bcc , $shpno,  $supnm, "all");

			}
           if(intval($status) >= 1){
               switch ($ordflg){
                   case "1":
                        // approve1 => send mail to cus1 denso cus2 
                       $emailcus1=[];
                       $tmpEmail1=[];
                       $query1="
                           select distinct awscusmas.mail_add1, awscusmas.mail_add2, awscusmas.mail_add3 ,supmas.email1,supmas.email2
                           , shiptoma.comp_mail_add, supmas.supno, supmas.supnm, supmas.supno, supmas.supnm ,shiptoma.prsn_mail_add1,shiptoma.prsn_mail_add2,shiptoma.prsn_mail_add3
                           from suporderhdr 
                               join suporderdtl on suporderhdr.Owner_Comp = suporderdtl.Owner_Comp   and suporderhdr.orderno = suporderdtl.orderno and suporderhdr.Corno =  suporderdtl.Corno
                               left join awscusmas on suporderhdr.Dealer = awscusmas.cusno1 and suporderhdr.cusno = awscusmas.cusno1 and suporderhdr.Owner_Comp = awscusmas.Owner_Comp -- cus2
                               left join shiptoma on suporderhdr.Owner_Comp = shiptoma.Owner_Comp and suporderhdr.Dealer = shiptoma.Cusno -- cus1
                               left join supmas on  suporderdtl.Owner_Comp = supmas.Owner_Comp  and suporderdtl.supno = supmas.supno -- supplier
                           where trim(suporderdtl.orderno)= '$orderno' and suporderdtl.Owner_Comp =  '$comp' 
                               and suporderdtl.Corno =  '$corno' and awscusmas.cusno2 ='$vcusno' 
                       "; 
                     //echo $query1;
                       $sql1=mysqli_query($msqlcon,$query1);
                       while($tmphsl2=mysqli_fetch_array($sql1)){
							$mail_app1_add1=$tmphsl2['mail_add1'];
							$mail_app1_add2=$tmphsl2['mail_add2'];
							$mail_app1_add3=$tmphsl2['mail_add3'];
							$comp_app1_mail_add=$tmphsl2['comp_mail_add'];
							$pers_mail_app1_add1=$tmphsl2['prsn_mail_add1'];
							$pers_mail_app1_add2=$tmphsl2['prsn_mail_add2'];
							$pers_mail_app1_add3=$tmphsl2['prsn_mail_add3'];
							$supno=$tmphsl2['supno'];
							$supnm=$tmphsl2['supnm'];
							$sum_mail1=$tmphsl2['email1'];
							$sum_mail2=$tmphsl2['email2'];
                       }
                     //  array_push($tmpEmail1, $to,$mail_app1_add1,$mail_app1_add2,$mail_app1_add3,$comp_app1_mail_add);
                       // array_push($tmpEmail1, $to,$comp_app1_mail_add,$pers_mail_app1_add1,$pers_mail_app1_add2,$pers_mail_app1_add3,$sum_mail1,$sum_mail2,$mail_app1_add1,$mail_app1_add2,$mail_app1_add3);
                       array_push($tmpEmail1, $to,$comp_app1_mail_add,$pers_mail_app1_add1,$pers_mail_app1_add2,$pers_mail_app1_add3,$sum_mail1,$sum_mail2);
                       for($index=0;$index<count($tmpEmail1);$index++){
                           if(
                               !in_array($tmpEmail1[$index],$emailcus1) && !empty($tmpEmail1[$index]) ){
                               array_push($emailcus1,$tmpEmail1[$index]);
                           }
                       }
                     //  echo "Approve1 >> ";
                       //print_r($emailcus1)."<BR/>";
                      // echo $emailcus1 .">>". $orderno.">>". $corno .">>". $cusno.">>".$cusnm .">>". $bcc .">>". $supno.">>".  $supnm;
					  // print_r($emailcus1);
                      awssendmailnondensoAttachFile($emailcus1, $orderno, $corno , $cusno , $cusnm , $bcc , $shpno,  $supnm, "1");

                       break;
					   
					case "2":
                       // approve2 => send mail to cus1 cus2 
                       $emailcus2=[];
                       $tmpEmail2=[];
                       $query2="
                           select distinct awscusmas.mail_add1, awscusmas.mail_add2, awscusmas.mail_add3,supmas.email1,supmas.email2
                           , shiptoma.comp_mail_add, supmas.supno, supmas.supnm ,shiptoma.prsn_mail_add1,shiptoma.prsn_mail_add2,shiptoma.prsn_mail_add3
                           from supawsorderhdr 
                               join supawsorderdtl on supawsorderhdr.Owner_Comp = supawsorderdtl.Owner_Comp   and supawsorderhdr.orderno = supawsorderdtl.orderno and supawsorderhdr.Corno =  supawsorderdtl.Corno
                               left join awscusmas on supawsorderhdr.Dealer = awscusmas.cusno1 and supawsorderhdr.cusno = awscusmas.cusno2 and supawsorderhdr.Owner_Comp = awscusmas.Owner_Comp -- cus2
                               left join shiptoma on supawsorderhdr.Owner_Comp = shiptoma.Owner_Comp and supawsorderhdr.Dealer = shiptoma.Cusno -- cus1
                               left join supmas on  supawsorderdtl.Owner_Comp = supmas.Owner_Comp  and supawsorderdtl.supno = supmas.supno -- supplier
                           where trim(supawsorderdtl.orderno)= '$orderno' and supawsorderdtl.Owner_Comp =  '$comp' 
                               and supawsorderdtl.Corno =  '$corno' and supawsorderdtl.cusno ='$vcusno' 
                       "; 
                       //echo $query2;
                       $sql2=mysqli_query($msqlcon,$query2);
                       if($tmphsl2=mysqli_fetch_array($sql2)){
							$mail_app2_add1=$tmphsl2['mail_add1'];
							$mail_app2_add2=$tmphsl2['mail_add2'];
							$mail_app2_add3=$tmphsl2['mail_add3'];
							$comp_mail_app2_add=$tmphsl2['comp_mail_add'];
							$pers_mail_app2_add1=$tmphsl2['prsn_mail_add1'];
							$pers_mail_app2_add2=$tmphsl2['prsn_mail_add2'];
							$pers_mail_app2_add3=$tmphsl2['prsn_mail_add3'];
							$supno=$tmphsl2['supno'];
							$supnm=$tmphsl2['supnm'];
							$sum_mail1=$tmphsl2['email1'];
							$sum_mail2=$tmphsl2['email2'];
                       }
                     //  array_push($tmpEmail2, $mail_app2_add1,$mail_app2_add2,$mail_app2_add3,$comp_mail_app2_add);
                       array_push($tmpEmail2, $comp_mail_app2_add,$pers_mail_app2_add1,$pers_mail_app2_add2,$pers_mail_app2_add3,$mail_app2_add1,$mail_app2_add2,$mail_app2_add3);
                       for($index2=0;$index2<count($tmpEmail2);$index2++){
                           if(
                               !in_array($tmpEmail2[$index2],$emailcus2) && !empty($tmpEmail2[$index2]) ){
                               array_push($emailcus2,$tmpEmail2[$index2]);
                           }
                       }
                       
                       awssendmailnondensoAttachFile($emailcus2, $orderno, $corno , $cusno , $cusnm , $bcc, $shpno,  $supnm, "2");

                       //echo "Approve2 >> ";
                       //print_r($emailcus2)."<BR/>";
                       break;
						
					case "R":
                       // Reject => 2nd only
                       $emailcusR=[];
                       $tmpEmailR=[];
                       $queryR="
                           select distinct awscusmas.mail_add1, awscusmas.mail_add2, awscusmas.mail_add3,supmas.email1,supmas.email2
                           , shiptoma.comp_mail_add, supmas.supno, supmas.supnm ,shiptoma.prsn_mail_add1,shiptoma.prsn_mail_add2,shiptoma.prsn_mail_add3
                           from supawsorderhdr 
                               join supawsorderdtl on supawsorderhdr.Owner_Comp = supawsorderdtl.Owner_Comp   and supawsorderhdr.orderno = supawsorderdtl.orderno and supawsorderhdr.Corno =  supawsorderdtl.Corno
                               left join awscusmas on supawsorderhdr.Dealer = awscusmas.cusno1 and supawsorderhdr.cusno = awscusmas.cusno2 and supawsorderhdr.Owner_Comp = awscusmas.Owner_Comp -- cus2
                               left join shiptoma on supawsorderhdr.Owner_Comp = shiptoma.Owner_Comp and supawsorderhdr.Dealer = shiptoma.Cusno -- cus1
                               left join supmas on  supawsorderdtl.Owner_Comp = supmas.Owner_Comp  and supawsorderdtl.supno = supmas.supno -- supplier
                           where trim(supawsorderdtl.orderno)= '$orderno' and supawsorderdtl.Owner_Comp =  '$comp' 
                               and supawsorderdtl.Corno =  '$corno' and supawsorderdtl.cusno ='$vcusno' 
                       "; 
                       //echo $queryR;
                       $sqlR=mysqli_query($msqlcon,$queryR);
                       if($tmphslR=mysqli_fetch_array($sqlR)){
							$mail_app2_add1=$tmphslR['mail_add1'];
							$mail_app2_add2=$tmphslR['mail_add2'];
							$mail_app2_add3=$tmphslR['mail_add3'];
							$comp_mail_app2_add=$tmphslR['comp_mail_add'];
							$pers_mail_app2_add1=$tmphslR['prsn_mail_add1'];
							$pers_mail_app2_add2=$tmphslR['prsn_mail_add2'];
							$pers_mail_app2_add3=$tmphslR['prsn_mail_add3'];
							$supno=$tmphslR['supno'];
							$supnm=$tmphslR['supnm'];
							$sum_mail1=$tmphslR['email1'];
							$sum_mail2=$tmphslR['email2'];
                       }
                     //  array_push($tmpEmailR, $mail_app2_add1,$mail_app2_add2,$mail_app2_add3,$comp_mail_app2_add);
                       array_push($tmpEmailR,$mail_app2_add1,$mail_app2_add2,$mail_app2_add3);
                       for($indexR=0;$indexR<count($tmpEmailR);$indexR++){
                           if(
                               !in_array($tmpEmailR[$indexR],$emailcusR) && !empty($tmpEmailR[$indexR]) ){
                               array_push($emailcusR,$tmpEmailR[$indexR]);
                           }
                       }
                       
                       awssendmailnondensoAttachFile($emailcusR, $orderno, $corno , $cusno , $cusnm , $bcc, $shpno,  $supnm, "all");

                       //echo "Approve2 >> ";
                       //print_r($emailcus2)."<BR/>";
                       break;
					
               }
           }
       }
      
    }
	
}
