<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
$currentUserId = $USER->GetId();
CPullWatch::Add($currentUserId, 'PULL_TEST');
CUtil::InitJSCore(array('ajax', 'jquery'/*Если не подключена ранее*/, 'popup'));// Подключаем библиотеку
?>
<style>
<!--
#ajax-add-schema {display:none; width:1024px; min-height:578px;}
-->
</style>

<button onclick="sendCommand();">It`s work!</button>
<div id="pull_test"></div>
<div id="ajax-add-schema"></div>
<script type="text/javascript">

function sendCommand()
{
	BX.ajax({
		url: '/bitrix/components/ucre/pull.test/ajax.php',
		method: 'POST',
		data: {'SEND' : 'Y', 'sessid': BX.bitrix_sessid()}
	});
}

BX.ready(function(){
	BX.addCustomEvent("onPullEvent", function(module_id,command,params) {
		console.log(module_id,command,params);
		if (module_id == "test" && command == 'check')
		{
			BX('pull_test').innerHTML += '<table><tr><td>Переданная метка</td><td>'+params.TIME+'</td></tr></table>';
			//BX.ajax.insertToNode('https://yandex.ru', BX('ajax-add-schema'));//ajax-загрузка контента из url, у меня он помещён в "Короткие ссылки" /bitrix/admin/short_uri_admin.php?lang=ru
			//Можно использовать такой адрес /include/schema.php      
			schema.show(); //отображение окна

		}
		if (module_id == "megapbx" && command == 'incoming' && params.USERID == <?=$currentUserId?>)
		{
			BX('pull_test').innerHTML += 'Входящий звонок с номера: '+params.PHONE+'<br>';
		}
	});
	BX.PULL.extendWatch('PULL_TEST');
	
	var schema = new BX.PopupWindow("schema", null, {
		content: BX('ajax-add-schema'),//Контейнер
		closeIcon: {right: "10px", top: "10px"},//Иконка закрытия
		titleBar: {content: BX.create("span", {html: '<b>Схема проъезда</b>', 'props': {'className': 'access-title-bar'}})},//Название окна 
		zIndex: 0,
		offsetLeft: 0,
		offsetTop: 0,
		draggable: {restrict: true},//Окно можно перетаскивать на странице
		overlay: {backgroundColor: 'black', opacity: '80' },  /* затемнение фона */
		/*Если потребуется, можно использовать кнопки управления формой        
		buttons: [
		new BX.PopupWindowButton({
		text: "Отправить",
		className: "popup-window-button-accept",
		events: {click: f unction(){
		BX.ajax.submit(BX("myForm"), f unction(data){ // отправка данных из формы с id="myForm" в файл из action="..."
		BX('ajax-add-schema').innerHTML = data;
		});
		}}
		}),
		new BX.PopupWindowButton({
		text: "Закрыть",
		className: "webform-button-link-cancel",
		events: {click: f unction(){
		this.popupWindow.close();// закрытие окна
		}}
		})
		]
		*/}); 
});
</script>
