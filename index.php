<?php
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
    </style>
</head>
<body>
<h1>Results</h1>
<p>Active filters:</p>
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