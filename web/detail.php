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

$csvFile = 'logfile.csv';
$csvFileTemp = 'logfile-detail.csv';
$csv = readCSV($csvFile);
$csvTemp = readCSV($csvFileTemp);
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <title>Detail</title>
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

</body>
</html>