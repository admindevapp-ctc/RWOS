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
        <form method="POST" enctype="multipart/form-data" name="uploadForm" action="db/upload-bm008.php">
            <h3>Ordering</h3>
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
                $msg=$_GET['msg'];
                
                // If error upload
                if($msg=='Error'){
                    $msgtbl="<table border=1 class=idtable>
                        <tr>
                            <th align=center>ITEM NUMBER</th>
                            <th align=center>ASSYCD</th>
                            <th align=center>ITDSC</th>
                            <th align=center>ITCLS</th>
                            <th align=center>PLANN</th>
                            <th align=center>PRODUCT</th>
                            <th align=center>SUB PRODUCT</th>
                            <th align=center>LOT SIZE</th>
                            <th align=center>ITEM CATEGORY</th>
                            <th align=center>ITEM TYPE</th>
                            <th align=center>Error Message</th>
                        </tr>";
                    $qse="SELECT * FROM bm008prtmp WHERE StatusItem='E'";
                    $sqlqse=mysqli_query($msqlcon,$qse);
                    while($arx=mysqli_fetch_array($sqlqse)){
                        $eitnbr=$arx['ITNBR'];
                        $eassycd=$arx['ASSYCD'];
                        $eitdsc=$arx['ITDSC'];
                        $eitcls=$arx['ITCLS'];
                        $eplann=$arx['PLANN'];
                        $eproduct=$arx['Product'];
                        $esubproduct=$arx['SubProd'];
                        $elotsize=$arx['Lotsize'];
                        $eitcat=$arx['ITCAT'];
                        $eittyp=$arx['ITTYP'];
                        $eketerangan=$arx['Keterangan'];
                        $msgtbl.="<tr>
                                    <td>$eitnbr</td>
                                    <td>$eassycd</td>
                                    <td>$eitdsc</td>
                                    <td>$eitcls</td>
                                    <td>$eplann</td>
                                    <td>$eproduct</td>
                                    <td>$esubproduct</td>
                                    <td>$elotsize</td>
                                    <td>$eitcat</td>
                                    <td>$eittyp</td>
                                    <td>$eketerangan</td>
                                </tr>";
                    }
                    $msgtbl.="</table>";
                    $qd="DELETE FROM bm008prtmp";
                    mysqli_query($msqlcon,$qd);
                    $msg=$msgtbl;
                }
                
                // If succesfully add data
                if($msg=='Add'){
                    
                    $msgsuccess='Add data success';
                    $msgsuccess.="<table border=1 class=idtable>
                                    <tr>
                                        <th align=center>ITEM NUMBER</th>
                                        <th align=center>ASSYCD</th>
                                        <th align=center>ITEM DESCRIPTION</th>
                                        <th align=center>ITCLS</th>
                                        <th align=center>PLANN</th>
                                        <th align=center>PRODUCT</th>
                                        <th align=center>SUB PRODUCT</th>
                                        <th align=center>LOT SIZE</th>
                                        <th align=center>ITEM CATEGORY</th>
                                        <th align=center>ITEM TYPE</th>
                                    </tr>";
                    $qa="SELECT ITNBR, ASSYCD, ITDSC, ITCLS, PLANN, Product, SubProd, Lotsize, ITCAT, ITTYP FROM bm008prtmp WHERE StatusItem !='H'";
                    $sqlqa=mysqli_query($msqlcon,$qa);
                    while($arrqa=mysqli_fetch_array($sqlqa)){
                        $search=array("'","Ã");
                        $replace=array("\\'","A");
                
                        $aitnbr=$arrqa['ITNBR'];
                        $aassycd=$arrqa['ASSYCD'];
                        $aitdsc=str_replace($search,$replace,stripslashes($arrqa['ITDSC']));
                        $aitcls=$arrqa['ITCLS'];
                        $aplann=$arrqa['PLANN'];
                        $aproduct=$arrqa['Product'];
                        $asubprod=$arrqa['SubProd'];
                        $alotsize=$arrqa['Lotsize'];
                        $aitcat=$arrqa['ITCAT'];
                        $aittyp=$arrqa['ITTYP'];
                            
                        $qi2="INSERT INTO bm008pr(ITNBR, ASSYCD, ITDSC, ITCLS, 
                                PLANN, Product, SubProd, Lotsize, ITCAT, ITTYP)
                                VALUES('$aitnbr','$aassycd','$aitdsc','$aitcls',
                                '$aplann','$aproduct','$asubprod','$alotsize',
                                '$aitcat','$aittyp')";
                        mysqli_query($msqlcon,$qi2);
                        $msgsuccess.="<tr>
                                        <td>$aitnbr</td>
                                        <td>$aassycd</td>
                                        <td>$aitdsc</td>
                                        <td>$aitcls</td>
                                        <td>$aplann</td>
                                        <td>$aproduct</td>
                                        <td>$asubprod</td>
                                        <td>$alotsize</td>
                                        <td>$aitcat</td>
                                        <td>$aittyp</td>
                                    </tr>";
                    }
                    $msgsuccess.="</table>";
                    $qd="DELETE FROM bm008prtmp";
                    mysqli_query($msqlcon,$qd);
                    $msg=$msgsuccess;
                }
                
                // If succesfully replace partial data
                if($msg=='Replace'){
                    
                    $msgsuccess='Replace data partial success';
                    $msgsuccess.="<table border=1 class=idtable>
                                    <tr>
                                        <th align=center>ITEM NUMBER</th>
                                        <th align=center>ASSYCD</th>
                                        <th align=center>ITEM DESCRIPTION</th>
                                        <th align=center>ITCLS</th>
                                        <th align=center>PLANN</th>
                                        <th align=center>PRODUCT</th>
                                        <th align=center>SUB PRODUCT</th>
                                        <th align=center>LOT SIZE</th>
                                        <th align=center>ITEM CATEGORY</th>
                                        <th align=center>ITEM TYPE</th>
                                    </tr>";
                    $qu3="SELECT ITNBR, ASSYCD, ITDSC, ITCLS, PLANN, Product, SubProd, Lotsize, ITCAT, ITTYP FROM bm008prtmp WHERE StatusItem !='H'";
                    $sqlqu3=mysqli_query($msqlcon,$qu3);
                    while($arrqu3=mysqli_fetch_array($sqlqu3)){
                        $esearch=array("'","Ã");
                        $ereplace=array("\\'","A");
                        
                        $uitnbr=$arrqu3['ITNBR'];
                        $uassycd=$arrqu3['ASSYCD'];
                        $uitdsc=str_replace($esearch,$ereplace,stripslashes($arrqu3['ITDSC']));
                        $uitcls=$arrqu3['ITCLS'];
                        $uplann=$arrqu3['PLANN'];
                        $uproduct=$arrqu3['Product'];
                        $usubprod=$arrqu3['SubProd'];
                        $ulotsize=$arrqu3['Lotsize'];
                        $uitcat=$arrqu3['ITCAT'];
                        $uittyp=$arrqu3['ITTYP'];
                           
                        //Query Update
                        $qu="UPDATE bm008pr SET 
                                ITNBR='$uitnbr',
                                ASSYCD='$uassycd',
                                ITDSC='$uitdsc',
                                ITCLS='$uitcls',
                                PLANN='$uplann',
                                Product='$uproduct',
                                SubProd='$usubprod',
                                Lotsize='$ulotsize',
                                ITCAT='$uitcat',
                                ITTYP='$uittyp'
                                WHERE ITNBR='$uitnbr'";
                        mysqli_query($msqlcon,$qu) OR die(mysqli_error());
                        
                        $uitdsc=stripslashes($uitdsc);      // Return format view in table
                        $msgsuccess.="<tr>
                                        <td>$uitnbr</td>
                                        <td>$uassycd</td>
                                        <td>$uitdsc</td>
                                        <td>$uitcls</td>
                                        <td>$uplann</td>
                                        <td>$uproduct</td>
                                        <td>$usubprod</td>
                                        <td>$ulotsize</td>
                                        <td>$uitcat</td>
                                        <td>$uittyp</td>
                                    </tr>";
                    }
                    $msgsuccess.="</table>";
                    $qd="DELETE FROM bm008prtmp";
                    mysqli_query($msqlcon,$qd);
                    
                    $msg=$msgsuccess;
                }
                
                // If succesfully replace partial data
                if($msg=='Confirm'){
                    // Check count 
                    $qc1="SELECT COUNT(*) AS fcount FROM bm008pr";
                    $sqlqc1=mysqli_query($msqlcon,$qc1);
                    $arqc1=mysqli_fetch_array($sqlqc1);
                    $fcount=$arqc1['fcount'];
                        
                    $qc2="SELECT COUNT(*) AS tmpcount FROM bm008prtmp WHERE StatusItem!='H'";
                    $sqlqc2=mysqli_query($msqlcon,$qc2);
                    $arqc2=mysqli_fetch_array($sqlqc2);
                    $tmpcount=$arqc2['tmpcount'];
                        
                        echo "Do you want to replace $fcount with $tmpcount?";
                    ?>
                        <form method="POST" enctype="multipart/form-data" action="db/replace-all.php">
                            <input type="submit" name="yesbtn" value="Yes">
                            <input type="submit" name="nobtn" value="No">
                        </form>
                        <table border="1" class="idtable">
                            <tr>
                                <th align="center">ITEM NUMBER</th>
                                <th align="center">ASSYCD</th>
                                <th align="center">ITEM DESCRIPTION</th>
                                <th align="center">ITCLS</th>
                                <th align="center">PLANN</th>
                                <th align="center">PRODUCT</th>
                                <th align="center">SUB PRODUCT</th>
                                <th align="center">LOT SIZE</th>
                                <th align="center">ITEM CATEGORY</th>
                                <th align="center">ITEM TYPE</th>
                            </tr>
                    <?
                        $qa2="SELECT ITNBR, ASSYCD, ITDSC, ITCLS, PLANN, Product, SubProd, Lotsize, ITCAT, ITTYP FROM bm008prtmp WHERE StatusItem!='H' LIMIT 10";
                        $sqlqa2=mysqli_query($msqlcon,$qa2);
                        while($arrqa2=mysqli_fetch_array($sqlqa2)){
                            $aitnbr=$arrqa2['ITNBR'];
                            $aassycd=$arrqa2['ASSYCD'];
                            $aitdsc=$arrqa2['ITDSC'];
                            $aitcls=$arrqa2['ITCLS'];
                            $aplann=$arrqa2['PLANN'];
                            $aproduct=$arrqa2['Product'];
                            $asubprod=$arrqa2['SubProd'];
                            $alotsize=$arrqa2['Lotsize'];
                            $aitcat=$arrqa2['ITCAT'];
                            $aittyp=$arrqa2['ITTYP'];
                            echo "<tr>
                                    <td>$aitnbr</td>
                                    <td>$aassycd</td>
                                    <td>$aitdsc</td>
                                    <td>$aitcls</td>
                                    <td>$aplann</td>
                                    <td>$aproduct</td>
                                    <td>$asubprod</td>
                                    <td>$alotsize</td>
                                    <td>$aitcat</td>
                                    <td>$aittyp</td>
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
