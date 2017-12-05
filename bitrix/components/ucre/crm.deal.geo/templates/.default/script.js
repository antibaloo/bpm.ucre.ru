function getCoords(elem) { // кроме IE8-
  var box = elem.getBoundingClientRect();
  return {
    top: box.top + pageYOffset,
    left: box.left + pageXOffset
  };

}
function convert(coords) {
  var projection = myMap.options.get('projection');
  return coords.map(function(el) {
    var c = projection.fromGlobalPixels(myMap.converter.pageToGlobal([el.x, el.y]), myMap.getZoom());
    return c;
  });
}

function mouseDown(e) {
  ctx.clearRect(0, 0, canv.width, canv.height);
  console.log(canv.width +" "+ canv.height);
  startX = e.pageX /*- e.target.offsetLeft*/;
  startY = e.pageY /*- e.target.offsetTop*/;    
  canv.addEventListener('mouseup', mouseUp);
  canv.addEventListener('mousemove', mouseMove);
  line = [];
  var myOffset = getCoords(canv);
  line.push({
    x: startX,
    y: startY
  });
}

function mouseMove(e) {
  var x = e.pageX /*- e.target.offsetLeft*/,
      y = e.pageY /*- e.target.offsetTop*/;
  var myOffset = getCoords(canv);
  
  ctx.beginPath();
  ctx.moveTo(startX - myOffset.left, startY - myOffset.top);
  ctx.lineTo(x - myOffset.left, y -  myOffset.top);
  ctx.strokeStyle="#0000FF";
  ctx.lineWidth = 1;
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
  drawButton.data.set("content","Очистить");
  $("#canv").css( "zIndex", -1);
  canv.removeEventListener('mousedown', mouseDown);
  saveButton.state.set("enabled",true);
  drawButton.state.set("enabled",true);
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
  $("#polygonCoords").val(JSON.stringify(res));
  //console.log(res);
}