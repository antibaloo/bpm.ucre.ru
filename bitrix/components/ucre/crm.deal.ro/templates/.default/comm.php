<?php
if(!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED!==true) die();
?>
<div style="background-color: white; padding: 5px;">
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
        <div class="crm-offer-info-label-wrap"><span class="crm-offer-info-label"></span></div>
      </td><td class="crm-offer-info-right">
      <div class="crm-offer-info-label-wrap"><span class="crm-offer-info-label"></span></div>
      </td>
    </tr>
    <tr class="crm-offer-row">
      <td class="crm-offer-info-drg-btn"></td>
      <td class="crm-offer-info-left">
        <div class="crm-offer-info-label-wrap"><span class="crm-offer-info-label">Назначение:</span></div>
      </td>
      <td class="crm-offer-info-right">
        <div class="crm-offer-info-label-wrap"><span class="crm-offer-info-label"><?=$arResult['APPOINTMENT']?></span></div>
      </td>
      <td class="crm-offer-info-left">
        <div class="crm-offer-info-label-wrap"><span class="crm-offer-info-label">Парковка/Охрана:</span></div>
      </td><td class="crm-offer-info-right">
      <div class="crm-offer-info-label-wrap"><span class="crm-offer-info-label"><?=$arResult['PARKING']?>/<?=$arResult['PARKING']?></span></div>
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
    <tr class="crm-offer-row">
      <td class="crm-offer-info-drg-btn"></td>
      <td class="crm-offer-info-left">
        <div class="crm-offer-info-label-wrap"><span class="crm-offer-info-label">Кадастровый номер:</span></div>
      </td>
      <td class="crm-offer-info-right">
        <div class="crm-offer-info-label-wrap"><span class="crm-offer-info-label"><?=$arResult['KAD_NUMBER']?></span></div>
      </td>
      <td class="crm-offer-info-left">
        <div class="crm-offer-info-label-wrap"><span class="crm-offer-info-label">Ссылка на сайт:</span></div>
      </td><td class="crm-offer-info-right">
      <div class="crm-offer-info-label-wrap"><span class="crm-offer-info-label"><?=$arResult['LINK']?></span></div>
      </td>
    </tr>
  </tbody>
</table>
</div>