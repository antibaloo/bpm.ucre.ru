<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
CPullWatch::Add($USER->GetId(), 'PULL_TEST');
?>
<div id="pull_test"></div>
<script type="text/javascript">
	BX.ready(function(){
		BX.addCustomEvent("onPullEvent", function(module_id,command,params) {
			console.log(module_id,command,params);
			if (module_id == '<?=$arParams["MODULE_ID"]?>' && command == '<?=$arParams["COMMAND"]?>' && params.USER == <?=$arParams["USER"]?>)
			{
				BX('pull_test').innerHTML += params.TIME+' '+params.USER+' '+params.EVENT+'<br>';
			}
		});
		BX.PULL.extendWatch('PULL_TEST');
	});
</script>
