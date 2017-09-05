<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
IncludeModuleLangFile($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/intranet/public/crm/reports/index.php");
$APPLICATION->SetTitle("Новые лиды");
?>
	<style>
	</style>
	<div style="margin:0 auto; width: 50%; padding: 5px; background-color: #eeeeee;">
		<form>
			<div id="leadGoal">

			</div>
			<div id="roType">
				
			</div>
			<div id="roProperty">
				
			</div>
			<button id="resetForm" type="button">
				Сбросить форму
			</button>
		</form>
	</div>
<script>
	$(document).ready(function(){
		$("#leadGoal").load("./templates/leadgoal.html");
		$("#roType").load("./templates/rotype.html");
		$("#roProperty").load("./templates/roproperty.html");
	});
	$("#resetForm").click(function(){
		$("#leadGoal").load("./templates/leadgoal.html");
		$("#roType").load("./templates/rotype.html");
		$("#roProperty").load("./templates/roproperty.html");
	});
</script>
	<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>