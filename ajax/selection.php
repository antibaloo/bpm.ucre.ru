<?php
require_once($_SERVER['DOCUMENT_ROOT'] . "/bitrix/modules/main/include/prolog_before.php");
if ($USER->GetID()!=24){
  $APPLICATION->IncludeComponent(
    "ucre:select.request",
    "",
    array('ID' => $_GET['id'], 'CATEGORY' => $_GET['category']),
    false
  );
}else{
 if ($_GET['category'] == 0 || $_GET['category'] == 4) {
   echo "<h2>Встречные для продажи и новостроек!</h2>";
   $APPLICATION->IncludeComponent(
     "ucre:select.relevant.for_sell",
     "",
     array('ID' => $_GET['id']),
     false
   );
 }elseif ($_GET['category'] == 2){
   echo "<h2>Встречные для заявок на покупку!</h2>";
   $APPLICATION->IncludeComponent(
     "ucre:select.relevant.for_buy",
     "",
     array('ID' => $_GET['id']),
     false
   );
 }else {
   echo "<h2>Для это категории заявок поиск встречных не производится!</h2>";
 }
}
?>