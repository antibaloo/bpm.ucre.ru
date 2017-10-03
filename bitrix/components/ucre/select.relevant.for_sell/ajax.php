<?php
require_once($_SERVER['DOCUMENT_ROOT'] . "/bitrix/modules/main/include/prolog_before.php");
CModule::IncludeModule("crm");
if (strripos ($_SERVER['HTTP_REFERER'], 'bpm.ucre.ru')!==false){
  $params = unserialize($_POST['params']);
  $type_s =array(
    "1" =>"комната",
    "2" => "квартира",
    "3" => "дом",
    "4" => "таунхаус",
    "5" => "дача",
    "6" => "участок",
    "7" => "коммерческий",
  );
  //Фильтр по уже имеющимся в потенциальных
  $rsPotentials = $DB->Query("select buy_deal_id from b_crm_potential_deals where sell_deal_id=".$_POST['deal_id']);
  $arrayPotentials = array();
  while ($aPotentials = $rsPotentials->Fetch()){
    $arrayPotentials[] = $aPotentials['buy_deal_id'];
  }
  //Фильтр по рынку поиска
  $market = "";
  if ($params['MARKET'] == "вторичка") $market = array(827);
  if ($params['MARKET'] == "первичка") $market = array(828);
  //Фильтр по типу недвижимости
  switch ($params['TYPE']){
    case "комната":
      $type = 1;
      break;
    case "квартира":
      $type = 2;
      break;
    case "дом":
      $type = 3;
      break;
    case "таунхаус":
      $type = 4;
      break;
    case "дача":
      $type = 5;
      break;
    case "участок":
      $type = 6;
      break;
    case "коммерческий":
      $type = 7;
      break;
    default:
      $type = "";
      break;
  }
  $arFilter = array(
    "!ID" => $arrayPotentials, 
      "CATEGORY_ID" => 2, 
      "STAGE_ID" => "C2:PROPOSAL", 
      "UF_CRM_5895BC940ED3F" => $market, 
      "UF_CRM_58CFC7CDAAB96" =>$type,
      "<=UF_CRM_58958B576448C" =>$params['PRICE'],
      ">=UF_CRM_58958B5751841" =>$params['PRICE'],
      "<=UF_CRM_58958B529E628" =>$params['ROOMS'],
      "<=UF_CRM_58958B52F2BAC" =>$params['KITCHEN_AREA'],
  );
  //Фильтр по исключению этажей
  $arFilter[] = array('LOGIC' => 'OR',array('UF_CRM_1506501917' => 0),array('<=UF_CRM_1506501917' => $params['FLOOR'])); //Поле "Этаж от" в заявке на покупку равно 0 или меньше или равно этажу 
  $arFilter[] = array('LOGIC' => 'OR',array('UF_CRM_1506501950' => 0),array('>=UF_CRM_1506501950' => $params['FLOOR'])); //Поле "Этаж до" в заявке на покупку равно 0 или больше или равно этажу 

  if ($params['FLOOR'] == $params['FLOORS'])  $arFilter['UF_CRM_1502433005'] = 0;//не последний
  if ($params['TYPE'] == 'комната') $arFilter['<=UF_CRM_58958B52BA439'] = $params['ROOM_AREA'];
  elseif ($params['TYPE'] == 'участок') $arFilter['<=UF_CRM_58958B52BA439'] = $params['PLOT_AREA'];
  else $arFilter['<=UF_CRM_58958B52BA439'] = $params['TOTAL_AREA'];
  //print_r($params);
  //print_r($arFilter);
  $rsDeal = CCrmDeal::GetList(
    array("DATE_MODIFY" => "DESC"),
    $arFilter,
    array("ID", "TITLE", "ASSIGNED_BY_ID", "UF_CRM_5895BC940ED3F","UF_CRM_58CFC7CDAAB96","UF_CRM_58958B576448C","UF_CRM_58958B5751841","UF_CRM_58958B529E628","UF_CRM_58958B52BA439","UF_CRM_58958B52F2BAC","UF_CRM_58958B51B667E","UF_CRM_1502432955","UF_CRM_1502433005")
  );
  $count = $rsDeal->SelectedRowsCount();
  
  //Запись результатов вызова инструмента в таблицу для отчета
  $DB->PrepareFields("b_crm_relevant_search");
  $arFields = array(
    "deal_id" => $_POST['deal_id'],
    "user_id" => $USER->GetID(),
    "search_date" => $DB->GetNowFunction(),
    "result_count" => $count,
    "search_params" => "'".$_POST['params']."'"
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
  $rows = 20;
  $pages = ($count % $rows)?intval($count/$rows)+1:$count/$rows;
  for ($i=1;$i<=$pages;$i++){
?>
<div class="page<?=($i == 1)?" active":""?>" id="page<?=$i?>">
  <table>
    <tr>
      <th>id</th>
      <th width="30%">Название заявки</th>
      <th>Рынок поиска</th>
      <th>Тип объекта</th>
      <th width="8%">Цена от</th>
      <th width="8%">Цена до</th>
      <th>N<sub>комнат</sub> от</th>
      <th>S<sub>общая</sub> от</th>
      <th>S<sub>кухни</sub> от</th>
      <th>Не первый</th>
      <th>Не последний</th>
      <th width="15%">Ответственный</th>
    </tr>
<?    
    for ($j=1;$j<=$rows;$j++){
      if ($mainDeal = $rsDeal->Fetch()){
        $assigned_user = CUser::GetByID($mainDeal['ASSIGNED_BY_ID'])->Fetch();
        $marketS = array();
        foreach ($mainDeal['UF_CRM_5895BC940ED3F'] as $mark){
          if ($mark == 827) $marketS[] = "В";
          if ($mark == 828) $marketS[] = "П";
        }
?>
    <tr class="row">
      <td><?=$mainDeal['ID']?></td>
      <td style="text-align: left; padding-left: 5px;"><a href="/crm/deal/show/<?=$mainDeal['ID']?>/" target="_blank"><?=$mainDeal['TITLE']?></a></td>
      <td><?=implode(",", $marketS)?></td>
      <td><?=$type_s[$mainDeal['UF_CRM_58CFC7CDAAB96']]?></td>
      <td><?=($mainDeal['UF_CRM_58958B576448C'])?$mainDeal['UF_CRM_58958B576448C']:"..."?></td>
      <td><?=($mainDeal['UF_CRM_58958B5751841'])?$mainDeal['UF_CRM_58958B5751841']:"..."?></td>
      <td><?=$mainDeal['UF_CRM_58958B529E628']?></td>
      <td><?=$mainDeal['UF_CRM_58958B52BA439']?></td>
      <td><?=$mainDeal['UF_CRM_58958B52F2BAC']?></td>
      <td><?=($mainDeal["UF_CRM_1502432955"])?"Х":""?></td>
      <td><?=($mainDeal["UF_CRM_1502433005"])?"Х":""?></td>
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
<table style="width:100%;border: 1px solid black;border-collapse: collapse;margin-bottom:15px;font-size: 14px;">
  <tr>
    <td style="border: 1px solid black;text-align:center;" width="4%"><b>Всего:</b></td>
    <td style="border: 1px solid black;text-align:left;" style="text-align: left; padding-left: 5px;"><b><span id="count"><?=$count?></span></b></td>
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