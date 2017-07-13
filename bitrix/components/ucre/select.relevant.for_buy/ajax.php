<?php
require_once($_SERVER['DOCUMENT_ROOT'] . "/bitrix/modules/main/include/prolog_before.php");
if ($_SERVER['SERVER_NAME'] == 'bpm.ucre.ru'){
  echo "<pre>";
  print_r($_POST);
  echo "</pre>";
  if ($_POST['market'] == "нет данных") die("Не введены параметры рынка поиска");
  $rsData = $DB->Query(hex2bin($_POST['sql']));
  echo $rsData->SelectedRowsCount();
}else{
  echo "<center><img style='margin: 0 auto;' src='https://bpm.ucre.ru/pub/images/away.jpg'></center>";
}
?>