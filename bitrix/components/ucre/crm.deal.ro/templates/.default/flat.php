<?php
if(!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED!==true) die();
?>
<table class="crm-offer-info-table crm-offer-main-info-text">
  <tbody>
    <tr><td colspan="5"><div class="crm-offer-title">Характеристики объекта по адресу: <?=$arResult['ADDRESS']?>, <?=($arResult['LINK'])?"<a href='".$arResult['LINK']."' target='_blank'>ссылка на сайт</a>":"<span style='color: red'>ссылки на сайт нет</span>"?></div></td></tr>
    <tr class="crm-offer-row">
      <td class="crm-offer-info-drg-btn"></td>
      <td class="crm-offer-info-left">
        <div class="crm-offer-info-label-wrap"><span class="crm-offer-info-label">Тип недвижимости:</span></div>
      </td>
      <td class="crm-offer-info-right">
        <div class="crm-offer-info-label-wrap"><span class="crm-offer-info-label"><?=$arResult['TYPE']?></span></div>
      </td>
      <td class="crm-offer-info-left">
        <div class="crm-offer-info-label-wrap"><span class="crm-offer-info-label">Тип дома:</span></div>
      </td><td class="crm-offer-info-right">
      <div class="crm-offer-info-label-wrap"><span class="crm-offer-info-label"><?=$arResult['HOUSE_TYPE']?></span></div>
      </td>
    </tr>
    <tr class="crm-offer-row">
      <td class="crm-offer-info-drg-btn"></td>
      <td class="crm-offer-info-left">
        <div class="crm-offer-info-label-wrap"><span class="crm-offer-info-label">Общая плошадь:</span></div>
      </td>
      <td class="crm-offer-info-right">
        <div class="crm-offer-info-label-wrap"><span class="crm-offer-info-label"><?=$arResult['TOTAL_AREA']?></span></div>
      </td>
      <td class="crm-offer-info-left">
        <div class="crm-offer-info-label-wrap"><span class="crm-offer-info-label">Тип балкона:</span></div>
      </td><td class="crm-offer-info-right">
      <div class="crm-offer-info-label-wrap"><span class="crm-offer-info-label"><?=$arResult['BALKON_TYPE']?></span></div>
      </td>
    </tr>
    <tr class="crm-offer-row">
      <td class="crm-offer-info-drg-btn"></td>
      <td class="crm-offer-info-left">
        <div class="crm-offer-info-label-wrap"><span class="crm-offer-info-label">Жилая площадь:</span></div>
      </td>
      <td class="crm-offer-info-right">
        <div class="crm-offer-info-label-wrap"><span class="crm-offer-info-label"><?=$arResult['LIVE_AREA']?></span></div>
      </td>
      <td class="crm-offer-info-left">
        <div class="crm-offer-info-label-wrap"><span class="crm-offer-info-label">Этаж:</span></div>
      </td><td class="crm-offer-info-right">
      <div class="crm-offer-info-label-wrap"><span class="crm-offer-info-label"><?=$arResult['FLOOR']?></span></div>
      </td>
    </tr>
    <tr class="crm-offer-row">
      <td class="crm-offer-info-drg-btn"></td>
      <td class="crm-offer-info-left">
        <div class="crm-offer-info-label-wrap"><span class="crm-offer-info-label">Площадь кухни:</span></div>
      </td>
      <td class="crm-offer-info-right">
        <div class="crm-offer-info-label-wrap"><span class="crm-offer-info-label"><?=$arResult['KITCHEN_AREA']?></span></div>
      </td>
      <td class="crm-offer-info-left">
        <div class="crm-offer-info-label-wrap"><span class="crm-offer-info-label">Этажность:</span></div>
      </td><td class="crm-offer-info-right">
      <div class="crm-offer-info-label-wrap"><span class="crm-offer-info-label"><?=$arResult['FLOORALL']?></span></div>
      </td>
    </tr>
    <tr class="crm-offer-row">
      <td class="crm-offer-info-drg-btn"></td>
      <td class="crm-offer-info-left">
        <div class="crm-offer-info-label-wrap"><span class="crm-offer-info-label">Населенный пункт:</span></div>
      </td>
      <td class="crm-offer-info-right">
        <div class="crm-offer-info-label-wrap"><span class="crm-offer-info-label"><?=$arResult['CITY']?></span></div>
      </td>
      <td class="crm-offer-info-left">
        <div class="crm-offer-info-label-wrap"><span class="crm-offer-info-label">Район:</span></div>
      </td><td class="crm-offer-info-right">
      <div class="crm-offer-info-label-wrap"><span class="crm-offer-info-label"><?=$arResult['LOCALITY']?></span></div>
      </td>
    </tr>
  </tbody>
</table>