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
		$owner_comp = ctc_get_session_comp(); // add by CTC
	 }else{
		echo "<script> document.location.href='../".redir."'; </script>";
	 }
}else{	
	header("Location:../login.php");
}

	$per_page=10;
	$num=5;
	/* Database connection information */
	require('db/conn.inc');
	$datefrom=trim($_GET['datefrom']);
	$dateto=trim($_GET['dateto']);	
	$search=trim($_GET['search']);
	$choose=trim($_GET['choose']);
	$desc=trim($_GET['description']);
	//total Row Count
	$sql = "select count(*) as count 
	from 
	( 
	SELECT  awsorderhdr.Owner_Comp,awsorderdtl.partno, awsorderhdr.cusno, awsorderhdr.orderno, awsorderhdr.Corno, awsorderhdr.orderdate, awsorderhdr.Dealer, itdsc, ordtype, qty,slsprice ,awsorderdtl.ordflg  as ordflg,awsorderhdr.shipto 
	FROM awsorderhdr 
		LEFT join awsorderdtl on awsorderhdr.orderno=awsorderdtl.orderno and awsorderhdr.Owner_Comp=awsorderdtl.Owner_Comp and awsorderhdr.Corno = awsorderdtl.Corno 
		LEFT join cusmas on awsorderhdr.cusno=cusmas.Cusno AND awsorderhdr.Owner_Comp=cusmas.Owner_Comp 
		LEFT JOIN awscusmas ON awscusmas.Owner_Comp = awsorderhdr.Owner_Comp AND awscusmas.cusno1 = awsorderhdr.Dealer AND awscusmas.ship_to_cd2 = awsorderhdr.shipto
		LEFT JOIN shiptoma ON shiptoma.Cusno = awscusmas.cusno1 AND shiptoma.ship_to_cd = awscusmas.ship_to_cd1
		LEFT JOIN rejectorder ON rejectorder.Owner_Comp = awsorderhdr.Owner_Comp and rejectorder.orderno = awsorderhdr.orderno and rejectorder.partno = awsorderdtl.partno
		where awsorderhdr.Dealer='$cusno' and awsorderhdr.cusno<>awsorderhdr.Dealer and awsorderhdr.orderdate>='$datefrom' and awsorderhdr.orderdate<='$dateto'  and awsorderhdr.Owner_Comp='$owner_comp'
	UNION
		SELECT supawsorderhdr.Owner_Comp, supawsorderdtl.partno, supawsorderhdr.cusno, supawsorderhdr.orderno, supawsorderhdr.Corno, supawsorderhdr.orderdate, supawsorderhdr.Dealer, itdsc, ordtype, qty,slsprice ,supawsorderdtl.ordflg COLLATE utf8_general_ci as ordflg,supawsorderhdr.shipto
	FROM supawsorderhdr 
		LEFT join supawsorderdtl on supawsorderhdr.orderno=supawsorderdtl.orderno and supawsorderhdr.Owner_Comp=supawsorderdtl.Owner_Comp and supawsorderhdr.Corno = supawsorderdtl.Corno 
		LEFT join cusmas on supawsorderhdr.cusno=cusmas.Cusno AND supawsorderhdr.Owner_Comp=cusmas.Owner_Comp 
		LEFT JOIN awscusmas ON awscusmas.Owner_Comp = supawsorderhdr.Owner_Comp AND awscusmas.cusno1 = supawsorderhdr.Dealer AND awscusmas.ship_to_cd2 = supawsorderhdr.shipto
		LEFT JOIN shiptoma ON shiptoma.Cusno = awscusmas.cusno1 AND shiptoma.ship_to_cd = awscusmas.ship_to_cd1
		LEFT JOIN rejectorder ON rejectorder.Owner_Comp = supawsorderhdr.Owner_Comp and rejectorder.orderno = supawsorderhdr.orderno and rejectorder.partno = supawsorderdtl.partno
		where supawsorderhdr.Dealer='$cusno'and supawsorderhdr.cusno<>supawsorderhdr.Dealer and supawsorderhdr.orderdate>='$datefrom' and supawsorderhdr.orderdate<='$dateto'  and supawsorderhdr.Owner_Comp='$owner_comp'
		) a where Owner_Comp='$owner_comp'";
	/*
	$sql = "SELECT  count(*) as count 
	FROM awsorderhdr
	inner join awsorderdtl on awsorderhdr.orderno=awsorderdtl.orderno and awsorderhdr.Owner_Comp=awsorderdtl.Owner_Comp and awsorderhdr.Corno = awsorderdtl.Corno
	inner join cusmas on awsorderhdr.cusno=cusmas.Cusno AND awsorderhdr.Owner_Comp=cusmas.Owner_Comp  ".
	" where awsorderhdr.Dealer='$cusno' and awsorderhdr.cusno<>awsorderhdr.Dealer  
	and awsorderhdr.orderdate>='$datefrom' and awsorderhdr.orderdate<='$dateto' 
	and awsorderhdr.Owner_Comp='$owner_comp'";
	*/
	 if($search!=''){
		//echo $search;
		switch($search){
			case "partno":
				$fld="partno";
				break;
			case "itdsc":
				$fld="itdsc";
				break;	
			case "cusno":
				$fld="cusno";
				break;
			case "corno":
				$fld="Corno";
				break;
			case "status":
				$fld="ordflg";
				break;
			case "shipfrom":
				$fld="ordflg";
				break;
		}
		switch($choose){
			case "eq":
				$op="=";
				$vdesc="'$desc'";
				break;
			case "like";
				$op="like";
				$vdesc="'%$desc%'";
				break;
			case "in";
				$op="in";
				if($desc=="R"){
					$op="=";
					$vdesc="'$desc'";
				}
				else if($desc=="1,2"){
					$op="!=";
					$vdesc="'R'";
				}
				else{
					$op="=";
					$vdesc="''";
				}
				break;
		}
		$sql = $sql . " and $fld  $op $vdesc";		
	 }		  
	$sql = $sql . " order by cusno, partno, orderdate";
	
	//echo $sql;
	$result = mysqli_query($msqlcon,$sql);
	//$count = mysqli_num_rows($result);
	$aRow = mysqli_fetch_array( $result );
	$count = $aRow['count'];
	//echo "<br> jumlah :".$count;
	$pages = ceil($count/$per_page);
	$page = $_GET['page'];
	if($page){ 
		$start = ($page - 1) * $per_page; 			
	}else{
		$start = 0;	
		$page=1;
	}
   
	if($count>$per_page){
		//echo "<div id=\"search\"><a href=\"#\"> <img src=\"images/view.png\" width=\"16\" height=\"16\" border=\"0\"></a></div>";
	}

	if($pages!=1 && $pages!=0){	
		$prev=$page-1;
		if($prev!=0){
			echo '<a href="#" onclick="paging('.$prev.')">Previous</a>';
		}else{
			echo '<a href="#">Previous</a>';
		}
	
	    if($page>=$num){
			if($pages<$page+2){
				$tgh=$pages-4;
			}else{
				$tgh=$page-2;
			}
		}else{
			$tgh=1;
		}
		
		$y=0;
		for($x=$tgh; $x<=$pages; $x++)
		{
			$y++;
			if($y<=5){
				if($page==$x){
				//	echo '<li id="'.$x.'" class="current">'.$x.'</li>';
					echo 	'<a href="#"  class="current" onclick="paging('.$x.')">'.$x.'</a>';
					}else{
					echo 	'<a href="#"  onclick="paging('.$x.')">'.$x.'</a>';
	
				}
			}else{
			  break;	
			}
		}
	
	
	
		if($pages>$page){
			$next=$page+1;
			echo '<a href="#" onclick="paging('.$next.')">Next</a>';
		}else{
			echo '<a href="#">Next</a>';
		}
	}
?>
