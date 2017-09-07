<?
$referer = explode("?",$_SERVER['HTTP_REFERER']);
if ($referer[0] == 'https://bpm.ucre.ru/crm/reports/potentials.php' && $_SERVER['REQUEST_METHOD'] == 'POST'){
  require ($_SERVER['DOCUMENT_ROOT'] . "/bitrix/modules/main/include/prolog_before.php");
  require($_SERVER["DOCUMENT_ROOT"]."/include/reports/functions.php");
  CModule::IncludeModule('intranet');
  if (substr_count($_POST['emploee'], ','))$rsData = $DB->Query("select ID, TITLE, ASSIGNED_BY_ID from b_crm_deal WHERE STAGE_SEMANTIC_ID='P' AND ASSIGNED_BY_ID IN(".$_POST['emploee'].") AND CATEGORY_ID=2 ORDER BY DATE_MODIFY DESC");
  else $rsData = $DB->Query("select ID, TITLE from b_crm_deal WHERE STAGE_SEMANTIC_ID='P' AND ASSIGNED_BY_ID=".$_POST['emploee']." AND CATEGORY_ID=2 ORDER BY DATE_MODIFY DESC");
  $count = $rsData->SelectedRowsCount();
  $rows = 20;
  $pages = ($count % $rows)?intval($count/$rows)+1:$count/$rows;
  if ($count){
     for ($i=1;$i<=$pages;$i++){//постраничный вывод
?>
<div class="page<?=($i == 1)?" active":""?>" id="page<?=$i?>">
  <table>
    <tr>
      <th>ID</th>
      <th style="width:30%;">Название</th>
      <th>Ответственный</th>
      <th>Новых</th>
      <th>Положительных</th>
      <th>Отрицательных</th>
    </tr>
     
<?
       for ($j=1;$j<=$rows;$j++){
         if ($aRes = $rsData->Fetch()){
           $arUser = (substr_count($_POST['emploee'], ','))?$USER->GetById($aRes['ASSIGNED_BY_ID'])->Fetch():$USER->GetById($_POST['emploee'])->Fetch();
?>
    <tr>
      <td><?=$aRes['ID']?></td>
      <td style="text-align:left;width:30%;"><a href="/crm/deal/show/<?=$aRes['ID']?>/" target="_blank"><?=$aRes['TITLE']?></a></td>
      <td><?=$arUser['LAST_NAME']." ".$arUser['NAME']." ".$arUser['SECOND_NAME']?></td>
      <td><?=$DB->Query("SELECT * FROM b_crm_potential_deals WHERE result='new' AND buy_deal_id=".$aRes['ID'])->SelectedRowsCount()?></td>
      <td><?=$DB->Query("SELECT * FROM b_crm_potential_deals WHERE result='yes' AND buy_deal_id=".$aRes['ID'])->SelectedRowsCount()?></td>
      <td><?=$DB->Query("SELECT * FROM b_crm_potential_deals WHERE result='no' AND buy_deal_id=".$aRes['ID'])->SelectedRowsCount()?></td>
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
    <td style="border: 1px solid black;text-align:left;padding-left: 5px"><b><span id="countP"><?=$count?></span></b></td>
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
    echo "Заявки на покупку не найдены!";
  }
}else {
  echo "<center><img style='margin: 0 auto;' src='../../../pub/images/away.jpg'></center>";
}
?>
