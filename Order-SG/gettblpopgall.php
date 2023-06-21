<?php 

session_start();
require_once('./../core/ctc_init.php'); // add by CTC
require_once('../language/Lang_Lib.php');

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
	$qrycusmas="select cusno from cusmas where cust3= '$cusno' and Owner_Comp='$owner_comp' ";
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
	
	
	
	$sql=  " SELECT count(*) as count  from ( select count(*) 
	FROM orderdtl  INNER JOIN bm008pr ON orderdtl.partno = bm008pr.itnbr AND orderdtl.Owner_Comp = bm008pr.Owner_Comp ".
	//" where orderdtl.cust3='$cusno' and orderdtl.orderdate>='$datefrom' and orderdtl.orderdate<='$dateto' GROUP BY Corno, cusno, orderdate, orderno, Subprod  ORDER BY Corno, cusno, orderdate, orderno, Subprod ) as test"	;
	" where orderdtl.cusno in $comp and orderdtl.orderdate>='$datefrom' and orderdtl.orderdate<='$dateto' AND orderdtl.Owner_Comp='$owner_comp' GROUP BY Corno, cusno, orderdate, orderno, Subprod  ORDER BY Corno, cusno, orderdate, orderno, Subprod ) as test"	;

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
			echo '<a href="#" onclick="paging('.$prev.')">'.get_lng($_SESSION["lng"], "L0103")/*Previous*/.'</a>';
		}else{
			echo '<a href="#">'.get_lng($_SESSION["lng"], "L0103")/*Previous*/.'</a>';
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
			echo '<a href="#" onclick="paging('.$next.')">'.get_lng($_SESSION["lng"], "L0104")/*Next*/.'</a>';
		}else{
			echo '<a href="#">'.get_lng($_SESSION["lng"], "L0104")/*Next*/.'</a>';
		}
	}
?>
