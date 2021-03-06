<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

$panelItems = array();
$idPrefix = "crm_control_panel_";
$crmPanelContainer = $idPrefix."container";
$menuContainerId = $idPrefix."menu";
$searchContainerId = $idPrefix."search";
$searchInputId = $searchContainerId."_input";

if (!empty($arResult["ITEMS"]) && is_array($arResult["ITEMS"]))
{
	foreach ($arResult["ITEMS"] as $key => $item)
	{
		$itemActions = isset($item["ACTIONS"]) ? array(
			"CLASS" => $item["ACTIONS"][0]["ID"] === "CREATE" ? "crm-menu-plus-btn" : "",
			"URL" => $item["ACTIONS"][0]["URL"]
		) : false;

		$panelItems[] = array(
			"TEXT" => $item["NAME"],
			"URL" => $item["URL"],
			"CLASS" => "crm-menu-".$item["ICON"]." crm-menu-item-wrap",
			"CLASS_SUBMENU_ITEM" => "crm-menu-more-".$item["ICON"],
			"ID" => $item["ID"],
			"SUB_LINK" => $itemActions,
			"COUNTER" => $item["COUNTER"] > 0 ? $item["COUNTER"] : false,
			"IS_ACTIVE" => $arResult["ACTIVE_ITEM_ID"] === $item["ID"],
			"IS_LOCKED" => $item["IS_LOCKED"] ? true : false
		);
	}

	$moreItem = array(
		"CLASS" => "crm-menu-item-wrap crm-menu-".$arResult["ADDITIONAL_ITEM"]["ICON"],
		"TEXT" => $arResult["ADDITIONAL_ITEM"]["NAME"]
	);
}

$arResult["CRM_PANEL_CONTAINER_ID"] = $crmPanelContainer;
$arResult["CRM_PANEL_MENU_CONTAINER_ID"] = $menuContainerId;
$arResult["CRM_PANEL_SEARCH_CONTAINER_ID"] = $searchContainerId;
$arResult["CRM_PANEL_SEARCH_INPUT_ID"] = $searchInputId;
$arResult["ITEMS"] = $panelItems;
$arResult["MORE_ITEM"] = $moreItem;