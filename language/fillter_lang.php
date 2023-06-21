<?php
require_once('conn.inc');

$mtoQry = "select * from language where usgflg = 1 order by ID ASC";
$mtoResult = mysqli_query($msqlcon,$mtoQry);

$param = "";
if(isset($_SESSION['lng'])){
    $param = $_SESSION['lng'];
}
// echo $param;
?>
<select name="lang" id="select_lang">
    <?php
    while ($mtoArray = mysqli_fetch_array($mtoResult)) {
        ?>
        <option class="se_lang" value="<?php echo $mtoArray["parameter"]; ?>" <?php echo $param == $mtoArray["parameter"]?"selected":""; ?>><?php echo $mtoArray["language"]; ?></option>
        <?php
    }
    ?>
        <!--    <option value="th">Thai/ไทย</option>
            <option value="eng">Englist/อังกฤษ</option>-->
</select>