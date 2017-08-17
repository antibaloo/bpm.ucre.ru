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
      .add('mapTools')
   /* myMap.events.add('mousemove', function (e) {
      var coords = e.get('coords');
      myMap.balloon.open(coords, {
        contentHeader:'Событие!',
        contentBody:'<p>Координаты: ' + [
          coords[0].toPrecision(6),
          coords[1].toPrecision(6)
        ].join(', ') + '</p>'
      });
    });*/
    
    polyline = new ymaps.Polyline([], {}, {
      editorMenuManager: function (items) {
        var items = [];
        items.push({
          title: "Замкнуть область",
          onClick:  function () {
            polygon = new ymaps.Polygon([polyline.editor.geometry.getCoordinates()], {}, {});
            myMap.geoObjects.remove(polyline);
            myMap.geoObjects.add(polygon);
            console.log(polygon.editor.geometry.getCoordinates());
          }
        });
        return items;
      }
    });
    
    /*polyline.editor.events.add("vertexadd",function (e){
      var coords = polyline.geometry.getCoordinates()
      console.log(coords);
    });*/
    /*polyline.editor.events.add("statechange",function (e){
      var coords = polyline.geometry.getCoordinates()
      console.log(coords);
    });*/
    myMap.geoObjects.add(polyline);
    polyline.editor.startEditing();
    polyline.editor.startDrawing();
    $('#addPolyline').attr('disabled', true);

    
  }
</script>