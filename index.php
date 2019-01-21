<?php

$filter_data_start = isset($_GET['start']) ? $_GET['start'] : null;
$filter_data_end = isset($_GET['end']) ? $_GET['end'] : null;

function convertDate($date){
    return $date;
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
            return "blue";
            break;
        case 2:
            return "green";
            break;
        case 3:
            return "red";
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
    
    .filter{
        float: left;
        border-radius: 25px;
        border: 2px solid blue;
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
    if($x==0 or (($x-1)%10==0 and $x>1 and $x<count($csv)-1)){
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
    if($x==count($csv)-1 or (($x%10==0) && $x>0)){
        print('</table>
        </td>
        </tr>');
    }
}
print('</table>');
?>

<h1>Detailed</h1>
<div><div class="filter-start">Filters:</div>
<?php
if(isset($filter_data_start) and !is_null($filter_data_start)){
    print('<div class="filter">'.convertDate($filter_data_start).'</div>');
}
if(isset($filter_data_end) and !is_null($filter_data_end)){
    print('<div class="filter">'.convertDate($filter_data_end).'</div>');
}
?>
</div>
<?php

foreach ($csv as $l){
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

$dataStart[0] = $csv[0][0];
$dataStart[1] = $csv[0][1];
$dataEnd[0] = $csv[count($csv)-1][0];
$dataEnd[1] = $csv[count($csv)-1][1];
#this is where we plug the filters based on the current page address using $_GET... TODO

$critical = array();
$results = array();

foreach($csv as $csvItem){
    if($csvItem[0]>=$dataStart[0] && $csvItem[1]>=$dataStart[1] && $csvItem[0]<=$dataEnd[0] && $csvItem[1]<=$dataEnd[1]){
        $results[] = $csvItem;
    }
}

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

for($x=0;$x<count($results);$x++){
    $listitem = $results[$x];
    for($y=2;$y<10;$y++){
        if($listitem[$y]==1 or $listitem[$y]==2){
            $start = $x;
            for($z=$x;$z<count($csv);$z++){
                if($results[$z][$y]==3 or $results[$z][$y]==0 or $z==count($csv)-1){
                    if($z==count($results)-1){
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
    print('<tr><td>'.$results[$result[0]][0].', '.$results[$result[0]][1].'</td><td>'.$results[$result[1]-1][0].', '.$results[$result[1]-1][1].'</td><td>'.$result[2].'</td></tr>');
}

?>
</table>
</body>
</html>