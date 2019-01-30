<?php 

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
