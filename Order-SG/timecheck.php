<?php require_once('./../core/ctc_init.php'); // add by CTC ?>
<!--start-- 03/10/2019 Prachaya inphum CTC -->

<!--CTC-Get time disable function from database 17/09/2020 -->
<?php

	$setdayQry="select * from duedate where ordtype='U' and Owner_Comp='$comp' ";
	$setdaysql=mysqli_query($msqlcon,$setdayQry);
	$time="";
	$endtime="";
	$hourStr = "";
	$minStr = "";
	$hourEnd = "";
	$minEnd = "";
	if($result = mysqli_fetch_array ($setdaysql)){
		$time=$result['setday'];
		$endtime=$result['endday'];
	}else{
		$time = "00:00";
		$endtime = "00:00";
	}

	$times = explode(":", $time);
	$hourStr = $times[0];
	$minStr = $times[1];	

	$endtimes = explode(":", $endtime);
	$hourEnd = $endtimes[0];
	$minEnd = $endtimes[1];	

?>

<script type="text/javascript">
var time = '<?php echo date('U');?>';
//var diffDMT = '<?php echo substr(date('O'),0,3);?>';
//console.log(time);
var diffDMT = '<?php echo substr(date('O'),0,3) + (substr(date('O'),3,2) / 60);?>';
var orderPage = '<?php echo trim($_GET['current'])?>';      
var importPage ='<?php echo $_GET['selection']?>';          
var warnningPage ='<?php echo $_POST['ordertype']?>'; 
var popupCheckTime = false; //For checking this page is already popup

//var hourDis = 12;
//var minDis = 03;
//var hourEna = 23; // 25/10/2019 Prachaya inphum CTC
//var minEna = 59;  // 25/10/2019 Prachaya inphum CTC

//CTC-Get time disable function from database 17/09/2020

var hourDis = <?php echo $hourStr?>;
var minDis = <?php echo $minStr?>;
var hourEna = <?php echo $hourEnd?>;
var minEna = <?php echo $minEnd?>;

function calcTime(timestamp,offset) {

	// create Date object for current location
	d = new Date(timestamp * 1000);
	// console.log(d);

	// convert to msec
	// add local time zone offset
	// get UTC time in msec
	utc = d.getTime() + (d.getTimezoneOffset() * 60000);

	// create new Date object for different city
	// using supplied offset
	nd = new Date(utc + (3600000*offset));

	// return time as a string
	return nd;

}


function updateTime() {
    const timeObj = new Date(time * 1000);
	var local = calcTime(time,diffDMT);

   // var hr = timeObj.getHours();
    var hr = local.getHours();
    // var hr = '<?php echo date("H");?>';
    // var mi = timeObj.getMinutes(); 
    var mi = local.getMinutes(); 
    // var mi = '<?php echo date("i");?>';
    // var se = timeObj.getSeconds(); //for test
    var se = local.getSeconds(); //for test
    console.log(hr,':',mi,':',se);
    
    if(popupCheckTime == false && (orderPage =='urgentOrder'||importPage == 'urgentOrder' || warnningPage == 'Urgent')){ //checking page 
		// Modify 25/10/2019 Prachaya inphum CTC --Start--
		if (hourDis < hourEna) {
			if(parseFloat(hr) == parseFloat(hourDis)){ //check hour
				if(parseFloat(mi) >= parseFloat(minDis)){ //check minute mitreginDis
					//console.log('dis < ena 1')
					popupCheckTime = true
					$("#dialog-timeout").dialog("open");
					
				}  
				//console.log('dis < ena 2')
			}else if(parseFloat(hr) > parseFloat(hourDis) && parseFloat(hr) < parseFloat(hourEna)){
				popupCheckTime = true
				$("#dialog-timeout").dialog("open");
				console.log('dis < ena 3')
			}else if(parseFloat(hr) == parseFloat(hourEna)) {
				if(parseFloat(mi) < parseFloat(minEna)){ //check minute
					popupCheckTime = true
					$("#dialog-timeout").dialog("open");
					//console.log('dis < ena 4')
				}
				//console.log('dis < ena 5')
			}
		} else if (hourDis > hourEna) {
			// after disable
			if(parseFloat(hr) == parseFloat(hourDis)){ //check hour
				if(parseFloat(mi) >= parseFloat(minDis)){ //check minute
					//console.log('after dis, 1')
					popupCheckTime = true
					$("#dialog-timeout").dialog("open");
					
				}  
				//console.log('after dis, 2')
			}else if(parseFloat(hr) > parseFloat(hourDis)){
				popupCheckTime = true
				$("#dialog-timeout").dialog("open");	
				//console.log('after dis, 3')
			}
			
			// before enable
			if(parseFloat(hr) == parseFloat(hourEna)) { //check hour
				if(parseFloat(mi) < parseFloat(minEna)){ //check minute
					popupCheckTime = true
					$("#dialog-timeout").dialog("open");
					//console.log('after dis, 4')
				}  
				console.log('after dis, 5')
			} else if(parseFloat(hr) < parseFloat(hourEna)) { //check minute
				popupCheckTime = true
				$("#dialog-timeout").dialog("open");
				//console.log('after dis, 6')
			}
		} else {
			if (minDis < minEna) {
				if(parseFloat(hr) == parseFloat(hourDis)){ //check hour
					if(parseFloat(mi) >= parseFloat(minDis) && parseFloat(mi) < parseFloat(minEna)){ //check minute
						popupCheckTime = true
						$("#dialog-timeout").dialog("open");
						//console.log('same, 1')
					}  
					//console.log('same, 2')
				}
			} else if (minDis > minEna) {
				 if(parseFloat(hr) == parseFloat(hourDis)){ //check hour
					if(parseFloat(mi) >= parseFloat(minDis) || parseFloat(mi) < parseFloat(minEna)){ //check minute
						popupCheckTime = true
						$("#dialog-timeout").dialog("open");
						//console.log('same, 3')
					}  
					//console.log('same, 4')
				} 
			} else {
				// No expectation for using this case.
			}
		}
		// Modify 25/10/2019 Prachaya inphum CTC --End--
    }

    time++;
}

$(function(){
    setInterval(updateTime,1000) 
});
</script>
<div id="dialog-timeout" title="<?php echo $_SESSION["lng"]=='th'?"หมดเวลาในการสั่งซื้อแบบเร่งด่วน":"Urgen Order Time Out" ;?>"style="display: none;">
    <br>
    <p>
	<?php 
	$messageBox = "";
	$dateTime=date_create_from_format("H:i",$time);
	$endDateTime=date_create_from_format("H:i",$endtime);
	
	if($_SESSION["lng"]=='en'){
		$messageBox = get_lng($_SESSION["lng"], "W0028");
		$messageBox = str_replace("@strDis",date_format($dateTime,"h:i a"),$messageBox);
		$messageBox = str_replace("@endDis",date_format($endDateTime,"h:i a"),$messageBox);
		//$messageBox = str_replace($messageBox,"@strDis",date_format($time,"hh:ii A"));
	}else{
		
		//echo date_format($date,"HH:ii");
		$messageBox = get_lng($_SESSION["lng"], "W0028");
		$messageBox = str_replace("@strDis",date_format($dateTime,"H:i")." น.",$messageBox);
		$messageBox = str_replace("@endDis",date_format($endDateTime,"H:i")." น.",$messageBox);
		//$messageBox = str_replace($messageBox,"@strDis",date_format($time,"HH:ii"));
	}
	//echo get_lng($_SESSION["lng"], "W0028")
	echo $messageBox;
	?>
	</p>

</div>
<!-- 03/10/2019 Prachaya inphum CTC --end-->