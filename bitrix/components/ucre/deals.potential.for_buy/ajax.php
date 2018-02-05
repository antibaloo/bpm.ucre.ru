<?php
require_once($_SERVER['DOCUMENT_ROOT'] . "/bitrix/modules/main/include/prolog_before.php");
if (strripos ($_SERVER['HTTP_REFERER'], 'bpm.ucre.ru')!==false){
  $rsData = $DB->Query("select sell_deal_id from b_crm_potential_deals where buy_deal_id=".$_POST['id']." AND result='new'");
  $newCount = $rsData->SelectedRowsCount();
  $rsData = $DB->Query("select sell_deal_id from b_crm_potential_deals where buy_deal_id=".$_POST['id']." AND result='yes'");
  $yesCount = $rsData->SelectedRowsCount();
  $rsData = $DB->Query("select sell_deal_id from b_crm_potential_deals where buy_deal_id=".$_POST['id']." AND result='no'");
  $noCount = $rsData->SelectedRowsCount();
  echo '<a href="javascript:filter(&#39;new&#39;)"><span style="color:grey;font-weight: bold">Новые</span> - <span>'.$newCount.'</span></a>&nbsp;&nbsp;&nbsp;<a href="javascript:filter(&#39;yes&#39;)"><span style="color:green;font-weight: bold">Положительные</span> - <span>'.$yesCount.'</span></a>&nbsp;&nbsp;&nbsp;<a href="javascript:filter(&#39;no&#39;)"><span style="color:blue;font-weight: bold">Отрицательные</span> - <span>'.$noCount.'</span></a>';
  $type_s = array(
      '383' =>  "дом",
      '384' =>  "таунхаус",
      '385' =>  "Дача",
    );
  $rsData = $DB->Query("select b_crm_potential_deals.sell_deal_id, b_crm_potential_deals.price, b_crm_potential_deals.object, b_crm_potential_deals.access, b_crm_potential_deals.yard, b_crm_potential_deals.infra, b_crm_potential_deals.comment, b_uts_crm_deal.UF_CRM_58958B5734602, b_iblock_element_prop_s42.PROPERTY_210, b_iblock_element_prop_s42.PROPERTY_215, b_iblock_element_prop_s42.PROPERTY_217, b_iblock_element_prop_s42.PROPERTY_218, b_iblock_element_prop_s42.PROPERTY_221, b_iblock_element_prop_s42.PROPERTY_222, b_iblock_element_prop_s42.PROPERTY_224, b_iblock_element_prop_s42.PROPERTY_225, b_iblock_element_prop_s42.PROPERTY_226, b_iblock_element_prop_s42.PROPERTY_229, b_iblock_element_prop_s42.PROPERTY_242, b_iblock_element_prop_s42.PROPERTY_243, b_iblock_element_prop_s42.PROPERTY_292 from b_crm_potential_deals inner join b_uts_crm_deal ON b_crm_potential_deals.sell_deal_id=b_uts_crm_deal.VALUE_ID INNER JOIN b_iblock_element ON b_uts_crm_deal.UF_CRM_1469534140 = b_iblock_element.ID INNER JOIN b_iblock_element_prop_s42 ON b_iblock_element.ID = b_iblock_element_prop_s42.IBLOCK_ELEMENT_ID where buy_deal_id=".$_POST['id']." AND result='".$_POST['filter']."'");
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
      <th rowspan="2" width="8%">Цена, руб.</th>
      <th colspan="5">Оценки по параметрам, +/-</th>
      <th width="4%" rowspan="2">Итог</th>
      <th width="22%" rowspan="2">Комментарий</th>
    </tr>
    <tr>
      <th width="4%" title="Цена">Ц</th>
      <th width="4%" title="Объект">О</th>
      <th width="4%" title="Подъезд">П</th>
      <th width="4%" title="Двор">Д</th>
      <th width="4%" title="Инфраструктура">И</th>
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
      <th width="4%" rowspan="2"></th>
      <th rowspan="2" width="4%">id</th>
      <th width="45%" rowspan="2">Резюме заявки</th>
      <th rowspan="2" width="8%">Цена, руб.</th>
      <th colspan="5">Оценки по параметрам, +/-</th>
      <th width="4%" rowspan="2">Итог</th>
      <th max-width="22%" width="22%" rowspan="2">Комментарий</th>
    </tr>
    <tr>
      <th width="4%" title="Цена">Ц</th>
      <th width="4%" title="Объект">О</th>
      <th width="4%" title="Подъезд">П</th>
      <th width="4%" title="Двор">Д</th>
      <th width="4%" title="Инфраструктура">И</th>
    </tr>  
<?
    for ($j=1;$j<=$rows;$j++){
      if ($aRes = $rsData->Fetch()){
        //Материал стен
        $rsWallsType = CIBlockPropertyEnum::GetList(array(), array("ID" => (int)$aRes['PROPERTY_242']));
        if($wallsType = $rsWallsType->GetNext()) $wallsTypeValue = $wallsType["VALUE"];
        else $wallsTypeValue = "<span style='color:red'>неизвестно из чего</span>";
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
        
        $city = ($aRes['PROPERTY_215'])?$aRes['PROPERTY_215']:"<span style='color:red'><s>н.п.</s></span>";
        $street = ($aRes['PROPERTY_217'])?$aRes['PROPERTY_217']:"<span style='color:red'><s>улица</s></span>";
        $house = ($aRes['PROPERTY_218'])?$aRes['PROPERTY_218']:"<span style='color:red'><s>дом</s></span>";
        
        switch($aRes['PROPERTY_210']){
          case 381:
            $resume = "Комната ".number_format($aRes['PROPERTY_228'],0)." м<sup>2</sup>, этаж ".number_format($aRes['PROPERTY_221'],0)." из ".number_format($aRes['PROPERTY_222'],0).", ".$houseTypeValue.", ".$city.", ".$street.", ".$house;
            break;
          case 382:
            $resume = number_format($aRes['PROPERTY_229'],0)."-к, ".number_format($aRes['PROPERTY_224'],2)."/".number_format($aRes['PROPERTY_225'],2)."/".number_format($aRes['PROPERTY_226'],2).", ".number_format($aRes['PROPERTY_221'],0)." из ".number_format($aRes['PROPERTY_222'],0).", ".$houseTypeValue.", ".$street.", ".$house." (".$city.")";
            break;
          case 383:
          case 384:
            $resume = number_format($aRes['PROPERTY_229'],0)."-к ".$type_s[$aRes['PROPERTY_210']]." площадью ".number_format($aRes['PROPERTY_224'],2)." м<sup>2</sup> на участке в ".number_format($aRes['PROPERTY_292'],2)." сот, ".$wallsTypeValue.", ".$city.", ".$street;
            break;
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
    <tr id="P<?=$aRes['sell_deal_id']?>" class="rowP">
      <td >
        <?=(($_POST['assigned_by_id'] == $USER->GetID() || $USER->IsAdmin()) && $_POST['filter']!='yes')?'<a href="javascript:showDialog('.$aRes['sell_deal_id'].',	&#34;plus&#34;)" title="Оценить положительно."><span style="color:green;font-weight: bold">+</span></a>':''?>&nbsp;
        <?=(($_POST['assigned_by_id'] == $USER->GetID() || $USER->IsAdmin()) && $_POST['filter']!='no')?'<a href="javascript:showDialog('.$aRes['sell_deal_id'].', &#34;minus&#34;)" title="Оценить отрицательно."><span style="color:blue;font-weight: bold">-</span></a>':''?>&nbsp;
        <?=(($_POST['assigned_by_id'] == $USER->GetID() || $USER->IsAdmin()) && $_POST['filter']=='new')?'<a href="javascript:showDialog('.$aRes['sell_deal_id'].', &#34;delete&#34;)" title="Удалить из потенциальных."><span style="color:red;font-weight: bold">x</span></a>':''?>
      </td>
      <td title="<?=$aRes['sell_deal_id']?>"><?=$aRes['sell_deal_id']?></td>
      <td style="text-align: left;" title="<?=$resume?>"><a href="/crm/deal/show/<?=$aRes['sell_deal_id']?>/" target="_blank"><?=$resume?></a></td>
      <td><?=$aRes['UF_CRM_58958B5734602']?></td>
      <td><?=$aRes['price']?></td>
      <td><?=$aRes['object']?></td>
      <td><?=$aRes['access']?></td>
      <td><?=$aRes['yard']?></td>
      <td><?=$aRes['infra']?></td>
      <td></td>
      <td style="text-align: left;" title="<?=$aRes['comment']?>"><?=$aRes['comment']?></td>
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
  if ($_POST['filter'] == "new" && ($_POST['assigned_by_id'] == $USER->GetID() || $USER->IsAdmin())) {
    $rsData = $DB->Query("select deal_id from b_crm_potential_form WHERE deal_id=".$_POST['id']);
    $countAll = $rsData->SelectedRowsCount();
    $rsData = $DB->Query("select deal_id from b_crm_potential_form WHERE deal_id=".$_POST['id']." AND user_id=".$USER->GetID());
    $countCurUser = $rsData->SelectedRowsCount();
    echo "<a href='/crm/dealViewForm.php?buy_id=".$_POST['id']."' target='_blank'>Бланк осмотра</a><hr> По данной заявке бланк осмотра выдавался ".$countAll." раз(а), в том числе ".$countCurUser." текущему пользователю";
  }
}else{
  echo "<center><img style='margin: 0 auto;' src='https://bpm.ucre.ru/pub/images/away.jpg'></center>";
}
?>