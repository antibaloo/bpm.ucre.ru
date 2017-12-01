<?php
if(!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED!==true) die();
//echo "<pre>";print_r($arResult);echo "</pre>";
?>
<form id="polygonForm">
  <input type="hidden" name="deal_id" value="<?=$arResult['DEAL_ID']?>">
  <input type="hidden" name="polygonCoords" id="polygonCoords" value="<?=($arResult['POLYGON']!="")?$arResult['POLYGON']:""?>">
</form>
<canvas id="canv"></canvas>
<div id="map"></div>
<script src="https://api-maps.yandex.ru/2.1/?load=package.full&lang=ru-RU"></script>
<script src="https://code.jquery.com/jquery-1.8.3.js"></script>
<script src="https://mourner.github.io/simplify-js/simplify.js"></script>
<script>
  ymaps.ready(init);
  var myMap;
  var polygon;
  var drawButton;
  var saveButton;
  var closeButton;
  
  function init () {
    myMap = new ymaps.Map("map", {
      center: [51.779700, 55.116868],
      zoom: 13
    });
    myMap.controls.add('zoomControl');
    myMap.behaviors.enable('scrollZoom');
    
    
    if ($("#polygonCoords").val()!=""){
      //drawButton.data.set("content","Очистить");
      polygon = new ymaps.Polygon([JSON.parse($("#polygonCoords").val())], {}, {
        fillColor: '#1092DC',
        strokeColor: '#0000FF',
        opacity: 0.5,
        strokeWidth: 3
      });
      myMap.geoObjects.add(polygon);
      myMap.setBounds(myMap.geoObjects.getBounds());
    }
  }  
</script>