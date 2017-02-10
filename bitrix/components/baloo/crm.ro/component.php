<?
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED!==true)die();//Запрет вызова из адресной строки браузера

$property_enums = CIBlockPropertyEnum::GetList(Array(), Array("IBLOCK_ID"=>42, "CODE"=>"TYPE"));
$ro_type = array(""=>"(выберите тип)");
while($enum_fields = $property_enums->GetNext())
{
	$ro_type[$enum_fields["ID"]] = $enum_fields["VALUE"];
}

$bg_filter = array(""=>"(нет объекта)");

$bg_res = CIBlockElement::GetList(array("ID"=>"desc"), array("IBLOCK_ID" => 43), false, false, array("ID","NAME"));
while($bgRes = $bg_res->GetNext()){
	$bg_filter[$bgRes["ID"]] = htmlspecialchars_decode($bgRes["NAME"], ENT_QUOTES);
}

$users_filter = array (/*"ACTIVE" => "Y",*/ "GROUPS_ID" => array(12));
$aUsers = array ();
$users_res = CUser::GetList(($by="id"),($order="asc"),$users_filter);
$u_f = array(""=>"(выберите ответственного)");
while($aUs = $users_res->Fetch())	{
	$aUsers[$aUs["ID"]] = $aUs["LAST_NAME"]." ".substr($aUs["NAME"],0,1).". ".substr($aUs["SECOND_NAME"],0,1).".";
	$u_f[$aUs["ID"]] = $aUs["LAST_NAME"]." ".substr($aUs["NAME"],0,1).". ".substr($aUs["SECOND_NAME"],0,1).".";
	if ($aUs["ACTIVE"]=="N"){
		$aUsers[$aUs["ID"]] .="(у)";
		$u_f[$aUs["ID"]] .="(у)";
	}
}
$arResult["GRID_ID"] = "ro_grid";
$arResult["FILTER"] = array(
	array("id" => "ID", "name"=>"ID"),
	array("id"=>"CODE", "name"=>"КОД"),
	array("id"=>"STATUS", "name"=>"Статус объекта", "type"=>"list", "items"=> array(""=>"(выберите статус)",
																																									"Активный"=>"Активный",
																																									"Активная стадия"=>"Активная стадия",
																																									"Продан"=>"Продан",
																																									"Продан без нас"=>"Продан без нас",
																																									"Приостановлен"=>"Приостановлен",
																																								 "Неверный номер"=>"Неверный номер",)),
	array("id"=>"KAD_NUMBER", "name"=>"Кадастровый номер"),
	array("id"=>"RO_TYPE", "name"=>"Тип объекта", "type"=>"list", "items"=> $ro_type),
	array("id"=>"PROPERTY_313", "name"=>"Ответственный","type"=>"list","items"=>$u_f),
	array("id"=>"BG", "name"=>"Объект БГ","type"=>"list","items"=>$bg_filter),
	array("id"=>"PROPERTY_261", "name"=>"Номер договора"),
	array("id"=>"PROPERTY_262", "name"=>"Тип договора"),
	array("id"=>"PROPERTY_217","name"=>"Улица"),
	array("id"=>"PROPERTY_229","name"=>"Кол-во комнат"),
	array("id"=>"ACCESS","name"=>"Подъезд (секция)"),
	array("id"=>"priceot","name"=>"Цена от"),
	array("id"=>"pricedo","name"=>"Цена до"),
);

$arResult["USERS"] = $aUsers;

$grid_options = new CGridOptions($arResult["GRID_ID"]);
$aSort = $grid_options->GetSorting(array("sort"=>array("ID"=>"desc"), "vars"=>array("by"=>"by", "order"=>"order")));
$aNav = $grid_options->GetNavParams(array("nPageSize"=>20));
$aSortArg = each($aSort["sort"]);
$arSelect = Array("ID", "IBLOCK_ID", "CODE", "NAME","CREATED_BY","MODIFIED_BY","DATE_CREATE","TIMESTAMP_X", "DATE_ACTIVE_FROM","DETAIL_TEXT","PROPERTY_*");

$iblock_filter = array (
	"IBLOCK_ID" => 42,
	"ID" => $_REQUEST['ID'],
	"CODE" => $_REQUEST['CODE'],
	"PROPERTY_210" => $_REQUEST['RO_TYPE'],
	"PROPERTY_313" => $_REQUEST['PROPERTY_313'],
	"%PROPERTY_217" => $_REQUEST['PROPERTY_217'],
	">=PROPERTY_321"=>$_REQUEST['priceot'],
	"<=PROPERTY_321"=>$_REQUEST['pricedo'],
	"PROPERTY_229"=>$_REQUEST['PROPERTY_229'],
	"PROPERTY_266"=>$_REQUEST['STATUS'],
	"%PROPERTY_261"=>$_REQUEST['PROPERTY_261'],
	"%PROPERTY_262"=>$_REQUEST['PROPERTY_262'],
	"PROPERTY_206" => $_REQUEST['BG'],
	"PROPERTY_219" => $_REQUEST['ACCESS'],
);

$arResult['iblock_filter'] = $iblock_filter;

$db_res = CIBlockElement::GetList($aSortArg, $iblock_filter, false, Array("nPageSize"=>20), $arSelect);
$db_res->NavStart($aNav["nPageSize"]);

$aRows = array();
while($aRes = $db_res->GetNext())
{
	
	$aCols = array(
		"ID" => '<a href="?show&id='.$aRes['ID'].'">'.$aRes['ID'].'</a>',
		"PROPERTY_206" =>'<a href="../bg/?show&id='.$aRes['PROPERTY_206'].'">'.$bg_filter[$aRes['PROPERTY_206']].'</a>',
		"PROPERTY_210" => $ro_type[$aRes['PROPERTY_210']],
		"PROPERTY_321" => number_format($aRes['PROPERTY_321'],2,"."," "),
		"PROPERTY_313" => $arResult["USERS"][intval($aRes['PROPERTY_313'])],
		"CREATED_BY" => $arResult["USERS"][intval($aRes['CREATED_BY'])],
		"MODIFIED_BY" => $arResult["USERS"][intval($aRes['MODIFIED_BY'])],
	);
	$aActions = Array(
		array("ICONCLASS"=>"view", "TEXT"=>"Посмотреть", "ONCLICK"=>"jsUtils.Redirect(arguments, '?show&id=".$aRes["ID"]."')", "DEFAULT"=>true),
		array("ICONCLASS"=>"edit", "TEXT"=>"Редактировать", "ONCLICK"=>"jsUtils.Redirect(arguments, 'https://bpm.ucre.ru/bitrix/admin/iblock_element_edit.php?IBLOCK_ID=42&type=CRM_PRODUCT_CATALOG&ID=".$aRes["ID"]."&lang=ru&find_section_section=-1&WF=Y')", "DEFAULT"=>true),
		array("ICONCLASS"=>"copy", "TEXT"=>"Конвертировать в заявку", "ONCLICK"=>"jsUtils.Redirect(arguments, '?convert&id=".$aRes["ID"]."')", "DEFAULT"=>true),
		//array("ICONCLASS"=>"copy", "TEXT"=>"Конвертировать все объекты в заявки", "ONCLICK"=>"jsUtils.Redirect(arguments, '?convert')", "DEFAULT"=>true),
		//array("ICONCLASS"=>"edit", "TEXT"=>"Изменить", "ONCLICK"=>"jsUtils.Redirect(arguments, '?edit&id=".$aRes["ID"]."')", "DEFAULT"=>true),
		//array("ICONCLASS"=>"copy", "TEXT"=>"Добавить копию", "ONCLICK"=>"jsUtils.Redirect(arguments, '?copy&id=".$aRes["ID"]."')"),
		
	);
	if ($USER->GetID()!=24){
		if ($USER->GetID()==1 || $USER->GetID()==26){
			//unset($aActions[2]);
			//unset($aActions[3]);
		}else{
			unset($aActions[1]);
			unset($aActions[2]);
			//unset($aActions[3]);
		}
	}
	$aRows[] = array("data"=>$aRes, "actions"=>$aActions, "columns"=>$aCols, "editable"=>false);
}

$arResult["ROWS"] = $aRows;
$arResult["ROWS_COUNT"] = $db_res->SelectedRowsCount();
$arResult["SORT"] = $aSort["sort"];
$arResult["SORT_VARS"] = $aSort["vars"];

$db_res->bShowAll = false;
$arResult["NAV_OBJECT"] = $db_res;



$componentPage = 'index';
$arResult['action'] = 'list';
if (isset($_REQUEST['edit'])){
	$arResult['action'] = 'edit';
	$componentPage = 'edit';
} else if (isset($_REQUEST['copy'])){
	$arResult['action'] = 'copy';
	$componentPage = 'edit';
}	else if (isset($_REQUEST['show'])){
	$componentPage = 'show';
	$arResult['action'] = 'show';
} else if (isset($_REQUEST['convert'])){
	$componentPage = 'convert';
	$arResult['action'] = 'convert';
}

$this->IncludeComponentTemplate($componentPage);
//if ($USER->GetID()==24){var_dump($aActions);}
?>