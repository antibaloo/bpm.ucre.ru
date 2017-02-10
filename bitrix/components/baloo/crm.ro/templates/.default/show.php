<?
if(!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED!==true) die();
?>
<?
if (in_array($USER->GetID(), array(24,26,1,11,12,44,98))) {
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
				array("SEPARATOR"=>true), 
				array(
					"TEXT"=>"База города",
					"TITLE"=>"Список объектов базы города",
					"LINK"=>"../bg/",
					"ICON"=>"btn-list",
				),
				array("SEPARATOR"=>true),
				array(
					"TEXT"=>"Редактировать",
					"TITLE"=>"Редактировать текущий объект",
					"LINK_PARAM"=>"target='_blank'",
					"LINK"=>"https://bpm.ucre.ru/bitrix/admin/iblock_element_edit.php?IBLOCK_ID=42&type=CRM_PRODUCT_CATALOG&ID=".$_REQUEST['id']."&lang=ru&find_section_section=-1&WF=Y",
					"ICON"=>"btn-copy",
				),
			),
		),
		$component
	);
}else {
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
				array("SEPARATOR"=>true), 
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
	"baloo:crm.ro.show",
	"",
	array(
	'ID' => $_REQUEST['id'],
	'USERS' => $arResult['USERS'],
),
	$component
);
?>


<!--<object data="https://bpm.ucre.ru/upload/iblock/ae4/%D0%9F%D0%94_%D0%96%D0%9A_%D0%92%D0%B8%D0%BA%D1%82%D0%BE%D1%80%D0%B8%D1%8F.pdf" type="application/pdf" width="200" height="150"> 
<p><a href="https://bpm.ucre.ru/upload/iblock/ae4/%D0%9F%D0%94_%D0%96%D0%9A_%D0%92%D0%B8%D0%BA%D1%82%D0%BE%D1%80%D0%B8%D1%8F.pdf">Скачать PDF</a></p> 
</object>