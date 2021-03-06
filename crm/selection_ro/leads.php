<?
$start = microtime(true);//Засекаем время выполнения скрипта
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Поиск объектов на Авито");
global $APPLICATION;
$APPLICATION->SetAdditionalCSS('/bitrix/js/crm/css/crm.css');
$APPLICATION->SetAdditionalCSS("/bitrix/themes/.default/crm-entity-show.css");
if(SITE_TEMPLATE_ID === 'bitrix24')
{
	$APPLICATION->SetAdditionalCSS("/bitrix/themes/.default/bitrix24/crm-entity-show.css");
}

if (in_array($USER->GetID(), array(1,24,203,204))){
?>
<link href="custom.css?<?=time();?>" rel="stylesheet">
<link rel="stylesheet" href="/bitrix/js/baloo/fancyapps/source/jquery.fancybox.css" type="text/css" media="screen" />
<script type="text/javascript" src="/bitrix/js/baloo/fancyapps/source/jquery.fancybox.pack.js"></script>
<form id="avito" name="avito">
  <input type="hidden" name="base_url" value="https://www.avito.ru/orenburg">
  <input type="radio" name="type" value="kvartiry" checked> Квартиры
  <!--<input type="radio" name="type" value="doma_dachi_kottedzhi"> Дома, дачи, коттеджи
  <input type="radio" name="type" value="zemelnye_uchastki"> Земельные участк
  <input type="radio" name="type" value="kommercheskaya_nedvizhimost"> Коммерческая недвижимость
  <input type="radio" name="type" value="komnaty"> Комнаты
  <input type="radio" name="type" value="garazhi_i_mashinomesta"> Гаражи и машиноместа
  <input type="radio" name="type" value="nedvizhimost_za_rubezhom"> Недвижимость за рубежом-->
  <input type="hidden" name="operation" value="prodam">
  <div id="kvartiry" style="display:block">
		<select name="user">
			<!--<option value="">Все</option>-->
			<option value="1">Частные</option>
			<option value="2">Агентства</option>
		</select>
    <select name="rooms">
      <!--<option value="">(кол-во комнат)</option>-->
      <option value="studii">Студии</option>
      <option value="1-komnatnye">1-комнатные</option>
      <option value="2-komnatnye">2-комнатные</option>
      <option value="3-komnatnye">3-комнатные</option>
      <option value="4-komnatnye">4-комнатные</option>
      <option value="5-komnatnye">5-комнатные</option>
      <option value="6-komnatnye">6-комнатные</option>
      <option value="7-komnatnye">7-комнатные</option>
      <option value="8-komnatnye">8-комнатные</option>
      <option value="9-komnatnye">9-комнатные</option>
      <option value="mnogokomnatnye">многокомнатные</option>
    </select>
    <select name="market">
      <!--<option value="">(выберите рынок)</option>-->
      <option value="vtorichka">Вторичка</option>
      <option value="novostroyka">Новостройка</option>
    </select>
    <select name="type_house">
      <!--<option value="">(тип дома)</option>-->
      <option value="kirpichnyy_dom">Кирпичный</option>
      <option value="panelnyy_dom">Панельный</option>
      <option value="blochnyy_dom">Блочный</option>
      <option value="monolitnyy_dom">Монолитный</option>
      <option value="derevyannyy_dom">Деревяный</option>
    </select>
		<input type="text" name="content" size="100" placeholder="ключевые слова">
    <!--Этаж <input type="number" min="1" max="31" step="1" name="floor">
    <span style="border: 1px solid black"><input type="checkbox" name="notfirst"> не последний </span>
    Этажей <input type="number" min="1" max="31" step="1" name="floors">-->
  </div>
  <div id="doma_dachi_kottedzhi" style="display:none"></div>
  <div id="zemelnye_uchastki" style="display:none"></div>
  <div id="kommercheskaya_nedvizhimost" style="display:none"></div>
  <div id="komnaty" style="display:none"></div>
  <div id="garazhi_i_mashinomesta" style="display:none"></div>
  <div id="nedvizhimost_za_rubezhom" style="display:none"></div>
	<input type="button" onclick="send_form()" value="Искать">
</form>
<div id="result">
  
</div>
<?
$time = microtime(true) - $start;
//echo "<br>Скрипт отработал за ".$time." секунд.";
}else{
  echo "Вам сюда пока нельзя!";
}
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");
?>
<script>
  function set_active(object){
    if(!object.classList.contains('active')){
      var el = document.getElementById("page"+object.innerHTML);
      var a_page = document.getElementsByClassName("page active");
      var a_pages = document.getElementsByClassName("pages active");
      a_page[0].classList.remove('active');
      a_pages[0].classList.remove('active');
      el.classList.add('active');
      object.classList.add('active');
    }
  }
	function show_ad(object) {
		//document.getElementById('avitoAd').innerHTML += "<br>"+object.getAttribute("link");
		//document.getElementById('avitoAd').style.display = 'block';
		var adParams = object.getAttribute("adParams");
		$.ajax({
			type: "POST",
			url:"./ajax/avito_parse.php",
			dataType: "html",
			data: {adParams: adParams},
			success: function (html){
				$("#avitoAd").html(html);
				$("#avitoAd").show();
			},
			error: function (html){
				$("#avitoAd").html("Технические неполадки! В ближайшее время все будет исправлено!");
				$("#avitoAd").show();
			},
		});
	}
	function send_form() {
    var data = $('#avito').serialize();
    $.ajax({
      type: "POST",
      url: "./ajax/avito_search.php",
      dataType: "text",
      data: data,
      success: function (html) {
        $("#result").html(html);
      },
      error: function (html) {
        $("#result").html("Технические неполадки! В ближайшее время все будет исправлено!");
      },
    });
  }
	function convertToLead(id){
		var data = $('#toLead_'+id).serialize();
		$.ajax({
      type: "POST",
      url: "./ajax/converttolead.php",
      dataType: "text",
      data: data,
      success: function (html) {
        $("#avitoAd").html(html);
				$("tr#"+id).detach();
      },
      error: function (html) {
        $("#avitoAd").html("Технические неполадки! В ближайшее время все будет исправлено!");
      },
    });
	}
  var rad = document.avito.type;
	var prev = rad[0]; //Вкладка квартир включена по-умолчанию
  for(var i = 0; i < rad.length; i++) {
    rad[i].onclick = function() {
      if(this !== prev) {
        if (prev){
          $('#'+prev.value).hide();
        }
        $('#'+this.value).show();
        prev = this;
      }
      //send_form();
    };
  }
</script>