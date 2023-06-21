<?php 

session_start();
require_once('./../../core/ctc_init.php'); // add by CTC

$comp = ctc_get_session_comp(); 
$supno=$_SESSION['supno'];
$return_arr = array();
$cusno = $_POST["cusno"];
//echo $cusno;

if($cusno != "")
{
    require('../db/conn.inc');
    $query = "
    SELECT distinct(ship_to_cd) as shipto FROM shiptoma
    WHERE Owner_Comp = '$comp'  
    and Cusno = '$cusno'";

    $result = mysqli_query($msqlcon,$query);
    //echo $query;
    
    while($row = mysqli_fetch_array($result)){
        $cusno = $row['Cusno'];
        $shipto = $row['shipto'];

        $return_arr[] = array("cusno" => $cusno,
                        "shipto" => $shipto);

    }
    
}
// Encoding array in JSON format
echo json_encode($return_arr);
	
?>
