<?php
if(!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED!==true) die();
$APPLICATION->SetTitle("Заполните параметры участка");
?>
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.4/css/select2.min.css" rel="stylesheet" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.4/js/select2.min.js"></script>
<script src="https://api-maps.yandex.ru/2.0/?load=package.full&lang=ru-RU"></script>
<form id ="plotForm" method="POST" action="<?=$_SERVER["REQUEST_URI"]?>">
  <div class="addressWrapper">
    <div class="empty"></div>
    <div class="label addressBox <?=($arResult['errors']['UF_PLOT_ADDRESS'])?"fieldError":""?>" title="<?=($arResult['errors']['UF_PLOT_ADDRESS'])?$arResult['errors']['UF_PLOT_ADDRESS']:""?>">Адрес участка *</div>
    <div class="param addressBox">
      <input class="block-input" type="search" id="UF_PLOT_ADDRESS" name="UF_PLOT_ADDRESS" placeholder="Введите адрес участка" value="<?=$arResult['UF_PLOT_ADDRESS']?>">
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
    <div class="label paramBox <?=($arResult['errors']['UF_LATITUDE'])?"fieldError":""?>" title="<?=($arResult['errors']['UF_LATITUDE'])?$arResult['errors']['UF_LATITUDE']:""?>">Широта *</div>
    <div class="param paramBox"><input class="block-input" type="search" id="UF_LATITUDE" name="UF_LATITUDE" placeholder="широта" value="<?=$arResult['UF_LATITUDE']?>"></div>
    <div class="label paramBox <?=($arResult['errors']['UF_LONGITUDE'])?"fieldError":""?>" title="<?=($arResult['errors']['UF_LONGITUDE'])?$arResult['errors']['UF_LONGITUDE']:""?>">Долгота *</div>
    <div class="param paramBox"><input class="block-input" type="search" id="UF_LONGITUDE" name="UF_LONGITUDE" placeholder="долгота" value="<?=$arResult['UF_LONGITUDE']?>"></div>
    <div class="empty"></div>
  </div>
  <div class="paramsWrapper">
    <div class="empty"></div>
    <div class="label paramBox <?=($arResult['errors']['UF_PLOT_KAD_NUM'])?"fieldError":""?>" title="<?=($arResult['errors']['UF_PLOT_KAD_NUM'])?$arResult['errors']['UF_PLOT_KAD_NUM']:""?>">Кадастровый номер *</div>
    <div class="param paramBox"><input class="block-input" type="search" id="UF_PLOT_KAD_NUM" name="UF_PLOT_KAD_NUM" placeholder="кадастровый номер" value="<?=$arResult['UF_PLOT_KAD_NUM']?>"></div>
    <div class="label paramBox <?=($arResult['errors']['UF_P_SQUARE'])?"fieldError":""?>" title="<?=($arResult['errors']['UF_P_SQUARE'])?$arResult['errors']['UF_P_SQUARE']:""?>">Общая площадь *</div>
    <div class="param paramBox"><input class="block-input" type="search" id="UF_P_SQUARE" name="UF_P_SQUARE" placeholder="общая площадь" value="<?=$arResult['UF_P_SQUARE']?>"></div>
    <div class="empty"></div>
  </div>
  <div class="paramsWrapper">
    <div class="empty"></div>
    <div class="label paramBox">Категория земель</div>
    <div class="param paramBox"><input class="block-input" type="search" id="UF_PLOT_TYPE_SHORT" placeholder="категория земли" value="<?=$arResult['UF_PLOT_TYPE_SHORT']?>" readonly></div>
    <div class="empty"></div>
    <div class="empty"></div>
    <div class="empty"></div>
  </div>
  <input id="ID" name="ID" type="hidden" value="<?=$arResult['ID']?>">
  <input id="UF_PLOT_TYPE_ID" name="UF_PLOT_TYPE_ID" type="hidden" value="<?=$arResult['UF_PLOT_TYPE_ID']?>">
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
<?//echo "<pre>";print_r($arResult);echo "</pre>"?>
<?
$APPLICATION->IncludeComponent(
		'ucre:plot.list',
		'',
		array(
      'NO_MENU' => true,
      'FILTER' => array("ID" => $arResult['duplicates']),
			'ON_PAGE' => 20,
			'AJAX_MODE' => 'Y',
			'AJAX_OPTION_SHADOW' => 'Y',
			'AJAX_OPTION_JUMP' => 'N'
		),
		$component
	);
?>
<script>
  $(".formButton").click(function (){
    if ($(this).attr("action") == 'cancel'){
      document.location.href = '/townbase/plot/list/';
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
          geocode: $("#UF_PLOT_ADDRESS").val(),
          format: 'json'
        },
        success: function (json) {
          if (json.response.GeoObjectCollection.metaDataProperty.GeocoderResponseMetaData.found > 0){
            var pos = json.response.GeoObjectCollection.featureMember[0].GeoObject.Point.pos.split(" ");
            $("#UF_LATITUDE").val(pos[1]);
            $("#UF_LONGITUDE").val(pos[0]);
            $("#UF_PLOT_ADDRESS").val(json.response.GeoObjectCollection.featureMember[0].GeoObject.metaDataProperty.GeocoderMetaData.Address.formatted);
            var coords = [pos[1], pos[0]];
            getRosreestInfo(coords);//Инфа из Росреестра по участки нах в точке с координатами
            // Если метка уже создана – удалить ее.
            if (myPlacemark) myMap.geoObjects.remove(myPlacemark);
            myPlacemark = new ymaps.Placemark(coords,{
              hintContent: $("#UF_PLOT_ADDRESS").val(),
              iconContent: $("#UF_PLOT_ADDRESS").val().slice(0,55)+"..."
            },{
              preset: 'twirl#redStretchyIcon',
              draggable: true
            });
            myMap.geoObjects.add(myPlacemark);
            // Слушаем событие окончания перетаскивания на метке.
            myPlacemark.events.add('dragend', dragEndFunc);
            myMap.setCenter(coords, 16);
          }else{
            $("#UF_PLOT_ADDRESS").val("");
            $("#UF_PLOT_ADDRESS").attr("placeholder", "заданный объект не найден");
          }
        },
        error: function (json) {
          console.log("WFT!!!");
        },
      });
    }
  });
  
  var myPlacemark, myMap, rectangle, selectedPlot;//Делаем глобальными для доступности из всех функций
  var PlotTypeListByCode = JSON.parse('<?=$arResult['PlotTypeListByCode']?>');

  //console.log(PlotTypeListByCode);
  ymaps.ready(init);
  
  function init() {
    if ($("#UF_LATITUDE").val()>0 && $("#UF_LONGITUDE").val()>0 && $("#UF_PLOT_ADDRESS").val() != "" ){
      myMap = new ymaps.Map("map", {
        center: [$("#UF_LATITUDE").val(), $("#UF_LONGITUDE").val()], 
        zoom: 16
      });
      myMap.controls.add('zoomControl');
      myMap.behaviors.enable('scrollZoom');
      
      getRosreestInfo([$("#UF_LATITUDE").val(), $("#UF_LONGITUDE").val()]);//Инфа из Росреестра по участку находящемуся в точке с координатами
      
      myPlacemark = new ymaps.Placemark([$("#UF_LATITUDE").val(), $("#UF_LONGITUDE").val()],{
        hintContent: $("#UF_PLOT_ADDRESS").val(),
        iconContent: $("#UF_PLOT_ADDRESS").val().slice(0,55)+"..."
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
      myMap.controls.add('zoomControl');
      myMap.behaviors.enable('scrollZoom');
    }
    imgLayer = new ymaps.Layer(imgUrlTemplate, {tileTransparent: true});
    myMap.layers.add(imgLayer);
    
    myMap.events.add('click', function (e) {
      var coords = e.get('coords');
      getRosreestInfo(coords);//Инфа из Росреестра по участку находящемуся в точке с координатами
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
    getRosreestInfo(coords);//Инфа из Росреестра по участки нах в точке с координатами
  }
  function getRosreestInfo(coords){
    $.ajax({
      url: "https://pkk5.rosreestr.ru/api/features/1",
      type: "GET",
      datatype: "json",
      data:{
        text: coords[0]+' '+coords[1],
        tolerance: 2,
        limit: 11
      },
      success: function (json) {
        if (json.features.length){
          //console.log(json);
          id = json.features[0].attrs.id;
          bbox = myMap.getBounds();
          //console.log(bbox);
          
          
          //Если прямоугольник уже есть, удаляем
          if (rectangle) myMap.geoObjects.remove(rectangle);
          // Создаем прямоугольник на основе границы карты. 
          
          rectangle = new ymaps.Rectangle(myMap.getBounds(), {}, {
            interactivityModel: 'default#transparent',//Для прозрачности для событий карты
            separateContainer: true,
            outline: false,
            fillOpacity: 0.7,
            fillImageHref: 'https://pkk5.rosreestr.ru/arcgis/rest/services/Cadastre/CadastreSelected/MapServer/export?dpi=96&transparent=true&format=png32&bbox='+bbox[0][1]+","+bbox[0][0]+","+bbox[1][1]+","+bbox[1][0]+'&size='+$("#map").width()+","+$("#map").height()+'&bboxSR=4326&imageSR=102100&layers=show%3A6%2C7&layerDefs=%7B"6":"ID%20=%20%27'+id+'%27","7":"ID%20=%20%27'+id+'%27"%7D&f=image'      
          });
          // Добавляем прямоугольник на карту.
          myMap.geoObjects.add(rectangle);
          
          $.ajax({
            url: "https://pkk5.rosreestr.ru/api/features/1/"+id,
            datatype: "json",
            type: "GET",
            success: function(json) {
              $("#UF_PLOT_ADDRESS").val(json.feature.attrs.address);
              $("#UF_P_SQUARE").val(json.feature.attrs.area_value);
              $("#UF_PLOT_KAD_NUM").val(json.feature.attrs.cn);
              $("#UF_PLOT_TYPE_ID").val(PlotTypeListByCode[json.feature.attrs.category_type].ID);
              $("#UF_PLOT_TYPE_SHORT").val(PlotTypeListByCode[json.feature.attrs.category_type].UF_PLOT_TYPE_SHORT);
              myPlacemark.properties.set({
                hintContent: $("#UF_PLOT_ADDRESS").val(),
                iconContent: $("#UF_PLOT_ADDRESS").val().slice(0,55)+"..."
              });
              //console.log(json.feature.attrs); 
            },
            error: function (){
              console.log("Error in pkk5 id request");
            }
          });
          /*
          $.ajax({
          url: "/ajax/getRequest.php",
          type: "POST",
          data: {
          url: "https://rosreestr.ru/api/online/fir_object/"+id
          },
          datatype: "json",
          success: function(json) {
          //console.log(JSON.parse(json)); 
          },
          error: function (){
          console.log("Error!");
          }
          });*/
        }else{
          $("#UF_PLOT_ADDRESS").val("Участок не найден, попробуйте еще раз!");
          myPlacemark.properties.set({
            hintContent: "Попробуйте еще раз!",
            iconContent: "Участок не найден!"
          });
        }
      },
      error: function (json) {
        console.log("WFT!!!");
      },
    });
  }
  function imgUrlTemplate (tileNumber,tileZoom){
    var leftBottomPixel = new ymaps.geometry.Point([tileNumber[0]*256,tileNumber[1]*256+256]),
        rightTopPixel = new ymaps.geometry.Point([tileNumber[0]*256+256,tileNumber[1]*256]);
    var leftBottomGeo = myMap.options.get('projection').fromGlobalPixels(leftBottomPixel.getCoordinates(), tileZoom),
        rightTopGeo = myMap.options.get('projection').fromGlobalPixels(rightTopPixel.getCoordinates(), tileZoom);
    var lBbox = leftBottomGeo[1]+","+leftBottomGeo[0]+","+rightTopGeo[1]+","+rightTopGeo[0];
    return 'https://pkk5.rosreestr.ru/arcgis/rest/services/Cadastre/Cadastre/MapServer/export?bbox='+lBbox+'&bboxSR=4326&layers=&layerDefs=&size=256%2C256&imageSR=102100&format=png&transparent=true&dpi=96&f=image';
  }
</script>