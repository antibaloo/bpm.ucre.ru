<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
?>
<form id="messageForm">
	Адресат <input id="user" value="<?=$USER->GetID()?>"><br>
	Команда
	<select id="command">
		<option value="register">Регистрация внешнего звонка</option>
		<option value="finish">Окончание внешнего звонка</option>
	</select><br>
	Телефонный номер <input id="phone" value="+79877955786"><br>
	Направление
	<select id="type">
	<option value="2">Входящий</option>
	<option value="1">Исходящий</option>
	</select><br>
	Источник
	<select id="source">
		<option value="avito">Авито</option>
		<option value="irr">ИРР</option>
		<option value="site">Сайт</option>
		<option value="tv">ТВ реклама</option>
	</select><br>
	CallId <input id="callid" value="17e8c92d-9a8a-4485-80ce-05f57c33c601"><br>
	Тип сущности CRM
	<select id="crm_entity_type">
		<option value="">не зарегистрированный номер</option>
		<option value="lead">Лид</option>
		<option value="contact">Контакт</option>
		<option value="company">Компания</option>
	</select><br>
	id сущности <input id="crm_entity_id" value="0">
</form>
<button onclick="sendCommand();">It`s work!</button>
<script type="text/javascript">
	function sendCommand(){
		BX.ajax({
			url: '<?=$this->GetPath()?>/ajax.php',
			method: 'POST',
			data: {
				'SEND' : 'Y', 
				'sessid': BX.bitrix_sessid(),
				'command': $("#command").val(),
				'user': $("#user").val(),
				'phone': $("#phone").val(),
				'type': $("#type").val(),
				'source': $("#source").val(),
				'callid': $("#callid").val(),
				'crm_entity_type': $("#crm_entity_type").val(),
				'crm_entity_id': $("#crm_entity_id").val()
			}
		});
	}
</script>
