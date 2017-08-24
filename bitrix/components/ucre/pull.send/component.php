<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
?>
<form id="messageForm">
	Событие <input id="event" value="incomming"><br>
	Адресат <input id="user" value="<?=$USER->GetID()?>"><br>
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
				'user': $("#user").val(),
				'event': $("#event").val()
			}
		});
	}
</script>
