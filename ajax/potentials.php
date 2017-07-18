<?php
require_once($_SERVER['DOCUMENT_ROOT'] . "/bitrix/modules/main/include/prolog_before.php");
if ($USER->GetID()!=24 && $USER->GetID()!=1){
  echo "<br><center><img style='margin: 0 auto;' src='/ajax/images/construction.png'></center>";
}else{
  if ($_POST['category'] == 0 || $_POST['category'] == 4) {
    $APPLICATION->IncludeComponent(
      "ucre:deals.potential.for_sell",
      "",
      array('ID' => $_POST['id']),
      false
    );
  }elseif ($_POST['category'] == 2){
    $APPLICATION->IncludeComponent(
      "ucre:deals.potential.for_buy",
      "",
      array('ID' => $_POST['id']),
      false
    );
 }else {
   echo "<h2>Для это категории заявок поиск встречных не производится!</h2>";
 }
}
?>