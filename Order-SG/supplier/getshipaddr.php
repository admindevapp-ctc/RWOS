<?php 

session_start();
require_once('./../../core/ctc_init.php'); // add by CTC

$comp = ctc_get_session_comp(); 
$supno=$_SESSION['supno'];
$return_arr = array();
$cusno = $_POST["cusno"];
$vshipto = $_POST["shipto"];
//$vresult="";
//$vresult .= "shipto" . $vshipto . "<br/><br/>";


if($cusno != "")
{
    require('../db/conn.inc');
    $query = "
    select distinct ship_to_cd as shipto, adrs1 as Address
    from shiptoma
    where  Owner_Comp = '$comp'  and Cusno = '$cusno' ";

    if($shipto!="" || $shipto!="undefined"|| $shipto!="null" ){
        $query .= "and ship_to_cd = '".$vshipto."'";
    }

    $result = mysqli_query($msqlcon,$query);
    //echo $query;
    while($row = mysqli_fetch_array($result)){
        $shipto = $row['shipto'];
        $address = $row['Address'];

        $return_arr[] = array("shipto" => $shipto,
                        "Address" => $address);

    }
  //  $vresult .= $query;
}
// Encoding array in JSON format
echo json_encode($return_arr);
	//echo $vresult;
?>
