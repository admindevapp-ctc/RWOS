<? session_start() ;
?>
<?
if(isset($_SESSION['cusno']))
{       
	$_SESSION['cusnm'];
		$_SESSION['password'];
		$_SESSION['alias'];
		$_SESSION['tablename'];
		$_SESSION['user'];
		$_SESSION['dealer'];
		$_SESSION['group'];
		$_SESSION['type'];
		$_SESSION['custype'];
		

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
}else{	
header("Location: login.php");
}


	$per_page=10;
	$num=5;
	/* Database connection information */
	require('db/conn.inc');
	$periode=trim($_GET['periode']);
	$search=trim($_GET['search']);
	$choose=trim($_GET['choose']);
	$desc=trim($_GET['description']);
	//total Row Count
	$sql = "SELECT count(*) as count from orderhdr inner join orderdtl on orderhdr.orderno=orderdtl.orderno ".
 " where (orderprd=".$periode. " or SUBSTR(DueDate,1,6)=".$periode. ")";
	 if($search!=''){
		//echo $search;
		switch($search){
			case "partno":
				$fld="orderdtl.partno";
				break;
	    	case "cusno":
				$fld="orderhdr.cusno";
				break; 
			case "corno":
				$fld="orderhdr.Corno";
				break;
		}
		switch($choose){
			case "eq":
				$op="=";
				$vdesc=$desc;
				break;
			case "like";
				$op="like";
				$vdesc="%$desc%";
				break;
		}
		$sql = $sql . " and $fld $op '$vdesc'";	
	 }		  
	$sql = $sql . " order by partno, orderhdr.orderdate";
	
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
