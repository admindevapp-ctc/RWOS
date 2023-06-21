<?
function pagingx($qry, $per_page, $num,$mpage){
    require('../db/conn.inc');
    $sql = $qry;
    $result = mysqli_query($msqlcon,$sql);
    $count = mysqli_num_rows($result);
    $pages = ceil($count/$per_page);
    $page = $mpage;
    
    
    
    $xcusno=$_GET["vcusno"];
    $xprtno=$_GET["vprtno"];
    $xsupno=$_GET["vsupno"];
    if(isset($_GET["button"])){
        $xcusno1=$_GET["s_cusno1"];
        $xcusgrp2=$_GET["s_cusgrp2"];
        $xpartnumber=$_GET["s_partnumber"];
        $xproduct=$_GET["s_product"];
        $xpartname=$_GET["s_partname"];
        $xcondition=$_GET["s_condition"];
		$xbrand=$_GET["s_brand"];
		$xsupcode=$_GET["s_supcode"];

    }
    
    if($pages!=1 && $pages!=0){ 
            $prev=$page-1;
            if($prev!=0){
                if(isset($_GET["button"])){
                    echo '<a href="?page='.$prev.'&vcusno='.$xcusno.'&vprtno='.$xprtno.'&vsupno='.$xsupno.'&s_cusno1='.$xcusno1
                    .'&s_cusgrp2='.$xcusgrp2.'&s_partnumber='.$xpartnumber.'&s_product='.$xproduct.'&s_partname='.$xpartname
                    .'&s_condition='.$xcondition.'&s_brand='.$xbrand.'&s_supcode='.$xsupcode.'&button=Search'.
                    '">Previous</a>';
                }
                else{
                    echo '<a href="?page='.$prev.'&vcusno='.$xcusno.'&vprtno='.$xprtno.'&vsupno='.$xsupno.'&button=Search">Previous</a>';
                }
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
                            if(isset($_GET["button"])){
                                echo "<a href='?page=".$x. "&vcusno=".$xcusno."&vprtno=".$xprtno."&vsupno=".$xsupno."&s_cusno1=".$xcusno1
                                ."&s_cusgrp2=".$xcusgrp2."&s_partnumber=".$xpartnumber."&s_product=".$xproduct."&s_partname=".$xpartname
                                ."&s_condition=".$xcondition ."&s_brand=".$xbrand ."&vsupno=".$xsupno."&button=Search'".
                                "' class=\"current\" >".$x."</a>";
                            }
                            else{
                                echo    "<a href='?page=".$x. "&vcusno=".$xcusno."&vprtno=".$xprtno."&vsupno=".$xsupno."&button=Search' class=\"current\" >".$x."</a>";
                            }
                        }else{
                            if(isset($_GET["button"])){
                                echo "<a href='?page=".$x. "&vcusno=".$xcusno."&vprtno=".$xprtno."&vsupno=".$xsupno."&s_cusno1=".$xcusno1
                                ."&s_cusgrp2=".$xcusgrp2."&s_partnumber=".$xpartnumber."&s_product=".$xproduct."&s_partname=".$xpartname
                                ."&s_condition=".$xcondition ."&s_brand=".$xbrand ."&vsupno=".$xsupno."&button=Search'".
                                "' >".$x."</a>";
                            }
                            else{
                                echo    "<a href='?page=".$x. "&vcusno=".$xcusno."&vprtno=".$xprtno."&vsupno=".$xsupno."&button=Search'>".$x."</a>";
                            }
                        }
                    }else{
                        break;  
                    }
                }  //for
    
    
    
                if($pages>$page){
                    $next=$page+1;
                    
                    if(isset($_GET["button"])){
                        echo "<a href='?page=".$next. "&vcusno=".$xcusno."&vprtno=".$xprtno."&vsupno=".$xsupno."&s_cusno1=".$xcusno1
                        ."&s_cusgrp2=".$xcusgrp2."&s_partnumber=".$xpartnumber."&s_product=".$xproduct."&s_partname=".$xpartname."&s_condition=".$xcondition."&button=Search'".
                        "'>Next</a>";
                    }
                    else{
                        echo '<a href="?page='.$next.'&vcusno='.$xcusno.'&vprtno='.$xprtno.'&supno='.$xsupno.'&button=Search">Next</a>';
                    }
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
                            echo    "<a href='?".$fieldname."=".$x. "' class=\"current\" >".$x."</a>";
                        }else{
                            echo    "<a href='?".$fieldname."=".$x. "'>".$x."</a>";
    
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