<?php 
error_reporting(E_ALL);
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <title>Cam</title>
    <style>
    .pic{
        float:left;
    }
    </style>
</head>
<body>
<?php
$directory = "./img/d6t8l06-logger";
$images = glob($directory . "/*.jpg");

foreach($images as $image)
{
  print('<p><a href="'.$image.'">'.$image.'</a></p>');
}

?>
    
</body>
</html>