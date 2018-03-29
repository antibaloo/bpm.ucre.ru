<?php
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED!==true) die();
?>
<pre>
<?print_r($arResult);?>
</pre>
<div class="offerForm">
  <form id="crm_offer_buy">
    <div class="typeWrapper">
      <div class="empty"></div>
      <div class="objectType" typeId="1">Комната</div>
      <div class="objectType" typeId="2">Квартира</div>
      <div class="objectType" typeId="3">Дом</div>
      <div class="objectType" typeId="4">Таунхаус</div>
      <div class="objectType" typeId="5">Дача</div>
      <div class="objectType" typeId="6">Участок</div>
      <div class="objectType" typeId="7">Коммерческий</div>
      <div class="empty"></div>
    </div>
    <input type="hidden" id="UF_CRM_58CFC7CDAAB96" name="UF_CRM_58CFC7CDAAB96" value="">
    <input type="hidden" name="OFFER_AJAX_ID" value="<?=$arResult['OFFER_AJAX_ID']?>">
  </form>
</div>
<div class="offerMap">
  Карта
</div>

<div class="offerResultGrid">
  Грид
</div>

<script>
  $(document).ready(function() {
    console.log($(".offerForm").height());
    $(".offerMap").height($(".offerForm").height());
  });
  $(".objectType").click(function(){
    $("#UF_CRM_58CFC7CDAAB96").val($(this).attr("typeId"));
    var data = $('#crm_offer_buy').serialize();
    $.ajax({
      url: "<?=$arResult['COMPONENT_PATH']?>/ajax.php",
      type: "POST",
      data: data,
      dataType: "text",
      success: function (html) {
        $("#<?=$arResult['OFFER_AJAX_ID']?>").html(html);
      },
      error: function (html) {
        $("#<?=$arResult['OFFER_AJAX_ID']?>").html("Технические неполадки! В ближайшее время все будет исправлено!");
      },
    });
  });
</script>