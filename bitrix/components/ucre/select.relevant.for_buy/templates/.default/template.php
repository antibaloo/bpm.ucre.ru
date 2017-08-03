<link href="/include/custom_css/custom_paging.css?<?=time();?>" rel="stylesheet">
<?php
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED!==true)die();
//echo $arResult['SQL_STRING'];
?>
<br>
<form id="select_relevant_to_buy">
  Рынок поиска: <?=$arResult['SELECT_PARAMS']['MARKET']?>&nbsp;
  Тип объекта: <?=$arResult['SELECT_PARAMS']['TYPE']?>&nbsp;
  N<sub>комнат</sub> от <?=$arResult['SELECT_PARAMS']['ROOMS']?>&nbsp;
  S<sub>общ.</sub> от <?=$arResult['SELECT_PARAMS']['TOTAL_AREA']?>&nbsp;
  Этажи: <?=($arResult['SELECT_PARAMS']['FIRST'])?$arResult['SELECT_PARAMS']['FIRST']:"можно первый"?>&nbsp;<?=($arResult['SELECT_PARAMS']['LAST'])?$arResult['SELECT_PARAMS']['LAST']:"можно последний"?>&nbsp;
  Цена от <?=($arResult['SELECT_PARAMS']['MINPRICE'])?$arResult['SELECT_PARAMS']['MINPRICE']:"-"?> до <?=($arResult['SELECT_PARAMS']['MAXPRICE'])?$arResult['SELECT_PARAMS']['MAXPRICE']:"-"?> &nbsp;
  <input id="submit" type="button" value="Искать">
  
  <input type="hidden" name="sql" value="<?=bin2hex($arResult['SQL_STRING'])?>">
  <input type="hidden" name="deal_id" value="<?=$arResult['ID']?>">
  <input type="hidden" name="assigned_by_id" value="<?=$arResult['ASSIGNED_BY_ID']?>">
</form>

<div id="resultGrid">
<?
  $currentUserCount = $DB->Query("select * from b_crm_relevant_search where deal_id=".$arResult['ID']." AND user_id=".$USER->GetID())->SelectedRowsCount();
  $allUsersCount = $DB->Query("select * from b_crm_relevant_search where deal_id=".$arResult['ID'])->SelectedRowsCount();
  //Вывод статистики использования инструмента
  echo "Запрос по встречным заявкам текущий пользователь произвел ".$currentUserCount." раз. Всего запросов по заявке ".$allUsersCount."<br><br>";
?>  
</div>
<script>
  $("#submit").click(function () {
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