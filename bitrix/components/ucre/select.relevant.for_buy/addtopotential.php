<?php
require_once($_SERVER['DOCUMENT_ROOT'] . "/bitrix/modules/main/include/prolog_before.php");
if ($_SERVER['SERVER_NAME'] == 'bpm.ucre.ru'){
  $DB->PrepareFields("b_crm_potential_deals");
  $arFields = array(
    "buy_deal_id" => $_POST['buy_deal_id'],
    "sell_deal_id" => $_POST['sell_deal_id'],
    "result" => "'new'",
    "add_date" => $DB->GetNowFunction(),
    "result_date" => "'0000-00-00 00:00:00'",
    "comment" => "'Нет комментария'"
  );
  $DB->StartTransaction();
  $ID = $DB->Insert("b_crm_potential_deals", $arFields, $err_mess.__LINE__);
  if (strlen($strError)<=0){
    $DB->Commit();
    echo "Заявка №".$_POST['sell_deal_id']." добавлена.";
  }else {
    $DB->Rollback();
    echo "Ошибка добавления заявки №".$_POST['sell_deal_id']."!";
  }
}else{
  echo "<center><img style='margin: 0 auto;' src='https://bpm.ucre.ru/pub/images/away.jpg'></center>";
}
?>