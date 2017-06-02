<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Отчет по подразделению");
$structure = CIntranetUtils::GetStructure();
echo "<pre>";
//print_r($structure);
echo "</pre>";
?>
<form id="report">
  <select name="department">
    <?
    foreach($structure['DATA'] as $element){
      $value = "";
      for ($i = 1; $i<$element['DEPTH_LEVEL'];$i++){
        $value .= "-";
      }
      $value .= $element['NAME'];
      echo '<option value="'.$element['ID'].'">'.$value.'</option>';
    }
    ?>
  </select>
  , включая все подотделы <input type="checkbox" name="subdepartments">&nbsp;
  <select id="interval" onchange="setDateInterval();">
    <option value="interval">Интервал дат</option>
    <option value="currentmonth">Текущий месяц</option>
    <option value="lastmonth">Прошедший месяц</option>
  </select>&nbsp;
  <input type="date" name="date1" id="date1"> - <input type="date" name="date2" id="date2">
  <br>
  <input type="button" id="submit" value="Сформировать отчет" onclick="reportResult();">
</form>
<div id="reportResult">
</div>
<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>
<script>
  function reportResult() {
    var data = $('#report').serialize();
    $.ajax({
      type: "POST",
      url: "./ajax/group.php",
      dataType: "text",
      data: data,
      success: function (html) {
        $("#reportResult").html(html);
      },
      error: function (html) {
        $("#reportResult").html("Технические неполадки! В ближайшее время все будет исправлено!");
      },
    });
  }
  function setDateInterval(){
    var currentDate = new Date(2017,11,17);
    if (interval.value == "currentmonth"){
      var firstDate = new Date(currentDate.getFullYear(), currentDate.getMonth(),1);
      var lastDate = new Date(currentDate.getFullYear(), currentDate.getMonth()+1,0);
      alert("Current date: "+currentDate+", current month: "+firstDate+" - "+lastDate);
      //date1.value = d1_year+"-"+d1_month+"-01";
      //date2.value = d2_year+"-"+d2_month+"-00";
    }
    if (interval.value == "lastmonth"){
      alert("Передыдущий");
    }
  }
</script>
