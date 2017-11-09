<?php
if(!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED!==true) die();
$APPLICATION->SetTitle("Заполните параметры здания");
?>
<script src="https://api-maps.yandex.ru/2.0/?load=package.full&lang=ru-RU"></script>
<form id ="buildingForm" method="POST" action="<?=$_SERVER["REQUEST_URI"]?>">
  <div class="typeWrapper">
    <div class="empty"></div>
    <div class="buildingType" action="type" typeId="1">Нежилое здание</div>
    <div class="buildingType chosen" action="type" typeId="2">Жилой дом</div>
    <div class="buildingType" action="type" typeId="3">Многоквартирный дом</div>
    <div class="empty"></div>
  </div>
  <div class="addressWrapper">
    <div class="empty"></div>
    <div class="label addressBox">Адрес здания</div>
    <div class="param addressBox">
      <input class="block-input" type="search" id="UF_BUILDING_ADDRESS" name="UF_BUILDING_ADDRESS" placeholder="Введите адрес здания" value="<?=$arResult['UF_BUILDING_ADDRESS']?>">
    </div>
    <div class="searchButton addressBox">Искать</div>
    <div class="empty"></div>
  </div>
  <div class="mapWrapper">
    <div class="empty"></div>
    <div id="map"></div>
    <div class="empty"></div>
  </div>
  <div class="paramsWrapper">
    <div class="empty"></div>
    <div class="label paramBox">Страна</div>
    <div class="param paramBox"><input class="block-input" type="search" id="UF_COUNTRY" name="UF_COUNTRY" placeholder="страна" value="<?=$arResult['UF_COUNTRY']?>"></div>
    <div class="label paramBox">Федеральный округ</div>
    <div class="param paramBox"><input class="block-input" type="search" id="UF_FED_DISTRICT" name="UF_FED_DISTRICT" placeholder="федеральный округ" value="<?=$arResult['UF_FED_DISTRICT']?>"></div>
    <div class="empty"></div>
  </div>
  <div class="paramsWrapper">
    <div class="empty"></div>
    <div class="label paramBox">Субъект федерации</div>
    <div class="param paramBox"><input class="block-input" type="search" id="UF_PROVINCE" name="UF_PROVINCE" placeholder="cубъект федерации: область, край, республика" value="<?=$arResult['UF_PROVINCE']?>"></div>
    <div class="label paramBox">Район субъекта</div>
    <div class="param paramBox"><input class="block-input" type="search" id="UF_AREA" name="UF_AREA" placeholder="район населенного пункта" value="<?=$arResult['UF_AREA']?>"></div>
    <div class="empty"></div>
  </div>
  <div class="paramsWrapper">
    <div class="empty"></div>
    <div class="label paramBox">Населенный пункт</div>
    <div class="param paramBox"><input class="block-input" type="search" id="UF_LOCALITY" name="UF_LOCALITY" placeholder="населенный пункт без топонимов" value="<?=$arResult['UF_LOCALITY']?>"></div>
    <div class="label paramBox">Район НП</div>
    <div class="param paramBox"><input class="block-input" type="search" id="UF_DISTRICT" name="UF_DISTRICT" placeholder="район населенного пункта" value="<?=$arResult['UF_DISTRICT']?>"></div>
    <div class="empty"></div>
  </div>
  <div class="paramsWrapper">
    <div class="empty"></div>
    <div class="label paramBox">Улица</div>
    <div class="param paramBox"><input class="block-input" type="search" id="UF_STREET" name="UF_STREET" placeholder="улица, проспект, проезд, переулок ..." value="<?=$arResult['UF_STREET']?>"></div>
    <div class="label paramBox">№ дома</div>
    <div class="param paramBox"><input class="block-input" type="search" id="UF_HOUSE" name="UF_HOUSE" placeholder="номер дома одной строкой" value="<?=$arResult['UF_HOUSE']?>"></div>
    <div class="empty"></div>
  </div>
   <div class="paramsWrapper">
    <div class="empty"></div>
    <div class="label paramBox">Индекс</div>
    <div class="param paramBox"><input class="block-input" type="search" id="UF_POSTAL" name="UF_POSTAL" placeholder="индекс" value="<?=$arResult['UF_POSTAL']?>"></div>
    <div class="empty"></div>
    <div class="empty"></div>
    <div class="empty"></div>
  </div>
  <div class="paramsWrapper">
    <div class="empty"></div>
    <div class="label paramBox">Широта</div>
    <div class="param paramBox"><input class="block-input" type="search" id="UF_LATITUDE" name="UF_LATITUDE" placeholder="широта" value="<?=$arResult['UF_LATITUDE']?>"></div>
    <div class="label paramBox">Долгота</div>
    <div class="param paramBox"><input class="block-input" type="search" id="UF_LONGITUDE" name="UF_LONGITUDE" placeholder="долгота" value="<?=$arResult['UF_LONGITUDE']?>"></div>
    <div class="empty"></div>
  </div>
  <input id="UF_BUILDING_TYPE_ID" name="UF_BUILDING_TYPE_ID" type="hidden" value="<?=$arResult['UF_BUILDING_TYPE_ID']?>">
  <input id="ACTION" name="ACTION" type="hidden">
  <button id="submit" type="submit" style="display:none;"></button>
</form>
<a href="/townbase/building/list/">Отменить</a>
<?echo "<pre>";print_r($arResult);echo "</pre>";?>
<script>
  $(".buildingType").click(function (){
    if ($(this).attr("typeId")!=$("#UF_BUILDING_TYPE_ID").val()){
      $("#UF_BUILDING_TYPE_ID").val($(this).attr("typeId"));
      $("#ACTION").val($(this).attr("action"));
      $('#submit').trigger('click');
    }
  });
  $(".searchButton").click(function(){
    if ($("#UF_BUILDING_ADDRESS").val() != ""){
      
    }
  });
  var myPlacemark, myMap;
  ymaps.ready(init);
  
  function init() {
    myMap = new ymaps.Map("map", {
      center: [51.779700, 55.116868], 
      zoom: 14
    });
    myMap.controls.add('zoomControl');
    myMap.behaviors.enable('scrollZoom');
  }
</script>