<?
require ($_SERVER['DOCUMENT_ROOT'] . "/bitrix/modules/main/include/prolog_before.php");
ob_start();
$params = unserialize($_POST['sql']);
?>
<html>
  <header><title>Результат подбора заявок <?=date("d.m.Y")?></title></header>
  <body>
    <style>
      div.page > table > tbody > tr > td{
        border: 1px solid black;text-align:center;
      }
    </style>
<?
  if ($params['goal']=='buy'){
     $type = array(
       ''    =>  "-",
       '813' =>  "комната",
       '814' =>  "квартира",
       '815' =>  "дом",
       '816' =>  "таунхаус",
       '817' =>  "дача",
       '818' =>  "участок",
       '819' =>  "коммерческий"
     );
    $rsQuery = "SELECT b_crm_deal.ID, b_crm_deal.TITLE, b_crm_deal.ASSIGNED_BY_ID, b_uts_crm_deal.UF_CRM_5895BC940ED3F,b_uts_crm_deal.UF_CRM_58958B5724514,b_uts_crm_deal.UF_CRM_58958B529E628,b_uts_crm_deal.UF_CRM_58958B52BA439,b_uts_crm_deal.UF_CRM_58958B52F2BAC,b_uts_crm_deal.UF_CRM_58958B51B667E, b_uts_crm_deal.UF_CRM_58958B576448C, b_uts_crm_deal.UF_CRM_58958B5751841 FROM b_crm_deal INNER JOIN b_uts_crm_deal ON b_crm_deal.ID=b_uts_crm_deal.VALUE_ID WHERE b_crm_deal.CATEGORY_ID = 2 AND b_crm_deal.STAGE_ID = 'C2:PROPOSAL'";
    //Фильтр по рынку
    if ($params['market'] == "Первичный") $rsQuery.=" AND UF_CRM_5895BC940ED3F LIKE '%828%'";
    if ($params['market'] == "Вторичный") $rsQuery.=" AND UF_CRM_5895BC940ED3F LIKE '%827%'";
    //Фильтр по типу недвижимости
    if ($params['type'] !='') $rsQuery.=" AND UF_CRM_58958B5724514=".$params['type'] ;
    //Фильтр по количеству комнат
    if ($params['rooms'] > 0) $rsQuery.=" AND UF_CRM_58958B529E628<=".$params['rooms'];
    //Фильтр по общей площади
    if ($params['square'] > 0) $rsQuery.=" AND UF_CRM_58958B52BA439<=".$params['square']." AND UF_CRM_58958B52BA439<>0";
    //Фильтр по площади кухни
    if ($params['kitchen'] > 0) $rsQuery.=" AND UF_CRM_58958B52F2BAC<=".$params['kitchen']." AND UF_CRM_58958B52F2BAC<>0";
    //Не первый этаж
    if ($params['floor'] == 1) $rsQuery.=" AND UF_CRM_58958B51B667E NOT LIKE '%754%'";
    //Не последний этаж
    if ($params['floor'] == $params['floors']) $rsQuery.=" AND UF_CRM_58958B51B667E NOT LIKE '%755%'";
    //Фильтр по цене
    if ($params['price'] > 0) $rsQuery.=" AND UF_CRM_58958B576448C<=".$params['price']." AND UF_CRM_58958B5751841>=".$params['price'];
    //Ответственный
    if ($params['assigned'] !='') $rsQuery.=" AND ASSIGNED_BY_ID=".$params['assigned'] ;
    $rsQuery .= " ORDER BY b_crm_deal.ID DESC";
    $rsData = $DB->Query($rsQuery);
    $count = $rsData->SelectedRowsCount();
?>
    Результаты поиска заявки на покупку с параметрами:<br><br>
    <table style="width:100%;border: 1px solid black;border-collapse: collapse;margin-bottom:15px;font-size: 14px;">
      <tr>
        <th style="border: 1px solid black;background-color: #b0e0e6;">Рынок</th>
        <th style="border: 1px solid black;background-color: #b0e0e6;">Тип объекта</th>
        <th style="border: 1px solid black;background-color: #b0e0e6;">Комнат</th>
        <th style="border: 1px solid black;background-color: #b0e0e6;">S<sub>общ.</sub></th>
        <th style="border: 1px solid black;background-color: #b0e0e6;">S<sub>кух.</sub></th>
        <th style="border: 1px solid black;background-color: #b0e0e6;">Этаж/Этажей</th>
        <th style="border: 1px solid black;background-color: #b0e0e6;">Цена</th>
      </tr>
      <tr>
        <td style="border: 1px solid black;text-align:center;"><?=$params['market']?></td>
        <td style="border: 1px solid black;text-align:center;"><?=($params['type'])?$type[$params['type']]:"-"?></td>
        <td style="border: 1px solid black;text-align:center;"><?=($params['rooms'])?$params['rooms']:"-"?></td>          
        <td style="border: 1px solid black;text-align:center;"><?=($params['square'])?$params['square']:"-"?></td>
        <td style="border: 1px solid black;text-align:center;"><?=($params['kitchen'])?$params['kitchen']:"-"?></td>
        <td style="border: 1px solid black;text-align:center;"><?=($params['floor'])?$params['floor']:"-"?>/<?=($params['floors'])?$params['floors']:"-"?></td>
        <td style="border: 1px solid black;text-align:center;"><?=($params['price'])?$params['price']:"-"?></td>
      </tr>
    </table>
    <br>
  <table style="width:100%;border: 1px solid black;border-collapse: collapse;margin-bottom:15px;font-size: 14px;">
    <tr>
      <th style="border: 1px solid black;background-color: #b0e0e6;">id</th>
      <th style="border: 1px solid black;background-color: #b0e0e6;">Название</th>
      <th style="border: 1px solid black;background-color: #b0e0e6;" title="Первичный/Вторичный">Рынок</th>
      <th style="border: 1px solid black;background-color: #b0e0e6;">Тип объекта</th>
      <th style="border: 1px solid black;background-color: #b0e0e6;" title="Количество комнат от ">N<sub>к</sub> от</th>
      <th style="border: 1px solid black;background-color: #b0e0e6;">S<sub>общ.</sub> от</th>
      <th style="border: 1px solid black;background-color: #b0e0e6;">S<sub>кухни</sub> от</th>
      <th style="border: 1px solid black;background-color: #b0e0e6;">Этажи</th>
      <th style="border: 1px solid black;background-color: #b0e0e6;">Цена <sub>min</sub></th>
      <th style="border: 1px solid black;background-color: #b0e0e6;">Цена <sub>max</sub></th>
      <th style="border: 1px solid black;background-color: #b0e0e6;">Ответственный</th>
    </tr>
<?
      while($aRes = $rsData->Fetch()){
        $assigned_user = CUser::GetByID($aRes['ASSIGNED_BY_ID'])->Fetch();
        $market = unserialize($aRes['UF_CRM_5895BC940ED3F']);
        $floors = unserialize($aRes['UF_CRM_58958B51B667E']);
?>
    <tr>
      <td style="border: 1px solid black;text-align:center;"><?=$aRes['ID']?></td>
      <td style="border: 1px solid black;text-align: left; padding-left: 10px;"><a href="/crm/deal/show/<?=$aRes['ID']?>/" target="_blank"><?=$aRes['TITLE']?></a></td>
      <td style="border: 1px solid black;text-align:center;"><?=(in_array(828, $market))?"П":"-"?>/<?=(in_array(827, $market))?"В":"-"?></td>
      <td style="border: 1px solid black;text-align:center;"><?=$type[$aRes['UF_CRM_58958B5724514']]?></td>
      <td style="border: 1px solid black;text-align:center;"><?=($aRes['UF_CRM_58958B529E628'])?$aRes['UF_CRM_58958B529E628']:"-"?></td>
      <td style="border: 1px solid black;text-align: right; padding-right: 5px;"><?=($aRes['UF_CRM_58958B52BA439'])?$aRes['UF_CRM_58958B52BA439']:"-"?></td>
      <td style="border: 1px solid black;text-align: right; padding-right: 5px;"><?=($aRes['UF_CRM_58958B52F2BAC'])?$aRes['UF_CRM_58958B52F2BAC']:"-"?></td>
      <td style="border: 1px solid black;text-align:center;"><?=(in_array(754, $floors))?"<span title='не первый'><s>&#8595;</s></span>":""?><?=(count($floors) == 2)?"/":""?><?=(in_array(755, $floors))?"<span title='не последний'><s>&#8593;</s></span>":""?></td>
      <td style="border: 1px solid black;text-align: right; padding-right: 5px;"><?=($aRes['UF_CRM_58958B576448C'])?$aRes['UF_CRM_58958B576448C']:"-"?></td>
      <td style="border: 1px solid black;text-align: right; padding-right: 5px;"><?=($aRes['UF_CRM_58958B5751841'])?$aRes['UF_CRM_58958B5751841']:"-"?></td>
      <td style="border: 1px solid black;text-align:center;"><a href="/company/personal/user/<?=$assigned_user['ID']?>/" target="_blank"><?=$assigned_user['LAST_NAME']." ".$assigned_user['NAME']?></a></td>
    </tr>  
<?        
      }
?>
    <tr><td></td><td colspan="10" style="border: 1px solid black;text-align: left; padding-left: 5px;"><b>Всего: <?=$count?></b></td></tr>
    </table>

<?    
  }
  if ($params['goal']=='sell'){
    $type_s = array(
      ''    =>  "-",
      '381' =>  "комната",
      '382' =>  "квартира",
      '383' =>  "дом",
      '384' =>  "таунхаус",
      '385' =>  "дача",
      '386' =>  "участок",
      '387' =>  "коммерческий"
    );
    $floors_s = array($params['nfirst'],$params['nlast']);
    $rsQuery = "SELECT b_crm_deal.ID,b_crm_deal.CATEGORY_ID, b_crm_deal.TITLE, b_crm_deal.ASSIGNED_BY_ID, b_uts_crm_deal.UF_CRM_1469534140,b_uts_crm_deal.UF_CRM_58958B5734602, b_iblock_element.NAME, b_iblock_element_prop_s42.PROPERTY_210, b_iblock_element_prop_s42.PROPERTY_209, b_iblock_element_prop_s42.PROPERTY_229, b_iblock_element_prop_s42.PROPERTY_224, b_iblock_element_prop_s42.PROPERTY_226, b_iblock_element_prop_s42.PROPERTY_221, b_iblock_element_prop_s42.PROPERTY_222, b_iblock_element_prop_s42.PROPERTY_292, b_iblock_element_prop_s42.PROPERTY_298, b_iblock_element_prop_s42.PROPERTY_299 FROM b_crm_deal INNER JOIN b_uts_crm_deal ON b_crm_deal.ID=b_uts_crm_deal.VALUE_ID INNER JOIN b_iblock_element ON b_uts_crm_deal.UF_CRM_1469534140 = b_iblock_element.ID INNER JOIN b_iblock_element_prop_s42 ON b_iblock_element.ID = b_iblock_element_prop_s42.IBLOCK_ELEMENT_ID WHERE (b_crm_deal.STAGE_ID = 'PROPOSAL' or b_crm_deal.STAGE_ID = '8' or b_crm_deal.STAGE_ID = 'C4:1' or b_crm_deal.STAGE_ID = 'C4:PROPOSAL')";
    //Фильтр по рынку
    if ($params['market'] == "Первичный") $rsQuery.=" AND CATEGORY_ID=4";
    if ($params['market'] == "Вторичный") $rsQuery.=" AND CATEGORY_ID=0";
    //Фильтр по типу недвижимости
    if ($params['type_s'] != "") $rsQuery.=" AND PROPERTY_210=".$params['type_s'];
    //Фильтр по ценам
    if ($params['price_min'] > 0) $rsQuery.=" AND UF_CRM_58958B5734602>=".$params['price_min'];
    if ($params['price_max'] > 0) $rsQuery.=" AND UF_CRM_58958B5734602<=".$params['price_max'];
    //Фильтр по комнатам
    if ($params['rooms_s'] > 0) $rsQuery.=" AND PROPERTY_229".$params['rooms_rule'].$params['rooms_s'];
    //Фильтр по общей площади
    if ($params['square_s'] > 0) $rsQuery.=" AND PROPERTY_224>=".$params['square_s'];
    //Фильтр по площади кухни
    if ($params['kitchen_s'] > 0) $rsQuery.=" AND PROPERTY_226>=".$params['kitchen_s'];
    //Не первый
    if ($params['nfirst']) $rsQuery.=" AND PROPERTY_221<>1";
    //Не последний
    if ($params['nlast']) $rsQuery.=" AND PROPERTY_221<>PROPERTY_222";
    //Фильтр по улице
    if ($params['street']) $rsQuery.=" AND PROPERTY_217 LIKE '%".$params['street']."%'";
    //Ответственный
    if ($params['assigned'] !='') $rsQuery.=" AND ASSIGNED_BY_ID=".$params['assigned'] ;
    $rsQuery .= " ORDER BY b_uts_crm_deal.UF_CRM_58958B5734602 ASC";
    $rsData = $DB->Query($rsQuery);
    $count = $rsData->SelectedRowsCount();
?>
    Результаты поиска заявки на продажу с параметрами:<br><br>
    <table style="width:100%;border: 1px solid black;border-collapse: collapse;margin-bottom:15px;font-size: 14px;">
      <tr>
        <th style="border: 1px solid black;background-color: #b0e0e6;">Рынок</th>
        <th style="border: 1px solid black;background-color: #b0e0e6;">Тип объекта</th>
        <th style="border: 1px solid black;background-color: #b0e0e6;">Цена от</th>
        <th style="border: 1px solid black;background-color: #b0e0e6;">до</th>
        <th style="border: 1px solid black;background-color: #b0e0e6;">Комнат от</th>
        <th style="border: 1px solid black;background-color: #b0e0e6;">S<sub>общ.</sub>от</th>
        <th style="border: 1px solid black;background-color: #b0e0e6;">S<sub>кух.</sub>от</th>
        <th style="border: 1px solid black;background-color: #b0e0e6;">Искл. этажи</th>
        <th style="border: 1px solid black;background-color: #b0e0e6;">Улица</th>
      </tr>
      <tr>
        <td style="border: 1px solid black;text-align:center;"><?=$params['market']?></td>
        <td style="border: 1px solid black;text-align:center;"><?=($params['type_s'])?$type_s[$params['type_s']]:"-"?></td>
        <td style="border: 1px solid black;text-align:center;"><?=($params['price_min'])?$params['price_min']:"-"?></td>          
        <td style="border: 1px solid black;text-align:center;"><?=($params['price_min'])?$params['price_min']:"-"?></td>
        <td style="border: 1px solid black;text-align:center;"><?=($params['rooms_s'])?$params['rooms_s']:"-"?></td>
        <td style="border: 1px solid black;text-align:center;"><?=($params['square_s'])?$params['square_s']:"-"?></td>
        <td style="border: 1px solid black;text-align:center;"><?=($params['kitchen_s'])?$params['kitchen_s']:"-"?></td>
        <td style="border: 1px solid black;text-align:center;"><?=($floors_s[0])?"<s>первый</s>":""?><?=($floors_s[0] && $floors_s[1])?"/":""?><?=($floors_s[1])?"<s>последний</s>":""?></td>
        <td style="border: 1px solid black;text-align:center;"><?=($params['street'])?$params['street']:"-"?></td>
      </tr>
    </table>
    <table style="width:100%;border: 1px solid black;border-collapse: collapse;margin-bottom:15px;font-size: 14px;">
      <tr>
        <th style="border: 1px solid black;background-color: #b0e0e6;">id</th>
        <th style="border: 1px solid black;background-color: #b0e0e6;">Название</th>
        <th style="border: 1px solid black;background-color: #b0e0e6;">Цена, руб.</th>
        <th style="border: 1px solid black;background-color: #b0e0e6;">Наименование  объекта</th>
        <th style="border: 1px solid black;background-color: #b0e0e6;" title="Количество комнат">N<sub>к</sub></th>
        <th style="border: 1px solid black;background-color: #b0e0e6;">S<sub>общ.</sub></th>
        <th style="border: 1px solid black;background-color: #b0e0e6;">S<sub>кухни</sub></th>
        <th style="border: 1px solid black;background-color: #b0e0e6;">Этажность</th>
        <th style="border: 1px solid black;background-color: #b0e0e6;">Ответственный</th>
      </tr>
      <?
      while ($aRes = $rsData->Fetch()){
        $assigned_user = CUser::GetByID($aRes['ASSIGNED_BY_ID'])->Fetch();
        if ($aRes['PROPERTY_210'] == 386) $square = $aRes['PROPERTY_292'];
        else $square = $aRes['PROPERTY_224'];
      ?>
      <tr>
        <td style="border: 1px solid black;text-align:center;"><?=$aRes['ID']?></td>
        <td style="border: 1px solid black;text-align: left; padding-left: 5px;"><a href="/crm/deal/show/<?=$aRes['ID']?>/" target="_blank"><?=$aRes['TITLE']?></a></td>
        <td style="border: 1px solid black;text-align: right; padding-right: 5px;"><?=($aRes['UF_CRM_58958B5734602'])?$aRes['UF_CRM_58958B5734602']:"-"?></td>
        <td style="border: 1px solid black;text-align: left; padding-left: 5px;"><a href="/crm/ro/?show&id=<?=$aRes['UF_CRM_1469534140']?>" target="_blank"><?=$aRes['NAME']?></a></td>
        <td style="border: 1px solid black;text-align:center;"><?=($aRes['PROPERTY_229'])?intval($aRes['PROPERTY_229']):"-"?></td>
        <td style="border: 1px solid black;text-align: right; padding-right: 5px;"><?=($square)?number_format($square,2):"-"?></td>
        <td style="border: 1px solid black;text-align: right; padding-right: 5px;"><?=($aRes['PROPERTY_226'])?number_format($aRes['PROPERTY_226'],2):"-"?></td>
        <td style="border: 1px solid black;text-align:center;"><?=($aRes['PROPERTY_221'])?$aRes['PROPERTY_221']:"-"?>/<?=($aRes['PROPERTY_222'])?$aRes['PROPERTY_222']:"-"?></td>
        <td style="border: 1px solid black;text-align:center;"><a href="/company/personal/user/<?=$assigned_user['ID']?>/" target="_blank"><?=$assigned_user['LAST_NAME']." ".$assigned_user['NAME']?></a></td>
      </tr>
      <?    
      }
      ?>
      <tr><td style="border: 1px solid black;"></td><td colspan="8"  style="border: 1px solid black;text-align: left; padding-left: 5px;"><b>Всего: <?=$count?></b></td></tr>
    </table>
<?    
  }
?>
  </body>
</html>
<?
$report = ob_get_contents();
ob_end_clean();
$to  = $_POST['email'];
if ($to == "") {
  echo "Адрес отправки пустой!";
}else{
  $subject = "Результат подбора заявок ".date("d.m.Y");
  $headers  = "Content-type: text/html; charset=utf-8 \r\n"; 
  $headers .= "From: Администратор КП ЕЦН <admin@ucre.ru>\r\n";
  if (mail($to, $subject, $report, $headers)){
    echo "Отправлено.";
  }else{
    echo "Ошибка.";
  }
}
?>