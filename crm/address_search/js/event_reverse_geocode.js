ymaps.ready(init);

function init() {
    var myPlacemark,
        myMap = new ymaps.Map('map', {
            center: [51.768199, 55.096955],
            zoom: 13
        }, {
            searchControlProvider: 'yandex#search'
        });

    // Слушаем клик на карте.
    myMap.events.add('click', function (e) {
        var coords = e.get('coords');

        // Если метка уже создана – просто передвигаем ее.
        if (myPlacemark) {
            myPlacemark.geometry.setCoordinates(coords);
        }
        // Если нет – создаем.
        else {
            myPlacemark = createPlacemark(coords);
            myMap.geoObjects.add(myPlacemark);
            // Слушаем событие окончания перетаскивания на метке.
            myPlacemark.events.add('dragend', function () {
                getAddress(myPlacemark.geometry.getCoordinates());
            });
        }
        getAddress(coords);
    });

    // Создание метки.
    function createPlacemark(coords) {
        return new ymaps.Placemark(coords, {
            iconCaption: 'поиск...'
        }, {
            preset: 'islands#violetDotIconWithCaption',
            draggable: true
        });
    }

    // Определяем адрес по координатам (обратное геокодирование).
    function getAddress(coords) {
        myPlacemark.properties.set('iconCaption', 'поиск...');
        ymaps.geocode(coords).then(function (res) {
          var firstGeoObject = res.geoObjects.get(0);
          var meta = firstGeoObject.properties.get("metaDataProperty");
          
          var data = meta.GeocoderMetaData.Address;
          $.ajax({
            type: "POST",
            url: "/ajax/kladronestring.php",
            dataType: "text",
            data: {request: data, coords: coords},
            success: function (html) {
              $("#address").html(html);
            },
            error: function (html) {
              $("#address").html("Что-то пошло не так!");
            },
          });
          console.log(coords);
          //console.log(meta.GeocoderMetaData.kind);
          console.log(meta.GeocoderMetaData.Address);
          //console.log(meta.GeocoderMetaData.Address);

         //document.getElementById("address").innerHTML =firstGeoObject.properties.get('text');

            myPlacemark.properties
                .set({
                    iconCaption: firstGeoObject.properties.get('name'),
                    balloonContent: firstGeoObject.properties.get('text')
                });
        });
    }
}

