<?php
if(!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED!==true) die();
$APPLICATION->SetTitle("Зполните параметры нежилого помещения");
echo "<pre>";print_r($arResult);echo "</pre>";
?>
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.4/css/select2.min.css" rel="stylesheet" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.4/js/select2.min.js"></script>
<script src="https://api-maps.yandex.ru/2.0/?load=package.full&lang=ru-RU"></script>
<form id ="placementForm" method="POST" action="<?=$_SERVER["REQUEST_URI"]?>">
  <div class="typeWrapper">
    <div class="empty"></div>
    <div class="placementType chosen">Нежилое помещение</div>
    <div class="placementType">Комната</div>
    <div class="placementType">Квартира</div>
    <div class="empty"></div>
  </div>
  <div class="paramsWrapper">
    <div class="empty"></div>
    <div class="label paramBox">Адрес здания</div>
    <div class="param paramBox"><a href="/townbase/building/show/<?=$arResult['UF_BUILDING_ID']?>/" target="_blank"><?=$arResult['UF_BUILDING_ADDRESS']?></a></div>
    <div class="label paramBox <?=($arResult['errors']['UF_PL_NUMBER'])?"fieldError":""?>" title="<?=($arResult['errors']['UF_PL_NUMBER'])?$arResult['errors']['UF_PL_NUMBER']:""?>">Номер помещения *</div>
    <div class="param paramBox"><input class="block-input" type="search" id="UF_PL_NUMBER" name="UF_PL_NUMBER" placeholder="номер квартиры" value="<?=$arResult['UF_PL_NUMBER']?>"></div>
    <div class="empty"></div>
  </div>
  <div class="mapWrapper">
    <div class="empty"></div>
    <div id="map"></div>
    <div class="empty"></div>
  </div>
  <input id="ID" name="ID" type="hidden" value="<?=$arResult['ID']?>">
  <input id="UF_BUILDING_ID" name="UF_BUILDING_ID" type="hidden" value="<?=$arResult['UF_BUILDING_ID']?>">
  <input id="UF_BUILDING_ADDRESS" type="hidden" value="<?=$arResult['UF_BUILDING_ADDRESS']?>">
  <input id="UF_PLACEMENT_TYPE_ID" name="UF_PLACEMENT_TYPE_ID" type="hidden" value="<?=$arResult['UF_PLACEMENT_TYPE_ID']?>">
  <input id="UF_LIVING_TYPE_ID" name="UF_LIVING_TYPE_ID" type="hidden" value="<?=$arResult['UF_LIVING_TYPE_ID']?>">
  <input id="UF_LATITUDE" type="hidden" value="<?=$arResult['UF_LATITUDE']?>">
  <input id="UF_LONGITUDE" type="hidden" value="<?=$arResult['UF_LONGITUDE']?>">
  <button id="submit" type="submit" style="display:none;"></button>
  <div class="buttonWrapper">
    <div class="empty"></div>
    <div class="empty"></div>
    <div class="formButton" action="save">Сохранить</div>
    <div class="empty"></div>
    <div class="formButton" action="cancel">Отменить</div>
    <div class="empty"></div>
    <div class="empty"></div>
  </div>
</form>
<script>
  $(".formButton").click(function (){
    if ($(this).attr("action") == 'cancel'){
      document.location.href = '/townbase/placement/list/';
    }else{
      $("#ACTION").val($(this).attr("action"));
      $('#submit').trigger('click');
    }
  });
  var myPlacemark, myMap;//Делаем глобальными для доступности из всех функций
  ymaps.ready(init);
  function init() {
    myMap = new ymaps.Map("map", {
      center: [$("#UF_LATITUDE").val(), $("#UF_LONGITUDE").val()], 
      zoom: 16
    });
    //myMap.controls.add('zoomControl');
    //myMap.behaviors.enable('scrollZoom');
    myMap.behaviors.disable('drag');
    myPlacemark = new ymaps.Placemark([$("#UF_LATITUDE").val(), $("#UF_LONGITUDE").val()],{
      hintContent: $("#UF_BUILDING_ADDRESS").val(),
      iconContent: $("#UF_BUILDING_ADDRESS").val().slice(0,55)+"..."
    },{
      preset: 'twirl#redStretchyIcon',
      draggable: false
    });
    myMap.geoObjects.add(myPlacemark);
  }  
</script>