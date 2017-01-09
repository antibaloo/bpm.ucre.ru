<?php
require ($_SERVER['DOCUMENT_ROOT'] . "/bitrix/modules/main/include/prolog_before.php");
echo "Выгрузка объектов недвижимости в формате Авито-Недвижимость, выгружено ".$num." объектов за ".$time." секунд.(включая комнат - ".$r.", квартир - ".$f.", домов, таунхаусов - ".$h.", дач - ".$d.", участков - ".$p.", коммерческих - ".$c.").";			
require ($_SERVER['DOCUMENT_ROOT'] . "/bitrix/modules/main/include/epilog_after.php");      
?>