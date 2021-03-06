<?php
if(!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED!==true) die();
$APPLICATION->SetTitle("Заполните параметры здания");
?>
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.4/css/select2.min.css" rel="stylesheet" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.4/js/select2.min.js"></script>
<script src="https://api-maps.yandex.ru/2.0/?load=package.full&lang=ru-RU"></script>
<form id ="buildingForm" method="POST" action="<?=$_SERVER["REQUEST_URI"]?>">
  <div class="typeWrapper">
    <div class="empty"></div>
    <div class="buildingType">Нежилое здание</div>
    <div class="buildingType">Жилой дом</div>
    <div class="buildingType chosen">Многоквартирный дом</div>
    <div class="empty"></div>
  </div>
  <div class="addressWrapper">
    <div class="empty"></div>
    <div class="label addressBox <?=($arResult['errors']['UF_BUILDING_ADDRESS'])?"fieldError":""?>" title="<?=($arResult['errors']['UF_BUILDING_ADDRESS'])?$arResult['errors']['UF_BUILDING_ADDRESS']:""?>">Адрес здания *</div>
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
    <div class="label paramBox <?=($arResult['errors']['UF_COUNTRY'])?"fieldError":""?>" title="<?=($arResult['errors']['UF_COUNTRY'])?$arResult['errors']['UF_COUNTRY']:""?>">Страна *</div>
    <div class="param paramBox"><input class="block-input" type="search" id="UF_COUNTRY" name="UF_COUNTRY" placeholder="страна" value="<?=$arResult['UF_COUNTRY']?>"></div>
    <div class="label paramBox <?=($arResult['errors']['UF_FED_DISTRICT'])?"fieldError":""?>" title="<?=($arResult['errors']['UF_FED_DISTRICT'])?$arResult['errors']['UF_FED_DISTRICT']:""?>">Федеральный округ *</div>
    <div class="param paramBox"><input class="block-input" type="search" id="UF_FED_DISTRICT" name="UF_FED_DISTRICT" placeholder="федеральный округ" value="<?=$arResult['UF_FED_DISTRICT']?>"></div>
    <div class="empty"></div>
  </div>
  <div class="paramsWrapper">
    <div class="empty"></div>
    <div class="label paramBox <?=($arResult['errors']['UF_PROVINCE'])?"fieldError":""?>" title="<?=($arResult['errors']['UF_PROVINCE'])?$arResult['errors']['UF_PROVINCE']:""?>">Субъект федерации *</div>
    <div class="param paramBox"><input class="block-input" type="search" id="UF_PROVINCE" name="UF_PROVINCE" placeholder="cубъект федерации: область, край, республика" value="<?=$arResult['UF_PROVINCE']?>"></div>
    <div class="label paramBox <?=($arResult['errors']['UF_AREA'])?"fieldError":""?>" title="<?=($arResult['errors']['UF_AREA'])?$arResult['errors']['UF_AREA']:""?>">Район субъекта *</div>
    <div class="param paramBox"><input class="block-input" type="search" id="UF_AREA" name="UF_AREA" placeholder="район населенного пункта" value="<?=$arResult['UF_AREA']?>"></div>
    <div class="empty"></div>
  </div>
  <div class="paramsWrapper">
    <div class="empty"></div>
    <div class="label paramBox <?=($arResult['errors']['UF_LOCALITY'])?"fieldError":""?>" title="<?=($arResult['errors']['UF_LOCALITY'])?$arResult['errors']['UF_LOCALITY']:""?>">Населенный пункт *</div>
    <div class="param paramBox"><input class="block-input" type="search" id="UF_LOCALITY" name="UF_LOCALITY" placeholder="населенный пункт без топонимов" value="<?=$arResult['UF_LOCALITY']?>"></div>
    <div class="label paramBox <?=($arResult['errors']['UF_DISTRICT'])?"fieldError":""?>" title="<?=($arResult['errors']['UF_DISTRICT'])?$arResult['errors']['UF_DISTRICT']:""?>">Район НП</div>
    <div class="param paramBox"><input class="block-input" type="search" id="UF_DISTRICT" name="UF_DISTRICT" placeholder="район населенного пункта" value="<?=$arResult['UF_DISTRICT']?>"></div>
    <div class="empty"></div>
  </div>
  <div class="paramsWrapper">
    <div class="empty"></div>
    <div class="label paramBox <?=($arResult['errors']['UF_STREET'])?"fieldError":""?>" title="<?=($arResult['errors']['UF_STREET'])?$arResult['errors']['UF_STREET']:""?>">Улица *</div>
    <div class="param paramBox"><input class="block-input" type="search" id="UF_STREET" name="UF_STREET" placeholder="улица, проспект, проезд, переулок ..." value="<?=$arResult['UF_STREET']?>"></div>
    <div class="label paramBox <?=($arResult['errors']['UF_HOUSE'])?"fieldError":""?>" title="<?=($arResult['errors']['UF_HOUSE'])?$arResult['errors']['UF_HOUSE']:""?>">№ дома *</div>
    <div class="param paramBox"><input class="block-input" type="search" id="UF_HOUSE" name="UF_HOUSE" placeholder="номер дома одной строкой" value="<?=$arResult['UF_HOUSE']?>"></div>
    <div class="empty"></div>
  </div>
   <div class="paramsWrapper">
    <div class="empty"></div>
    <div class="label paramBox <?=($arResult['errors']['UF_POSTAL'])?"fieldError":""?>" title="<?=($arResult['errors']['UF_POSTAL'])?$arResult['errors']['UF_POSTAL']:""?>">Индекс *</div>
    <div class="param paramBox"><input class="block-input" type="search" id="UF_POSTAL" name="UF_POSTAL" placeholder="индекс" value="<?=$arResult['UF_POSTAL']?>"></div>
    <div class="label paramBox <?=($arResult['errors']['UF_YEAR_BUILT'])?"fieldError":""?>" title="<?=($arResult['errors']['UF_YEAR_BUILT'])?$arResult['errors']['UF_YEAR_BUILT']:""?>">Год постройки</div>
    <div class="param paramBox"><input class="block-input" type="search" id="UF_YEAR_BUILT" name="UF_YEAR_BUILT" placeholder="год постройки" value="<?=$arResult['UF_YEAR_BUILT']?>"></div>
    <div class="empty"></div>
  </div>
  <div class="paramsWrapper">
    <div class="empty"></div>
    <div class="label paramBox <?=($arResult['errors']['UF_LATITUDE'])?"fieldError":""?>" title="<?=($arResult['errors']['UF_LATITUDE'])?$arResult['errors']['UF_LATITUDE']:""?>">Широта *</div>
    <div class="param paramBox"><input class="block-input" type="search" id="UF_LATITUDE" name="UF_LATITUDE" placeholder="широта" value="<?=$arResult['UF_LATITUDE']?>"></div>
    <div class="label paramBox <?=($arResult['errors']['UF_LONGITUDE'])?"fieldError":""?>" title="<?=($arResult['errors']['UF_LONGITUDE'])?$arResult['errors']['UF_LONGITUDE']:""?>">Долгота *</div>
    <div class="param paramBox"><input class="block-input" type="search" id="UF_LONGITUDE" name="UF_LONGITUDE" placeholder="долгота" value="<?=$arResult['UF_LONGITUDE']?>"></div>
    <div class="empty"></div>
  </div>
  <div class="paramsWrapper">
    <div class="empty"></div>
    <div class="label paramBox <?=($arResult['errors']['UF_BUILDING_KAD_NUM'])?"fieldError":""?>" title="<?=($arResult['errors']['UF_BUILDING_KAD_NUM'])?$arResult['errors']['UF_BUILDING_KAD_NUM']:""?>">Кадастровый номер *</div>
    <div class="param paramBox"><input class="block-input" type="search" id="UF_BUILDING_KAD_NUM" name="UF_BUILDING_KAD_NUM" placeholder="кадастровый номер" value="<?=$arResult['UF_BUILDING_KAD_NUM']?>"></div>
    <div class="label paramBox <?=($arResult['errors']['UF_B_SQUARE'])?"fieldError":""?>" title="<?=($arResult['errors']['UF_B_SQUARE'])?$arResult['errors']['UF_B_SQUARE']:""?>">Общая площадь *</div>
    <div class="param paramBox"><input class="block-input" type="search" id="UF_B_SQUARE" name="UF_B_SQUARE" placeholder="общая площадь" value="<?=$arResult['UF_B_SQUARE']?>"></div>
    <div class="empty"></div>
  </div>
  <div class="paramsWrapper">
    <div class="empty"></div>
    <div class="label paramBox <?=($arResult['errors']['UF_PLACEMENT_COUNT'])?"fieldError":""?>" title="<?=($arResult['errors']['UF_PLACEMENT_COUNT'])?$arResult['errors']['UF_PLACEMENT_COUNT']:""?>">Количество помещений *</div>
    <div class="param paramBox"><input class="block-input" type="search" id="UF_PLACEMENT_COUNT" name="UF_PLACEMENT_COUNT" placeholder="количеств опомещений" value="<?=$arResult['UF_PLACEMENT_COUNT']?>"></div>
    <div class="label paramBox <?=($arResult['errors']['UF_LIVE_COUNT'])?"fieldError":""?>" title="<?=($arResult['errors']['UF_LIVE_COUNT'])?$arResult['errors']['UF_LIVE_COUNT']:""?>">из них жилые *</div>
    <div class="param paramBox"><input class="block-input" type="search" id="UF_LIVE_COUNT" name="UF_LIVE_COUNT" placeholder="из них жилые" value="<?=$arResult['UF_LIVE_COUNT']?>"></div>
    <div class="empty"></div>
  </div>
  <div class="paramsWrapper">
    <div class="empty"></div>
    <div class="label paramBox <?=($arResult['errors']['UF_FLOORS_MIN'])?"fieldError":""?>" title="<?=($arResult['errors']['UF_FLOORS_MIN'])?$arResult['errors']['UF_FLOORS_MIN']:""?>">Этажность <sub>min</sub> *</div>
    <div class="param paramBox"><input class="block-input" type="search" id="UF_FLOORS_MIN" name="UF_FLOORS_MIN" placeholder="минимальная этажность" value="<?=$arResult['UF_FLOORS_MIN']?>"></div>
    <div class="label paramBox <?=($arResult['errors']['UF_FLOORS_MAX'])?"fieldError":""?>" title="<?=($arResult['errors']['UF_FLOORS_MAX'])?$arResult['errors']['UF_FLOORS_MAX']:""?>">Этажность <sub>max</sub> *</div>
    <div class="param paramBox"><input class="block-input" type="search" id="UF_FLOORS_MAX" name="UF_FLOORS_MAX" placeholder="максимальная этажность" value="<?=$arResult['UF_FLOORS_MAX']?>"></div>
    <div class="empty"></div>
  </div>
  <div class="paramsWrapper">
    <div class="empty"></div>
    <div class="label paramBox <?=($arResult['errors']['UF_UNDER_FLOORS'])?"fieldError":""?>" title="<?=($arResult['errors']['UF_UNDER_FLOORS'])?$arResult['errors']['UF_UNDER_FLOORS']:""?>">Подземных этажей *</div>
    <div class="param paramBox"><input class="block-input" type="search" id="UF_UNDER_FLOORS" name="UF_UNDER_FLOORS" placeholder="подземных этажей" value="<?=$arResult['UF_UNDER_FLOORS']?>"></div>
    <div class="label paramBox <?=($arResult['errors']['UF_MATERIAL'])?"fieldError":""?>" title="<?=($arResult['errors']['UF_MATERIAL'])?$arResult['errors']['UF_MATERIAL']:""?>">Тип дома *</div>
    <div class="param paramBox">
      <select class="selectList" id="UF_MATERIAL" name="UF_MATERIAL"></select>
    </div>
    <div class="empty"></div>
  </div>
  <div class="paramsWrapper">
    <div class="empty"></div>
    <div class="label paramBox <?=($arResult['errors']['UF_SECTIONS'])?"fieldError":""?>" title="<?=($arResult['errors']['UF_SECTIONS'])?$arResult['errors']['UF_SECTIONS']:""?>">Подъездов *</div>
    <div class="param paramBox"><input class="block-input" type="search" id="UF_SECTIONS" name="UF_SECTIONS" placeholder="количество секций" value="<?=$arResult['UF_SECTIONS']?>"></div>
    <div class="label paramBox <?=($arResult['errors']['UF_ELEVATORS'])?"fieldError":""?>" title="<?=($arResult['errors']['UF_ELEVATORS'])?$arResult['errors']['UF_ELEVATORS']:""?>">Лифтов *</div>
    <div class="param paramBox"><input class="block-input" type="search" id="UF_ELEVATORS" name="UF_ELEVATORS" placeholder="количество лифтов" value="<?=$arResult['UF_ELEVATORS']?>"></div>
    <div class="empty"></div>
  </div>
  <div class="paramsWrapper">
    <div class="empty"></div>
    <div class="label paramBox">Земельный участок *</div>
    <div class="param paramBox"><a href="/townbase/plot/show/<?=$arResult['UF_B_PLOT_ID']?>/" target="_blank"><?=$arResult['UF_PLOT_ADDRESS']?></a></div>
    <div class="label paramBox <?=($arResult['errors']['UF_RS'])?"fieldError":""?>" title="<?=($arResult['errors']['UF_RS'])?$arResult['errors']['UF_RS']:""?>">ЖК</div>
    <div class="param paramBox">
      <select class="selectList" id="UF_RS" name="UF_RS"></select>
    </div>
    <div class="empty"></div>
  </div>
  <input id="ID" name="ID" type="hidden" value="<?=$arResult['ID']?>">
  <input id="UF_BUILDING_TYPE_ID" name="UF_BUILDING_TYPE_ID" type="hidden" value="<?=$arResult['UF_BUILDING_TYPE_ID']?>">
  <input id="UF_B_PLOT_ID" name="UF_B_PLOT_ID" type="hidden" value="<?=$arResult['UF_B_PLOT_ID']?>">
  <input id="ACTION" name="ACTION" type="hidden">
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
  $(document).ready(function() {
    $('#UF_RS').select2({data: <?=$arResult['ZHKS']?>});
    $('#UF_MATERIAL').select2({data: <?=$arResult['MATERIALS']?>});
  });
  
  $(".formButton").click(function (){
    if ($(this).attr("action") == 'cancel'){
      document.location.href = '/townbase/building/list/';
    }else{
      $("#ACTION").val($(this).attr("action"));
      $('#submit').trigger('click');
    }
  });
  $(".searchButton").click(function(){
    if ($("#UF_BUILDING_ADDRESS").val() != ""){
      $.ajax({
        url: "https://geocode-maps.yandex.ru/1.x/",
        type: "GET",
        datatype: "json",
        data:{
          geocode: $("#UF_BUILDING_ADDRESS").val(),
          format: 'json'
        },
        success: function (json) {
          if (json.response.GeoObjectCollection.metaDataProperty.GeocoderResponseMetaData.found > 0){
            var pos = json.response.GeoObjectCollection.featureMember[0].GeoObject.Point.pos.split(" ");
            $("#UF_LATITUDE").val(pos[1]);
            $("#UF_LONGITUDE").val(pos[0]);
            $("#UF_BUILDING_ADDRESS").val(json.response.GeoObjectCollection.featureMember[0].GeoObject.metaDataProperty.GeocoderMetaData.Address.formatted);
            $("#UF_POSTAL").val(json.response.GeoObjectCollection.featureMember[0].GeoObject.metaDataProperty.GeocoderMetaData.Address.postal_code);
            //Заполняем поля компонентов адреса
            json.response.GeoObjectCollection.featureMember[0].GeoObject.metaDataProperty.GeocoderMetaData.Address.Components.forEach(function(el){
              switch (el.kind){
                case 'country':
                  $("#UF_COUNTRY").val(el.name);
                  break;
                case 'province':
                  if (el.name.indexOf("округ")>-1) $("#UF_FED_DISTRICT").val(el.name);
                  else $("#UF_PROVINCE").val(el.name);
                  break;
                case 'area':
                  $("#UF_AREA").val(el.name);
                  break;
                case 'locality':
                  $("#UF_LOCALITY").val(el.name);
                  break;
                case 'district':
                  $("#UF_DISTRICT").val(el.name);
                  break;
                case 'street':
                  $("#UF_STREET").val(el.name);
                  break;
                case 'house':
                  $("#UF_HOUSE").val(el.name);
                  break;
              }
            });
            
            var coords = [pos[1], pos[0]];
            // Если метка уже создана – удалить ее.
            if (myPlacemark) myMap.geoObjects.remove(myPlacemark);
            myPlacemark = new ymaps.Placemark(coords,{
              hintContent: $("#UF_BUILDING_ADDRESS").val(),
              iconContent: $("#UF_BUILDING_ADDRESS").val().slice(0,55)+"..."
            },{
              preset: 'twirl#redStretchyIcon',
              draggable: true
            });
            myMap.geoObjects.add(myPlacemark);
            // Слушаем событие окончания перетаскивания на метке.
            myPlacemark.events.add('dragend', dragEndFunc);
            myMap.setCenter(coords, 16);
          }else{
            $("#UF_BUILDING_ADDRESS").val("");
            $("#UF_BUILDING_ADDRESS").attr("placeholder", "заданный объект не найден");
          }
        },
        error: function (json) {
          console.log("WFT!!!");
        },
      });
    }
  });
    
  var myPlacemark, myMap;//Делаем глобальными для доступности из всех функций
  ymaps.ready(init);
  
  function init() {
    if ($("#UF_LATITUDE").val()>0 && $("#UF_LONGITUDE").val()>0 && $("#UF_BUILDING_ADDRESS").val() != "" ){
      myMap = new ymaps.Map("map", {
        center: [$("#UF_LATITUDE").val(), $("#UF_LONGITUDE").val()], 
        zoom: 16
      });
      myMap.controls.add(new ymaps.control.TypeSelector(['yandex#map', 'yandex#satellite', 'yandex#hybrid']));
      myMap.controls.add('zoomControl');
      myMap.behaviors.enable('scrollZoom');
      myPlacemark = new ymaps.Placemark([$("#UF_LATITUDE").val(), $("#UF_LONGITUDE").val()],{
        hintContent: $("#UF_BUILDING_ADDRESS").val(),
        iconContent: $("#UF_BUILDING_ADDRESS").val().slice(0,55)+"..."
      },{
        preset: 'twirl#redStretchyIcon',
        draggable: true
      });
      myMap.geoObjects.add(myPlacemark);
      // Слушаем событие окончания перетаскивания на метке.
      myPlacemark.events.add('dragend', dragEndFunc);
    }else{
      myMap = new ymaps.Map("map", {
        center: [51.779700, 55.116868], 
        zoom: 13
      });
      myMap.controls.add(new ymaps.control.TypeSelector(['yandex#map', 'yandex#satellite', 'yandex#hybrid']));
      myMap.controls.add('zoomControl');
      myMap.behaviors.enable('scrollZoom');
    }
    myMap.events.add('click', function (e) {
      var coords = e.get('coords');
      if (myPlacemark) myMap.geoObjects.remove(myPlacemark);
      myPlacemark = new ymaps.Placemark(coords,{
        iconContent: "поиск..."
      },{
        preset: 'twirl#redStretchyIcon',
        draggable: true
      });
      myMap.geoObjects.add(myPlacemark);
      myMap.setCenter(coords, 16);
      $("#UF_LATITUDE").val(coords[0]);
      $("#UF_LONGITUDE").val(coords[1]);
      ymaps.geocode(coords, {json: true}).then(function (json) {
         console.log(json);
        $("#UF_BUILDING_ADDRESS").val(json.GeoObjectCollection.featureMember[0].GeoObject.metaDataProperty.GeocoderMetaData.Address.formatted);
        $("#UF_POSTAL").val(json.GeoObjectCollection.featureMember[0].GeoObject.metaDataProperty.GeocoderMetaData.Address.postal_code);
        $("#UF_DISTRICT").val("");
        json.GeoObjectCollection.featureMember[0].GeoObject.metaDataProperty.GeocoderMetaData.Address.Components.forEach(function(el){
          switch (el.kind){
            case 'country':
              $("#UF_COUNTRY").val(el.name);
              break;
            case 'province':
              if (el.name.indexOf("округ")>-1) $("#UF_FED_DISTRICT").val(el.name);
              else $("#UF_PROVINCE").val(el.name);
              break;
            case 'area':
              $("#UF_AREA").val(el.name);
              break;
            case 'locality':
              $("#UF_LOCALITY").val(el.name);
              break;
            case 'district':
              $("#UF_DISTRICT").val(el.name);
              break;
            case 'street':
              $("#UF_STREET").val(el.name);
              break;
            case 'house':
              $("#UF_HOUSE").val(el.name);
              break;
          }
        });
        myPlacemark.properties.set({
          hintContent: $("#UF_BUILDING_ADDRESS").val(),
          iconContent: $("#UF_BUILDING_ADDRESS").val().slice(0,55)+"..."
        });
      });
      // Слушаем событие окончания перетаскивания на метке.
      myPlacemark.events.add('dragend', dragEndFunc);
    });
  }
  function dragEndFunc(){
    myPlacemark.properties.set('iconContent', 'поиск...');
    var coords = myPlacemark.geometry.getCoordinates();
    myMap.setCenter(coords, 16);
    $("#UF_LATITUDE").val(coords[0]);
    $("#UF_LONGITUDE").val(coords[1]);
    ymaps.geocode(coords, {json: true}).then(function (json) {
      $("#UF_BUILDING_ADDRESS").val(json.GeoObjectCollection.featureMember[0].GeoObject.metaDataProperty.GeocoderMetaData.Address.formatted);
      $("#UF_POSTAL").val(json.GeoObjectCollection.featureMember[0].GeoObject.metaDataProperty.GeocoderMetaData.Address.postal_code);
      $("#UF_DISTRICT").val("");
      json.GeoObjectCollection.featureMember[0].GeoObject.metaDataProperty.GeocoderMetaData.Address.Components.forEach(function(el){
        switch (el.kind){
          case 'country':
            $("#UF_COUNTRY").val(el.name);
            break;
          case 'province':
            if (el.name.indexOf("округ")>-1) $("#UF_FED_DISTRICT").val(el.name);
            else $("#UF_PROVINCE").val(el.name);
            break;
          case 'area':
            $("#UF_AREA").val(el.name);
            break;
          case 'locality':
            $("#UF_LOCALITY").val(el.name);
            break;
          case 'district':
            $("#UF_DISTRICT").val(el.name);
            break;
          case 'street':
            $("#UF_STREET").val(el.name);
            break;
          case 'house':
            $("#UF_HOUSE").val(el.name);
            break;
        }        
      });
      myPlacemark.properties.set({
        hintContent: $("#UF_BUILDING_ADDRESS").val(),
        iconContent: $("#UF_BUILDING_ADDRESS").val().slice(0,55)+"..."
      });
    });
  }
</script>