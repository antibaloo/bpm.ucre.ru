<?php
include '../include/kladr/kladr.php';
//echo "<pre>";
//print_r($_POST);
$kladr_api = json_decode(file_get_contents($_SERVER['DOCUMENT_ROOT']."/../kladr_api_params"));
$api = new Kladr\Api($kladr_api->token);
// Формирование запроса
$query              = new Kladr\Query();
$query->ContentName = $_POST['request']['formatted'];
$query->OneString = true;
$query->WithParent = true;
$query->Limit     = 1;

// Получение данных в виде ассоциативного массива
$arResult = $api->QueryToJson($query,true);

//print_r($arResult);
//echo "</pre>";
echo "Широта: ".$_POST['coords'][0]." Долгота: ".$_POST['coords'][1];
?>
<table>
  <tr>
    <th colspan='2'>Данные Яндекс-карт</th>
  </tr>
  <tr>
    <th colspan='2'><?=$_POST['request']['formatted']?></th>
  </tr>
  <?foreach ($_POST['request']['Components'] as $component){?>
  <tr>
    <td><?=$component['kind']?></td>
    <td><?=$component['name']?></td>
  </tr>
  <?}?>
</table>
<table>
  <tr>
    <th colspan='2'>Результаты поиска в КЛАДР</th>
  </tr>
  <tr>
    <td>Адрес поиска: </td>
    <td><?=$_POST['request']['formatted']?></td>
  </tr>
  <?if (count($arResult['result'])){?>
  <tr>
    <td><?=$arResult['result'][0]['type']?></td>
    <td><?=$arResult['result'][0]['name']?></td>
  </tr>
  <tr>
    <td colspan="2"><?=$arResult['result'][0]['fullName']?></td>
  </tr>
    <tr>
    <td colspan="2">&nbsp;</td>
  </tr>
  <?foreach($arResult['result'][0]['parents'] as $parent){?>
  <tr>
    <td><?=$parent['contentType']?></td>
    <td><?=$parent['name']?></td>
  </tr>
  <?}?>
  <?}else{?>
  <tr>
    <td colspan='2'>Поиск не дал результатов</td>
  </tr>
  <?}?>
</table>