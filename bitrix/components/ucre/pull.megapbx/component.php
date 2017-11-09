<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
$currentUserId = $USER->GetId();
CPullWatch::Add($currentUserId, 'PULL_MEGAPBX');
?>
<script type="text/javascript">
BX.ready(function(){
	BX.addCustomEvent("onPullEvent", function(module_id,command,time, params) {
    if (module_id == "megapbx") console.log(module_id,command,time,params);
	});
	BX.PULL.extendWatch('PULL_MEGAPBX');
});
</script>
