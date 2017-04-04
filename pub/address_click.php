<!DOCTYPE html>
<html>
<head>
  <title>Примеры. Определение адреса клика на карте с помощью обратного геокодирования</title>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <!-- Если вы используете API локально, то в URL ресурса необходимо указывать протокол в стандартном виде (http://...)-->
  <script src="https://code.jquery.com/jquery-1.8.3.js"></script>
  <script src="https://api-maps.yandex.ru/2.1/?lang=ru_RU" type="text/javascript"></script>
  <script src="event_reverse_geocode.js" type="text/javascript"></script>
  <style type="text/css">
    html, body {
      width: 100%;
      height: 100%;
      margin: 0;
      padding: 0;
      font-family: Arial;
      font-size: 14px;
    }
    #map {

      width: 70%;
      height: 80%;
      float: left;
    }
    #address {
      padding-left:1%;
      width: 29%;
      height: 80%;
      float: right;
    }
    #sync{
      clear: both;
    }
    .header {
      padding: 5px;
    }

  </style>
</head>
<body>
  <p class="header">Кликните по карте, чтобы узнать адрес</p>
  <div id="map"></div>
  <div id="address">
  </div>
</body>
</html>