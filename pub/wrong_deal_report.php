<?php
require ($_SERVER['DOCUMENT_ROOT'] . "/bitrix/modules/main/include/prolog_before.php");
CModule::IncludeModule("crm");
ob_start();
?>
<html>
  <head>
    <title>Отчет об ошибочных заявках</title> 
  </head>
  <body>
<?
if (isset($_GET['assigned'])){    
  $rsDeal = CCrmDeal::GetListEx(
    array(), 
    array("CATEGORY_ID" => array(0,4), "STAGE_ID" => array("PROPOSAL","C4:1", "C4:PROPOSAL"),"UF_CRM_1469534140" => "", "ASSIGNED_BY_ID" => $_GET['assigned'], "CHECK_PERMISSIONS" => "N"), 
    false, 
    false, 
    array("ID","TITLE","UF_CRM_1469534140", "ASSIGNED_BY_ID"),
    array()
  );
} else {
  $rsDeal = CCrmDeal::GetListEx(
    array(), 
    array("CATEGORY_ID" => array(0,4), "STAGE_ID" => array("PROPOSAL","C4:1", "C4:PROPOSAL"),"UF_CRM_1469534140" => "", "CHECK_PERMISSIONS" => "N"), 
    false, 
    false, 
    array("ID","TITLE","UF_CRM_1469534140", "ASSIGNED_BY_ID"),
    array()
  );
}
while ($mainDeal = $rsDeal->Fetch()){
  $errors[$mainDeal['ASSIGNED_BY_ID']][$mainDeal['ID']]['TITLE'] = $mainDeal['TITLE'];
  $errors[$mainDeal['ASSIGNED_BY_ID']][$mainDeal['ID']]['ERRORS'][] = "нет объекта";
}
$noobject = $rsDeal->SelectedRowsCount();

if (isset($_GET['assigned'])){
  $rsDeal = CCrmDeal::GetListEx(
    array(), 
    array("CATEGORY_ID" => array(0,2,4), "STAGE_ID" => array("PROPOSAL","C4:1", "C4:PROPOSAL", "C2:PROPOSAL"),"CONTACT_ID" => "", "COMPANY_ID" => "", "ASSIGNED_BY_ID" => $_GET['assigned'], "CHECK_PERMISSIONS" => "N"), 
    false, 
    false, 
    array("ID","TITLE","CONTACT_ID", "COMPANY_ID", "ASSIGNED_BY_ID"),//Тип, кол-во комнат, цена от, цена до, цена строго
    array()
  );
} else {
  $rsDeal = CCrmDeal::GetListEx(
    array(), 
    array("CATEGORY_ID" => array(0,2,4), "STAGE_ID" => array("PROPOSAL","C4:1", "C4:PROPOSAL", "C2:PROPOSAL"),"CONTACT_ID" => "", "COMPANY_ID" => "" ,"CHECK_PERMISSIONS" => "N"), 
    false, 
    false, 
    array("ID","TITLE","CONTACT_ID", "COMPANY_ID", "ASSIGNED_BY_ID"),//Тип, кол-во комнат, цена от, цена до, цена строго
    array()
  );
}
while ($mainDeal = $rsDeal->Fetch()){
  $errors[$mainDeal['ASSIGNED_BY_ID']][$mainDeal['ID']]['TITLE'] = $mainDeal['TITLE'];
  $errors[$mainDeal['ASSIGNED_BY_ID']][$mainDeal['ID']]['ERRORS'][] = "нет данных клиента";
}
$nocontact = $rsDeal->SelectedRowsCount();
$count =0;
?>
    <div style="margin: 0 auto;padding: 40px;max-width: 800px;">
      <div style="margin: 0 0 40px 0;width: 100%;box-shadow: 0 1px 3px rgba(0, 0, 0, 0.2);display: table;">
        <div style="display: table-row; background: #f6f6f6; font-weight: 900;color: #ffffff;background: #ea6153;">
          <div style="padding: 6px 12px; display: table-cell;">
            ID
          </div>
          <div style="padding: 6px 12px; display: table-cell;">
            Название
          </div>
          <div style="padding: 6px 12px; display: table-cell;">
            Ответственный
          </div>
          <div style="padding: 6px 12px; display: table-cell;">
            Объект     
          </div>
          <div style="padding: 6px 12px; display: table-cell;">
            Клиент
          </div>
        </div>
<?

foreach ($errors as $assigned=>$deals){
  if (!isset($_GET['assigned'])){
    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, 'https://bpm.ucre.ru/pub/wrong_deal_report.php?assigned='.$assigned);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER,true);
    $out = curl_exec($curl);
    //echo $out;
    curl_close($curl);
  }
  $count+= count($deals);
  foreach ($deals as $id=>$deal){
    $rsUser = CUser::GetByID($assigned);
    $arUser = $rsUser->Fetch();
?>
        <div style="display: table-row;background: #f6f6f6;">      
          <div style="padding: 6px 12px; display: table-cell;">
            <?=$id?>
          </div>
          <div style="padding: 6px 12px; display: table-cell;">
            <a href="https://bpm.ucre.ru/crm/deal/show/<?=$id?>/"><?=$deal['TITLE']?></a>
          </div>
          <div style="padding: 6px 12px; display: table-cell;">
            <?=$arUser['LAST_NAME']." ".substr($arUser['NAME'],0,1).". ".substr($arUser['SECOND_NAME'],0,1)."."?>
          </div>
          <div style="padding: 6px 12px; display: table-cell;">
            <?=(in_array("нет объекта", $deal['ERRORS']))?"отсутствует":""?>
          </div>
          <div style="padding: 6px 12px; display: table-cell;">
            <?=(in_array("нет данных клиента", $deal['ERRORS']))?"отсутствует":""?>
          </div>
        </div>
<?    
  }
}
?>
        <div style="display: table-row; background: #f6f6f6; font-weight: 900;color: #ffffff;background: #ea6153;">
          <div style="padding: 6px 12px; display: table-cell;">
            Итого:
          </div>
          <div style="padding: 6px 12px; display: table-cell;">
            <?=$count?>
          </div>
          <div style="padding: 6px 12px; display: table-cell;">
          </div>
          <div style="padding: 6px 12px; display: table-cell;">
            <?=$noobject?>
          </div>
          <div style="padding: 6px 12px; display: table-cell;">
            <?=$nocontact?>
          </div>
        </div>
      </div>
    </div>
  </body>
</html>
<?
$report = ob_get_contents();
ob_end_clean();
if (isset($_GET['assigned'])){
  $rsUser = CUser::GetByID($_GET['assigned']);
  $arUser = $rsUser->Fetch();
  $filename = "reports/error_report_by_".$arUser['LAST_NAME']."(".$_GET['assigned'].").html";
  $to  = $arUser['EMAIL'];
  $subject = "Отчет об ошибочных заявках от ".date("d.m.Y").", сотрудник: ".$arUser['LAST_NAME']." ".substr($arUser['NAME'],0,1).". ".substr($arUser['SECOND_NAME'],0,1)."."; 
} else {
  $filename = "reports/error_report_by_all.html";
  $to  = "a.s.abalakov@ucre.ru, a.s.cherkasov@ucre.ru";
  $subject = "Отчет об ошибочных заявках от ".date("d.m.Y");
}
$headers  = "Content-type: text/html; charset=utf-8 \r\n"; 
$headers .= "From: Администратор КП ЕЦН <admin@ucre.ru>\r\n";
mail($to, $subject, $report, $headers);
file_put_contents($filename, $report);
require ($_SERVER['DOCUMENT_ROOT'] . "/bitrix/modules/main/include/epilog_after.php");
?>