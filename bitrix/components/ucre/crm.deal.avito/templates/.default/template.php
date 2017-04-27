<?php
if(!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED!==true) die();
?>
<table class="crm-offer-info-table crm-offer-main-info-text">
  <tbody>
    <tr><td colspan="5"><div class="crm-offer-title">Заявка создана из лида по данным парсера Авито-Недвижимость</div></td></tr>
    <tr class="crm-offer-row">
			<td class="crm-offer-info-drg-btn"></td>
			<td class="crm-offer-info-left">
				<div class="crm-offer-info-label-wrap"><span class="crm-offer-info-label">Ссылка на объявление:</span></div>
			</td>
			<td class="crm-offer-info-right">
				<div class="crm-offer-info-label-wrap"><span class="crm-offer-info-label"><?=$arResult['LINK']?></span></div>
			</td>
			<td class="crm-offer-info-left">
				<div class="crm-offer-info-label-wrap"><span class="crm-offer-info-label">Профиль Авито:</span></div>
			</td>
			<td class="crm-offer-info-right">
				<div class="crm-offer-info-label-wrap"><span class="crm-offer-info-label"><?=$arResult['PROFILE']?></span></div>
			</td>
		</tr>
    <tr class="crm-offer-row">
			<td class="crm-offer-info-drg-btn"></td>
			<td class="crm-offer-info-left">
				<div class="crm-offer-info-label-wrap"><span class="crm-offer-info-label">Фотографии с Авито:</span></div>
			</td>
			<td class="crm-offer-info-right" colspan="3">
				<div class="crm-offer-info-label-wrap" style="text-align: center;">
          <?=$arResult['PHOTO']?>
				</div>
			</td>
		</tr>
    <tr class="crm-offer-row">
			<td class="crm-offer-info-drg-btn"></td>
			<td class="crm-offer-info-left">
				<div class="crm-offer-info-label-wrap"><span class="crm-offer-info-label">Площади (общ./жил./кух.):</span></div>
			</td>
			<td class="crm-offer-info-right">
				<div class="crm-offer-info-label-wrap">
					<span class="crm-offer-info-label">
						<?=$arResult['SQUARE']?>
					</span>
				</div>
			</td>
			<td class="crm-offer-info-left">
				<div class="crm-offer-info-label-wrap"><span class="crm-offer-info-label">Этаж/Этажность:</span></div>
			</td>
			<td class="crm-offer-info-right">
				<div class="crm-offer-info-label-wrap">
					<span class="crm-offer-info-label">
						<?=$arResult['FLOORS']?>
					</span>
				</div>
			</td>
		</tr>
    <tr class="crm-offer-row">
			<td class="crm-offer-info-drg-btn"></td>
			<td class="crm-offer-info-left">
				<div class="crm-offer-info-label-wrap"><span class="crm-offer-info-label">Адрес:</span></div>
			</td>
			<td class="crm-offer-info-right">
				<div class="crm-offer-info-label-wrap"><span class="crm-offer-info-label"><?=$arResult['ADDRESS']?></span></div>
			</td>
			<td class="crm-offer-info-left">
				<div class="crm-offer-info-label-wrap">
					<span class="crm-offer-info-label">
						<form id="object">
							<input type="hidden" name="deal_id" value="<?=$arResult['ID']?>">
							<input type="button" id="create" value="Создать объект" <?=$arResult['BUTTON']?>/>
						</form>
					</span>
				</div>
			</td>
			<td class="crm-offer-info-right">
				<div class="crm-offer-info-label-wrap">
					<span class="crm-offer-info-label">
						<div id="result"><?=$arResult['RESULT']?></div>
					</span>
				</div>
			</td>
		</tr>
  </tbody>
</table>