<?php
if(!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED!==true) die();
$APPLICATION->SetTitle("Карточка участка");
?>
<script src="https://api-maps.yandex.ru/2.0/?load=package.full&lang=ru-RU"></script>
<div class="addressWrapper">
  <div class="empty"></div>
  <div class="label addressBox">Адрес участка</div>
  <div class="param addressBox"><?=$arResult['DATA']['UF_PLOT_ADDRESS']?></div>
  <div class="empty"></div>
  <div class="empty"></div>
</div>
<div class="mapWrapper">
  <div class="empty"></div>
  <div id="map"></div>
  <div class="empty"></div>
</div>
<div class="paramsWrapper">
  <div class="empty"></div>
  <div class="label paramBox">Широта</div>
  <div class="param paramBox"><?=$arResult['DATA']['UF_LATITUDE']?></div>
  <div class="label paramBox">Долгота</div>
  <div class="param paramBox"><?=$arResult['DATA']['UF_LONGITUDE']?></div>
  <div class="empty"></div>
</div>
<div class="paramsWrapper">
  <div class="empty"></div>
  <div class="label paramBox">Кадастровый номер</div>
  <div class="param paramBox"><?=$arResult['DATA']['UF_PLOT_KAD_NUM']?></div>
  <div class="label paramBox">Общая площадь</div>
  <div class="param paramBox"><?=$arResult['DATA']['UF_P_SQUARE']?></div>
  <div class="empty"></div>
</div>
<div class="paramsWrapper">
  <div class="empty"></div>
  <div class="label paramBox">Категория земель</div>
  <div class="param paramBox"><?=$arResult['DATA']['UF_PLOT_TYPE_SHORT']?></div>
  <div class="empty"></div>
  <div class="empty"></div>
  <div class="empty"></div>
</div>
<div class="buttonWrapper">
  <div class="empty"></div>
  <div class="empty"></div>
  <div class="formButton" href="/townbase/plot/edit/<?=$arResult['DATA']['ID']?>/">Редактировать</div>
  <div class="empty"></div>
  <div class="formButton" href="/townbase/plot/list/">к списку</div>
  <div class="empty"></div>
  <div class="empty"></div>
</div>
<script>
  $(".formButton").click(function (){
    document.location.href = $(this).attr("href");
  });
  ymaps.ready(init);
  
  var myPlacemark, myMap;
  function init() {
    myMap = new ymaps.Map("map", {
      center: [<?=$arResult['DATA']['UF_LATITUDE']?>, <?=$arResult['DATA']['UF_LONGITUDE']?>], 
      zoom: 16
    });
    //myMap.controls.add('zoomControl');
    //myMap.behaviors.enable('scrollZoom');
    myMap.behaviors.disable('drag');
    myPlacemark = new ymaps.Placemark([<?=$arResult['DATA']['UF_LATITUDE']?>, <?=$arResult['DATA']['UF_LONGITUDE']?>],{
      hintContent: '<?=$arResult['DATA']['UF_PLOT_ADDRESS']?>',
      iconContent: '<?=$arResult['DATA']['UF_PLOT_ADDRESS']?>'
    },{
      preset: 'twirl#redStretchyIcon',
      draggable: false
    });
    imgLayer = new ymaps.Layer(imgUrlTemplate, {tileTransparent: true});
    myMap.layers.add(imgLayer);
    
    myMap.geoObjects.add(myPlacemark);
    bbox = myMap.getBounds();
    
    /*Кадастровый номер ----> id*/
    id_array = '<?=$arResult['DATA']['UF_PLOT_KAD_NUM']?>'.split(":");
    id_array.forEach(function(currentValue,index, array){
      array[index] = parseInt(currentValue, 10);
    });
    id = id_array.join(':');
    
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