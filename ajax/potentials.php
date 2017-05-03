<?php
require_once($_SERVER['DOCUMENT_ROOT'] . "/bitrix/modules/main/include/prolog_before.php");
if ($USER->GetID() == 24){
  $APPLICATION->IncludeComponent(
    "ucre:crm.deal.potentials",
    "",
    array('ID' => $_POST['id'], 'CATEGORY' => $_POST['category'], 'TABID' =>$_POST['tabid'], 'FILTER' => ($_POST['filter'])?$_POST['filter']:"new"),
    false
  );
}else{
  echo "<br><center><img style='margin: 0 auto;' src='/ajax/images/construction.png'></center>";
}
?>