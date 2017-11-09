<?php
if(!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED!==true) die();
$APPLICATION->SetTitle("Выберите тип здания");
?>
<form id ="buildingForm" method="POST" action="<?=$_SERVER["REQUEST_URI"]?>">
  <div class="gridWrapper">
    <div class="empty"></div>
    <div class="buildingType" action="type" typeId="1">Нежилое здание</div>
    <div class="buildingType" action="type" typeId="2">Жилой дом</div>
    <div class="buildingType" action="type" typeId="3">Многоквартирный дом</div>
    <div class="empty"></div>
  </div>
  <input id="UF_BUILDING_TYPE_ID" name="UF_BUILDING_TYPE_ID" type="hidden">
  <input id="ACTION" name="ACTION" type="hidden">
  <button id="submit" type="submit" style="display:none;"></button>
</form>
<a href="/townbase/building/list/">Отменить</a>
<?echo "<pre>";print_r($arResult);echo "</pre>";?>
<script>
  $(".buildingType").click(function (){
    $("#UF_BUILDING_TYPE_ID").val($(this).attr("typeId"));
    $("#ACTION").val($(this).attr("action"));
    $('#submit').trigger('click');
  });
</script>