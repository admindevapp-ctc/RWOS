<?php


session_start();
require_once('./../../core/ctc_init.php'); // add by CTC


   function displayPaginationBelow($per_page,$page){
    
		$comp = ctc_get_session_comp(); // add by CTC
        $page_url="?";
        include "../../db/conn.inc";
        $supno=$_SESSION['supno'];
        // $query = "SELECT COUNT(*) as totalCount FROM supannounce WHERE Owner_Comp='$comp' and supno = '$supno'";
		$query = "SELECT COUNT(*) as totalCount FROM ( SELECT * from supannounce WHERE Owner_Comp = '$comp' AND supno = '$supno' GROUP BY supno, title, detail, `start`, `end` )as tbl ";

        $rec = mysqli_fetch_array(mysqli_query($msqlcon,$query));
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
        //return $query ;
    } 
?>
