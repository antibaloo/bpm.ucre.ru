<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true)	die();
echo "<h1>".$arResult['DATA']['NAME']."</h1>";
$IMAGES ="";
?>
<?
$APPLICATION->IncludeComponent(
	"bitrix:main.interface.form",
	"",
	array(
		"FORM_ID" => $arResult["FORM_ID"],
	  "TABS"=>array(
			array("id"=>"tab1", "name"=>"Основная информация", "icon"=>"", "fields"=>array(
				array("id"=>"ACTIVE", "name"=>"Активен", "type"=>"checkbox"),
				array("id"=>"PROPERTY_210", "name"=>"Тип объекта", "required"=>true),
				array("id"=>"PROPERTY_209", "name"=>"Адрес"),
				array("id"=>"PROPERTY_212", "name"=>"Кадастровый номер"),
				array("id"=>"PROPERTY_206", "name"=>"Связанный объект БГ"),
				//array("id"=>"PROPERTY_206", "name"=>"Связанный объект БГ", "type" => "label" ,"value"=>'<a href="../bg/?show&id='.$arResult['DATA']['PROPERTY_206'].'">'.$arResult['DATA']['PROPERTY_206'].'</a>'),
				array("id"=>"section1", "name"=>"", "type"=>"section"),
			)),
			array("id"=>"tab2", "name"=>"Адрес", "icon"=>"", "fields"=>array(
				array("id"=>"PROPERTY_223", "name"=>"Индекс"),
				array("id"=>"PROPERTY_213", "name"=>"Субъект РФ"),
				array("id"=>"PROPERTY_214", "name"=>"Район субъекта"),
				array("id"=>"PROPERTY_215", "name"=>"Населенный пункт"),
				array("id"=>"PROPERTY_216", "name"=>"Район"),
				array("id"=>"PROPERTY_217", "name"=>"Улица"),
				array("id"=>"PROPERTY_218", "name"=>"Дом №"),
				array("id"=>"PROPERTY_219", "name"=>"Подъезд №"),
				array("id"=>"PROPERTY_221", "name"=>"Этаж №"),
				array("id"=>"PROPERTY_220", "name"=>"Квартира №"),
				array("id"=>"section1", "type"=>"section"),
			)),
			array("id"=>"tab3", "name"=>"Информация о здании", "icon"=>"", "fields"=>array(
				array("id" => "PROPERTY_222", "name" => "Этажность"),
				array("id"=>"section1", "type"=>"section"),
			)),
			array("id"=>"tab4", "name"=>"Информация о квартире", "icon"=>"", "fields"=>array(
				array("id" => "PROPERTY_229", "name" => "Количество комнат"),
				array("id" => "PROPERTY_224", "name" => "Общая площадь"),
				array("id" => "PROPERTY_225", "name" => "Жилая площадь"),
				array("id" => "PROPERTY_226", "name" => "Площадь кухни"),
				array("id"=>"section1", "type"=>"section"),
			)),
			array("id"=>"tab5", "name"=>"Изображения", "icon"=>"", "fields"=>array(
				array("id"=> "PROPERTY_237", "name"=> "Фотографии"),
				array("id"=> "PROPERTY_236", "name"=> "Планировки"),
			)),
			array("id"=>"tab6", "name"=>"Служебная информация", "icon"=>"", "fields"=>array(
				array("id"=>"ID", "name"=>"ID", "type"=>"label"),
				array("id"=>"CREATED_BY", "name"=>"Создал", "type" => "label","value" =>$arResult['USERS'][intval($arResult['DATA']['CREATED_BY'])]),
				array("id"=>"DATE_CREATE", "name"=>"Дата создания", "type" => "label"),
				array("id"=>"MODIFIED_BY", "name"=>"Изменил", "type" => "label","value" =>$arResult['USERS'][intval($arResult['DATA']['MODIFIED_BY'])]),
				array("id"=>"TIMESTAMP_X", "name"=>"Дата изменения", "type" => "label"),
				array("id"=>"section1", "type"=>"section"),
			)),
		),
		"BUTTONS"=>array("back_url"=>"../ro/", "custom_html"=>"", "standard_buttons"=>true),
		"DATA"=>$arResult["DATA"],
	),
	$component
);
?>
<?
var_dump($arResult["DATA"]);

var_dump($_POST);
?>