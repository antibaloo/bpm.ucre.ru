<?php
require($_SERVER["DOCUMENT_ROOT"]."/include/ocr/ocr.php");  
// пробуем распознать картинку 1.png
$encrypt = new crackCapcha('phone_image_838143255.png'); 
echo "<br><span style='font-size: 25px;'>Resolve: ".$encrypt->resolve."</span><br><br>";
?>