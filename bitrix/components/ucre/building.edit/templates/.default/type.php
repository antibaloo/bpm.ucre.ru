<?php
if(!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED!==true) die();
$APPLICATION->SetTitle("Выберите тип здания");
?>
<form id ="buildingForm" method="POST" action="<?=$_SERVER["REQUEST_URI"]?>">
  <div class="typeWrapper">
    <div class="empty"></div>
    <div class="buildingType toChoose" action="type" typeId="1">Нежилое здание</div>
    <div class="buildingType toChoose" action="type" typeId="2">Жилой дом</div>
    <div class="buildingType toChoose" action="type" typeId="3">Многоквартирный дом</div>
    <div class="empty"></div>
  </div>
  <input id="ID" name="ID" type="hidden" value="<?=$arResult['ID']?>">
  <input id="UF_BUILDING_TYPE_ID" name="UF_BUILDING_TYPE_ID" type="hidden" value="<?=$arResult['UF_BUILDING_TYPE_ID']?>">
  <input id="ACTION" name="ACTION" type="hidden">
  <button id="submit" type="submit" style="display:none;"></button>
  <div class="buttonWrapper">
    <div class="empty"></div>
    <div class="empty"></div>
    <div class="empty"></div>
    <div class="empty"></div>
    <div class="empty"></div>
    <div class="empty"></div>
    <div class="empty"></div>
    <div class="formButton" action="cancel">Отменить</div>
  </div>
</form>
<?echo "<pre>";print_r($arResult);echo "</pre>";?>
<script>
  $(".formButton").click(function (){
      document.location.href = '/townbase/building/list/';
  });
  $(".buildingType").click(function (){
    $("#UF_BUILDING_TYPE_ID").val($(this).attr("typeId"));
    $("#ACTION").val($(this).attr("action"));
    $('#submit').trigger('click');
  });
</script>