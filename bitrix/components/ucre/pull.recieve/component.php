<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
CPullWatch::Add($USER->GetId(), 'PULL_TEST');
?>
<div id="pull_test" style="text-align:center; box-shadow: 0px 0px 100px 0px #000000; width: 400px;height: 250px;	margin: auto;display: none;background: #fff;z-index: 200;	position: fixed;left: 0;right: 0;top: 0;bottom: 0;padding: 10px;">
</div>
<script type="text/javascript">
	BX.ready(function(){
		
		BX.addCustomEvent("onPullEvent", function(module_id,command,params) {
			console.log(module_id,command,params);
			if (module_id == 'ucre'  && params.USER == <?=$arParams["USER"]?>){
					if(command == 'open'){
						$('#pull_test').show();
						BX('pull_test').innerHTML += command+' '+params.TIME+' '+params.USER+' '+params.EVENT+'<br>';
					}
					if(command == 'close'){
						BX('pull_test').innerHTML += command+' '+params.TIME+' '+params.USER+' '+params.EVENT+'<br>';
						$('#pull_test').hide();
					}
			}
		});
		BX.PULL.extendWatch('PULL_TEST');
	});
</script>
