<?php
include "db/config.php";
?>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <title>Ordering-DIAS</title>
        <link href="css/all.css" rel="stylesheet" type="text/css" />
    </head>
    <body>
        <form method="POST" enctype="multipart/form-data" name="uploadForm" action="db/upload-price.php">
            <h3>Master Price</h3>
            <table>
                <tr>
                    <td>Upload</td>
                    <td>:</td>
                    <td><input type="file" size="45" name="userfile"></td>
                </tr>
                <tr>
                    <td>File type</td>
                    <td>:</td>
                    <td>
                        <input type="radio" name="group2" value="csv"> .csv
                        <input type="radio" name="group2" value="excel"> .xls
                    </td>
                </tr>
                <tr>
                    <td>First row for header</td>
                    <td>:</td>
                    <td>
                        <input type="radio" name="group3" value="yesrow"> Yes
                        <input type="radio" name="group3" value="norow"> No
                    </td>
                </tr>
                <tr>
                    <td></td>
                    <td></td>
                    <td>
                        <input type="radio" name="group1" value="add"> Add
                        <input type="radio" name="group1" value="edit"> Replace Partial
                        <input type="radio" name="group1" value="editall"> Replace All
                    </td>
                </tr>
                <tr>
                    <td></td>
                    <td></td>
                    <td>
                        <input name="submit" type="submit" value="Submit"> 
                        <input type="reset" value="Reset">
                    </td>
                </tr>
            </table><br/>
        </form>
        <div>
            <?
               include "../.../db/conn.inc";
			   $msg=$_GET['msg'];
                
                // If error upload
                if($msg=='Error'){
                    $msgtbl="<table border=1 class=idtable>
                        <tr>
                            <th align=center>CUSTOMER NUMBER</th>
                            <th align=center>ITEM NUMBER</th>
                            <th align=center>PRICE</th>
                            <th align=center>CURRENCY CODE</th>
                            <th align=center>CUST2</th>
                            <th align=center>CUST3</th>
                            <th align=center>Error Message</th>
                        </tr>";
                    $qse="SELECT * FROM sellpricetmp WHERE StatusItem='E'";
                    $sqlqse=mysqli_query($msqlcon,$qse);
                    while($arx=mysqli_fetch_array($sqlqse)){
                        $ecusno=$arx['Cusno'];
                        $eitnbr=$arx['Itnbr'];
                        $eprice=$arx['Price'];
                        $ecurcd=$arx['CurCD'];
                        $ecust2=$arx['CUST2'];
                        $ecust3=$arx['CUST3'];
                        $eketerangan=$arx['Keterangan'];
                        $msgtbl.="<tr>
                                    <td>$ecusno</td>
                                    <td>$eitnbr</td>
                                    <td>$eprice</td>
                                    <td>$ecurcd</td>
                                    <td>$ecust2</td>
                                    <td>$ecust3</td>
                                    <td>$eketerangan</td>
                                </tr>";
                    }
                    $msgtbl.="</table>";
                    $qd="DELETE FROM sellpricetmp";
                    mysqli_query($msqlcon,$qd);
                    $msg=$msgtbl;
                }
                
                // If succesfully add data
                if($msg=='Add'){
                    
                    $msgsuccess='Add data success';
                    $msgsuccess.="<table border=1 class=idtable>
                                    <tr>
                                        <th align=center>CUSTOMER NUMBER</th>
                                        <th align=center>ITEM NUMBER</th>
                                        <th align=center>PRICE</th>
                                        <th align=center>CURRENCY CODE</th>
                                        <th align=center>CUST2</th>
                                        <th align=center>CUST3</th>
                                    </tr>";
                    $qa="SELECT Cusno, Itnbr, Price, CurCD, CUST2, CUST3 FROM sellpricetmp WHERE StatusItem !='H'";
                    $sqlqa=mysqli_query($msqlcon,$qa);
                    while($arrqa=mysqli_fetch_array($sqlqa)){
                        $search=array("'","Ã");
                        $replace=array("\\'","A");
                
                        $acusno=$arrqa['Cusno'];
                        $aitnbr=$arrqa['Itnbr'];
                        $aprice=$arrqa['Price'];
                        $acurcd=$arrqa['CurCD'];
                        $acust2=$arrqa['CUST2'];
                        $acust3=$arrqa['CUST3'];
                            
                        $qi2="INSERT INTO sellprice(Cusno, Itnbr, Price, CurCD, CUST2, CUST3
                                VALUES('$acusno','$aitnbr','$aprice','$acurcd','$acust2','$acust3')";
                        mysqli_query($msqlcon,$qi2);
                        $msgsuccess.="<tr>
                                        <td>$acusno</td>
                                        <td>$aitnbr</td>
                                        <td>$aprice</td>
                                        <td>$acurcd</td>
                                        <td>$acust2</td>
                                        <td>$acust3</td>
                                    </tr>";
                    }
                    $msgsuccess.="</table>";
                    $qd="DELETE FROM sellpricetmp";
                    mysqli_query($msqlcon,$qd);
                    $msg=$msgsuccess;
                }
                
                // If succesfully replace partial data
                if($msg=='Replace'){
                    
                    $msgsuccess='Replace data partial success';
                    $msgsuccess.="<table border=1 class=idtable>
                                    <tr>
                                        <th align=center>CUSTOMER NUMBER</th>
                                        <th align=center>ITEM NUMBER</th>
                                        <th align=center>PRICE</th>
                                        <th align=center>CURRENCY CODE</th>
                                        <th align=center>CUST2</th>
                                        <th align=center>CUST3</th>
                                    </tr>";
                    $qu3="SELECT Cusno, Itnbr, Price, CurCD, CUST2, CUST3 FROM sellpricetmp WHERE StatusItem !='H'";
                    $sqlqu3=mysqli_query($msqlcon,$qu3);
                    while($arrqu3=mysqli_fetch_array($sqlqu3)){
                        $esearch=array("'","Ã");
                        $ereplace=array("\\'","A");
                        
                        $ucusno=$arrqu3['Cusno'];
                        $uitnbr=$arrqu3['Itnbr'];
                        $uprice=$arrqu3['Price'];
                        $ucurcd=$arrqu3['CurCD'];
                        $ucust2=$arrqu3['CUST2'];
                        $ucust3=$arrqu3['CUST3'];
                           
                        //Query Update
                        $qu="UPDATE sellprice SET 
                                Cusno='$ucusno',
                                Itnbr='$uitnbr',
                                Price='$uprice',
                                CurCD='$ucurcd',
                                CUST2='$ucust2',
                                CUST3='$ucust3'
                                WHERE Cusno='$ucusno' AND Itnbr='$uitnbr'";
                        mysqli_query($msqlcon,$qu) OR die(mysqli_error());
                        
                        $msgsuccess.="<tr>
                                        <td>$ucusno</td>
                                        <td>$uitnbr</td>
                                        <td>$uprice</td>
                                        <td>$ucurcd</td>
                                        <td>$ucust2</td>
                                        <td>$ucust3</td>
                                    </tr>";
                    }
                    $msgsuccess.="</table>";
                    $qd="DELETE FROM sellpricetmp";
                    mysqli_query($msqlcon,$qd);
                    
                    $msg=$msgsuccess;
                }
                
                // If succesfully replace partial data
                if($msg=='Confirm'){
                    // Check count 
                    $qc1="SELECT COUNT(*) AS fcount FROM sellprice";
                    $sqlqc1=mysqli_query($msqlcon,$qc1);
                    $arqc1=mysqli_fetch_array($sqlqc1);
                    $fcount=$arqc1['fcount'];
                        
                    $qc2="SELECT COUNT(*) AS tmpcount FROM sellpricetmp WHERE StatusItem!='H'";
                    $sqlqc2=mysqli_query($msqlcon,$qc2);
                    $arqc2=mysqli_fetch_array($sqlqc2);
                    $tmpcount=$arqc2['tmpcount'];
                        
                        echo "Do you want to replace $fcount with $tmpcount?";
                    ?>
                        <form method="POST" enctype="multipart/form-data" action="db/replace-all-price.php">
                            <input type="submit" name="yesbtn" value="Yes">
                            <input type="submit" name="nobtn" value="No">
                        </form>
                        <table border="1" class="idtable">
                            <tr>
                                <th align="center">CUSTOMER NUMBER</th>
                                    <th align="center">ITEM NUMBER</th>
                                    <th align="center">PRICE</th>
                                    <th align="center">CURRENCY CODE</th>
                                    <th align="center">CUST2</th>
                                    <th align="center">CUST3</th>
                            </tr>
                    <?
                        $qa2="SELECT Cusno, Itnbr, Price, CurCD, CUST2, CUST3 FROM sellpricetmp WHERE StatusItem!='H' LIMIT 10";
                        $sqlqa2=mysqli_query($msqlcon,$qa2);
                        while($arrqa2=mysqli_fetch_array($sqlqa2)){
                            $acusno=$arrqa2['Cusno'];
                            $aitnbr=$arrqa2['Itnbr'];
                            $aprice=$arrqa2['Price'];
                            $acurcd=$arrqa2['CurCD'];
                            $acust2=$arrqa2['CUST2'];
                            $acust3=$arrqa2['CUST3'];
                            echo "<tr>
                                    <td>$acusno</td>
                                    <td>$aitnbr</td>
                                    <td>$aprice</td>
                                    <td>$acurcd</td>
                                    <td>$acust2</td>
                                    <td>$acust3</td>
                                </tr>";
                        }
                        echo "</table>";
                    
                    $msg=$msgsuccess;
                }
                echo $msg;
            ?>
        </div>
    </body>
</html>
