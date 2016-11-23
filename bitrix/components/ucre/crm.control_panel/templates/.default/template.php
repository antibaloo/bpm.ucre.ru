<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true)
{
	die();
} 

$APPLICATION->SetAdditionalCSS("/bitrix/js/crm/css/crm.css");

CJSCore::Init(array("pin"));

?>

<div id="<?=$arResult["CRM_PANEL_CONTAINER_ID"]?>" class="bx-pin crm-header"
	 data-pin-top="true"
	 data-pin-set-outer-width="true"
	 data-pin-use-controll="true"
	 data-pin-top-class="crm-menu-fixed"
	 data-pin-controll-class="crm-menu-fixed-btn"
	 data-pin-controll-pin-class="crm-lead-header-contact-btn-pin"
	 data-pin-controll-unpin-class="crm-lead-header-contact-btn-unpin"
	 data-pin-use-outer-width="workarea-content-paddings">
	<div class="crm-header-inner">
		<div class="crm-menu-wrap">
			<?$APPLICATION->IncludeComponent(
				"bitrix:main.interface.buttons",
				"",
				array(
					"ID" => $arResult["CRM_PANEL_MENU_CONTAINER_ID"],
					"CLASS_ITEM_LINK" => "crm-menu-item",
					"CLASS_ITEM_ICON" => "crm-menu-icon",
					"CLASS_ITEM_TEXT" => "crm-menu-name",
					"CLASS_ITEM_COUNTER" => "crm-menu-icon-counter",
					"CLASS_ITEM_ACTIVE" => "crm-menu-item-active",
					"ITEMS" => $arResult["ITEMS"],
					"MORE_BUTTON" => $arResult["MORE_ITEM"]
				)
			);?>			
		</div>
		<div class="crm-search-wrap">
			<? if ($arResult["ENABLE_SEARCH"]) : ?>
				<span id="<?=$arResult["CRM_PANEL_SEARCH_CONTAINER_ID"]?>" class="crm-search-block">
					<form class="crm-search" action="<?=htmlspecialcharsbx($arResult["SEARCH_PAGE_URL"])?>" method="get">
						<button type="submit" class="crm-search-btn"></button>
						<span class="crm-search-inp-wrap"><input 
							id="<?=$arResult["CRM_PANEL_SEARCH_INPUT_ID"]?>" 
							class="crm-search-inp" 
							name="q" 
							type="text" 
							autocomplete="off" 
							placeholder="<?=GetMessage("CRM_CONTROL_PANEL_SEARCH_PLACEHOLDER")?>"/></span>
						<input type="hidden" name="where" value="crm"><?
						$APPLICATION->IncludeComponent(
							"bitrix:search.title",
							"backend",
							array(
								"NUM_CATEGORIES" => 1,
								"CATEGORY_0_TITLE" => "CRM",
								"CATEGORY_0" => array(0 => "crm"),
								"USE_LANGUAGE_GUESS" => "N",
								"PAGE" => $arResult["PATH_TO_SEARCH_PAGE"],
								"CONTAINER_ID" => $arResult["CRM_PANEL_SEARCH_CONTAINER_ID"],
								"INPUT_ID" => $arResult["CRM_PANEL_SEARCH_INPUT_ID"],
								"SHOW_INPUT" => "N"
							),
							$component,
							array("HIDE_ICONS"=>true)
						);
					?></form>
				</span>
			<? endif; ?>			
		</div>	
	</div>
	<div class="crm-menu-shadow"></div>	
</div>