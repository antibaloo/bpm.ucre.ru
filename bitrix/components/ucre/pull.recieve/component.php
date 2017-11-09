<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
CPullWatch::Add($USER->GetId(), 'UCRE_CALL_CARD');
?>
<script type="text/javascript">
	BX.ready(function(){
		BX.addCustomEvent("onPullEvent", function(module_id,command,params) {
			console.log(module_id,command,params);
			if (module_id == 'ucre'  && params.USER == <?=$arParams["USER"]?>){
					if(command == 'register'){
						$('body').append('<div id="'+params.CALLID+'" style="text-align:left; box-shadow: 0px 0px 100px 0px #000000; width: 50%;height: 50%;	margin: auto;background: #fff;z-index: 200;	position: fixed;left: 0;right: 0;top: 0;bottom: 0;padding: 10px;"></div>');
						BX.ajax({
							url: '<?=$this->GetPath()?>/ajax.php',
							method: 'POST',
							dataType: 'html',
							data: {
								params: params
							},
							onsuccess: function(html){
								BX(params.CALLID).innerHTML = html;
							},
							onfailure: function(){
								BX(params.CALLID).innerHTML = "Что-то пошло не так!!!";
							}
						});
					}
					if(command == 'finish'){
						$("#"+params.CALLID).remove()
					}
			}
		});
		BX.PULL.extendWatch('UCRE_CALL_CARD');
	});
</script>
