<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
IncludeModuleLangFile($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/intranet/public/crm/reports/index.php");
$APPLICATION->SetTitle("Новые лиды");
?>
<link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<style>
</style>
<div style="margin:0 auto; width: 50%; padding: 5px; background-color: #eeeeee;">
	<form id="leadForm">
		<div id="leadContacts">
		</div>
		<div id="leadGoal">
		</div>
		<div id="roType">
		</div>
		<div id="leadMap">
		</div>
		<div id="roProperty">
		</div>
		<button id="saveForm" type="button">Сохранить форму</button>&nbsp;<button id="resetForm" type="button">Сбросить форму</button>
	</form>
</div>
<div id="result" style="margin:0 auto; width: 50%; padding: 5px; background-color: #1eeee1;"></div>
<script src="/include/maskedInput/jquery.maskedinput.js"></script>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<script>
	$(document).ready(function(){
		$("#leadContacts").load("./templates/leadcontacts.html");
		$("#leadGoal").load("./templates/leadgoal.html");
		$("#roType").load("./templates/rotype.html");
		$("#roProperty").load("./templates/roproperty.html");
		/*$("#mainProperty").load("./templates/mainproperty.html");*/
		$("#result").html("");
	});
	$("#resetForm").click(function(){
		$("#leadContacts").load("./templates/leadcontacts.html");
		$("#leadGoal").load("./templates/leadgoal.html");
		$("#roType").load("./templates/rotype.html");
		$("#roProperty").load("./templates/roproperty.html");
		/*$("#mainProperty").load("./templates/mainproperty.html");*/
		$("#result").html("");
	});
	$("#saveForm").click(function(){
		var data = $("#leadForm").serialize();
		$.ajax({
			type: "POST",
      url: "check.php",
      dataType: "json",
      data: data,
			success: function (json) {
        $("#result").html(json);
				console.log(json);
      },
      error: function (json) {
        $("#result").html("Технические неполадки! В ближайшее время все будет исправлено!");
      },
		});
	});
</script>
<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>