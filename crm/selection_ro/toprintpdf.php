<link href="custom.css?<?=time();?>" rel="stylesheet">
<script src="https://code.jquery.com/jquery-1.8.3.js"></script>
<?
require_once($_SERVER['DOCUMENT_ROOT'] . "/bitrix/modules/main/include/prolog_before.php");
//require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
require_once $_SERVER["DOCUMENT_ROOT"].'/include/dompdf-0.7.0/autoload.inc.php';
$params = unserialize($_GET['sql']);
/*echo "<pre>";
print_r($params);
echo "</pre>";*/
//$APPLICATION->SetTitle("PDF версия результатов поиска.");
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
if ($params['goal']=='sell'){
  $rsQuery = "SELECT b_crm_deal.ID,b_crm_deal.CATEGORY_ID, b_crm_deal.TITLE, b_crm_deal.ASSIGNED_BY_ID, b_uts_crm_deal.UF_CRM_1469534140,b_uts_crm_deal.UF_CRM_58958B5734602, b_iblock_element.NAME, b_iblock_element_prop_s42.PROPERTY_210, b_iblock_element_prop_s42.PROPERTY_209, b_iblock_element_prop_s42.PROPERTY_215, b_iblock_element_prop_s42.PROPERTY_217, b_iblock_element_prop_s42.PROPERTY_218, b_iblock_element_prop_s42.PROPERTY_229, b_iblock_element_prop_s42.PROPERTY_228, b_iblock_element_prop_s42.PROPERTY_243, b_iblock_element_prop_s42.PROPERTY_224, b_iblock_element_prop_s42.PROPERTY_226, b_iblock_element_prop_s42.PROPERTY_221, b_iblock_element_prop_s42.PROPERTY_222, b_iblock_element_prop_s42.PROPERTY_292, b_iblock_element_prop_s42.PROPERTY_298, b_iblock_element_prop_s42.PROPERTY_299 FROM b_crm_deal INNER JOIN b_uts_crm_deal ON b_crm_deal.ID=b_uts_crm_deal.VALUE_ID INNER JOIN b_iblock_element ON b_uts_crm_deal.UF_CRM_1469534140 = b_iblock_element.ID INNER JOIN b_iblock_element_prop_s42 ON b_iblock_element.ID = b_iblock_element_prop_s42.IBLOCK_ELEMENT_ID WHERE (b_crm_deal.STAGE_ID = 'PROPOSAL' or b_crm_deal.STAGE_ID = '8' or b_crm_deal.STAGE_ID = 'C4:1' or b_crm_deal.STAGE_ID = 'C4:PROPOSAL')";
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
  //Фильтр по району
  if ($params['locality']) $rsQuery.=" AND PROPERTY_216 LIKE '%".$params['locality']."%'";
  //Фильтр по улице
  if ($params['street']) $rsQuery.=" AND PROPERTY_217 LIKE '%".$params['street']."%'";
  //Ответственный
  if ($params['assigned'] !='') $rsQuery.=" AND ASSIGNED_BY_ID=".$params['assigned'] ;
  //Фильтр по тэгам
  if ($params['tags']){
    $tags = explode(",",$params["tags"]);
    $rsQuery.= " AND (";
    foreach ($tags as $key=>$tag){
      $rsQuery.= "UF_CRM_1494396942 LIKE '%".trim($tag)."%'";
      if ($key != count($tags)-1) $rsQuery.= " OR ";
    }
    $rsQuery.= ")";
  }
  $rsQuery .= " ORDER BY b_crm_deal.ID DESC";
  $rsData = $DB->Query($rsQuery);
  $count = $rsData->SelectedRowsCount();
  //ob_start();
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
      <th rowspan="2">id</th>
      <th rowspan="2">Резюме объекта недвижимости</th>
      <th rowspan="2">Цена, руб.</th>
      <th colspan="5">Оценки по параметрам (+/-)</th>
      <th rowspan="2">Итог</th>
      <th rowspan="2">Комментарий</th>
    </tr>
    <tr>
      <th>Цена</th>
      <th>Объект</th>
      <th>Подъезд</th>
      <th>Двор</th>
      <th>Инф-ра</th>
    </tr>
<?
  while ($aRes = $rsData->Fetch()){
    $assigned_user = CUser::GetByID($aRes['ASSIGNED_BY_ID'])->Fetch();
    if ($aRes['PROPERTY_210'] == 386) $square = $aRes['PROPERTY_292'];
    else $square = $aRes['PROPERTY_224'];
    
    $rsHouseType = CIBlockPropertyEnum::GetList(array(), array("ID" => (int)$aRes['PROPERTY_243']));
    if($houseType = $rsHouseType->GetNext())
    {
      $houseTypeValue = $houseType["VALUE"];
    }
    else $houseTypeValue = "<span style='color:red'>тип дома неизвестен</span>";
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
      default:
        $resume = "непонятная хрень";
        break;
    }
?>
    <tr style="page-break-inside: avoid;" class="row" id="<?=$aRes['ID']?>" onclick="delete_row(<?=$aRes['ID']?>);">
      <td><?=$aRes['ID']?></td>
      <td style="text-align: left; padding-right: 5px;"><?=$resume?></td>
      <td style="text-align: right; padding-right: 5px;"><?=($aRes['UF_CRM_58958B5734602'])?number_format($aRes['UF_CRM_58958B5734602'],2,"."," "):"-"?></td>
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
?>
    <tr><td></td><td colspan="10" style="text-align: left; padding-left: 5px;">Всего: <span id="count"><?=$count?></span></td></tr>
  </table>
</div>
<?  
}
$html_echo = ob_get_contents();
$html .= $html_echo;
$html .="</body></html>";
/*file_put_contents('ob_get_content.html', $html);
ob_end_clean();
echo $html_echo;
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

echo '<a href="'.$filename.'" target="_blank">Скачать</a>';
*/
//require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");
?>
<script>
  function delete_row(row_id){
    if(confirm("Вы действительно хотите удалить эту заявку из подбора?")){
      $('#'+row_id).remove();
      $('#count').text($('.row').length);
    }
  }
</script>