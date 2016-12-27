<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$ids = unserialize($_GET['ids']);
$APPLICATION->SetTitle("Таблица результатов подбора: ".count($ids)." шт.");

?>
<style type="text/css">
  .mytable {
    border-collapse: collapse; /* Убираем двойные линии между ячейками */
    width: 100%;
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
foreach ($ids as $id){
  $dbRes = CIBlockElement::GetByID($id);
  if($arRes = $dbRes->GetNextElement()) {
    $el = $arRes->GetFields();
    $el["PROPERTIES"] = $arRes->GetProperties();
  }
?>
<table class="mytable">
  <tr align="center"><th class="myth" rowspan="2">id</th><th class="myth" rowspan="2">Адрес</th><th class="myth" colspan="4">Площади, кв.м</th><th class="myth" rowspan="2">Этажность</th><th class="myth" rowspan="2">Цена, руб.</th><th class="myth" rowspan="2">Тип договора</th><th class="myth" rowspan="2">Ответственный</th></tr>
  <tr><th class="myth">общ.</th><th class="myth">жил.</th><th class="myth">кух.</th><th class="myth" >бал.</th></tr>
  <tr>
    <td class="mytd">
      <?=$el['ID']?>
    </td>
    <td  class="mytd">
      <?=$el['PROPERTIES']['ADDRESS']['VALUE']?>
    </td>
    <td class="mytd" align="center">
      <?=$el['PROPERTIES']['TOTAL_AREA']['VALUE']?>
    </td>
    <td class="mytd" align="center">
      <?=$el['PROPERTIES']['LIVE_AREA']['VALUE']?>
    </td>
    <td class="mytd" align="center">
      <?=$el['PROPERTIES']['KITCHEN_AREA']['VALUE']?>
    </td>
    <td class="mytd" align="center">
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
    <?
    $rsUser = CUser::GetByID($el['PROPERTIES']['ASSIGNED_BY']['VALUE']);
    $arUser = $rsUser->Fetch();
    ?>
    <td  class="mytd" align="center">
      <?=$arUser['NAME']." ".$arUser['SECOND_NAME']." ".$arUser['LAST_NAME']?>
    </td>
  </tr> 
  <tr>
    <td class="mytd" colspan="10">
      <?=strip_tags($el['DETAIL_TEXT'])?>
    </td>
  </tr>  
</table>  
<br>
<?}?>
<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");
?>