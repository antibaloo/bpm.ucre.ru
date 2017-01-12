<?
require_once($_SERVER['DOCUMENT_ROOT'] . "/bitrix/modules/main/include/prolog_before.php");
CModule::IncludeModule("crm");
if (isset($_POST['id'])){
	//Поиск заявки с ID
	$tempDeal = new CCrmDeal;
	$tempob = $tempDeal->GetListEx(array(), array("ID" => $_POST['id']), false, false, array("UF_CRM_1469534140"),array());
	if ($tempFields = $tempob->Fetch()){
		if ($tempFields['UF_CRM_1469534140']){
			if (intval($tempFields['UF_CRM_1469534140']) > 5560)
				$rsData = $DB->Query('SELECT ucre_avito_log_element.*, ucre_avito_log.UF_TIME FROM ucre_avito_log_element LEFT JOIN ucre_avito_log ON ucre_avito_log_element.UF_AVITO_LOG_ID = ucre_avito_log.UF_AVITO_ID WHERE UF_CRM_ID = '.$tempFields['UF_CRM_1469534140'].' ORDER BY UF_AVITO_LOG_ID DESC');
			if (intval($tempFields['UF_CRM_1469534140']) <= 5560){
				$res = CIBlockElement::GetByID(intval($tempFields['UF_CRM_1469534140']));
				if($ar_res = $res->GetNext())
					$rsData = $DB->Query('SELECT ucre_avito_log_element.*, ucre_avito_log.UF_TIME FROM ucre_avito_log_element LEFT JOIN ucre_avito_log ON ucre_avito_log_element.UF_AVITO_LOG_ID = ucre_avito_log.UF_AVITO_ID WHERE UF_CRM_ID = '.$ar_res['CODE'].' ORDER BY UF_AVITO_LOG_ID DESC');
			}
			if($aRes = $rsData->GetNext()){
				$avitId = substr(strrchr($aRes['UF_AVITO_LINK'], "_"), 1);//'https://www.avito.ru/items/stat/860175284?step=0'
				//echo "Ссылка на статистику: https://www.avito.ru/items/stat/".$avitId."?step=".$_POST['step']."<br>";
				if( $curl = curl_init() ) {
					curl_setopt($curl, CURLOPT_URL, 'https://www.avito.ru/items/stat/'.$avitId.'?step='.$_POST['step']);
					curl_setopt($curl, CURLOPT_RETURNTRANSFER,true);
					$out = curl_exec($curl);
					//echo $out;
					curl_close($curl);
					$dom = new DomDocument();
					$dom->loadHTML($out);
					$xpath = new DomXPath($dom);
					$stat = $xpath->query("///div[@class='item-stats__chart js-item-stats-chart']");
					$all = $xpath->query("///div[@class='item-stats-legend']");
					$startdate = $xpath->query("///div[@class='item-stats__date']");
					$stat_array = json_decode(utf8_decode($stat->item(0)->getAttribute("data-chart")));
					$seens = (array) $stat_array;
					$stat_table = array(array(),array());
					
					foreach ($seens['columns'][1] as $value){
						$stat_table[1][] = $value;
					}
					foreach ($seens['dates'] as $value){
						$stat_table[0][] = $value;
					}
					$stat_table[0][0] = "Даты";
					$stat_table[1][0] = "Просмотры";
					echo "<br>".utf8_decode($startdate->item(0)->nodeValue)."<br><br>";
					echo "<table style='width:100%; border: 1px solid black; border-collapse: collapse;'><tr>";
					foreach($stat_table[0] as $value){
						echo "<td style='border: 1px solid black;'>".$value."</td>";
					}
					echo "</tr><tr>";
					foreach($stat_table[1] as $value){
						echo "<td style='border: 1px solid black;'>".$value."</td>";
					}
					echo "</tr></table>";

					echo "<br>Всего: ".utf8_decode($all->item(0)->nodeValue);
				}
			}else{
				echo "<br><h2>Выгрузка объекта ".$tempFields['UF_CRM_1469534140']." не производилась!</h2>";
			}
		}else{
			echo "<br><h2>Нет связанного объекта!</h2><br>";
		}
	}
	
}
//print_r($_POST);
?>
<form width="100%" style="background-color: #f4f0d2" method="POST" id="formx" action="javascript:void(null);">
	<input type="hidden" name="id" value="<?=$_POST['id']?>">
	<input type="hidden" name="step" value="<?=$_POST['step']?>">
	<input type="button" name="left" value="<" onclick="call('left')">
	Счетчик просмотров Авито
	<input type="button" name="right" value=">" onclick="call('right')" <?=($_POST['step'] == 0)?'disabled':''?>>
</form>
<script type="text/javascript" language="javascript">
 	function call(direction) {
 	  var msg = $('#formx').serialize();
		var step = $('input[name=step]').val();
		if (direction == 'right') step = step - 14;
		if (direction == 'left') step = Number(step) + 14;
		$.ajax({
			type: 'POST',
			url: '/ajax/views.php',
			dataType: "html",
			data: msg+"&step="+step,
			success: function(data) {
				$('#inner_tab_tab_191183').html(data);
			},
			error:  function(xhr, str){
				alert('Возникла ошибка: ' + xhr.responseCode);
			}
		});
	}
</script>
