<?php
if(!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED!==true) die();
?>
<table class="crm-offer-info-table crm-offer-main-info-text">
  <tbody>
    <tr><td colspan="4"><div class="crm-offer-title">География поиска: <span id="polygon"><?=($arResult['POLYGON']=="")?"область не задана.":"область задана."?></span></div></td><td><div class="crm-offer-title"><a href="#" onclick="openMap();return false;"><span id="action"><?=($arResult['POLYGON']=="")?"Задать область поиска.":"Смотреть/переопределить область поиска."?></span></a></div></td></tr>
    
  </tbody>
</table>
<form id="polygonForm">
  <input type="hidden" name="deal_id" value="<?=$arResult['DEAL_ID']?>">
  <input type="hidden" name="polygonCoords" id="polygonCoords" value="<?=($arResult['POLYGON']!="")?$arResult['POLYGON']:""?>">
</form>
<div id="geo">
  <canvas id="canv"></canvas>
  <div id="map"></div>
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
    drawButton = new ymaps.control.Button("Задать");
    drawButton.events
    .add('click', function () {
      if (drawButton.data.get("content") == 'Задать'){
        $("#canv").css( "zIndex", 302);
        canv.addEventListener('mousedown', mouseDown);
        drawButton.data.set("content","Отменить");
      }else if (drawButton.data.get("content") == 'Отменить'){
        $("#canv").css( "zIndex", -1);
        canv.removeEventListener('mousedown', mouseDown);
        drawButton.data.set("content","Задать");
      }else if (drawButton.data.get("content") == 'Очистить'){
        saveButton.state.set("enabled",false);
        $("#canv").css( "zIndex", -1);
        myMap.geoObjects.remove(polygon);
        //Удалить полигон, очистить поле координат
        drawButton.data.set("content","Задать");
      }
    });
    myMap.controls.add(drawButton, {left: 10, top:10});
    drawButton.options.set("selectOnClick",false);

    saveButton = new ymaps.control.Button("Сохранить");
    saveButton.events
    .add('click', function () {
      var data = $("#polygonForm").serialize();
      $.ajax({
        url:'<?=$arResult['COMPONENT_PATH']?>/ajax.php',
        type: "POST",
        dataType: "html",
        data: data,
        success: function (html) {
          $("#polygon").html(html);
          if (html == "область сохранена.") $("#action").html("Смотреть/переопределить область поиска.");
         },
        error: function (html) {
          $("#polygon").html("Технические неполадки! В ближайшее время все будет исправлено!");
        },
      });
      $('#geo').hide();
    });
    myMap.controls.add(saveButton, {left: 10+84+10, top:10});
    saveButton.options.set("selectOnClick",false);
    saveButton.state.set("enabled",false);
    
    closeButton = new ymaps.control.Button("Х");
    closeButton.events.add('click', function(){
      $('#geo').hide();
    });
    myMap.controls.add(closeButton, {right: 10, top:10});
    closeButton.options.set("selectOnClick",false);
    if ($("#polygonCoords").val()!=""){
      drawButton.data.set("content","Очистить");
      polygon = new ymaps.Polygon([JSON.parse($("#polygonCoords").val())], {}, {
        fillColor: '#1092DC',
        strokeColor: '#0000FF',
        opacity: 0.5,
        strokeWidth: 3
      });
      myMap.geoObjects.add(polygon);
    }
  }
  var canv = document.getElementById('canv'),
      ctx = canv.getContext('2d'),
      line = [],
      startX = 0,
      startY = 0;
  
  function openMap(){
    $('#geo').show();
    canv.width = document.getElementById('map').offsetWidth;
    canv.height = document.getElementById('map').offsetHeight;
  }
</script>