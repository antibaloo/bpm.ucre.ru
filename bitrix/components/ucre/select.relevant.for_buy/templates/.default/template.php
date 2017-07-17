<link href="/include/custom_css/custom_paging.css?<?=time();?>" rel="stylesheet">
<?php
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED!==true)die();
//echo $arResult['SQL_STRING'];
?>
<br>
<form id="select_relevant_to_buy">
  <table style="width:100%;">
    <tr align="center">
      <th>Рынок поиска</th>
      <th>Тип объекта</th>
      <th>N<sub>комнат</sub></th>
      <th>S<sub>общ.</sub></th>
      <th>S<sub>кух.</sub></th>
      <th colspan="2">Этажи</th>
      <th>Цена<sub>от</sub></th>
      <th>Цена<sub>до</sub></th>
    </tr>
    <tr align="center">
      <td><input name="market" type="text" value="<?=$arResult['SELECT_PARAMS']['MARKET']?>" readonly></td>
      <td><input name="type" type="text" value="<?=$arResult['SELECT_PARAMS']['TYPE']?>" readonly></td>
      <td><input name="rooms" type="text" value="<?=$arResult['SELECT_PARAMS']['ROOMS']?>" readonly></td>
      <td><input name="totalarea" type="text" value="<?=$arResult['SELECT_PARAMS']['TOTAL_AREA']?>" readonly></td>
      <td><input name="kitchenarea" type="text" value="<?=$arResult['SELECT_PARAMS']['KITCHEN_AREA']?>" readonly></td>
      <td><input name="nfirsl" type="text" value="<?=$arResult['SELECT_PARAMS']['FIRST']?>" readonly></td>
      <td><input name="nlast" type="text" value="<?=$arResult['SELECT_PARAMS']['LAST']?>" readonly></td>
      <td><input name="miprice" type="text" value="<?=$arResult['SELECT_PARAMS']['MINPRICE']?>" readonly></td>
      <td><input name="maxprice" type="text" value="<?=$arResult['SELECT_PARAMS']['MAXPRICE']?>" readonly></td>
      <td><input id="submit" type="button" value="Искать"></td>
    </tr>
  </table>
  <input type="hidden" name="sql" value="<?=bin2hex($arResult['SQL_STRING'])?>">
  <input type="hidden" name="deal_id" value="<?=$arResult['ID']?>">
  <input type="hidden" name="assigned_by_id" value="<?=$arResult['ASSIGNED_BY_ID']?>">
</form>

<div id="resultGrid">
<?
  $currentUserCount = $DB->Query("select * from b_crm_relevant_search where deal_id=".$arResult['ID']." AND user_id=".$USER->GetID())->SelectedRowsCount();
  $allUsersCount = $DB->Query("select * from b_crm_relevant_search where deal_id=".$arResult['ID'])->SelectedRowsCount();
  //Вывод статистики использования инструмента
  echo "Запрос по встречным заявкам текущий пользователь произвел ".$currentUserCount." раз. Всего запросов по заявке ".$allUsersCount."<hr>";
?>  
</div>
<script>
  $("#submit").click(function () {
    var data = $('#select_relevant_to_buy').serialize();
    $.ajax({
      url: "/bitrix/components/ucre/select.relevant.for_buy/ajax.php",
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
      url: "/bitrix/components/ucre/select.relevant.for_buy/addtopotential.php",
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