<? session_start() ?>
<?
if(isset($_SESSION['cusno']))
{       
	 if($_SESSION['redir']!='denso-sg'){
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
	 }else{
		   echo "<script> document.location.href='../".redir."'; </script>";
	 }
}else{	
header("Location:../login.php");
}


require('db/conn.inc');
//echo $action;
$vaction=trim($_GET['action']);
$vordno=trim($_GET['ordno']);
$xcusno=trim($_GET['cusno']);
$vcorno=trim($_GET['corno']);
$vorddate=trim($_GET['orddate']);
$vshpno=trim($_GET['shpno']);
$vordtype=trim($_GET['ordtype']);
$vtransport=trim($_GET['transport']);



//echo " corno adalah :".$vcorno;
//echo "<script>alert($action);</script>";

switch ($vaction)
{

case 'new':
	$vcusno=$cusno;
	$vcusnm=$cusnm;
	checknew($vcusno,$vcusnm,$vcorno,$vordno,$vshpno,$vorddate, $vaction);
	break;

case 'newAdd':
	$vcusno=$cusno;
	$vcusnm=$cusnm;
    $xaction="new";
	checknewAdd($vcusno,$vcusnm,$vcorno,$vordno,$vordtype,$vorddate,$vshpno, $xaction);
	break;
}


		
function checknewAdd($vcusno,$vcusnm,$vcorno,$vordno,$vordtype,$vorddate,$vshpno,$action){
		include "crypt.php";
        $query="select * from orderhdr where Cust3='". $cusno. "' and Corno='".$vcorno."'";
        $sql=mysqli_query($msqlcon,$query);
   		$hasil = mysqli_fetch_array ($sql);
        if(!$hasil){
        echo "Addorder.php?".paramEncrypt("action=$action&ordno=$vordno&cusno=$vcusno&ordtype=$vordtype&orddate=$vorddate&corno=$vcorno&shpno=$vshpno");
        }else{
              echo "Error - PO has already found, Use edit or new PO!";
            }
}


function checknew($vcusno,$vcusnm,$vcorno,$vordno,$vshpno,$vorddate,$action){
		include "crypt.php";
        $query="select * from orderhdr where Cusno = '". $vshpno. "' and Corno='".$vcorno."'";
        $sql=mysqli_query($msqlcon,$query);
   		$hasil = mysqli_fetch_array ($sql);
        if(!$hasil){
            $query="select * from orderhdr where orderno='".$vordno."'";
        	$sql=mysqli_query($msqlcon,$query);
   			$hasil = mysqli_fetch_array ($sql);
        	if(!$hasil){
               		echo "order2.php?".paramEncrypt("action=$action&ordno=$vordno&cusno=$vcusno&orddate=$vorddate&corno=$vcorno&shpno=$vshpno");      
                    //echo $query;
                    }else{
  	
                      echo "Error - Denso Order No has found, Close your Internet browser!";
                      }
            }else{
              echo "Error - PO has already found, Use edit or new PO!";
            }
           
}


?>
