<?
if(!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED!==true) die();
?>
<?
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
?>
<?$APPLICATION->IncludeComponent(
	"baloo:crm.ro.edit",
	"",
	array(
	'USERS' => $arResult['USERS'],
),
	$component
);
?>
<?
echo "Результаты работы компонента: <br>";
var_dump($arResult);
?>
<br>
<?
echo "Параметры адресной строки: <br>";
var_dump($_REQUEST);
?>