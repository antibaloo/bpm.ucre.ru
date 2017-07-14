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
    </tr>
  </table>
  <input type="hidden" name="sql" value="<?=bin2hex($arResult['SQL_STRING'])?>">
</form>
<input id="submit" type="button" value="Искать">
<div id="resultGrid">
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