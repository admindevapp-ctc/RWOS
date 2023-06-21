<?
function paging($qry, $per_page, $num,$mpage){
	require('../db/conn.inc');
	$sql = $qry;
	$result = mysqli_query($msqlcon,$sql);
	$count = mysqli_num_rows($result);
	$pages = ceil($count/$per_page);
	$page = $mpage;
	
		$xcusno=$_GET["vcusno"];
		$xprtno=$_GET["vprtno"];
		
	
	if($pages!=1 && $pages!=0){	
			$prev=$page-1;
			if($prev!=0){
				//echo '<a href="?page='.$prev.'">Previous</a>';
				echo '<a href="?page='.$prev.'&vcusno='.$xcusno.'&vprtno='.$xprtno.'&button=Search">Previous</a>';
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
							echo 	"<a href='?page=".$x. "&vcusno=".$xcusno."&vprtno=".$xprtno."&button=Search' class=\"current\" >".$x."</a>";
						}else{
							echo 	"<a href='?page=".$x. "&vcusno=".$xcusno."&vprtno=".$xprtno."&button=Search'>".$x."</a>";
	
						}
					}else{
			  			break;	
					}
				}  //for
	
	
	
				if($pages>$page){
					$next=$page+1;
					echo '<a href="?page='.$next.'&vcusno='.$xcusno.'&vprtno='.$xprtno.'&button=Search">Next</a>';
				}else{
					echo '<a href="#">Next</a>';
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
function pagingfld($qry, $per_page, $num,$mpage,$fieldname){
	
require('db/conn.inc');
	$sql = $qry;
	$result = mysqli_query($msqlcon,$sql);
	$count = mysqli_num_rows($result);
	$pages = ceil($count/$per_page);
	$page = $mpage;
	
	
	if($pages!=1 && $pages!=0){	
			$prev=$page-1;
			if($prev!=0){
				echo '<a href="?'.$fieldname.'='.$prev.'">Previous</a>';
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
							echo 	"<a href='?".$fieldname."=".$x. "' class=\"current\" >".$x."</a>";
						}else{
							echo 	"<a href='?".$fieldname."=".$x. "'>".$x."</a>";
	
						}
					}else{
			  			break;	
					}
				}  //for
	
	
	
				if($pages>$page){
					$next=$page+1;
					echo '<a href="?'.$fieldname.'='.$next.'">Next</a>';
				}else{
					echo '<a href="#">Next</a>';
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