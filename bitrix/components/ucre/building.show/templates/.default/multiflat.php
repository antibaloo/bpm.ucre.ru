<?php
if(!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED!==true) die();
$APPLICATION->SetTitle("Карточка многоквартирного дома");
?>
<script src="https://api-maps.yandex.ru/2.0/?load=package.full&lang=ru-RU"></script>
<div class="typeWrapper">
  <div class="empty"></div>
  <div class="buildingType">Нежилое здание</div>
  <div class="buildingType">Жилой дом</div>
  <div class="buildingType chosen">Многоквартирный дом</div>
  <div class="empty"></div>
</div>
<div class="addressWrapper">
  <div class="empty"></div>
  <div class="label addressBox">Адрес здания</div>
  <div class="param addressBox"><?=$arResult['DATA']['UF_BUILDING_ADDRESS']?></div>
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
  <div class="label paramBox">Страна</div>
  <div class="param paramBox"><?=$arResult['DATA']['UF_COUNTRY']?></div>
  <div class="label paramBox">Федеральный округ</div>
  <div class="param paramBox"><?=$arResult['DATA']['UF_FED_DISTRICT']?></div>
  <div class="empty"></div>
</div>
<div class="paramsWrapper">
  <div class="empty"></div>
  <div class="label paramBox">Субъект федерации</div>
  <div class="param paramBox"><?=$arResult['DATA']['UF_PROVINCE']?></div>
  <div class="label paramBox">Район субъекта</div>
  <div class="param paramBox"><?=$arResult['DATA']['UF_AREA']?></div>
  <div class="empty"></div>
</div>
<div class="paramsWrapper">
  <div class="empty"></div>
  <div class="label paramBox">Населенный пункт</div>
  <div class="param paramBox"><?=$arResult['DATA']['UF_LOCALITY']?></div>
  <div class="label paramBox">Район НП</div>
  <div class="param paramBox"><?=$arResult['DATA']['UF_DISTRICT']?></div>
  <div class="empty"></div>
</div>
<div class="paramsWrapper">
  <div class="empty"></div>
  <div class="label paramBox">Улица</div>
  <div class="param paramBox"><?=$arResult['DATA']['UF_STREET']?></div>
  <div class="label paramBox">№ дома</div>
  <div class="param paramBox"><?=$arResult['DATA']['UF_HOUSE']?></div>
  <div class="empty"></div>
</div>
<div class="paramsWrapper">
  <div class="empty"></div>
  <div class="label paramBox ">Индекс</div>
  <div class="param paramBox"><?=$arResult['DATA']['UF_POSTAL']?></div>
  <div class="label paramBox">Год постройки</div>
  <div class="param paramBox"><?=$arResult['DATA']['UF_YEAR_BUILT']?></div>
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
  <div class="param paramBox"><?=$arResult['DATA']['UF_BUILDING_KAD_NUM']?></div>
  <div class="label paramBox">Общая площадь</div>
  <div class="param paramBox"><?=$arResult['DATA']['UF_B_SQUARE']?></div>
  <div class="empty"></div>
</div>
<div class="paramsWrapper">
  <div class="empty"></div>
  <div class="label paramBox">Количество помещений</div>
  <div class="param paramBox"><?=$arResult['DATA']['UF_PLACEMENT_COUNT']?></div>
  <div class="label paramBox">из них жилые</div>
  <div class="param paramBox"><?=$arResult['DATA']['UF_LIVE_COUNT']?></div>
  <div class="empty"></div>
</div>
<div class="paramsWrapper">
  <div class="empty"></div>
  <div class="label paramBox">Этажность <sub>min</sub></div>
  <div class="param paramBox"><?=$arResult['DATA']['UF_FLOORS_MIN']?></div>
  <div class="label paramBox">Этажность <sub>max</sub></div>
  <div class="param paramBox"><?=$arResult['DATA']['UF_FLOORS_MAX']?></div>
  <div class="empty"></div>
</div>
<div class="paramsWrapper">
  <div class="empty"></div>
  <div class="label paramBox">Подземных этажей</div>
  <div class="param paramBox"><?=$arResult['DATA']['UF_UNDER_FLOORS']?></div>
  <div class="label paramBox">Тип дома</div>
  <div class="param paramBox"><?=$arResult['DATA']['MATERIAL_NAME']?></div>
  <div class="empty"></div>
</div>
<div class="paramsWrapper">
  <div class="empty"></div>
  <div class="label paramBox">Подъездов</div>
  <div class="param paramBox"><?=$arResult['DATA']['UF_SECTIONS']?></div>
  <div class="label paramBox">Лифтов</div>
  <div class="param paramBox"><?=$arResult['DATA']['UF_ELEVATORS']?></div>
  <div class="empty"></div>
</div>
<div class="paramsWrapper">
  <div class="empty"></div>
  <div class="label paramBox">Земельный участок</div>
  <div class="param paramBox"><a href="/townbase/plot/show/<?=$arResult['DATA']['UF_B_PLOT_ID']?>/" target="_blank"><?=$arResult['DATA']['PLOT_NAME']?></a></div>
  <div class="label paramBox">ЖК</div>
  <div class="param paramBox"><a href="/townbase/complex/show/<?=$arResult['DATA']['UF_RS']?>/" target="_blank"><?=$arResult['DATA']['RS_NAME']?></a></div>
  <div class="empty"></div>
</div>
<div class="buttonWrapper">
  <div class="empty"></div>
  <div class="empty"></div>
  <div class="formButton" href="/townbase/building/edit/<?=$arResult['DATA']['ID']?>/">Редактировать</div>
  <div class="formButton" href="/townbase/building/chess/<?=$arResult['DATA']['ID']?>/">Шахматка</div>
  <div class="formButton" href="/townbase/building/list/">к списку</div>
  <div class="empty"></div>
  <div class="empty"></div>
</div>
<?echo "<pre>";print_r($arResult);echo "</pre>";?>
<script>
    $(".formButton").click(function (){
      document.location.href = $(this).attr("href");
    });
  ymaps.ready(init);
  function init() {
    var myPlacemark, myMap;
    myMap = new ymaps.Map("map", {
      center: [<?=$arResult['DATA']['UF_LATITUDE']?>, <?=$arResult['DATA']['UF_LONGITUDE']?>], 
      zoom: 16
    });
    //myMap.controls.add('zoomControl');
    //myMap.behaviors.enable('scrollZoom');
    myMap.behaviors.disable('drag');
    myPlacemark = new ymaps.Placemark([<?=$arResult['DATA']['UF_LATITUDE']?>, <?=$arResult['DATA']['UF_LONGITUDE']?>],{
      hintContent: '<?=$arResult['DATA']['UF_BUILDING_ADDRESS']?>',
      iconContent: '<?=$arResult['DATA']['UF_BUILDING_ADDRESS']?>'
    },{
      preset: 'twirl#redStretchyIcon',
      draggable: false
    });
    myMap.geoObjects.add(myPlacemark);
  }
</script>