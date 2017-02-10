<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true)	die();
?>
<link rel="stylesheet" href="/bitrix/js/baloo/fancybox/source/jquery.fancybox.css?v=2.1.5" type="text/css" media="screen" />
<script type="text/javascript" src="/bitrix/js/baloo/fancybox/source/jquery.fancybox.pack.js?v=2.1.5"></script>
<?
$property_enums = CIBlockPropertyEnum::GetList(Array(), Array("IBLOCK_ID"=>42, "CODE"=>"TYPE"));
$ro_type = array();
while($enum_fields = $property_enums->GetNext())
{
	$ro_type[$enum_fields["ID"]] = $enum_fields["VALUE"];
}
$images = "";
$plans = "";
$objdocs = "";
$ownerdocs = "";
$file = new CFile();
foreach ($arResult['DATA']['PROPERTY_237'] as $imageid){
	$fileInfo = $file->GetFileArray($imageid);
	$images .='<a class="fancybox" rel="gallery1" href="https://bpm.ucre.ru'.$fileInfo['SRC'].'"><img src="https://bpm.ucre.ru/'.$fileInfo['SRC'].'" width="auto" height="150" alt=""/></a>&nbsp;';
	//$images .= CFile::ShowImage($imageid, 250, 150, "border=0", "", true)." ";
}
foreach ($arResult['DATA']['PROPERTY_236'] as $imageid){
	$fileInfo = $file->GetFileArray($imageid);
	$plans .='<a class="fancybox" rel="gallery1" href="https://bpm.ucre.ru'.$fileInfo['SRC'].'"><img src="https://bpm.ucre.ru/'.$fileInfo['SRC'].'" width="auto" height="150" alt=""/></a>&nbsp;';
	//$plans .= CFile::ShowImage($imageid, 250, 150, "border=0", "", true)." ";
}
foreach ($arResult['DATA']['PROPERTY_293'] as $key=>$objid){
	$i=$key+1;
	$objdocs .= '<a href="'.CFile::GetPath($objid).'" target="_blank">Документ №'.$i.'</a><br>';
}
foreach ($arResult['DATA']['PROPERTY_294'] as $ownerid){
	$i=$key+1;
	$ownerdocs .='<a href="'.CFile::GetPath($ownerid).'" target="_blank">Документ №'.$i.'</a><br>';
}
?>
<?
if (in_array($USER->GetID(), array(24))){
	echo "<h1>".$arResult['DATA']['NAME']."</h1>";
$APPLICATION->IncludeComponent(
	"bitrix:main.interface.form",
	"",
	array(
		"FORM_ID" => $arResult["FORM_ID"],
	  "TABS"=>array(
			array("id"=>"tab1", "name"=>"Основная информация", "icon"=>"", "fields"=>array(
				array("id"=>"PROPERTY_266", "name"=>"Статус", "type"=>"label"),
				array("id"=>"PROPERTY_300", "name"=>"Операция", "type"=>"label"),
	array("id"=>"PROPERTY_321", "name"=>"Цена", "type"=>"label","value" => number_format($arResult['DATA']['PROPERTY_321'], 2, '.', ' ').' руб.'),
				array("id"=>"PROPERTY_210", "name"=>"Тип объекта", "type" => "label", "value" => $ro_type[$arResult['DATA']['PROPERTY_210']]),
				array("id"=>"PROPERTY_209", "name"=>"Адрес", "type" => "label"),
				array("id"=>"PROPERTY_212", "name"=>"Кадастровый номер", "type" => "label"),
				array("id"=>"PROPERTY_206", "name"=>"Связанный объект БГ", "type" => "label" ,"value"=>'<a href="../bg/?show&id='.$arResult['DATA']['PROPERTY_206'].'">'.$arResult['DATA']['PROPERTY_206'].'</a>'),
				array("id"=>"PROPERTY_319", "name"=>"Заявка по объекту", "type"=>"label", "value"=>'<a href="../deal/show/'.intval($arResult['DATA']['PROPERTY_319']).'/">'.'Заявка № '.intval($arResult['DATA']['PROPERTY_319']).'</a>'),
				array("id"=>"section1", "name"=>"", "type"=>"section"),
			)),
			array("id"=>"tab2", "name"=>"Адрес", "icon"=>"", "fields"=>array(
				array("id"=>"PROPERTY_223", "name"=>"Индекс", "type" => "label"),
				array("id"=>"PROPERTY_213", "name"=>"Субъект РФ", "type" => "label"),
				array("id"=>"PROPERTY_214", "name"=>"Район субъекта", "type" => "label"),
				array("id"=>"PROPERTY_215", "name"=>"Населенный пункт", "type" => "label"),
				array("id"=>"PROPERTY_216", "name"=>"Район", "type" => "label"),
				array("id"=>"PROPERTY_217", "name"=>"Улица", "type" => "label"),
				array("id"=>"PROPERTY_218", "name"=>"Дом №", "type" => "label"),
				array("id"=>"section1", "type"=>"section"),
			)),
			array("id"=>"tab5", "name"=>"Данные собственника", "icon"=>"", "fields"=>array(
				array("id" => ($arResult['DATA']['PROPERTY_246']=='Ф') ? "PROPERTY_244":"PROPERTY_245", "name" => ($arResult['DATA']['PROPERTY_246']=='Ф') ? "ФИО собственника":"Наименование", "type" => "label"),
				array("id"=>"PROPERTY_247", "name"=>"Телефон","type"=>"label"),
				array("id"=>"PROPERTY_248", "name"=>"Телефон (дополнительно)","type"=>"label"),
				array("id" => ($arResult['DATA']['PROPERTY_246']=='Ф') ? "PROPERTY_255":"PROPERTY_249", "name" => ($arResult['DATA']['PROPERTY_246']=='Ф') ? "Мобильный":"Факс", "type" => "label"),
				array("id"=>"PROPERTY_250", "name"=>"E-mail","type"=>"label"),
				array("id"=>"PROPERTY_251", "name"=>"E-mail (дополнительно)","type"=>"label"),
				array("id"=>"PROPERTY_252", "name"=>"WWW-страница","type"=>"label"),
				array("id" => ($arResult['DATA']['PROPERTY_246']=='Ф') ? "PROPERTY_256":"PROPERTY_253", "name" => ($arResult['DATA']['PROPERTY_246']=='Ф') ? "Домашний телефон":"ИНН", "type" => "label"),
				array("id" => ($arResult['DATA']['PROPERTY_246']=='Ф') ? "PROPERTY_257":"PROPERTY_254", "name" => ($arResult['DATA']['PROPERTY_246']=='Ф') ? "Дата рождения":"КПП", "type" => "label"),
			)),
			array("id"=>"tab6", "name"=>"Изображения", "icon"=>"", "fields"=>array(
				array("id" => "PROPERTY_237", "name" => "Фотографии", "type" => "custom", "value"=>$images),
				array("id" => "PROPERTY_236", "name" => "Планировки", "type" => "custom", "value"=>$plans),
			)),
			array("id"=>"tab10", "name"=>"Приложения", "icon"=>"", "fields"=>array(
				array("id" => "PROPERTY_293", "name" => "Документы объекта", "type" => "custom", "value"=>$objdocs),
				array("id" => "PROPERTY_294", "name" => "Документы собственника", "type" => "custom", "value"=>$ownerdocs),
			)),
			array("id"=>"tab7", "name"=>"Описание", "icon"=>"", "fields"=>array(
				array("id"=>"DETAIL_TEXT", "name" => "Подробное описание объекта", "type"=>"label"),
			)),
			array("id"=>"tab8", "name"=>"Информация о договоре", "icon"=>"", "fields"=>array(
				array("id"=>"PROPERTY_261", "name" => "Номер договора", "type"=>"label"),
				array("id"=>"PROPERTY_262", "name" => "Тип договора", "type"=>"label"),
				array("id"=>"PROPERTY_263", "name" => "Дата заключения", "type"=>"label"),
				array("id"=>"PROPERTY_264", "name" => "Дата окончания", "type"=>"label"),
			)),
			array("id"=>"tab9", "name"=>"Служебная информация", "icon"=>"", "fields"=>array(
				array("id"=>"ID", "name"=>"ID", "type"=>"label"),
				array("id"=>"CODE", "name"=>"Код объекта ecrplus.ru", "type"=>"label"),
				array("id"=>"ASSIGNED_BY", "name"=>"Ответственный", "type" => "label","value" =>$arResult['USERS'][intval($arResult['DATA']['PROPERTY_313'])]),
				array("id"=>"DATE_CREATE", "name"=>"Дата создания", "type" => "label"),
				array("id"=>"MODIFIED_BY", "name"=>"Изменил", "type" => "label","value" =>$arResult['USERS'][intval($arResult['DATA']['MODIFIED_BY'])]),
				array("id"=>"TIMESTAMP_X", "name"=>"Дата изменения", "type" => "label"),
				array("id"=>"PROPERTY_260", "name"=>"Срок выгрузки на Авито", "type" => "label"),
				array("id"=>"section1", "type"=>"section"),
			)),
		),
		"BUTTONS"=>array("back_url"=>".", "custom_html"=>"<input type='button' value='Вернуться' name='back' onclick='window.location=&quot;.&quot;' title='Вернуться'>", "standard_buttons"=>false),
		"DATA"=>$arResult["DATA"],
	),
	$component
);
} else{
	echo "<h1>".$arResult['DATA']['NAME']."</h1>";
$APPLICATION->IncludeComponent(
	"bitrix:main.interface.form",
	"",
	array(
		"FORM_ID" => $arResult["FORM_ID"],
	  "TABS"=>array(
			array("id"=>"tab1", "name"=>"Основная информация", "icon"=>"", "fields"=>array(
				array("id"=>"PROPERTY_266", "name"=>"Статус", "type"=>"label"),
				array("id"=>"PROPERTY_300", "name"=>"Операция", "type"=>"label"),
	array("id"=>"PROPERTY_321", "name"=>"Цена", "type"=>"label","value" => number_format($arResult['DATA']['PROPERTY_321'], 2, '.', ' ').' руб.'),
				array("id"=>"PROPERTY_210", "name"=>"Тип объекта", "type" => "label", "value" => $ro_type[$arResult['DATA']['PROPERTY_210']]),
				array("id"=>"PROPERTY_209", "name"=>"Адрес", "type" => "label"),
				array("id"=>"PROPERTY_212", "name"=>"Кадастровый номер", "type" => "label"),
				array("id"=>"PROPERTY_206", "name"=>"Связанный объект БГ", "type" => "label" ,"value"=>'<a href="../bg/?show&id='.$arResult['DATA']['PROPERTY_206'].'">'.$arResult['DATA']['PROPERTY_206'].'</a>'),
				array("id"=>"PROPERTY_319", "name"=>"Заявка по объекту", "type"=>"label", "value"=>'<a href="../deal/show/'.intval($arResult['DATA']['PROPERTY_319']).'/">'.'Заявка № '.intval($arResult['DATA']['PROPERTY_319']).'</a>'),
				array("id"=>"section1", "name"=>"", "type"=>"section"),
			)),
			array("id"=>"tab2", "name"=>"Адрес", "icon"=>"", "fields"=>array(
				array("id"=>"PROPERTY_223", "name"=>"Индекс", "type" => "label"),
				array("id"=>"PROPERTY_213", "name"=>"Субъект РФ", "type" => "label"),
				array("id"=>"PROPERTY_214", "name"=>"Район субъекта", "type" => "label"),
				array("id"=>"PROPERTY_215", "name"=>"Населенный пункт", "type" => "label"),
				array("id"=>"PROPERTY_216", "name"=>"Район", "type" => "label"),
				array("id"=>"PROPERTY_217", "name"=>"Улица", "type" => "label"),
				array("id"=>"PROPERTY_218", "name"=>"Дом №", "type" => "label"),
				array("id"=>"section1", "type"=>"section"),
			)),
			array("id"=>"tab5", "name"=>"Данные собственника", "icon"=>"", "fields"=>array(
				array("id" => ($arResult['DATA']['PROPERTY_246']=='Ф') ? "PROPERTY_244":"PROPERTY_245", "name" => ($arResult['DATA']['PROPERTY_246']=='Ф') ? "ФИО собственника":"Наименование", "type" => "label"),
			)),
			array("id"=>"tab6", "name"=>"Изображения", "icon"=>"", "fields"=>array(
				array("id" => "PROPERTY_237", "name" => "Фотографии", "type" => "custom", "value"=>$images),
				array("id" => "PROPERTY_236", "name" => "Планировки", "type" => "custom", "value"=>$plans),
			)),
			array("id"=>"tab7", "name"=>"Описание", "icon"=>"", "fields"=>array(
				array("id"=>"DETAIL_TEXT", "name" => "Подробное описание объекта", "type"=>"label"),
			)),
			array("id"=>"tab8", "name"=>"Информация о договоре", "icon"=>"", "fields"=>array(
				array("id"=>"PROPERTY_261", "name" => "Номер договора", "type"=>"label"),
				array("id"=>"PROPERTY_262", "name" => "Тип договора", "type"=>"label"),
				array("id"=>"PROPERTY_263", "name" => "Дата заключения", "type"=>"label"),
				array("id"=>"PROPERTY_264", "name" => "Дата окончания", "type"=>"label"),
			)),
			array("id"=>"tab9", "name"=>"Служебная информация", "icon"=>"", "fields"=>array(
				array("id"=>"ID", "name"=>"ID", "type"=>"label"),
				array("id"=>"CODE", "name"=>"Код объекта ecrplus.ru", "type"=>"label"),
				array("id"=>"ASSIGNED_BY", "name"=>"Ответственный", "type" => "label","value" =>$arResult['USERS'][intval($arResult['DATA']['PROPERTY_313'])]),
				array("id"=>"DATE_CREATE", "name"=>"Дата создания", "type" => "label"),
				array("id"=>"MODIFIED_BY", "name"=>"Изменил", "type" => "label","value" =>$arResult['USERS'][intval($arResult['DATA']['MODIFIED_BY'])]),
				array("id"=>"TIMESTAMP_X", "name"=>"Дата изменения", "type" => "label"),
				array("id"=>"PROPERTY_260", "name"=>"Срок выгрузки на Авито", "type" => "label"),
				array("id"=>"section1", "type"=>"section"),
			)),
		),
		"BUTTONS"=>array("back_url"=>".", "custom_html"=>"<input type='button' value='Вернуться' name='back' onclick='window.location=&quot;.&quot;' title='Вернуться'>", "standard_buttons"=>false),
		"DATA"=>$arResult["DATA"],
	),
	$component
);
}
?>