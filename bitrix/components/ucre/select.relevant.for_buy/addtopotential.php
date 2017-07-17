<?php
require_once($_SERVER['DOCUMENT_ROOT'] . "/bitrix/modules/main/include/prolog_before.php");
if ($_SERVER['SERVER_NAME'] == 'bpm.ucre.ru'){
  echo "<pre>";
  print_r($_POST);
  echo "</pre>";
}else{
  echo "<center><img style='margin: 0 auto;' src='https://bpm.ucre.ru/pub/images/away.jpg'></center>";
}
?>