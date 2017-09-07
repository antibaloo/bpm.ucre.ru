<link href="/include/custom_css/custom_paging.css?<?=time();?>" rel="stylesheet">
<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
CModule::IncludeModule('intranet');
require($_SERVER["DOCUMENT_ROOT"]."/include/reports/functions.php");
$APPLICATION->SetTitle("Отчет по встречным заявкам");
?>
<center><button class="report" id="emploee">По сотрудникам</button>&nbsp;<button class="report" id="buy">По покупкам</button>&nbsp;<button class="report" id="sell">По продажам</button></center><hr>
<div id="result"></div>
<script>
  $(".report").click(function () {
    $.ajax({
      url: "./ajax/relevant.php",
      type: "POST",
      dataType: "html",
      data: {
        report: $(this).attr('id')
      },
     
      success: function (html) {
        $("#result").html(html);
      },
      error: function (html) {
        $("#result").html("Технические неполадки! В ближайшее время все будет исправлено!");
      },
    });
  });
  
  $("#buyinside").click(function () {
    $.ajax({
      url: "./ajax/relevant.php",
      type: "POST",
      dataType: "html",
      data: {
        report: 'buy',
        inside: 1
      },
     
      success: function (html) {
        $("#result").html(html);
      },
      error: function (html) {
        $("#result").html("Технические неполадки! В ближайшее время все будет исправлено!");
      },
    });
  });
</script>
<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>