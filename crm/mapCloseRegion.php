<style>
  html, body, #map {
    width: 100%; height: 97%; padding: 0; margin: 0;
  }
  
  canvas {
    position: absolute;
    z-index: -1;
    top: 0;
    left: 0;
  }
</style>
<canvas id="canv"></canvas>
<div id="map"></div>
<button id="toggleDrag">Выделить</button>
<script src="https://api-maps.yandex.ru/2.0/?load=package.full&lang=ru-RU"></script>
<script src="https://code.jquery.com/jquery-1.8.3.js"></script>
<script src="https://mourner.github.io/simplify-js/simplify.js"></script>
<script>
  function convert(coords) {
    var projection = myMap.options.get('projection');

    return coords.map(function(el) {
      var c = projection.fromGlobalPixels(myMap.converter.pageToGlobal([el.x, el.y]), myMap.getZoom());
      return c;
    });
  }
  
  function mouseDown(e) {
    ctx.clearRect(0, 0, canv.width, canv.height);
    startX = e.pageX - e.target.offsetLeft;
    startY = e.pageY - e.target.offsetTop;
    canv.addEventListener('mouseup', mouseUp);
    canv.addEventListener('mousemove', mouseMove);
    line = [];
    line.push({
      x: startX,
      y: startY
    });
  }
  
  function mouseMove(e) {
    var x = e.pageX - e.target.offsetLeft,
        y = e.pageY - e.target.offsetTop;
    
    ctx.beginPath();
    ctx.moveTo(startX, startY);
    ctx.lineTo(x, y);
    ctx.stroke();
    
    startX = x;
    startY = y;
    line.push({
      x: x,
      y: y
    });
  }
  
  function mouseUp() {
    canv.removeEventListener('mouseup', mouseUp);
    canv.removeEventListener('mousemove', mouseMove);
    aproximate();
    $("#toggleDrag").html('Очистить');
    canv.removeEventListener('mousedown', mouseDown);
  }
  function aproximate() {
    ctx.clearRect(0, 0, canv.width, canv.height);
    var res = simplify(line, 5);
    res = convert(res);
    polygon = new ymaps.Polygon([res], {}, {
      fillColor: '#1092DC',
      strokeColor: '#0000FF',
      opacity: 0.5,
      strokeWidth: 3
    });
    myMap.geoObjects.add(polygon);
    console.log(res);
  }
  
  $("#toggleDrag").click(function(){
    if ($(this).html() == 'Выделить') {
      $("#canv").css( "zIndex", 9999);
      canv.addEventListener('mousedown', mouseDown);
      $(this).html("Отменить");
    }else if ($(this).html() == 'Отменить'){
      $("#canv").css( "zIndex", -1);
      canv.removeEventListener('mousedown', mouseDown);
      $(this).html("Выделить");
    }else if ($(this).html() == 'Очистить'){
      $("#canv").css( "zIndex", -1);
      myMap.geoObjects.remove(polygon);
      //Удалить полигон, очистить поле координат
      $(this).html("Выделить");
    }
  });
  ymaps.ready(init);
  var myMap;
  var polygon;
  function init () {
    myMap = new ymaps.Map("map", {
      center: [51.779700, 55.116868], 
      zoom: 13
    });
    myMap.controls.add('zoomControl');
    myMap.behaviors.enable('scrollZoom');
  }
  
  //-------------Основной код--------------
  var canv = document.getElementById('canv'),
      ctx = canv.getContext('2d'),
      line = [];
  var map = document.getElementById('map');
  canv.width = map.offsetWidth;
  canv.height = map.offsetHeight;
  var startX = 0,
      startY = 0;
  
</script>