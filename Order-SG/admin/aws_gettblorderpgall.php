<?php 

session_start();
require_once('./../../core/ctc_init.php'); // add by CTC

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
		$comp = ctc_get_session_comp(); // add by CTC
	 }else{
		   echo "<script> document.location.href='../".redir."'; </script>";
	 }
}else{	
header("Location:../login.php");
}


	$per_page=10;
	$num=5;
	/* Database connection information */
	require('../db/conn.inc');
	$datefrom=trim($_GET['datefrom']);
	$dateto=trim($_GET['dateto']);
	$search=trim($_GET['search']);
	$choose=trim($_GET['choose']);
	$desc=trim($_GET['description']);
	//total Row Count
	$sql = "SELECT count(*) as count from awsorderhdr 
                inner join awsorderdtl on awsorderhdr.orderno=awsorderdtl.orderno and awsorderhdr.Owner_Comp=awsorderdtl.Owner_Comp".
		" left join vt_cst_ordr_prog on awsorderdtl.cusno=vt_cst_ordr_prog.CST_CD and awsorderdtl.Corno=vt_cst_ordr_prog.CST_PO_NO  and awsorderdtl.partno =vt_cst_ordr_prog.CST_ORDR_INPT_ITEM_NO and awsorderdtl.owner_comp=vt_cst_ordr_prog.owner_comp".	
		" INNER JOIN awscusmas on awscusmas.cusno2 = awsorderhdr.CUST3 AND awsorderhdr.shipto = awscusmas.ship_to_cd2 AND awsorderhdr.Owner_Comp = awscusmas.Owner_Comp".
		" where awsorderhdr.orderdate>='$datefrom' and awsorderhdr.orderdate<='$dateto' and awsorderhdr.Owner_Comp='$comp' " ;


  
	 if($search!=''){
		//echo $search;
		switch($search){
			case "partno":
				$fld="awsorderdtl.partno";
				break;
            case "corno":
                $fld="awsorderhdr.Corno";
                break;
			case "cusno1":
				$fld="awsorderhdr.Dealer";
				break;
			case "cusno2":
				$fld="awsorderhdr.cusno";
				break;
            case "status":
                $fld="awsorderdtl.ordflg";
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
					$vdesc="($desc)";
				}
				else{
					$op="=";
					$vdesc="''";
				}
				break;
		}
		$sql = $sql . " and $fld $op $vdesc";	
	 }		  
	$sql = $sql . " order by partno, awsorderhdr.orderdate";
	
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
