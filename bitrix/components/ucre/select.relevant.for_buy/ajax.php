<?php
require_once($_SERVER['DOCUMENT_ROOT'] . "/bitrix/modules/main/include/prolog_before.php");
if ($_SERVER['SERVER_NAME'] == 'bpm.ucre.ru'){
  //echo "<pre>";
  //print_r($_POST);
  //echo "</pre>";
  if ($_POST['market'] == "нет данных") die("Не введены параметры рынка поиска");
  $sql_string = hex2bin($_POST['sql']);
  
  //Фильтр по уже имеющимся в потенциальных
  $rsPotentials = $DB->Query("select sell_deal_id from b_crm_potential_deals where buy_deal_id=".$_POST['deal_id']);
  $arrayPotentials = array();
  while ($aPotentials = $rsPotentials->Fetch()){
    $arrayPotentials[] = $aPotentials['sell_deal_id'];
  }
  if (count($arrayPotentials)){
    $sql_string .= " AND b_crm_deal.ID NOT IN(".implode(",",$arrayPotentials).")";
  }
  $sql_string .= " ORDER BY DATE_MODIFY DESC";

  $rsData = $DB->Query($sql_string);
  
  $count = $rsData->SelectedRowsCount();
  //Запись результатов вызова инструмента в таблицу для отчета
  $DB->PrepareFields("b_crm_relevant_search");
  $arFields = array(
    "deal_id" => $_POST['deal_id'],
    "user_id" => $USER->GetID(),
    "search_date" => $DB->GetNowFunction(),
    "result_count" => $count
  );
  $DB->StartTransaction();
  $ID = $DB->Insert("b_crm_relevant_search", $arFields, $err_mess.__LINE__);
  if (strlen($strError)<=0){
    $DB->Commit();
    
  }else {
    $DB->Rollback();
    echo "Ошибка записи результатов поиска, сообщите одминистратору системы!<br>";
  }
  //-----------------------------------------------------------//
  
  $currentUserCount = $DB->Query("select * from b_crm_relevant_search where deal_id=".$_POST['deal_id']." AND user_id=".$USER->GetID())->SelectedRowsCount();
  $allUsersCount = $DB->Query("select * from b_crm_relevant_search where deal_id=".$_POST['deal_id'])->SelectedRowsCount();
  //Вывод статистики использования инструмента
  echo "Запрос по встречным заявкам текущий пользователь произвел ".$currentUserCount." раз. Всего запросов по заявке ".$allUsersCount."<br><br>";
  echo '<div id="resultAdd"></div>';//Результаты переноса заявки в потенциальные сделки
  $rows = 20;
  $pages = ($count % $rows)?intval($count/$rows)+1:$count/$rows;
  for ($i=1;$i<=$pages;$i++){
?>
<div class="page<?=($i == 1)?" active":""?>" id="page<?=$i?>">
  <table>
    <tr>
      <th></th>
      <th width="5%">id</th>
      <th width="25%">Название заявки</th>
      <th width="8%">Цена, руб.</th>
      <th width="25%">Адрес объекта</th>
      <th>N<sub>комнат</sub></th>
      <th>S<sub>общая</sub></th>
      <th>S<sub>кухни</sub></th>
      <th title="Этажность">Эт.</th>
      <th width="15%">Ответственный</th>
    </tr>
<?
    for ($j=1;$j<=$rows;$j++){
      if ($aRes = $rsData->Fetch()){
        $assigned_user = CUser::GetByID($aRes['ASSIGNED_BY_ID'])->Fetch();
        if ($aRes['PROPERTY_210'] == 386) $square = $aRes['PROPERTY_292'];
        else $square = $aRes['PROPERTY_224'];
?>
    <tr id="R<?=$aRes['ID']?>" class="row">
      <td><?=($_POST['assigned_by_id'] == $USER->GetID() || $USER->IsAdmin())?"<a href='javascript:addpotential(".$aRes['ID'].")'><span style='color:green;font-weight: bold'>+</span></a>":""?></td>
      <!--<td><button onclick="addpotential(<?=$aRes['ID']?>)" <?=($_POST['assigned_by_id'] == $USER->GetID() || $USER->IsAdmin())?"":"disabled"?>>+</button></td>-->
      <td><?=$aRes['ID']?></td>
      <td style="text-align: left; padding-left: 5px;" title="<?=$aRes['TITLE']?>"><a href="/crm/deal/show/<?=$aRes['ID']?>/" target="_blank"><?=$aRes['TITLE']?></a></td>
      <td style="text-align: right; padding-right: 5px;" title="<?=($aRes['UF_CRM_58958B5734602'])?$aRes['UF_CRM_58958B5734602']:"цена не указана"?>"><?=($aRes['UF_CRM_58958B5734602'])?$aRes['UF_CRM_58958B5734602']:"<span style='color:red;'>цена не указана</span>"?></td>
      <td style="text-align: left; padding-left: 5px;" title="<?=$aRes['PROPERTY_209']?>"><?=$aRes['PROPERTY_209']?></td>
      <td><?=($aRes['PROPERTY_229'])?intval($aRes['PROPERTY_229']):"-"?></td>
      <td style="text-align: right; padding-right: 5px;"><?=($square)?number_format($square,2):"-"?></td>
      <td style="text-align: right; padding-right: 5px;"><?=($aRes['PROPERTY_226'])?number_format($aRes['PROPERTY_226'],2):"-"?></td>
      <td><?=($aRes['PROPERTY_221'])?$aRes['PROPERTY_221']:"-"?>/<?=($aRes['PROPERTY_222'])?$aRes['PROPERTY_222']:"-"?></td>
      <td><a href="/company/personal/user/<?=$assigned_user['ID']?>/" target="_blank" title="<?=$assigned_user['PERSONAL_PHONE']?>" ><?=$assigned_user['LAST_NAME']." ".$assigned_user['NAME']?></a></td>
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
    <td style="border: 1px solid black;text-align:center;" width="3%"><b>Всего:</b></td>
    <td style="border: 1px solid black;text-align:left;" colspan="9" style="text-align: left; padding-left: 5px;"><b><span id="count"><?=$count?></span></b></td>
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