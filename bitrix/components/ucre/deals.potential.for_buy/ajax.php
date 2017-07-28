<?php
require_once($_SERVER['DOCUMENT_ROOT'] . "/bitrix/modules/main/include/prolog_before.php");
if ($_SERVER['SERVER_NAME'] == 'bpm.ucre.ru'){
  $type_s = array(
      '383' =>  "Дом",
      '384' =>  "Таунхаус",
      '385' =>  "Дача",
    );
  $rsData = $DB->Query("select b_crm_potential_deals.sell_deal_id, b_uts_crm_deal.UF_CRM_58958B5734602, b_iblock_element_prop_s42.PROPERTY_210, b_iblock_element_prop_s42.PROPERTY_215, b_iblock_element_prop_s42.PROPERTY_217, b_iblock_element_prop_s42.PROPERTY_218, b_iblock_element_prop_s42.PROPERTY_221, b_iblock_element_prop_s42.PROPERTY_222, b_iblock_element_prop_s42.PROPERTY_224, b_iblock_element_prop_s42.PROPERTY_225, b_iblock_element_prop_s42.PROPERTY_226, b_iblock_element_prop_s42.PROPERTY_229, b_iblock_element_prop_s42.PROPERTY_243 from b_crm_potential_deals inner join b_uts_crm_deal ON b_crm_potential_deals.sell_deal_id=b_uts_crm_deal.VALUE_ID INNER JOIN b_iblock_element ON b_uts_crm_deal.UF_CRM_1469534140 = b_iblock_element.ID INNER JOIN b_iblock_element_prop_s42 ON b_iblock_element.ID = b_iblock_element_prop_s42.IBLOCK_ELEMENT_ID where buy_deal_id=".$_POST['id']." AND result='".$_POST['filter']."'");
  //$rsData = $DB->Query("select * from b_crm_potential_deals where buy_deal_id=".$_POST['id']." AND result='".$_POST['filter']."'");
  $count = $rsData->SelectedRowsCount();
  echo '<div id="resultOps"></div>';//Результаты удаление, выставления оценки
  $rows = 20;
  $pages = ($count % $rows)?intval($count/$rows)+1:$count/$rows;
?>
<?
  if (!$count) {//Если нет результатов по фильтру
?>
<div class="page active">
  <table>
    <tr>
      <th rowspan="2"></th>
      <th width="45%" rowspan="2">Резюме заявки</th>
      <th rowspan="2">Цена, руб.</th>
      <th colspan="5">Оценки по параметрам, +/-</th>
      <th width="4%" rowspan="2">Итог</th>
      <th width="22%" rowspan="2">Комментарий</th>
    </tr>
    <tr>
      <th width="4%">Цена</th>
      <th width="4%">Объект</th>
      <th width="4%">Подъезд</th>
      <th width="4%">Двор</th>
      <th width="4%">Инф-ра</th>
    </tr>
    <tr>
      <td></td>
      <td style="text-align:center;" colspan="9"><b>Нет записей!</b></td>
    </tr>
  </table>
</div>
<?    
  }
  for ($i=1;$i<=$pages;$i++){//постраничный вывод
?>
<div class="page<?=($i == 1)?" active":""?>" id="page<?=$i?>">
  <table>
    <tr>
      <th rowspan="2"></th>
      <th width="45%" rowspan="2">Резюме заявки</th>
      <th rowspan="2">Цена, руб.</th>
      <th colspan="5">Оценки по параметрам, +/-</th>
      <th width="4%" rowspan="2">Итог</th>
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
    for ($j=1;$j<=$rows;$j++){
      if ($aRes = $rsData->Fetch()){
        //Тип дома
        $rsHouseType = CIBlockPropertyEnum::GetList(array(), array("ID" => (int)$aRes['PROPERTY_243']));
        if($houseType = $rsHouseType->GetNext()) $houseTypeValue = $houseType["VALUE"];
        else $houseTypeValue = "<span style='color:red'>тип дома неизвестен</span>";
        $city = ($aRes['PROPERTY_215'])?$aRes['PROPERTY_215']:"<span style='color:red'><s>н.п.</s></span>";
        $street = ($aRes['PROPERTY_217'])?$aRes['PROPERTY_217']:"<span style='color:red'><s>улица</s></span>";
        $house = ($aRes['PROPERTY_218'])?$aRes['PROPERTY_218']:"<span style='color:red'><s>дом</s></span>";
        
        switch($aRes['PROPERTY_210']){
          case 381:
            $resume = "Комната";
            break;
          case 382:
            $resume = number_format($aRes['PROPERTY_229'],0)."-к квартира ".number_format($aRes['PROPERTY_224'],2)."/".number_format($aRes['PROPERTY_225'],2)."/".number_format($aRes['PROPERTY_226'],2)." (общ/жил/кух), этаж ".number_format($aRes['PROPERTY_221'],0)." из ".number_format($aRes['PROPERTY_222'],0).", ".$houseTypeValue.", ".$city.", ".$street.", ".$house;
            break;
          case 383:
          case 384:
          case 385:
            $resume = $type_s[$aRes['PROPERTY_210']]." площадью ";
            break;
          case 386:
            $resume = "Участок";
            break;
          case 387:
            $resume = "Коммерческая недвижимость";
            break;
          default:
            $resume = "непонятная хрень";
            break;
        }
?>
    <tr id="P<?=$aRes['sell_deal_id']?>" class="rowP">
      <td >
        <a href="<?=($_POST['assigned_by_id'] == $USER->GetID() || $USER->IsAdmin())?'javascript:addplus('.$aRes['sell_deal_id'].')':'javascript:return false;'?>"><span style='color:green;font-weight: bold'>+</span></a>&nbsp;
        <a href="<?=($_POST['assigned_by_id'] == $USER->GetID() || $USER->IsAdmin())?'javascript:addminus('.$aRes['sell_deal_id'].')':'javascript:return false;'?>"><span style='color:blue;font-weight: bold'>-</span></a>&nbsp;
        <a href="<?=($_POST['assigned_by_id'] == $USER->GetID() || $USER->IsAdmin())?'javascript:deletefrom('.$aRes['sell_deal_id'].')':'javascript:return false;'?>"><span style='color:red;font-weight: bold'>x</span></a>
      </td>
      <td style="text-align: left; padding-left: 5px;"><a href="/crm/deal/show/<?=$aRes['sell_deal_id']?>/" target="_blank"><?=$resume?></a></td>
      <td><?=$aRes['UF_CRM_58958B5734602']?></td>
      <td></td>
      <td></td>
      <td></td>
      <td></td>
      <td></td>
      <td></td>
      <td></td>
    </tr>
<?
      }
    }
?>
  </table>
</div>
<?    
  }
?>
<table style="width:100%;border: 1px solid black;border-collapse: collapse;margin-bottom:15px;font-size: 14px;font-weight: bold;">
  <tr>
    <td style="border: 1px solid black;text-align:center;" width="4%"><b>Всего:</b></td>
    <td style="border: 1px solid black;text-align:left;padding-left: 5px;" colspan="9"><b><span id="countP"><?=$count?></span></b></td>
  </tr>
</table>
<div class="pages">
  <center>
<?
  for ($i=1;$i<=$pages;$i++){//Цикл по страницам для номеров страниц
    echo "<span class='pages".(($i == 1)?" active":"")."' onclick='set_active(this)'>".$i."</span>&nbsp;";
  }
?>
  </center>
</div>
<?  
}else{
  echo "<center><img style='margin: 0 auto;' src='https://bpm.ucre.ru/pub/images/away.jpg'></center>";
}
?>