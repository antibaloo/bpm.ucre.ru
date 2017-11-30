<?php
if(!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED!==true) die();
$APPLICATION->SetTitle("Выберите тип помещения");
echo "<pre>";print_r($arResult);echo "</pre>";
?>
<form id ="placementForm" method="POST" action="<?=$_SERVER["REQUEST_URI"]?>">
  <div class="typeWrapper">
    <div class="empty"></div>
    <div class="placementType toChoose" action="type" typeId="1">Нежилое помещение</div>
    <div class="placementType toChoose" action="type" typeId="2" livingTypeId="1">Комната</div>
    <div class="placementType toChoose" action="type" typeId="2" livingTypeId="1">Квартира</div>
    <div class="empty"></div>
  </div>
  <input id="ID" name="ID" type="hidden" value="<?=$arResult['ID']?>">
  <input id="UF_BUILDING_ID" name="UF_BUILDING_ID" type="hidden" value="<?=$arResult['UF_BUILDING_ID']?>">
  <input id="UF_PLACEMENT_TYPE_ID" name="UF_PLACEMENT_TYPE_ID" type="hidden" value="<?=$arResult['UF_PLACEMENT_TYPE_ID']?>">
  <input id="UF_LIVING_TYPE_ID" name="UF_LIVING_TYPE_ID" type="hidden" value="<?=$arResult['UF_LIVING_TYPE_ID']?>">
  <button id="submit" type="submit" style="display:none;"></button>
  <div class="buttonWrapper">
    <div class="empty"></div>
    <div class="empty"></div>
    <div class="empty"></div>
    <div class="empty"></div>
    <div class="empty"></div>
    <div class="empty"></div>
    <div class="formButton" action="cancel">Отменить</div>
  </div>
</form>
<script>
  $(".formButton").click(function (){
      document.location.href = '/townbase/placement/list/';
  });
  $(".placementType").click(function (){
    $("#UF_PLACEMENT_TYPE_ID").val($(this).attr("typeId"));
    $("#UF_LIVING_TYPE_ID").val($(this).attr("livingTypeId"));
    $('#submit').trigger('click');
  });
</script>