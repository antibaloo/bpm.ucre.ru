<?php
if(!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED!==true) die();
?>
<table class="crm-offer-info-table crm-offer-main-info-text">
  <tbody>
    <tr><td colspan="5"><div class="crm-offer-title">Характеристики объекта по адресу: <?=$arResult['ADDRESS']?></div></td></tr>
    <tr class="crm-offer-row">
      <td class="crm-offer-info-drg-btn"></td>
      <td class="crm-offer-info-left">
        <div class="crm-offer-info-label-wrap"><span class="crm-offer-info-label">Тип недвижимости:</span></div>
      </td>
      <td class="crm-offer-info-right">
        <div class="crm-offer-info-label-wrap"><span class="crm-offer-info-label"><?=$arResult['TYPE']?></span></div>
      </td>
      <td class="crm-offer-info-left">
        <div class="crm-offer-info-label-wrap"><span class="crm-offer-info-label">Ссылка на сайте:</span></div>
      </td><td class="crm-offer-info-right">
      <div class="crm-offer-info-label-wrap"><span class="crm-offer-info-label"><?=$arResult['LINK']?></span></div>
      </td>
    </tr>
    <tr class="crm-offer-row">
      <td class="crm-offer-info-drg-btn"></td>
      <td class="crm-offer-info-left">
        <div class="crm-offer-info-label-wrap"><span class="crm-offer-info-label">Плошадь комнаты:</span></div>
      </td>
      <td class="crm-offer-info-right">
        <div class="crm-offer-info-label-wrap"><span class="crm-offer-info-label"><?=$arResult['ROOM_AREA']?></span></div>
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
        <div class="crm-offer-info-label-wrap"><span class="crm-offer-info-label">Этаж:</span></div>
      </td>
      <td class="crm-offer-info-right">
        <div class="crm-offer-info-label-wrap"><span class="crm-offer-info-label"><?=$arResult['FLOOR']?></span></div>
      </td>
      <td class="crm-offer-info-left">
        <div class="crm-offer-info-label-wrap"><span class="crm-offer-info-label">Этажность:</span></div>
      </td><td class="crm-offer-info-right">
      <div class="crm-offer-info-label-wrap"><span class="crm-offer-info-label"><?=$arResult['FLOORALL']?></span></div>
      </td>
    </tr>
  </tbody>
</table>