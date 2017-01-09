<?
require_once($_SERVER['DOCUMENT_ROOT'] . "/bitrix/modules/main/include/prolog_before.php");
CModule::IncludeModule("crm");
print_r($_POST);
//if (!isset($_POST['step'])) $step = 0;
/*if (isset($_POST['right'])) echo "left";
if (isset($_POST['left'])) $step = $_POST['step']+14;*/
?>
<form width="100%" style="background-color: #f4f0d2" method="POST" id="formx" action="javascript:void(null);" onsubmit="call()">
	<input type="hidden" name="id" value="<?=$_POST['id']?>">
	<input type="text" name="step" value="<?=$step?>">
	<input type="submit" name="left" value="<">
	Счетчик просмотров Авито
	<input type="submit" name="right" value=">">
</form>
<script type="text/javascript" language="javascript">
 	function call() {
 	  var msg   = $('#formx').serialize();
		$.ajax({
			type: 'POST',
			url: '/ajax/views.php',
			dataType: "html",
			data: msg,
			success: function(data) {
				$('#inner_tab_tab_191183').html(data);
			},
			error:  function(xhr, str){
				alert('Возникла ошибка: ' + xhr.responseCode);
			}
		});
	}
</script>
