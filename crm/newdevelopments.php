<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Справочник новостроек Авито(Оренбургская область):");
$developments = simplexml_load_file('https://autoload.avito.ru/format/New_developments.xml');
?>
<table style="border: 1px solid black!important; border-collapse: collapse!important;">
  <tr>
    <th style="border: 1px solid black!important; padding:5px!important;"></th>
    <th style="border: 1px solid black!important; padding:5px!important;">Адрес</th>
    <th style="border: 1px solid black!important; padding:5px!important;">Застройщик</th>
    <th style="border: 1px solid black!important; padding:5px!important;">Прописывать в объект</th>
  </tr>
<?
foreach ($developments->Region as $region) {
  if ($region['name']=='Оренбургская область') {
?>
  <tr><th colspan="4" style="border: 1px solid black!important; text-align: center!important; padding:5px!important;"><?=$region['name']?></th></tr>
<?    
    foreach ($region->City as $city){
?>
  <tr><th colspan="4" style="border: 1px solid black!important; text-align: center!important; padding:5px!important;"><?=$city['name']?></th></tr>
<?      
      foreach($city->Object as $object){
?>
  <tr>
    <th style="border: 1px solid black!important; padding:5px!important;text-align: right!important;">Жилой комплекс:</th>
    <th style="border: 1px solid black!important; padding:5px!important;"><?=$object['address']?></th>
    <th style="border: 1px solid black!important; padding:5px!important;"><?=$object['developer']?></th>
    <th style="border: 1px solid black!important; padding:5px!important;"><?=$object['id']." - ".$object['name']?></th>
  </tr>
<?
        foreach($object->Housing as $housing){
?>
  <tr>
    <td style="border: 1px solid black!important; padding:5px!important;text-align: right!important;">Дом:</td>
    <td style="border: 1px solid black!important; padding:5px!important;"><?=$housing['address']?></td>
    <td style="border: 1px solid black!important; padding:5px!important;"></td>
    <td style="border: 1px solid black!important; padding:5px!important;"><?=$housing['id']." - ".$object['name']."(".$housing['name'].")"?></td>
  </tr>
  
<?          
        }
      }
    }
  }
}
?>
</table>
<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");
?>