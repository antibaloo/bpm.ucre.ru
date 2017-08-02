<link href="/include/custom_css/custom_paging.css?<?=time();?>" rel="stylesheet">
<?php
require_once($_SERVER['DOCUMENT_ROOT'] . "/bitrix/modules/main/include/prolog_before.php");
if (strripos ($_SERVER['HTTP_REFERER'], 'bpm.ucre.ru')!==false && $_REQUEST['buy_id']>0){
  $type_s = array(
      '383' =>  "Дом",
      '384' =>  "Таунхаус",
      '385' =>  "Дача",
    );
  $result_array = array();
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
      $result_array[] = $aRes['sell_deal_id'];
      //Материал стен
      $rsWallsType = CIBlockPropertyEnum::GetList(array(), array("ID" => (int)$aRes['PROPERTY_242']));
      if($wallsType = $rsWallsType->GetNext()) $wallsTypeValue = $wallsType["VALUE"];
      else $wallsTypeValue = "<span style='color:red'>неизвестно из чего </span>";
      //Тип дома
      $rsHouseType = CIBlockPropertyEnum::GetList(array(), array("ID" => (int)$aRes['PROPERTY_243']));
      if($houseType = $rsHouseType->GetNext()) $houseTypeValue = $houseType["VALUE"];
      else $houseTypeValue = "<span style='color:red'>тип дома неизвестен</span>";
      //Назначение участка
      $rsCatType = CIBlockPropertyEnum::GetList(array(), array("ID" => (int)$aRes['PROPERTY_295']));
      if($catType = $rsCatType->GetNext()) $catTypeValue = $catType["VALUE"];
      else $catTypeValue ="<span style='color:red'>неизвестного назначения</span>";
      //Назначение коммерческого объекта
      $rsAppType = CIBlockPropertyEnum::GetList(array(), array("ID" => (int)$aRes['PROPERTY_238']));
      if($appType = $rsAppType->GetNext())  $appTypeValue = $appType["VALUE"];
      else     $appTypeValue = "<span style='color:red'>неизвестно что</span>";
      
      
      $city = ($aRes['PROPERTY_215'])?$aRes['PROPERTY_215']:"<span style='color:red'>н.п. не указан</span>";
      $street = ($aRes['PROPERTY_217'])?$aRes['PROPERTY_217']:"<span style='color:red'>улица не указана</span>";
      $house = ($aRes['PROPERTY_218'])?$aRes['PROPERTY_218']:"<span style='color:red'>дом не указан</span>";
      switch($aRes['PROPERTY_210']){
        case 381:
          $resume = "Комната ".number_format($aRes['PROPERTY_228'],0)." м<sup>2</sup>, этаж ".number_format($aRes['PROPERTY_221'],0)." из ".number_format($aRes['PROPERTY_222'],0).", ".$houseTypeValue.", ".$city.", ".$street.", ".$house;
          break;
        case 382:
          $resume = number_format($aRes['PROPERTY_229'],0)."-к квартира, ".number_format($aRes['PROPERTY_224'],2)."/".number_format($aRes['PROPERTY_225'],2)."/".number_format($aRes['PROPERTY_226'],2)." (общ/жил/кух), этаж ".number_format($aRes['PROPERTY_221'],0)." из ".number_format($aRes['PROPERTY_222'],0).", ".$houseTypeValue.", ".$city.", ".$street.", ".$house;
          break;
        case 383:
        case 384:
        case 385:
          $resume = $type_s[$aRes['PROPERTY_210']]." площадью ".number_format($aRes['PROPERTY_224'],2)." м<sup>2</sup> на участке в ".number_format($aRes['PROPERTY_292'],2)." сот, ".$wallsTypeValue.", ".$city.", ".$street;
          break;
        case 386:
          $resume = "Участок площадью ".number_format($aRes['PROPERTY_292'],2)." сот, ".$catTypeValue.", ".$city.", ".$street;
          break;
        case 387:
          $resume = $appTypeValue.", площадь: ".number_format($aRes['PROPERTY_224'],2)." м<sup>2</sup>, ".$city.", ".$street;
          break;
        default:
          $resume = "непонятная хрень";
          break;
      }
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
  $DB->PrepareFields("b_crm_potential_form");
  $arFields = array(
    'deal_id' => $_REQUEST['buy_id'],
    'form_date' => $DB->GetNowFunction(),
    'user_id' => $USER->GetID(),
    'result_count' => $count,
    'result_array' => "'".implode(",", $result_array)."'"
  );
  $DB->StartTransaction();
  $DB->insert("b_crm_potential_form", $arFields, $err_mess.__LINE__);
  if (strlen($strError)<=0){
    $DB->Commit();
    echo "Учет выдачи бланка произведен!";
  }else {
    $DB->Rollback();
    echo "Ошибка записи данных в лог!";
  }
}else{
  echo "<center><img style='margin: 0 auto;' src='https://bpm.ucre.ru/pub/images/away.jpg'></center>";
}
?>