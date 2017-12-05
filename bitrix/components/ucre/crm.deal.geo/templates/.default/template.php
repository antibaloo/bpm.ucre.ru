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
  var myMap,
      polygon,
      drawButton,
      saveButton,
      canv = document.getElementById('canv'),
      ctx = canv.getContext('2d'),
      line = [],
      startX = 0,
      startY = 0;
  ymaps.ready(init);
  function init () {
    myMap = new ymaps.Map('map', {
      center: [51.779700, 55.116868],
      zoom: 13,
      controls: ['zoomControl', 'typeSelector']
    });
    myMap.controls.add('zoomControl');
    myMap.behaviors.enable('scrollZoom');
    
    drawButton = new ymaps.control.Button("Задать");
    drawButton.events.add('click', function () {
      if (drawButton.data.get("content") == 'Задать'){
        $("#canv").css( "zIndex", 302);
        canv.addEventListener('mousedown', mouseDown);
        drawButton.state.set("enabled",false);
        saveButton.state.set("enabled",false);
      }else if (drawButton.data.get("content") == 'Очистить'){
        $("#polygonCoords").val("");
        $("#canv").css( "zIndex", -1);
        myMap.geoObjects.remove(polygon);
        //Удалить полигон, очистить поле координат
        drawButton.data.set("content","Задать");
      }
    });
    myMap.controls.add(drawButton, {left: 10, top:10});
    drawButton.options.set("selectOnClick",false);
    
    saveButton = new ymaps.control.Button("Сохранить");
    saveButton.events.add('click', function () {
      var data = $("#polygonForm").serialize();
      $.ajax({
        url:'<?=$arResult['COMPONENT_PATH']?>/ajax.php',
        type: "POST",
        dataType: "html",
        data: data,
        success: function (html) {
         },
        error: function (html) {
          $("#polygon").html("Технические неполадки! В ближайшее время все будет исправлено!");
        },
      });
    });
    myMap.controls.add(saveButton, {left: 10+84+10, top:10});
    saveButton.options.set("selectOnClick",false);
    saveButton.options.set("maxWidth", 120);
    
    
    if ($("#polygonCoords").val()!=""){
      drawButton.data.set("content","Очистить");
      polygon = new ymaps.Polygon([JSON.parse($("#polygonCoords").val())], {}, {
        fillColor: '#1092DC',
        strokeColor: '#0000FF',
        opacity: 0.5,
        strokeWidth: 3
      });
      myMap.geoObjects.add(polygon);
      myMap.events.add('sizechange', function () {
        myMap.setBounds(polygon.geometry.getBounds());
        canv.width = $('#map').width();
        canv.height = $('#map').height();
      });
    }else{
      myMap.events.add('sizechange', function () {
        canv.width = $('#map').width();
        canv.height = $('#map').height();
      });
    }
  }
</script>