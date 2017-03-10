<link href="custom.css?<?=time();?>" rel="stylesheet">
<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
require_once $_SERVER["DOCUMENT_ROOT"].'/include/dompdf-0.7.0/autoload.inc.php';
$params = unserialize($_GET['sql']);
$APPLICATION->SetTitle("PDF версия результатов поиска.");
$html = "
<html>
  <head>
    <style>
      body {
        font-family: DejaVu Sans;
      }
    </style>
    <link href='custom.css?".time()."' rel='stylesheet'>
    <style>
      div.page > table{font_size:10px}
    </style>
  </head>
  <body><center><h3>Результаты поиска заявок</h3></center>"
;
?>

<?
if ($params['goal']=='buy'){
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
  $rsQuery .= " ORDER BY b_crm_deal.ID DESC";
  $rsData = $DB->Query($rsQuery);
  $count = $rsData->SelectedRowsCount();
  ob_start();
?>
<div class="page active">
  <table>
    <tr>
      <th>id</th>
      <th>Название</th>
      <th title="Первичный/Вторичный">Рынок</th>
      <th>Тип объекта</th>
      <th title="Количество комнат от ">N<sub>к</sub> от</th>
      <th>S<sub>общ.</sub> от</th>
      <th>S<sub>кухни</sub> от</th>
      <th>Этажи</th>
      <th>Цена <sub>min</sub></th>
      <th>Цена <sub>max</sub></th>
      <th>Ответственный</th>
    </tr>
<?
  while($aRes = $rsData->Fetch()){
    $assigned_user = CUser::GetByID($aRes['ASSIGNED_BY_ID'])->Fetch();
    $market = unserialize($aRes['UF_CRM_5895BC940ED3F']);
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
    $floors = unserialize($aRes['UF_CRM_58958B51B667E']);
?>
    <tr>
      <td><?=$aRes['ID']?></td>
      <td style="text-align: left; padding-left: 10px;"><a href="/crm/deal/show/<?=$aRes['ID']?>/" target="_blank"><?=$aRes['TITLE']?></a></td>
      <td><?=(in_array(828, $market))?"П":"-"?>/<?=(in_array(827, $market))?"В":"-"?></td>
      <td><?=$type[$aRes['UF_CRM_58958B5724514']]?></td>
      <td><?=($aRes['UF_CRM_58958B529E628'])?$aRes['UF_CRM_58958B529E628']:"-"?></td>
      <td style="text-align: right; padding-right: 5px;"><?=($aRes['UF_CRM_58958B52BA439'])?$aRes['UF_CRM_58958B52BA439']:"-"?></td>
      <td style="text-align: right; padding-right: 5px;"><?=($aRes['UF_CRM_58958B52F2BAC'])?$aRes['UF_CRM_58958B52F2BAC']:"-"?></td>
      <td><?=(in_array(754, $floors))?"<span title='не первый'><s>&#8595;</s></span>":""?><?=(count($floors) == 2)?"/":""?><?=(in_array(755, $floors))?"<span title='не последний'><s>&#8593;</s></span>":""?></td>
      <td style="text-align: right; padding-right: 5px;"><?=($aRes['UF_CRM_58958B576448C'])?$aRes['UF_CRM_58958B576448C']:"-"?></td>
      <td style="text-align: right; padding-right: 5px;"><?=($aRes['UF_CRM_58958B5751841'])?$aRes['UF_CRM_58958B5751841']:"-"?></td>
      <td><a href="/company/personal/user/<?=$assigned_user['ID']?>/" target="_blank" title="<?=$assigned_user['PERSONAL_PHONE']?>" ><?=$assigned_user['LAST_NAME']." ".$assigned_user['NAME']?></a></td>
    </tr>  
<?    
  }
?>
    <tr><td></td><td colspan="10" style="text-align: left; padding-left: 5px;">Всего: <?=$count?></td></tr>
  </table>
</div>
<?
}
if ($params['goal']=='sell'){
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
  if ($params['rooms_s'] > 0) $rsQuery.=" AND PROPERTY_229>=".$params['rooms_s'];
  //Фильтр по общей площади
  if ($params['square_s'] > 0) $rsQuery.=" AND PROPERTY_224>=".$params['square_s'];
  //Фильтр по площади кухни
  if ($params['kitchen_s'] > 0) $rsQuery.=" AND PROPERTY_226>=".$params['kitchen_s'];
  //Не первый
  if ($params['nfirst']) $rsQuery.=" AND PROPERTY_221<>1";
  //Не последний
  if ($params['nlast']) $rsQuery.=" AND PROPERTY_221<>PROPERTY_222";
  $rsQuery .= " ORDER BY b_crm_deal.ID DESC";
  $rsData = $DB->Query($rsQuery);
  $count = $rsData->SelectedRowsCount();
?>
<div class="page active">
  <table>
    <tr>
      <th>id</th>
      <th>Название</th>
      <th>Цена, руб.</th>
      <th>Наименование  объекта</th>
      <th title="Количество комнат">N<sub>к</sub></th>
      <th>S<sub>общ.</sub></th>
      <th>S<sub>кухни</sub></th>
      <th>Этажность</th>
      <th>Ответственный</th>
    </tr>
<?
  while ($aRes = $rsData->Fetch()){
    $assigned_user = CUser::GetByID($aRes['ASSIGNED_BY_ID'])->Fetch();
    if ($aRes['PROPERTY_210'] == 386) $square = $aRes['PROPERTY_292'];
    else $square = $aRes['PROPERTY_224'];
?>
    <tr>
      <td><?=$aRes['ID']?></td>
      <td style="text-align: left; padding-left: 5px;"><a href="/crm/deal/show/<?=$aRes['ID']?>/" target="_blank"><?=$aRes['TITLE']?></a></td>
      <td style="text-align: right; padding-right: 5px;"><?=($aRes['UF_CRM_58958B5734602'])?$aRes['UF_CRM_58958B5734602']:"-"?></td>
      <td style="text-align: left; padding-left: 5px;"><a href="/crm/ro/?show&id=<?=$aRes['UF_CRM_1469534140']?>" target="_blank"><?=$aRes['NAME']?></a></td>
      <td><?=($aRes['PROPERTY_229'])?intval($aRes['PROPERTY_229']):"-"?></td>
      <td style="text-align: right; padding-right: 5px;"><?=($square)?number_format($square,2):"-"?></td>
      <td style="text-align: right; padding-right: 5px;"><?=($aRes['PROPERTY_226'])?number_format($aRes['PROPERTY_226'],2):"-"?></td>
      <td><?=($aRes['PROPERTY_221'])?$aRes['PROPERTY_221']:"-"?>/<?=($aRes['PROPERTY_222'])?$aRes['PROPERTY_222']:"-"?></td>
      <td><a href="/company/personal/user/<?=$assigned_user['ID']?>/" target="_blank" title="<?=$assigned_user['PERSONAL_PHONE']?>" ><?=$assigned_user['LAST_NAME']." ".$assigned_user['NAME']?></a></td>
    </tr>
 <?    
  }
?>
    <tr><td></td><td colspan="8" style="text-align: left; padding-left: 5px;">Всего: <?=$count?></td></tr>
  </table>
</div>
<?  
}
$html_echo = ob_get_contents();
$html .= $html_echo;
$html .="</body></html>";
file_put_contents('ob_get_content.html', $html);
ob_end_clean();
//echo $html_echo;
use Dompdf\Dompdf;
$dompdf = new Dompdf();
$dompdf->set_option('enable_css_float',true);
$dompdf->set_option('defaultFont', 'DejaVuSans');
$dompdf->setPaper('A4', 'landscape');
$dompdf->load_html($html);

$dompdf->render();
$output = $dompdf->output();
$filename = "selection_".$USER->GetID()."_".$USER->GetLastName().".pdf";
file_put_contents($filename, $output);
?>
<a href="<?=$filename?>" target="_blank">Скачать</a>
<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");
?>