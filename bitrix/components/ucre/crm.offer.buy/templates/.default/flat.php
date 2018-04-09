<?php
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED!==true) die();
?>
<pre>
<?//print_r($arResult);
//var_dump($arResult['PARAMS']['GEO_USE']);
?>
</pre>
<div class="offerForm">
  <form id="crm_offer_buy">
    <input name="ID" type="hidden" value="<?=$arResult['PARAMS']['ID']?>">
    <div class="gridWrapper">
      <div class="gridTitle">Тип объекта</div>
      <div class="gridValue">
        <select name="UF_CRM_58CFC7CDAAB96">
          <option value="">(выберите тип объекта)</option>
          <option value="1" <?=($arResult['PARAMS']['UF_CRM_58CFC7CDAAB96'] == 1)?"selected":""?>>Комната</option>
          <option value="2" <?=($arResult['PARAMS']['UF_CRM_58CFC7CDAAB96'] == 2)?"selected":""?>>Квартира</option>
          <option value="3" <?=($arResult['PARAMS']['UF_CRM_58CFC7CDAAB96'] == 3)?"selected":""?>>Дом</option>
          <option value="4" <?=($arResult['PARAMS']['UF_CRM_58CFC7CDAAB96'] == 4)?"selected":""?>>Таунхаус</option>
          <option value="5" <?=($arResult['PARAMS']['UF_CRM_58CFC7CDAAB96'] == 5)?"selected":""?>>Дача</option>
          <option value="6" <?=($arResult['PARAMS']['UF_CRM_58CFC7CDAAB96'] == 6)?"selected":""?>>Участок</option>
          <option value="7" <?=($arResult['PARAMS']['UF_CRM_58CFC7CDAAB96'] == 7)?"selected":""?>>Коммерческий</option>
        </select>
      </div>
      <div class="gridTitle">Рынок поиска</div>
      <div class="gridValue">
        Вторичка <input type="checkbox" value="827" name="UF_CRM_5895BC940ED3F[]" <?=(count($arResult['PARAMS']['UF_CRM_5895BC940ED3F']) == 2 || $arResult['PARAMS']['UF_CRM_5895BC940ED3F'][0] == 827)?"checked":""?>>
        Первичка <input type="checkbox" value="828" name="UF_CRM_5895BC940ED3F[]" <?=(count($arResult['PARAMS']['UF_CRM_5895BC940ED3F']) == 2 || $arResult['PARAMS']['UF_CRM_5895BC940ED3F'][0] == 828)?"checked":""?>>
      </div>
      <div class="gridTitle">Кол-во комнат</div>
      <div class="gridValue"><input name ="UF_CRM_58958B529E628" type="number" min="1" max="10" value="<?=$arResult['PARAMS']['UF_CRM_58958B529E628']?>"></div>
      <div class="gridTitle">Тип дома</div>
      <div class="gridValue">
        <select name="UF_CRM_58958B5207D0C" >
          <option value="">не выбрано</option>
          <option value="760" <?=($arResult['PARAMS']['UF_CRM_58958B5207D0C'] == 760)?"selected":""?>>Блочный</option>
          <option value="757" <?=($arResult['PARAMS']['UF_CRM_58958B5207D0C'] == 757)?"selected":""?>>Кирпичный</option>
          <option value="758" <?=($arResult['PARAMS']['UF_CRM_58958B5207D0C'] == 758)?"selected":""?>>Монолитный</option>
          <option value="2011" <?=($arResult['PARAMS']['UF_CRM_58958B5207D0C'] == 2011)?"selected":""?>>Монолитно-кирпичный</option>
          <option value="756" <?=($arResult['PARAMS']['UF_CRM_58958B5207D0C'] == 756)?"selected":""?>>Панельный</option>
          <option value="759" <?=($arResult['PARAMS']['UF_CRM_58958B5207D0C'] == 759)?"selected":""?>>Деревянный</option>
        </select>
      </div>
      <div class="gridTitle">S <sub>общ.</sub> от</div>
      <div class="gridValue"><input name ="UF_CRM_58958B52BA439" type="number" min="1" value="<?=$arResult['PARAMS']['UF_CRM_58958B52BA439']?>"></div>
      <div class="gridTitle">S <sub>кух.</sub> от</div>
      <div class="gridValue"><input name ="UF_CRM_58958B52F2BAC" type="number" min="1" value="<?=$arResult['PARAMS']['UF_CRM_58958B52F2BAC']?>"></div>
      <div class="gridTitle">Этаж от</div>
      <div class="gridValue"><input name ="UF_CRM_1506501917" type="number" min="1" value="<?=$arResult['PARAMS']['UF_CRM_1506501917']?>"></div>
      <div class="gridTitle">Этаж до</div>
      <div class="gridValue"><input name ="UF_CRM_1506501950" type="number" min="1" value="<?=$arResult['PARAMS']['UF_CRM_1506501950']?>"></div>
      <div class="gridTitle">Этажность от</div>
      <div class="gridValue"><input name ="UF_CRM_1522901904" type="number" min="1" value="<?=$arResult['PARAMS']['UF_CRM_1522901904']?>"></div>
      <div class="gridTitle">Этажность до</div>
      <div class="gridValue"><input name ="UF_CRM_1522901921" type="number" min="1" value="<?=$arResult['PARAMS']['UF_CRM_1522901921']?>"></div>
      <div class="gridTitle">Не последний</div>
      <div class="gridValue"><input type="checkbox" name="UF_CRM_1521541289" value="1" <?=($arResult['PARAMS']['UF_CRM_1521541289'])?"checked":""?>></div>
      <div class="gridTitle">Есть балкон</div>
      <div class="gridValue"><input type="checkbox" name="UF_CRM_58958B532A119" value="1" <?=($arResult['PARAMS']['UF_CRM_58958B532A119'])?"checked":""?>></div>
      <div class="gridTitle">Цена от</div>
      <div class="gridValue"><input name ="UF_CRM_58958B576448C" type="number" min="100000" step="50000" value="<?=$arResult['PARAMS']['UF_CRM_58958B576448C']?>"></div>
      <div class="gridTitle">Цена до</div>
      <div class="gridValue"><input name ="UF_CRM_58958B5751841" type="number" min="100000" step="50000" value="<?=$arResult['PARAMS']['UF_CRM_58958B5751841']?>"></div>
      <div class="gridTitle">Область поиска</div>
      <div class="gridValue">
        <input type="checkbox" id="GEO_USE" name="GEO_USE" value="1" <?=($arResult['PARAMS']['GEO_USE'] === "1")?"checked":""?>>
        <input type="hidden" id= "GEO" name="GEO" value="<?=$arResult['PARAMS']['GEO']?>">
      </div>
      <div class="gridTitle"></div>
      <div class="gridValue"></div>
    </div>
    <input type="hidden" name="OFFER_AJAX_ID" value="<?=$arResult['OFFER_AJAX_ID']?>"><!-- ID блока обертки шаблона -->
  </form>
  <center><a href="#" id="offerBuySearch" class="ui-btn ui-btn-bg-primary">Искать</a></center>
</div>
<div id="offerMap" class="offerMap">
</div>

<div class="resultGridWrapper">
  <div class="resultGridHeader"></div>
  <div class="resultGridHeader">id</div>
  <div class="resultGridHeader">Название заявки</div>
  <div class="resultGridHeader">Цена</div>
  <div class="resultGridHeader">Адрес объекта</div>
  <div class="resultGridHeader" title="Количество комнат">N<sub>к</sub></div>
  <div class="resultGridHeader" title="Общая площадь">S<sub>о</sub></div>
  <div class="resultGridHeader" title="Площадь кухни">S<sub>к</sub></div>
  <div class="resultGridHeader">Б</div>
  <div class="resultGridHeader">Т</div>
  <div class="resultGridHeader">Эт.</div>
  <div class="resultGridHeader">Ответственный</div>
<?foreach ($arResult['GRID'] as $row){?>
  <div class="resultGridCell centerCell"><a href="javascript:add2potential(<?=$row['ID']?>)"><span style="color:green;font-weight: bold">+</span></a></div>
  <div class="resultGridCell rightCell"><?=$row['ID']?></div>
  <div class="resultGridCell"><?=$row['TITLE']?></div>
  <div class="resultGridCell rightCell"><?=$row['UF_CRM_58958B5734602']?></div>
  <div class="resultGridCell"></div>
  <div class="resultGridCell"></div>
  <div class="resultGridCell"></div>
  <div class="resultGridCell"></div>
  <div class="resultGridCell"></div>
  <div class="resultGridCell"></div>
  <div class="resultGridCell"></div>
  <div class="resultGridCell"></div>
<?}?>
</div>
<script src="https://api-maps.yandex.ru/2.1/?load=package.full&lang=ru-RU"></script>
<script>
  var myMap,polygon;
  var points = <?php echo json_encode($arResult['POINTS']);?>;
  var outPoints = <?php echo json_encode($arResult['OUT_POINTS']);?>;

  ymaps.ready(init);
  function init () {
    myMap = new ymaps.Map('offerMap', {
      center: [51.779700, 55.116868],
      zoom: 13,
      controls: ['zoomControl', 'typeSelector'/*, 'fullscreenControl'*/]
    });
    
    if ($("#GEO").val()!=""){
      polygon = new ymaps.Polygon([JSON.parse($("#GEO").val())], {}, {
        fillColor: '#1092DC',
        strokeColor: '#0000FF',
        opacity: 0.5,
        strokeWidth: 3
      });
      myMap.geoObjects.add(polygon);
      myMap.setBounds(polygon.geometry.getBounds());
    }
    for (var point of points){
      var myGeoObject = new ymaps.GeoObject({
        // Описываем геометрию типа "Точка".
        geometry: {
          type: "Point",
          coordinates: [point.lat, point.lon]
        },
        // Описываем данные геообъекта.
        properties: {
          hintContent: point.name,
          balloonContentHeader: point.name,
          balloonContentBody: "тут будет ссылка и кнопки",
        }
      }, {
        // Задаем пресет метки с точкой без содержимого.
        preset: "islands#darkGreenCircleDotIcon",
        // Включаем возможность перетаскивания.
        draggable: false,
        // Переопределяем макет содержимого нижней части балуна.
        balloonContentFooterLayout: ymaps.templateLayoutFactory.createClass(
          'population: {{ properties.population }}, coordinates: {{ geometry.coordinates }}'
        ),
        // Отключаем задержку закрытия всплывающей подсказки.
        hintCloseTimeout: null
      })
      myMap.geoObjects.add(myGeoObject);
    }
    if ( !$("#GEO_USE").is( ":checked" ) ){
      for (var outPoint of outPoints){
        var myGeoObject = new ymaps.GeoObject({
          // Описываем геометрию типа "Точка".
          geometry: {
            type: "Point",
            coordinates: [outPoint.lat, outPoint.lon]
          },
          // Описываем данные геообъекта.
          properties: {
            hintContent: outPoint.name,
            balloonContentHeader: outPoint.name,
            balloonContentBody: "тут будет ссылка и кнопки",
          }
        }, {
          // Задаем пресет метки с точкой без содержимого.
          preset: "islands#grayCircleDotIcon",
          // Включаем возможность перетаскивания.
          draggable: false,
          // Переопределяем макет содержимого нижней части балуна.
          balloonContentFooterLayout: ymaps.templateLayoutFactory.createClass(
            'population: {{ properties.population }}, coordinates: {{ geometry.coordinates }}'
          ),
          // Отключаем задержку закрытия всплывающей подсказки.
          hintCloseTimeout: null
        })
        myMap.geoObjects.add(myGeoObject);
      }
    }
  }
  $("#offerBuySearch").click(function () {
    var data = $('#crm_offer_buy').serialize();
    $.ajax({
      url: "<?=$arResult['COMPONENT_PATH']?>/ajax.php",
      type: "POST",
      data: data,
      dataType: "text",
      success: function (html) {
        $("#<?=$arResult['OFFER_AJAX_ID']?>").html(html);
      },
      error: function (html) {
        $("#<?=$arResult['OFFER_AJAX_ID']?>").html("Технические неполадки! В ближайшее время все будет исправлено!");
      },
    });
  });
  $("#<?=$arResult['OFFER_AJAX_ID']?>").blur(function(){
    console.log("Изменение размера");
  });
</script>