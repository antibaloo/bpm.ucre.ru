<link href="/include/custom_css/custom_paging.css?<?=time();?>" rel="stylesheet">
<?php
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED!==true)die();
?>
<br>
<form id="select_relevant_to_buy">
  <table style="width:100%;border-collapse: collapse;margin-bottom:15px;">
    <tr style="border: 1px solid lightgrey">
      <td style="text-align:right;width:25%"><b>Рынок предложения:</b></td>
      <td style="text-align:left;width:25%"><?=$arResult['SELECT_PARAMS']['MARKET']?></td>
      <td style="text-align:right;width:25%"><b>Тип объекта:</b></td>
      <td style="text-align:left;width:25%"><?=$arResult['SELECT_PARAMS']['TYPE']?></td>
    </tr>
  </table>
  <center><input id="submit" type="button" value="Искать"></center>
  <input type="hidden" name="sql" value="<?=bin2hex($arResult['SQL_STRING'])?>">
  <input type="hidden" name="deal_id" value="<?=$arResult['ID']?>">
  <input type="hidden" name="assigned_by_id" value="<?=$arResult['ASSIGNED_BY_ID']?>">
</form>
<hr>
<div id="resultGrid">
<?
  $currentUserCount = $DB->Query("select * from b_crm_relevant_search where deal_id=".$arResult['ID']." AND user_id=".$USER->GetID())->SelectedRowsCount();
  $allUsersCount = $DB->Query("select * from b_crm_relevant_search where deal_id=".$arResult['ID'])->SelectedRowsCount();
  //Вывод статистики использования инструмента
  echo "Запрос по встречным заявкам текущий пользователь произвел ".$currentUserCount." раз. Всего запросов по заявке ".$allUsersCount."<br><br>";
?>  
</div>