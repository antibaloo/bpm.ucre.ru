<link href="/include/custom_css/custom_paging.css?<?=time();?>" rel="stylesheet">
<?php
require_once($_SERVER['DOCUMENT_ROOT'] . "/bitrix/modules/main/include/prolog_before.php");
if (strripos ($_SERVER['HTTP_REFERER'], 'bpm.ucre.ru')!==false && $_REQUEST['buy_id']>0){
  $type_s = array(
      '383' =>  "Дом",
      '384' =>  "Таунхаус",
      '385' =>  "Дача",
    );
  $rsData = $DB->Query("select b_crm_potential_deals.sell_deal_id, b_uts_crm_deal.UF_CRM_58958B5734602, b_iblock_element_prop_s42.PROPERTY_210, b_iblock_element_prop_s42.PROPERTY_215, b_iblock_element_prop_s42.PROPERTY_217, b_iblock_element_prop_s42.PROPERTY_218, b_iblock_element_prop_s42.PROPERTY_221, b_iblock_element_prop_s42.PROPERTY_222, b_iblock_element_prop_s42.PROPERTY_224, b_iblock_element_prop_s42.PROPERTY_225, b_iblock_element_prop_s42.PROPERTY_226, b_iblock_element_prop_s42.PROPERTY_229, b_iblock_element_prop_s42.PROPERTY_243 from b_crm_potential_deals inner join b_uts_crm_deal ON b_crm_potential_deals.sell_deal_id=b_uts_crm_deal.VALUE_ID INNER JOIN b_iblock_element ON b_uts_crm_deal.UF_CRM_1469534140 = b_iblock_element.ID INNER JOIN b_iblock_element_prop_s42 ON b_iblock_element.ID = b_iblock_element_prop_s42.IBLOCK_ELEMENT_ID where result='new' AND buy_deal_id=".$_REQUEST['buy_id']);
  $count = $rsData->SelectedRowsCount();
  $arUser = $USER->GetById($USER->GetID())->Fetch();
?>
<div class="page active">
  <div>
    <div style="float:left">
      <img src="/include/ucre_g.png">
    </div>
    <div style="float:left; padding-left: 25px;">
      ООО "Единый центр недвижимости"<br>г. Оренбург, ул. Советская, 46, 3 эт.<br>+7(922) 829-90-57, 8 (3532) 90-90-57<br>без выходных, 9:00 - 21:00</div>
    <div style="float:right">
      <ul style="margin-top:0px">
        <li>подбор создан в <?=date("H:i:s")?></li>
        <li>ФИО: <?=$arUser['LAST_NAME']." ".$arUser['NAME']." ".$arUser['SECOND_NAME']?></li>
        <li>отдел: <?=$arUser['WORK_DEPARTMENT']?></li>
        <li>должность: <?=$arUser['WORK_POSITION']?></li>
        <li>телефон: <?=$arUser['WORK_PHONE']?></li>
        <li>эл. почта: <?=$arUser['EMAIL']?></li>
      </ul>
    </div>
  </div>
  <div style="clear:both">
    <center><h2>Результат подбора заявок от <?=date("d.m.Y")?> : </h2></center>
  </div>
  
  <table>
    <tr>
      <th width="3%" rowspan="2">id</th>
      <th width="40%" rowspan="2">Резюме объекта недвижимости</th>
      <th rowspan="2" width="10%">Цена, руб.</th>
      <th colspan="5">Оценки по параметрам (+/-)</th>
      <th width="22%" rowspan="2">Комментарий</th>
    </tr>
    <tr>
      <th width="4%">Цена</th>
      <th width="4%">Объект</th>
      <th width="4%">Подъезд</th>
      <th width="4%">Двор</th>
      <th width="4%">Инф-ра</th>
    </tr>
 <?
    while ($aRes = $rsData->Fetch()){
      if ($aRes['PROPERTY_210'] == 386) $square = $aRes['PROPERTY_292'];
      else $square = $aRes['PROPERTY_224'];
      
?>
    <tr style="page-break-inside: avoid;" class="row">
      <td><?=$aRes['sell_deal_id']?></td>
      <td style="text-align: left; padding-right: 5px;"><?=$resume?></td>
      <td style="text-align: right; padding-right: 5px;"><?=($aRes['UF_CRM_58958B5734602'])?$aRes['UF_CRM_58958B5734602']:"<span style='color:red'>нет</span>"?></td>
      <td></td>
      <td></td>
      <td></td>
      <td></td>
      <td></td>
      <td></td>
    </tr>
 <?    
    }
 ?>
    
  </table>
</div>
<table style="width:100%;border: 1px solid black;border-collapse: collapse;margin-bottom:15px;font-size: 14px;font-weight: bold;">
  <tr>
    <td style="border: 1px solid black;text-align:center;" width="4%"><b>Всего:</b></td>
    <td style="border: 1px solid black;text-align:left;padding-left: 5px;" colspan="8"><b><?=$count?></b></td>
  </tr>
</table>
<?  
}else{
  echo "<center><img style='margin: 0 auto;' src='https://bpm.ucre.ru/pub/images/away.jpg'></center>";
}
?>