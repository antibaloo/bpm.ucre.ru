<?php
require_once($_SERVER['DOCUMENT_ROOT'] . "/bitrix/modules/main/include/prolog_before.php");
if ($_SERVER['SERVER_NAME'] == 'bpm.ucre.ru'){
  echo "<pre>";
  print_r($_POST);
  echo "</pre>";
  $rsData = $DB->Query("select * from b_crm_potential_deals where buy_deal_id=".$_POST['id']." AND result='".$_POST['filter']."'");
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
      <th rowspan="2">Резюме заявки</th>
      <th rowspan="2">Цена, руб.</th>
      <th colspan="5">Оценки по параметрам, +/-</th>
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
      <th rowspan="2"></th>
      <th rowspan="2">Резюме заявки</th>
      <th rowspan="2">Цена, руб.</th>
      <th colspan="5">Оценки по параметрам, +/-</th>
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
    for ($j=1;$j<=$rows;$j++){
      if ($aRes = $rsData->Fetch()){
?>
    <tr id="P<?=$aRes['sell_deal_id']?>" class="rowP">
      <td >
        <a href="<?=($_POST['assigned_by_id'] == $USER->GetID() || $USER->IsAdmin())?'javascript:addplus('.$aRes['sell_deal_id'].')':'javascript:return false;'?>"><span style='color:green;font-weight: bold'>+</span></a>&nbsp;
        <a href="<?=($_POST['assigned_by_id'] == $USER->GetID() || $USER->IsAdmin())?'javascript:addminus('.$aRes['sell_deal_id'].')':'javascript:return false;'?>"><span style='color:blue;font-weight: bold'>-</span></a>&nbsp;
        <a href="<?=($_POST['assigned_by_id'] == $USER->GetID() || $USER->IsAdmin())?'javascript:deletefrom('.$aRes['sell_deal_id'].')':'javascript:return false;'?>"><span style='color:red;font-weight: bold'>x</span></a>
      </td>
      <td></td>
      <td></td>
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
}else{
  echo "<center><img style='margin: 0 auto;' src='https://bpm.ucre.ru/pub/images/away.jpg'></center>";
}
?>