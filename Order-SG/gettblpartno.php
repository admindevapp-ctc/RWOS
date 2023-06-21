<? session_start() ?>
<?
if(isset($_SESSION['cusno']))
{       
		$_SESSION['cusno'];
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

if(trim($group)!=""){
$ex=explode(",", $group);
if(sizeof($ex)<1){
	$wh="";
}else{
	if(sizeof($ex)==1){
		$xwh="iGROUP='$ex[0]'";
	}else{
		for($i=0; $i<sizeof($ex); $i++){
				if($i==0){
					$xwh="iGROUP='".trim($ex[$i])."'";
				}else{
					$xwh=$xwh. " or iGROUP='".trim($ex[$i])."'";
				}
	
		}
	}
}
$xwh="(".$xwh.")";
}else{
$xwh="";
}



$page = $_REQUEST['page']; // get the requested page
$limit = $_REQUEST['rows']; // get how many rows we want to have into the grid
$sidx = $_REQUEST['sidx']; // get index row - i.e. user click to sort
$sord = $_REQUEST['sord']; // get the direction
//$page = 1; // get the requested page
//$limit = 15;
if(!$sidx) $sidx =1;

// search options
// IMPORTANT NOTE!!!!!!!!!!!!!!!!!!!!!!!!!!!!
// this type of constructing is not recommendet
// it is only for demonstration
//!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!

$wh = "";
$searchOn = Strip($_REQUEST['_search']);
if($searchOn=='true') {
	$fld = Strip($_REQUEST['searchField']);
	if( $fld =='ITNBR' || $fld=='ITDSC' || $fld=='ITCLS' || $fld=='ITTYP') {
		$fldata = Strip($_REQUEST['searchString']);
		$foper = Strip($_REQUEST['searchOper']);
		// costruct where
		//$wh .= " AND ".$fld;
		$wh .= $fld;
		switch ($foper) {
			case "bw":
				$fldata .= "%";
				$wh .= " LIKE '".$fldata."'";
				break;
			case "eq":
				if(is_numeric($fldata)) {
					$wh .= " = ".$fldata;
				} else {
					$wh .= " = '".$fldata."'";
				}
				break;
			case "ne":
				if(is_numeric($fldata)) {
					$wh .= " <> ".$fldata;
				} else {
					$wh .= " <> '".$fldata."'";
				}
				break;
			case "lt":
				if(is_numeric($fldata)) {
					$wh .= " < ".$fldata;
				} else {
					$wh .= " < '".$fldata."'";
				}
				break;
			case "le":
				if(is_numeric($fldata)) {
					$wh .= " <= ".$fldata;
				} else {
					$wh .= " <= '".$fldata."'";
				}
				break;
			case "gt":
				if(is_numeric($fldata)) {
					$wh .= " > ".$fldata;
				} else {
					$wh .= " > '".$fldata."'";
				}
				break;
			case "ge":
				if(is_numeric($fldata)) {
					$wh .= " >= ".$fldata;
				} else {
					$wh .= " >= '".$fldata."'";
				}
				break;
			case "ew":
				$wh .= " LIKE '%".$fldata."'";
				break;
			case "cn":
				$wh .= " LIKE '%".$fldata."%'";
				break;
			case "nc":
				$wh .= " not LIKE '%".$fldata."%'";
				break;
			default :
				$wh = "";
		}
	}
}
//echo $fld." : ".$wh;
// connect to the database







$totalrows = isset($_REQUEST['totalrows']) ? $_REQUEST['totalrows']: false;
if($totalrows) {$limit = $totalrows;}

require('db/conn.inc');
if($wh==""){
	$wh=$xwh;
}else{
	$wh=$wh . " and " . $xwh;
}
//echo $wh;
if($wh==""){
$result = mysqli_query($msqlcon,"SELECT COUNT(*) AS count FROM bm008pr");
}else{
	$result = mysqli_query($msqlcon,"SELECT COUNT(*) AS count FROM bm008pr where ".$wh);
}
$row = mysqli_fetch_array($result, MYSQL_ASSOC);
		$count = $row['count'];

		if( $count >0 ) {
			$total_pages = ceil($count/$limit);
		} else {
			$total_pages = 0;
		}
        if ($page > $total_pages) $page=$total_pages;
		$start = $limit*$page - $limit; // do not put $limit*($page - 1)
        if ($start<0) $start = 0;

if($wh==''){
$SQL = "SELECT * from bm008pr  ORDER BY  ".$sidx." ". $sord . " LIMIT ".$start." , ".$limit;
}else{
	$SQL = "SELECT * from bm008pr where ".$wh. " ORDER BY  ".$sidx." ". $sord . " LIMIT ".$start." , ".$limit;
}

$result = mysqli_query($msqlcon, $SQL ) or die("Couldn t execute query.".mysqli_error()); 
$responce->page = $page;
$responce->total = $total_pages;
$responce->records = $count;
$i=0;

while($row = mysqli_fetch_array($result,MYSQL_ASSOC)) {
	$responce->rows[$i]['id']=$row[ITNBR]; 
	$responce->rows[$i]['cell']=array($row[ITNBR],$row[ITDSC],$row[ITCLS],$row[ITTYP] );
	$i++; 
}
	echo json_encode($responce); 
	

function Strip($value)
{
	if(get_magic_quotes_gpc() != 0)
  	{
    	if(is_array($value))  
			if ( array_is_associative($value) )
			{
				foreach( $value as $k=>$v)
					$tmp_val[$k] = stripslashes($v);
				$value = $tmp_val; 
			}				
			else  
				for($j = 0; $j < sizeof($value); $j++)
        			$value[$j] = stripslashes($value[$j]);
		else
			$value = stripslashes($value);
	}
	return $value;
}
function array_is_associative ($array)
{
    if ( is_array($array) && ! empty($array) )
    {
        for ( $iterator = count($array) - 1; $iterator; $iterator-- )
        {
            if ( ! array_key_exists($iterator, $array) ) { return true; }
        }
        return ! array_key_exists(0, $array);
    }
    return false;
}

	
?>