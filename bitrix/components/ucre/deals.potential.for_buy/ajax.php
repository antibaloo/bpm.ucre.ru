<?php
require_once($_SERVER['DOCUMENT_ROOT'] . "/bitrix/modules/main/include/prolog_before.php");
if ($_SERVER['SERVER_NAME'] == 'bpm.ucre.ru'){
  echo "<pre>";
  print_r($_POST);
  echo "</pre>";
  $rsData = $DB->Query("select * from b_crm_potential_deals where buy_deal_id=".$_POST['id']." AND result='".$_POST['filter']."'");
}else{
  echo "<center><img style='margin: 0 auto;' src='https://bpm.ucre.ru/pub/images/away.jpg'></center>";
}
?>