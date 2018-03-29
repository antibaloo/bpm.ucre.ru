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
      <div class="gridTitle">Рынок поиска</div>
      <div class="gridValue">
        Вторичка <input type="checkbox" value="827" name="UF_CRM_5895BC940ED3F[]" <?=(count($arResult['PARAMS']['UF_CRM_5895BC940ED3F']) == 2 || $arResult['PARAMS']['UF_CRM_5895BC940ED3F'][0] == 827)?"checked":""?>>
        Первичка <input type="checkbox" value="828" name="UF_CRM_5895BC940ED3F[]" <?=(count($arResult['PARAMS']['UF_CRM_5895BC940ED3F']) == 2 || $arResult['PARAMS']['UF_CRM_5895BC940ED3F'][0] == 828)?"checked":""?>>
      </div>
      <div class="gridTitle">Кол-во комнат</div>
      <div class="gridValue"><input name ="UF_CRM_58958B529E628" type="number" min="1" max="10" value="<?=$arResult['PARAMS']['UF_CRM_58958B529E628']?>"></div>
      <div class="gridTitle">Тип дома</div>
      <div class="gridValue">
        <select name="UF_CRM_58958B5207D0C" >
          <option value="">не выбрано</option>
          <option value="760" <?=($arResult['PARAMS']['UF_CRM_58958B5207D0C'] == 760)?"selected":""?>>Блочный</option>
          <option value="757" <?=($arResult['PARAMS']['UF_CRM_58958B5207D0C'] == 757)?"selected":""?>>Кирпичный</option>
          <option value="758" <?=($arResult['PARAMS']['UF_CRM_58958B5207D0C'] == 758)?"selected":""?>>Монолитный</option>
          <option value="2011" <?=($arResult['PARAMS']['UF_CRM_58958B5207D0C'] == 2011)?"selected":""?>>Монолитно-кирпичный</option>
          <option value="756" <?=($arResult['PARAMS']['UF_CRM_58958B5207D0C'] == 756)?"selected":""?>>Панельный</option>
          <option value="759" <?=($arResult['PARAMS']['UF_CRM_58958B5207D0C'] == 759)?"selected":""?>>Деревянный</option>
        </select>
      </div>
      <div class="gridTitle">S <sub>общ.</sub> от</div>
      <div class="gridValue"><input name ="UF_CRM_58958B52BA439" type="number" min="1" value="<?=$arResult['PARAMS']['UF_CRM_58958B52BA439']?>"></div>
      <div class="gridTitle">S <sub>кух.</sub> от</div>
      <div class="gridValue"><input name ="UF_CRM_58958B52F2BAC" type="number" min="1" value="<?=$arResult['PARAMS']['UF_CRM_58958B52F2BAC']?>"></div>
      <div class="gridTitle">Этаж от</div>
      <div class="gridValue"></div>
      <div class="gridTitle">Этаж до</div>
      <div class="gridValue"></div>
      <div class="gridTitle">Этажность от</div>
      <div class="gridValue"></div>
      <div class="gridTitle">Этажность до</div>
      <div class="gridValue"></div>
      <div class="gridTitle">Не последний</div>
      <div class="gridValue"></div>
      <div class="gridTitle">Есть балкон</div>
      <div class="gridValue"></div>
      <div class="gridTitle">Цена от</div>
      <div class="gridValue"></div>
      <div class="gridTitle">Цена до</div>
      <div class="gridValue"></div>
      <div class="gridTitle">Область поиска</div>
      <div class="gridValue"></div>
      <div class="gridTitle"></div>
      <div class="gridValue"></div>
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