<?php
if(!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED!==true) die();
?>
<table class="crm-offer-info-table crm-offer-main-info-text">
  <tbody>
    <tr><td colspan="4"><div class="crm-offer-title">География поиска: не определенена.</div></td><td><div class="crm-offer-title"><a href="#" onclick="openMap();return false;">Определить</a></div></td></tr>
  </tbody>
</table>

<div id="geo">
  <canvas id="canv"></canvas>
  <div id="map"></div>

  <div id="controls">
    <div id="close"><a href="#"  onclick="$('#geo').hide();return false;">X</a></div>
  </div>
</div>

<script src="https://api-maps.yandex.ru/2.0/?load=package.full&lang=ru-RU"></script>
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
    drawButton = new ymaps.control.Button("Выделить");
    drawButton.events
    .add('click', function () {
      if (drawButton.data.get("content") == 'Выделить'){
        $("#canv").css( "zIndex", 302);
        canv.addEventListener('mousedown', mouseDown);
        drawButton.data.set("content","Отменить");
      }else if (drawButton.data.get("content") == 'Отменить'){
        $("#canv").css( "zIndex", -1);
        canv.removeEventListener('mousedown', mouseDown);
        drawButton.data.set("content","Выделить");
      }else if (drawButton.data.get("content") == 'Очистить'){
        $("#canv").css( "zIndex", -1);
        myMap.geoObjects.remove(polygon);
        //Удалить полигон, очистить поле координат
        drawButton.data.set("content","Выделить");
      }
      console.log('Щёлк'); console.log(drawButton.data.get("content"))
    });
    myMap.controls.add(drawButton, {float: 'none', position: {left: '5px'}  });
    drawButton.options.set("selectOnClick",false);
    /*
    saveButton = new ymaps.control.Button("Сохранить");
    myMap.controls.add(saveButton, {float: 'none', position: {left: '50%'} });
    closeButton = new ymaps.control.Button("Закрыть");
    myMap.controls.add(closeButton, {float: 'none', position: {right: '5px'} });
    console.log(closeButton);*/
  }
  var canv = document.getElementById('canv'),
      ctx = canv.getContext('2d'),
      line = [],
      startX = 0,
      startY = 0;
  function openMap(){
    $('#geo').show();
    /*
    canv.width = document.getElementById('map').offsetWidth;
    canv.height = document.getElementById('map').offsetHeight;*/
    canv.width = $("body").width();
    canv.height = $("body").height();
  }
</script>