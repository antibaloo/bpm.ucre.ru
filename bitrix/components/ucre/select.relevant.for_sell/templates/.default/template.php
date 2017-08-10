<link href="/include/custom_css/custom_paging.css?<?=time();?>" rel="stylesheet">
<?php
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED!==true)die();

?>
<br>
<?if ($arResult['NO_OBJECT']){?>
<h2>
  Без связанного объекта поиск встречных заявок невозможен
</h2>
<?}else{?>
<form id="select_relevant_to_sell">
  Параметры поиск заданы связанным объектом недвижимости <input id="submit" type="button" value="Искать">
  <input type="hidden" name="params" value='<?=serialize($arResult['SELECT_PARAMS'])?>'>
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
<?}?>
<script>
  $("#submit").click(function () {
    var data = $('#select_relevant_to_sell').serialize();
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