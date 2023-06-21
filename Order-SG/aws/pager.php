<?
function paging($qry, $per_page, $num,$mpage){
	require('../db/conn.inc');
	$sql = $qry;
	$result = mysqli_query($msqlcon,$sql);
	$count = mysqli_num_rows($result);
	$pages = ceil($count/$per_page);
	$page = $mpage;
	$supno = $_GET['supno'] != null ? $_GET['supno'] : '';
	
	
	if($pages!=1 && $pages!=0){	
			$prev=$page-1;
			if($prev!=0){
				echo '<a href="?page='.$prev.'&supno='.$supno.'&'. paramEncrypt("ordertype=Request") .'">'.get_lng($_SESSION["lng"], "L0103")/*Previous*/.'</a>';
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
							echo 	"<a href='?ordertype=Request&page=".$x. "&supno=$supno' class=\"current\" >".$x."</a>";
						}else{
							echo 	"<a href='?ordertype=Request&page=".$x. "&supno=$supno'>".$x."</a>";
	
						}
					}else{
			  			break;	
					}
				}  //for
	
	
	
				if($pages>$page){
					$next=$page+1;
					echo '<a href="?page='.$next.'&supno='.$supno.'">'.get_lng($_SESSION["lng"], "L0104")/*Next*/.'</a>';
				}else{
					echo '<a href="#">'.get_lng($_SESSION["lng"], "L0104")/*Next*/.'</a>';
				}
			
				$mulai=(($page-1)*$per_page)+1;
		   		$akhir=$page*$per_page;
		   		if($akhir>$count)$akhir=$count;
		   		echo "  | <span class=\"arial11black\">  View   : </span>";
		   		echo "  <span class=\"arial11blue\">"."  ".$mulai. " - ". $akhir."  "."</span>";
		   		echo "  <span class=\"arial11black\">". "  Of  " . "</span>";
		   		echo "  <span class=\"arial11blue\">". $count."</span>";   
			
			
			}
			
			
	
}
?>