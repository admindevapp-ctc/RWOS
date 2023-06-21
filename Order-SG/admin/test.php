<html>  
<head>  
<title>  
jQuery pagination  
</title>  
<meta name="viewport" content="width=device-width, initial-scale=1">  
<head>  
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">  
<style>  
#data tr {  
  display: none;  
}  
.page {  
margin-top: 50px;  
}  
table, th, td {  
  border: 1px solid black;  
}  
#data {  
  font-family: Arial, Helvetica, sans-serif;  
  border-collapse: collapse;  
  width: 100%;  
}  
#data td, #data th {  
  border: 1px solid #ddd;  
  padding: 8px;  
}  
#data tr:nth-child(even){ background-color: #f2f2f2; }  
  
#data tr:hover {  
background-color: #ddd;  
}  
#data th {  
  padding-top: 12px;  
  padding-bottom: 12px;  
  text-align: left;  
  background-color: #007bff;  
  color: white;  
}  
#nav {  
  display: inline-block;  
  margin-bottom: 1em;  
  margin-top: 1em;  
}  
#nav a {  
color: #007bff;  
font-size: 20px;  
margin-top: 22px;  
font-weight: 600;    
  padding-top: 30px;  
  padding-bottom: 10px;  
  text-align: left;  
  background-color: #007bff;  
  color: white;  
  padding: 6px;  

}  
a:hover, a:visited, a:link, a:active {  
    text-decoration: none;  
}  
</style>  
<script src="https://code.jquery.com/jquery-3.5.1.min.js"> </script>  
<script>  
$(document).ready (function () {  
    $("#dialog-form").hide();

    $('#data').after ('<div id="nav"></div>');  
    var rowsShown = 5;  
    var rowsTotal = $('#data tbody tr').length;  
   
    var numPages = rowsTotal/rowsShown;  
    for (i = 0;i < numPages;i++) {  
        var pageNum = i + 1;  
        $('#nav').append ('<a href="#" rel="'+i+'">'+pageNum+'</a> ');  
    }  
    $('#data thead tr').show(); 
    $('#data tbody tr').hide();  
    $('#data tbody tr').slice (0, rowsShown).show();  
    $('#nav a:first').addClass('active');  
    $('#nav a').bind('click', function() {  
    $('#nav a').removeClass('active');  
   $(this).addClass('active');  
        var currPage = $(this).attr('rel');  
        var startItem = currPage * rowsShown;  
        var endItem = startItem + rowsShown;  
        $('#data tbody tr').css('opacity','0.0').hide().slice(startItem, endItem).  
        css('display','table-row').animate({opacity:1}, 300);  
    });  
});  

function show(){
    $('#dialog-form').show();
}
</script>  
</head>  
<body>  
<input type ='button' onclick='show()' value='Open'>
<div id="dialog-form" title="Part Detail" class ="page" align="center">
<!--<div class ="page" align="center">  -->
    <table id="data" align="center">  
    <thead>
        <tr>  
            <th>Company</th>  
            <th>Contact</th>  
            <th>Country</th>  
        </tr>
    </thead>  
    <tbody>
    <tr>  
        <td>Alfreds Futterkiste</td>  
        <td>Maria Anders</td>  
        <td>Germany</td>  
    </tr>  
    <tr>  
        <td>Berglunds snabbk?p</td>  
        <td>Christina Berglund</td>  
        <td>Sweden</td>  
    </tr>  
    <tr>  
        <td> Centro Moctezuma</td>  
        <td>Francisco Chang</td>  
        <td>Mexico</td>  
    </tr>  
    <tr>  
        <td>Ernst Handel</td>  
        <td>Roland Mendel</td>  
        <td>Austria</td>  
    </tr>  
    <tr>  
        <td>Island Trading</td>  
        <td>Helen Bennett</td>  
        <td>UK</td>  
    </tr>  
    <tr>  
        <td>K?niglich Essen</td>  
        <td>Philip Cramer</td>  
        <td>Germany</td>  
    </tr>  
    <tr>  
        <td>Laughing Bacchus Winecellars</td>  
        <td>Yoshi Tannamuri</td>  
        <td>Canada</td>  
    </tr>  
    <tr>  
        <td>Magazzini Alimentari Riuniti</td>  
        <td>Giovanni Rovelli</td>  
        <td>Italy</td>  
    </tr>  
    <tr>  
        <td>North/South</td>  
        <td>Simon Crowther</td>                                                                                                                                                                                                                                                                                                                                                                                                                                                                
        <td>UK</td>  
    </tr>  
    <tr>  
        <td> Paris specialties</td>  
        <td> Marie Bertrand</td>  
        <td> France</td>  
    </tr>  
    <tr>  
        <td>K?niglich Essen</td>  
        <td>Philip Cramer</td>  
        <td>Germany</td>  
    </tr>  
    <tr>  
        <td>Laughing Bacchus Winecellars</td>  
        <td>Yoshi Tannamuri</td>  
        <td>Canada</td>  
    </tr>  
    <tr>  
        <td>Magazzini Alimentari Riuniti</td>  
        <td>Giovanni Rovelli</td>  
        <td>Italy</td>  
    </tr>  
    <tr>  
        <td>North/South</td>  
        <td>Simon Crowther</td>                                                                                                                                                                                                                                                                                                                                                                                                                                                                
        <td>UK</td>  
    </tr>  
    <tr>  
        <td> Paris specialties</td>  
        <td> Marie Bertrand</td>  
        <td> France</td>  
    </tr>  
    </tbody>
    </table>  
</div>  
</body>  
</html>  