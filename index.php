<?php
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

function parseDate($date){
    #format taken: Year-Month-Day 24h:m:s
    $converted = strtotime($date);
    return $converted;
}

function convertDate($date){
    return strftime('%d-%m-%Y %H:%M:%S', $date);
}

function printLegend(){
    print('<table class="legend clearfix"><tr><td><b>Legend</b></td><td class="'.colourcode(0).'">None</td>
    <td class="'.colourcode(1).'">D6T</td>
    <td class="'.colourcode(2).'">Cam</td>
    <td class="'.colourcode(3).'">Both</td></tr></table>');
}

function readCSV($csvFile){
    $file_handle = fopen($csvFile, 'r');
    while (!feof($file_handle) ) {
        $line_of_text[] = fgetcsv($file_handle, 1024);
    }
    fclose($file_handle);
    return $line_of_text;
}

function colourcode($arg){
    switch($arg){
        case 0:
            return "empty";
            break;
        case 1:
            return "orange";
            break;
        case 2:
            return "red";
            break;
        case 3:
            return "green";
            break;
    }
}

$csvFile = 'log.csv';
$csv = readCSV($csvFile);
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <title>Dataview</title>
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
    .celval{
        float:left;
        font-size: 9px;
        height:10px;
        width:10%;
        border-top:1px solid grey;
        border-left: 1px solid grey;
    }

    .celrow{
        font-weight: bold;
        text-align: center;
        clear:both;
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
printLegend();
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
for($x=0;$x<count($csv);$x++){
    if($x==0 or ($x%10==0 and $x>1 and $x<count($csv)-1)){
        print('<tr>
        <td class="ov-table-date">'.$csv[$x][0].' '.$csv[$x][1].'</td>
        <td class="ov-table-data">
        <table class="ov-table">');
    }
    print('<tr class="'.$x.'">');
    for($y=0;$y<8;$y++){
        print('<td class="ov-cel '.colourcode($csv[$x][$y+2]).'"></td>');
    }
    print('</tr>');
    if($x==count($csv)-1 or ((($x+1)%10==0) && $x>0)){
        print('</table>
        </td>
        </tr>');
    }
}
print('</table>');
?>

<h1>Detailed</h1>
<div class="clearfix"><div class="filter-start">Filters:</div><div class="filter-start">
<form method="get">
Start: <input type="text" name="start" id="start" value="<?php if(isset($filter_data_start) and !is_null($filter_data_start)){
    print(convertDate(parseDate($filter_data_start)));
}?>"/>
End: <input type="text" name="end" id="end" value="<?php if(isset($filter_data_end) and !is_null($filter_data_end)){
    print(convertDate(parseDate($filter_data_end)));
}?>"/>
<input type="submit" value="Go"/>
</form>
</div>
</div>
<div class="clearfix"><div class="filter-start">Active filters:</div>   
<?php
if(isset($filter_data_start) and !is_null($filter_data_start)){
    print('<div class="filter-blue"><b>Start:</b> '.convertDate(parseDate($filter_data_start)).'</div>');
}
if(isset($filter_data_end) and !is_null($filter_data_end)){
    print('<div class="filter-red"><b>End:</b> '.convertDate(parseDate($filter_data_end)).'</div>');
}
?>
</div>
<?php
printLegend();

$start_array = parseDate($csv[0][0].' '.$csv[0][1]);
if(isset($filter_data_start) and !is_null($filter_data_start)){
    $start_filter = parseDate($filter_data_start);
} else{
    $start_filter = $start_array;
}

$end_array = parseDate($csv[count($csv)-1][0].' '.$csv[count($csv)-1][1]);
if(isset($filter_data_end) and !is_null($filter_data_end)){
    $end_filter = parseDate($filter_data_end);
} else {
    $end_filter = $end_array;
}

$filtered_csv = array();

foreach($csv as $val){
    $valdate = parseDate($val[0].' '.$val[1]);
    if($valdate >= $start_filter and $valdate <= $end_filter){
        $filtered_csv[] = $val;
    }
}


foreach ($filtered_csv as $l){
    print('<div class="celrow">
    <div class="celval">'.$l[1].'</div>
    <div class="celval '.colourcode($l[2]).'"></div>
    <div class="celval '.colourcode($l[3]).'"></div>
    <div class="celval '.colourcode($l[4]).'"></div>
    <div class="celval '.colourcode($l[5]).'"></div>
    <div class="celval '.colourcode($l[6]).'"></div>
    <div class="celval '.colourcode($l[7]).'"></div>
    <div class="celval '.colourcode($l[8]).'"></div>
    <div class="celval '.colourcode($l[9]).' last"></div>
</div>');
}

?>

<div class="celrow">
    <div class="celval first"></div>
    <div class="celval">1</div>
    <div class="celval">2</div>
    <div class="celval">3</div>
    <div class="celval">4</div>
    <div class="celval">5</div>
    <div class="celval">6</div>
    <div class="celval">7</div>
    <div class="celval lastw">8</div>
</div>

</br></br>

<h1>Crucial Points</h1>
<table class="crucial"><thead><th>Start</th><th>End</th><th>Cell</th></thead>
<?php

$critical = array();

function checkExist($array,$x,$cell){
    $result = false;
    foreach($array as $item){
        if($x>=$item[0] && $x<=$item[1] && $item[2]==$cell){
            $result = true;
            break;
        }
    }
    return $result;
}

for($x=0;$x<count($filtered_csv);$x++){
    $listitem = $filtered_csv[$x];
    for($y=2;$y<10;$y++){
        if($listitem[$y]==1 or $listitem[$y]==2){
            $start = $x;
            for($z=$x;$z<count($filtered_csv);$z++){
                if($filtered_csv[$z][$y]==3 or $filtered_csv[$z][$y]==0 or $z==count($csv)-1){
                    if($z==count($filtered_csv)-1){
                        $end = $z+1;
                    } else{
                        $end = $z;
                    }
                    break;
                }
            }
            if (checkExist($critical,$start,$y-1)==false){
                $item = array($start, $end, $y-1);
                $critical[] = $item;
            }
        }
    }
}

foreach ($critical as $result){
    print('<tr><td>'.$filtered_csv[$result[0]][0].', '.$filtered_csv[$result[0]][1].'</td>
    <td>'.$filtered_csv[$result[1]-1][0].', '.$filtered_csv[$result[1]-1][1].'</td>
    <td>'.$result[2].'</td></tr>');
}

?>
</table>
</body>
</html>