<?php
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED!==true) die();
?>
<pre>
<?print_r($arResult);?>
</pre>
<div class="offerForm">
  <form id="crm_offer_buy">
    Тип недвижимости<input name="UF_CRM_58CFC7CDAAB96" type="text" value="<?=$arResult['PARAMS']['UF_CRM_58CFC7CDAAB96']?>">
    <input type="hidden" name="OFFER_AJAX_ID" value="<?=$arResult['OFFER_AJAX_ID']?>">
  </form>
  <center><a href="#" id="offerBuySearch" class="ui-btn ui-btn-bg-primary">Искать</a></center>
</div>
<div class="offerMap">
  Карта
</div>

<div class="offerResultGrid">
  Грид
</div>

<script>
  $(document).ready(function() {
    $(".offerMap").height($(".offerForm").height());
  });
  $("#offerBuySearch").click(function () {
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