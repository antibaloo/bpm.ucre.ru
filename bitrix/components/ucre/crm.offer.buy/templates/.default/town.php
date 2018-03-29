<?php
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED!==true) die();
?>
<pre>
<?print_r($arResult);?>
</pre>
<div class="offerForm">
  <form id="crm_offer_buy">
    <div class="gridWrapper">
      <div class="gridTitle">Тип объекта</div>
      <div class="gridValue">
        <select name="UF_CRM_58CFC7CDAAB96">
          <option value="">(выберите тип объекта)</option>
          <option value="1" <?=($arResult['PARAMS']['UF_CRM_58CFC7CDAAB96'] == 1)?"selected":""?>>Комната</option>
          <option value="2" <?=($arResult['PARAMS']['UF_CRM_58CFC7CDAAB96'] == 2)?"selected":""?>>Квартира</option>
          <option value="3" <?=($arResult['PARAMS']['UF_CRM_58CFC7CDAAB96'] == 3)?"selected":""?>>Дом</option>
          <option value="4" <?=($arResult['PARAMS']['UF_CRM_58CFC7CDAAB96'] == 4)?"selected":""?>>Таунхаус</option>
          <option value="5" <?=($arResult['PARAMS']['UF_CRM_58CFC7CDAAB96'] == 5)?"selected":""?>>Дача</option>
          <option value="6" <?=($arResult['PARAMS']['UF_CRM_58CFC7CDAAB96'] == 6)?"selected":""?>>Участок</option>
          <option value="7" <?=($arResult['PARAMS']['UF_CRM_58CFC7CDAAB96'] == 7)?"selected":""?>>Коммерческий</option>
        </select>
      </div>
      <div class="gridTitle"></div>
      <div class="gridValue">
      </div>
    </div>
    <input type="hidden" name="OFFER_AJAX_ID" value="<?=$arResult['OFFER_AJAX_ID']?>"><!-- ID блока обертки шаблона -->
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
    console.log($(".offerForm").height());
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