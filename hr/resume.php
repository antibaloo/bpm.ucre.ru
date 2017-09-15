<link href="/include/custom_css/custom_paging.css?<?=time();?>" rel="stylesheet">
<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
CModule::IncludeModule('iblock');
$APPLICATION->SetTitle("Резюме");
$status = array(
  'NEW' => 'новое',
  'REJECT' => 'отказ'
);
$arSelect = array("ID", "DATE_CREATE", "PROPERTY_*");
$arFilter = array("IBLOCK_ID" => 65);
$res = CIBlockElement::GetList(array("DATE_CREATE" => "DESC"), $arFilter, false, array(), $arSelect);
$count = $res->SelectedRowsCount();
$rows = 20;
$pages = ($count % $rows)?intval($count/$rows)+1:$count/$rows;

 for ($i=1;$i<=$pages;$i++){
?>
  <div class="page<?=($i == 1)?" active ":" "?>" id="page<?=$i?>">
    <table>
      <tr>
        <th>Действия</th>
        <th>Загружен</th>
        <th>Статус</th>
        <th>ФИО</th>
        <th>д.р.</th>
        <th>Телефон</th>
        <th>email</th>
        <th>Резюме</th>
      </tr>
      <?   
   for ($j=1;$j<=$rows;$j++){
      if ($ob = $res->GetNext()){
?>
        <tr>
          <td></td>
          <td>
            <?=$ob['DATE_CREATE']?>
          </td>
          <td>
            <?=$status[$ob['PROPERTY_422']]?>
          </td>
          <td>
            <?=$ob['PROPERTY_417']?>
          </td>
          <td>
            <?=$ob['PROPERTY_418']?>
          </td>
          <td>
            <?=$ob['PROPERTY_419']?>
          </td>
          <td>
            <?=$ob['PROPERTY_420']?>
          </td>
          <td><a href="<?=CFile::GetPath($ob['PROPERTY_421'])?>" target="_blank">Скачать</a></td>
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



/*while($ob = $res->GetNext())
{
   $PREVIEW_PICTURE = CFile::GetPath($ob["PROPERTY_421"]);
   echo "<pre>"; print_r($ob); echo "</pre>";
   echo "<pre>"; print_r($PREVIEW_PICTURE); echo "</pre>";
}*/

?>
      <?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>