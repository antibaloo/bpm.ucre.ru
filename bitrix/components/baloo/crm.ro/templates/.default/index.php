<?
if(!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED!==true) die();
?>
<?
if (in_array($USER->GetID(), array(24,26,1,11,12,44,98,202,203))) {
$APPLICATION->IncludeComponent(
	"bitrix:main.interface.toolbar",
	"",
	array(
		"BUTTONS"=>array(
			array(
				"TEXT"=>"Добавить новый объект",
				"TITLE"=>"Добавить новый объект недвижимости",
				"LINK_PARAM"=>"target='_blank'",
				"LINK"=>"https://bpm.ucre.ru/bitrix/admin/iblock_element_edit.php?IBLOCK_ID=42&type=CRM_PRODUCT_CATALOG&ID=0",
				"ICON"=>"btn-new",
			),
			array(
				"TEXT"=>"Объекты недвижимости",
				"TITLE"=>"Список объектов недвижимости",
				"LINK"=>".",
				"ICON"=>"btn-list",
			),
			/*array("SEPARATOR"=>true), */
			array(
				"TEXT"=>"База города",
				"TITLE"=>"Список объектов базы города",
				"LINK"=>"../bg/",
				"ICON"=>"btn-list",
			),
		),
	),
	$component
);
}else{
	$APPLICATION->IncludeComponent(
		"bitrix:main.interface.toolbar",
		"",
		array(
			"BUTTONS"=>array(
				array(
					"TEXT"=>"Объекты недвижимости",
					"TITLE"=>"Список объектов недвижимости",
					"LINK"=>".",
					"ICON"=>"btn-list",
				),
				array(
					"TEXT"=>"База города",
					"TITLE"=>"Список объектов базы города",
					"LINK"=>"../bg/",
					"ICON"=>"btn-list",
				),
			),
		),
		$component
	);
}


?>


<?
$APPLICATION->IncludeComponent(
	"bitrix:main.interface.grid",
	"",
	array("GRID_ID"=>$arResult["GRID_ID"],
				"HEADERS"=>array(array("id"=>"ID", "name"=>"ИД", "sort"=>"ID", "default"=>true, "align"=>"left"),
												 /*array("id"=>"CODE", "name"=>"КОД", "sort"=>"CODE", "default"=>true, "align"=>"left"),*/
												 array("id"=>"PROPERTY_210", "name"=>"Тип объекта", "sort"=>"PROPERTY_210", "default"=>true, "align"=>"left"),
												 array("id"=>"PROPERTY_266", "name"=>"Статус", "sort"=>"PROPERTY_266", "default"=>true),
												 array("id"=>"PROPERTY_262", "name"=>"Тип договора", "sort"=>"PROPERTY_262", "default"=>true),
												 array("id"=>"PROPERTY_209", "name"=>"Адрес", "sort"=>"PROPERTY_209", "default"=>true),
												 array("id"=>"PROPERTY_321", "name"=>"Цена", "sort"=>"PROPERTY_321", "default"=>true,"align"=>"right"),
												 array("id"=>"PROPERTY_212", "name"=>"Кадастровый номер", "sort"=>"PROPERTY_212", "default"=>true),
												 array("id"=>"PROPERTY_206", "name"=>"Объект БГ", "sort"=>"PROPERTY_206",	"default"=>true),
												 array("id"=>"PROPERTY_313", "name"=>"Ответственный", "sort"=>"CREATED_BY",	"default"=>true),
												 array("id"=>"CREATED_BY", "name"=>"Создал", "sort"=>"CREATED_BY",	"default"=>true),
												 array("id"=>"MODIFIED_BY", "name"=>"Изменил", "sort"=>"MODIFIED_BY",	"default"=>true),
												 /*array("id"=>"DATE_CREATE", "name"=>"Дата создания", "sort"=>"DATE_CREATE",	"default"=>true),
												 array("id"=>"TIMESTAMP_X", "name"=>"Дата изменения", "sort"=>"TIMESTAMP_X",	"default"=>true),*/
												 array("id"=>"PROPERTY_260", "name"=>"Срок Avito", "sort"=>"PROPERTY_260",	"default"=>true),
												 array("id"=>"PROPERTY_219", "name"=>"Подъезд", "sort"=>"PROPERTY_219"),
												),
				"SORT"=>$arResult["SORT"],
				"SORT_VARS"=>$arResult["SORT_VARS"],
				"ROWS"=>$arResult["ROWS"],
				"FOOTER"=>array(array("title"=>"Всего", "value"=>$arResult["ROWS_COUNT"])),
		"FILTER_TEMPLATE_NAME" => ".default", //tabbed
		"ACTION_ALL_ROWS"=>true,
		"EDITABLE"=>true,
		"NAV_OBJECT"=>$arResult["NAV_OBJECT"],
		"AJAX_MODE"=>"Y",
		"AJAX_OPTION_JUMP"=>"N",
		"AJAX_OPTION_STYLE"=>"Y",
		"FILTER"=>$arResult["FILTER"],
	),
	$component
);
?>