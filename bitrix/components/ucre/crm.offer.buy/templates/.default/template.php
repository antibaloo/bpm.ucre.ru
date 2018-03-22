<?php
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED!==true) die();
?>
<pre>
<?print_r($arResult);?>
</pre>

<form id="crm_offer_buy">
  Тип недвижимости<input name="UF_CRM_58CFC7CDAAB96" type="text" value="<?=$arResult['PARAMS']['UF_CRM_58CFC7CDAAB96']?>">
  <center><input id="submit" type="button" value="Искать"></center>
  <input type="hidden" name="OFFER_AJAX_ID" value="<?=$arResult['OFFER_AJAX_ID']?>">
</form>
<script>
    $("#submit").click(function () {
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