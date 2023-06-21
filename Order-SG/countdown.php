<p id="demo"></p>
<?php 

  require_once('./../core/ctc_init.php'); // add by CTC
  require('db/conn.inc');
  require_once('../language/Lang_Lib.php');
  
  $comp = ctc_get_session_comp(); // add by CTC

	
	$setdayQry="select * from duedate where ordtype='U' and Owner_Comp='$comp' ";
	$setdaysql=mysqli_query($msqlcon,$setdayQry);
	$time="";
	if($result = mysqli_fetch_array ($setdaysql)){
		$time=$result['setday'];
	}

?>
 <input type="hidden" id="setday" name="setday" value="<?php echo $time; ?>">
<script>
// Set the date we're counting down to
// var today = new Date();
// var toYear=today.getFullYear();
var toYear=<?php echo date("Y");?>;
// var toMonth=today.getMonth();
var toMonth=<?php echo date("m")-1;?>;
// var toDate=today.getDate();
var toDate=<?php echo date("d");?>;
var toGMP='<?php echo date("O");?>';
var toH='<?php echo date("H");?>';
console.log('toYear' , toYear);
console.log('toMonth' , toMonth);
console.log('toDate' , toDate);
console.log('toGMP' , toGMP);
console.log('toH' , toH);

var setday=$("#setday").val();
var monthArray=['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
var toMthStr=monthArray[toMonth];
var todayStr=toMthStr+" "+toDate+" "+toYear+" "+setday+" GMT"+toGMP;
var countDownDate = new Date(todayStr).getTime();
var nowSystem = <?php echo date('U'); ?>;
var nowSys = nowSystem *1000;
// Update the count down every 1 second

// CTC Sippavit 07/10/2020 show message when setday is set
if (setday) {
    var x = setInterval(function() {
        // Get todays date and time
        var now = new Date().getTime();

        console.log("countdown [now]: "+now);
        console.log("countdown [nowSys]: "+nowSys);
        nowSys = nowSys +1000;
        // var nx = new Date(now);
        // var ng = nx.getHours();
        // console.log(ng);
        // Find the distance between now an the count down date
        //var distance = countDownDate - now;
         var distance = countDownDate - nowSys;
        // console.log(countDownDate,nowSys,distance);
        var hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
        var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
        var seconds = Math.floor((distance % (1000 * 60)) / 1000);

        // Display the result in the element with id="demo"
        //document.getElementById("demo").innerHTML = "Time to place order for today delivery is left : <b><font color=red>"+hours + " H " + minutes + " M " + seconds + " S </font></b>";
              document.getElementById("demo").innerHTML = "<?php echo get_lng($_SESSION["lng"], "L0307");?><b><font color=red>"+hours + " H " + minutes + " M " + seconds + " S </font></b>";
        // If the count down is finished, write some text 
        if (distance < 0) {
          clearInterval(x);
              //document.getElementById("demo").innerHTML = "Time to place order for today delivery is <b><font color=red>over</font></b>.";
              document.getElementById("demo").innerHTML = "<?php echo get_lng($_SESSION["lng"], "L0306");?> <b><font color=red>over</font></b>.";

        }
    }, 1000);
}
</script>