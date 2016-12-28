<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
require_once $_SERVER["DOCUMENT_ROOT"].'/include/dompdf-0.7.0/autoload.inc.php';
$ids = unserialize($_GET['ids']);
$params = unserialize($_GET['params']);
$APPLICATION->SetTitle("Таблица результатов подбора: ".count($ids)." шт.");
$html = "
<html>
  <head>
    <style>
      body {
        font-family: DejaVu Sans;
      }
      table {
        border-collapse: collapse;
        width: 100%;
        font-size:	10px;
        page-break-inside: avoid;
      }
      th, td {
        padding: 3px; /* Поля вокруг содержимого таблицы */
        border: 1px solid black; /* Параметры рамки */
      }
      th {
        background: #b0e0e6; /* Цвет фона */
      }
      div {
        page-break-inside: avoid;
      }
    </style>
  </head>
  <body><h2>Таблица результатов подбора: ".count($ids)." шт.</h2>"
;
?>
<style type="text/css">
  .mytable {
    border-collapse: collapse; /* Убираем двойные линии между ячейками */
    width: 100%;
    font-family: DejaVu Sans;
    font-size:	10px;
  }
  .myth, .mytd {
    padding: 3px; /* Поля вокруг содержимого таблицы */
    border: 1px solid black; /* Параметры рамки */
  }
  .myth {
    background: #b0e0e6; /* Цвет фона */
  }
</style>

<?
switch ($params['TYPE']){
  case 381:
    $type = "комната";
    break;
  case 382:
    $type = "квартира";
    break;
  case 383:
    $type = "дом";
    break;
  case 384:
    $type = "таунхаус";
    break;
  case 385:
    $type = "дача";
    break;
  case 386:
    $type = "участок";
    break;
  case 387:
    $type = "коммерческий";
    break;
  default:
    $type = "";
}

$html .="
<div>
  <table>
    <caption>Параметры подбора</caption>
    <tr>
      <th rowspan='2'>Тип объекта</th>
      <th rowspan='2'>Кол-во комнат</th>
      <th rowspan='2'>Район</th>
      <th rowspan='2'>Улица</th>
      <th colspan='2'>Цена</th>
      <th colspan='2'>Площадь</th>
      <th colspan='2'>Этаж</th>
      <th colspan='2'>Этажность</th>
      <th colspan='2'>Площадь уч-ка</th>
    </tr>
    <tr>
      <th>от</th>
      <th>до</th>
      <th>от</th>
      <th>до</th>
      <th>от</th>
      <th>до</th>
      <th>от</th>
      <th>до</th>
      <th>от</th>
      <th>до</th>
    </tr>
    <tr>
      <td>".$type."</td>
      <td>".$params['ROOMS']."</td>
      <td>".$params['LOCALITY']."</td>
      <td>".$params['STREET']."</td>
      <td>".$params['MINPRICE']."</td>
      <td>".$params['MAXPRICE']."</td>
      <td>".$params['MINSQUARE']."</td>
      <td>".$params['MAXSQUARE']."</td>
      <td>".$params['MINFLOOR']."</td>
      <td>".$params['MAXFLOOR']."</td>
      <td>".$params['MINFLOORS']."</td>
      <td>".$params['MAXFLOORS']."</td>
      <td>".$params['MINPLOT']."</td>
      <td>".$params['MAXPLOT']."</td>
    </tr>
  </table>
</div
<br>
";
?>
<table class="mytable">
  <caption>Параметры подбора</caption>
    <tr>
      <th class="myth" rowspan='2'>Тип объекта</th>
      <th class="myth" rowspan='2'>Кол-во комнат</th>
      <th class="myth" rowspan='2'>Район</th>
      <th class="myth" rowspan='2'>Улица</th>
      <th class="myth" colspan='2'>Цена</th>
      <th class="myth" colspan='2'>Площадь</th>
      <th class="myth" colspan='2'>Этаж</th>
      <th class="myth" colspan='2'>Этажность</th>
      <th class="myth" colspan='2'>Площадь уч-ка</th>
    </tr>
    <tr>
      <th class="myth">от</th>
      <th class="myth">до</th>
      <th class="myth">от</th>
      <th class="myth">до</th>
      <th class="myth">от</th>
      <th class="myth">до</th>
      <th class="myth">от</th>
      <th class="myth">до</th>
      <th class="myth">от</th>
      <th class="myth">до</th>
    </tr>
    <tr>
      <td class="mytd"><?=$type?></td>
      <td class="mytd"><?=$params['ROOMS']?></td>
      <td class="mytd"><?=$params['LOCALITY']?></td>
      <td class="mytd"><?=$params['STREET']?></td>
      <td class="mytd"><?=$params['MINPRICE']?></td>
      <td class="mytd"><?=$params['MAXPRICE']?></td>
      <td class="mytd"><?=$params['MINSQUARE']?></td>
      <td class="mytd"><?=$params['MAXSQUARE']?></td>
      <td class="mytd"><?=$params['MINFLOOR']?></td>
      <td class="mytd"><?=$params['MAXFLOOR']?></td>
      <td class="mytd"><?=$params['MINFLOORS']?></td>
      <td class="mytd"><?=$params['MAXFLOORS']?></td>
      <td class="mytd"><?=$params['MINPLOT']?></td>
      <td class="mytd"><?=$params['MAXPLOT']?></td>
    </tr>
</table>
<br>
<?
foreach ($ids as $id){
  $dbRes = CIBlockElement::GetByID($id);
  if($arRes = $dbRes->GetNextElement()) {
    $el = $arRes->GetFields();
    $el["PROPERTIES"] = $arRes->GetProperties();
  }
  $rsUser = CUser::GetByID($el['PROPERTIES']['ASSIGNED_BY']['VALUE']);
  $arUser = $rsUser->Fetch();
  $html .= "
  <div>
  <table>
    <tr>
      <th>id</th><th>Адрес</th><th colspan='4'>Площади</th><th>Этажность</th><th>Цена</th><th>Договор</th><th>Ответственный</th>
    </tr>
    <tr>
      <td align='center'>".$el['ID']."</td>
      <td>".$el['PROPERTIES']['ADDRESS']['VALUE']."</td>
      <td align='center'>".$el['PROPERTIES']['TOTAL_AREA']['VALUE']."</td>
      <td align='center'>".$el['PROPERTIES']['LIVE_AREA']['VALUE']."</td>
      <td align='center'>".$el['PROPERTIES']['KITCHEN_AREA']['VALUE']."</td>
      <td align='center'>".$el['PROPERTIES']['BALKON_AREA']['VALUE']."</td>
      <td align='center'>".$el['PROPERTIES']['FLOOR']['VALUE']."/".$el['PROPERTIES']['FLOORALL']['VALUE']."</td>
      <td align='center'>".$el['PROPERTIES']['PRICE']['VALUE']."</td>
      <td align='center'>".$el['PROPERTIES']['CONTRACT_TYPE']['VALUE']."</td>
      <td align='center'>".$arUser['NAME']." ".$arUser['SECOND_NAME']." ".$arUser['LAST_NAME']."<br>".$arUser['WORK_PHONE']."</td>
    </tr>
    <tr>
      <td colspan='10'>".strip_tags($el['DETAIL_TEXT'])."</td>
    </tr>
  </table>
  <br>
  </div>
  ";
?>

<table class="mytable">
  <tr align="center"><th class="myth">id</th><th class="myth">Адрес</th><th class="myth" colspan="4">Площади, кв.м</th><th class="myth">Этажность</th><th class="myth">Цена, руб.</th><th class="myth">Тип договора</th><th class="myth">Ответственный</th></tr>
  <tr>
    <td class="mytd"  align="center" rowspan="2">
      <?=$el['ID']?>
    </td>
    <td  class="mytd">
      <?=$el['PROPERTIES']['ADDRESS']['VALUE']?>
    </td>
    <td class="mytd" align="center" title="Общая">
      <?=$el['PROPERTIES']['TOTAL_AREA']['VALUE']?>
    </td>
    <td class="mytd" align="center" title="Жилая">
      <?=$el['PROPERTIES']['LIVE_AREA']['VALUE']?>
    </td>
    <td class="mytd" align="center" title="Кухня">
      <?=$el['PROPERTIES']['KITCHEN_AREA']['VALUE']?>
    </td>
    <td class="mytd" align="center" title="Балкон">
      <?=$el['PROPERTIES']['BALKON_AREA']['VALUE']?>
    </td>
    <td class="mytd" align="right">
      <?=$el['PROPERTIES']['FLOOR']['VALUE']?>/<?=$el['PROPERTIES']['FLOORALL']['VALUE']?>
    </td>
    <td  class="mytd" align="right">
      <?=$el['PROPERTIES']['PRICE']['VALUE']?>
    </td>
    <td  class="mytd" align="center">
      <?=$el['PROPERTIES']['CONTRACT_TYPE']['VALUE']?>
    </td>
    <td  class="mytd" align="center">
      <?=$arUser['NAME']." ".$arUser['SECOND_NAME']." ".$arUser['LAST_NAME']."<br>".$arUser['WORK_PHONE']?>
    </td>
  </tr> 
  <tr>
    <td class="mytd" colspan="10">
      <?=($el['DETAIL_TEXT'] != "")?strip_tags($el['DETAIL_TEXT']):"Нет описания"?>
    </td>
  </tr>  
</table>
<br>
<?
$html .="</table>";
}?>

<?
$html .="</body></html>";
use Dompdf\Dompdf;
$dompdf = new Dompdf();
$dompdf->set_option('enable_css_float',true);
$dompdf->set_option('defaultFont', 'DejaVuSans');
$dompdf->setPaper('A4', 'landscape');
$dompdf->load_html($html);

$dompdf->render();
//$dompdf->stream("hello.pdf");
$output = $dompdf->output();
$filename = "selection_".$USER->GetID()."_".$USER->GetLastName().".pdf";
file_put_contents($filename, $output);
?>
<a href="<?=$filename?>" target="_blank">Скачать</a>
<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");
?>