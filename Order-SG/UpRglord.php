<?	
	require('db/conn.inc');
	$conn = new PDO('odbc:djoblib', 'edp12', 'nuel2811');
	if(!$conn) echo "odbc problem";
	//total Row Count
	
		$sql = "SELECT * from orderhdr inner join orderdtl on orderhdr.orderno=orderdtl.orderno".
		  " where orderhdr.Trflg='' and orderdtl.Tranflg='' and ordtype='R' and orderhdr.ordflg='1' and orderdtl.ordflg!='R' order by partno, orderhdr.orderdate";
		  
	$rResult = mysqli_query($msqlcon, $sql ) or die(mysqli_error());
	$count = mysqli_num_rows($rResult);
	$i="0";
	$vordno='';
	while ( $aRow = mysqli_fetch_array( $rResult ) )
	{
			$cusnov=$aRow['cusno'];
			$ordno=$aRow['orderno'];
			$periode=$aRow['orderprd'];
			if($periode=="")$periode=0;
				
			$partno=$aRow['partno'];
			$corno=$aRow['Corno'];
			$qty=$aRow['qty'];
			$bprice=$aRow['bprice'];
			$disc=$aRow['disc'];
			$slsprice=$aRow['slsprice'];
			$orddate=$aRow['orderdate'];
			$duedate=$aRow['DueDate'];
			if($duedate=='')$duedate=0;
			$ordsts=$aRow['ordtype'];
			$dlvby=	$aRow['dlvby'];
			$shpno=	0;
			
			
			$sql1="insert into T12400R(CUSNO,SHPNO,CORNO,SORNO,PRDYM,ORDDT,DUEDT,ORDTY,PRTNO,DLVBY,ORQTY, BPRIC, DISCO, SLSPR ,ORDFL) values($cusnov,$shpno,'$corno','$ordno',$periode,$orddate,$duedate,'$ordsts','$partno','$dlvby',$qty,$bprice, $disc, $slsprice,'1')";
			//echo $sql1."<br>";
			$sql_result=$conn->prepare($sql1);
			$sql_result->execute();
			$query2="update orderdtl set tranflg='1' where orderno='".$ordno."' and partno='".$partno."'"; 
			//echo $query2."<br>";
			
			mysqli_query($msqlcon, $query2 );
			
			if($i==0){
				$query3="update orderhdr set Trflg='1' where orderno='".$ordno."'"; 
				mysqli_query($msqlcon, $query3 );
				$i="1";
				$vordno=$ordno;
			//	echo $query3."<br>";

			}
			if($vordno!=$ordno){
					$query3="update orderhdr set Trflg='1' where orderno='".$ordno."'"; 
					mysqli_query($msqlcon, $query3 );
					$vordno=$ordno;
			//		echo $query3."<br>";

			}
			
}
if($i=="1")	echo $count. " records has been transfered to AS400";
?>
