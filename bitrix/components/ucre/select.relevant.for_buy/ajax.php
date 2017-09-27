<?php
require_once($_SERVER['DOCUMENT_ROOT'] . "/bitrix/modules/main/include/prolog_before.php");

/*------------------------------------Функции для гео поиска------------------------------------*/
function minCoord($a,$b){
  return ($a<$b)?$a:$b;
}
function maxCoord($a,$b){
  return ($a>$b)?$a:$b;
}
function isInPoly($polygon,$point){
  $i=1;
  $N=count($polygon);
  $isIn=false;
  $p1=$polygon[0];
  $p2;
  for(;$i<=$N;$i++)	{
    $p2 = $polygon[$i % $N];
    if ($point['lon'] > minCoord($p1['lon'],$p2['lon'])){
      if ($point['lon'] <= maxCoord($p1['lon'],$p2['lon'])){
        if ($point['lat'] <= maxCoord($p1['lat'],$p2['lat'])){
          if ($p1['lon'] != $p2['lon']){
            $xinters = ($point['lon']-$p1['lon'])*($p2['lat']-$p1['lat'])/($p2['lon']-$p1['lon'])+$p1['lat'];
            if ($p1['lat'] == $p2['lat'] || $point['lat'] <= $xinters) $isIn=!$isIn;
          }
        }
      }
    }
    $p1 = $p2;
  }
  return $isIn;
}

function makePolyArray($polystring){
  $polystring = str_replace("[[","",$polystring);
  $polystring = str_replace("]]","",$polystring);
  $polyArrayTemp = explode("],[",$polystring);
  $polygonTemp = array();
  foreach ($polyArrayTemp as $point){
    $tempPoint = explode(",",$point);
    $polygonTemp[] = array(
      "lat" => $tempPoint[0],
      "lon" => $tempPoint[1]
    );
  }
  return $polygonTemp;
}
/*--------------------------------------------------------------------------------------------------------------*/

if (strripos ($_SERVER['HTTP_REFERER'], 'bpm.ucre.ru')!==false){
  if ($_POST['market'] == "нет данных") die("Не введены параметры рынка поиска");
  /*Справочник типов балконов*/
  $balkon = array(
    '' => '<span title="нет данных" style="color: red">-</span>',
    '400' => '<span title="2 балкона">2Б</span>',
    '401' => '<span title="2 балкона, 2 лоджии">2Б2Л</span>',
    '402' => '<span title="2 лоджии">2Л</span>',
    '403' => '<span title="3 балкона">3Б</span>',
    '404' => '<span title="3 лоджии">3Л</span>',
    '405' => '<span title="4 лоджии">4Л</span>',
    '406' => '<span title="балкон">Б</span>',
    '407' => '<span title="Балкон, 2 лоджии">Б2Л</span>',
    '408' => '<span title="Балкон, лоджия">БЛ</span>',
    '409' => '<span title="лоджия">Л</span>',
    '410' => '<span title="Нет">нет</span>',
    '411' => '<span title="Эркер">Э</span>',
    '412' => '<span title="Эркер и лоджия">ЭЛ</span>',
   );
  /*Справочник типов домов*/
  $housetype = array(
    '' => '<span title="нет данных" style="color: red">-</span>',
    '426' => '<span title="Блочный">Б</span>',
    '427' => '<span title="Деревянный">Д</span>',
    '428' => '<span title="Кирпичный">К</span>',
    '429' => '<span title="Монолитно-Кирпичный">МК</span>',
    '430' => '<span title="Монолитный">М</span>',
    '431' => '<span title="Панельный">П</span>',
    '432' => '<span title="Сталинский">С</span>',
    '433' => '<span title="Элитный">Э</span>'
  );
  /*Справочник материалов стен*/
  $wallstype = array(
    '' => '<span title="нет данных" style="color: red">-</span>',
    '413' => '<span title="блок">Бл</span>',
    '414' => '<span title="бревно">Бр</span>',
    '415' => '<span title="брус">Б-с</span>',
    '416' => '<span title="иное">И</span>',
    '417' => '<span title="каркасно-щитовой">КЩ</span>',
    '418' => '<span title="кирпич">К</span>',
    '419' => '<span title="монолит">М</span>',
    '420' => '<span title="нет">нет</span>',
    '421' => '<span title="оцилиндрованное бревно">ОБ</span>',
    '422' => '<span title="панели">П</span>',
    '423' => '<span title="пеноблок">ПБ</span>',
    '424' => '<span title="сэндвич">С</span>',
    '425' => '<span title="шлакоблок">Ш</span>'
  );
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
    "result_count" => $count,
    "search_params" => "'".hex2bin($_POST['searchParams'])."'"
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
      <th width="30%">Название заявки</th>
      <th width="8%">Цена, руб.</th>
      <th width="20%">Адрес объекта</th>
      <th>N<sub>комнат</sub></th>
      <th>S<sub>общая</sub></th>
      <th>S<sub>кухни</sub></th>
      <?if ($_POST['rotype'] == 'Комната' || $_POST['rotype'] == 'Квартира'|| $_POST['rotype'] == 'Таунхаус'){?>
      <th title="Тип балкона">Б</th>
      <?}?>
      <?if ($_POST['rotype'] != 'Участок' && $_POST['rotype'] != 'Коммерческая'){?>
      <th title="<?=($_POST['rotype'] == 'Комната' || $_POST['rotype'] == 'Квартира')?"Тип дома":"Материал стен"?>"><?=($_POST['rotype'] == 'Комната' || $_POST['rotype'] == 'Квартира')?"Т":"М"?></th>
      <?}?>
      <th title="Этажность">Эт.</th>
      <th width="15%">Ответственный</th>
    </tr>
<?
    for ($j=1;$j<=$rows;$j++){
      if ($aRes = $rsData->Fetch()){
        /*--Проверка условий попадания в географическую область поиска--*/
        
        if ($_POST['searchGeo']!=""){
          if ($aRes['PROPERTY_298'] && $aRes['PROPERTY_299']){
            if (!isInPoly(makePolyArray($_POST['searchGeo']),array("lat" =>$aRes['PROPERTY_298'], "lon" => $aRes['PROPERTY_299']))){
              continue;
            }
          }
        }
        /*-------------------------------------------------------------*/
        $assigned_user = CUser::GetByID($aRes['ASSIGNED_BY_ID'])->Fetch();
        if ($aRes['PROPERTY_210'] == 386) $square = $aRes['PROPERTY_292'];
        else $square = $aRes['PROPERTY_224'];
        $shortAddress = $aRes['PROPERTY_217'].", ".$aRes['PROPERTY_218']." (".$aRes['PROPERTY_215'];
        if ($aRes['PROPERTY_216']) $shortAddress.=", ".$aRes['PROPERTY_216'];
        $shortAddress.=")";
?>
    <tr id="R<?=$aRes['ID']?>" class="row">
      <td><?=($_POST['assigned_by_id'] == $USER->GetID() || $USER->IsAdmin())?"<a href='javascript:addpotential(".$aRes['ID'].")'><span style='color:green;font-weight: bold'>+</span></a>":""?></td>
      <td><?=$aRes['ID']?></td>
      <td style="text-align: left; padding-left: 5px;" title="<?=$aRes['TITLE']?>"><a href="/crm/deal/show/<?=$aRes['ID']?>/" target="_blank"><?=$aRes['TITLE']?></a></td>
      <td style="text-align: right; padding-right: 5px;" title="<?=($aRes['UF_CRM_58958B5734602'])?number_format($aRes['UF_CRM_58958B5734602'],0,"."," "):"цена не указана"?>"><?=($aRes['UF_CRM_58958B5734602'])?number_format($aRes['UF_CRM_58958B5734602'],0,"."," "):"<span style='color:red;'>цена не указана</span>"?></td>
      <td style="text-align: left; padding-left: 5px;" title="<?=$aRes['PROPERTY_209']?>"><?=$shortAddress?></td>
      <td><?=($aRes['PROPERTY_229'])?intval($aRes['PROPERTY_229']):"-"?></td>
      <td style="text-align: right; padding-right: 5px;"><?=($square)?number_format($square,2):"-"?></td>
      <td style="text-align: right; padding-right: 5px;"><?=($aRes['PROPERTY_226'])?number_format($aRes['PROPERTY_226'],2):"-"?></td>
      <?if ($aRes['PROPERTY_210'] == 381 || $aRes['PROPERTY_210'] == 382){?>
      <td><?=$balkon[$aRes['PROPERTY_241']]?></td>
      <?}?>
      <?if ($aRes['PROPERTY_210'] != 386 && $aRes['PROPERTY_210'] != 387){?>
      <td><?=($aRes['PROPERTY_210'] ==381 || $aRes['PROPERTY_210']  == 382)?$housetype[$aRes['PROPERTY_243']]:$wallstype[$aRes['PROPERTY_242']]?></td>
      <?}?>
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