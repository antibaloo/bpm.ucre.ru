<div id="map" style="width:800px; height:600px"></div>
<script src="https://api-maps.yandex.ru/2.0/?load=package.full&lang=ru-RU"></script>
<script src="https://code.jquery.com/jquery-1.8.3.js"></script>
<script>
  var polyline;
  ymaps.ready(init);
  function init () {
    var myMap = new ymaps.Map('map', {
      center: [51.779700, 55.116868], 
      zoom: 13
    });
    
    //Добавляем элементы управления	
    myMap.controls                
      .add('zoomControl')               
      .add('typeSelector')                
      .add('mapTools');

    polyline = new ymaps.Polyline([], {}, {
      editorMenuManager: function (items) {
        var items = [];
        items.push({
          title: "Замкнуть область",
          onClick:  function () {
            polygon = new ymaps.Polygon([polyline.editor.geometry.getCoordinates()], {}, {});
            console.log(polyline.editor.geometry.getCoordinates());
            myMap.geoObjects.remove(polyline);
            myMap.geoObjects.add(polygon);
          }
        });
        return items;
      }
    });
    myMap.geoObjects.add(polyline);
    polyline.editor.startEditing();
    polyline.editor.startDrawing();
    $('#addPolyline').attr('disabled', true);
  }
</script>