<? session_start() ?>
<?
if(isset($_SESSION['cusno']))
{       
	if($_SESSION['redir']=='Order-SG'){
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
		if($type!='a')header("Location:../main.php");
	 }else{
		   echo "<script> document.location.href='../../".redir."'; </script>";
	 }
}else{	
header("Location:../../login.php");
}
?>


<?php
/********************************************

	For More Detail please Visit: 
	
	http://www.discussdesk.com/download-pagination-in-php-and-mysql-with-example.htm

	************************************************/
	
   function displayPaginationBelow($per_page,$page){
	   include('cata_connect.php');
	   $page_url="?";
    	$query = "SELECT COUNT(*) as totalCount FROM catalogue";
    	$rec = mysqli_fetch_array(mysqli_query($conn,$query));
    	$total = $rec['totalCount'];
        $adjacents = "2"; 

    	$page = ($page == 0 ? 1 : $page);  
    	$start = ($page - 1) * $per_page;								
		
    	$prev = $page - 1;							
    	$next = $page + 1;
        $setLastpage = ceil($total/$per_page);
    	$lpm1 = $setLastpage - 1;
    	
    	$setPaginate = "";
    	if($setLastpage > 1)
    	{	
    		$setPaginate .= "<class='setPaginate'>";
                    $setPaginate .= "<class='setPage'>Page $page of $setLastpage";
    		if ($setLastpage < 7 + ($adjacents * 2))
    		{	
    			for ($counter = 1; $counter <= $setLastpage; $counter++)
    			{
    				if ($counter == $page)
    					$setPaginate.= "<a class='current_page'>$counter</a>";
    				else
    					$setPaginate.= "<a href='{$page_url}page=$counter'>$counter</a>";					
    			}
    		}
    		elseif($setLastpage > 5 + ($adjacents * 2))
    		{
    			if($page < 1 + ($adjacents * 2))		
    			{
    				for ($counter = 1; $counter < 4 + ($adjacents * 2); $counter++)
    				{
    					if ($counter == $page)
    						$setPaginate.= "<a class='current_page'>$counter</a>";
    					else
    						$setPaginate.= "<a href='{$page_url}page=$counter'>$counter</a>";					
    				}
    				$setPaginate.= "...";
    				$setPaginate.= "<a href='{$page_url}page=$lpm1'>$lpm1</a>";
    				$setPaginate.= "<a href='{$page_url}page=$setLastpage'>$setLastpage</a>";		
    			}
    			elseif($setLastpage - ($adjacents * 2) > $page && $page > ($adjacents * 2))
    			{
    				$setPaginate.= "<a href='{$page_url}page=1'>1</a>";
    				$setPaginate.= "<a href='{$page_url}page=2'>2</a>";
    				$setPaginate.= "...";
    				for ($counter = $page - $adjacents; $counter <= $page + $adjacents; $counter++)
    				{
    					if ($counter == $page)
    						$setPaginate.= "<a class='current_page'>$counter</a>";
    					else
    						$setPaginate.= "<a href='{$page_url}page=$counter'>$counter</a>";					
    				}
    				$setPaginate.= "..";
    				$setPaginate.= "<a href='{$page_url}page=$lpm1'>$lpm1</a>";
    				$setPaginate.= "<a href='{$page_url}page=$setLastpage'>$setLastpage</a>";		
    			}
    			else
    			{
    				$setPaginate.= "<a href='{$page_url}page=1'>1</a>";
    				$setPaginate.= "<a href='{$page_url}page=2'>2</a>";
    				$setPaginate.= "..";
    				for ($counter = $setLastpage - (2 + ($adjacents * 2)); $counter <= $setLastpage; $counter++)
    				{
    					if ($counter == $page)
    						$setPaginate.= "<a class='current_page'>$counter</a>";
    					else
    						$setPaginate.= "<a href='{$page_url}page=$counter'>$counter</a>";					
    				}
    			}
    		}
    		
    		if ($page < $counter - 1){ 
    			$setPaginate.= "<a href='{$page_url}page=$next'>Next</a>";
                $setPaginate.= "<a href='{$page_url}page=$setLastpage'>Last</a>";
    		}else{
    			$setPaginate.= "<a class='current_page'>Next</a>";
                $setPaginate.= "<a class='current_page'>Last</a>";
            }

    		//$setPaginate.= "</ul>\n";		
    	}
    
    
        return $setPaginate;
    } 
?>