<?
require_once($_SERVER['DOCUMENT_ROOT'] . "/bitrix/modules/main/include/prolog_before.php");
CModule::IncludeModule("crm");
if (isset($_POST['id']) && $_POST['id'] > 0){
  $rsDeal = CCrmDeal::GetListEx(
    array(), 
    array("ID" => $_POST['id']), 
    false, 
    false, 
    array("LEAD_ID"),
    array()
  );
  $mainDeal = $rsDeal->Fetch();
  if ($mainDeal['LEAD_ID']>0){
    echo '<br><h2><a href="/crm/lead/show/'.$mainDeal['LEAD_ID'].'/" target="_blank">Перейти к лиду</a></h2>';
  }else{
    echo "<br><h2>Эта заявка создана без конвертации лида!</h2>";
  }
}
?>