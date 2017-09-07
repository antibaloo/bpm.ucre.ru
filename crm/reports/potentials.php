<link href="/include/custom_css/custom_paging.css?<?=time();?>" rel="stylesheet">
<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Отчет по потенциальным заявкам");
$structure = CIntranetUtils::GetStructure();
?>
<form id="report">
  <select id="department" name="department">
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
  &nbsp;
  <select name="emploee" id="emploee">
  </select>
  <br>
  <input type="button" id="submit" value="Сформировать отчет">
</form>
<hr>
<div id="reportResult">
</div>
<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>
<script>
  function set_active(object){
    if(!object.classList.contains('active')){
      var el = document.getElementById("page"+object.innerHTML);
      var a_page = document.getElementsByClassName("page active");
      var a_pages = document.getElementsByClassName("pages active");
      a_page[0].classList.remove('active');
      a_pages[0].classList.remove('active');
      el.classList.add('active');
      object.classList.add('active');
    }
  }
  $(document).ready(function(){
    $.ajax({
      type: "POST",
      url:"./ajax/emploeeSelector.php",
      datatType: "html",
      data: {
        "department":$("#department").val()
      },
      success: function (html){
        $("#emploee").html(html);
      },
      error: function (html){
        $("#emploee").html("option value='0'>Ошибка!!!</option>");
      }
    });
  });
  $("#department").change(function(){
    $.ajax({
      type: "POST",
      url:"./ajax/emploeeSelector.php",
      datatType: "html",
      data: {
        "department":$("#department").val()
      },
      success: function (html){
        $("#emploee").html(html);
      },
      error: function (html){
        $("#emploee").html("<option value='0'>Ошибка!!!</option>");
      }
    });
  });
  $("#submit").click(function () {
    $.ajax({
      type: "POST",
      url: "./ajax/potentials.php",
      dataType: "text",
      data: {
        'emploee': $("#emploee").val()
      },
      success: function (html) {
        $("#reportResult").html(html);
      },
      error: function (html) {
        $("#reportResult").html("Технические неполадки! В ближайшее время все будет исправлено!");
      },
    });
  });
</script>
