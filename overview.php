<?php

include("functions.php");

if (isset($_GET['start']) and !is_null($_GET['start']) and trim($_GET['start'])!=''){
    $filter_data_start = $_GET['start'];
} else {
    $filter_data_start = null;
}

if (isset($_GET['end']) and !is_null($_GET['end']) and trim($_GET['end'])!=''){
    $filter_data_end = $_GET['end'];
} else {
    $filter_data_end = null;
}

$csvFileP = 'logfile.csv';
$csvFileT = 'logfile-detail.csv';
$csvP = readCSV($csvFileP);
$csvT = readCSV($csvFileT);
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <title>Overview</title>
    <style>
    body{
        font-family: sans-serif;
    }
    .clearfix{
        clear:both;
    }
    .legend{
        margin-bottom: 5px;
        border: 2px solid grey;
        padding: 0px;
        border-collapse: collapse;
    }
    .legend td{
        padding: 5px;
        border-left: 2px solid grey;
    }

    .blue{
        background-color:cyan;
    }

    .red{
        background-color:salmon;
    }

    .orange{
        background-color:orange;
    }

    .green{
        background-color:lightgreen;
    }
    
    .empty{
        background-color:lightyellow;
    }

    .last{
        border-right: 1px solid grey;
    }
    
    .lastw{
        border-right: 1px solid white;
    }

    .first{
        border-left: 1px solid white;
    }
    .crucial{
        font-size: 12px;
        border-spacing: 10px;
        width: 700px;
        text-align: center;
    }
    .crucial td{
        width: 30%;
    }

    .filter-start{
        float: left;
        margin-right: 10px;
        height: 30px;
        line-height: 30px;
        margin-bottom: 10px;
    }
    
    .filter-blue{
        float: left;
        border-radius: 10px;
        border: 2px solid blue;
        padding: 5px;
        margin-bottom: 10px;
        margin-left: 3px;
    }

    .filter-red{
        float: left;
        border-radius: 10px;
        border: 2px solid red;
        padding: 5px;
        margin-bottom: 10px;
        margin-left: 3px;
    }

    .ov-table{
        border-collapse: collapse;
        width: 100%;
        margin: 0px;
        padding: 0px;
    }
    .ov-table-master{
        width: 70%;
        border-collapse: collapse;
        font-size: 8px;
        margin: 0px;
        padding: 0px;
    }
    .ov-table-date{
        width: 10%;
        padding: 0px;
        border-top: 1px solid black;
        vertical-align: top;
    }
    .ov-table-data{
        width: 90%;
        padding: 0px;
        vertical-align: top;
    }
    .ov-cel{
        width: 10%;
        height: 2px;
        padding: 0px;
        margin: 0px;
        text-align: center;
    }
    </style>
</head>
<body>

<h1>Overview</h1>
<?php
#printLegend(); need new legend


#Filters
$start_array = parseDate($csvP[0][0].' '.$csvP[0][1]);
if(isset($filter_data_start) and !is_null($filter_data_start)){
    $start_filter = parseDate($filter_data_start);
} else{
    $start_filter = $start_array;
}

$end_array = parseDate($csvP[count($csvP)-1][0].' '.$csvP[count($csvP)-1][1]);
if(isset($filter_data_end) and !is_null($filter_data_end)){
    $end_filter = parseDate($filter_data_end);
} else {
    $end_filter = $end_array;
}

$filtered_csv = array();
$filtered_csv_t = array();

foreach($csvP as $val){
    $valdate = parseDate($val[0].' '.$val[1]);
    if($valdate < $start_filter){
        continue;
    }
    if($valdate>$end_filter) {
        break;
    }
    if($valdate >= $start_filter and $valdate <= $end_filter){
        $filtered_csv[] = $val;
    }
}

foreach($csvT as $val){
    $valdate = parseDate($val[0].' '.$val[1]);
    if($valdate < $start_filter){
        continue;
    }
    if($valdate>$end_filter) {
        break;
    }
    if($valdate >= $start_filter and $valdate <= $end_filter){
        $filtered_csv_t[] = $val;
    }
}

#Overview

print('<table class="ov-table-master">');
print('<tr><td class="ov-table-date" style="border-top: 0px;"></td>
        <td class="ov-table-data">
        <table class="ov-table">
        <tr><td class="ov-cel">1</td>
        <td class="ov-cel">2</td>
        <td class="ov-cel">3</td>
        <td class="ov-cel">4</td>
        <td class="ov-cel">5</td>
        <td class="ov-cel">6</td>
        <td class="ov-cel">7</td>
        <td class="ov-cel">8</td>
        </tr>
        </table>
        </td>
</tr>');
for($x=0;$x<count($filtered_csv);$x++){
    if($x==0 or ($x%10==0 and $x>1 and $x<count($filtered_csv)-1)){
        print('<tr>
        <td class="ov-table-date">'.$filtered_csv[$x][0].' '.$filtered_csv[$x][1].'</td>
        <td class="ov-table-data">
        <table class="ov-table">');
    }
    print('<tr class="'.$x.'">');
    for($y=0;$y<8;$y++){
        print('<td class="ov-cel" style="background-color:'.heatmap_colour($filtered_csv_t[$x][$y+2]).'">'.$filtered_csv_t[$x][$y+2].'  / '.$filtered_csv[$x][$y+2].'</td>');
    }
    print('</tr>');
    if($x==count($filtered_csv)-1 or ((($x+1)%10==0) && $x>0)){
        print('</table>
        </td>
        </tr>');
    }
}
print('</table>');
?>

    
</body>
</html>