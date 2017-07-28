<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Автозаполнение формы");
?>
<form>
  Субъект федерации <input type="text" id="region" list="region_list" oninput="getlist(this)">
  <datalist id="region_list">
  </datalist>
  <br>
  Район субъекта <input type="text" id="district" list="district_list" oninput="getlist(this)">
  <datalist id="district_list">
  </datalist>
  <br>
  Населенный пункт <input type="text" id="city" list="city_list" oninput="getlist(this)">
  <datalist id="city_list">
  </datalist>
  <br>
  Улица <input type="text" id="street" list="street_list" oninput="getlist(this)">
  <datalist id="street_list">
  </datalist>
  <br>
  Дом <input type="text" id="building" list="building_list" oninput="getlist(this)">
  <datalist id="building_list">
  </datalist>
</form>
<div id="result"></div>

<script>
  function getlist(object){
    var parenttype = "";
    if (object.id == 'district') parenttype = 'region';
    else if (object.id == 'city') parenttype = 'district';
    else if (object.id == 'street') parenttype = 'city';
    else if (object.id == 'building') parenttype = 'street';
    
    var parentvalue = ($("#"+parenttype).val())?$("#"+parenttype).val():"";
    
    $.ajax({
      type: "POST",
      url: "/ajax/kladr_search.php",
      dataType: "text",
      data: {contentname: object.value, contenttype: object.id, parenttype: parenttype, parentvalue:  parentvalue},
      success: function (html) {
        $("#"+object.id+"_list").html(html);
        /*$("#result").html(html);*/
      },
      error: function (html) {
        $("#result").html("Что-то пошло не так!");
      },
    });
  }
</script>
<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");
?>