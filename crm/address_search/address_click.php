<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Поиск адреса по КЛАДР");
?>
<!-- Если вы используете API локально, то в URL ресурса необходимо указывать протокол в стандартном виде (http://...)-->
<script src="https://code.jquery.com/jquery-1.8.3.js"></script>
<script src="https://api-maps.yandex.ru/2.1/?lang=ru_RU" type="text/javascript"></script>
<script src="js/event_reverse_geocode.js" type="text/javascript"></script>
<style type="text/css">
  #map {
    width: 70%;
    height: 500px;
    float: left;
  }
  #address {
    padding-left:1%;
    width: 29%;
    height: 500px;
    float: right;
  }
  #sync{
    clear: both;
  }
  .header {
    padding: 5px;
  }
  
</style>  
<p class="header">Кликните по карте, чтобы узнать адрес</p>
<div id="map"></div>
<div id="address">
</div>
<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");
?>