<link href="/include/custom_css/custom_paging.css?<?=time();?>" rel="stylesheet">
<?php
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED!==true)die();
//echo $arResult['SQL_STRING'];
?>
<div style="background-color:white; padding: 5px;">
  <form id="select_relevant_to_buy">
    <table style="width:100%;border-collapse: collapse;margin-bottom:15px;">
      <tr style="border: 1px solid lightgrey">
        <td style="text-align:right;width:25%"><b>Рынок поиска:</b></td>
        <td style="text-align:left;width:25%"><?=$arResult['SELECT_PARAMS']['MARKET']?></td>
        <td style="text-align:right;width:25%"><b>Тип объекта:</b></td>
        <td style="text-align:left;width:25%"><?=$arResult['SELECT_PARAMS']['TYPE']?></td>
      </tr>
      <tr style="border: 1px solid lightgrey">
        <td style="text-align:right;width:25%"><b>N<sub>комнат</sub> от:</b></td>
        <td style="text-align:left;width:25%"><?=$arResult['SELECT_PARAMS']['ROOMS']?></td>
        <td style="text-align:right;width:25%"><b>S<sub>общ.</sub> от:</b></td>
        <td style="text-align:left;width:25%"><?=$arResult['SELECT_PARAMS']['TOTAL_AREA']?></td>
      </tr>
      <tr style="border: 1px solid lightgrey">
        <td style="text-align:right;width:25%"><b>S<sub>кух.</sub> от:</b></td>
        <td style="text-align:left;width:25%"><?=$arResult['SELECT_PARAMS']['KITCHEN_AREA']?></td>
        <td style="text-align:right;width:25%"><b>Нужен балкон:</b></td>
        <td style="text-align:left;width:25%"><?=$arResult['SELECT_PARAMS']['TYPEBALKON'] ?></td>
      </tr>
      <tr style="border: 1px solid lightgrey">
        <td style="text-align:right;width:25%"><b>Тип дома:</b></td>
        <td style="text-align:left;width:25%"><?=$arResult['SELECT_PARAMS']['TYPEHOUSE']?></td>
        <td style="text-align:right;width:25%"><b>Материал стен:</b></td>
        <td style="text-align:left;width:25%"><?=$arResult['SELECT_PARAMS']['TYPEWALLS'] ?></td>
      </tr>
      <tr style="border: 1px solid lightgrey">
        <td style="text-align:right;width:25%"><b>Желаемый этаж:</b></td>
        <td style="text-align:left;width:25%"><?="с ".$arResult['SELECT_PARAMS']['FLOOR_FROM']." по ".$arResult['SELECT_PARAMS']['FLOOR_TO']?></td>
        <td style="text-align:right;width:25%"><b>Не последний:</b></td>
        <td style="text-align:left;width:25%"><?=($arResult['SELECT_PARAMS']['LAST'])?$arResult['SELECT_PARAMS']['LAST']:"можно последний"?></td>
      </tr>
      <tr style="border: 1px solid lightgrey">
        <td style="text-align:right;width:25%"><b>Цена от:</b></td>
        <td style="text-align:left;width:25%"><?=$arResult['SELECT_PARAMS']['MINPRICE']?></td>
        <td style="text-align:right;width:25%"><b>Цена до:</b></td>
        <td style="text-align:left;width:25%"><?=$arResult['SELECT_PARAMS']['MAXPRICE']?></td>
      </tr>
      <tr style="border: 1px solid lightgrey">
        <td style="text-align:right;width:25%"><b>Населенный пункт:</b></td>
        <td style="text-align:left;width:25%"><?=$arResult['SELECT_PARAMS']['CITY']?></td>
        <td style="text-align:right;width:25%"><b>Район:</b></td>
        <td style="text-align:left;width:25%"><?=$arResult['SELECT_PARAMS']['LOCALITY']?></td>
      </tr>
      <tr style="border: 1px solid lightgrey">
        <td style="text-align:right;width:25%"><b>Улицы поиска:</b></td>
        <td colspan="3" style="text-align:left;width:75%"><?=$arResult['SELECT_PARAMS']['STREETS']?></td>
      </tr>
    </table>
    <center><input name="groupbyassigned" type="radio" value="no" checked> Не группировать по ответственным <input name="groupbyassigned" type="radio" value="yes"> Группировать по ответственным </center>
    <br>
    <center><input id="submit" type="button" value="Искать"></center>
    
    <input type="hidden" name="sql" value="<?=bin2hex($arResult['SQL_STRING'])?>">
    <input type="hidden" name="deal_id" value="<?=$arResult['ID']?>">
    <input type="hidden" name="assigned_by_id" value="<?=$arResult['ASSIGNED_BY_ID']?>">
    <input type="hidden" name="rotype" value="<?=$arResult['SELECT_PARAMS']['TYPE']?>">
    <input type="hidden" name="searchGeo" value="<?=$arResult['SELECT_PARAMS']['SEARCHGEO']?>">
    <input type="hidden" name="searchParams" value="<?=bin2hex(serialize($arResult['SELECT_PARAMS']))?>">
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
</div>
<script>
  $("#submit").click(function () {
console.log("1");
    var data = $('#select_relevant_to_buy').serialize();
    $.ajax({
      url: "<?=$arResult['COMPONENT_PATH']?>/ajax.php",
      type: "POST",
      data: data,
      dataType: "text",
      success: function (html) {
        $("#resultGrid").html(html);
      },
      error: function (html) {
        $("#resultGrid").html("Технические неполадки! В ближайшее время все будет исправлено!");
      },
    });
  });
  function addpotential(id){
    $('#R'+id).remove();
    $('#count').text($('.row').length);
    $.ajax({
      url: "<?=$arResult['COMPONENT_PATH']?>/addtopotential.php",
      type: "POST",
      datatype: "html",
      data:{
        buy_deal_id: <?=$arResult['ID']?>,
        sell_deal_id:id
      },
      success: function (html) {
        $("#resultAdd").html(html);
      },
      error: function (html) {
        $("#resultAdd").html("Технические неполадки! В ближайшее время все будет исправлено!");
      },
    });
  }
  function set_active(object){
    if(!object.classList.contains('active')){
      var el = document.getElementById("page"+object.innerHTML);
      var a_page = document.getElementsByClassName("page active");
      var a_pages = document.getElementsByClassName("pages active");
      a_page[0].classList.remove('active');
      a_pages[0].classList.remove('active');
      el.classList.add('active');
      object.classList.add('active');
    }
  }
</script>