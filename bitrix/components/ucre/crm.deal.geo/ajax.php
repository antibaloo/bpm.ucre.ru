<?php
require_once($_SERVER['DOCUMENT_ROOT'] . "/bitrix/modules/main/include/prolog_before.php");
if (strripos ($_SERVER['HTTP_REFERER'], 'bpm.ucre.ru')!==false){
  $rsData = $DB->Query("select polygonArray from b_crm_search_polygon where dealId=".$_POST['deal_id']);
  if ($rsData->Fetch()) $DB->Query("update b_crm_search_polygon SET polygonArray='".$_POST['polygonCoords']."' where dealId=".$_POST['deal_id']);
  else $DB->Query("insert into b_crm_search_polygon (dealId,polygonArray) VALUES(".$_POST['deal_id'].",'".$_POST['polygonCoords']."')");
  
  //Bitrix\Main\Diag\Debug::dumpToFile(array('POST'=>$_POST ),"","/debug.txt");
  echo "область сохранена.";
}else{
  echo "<center><img style='margin: 0 auto;' src='https://bpm.ucre.ru/pub/images/away.jpg'></center>";
}
?>