<?php

#the gate

if(isset($_GET['datetime']) and isset($_GET['viewmode'])){
    $datetimeAr = explode(' -- ',$_GET['datetime']);
    $dateA = $datetimeAr[0];
    $dateB = $datetimeAr[1];
    if($_GET['viewmode'] == "Detail"){
        print('<script type="text/javascript">location.href = \'./detail.php?start='.$dateA.'&end='.$dateB.'\';</script>');
    }
    if($_GET['viewmode'] == "Overview"){
        print('<script type="text/javascript">location.href = \'./overview.php?start='.$dateA.'&end='.$dateB.'\';</script>');
    }
}

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
    while (($result = fgetcsv($file_handle)) !== false) {
        if (array(null) !== $result) { // ignore blank lines
            $line_of_text[] = $result;
        }
    }
    fclose($file_handle);
    return $line_of_text;
}

function heatmap_colour($t){
    $hue = 30 + 240 * (30 - $t) / 60;
    return convertHSL($hue,50,40);
}

function convertHSL($h, $s, $l, $toHex=true){
    $h /= 360;
    $s /=100;
    $l /=100;

    $r = $l;
    $g = $l;
    $b = $l;
    $v = ($l <= 0.5) ? ($l * (1.0 + $s)) : ($l + $s - $l * $s);
    if ($v > 0){
          $m;
          $sv;
          $sextant;
          $fract;
          $vsf;
          $mid1;
          $mid2;

          $m = $l + $l - $v;
          $sv = ($v - $m ) / $v;
          $h *= 6.0;
          $sextant = floor($h);
          $fract = $h - $sextant;
          $vsf = $v * $sv * $fract;
          $mid1 = $m + $vsf;
          $mid2 = $v - $vsf;

          switch ($sextant)
          {
                case 0:
                      $r = $v;
                      $g = $mid1;
                      $b = $m;
                      break;
                case 1:
                      $r = $mid2;
                      $g = $v;
                      $b = $m;
                      break;
                case 2:
                      $r = $m;
                      $g = $v;
                      $b = $mid1;
                      break;
                case 3:
                      $r = $m;
                      $g = $mid2;
                      $b = $v;
                      break;
                case 4:
                      $r = $mid1;
                      $g = $m;
                      $b = $v;
                      break;
                case 5:
                      $r = $v;
                      $g = $m;
                      $b = $mid2;
                      break;
          }
    }
    $r = round($r * 255, 0);
    $g = round($g * 255, 0);
    $b = round($b * 255, 0);

    if ($toHex) {
        $r = ($r < 15)? '0' . dechex($r) : dechex($r);
        $g = ($g < 15)? '0' . dechex($g) : dechex($g);
        $b = ($b < 15)? '0' . dechex($b) : dechex($b);
        return "#$r$g$b";
    } else {
        return "rgb($r, $g, $b)";    
    }
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

$csvFile = 'logfile.csv';
$csvFileTemp = 'logfile-detail.csv';
$csv = readCSV($csvFile);
$csvTemp = readCSV($csvFileTemp);
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <title>Dataview</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.0/css/bootstrap.min.css" />
    <link rel="stylesheet" type="text/css" media="all" href="daterangepicker.css" />
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.22.1/moment.min.js"></script>
    <script type="text/javascript" src="daterangepicker.js"></script>

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
    .index-filterbox{
        text-align:center;
        margin: 0 auto;
    }
    h1{
        margin-top: 15px;
        text-align:center;
    }
    p{
        text-align:center;
    }
    </style>
</head>
<body>
<h1>D6T-logger Dataview</h1>
<p>Select the date range</p>
<div class="index-filterbox">
<form method="get">
<input type="text" id="demo" name="datetime" style="width:400px;" autocomplete="off"/>
<script>
$('#demo').daterangepicker({
    "showWeekNumbers": true,
    "timePicker": true,
    "timePicker24Hour": true,
    "timePickerSeconds": true,
    "locale": {
        "format": "DD-MM-YYYY HH:mm:ss",
        "separator": " -- ",
        "applyLabel": "Apply",
        "cancelLabel": "Cancel",
        "fromLabel": "From",
        "toLabel": "To",
        "customRangeLabel": "Custom",
        "weekLabel": "W",
        "daysOfWeek": [
            "Su",
            "Mo",
            "Tu",
            "We",
            "Th",
            "Fr",
            "Sa"
        ],
        "monthNames": [
            "January",
            "February",
            "March",
            "April",
            "May",
            "June",
            "July",
            "August",
            "September",
            "October",
            "November",
            "December"
        ],
        "firstDay": 1
    },
    "startDate": "28-01-2019 12:00:00",
    "endDate": "28-01-2019 16:00:00"
}, function(start, end, label) {

});
</script>
<input type="submit" name="viewmode" value="Overview"/>
<input type="submit" name="viewmode" value="Detail"/>
</form>
</div>

<!--
<h1>Crucial Points</h1>
<table class="crucial"><thead><th>Start</th><th>End</th><th>Cell</th></thead>-->
<?php
/*
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
}*/

?>
</table>
</body>
</html>