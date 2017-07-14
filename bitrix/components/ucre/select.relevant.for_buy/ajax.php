<?php
require_once($_SERVER['DOCUMENT_ROOT'] . "/bitrix/modules/main/include/prolog_before.php");
if ($_SERVER['SERVER_NAME'] == 'bpm.ucre.ru'){
  echo "<pre>";
  //print_r($_POST);
  echo "</pre>";
  if ($_POST['market'] == "нет данных") die("Не введены параметры рынка поиска");
  $rsData = $DB->Query(hex2bin($_POST['sql']));
  $count = $rsData->SelectedRowsCount();
  $rows = 20;
  $pages = ($count % $rows)?intval($count/$rows)+1:$count/$rows;
  for ($i=1;$i<=$pages;$i++){
?>
<div class="page<?=($i == 1)?" active":""?>" id="page<?=$i?>">
  <table>
    <tr>
      <th>Шапка таблицы<?=$i?></th>
    </tr>
<?
    for ($j=1;$j<=$rows;$j++){
?>
    <tr>
      <td>Строка таблицы<?=$i?></td>
    </tr>
<?    
    }
?>
    <tr>
      <td>Подвал таблицы<?=$i?></td>
    </tr>
  </table>
</div>
<?
  }
?>
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