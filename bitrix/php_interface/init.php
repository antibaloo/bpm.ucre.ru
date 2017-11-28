<?php
AddEventHandler("iblock", "OnAfterIBlockElementAdd", "sendnews");
AddEventHandler("iblock", "OnAfterIBlockElementUpdate", "sendnews");
function sendnews(&$arFields){
	if ($arFields['IBLOCK_ID'] == 1 || $arFields['IBLOCK_ID'] == 2){
		$url = "http://ucre.ru/getnewsfrombpm.php";
		$arSelect = array("ID", "ACTIVE", "NAME", "TAGS", "PREVIEW_PICTURE", "PREVIEW_TEXT", "DETAIL_PICTURE", "DETAIL_TEXT", "IBLOCK_ID");
		$arFilter = array('ID' =>$arFields['ID'], 'IBLOCK_ID' => $arFields['IBLOCK_ID']);
		$res = CIBlockElement::GetList(array(), $arFilter, false, false, $arSelect);
		$ob = $res->GetNextElement();
		$news = $ob->GetFields();
		$props = $ob->GetProperties();
		if ($props['PUBLISH']['VALUE'] != 'Да') return;	//Проверка признака публикации на сайте
		$data = array(
			'TIMESTAMP'					=>	ConvertTimeStamp(time(), "FULL"),
			'ID'								=>	$arFields['ID'],
			'IBLOCK_ID'					=>	$arFields['IBLOCK_ID'],
			'ACTIVE'						=>	$news['ACTIVE'],
			'NAME'							=>	$news['NAME'],
			'TAGS'							=>	$news['TAGS'],
			'PREVIEW_TEXT'			=>	$news['PREVIEW_TEXT'],
			'PREVIEW_TEXT_TYPE'	=>	$news['PREVIEW_TEXT_TYPE'],
			'DETAIL_PICTURE'		=>	($news['DETAIL_PICTURE'])?"https://bpm.ucre.ru".CFile::GetPath($news['DETAIL_PICTURE']):"",
			'DETAIL_TEXT'				=>	$news['DETAIL_TEXT'],
			'DETAIL_TEXT_TYPE'	=>	$news['DETAIL_TEXT_TYPE'],
		);
		$options = array(
			'http' => array(
			'method'  => 'POST',
			'content' => json_encode( $data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES ),
			'header'=>  "Content-Type: application/json\r\n" .
			"Accept: application/json\r\n"
		));
		$context  = stream_context_create($options);
		$result = file_get_contents( $url, false, $context );
		$response = json_decode( $result );
		$log_json = fopen('/home/bitrix/www_bpm/sendnews2ucre.log', 'a');
		fwrite( $log_json, "Отправка: ".json_encode( $data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES )."\r\n");
		fwrite( $log_json, "Ответ   : ".$result."\r\n");
		fclose( $log_json );
		if ($response->RESULT == 'OK'){
			CEventLog::Add(array(
				"SEVERITY" => "WARNING",
				"AUDIT_TYPE_ID" => "Обмен новостями с сайтом",
				"MODULE_ID" => "main",
				"ITEM_ID" => $arFields['ID'],
				"DESCRIPTION" => "Новость с ID ".$arFields['ID']." в инфоблоке с ID ".$arFields['IBLOCK_ID']." передана на сайт ucre.ru в элемент с ID ".$response->ID,
			));
		}elseif ($response->RESULT == 'ERROR'){
			CEventLog::Add(array(
				"SEVERITY" => "WARNING",
				"AUDIT_TYPE_ID" => "Обмен новостями с сайтом",
				"MODULE_ID" => "main",
				"ITEM_ID" => $arFields['ID'],
				"DESCRIPTION" => "Новость с ID ".$arFields['ID']." в инфоблоке с ID ".$arFields['IBLOCK_ID']." не передана на сайт ucre.ru, произошла ошибка: ".$response->TEXT,
			));
		}	else {
			CEventLog::Add(array(
				"SEVERITY" => "WARNING",
				"AUDIT_TYPE_ID" => "Обмен новостями с сайтом",
				"MODULE_ID" => "main",
				"ITEM_ID" => $arFields['ID'],
				"DESCRIPTION" => "При передаче новости с ID ".$arFields['ID']." в инфоблоке с ID ".$arFields['IBLOCK_ID']." не получен ответ от ucre.ru",
			));
		}
	}
}

AddEventHandler('crm', 'OnAfterCrmDealAdd', 'myDealAdd');
function myDealAdd (&$arFields){
	if ($arFields['UF_CRM_1469534140']) {//Если при создании заявки, с ней был связан объект
		CIBlockElement::SetPropertyValuesEx($arFields['UF_CRM_1469534140'], 42, array("ID_DEAL" => $arFields['ID']));// передаем в объект ID заявки
		CIBlockElement::SetPropertyValuesEx($arFields['UF_CRM_1469534140'], 42, array("ASSIGNED_BY" => $arFields['ASSIGNED_BY_ID']));//передаем в объект ID ответственного
		//CIBlockElement::SetPropertyValuesEx($arFields['UF_CRM_1469534140'], 42, array("PRICE" => $arFields['UF_CRM_579897C010103']));//передаем в объект данные о цене
		CIBlockElement::SetPropertyValuesEx($arFields['UF_CRM_1469534140'], 42, array("STATUS" => CCrmDeal::GetStageName($arFields['STAGE_ID'])));//передаем в объект статус заявки
		//$el = new CIBlockElement;
		//$el->Update($arFields['UF_CRM_1469534140'], array("DETAIL_TEXT" => $arFields['COMMENTS']));
	}
	//file_put_contents('/home/bitrix/www_bpm/myadd.log', var_export($arFields, true));
}

AddEventHandler('crm', 'OnAfterCrmDealUpdate', 'myDealUpdate');
function myDealUpdate (&$arFields){
	$dbResult = CCrmDeal::GetList(array(),array("ID"=>$arFields["ID"]),array());//Получаем полный вектор полей заявки, не зависимо от того, какие поля сохранялись
	$arFieldsExist = $dbResult->Fetch();
	if ($arFieldsExist['UF_CRM_1469534140']) {//Если у завки есть связанный объект
		CIBlockElement::SetPropertyValuesEx($arFieldsExist['UF_CRM_1469534140'], 42, array("ID_DEAL" => $arFieldsExist['ID']));// передаем в объект ID заявки
		CIBlockElement::SetPropertyValuesEx($arFieldsExist['UF_CRM_1469534140'], 42, array("ASSIGNED_BY" => $arFieldsExist['ASSIGNED_BY_ID']));//передаем в объект ID ответственного
		//CIBlockElement::SetPropertyValuesEx($arFieldsExist['UF_CRM_1469534140'], 42, array("PRICE" => $arFieldsExist['UF_CRM_579897C010103']));//передаем в объект данные о цене
		CIBlockElement::SetPropertyValuesEx($arFieldsExist['UF_CRM_1469534140'], 42, array("STATUS" => CCrmDeal::GetStageName($arFieldsExist['STAGE_ID'])));//передаем в объект статус заявки
		//$el = new CIBlockElement;
		//$el->Update($arFieldsExist['UF_CRM_1469534140'], array("DETAIL_TEXT" => $arFieldsExist['COMMENTS']));
	}
	//file_put_contents('/home/bitrix/www_bpm/myupdate.log', var_export($arFieldsExist, true));
}
/*
AddEventHandler('crm', 'OnBeforeCrmDealAdd', 'DealAdd');
function DealAdd(&$arFields){
	if ($arFields['UF_CRM_1469534140']!=''){
		$ro_res = CIBlockElement::GetByID($arFields['UF_CRM_1469534140']);
		$ro_element = $ro_res->GetNextElement();
		$ro_props = $ro_element->GetProperties();
		$arFields['UF_CRM_1476448884'] = $ro_props['ROOMS']['VALUE']; //Кол-во комнат
		$arFields['UF_CRM_1476448585'] = $ro_props['FLOOR']['VALUE']; //Этаж
		$arFields['UF_CRM_1475915490'] = $ro_props['TOTAL_AREA']['VALUE']; //Общ. площадь
		$arFields['UF_CRM_1479470711'] = $ro_props['STREET']['VALUE']; //улица
		$arFields['UF_CRM_1479470723'] = $ro_props['HOUSE']['VALUE']; //дом
		$arFields['UF_CRM_1479470770'] = $ro_props['FLAT']['VALUE']; //квартира
		$arFields['UF_CRM_1469597960'] = $ro_props['ADDRESS']['VALUE']; //Адрес
	} else {
		$arFields['UF_CRM_1476448884'] = ""; //Кол-во комнат
		$arFields['UF_CRM_1476448585'] = ""; //Этаж
		$arFields['UF_CRM_1475915490'] = ""; //Общ. площадь
		$arFields['UF_CRM_1479470711'] = ""; //улица
		$arFields['UF_CRM_1479470723'] = ""; //дом
		$arFields['UF_CRM_1479470770'] = ""; //квартира
		$arFields['UF_CRM_1469597960'] = ""; //Адрес
	}	
}



AddEventHandler('crm', 'OnBeforeCrmDealUpdate', 'DealUpdate');
function DealUpdate(&$arFields){
	if (isset($arFields['UF_CRM_1469534140'])){
		if ($arFields['UF_CRM_1469534140']!=''){
			$ro_res = CIBlockElement::GetByID($arFields['UF_CRM_1469534140']);
			$ro_element = $ro_res->GetNextElement();
			$ro_props = $ro_element->GetProperties();
			$arFields['UF_CRM_1476448884'] = $ro_props['ROOMS']['VALUE']; //Кол-во комнат
			$arFields['UF_CRM_1476448585'] = $ro_props['FLOOR']['VALUE']; //Этаж
			$arFields['UF_CRM_1475915490'] = $ro_props['TOTAL_AREA']['VALUE']; //Общ. площадь
			$arFields['UF_CRM_1479470711'] = $ro_props['STREET']['VALUE']; //улица
			$arFields['UF_CRM_1479470723'] = $ro_props['HOUSE']['VALUE']; //дом
			$arFields['UF_CRM_1479470770'] = $ro_props['FLAT']['VALUE']; //квартира
			$arFields['UF_CRM_1469597960'] = $ro_props['ADDRESS']['VALUE']; //Адрес
		} else {
			$arFields['UF_CRM_1476448884'] = ""; //Кол-во комнат
			$arFields['UF_CRM_1476448585'] = ""; //Этаж
			$arFields['UF_CRM_1475915490'] = ""; //Общ. площадь
			$arFields['UF_CRM_1479470711'] = ""; //улица
			$arFields['UF_CRM_1479470723'] = ""; //дом
			$arFields['UF_CRM_1479470770'] = ""; //квартира
			$arFields['UF_CRM_1469597960'] = ""; //Адрес
		}	
	}
}*/
AddEventHandler('crm', 'OnBeforeCrmLeadUpdate', 'LeadUpdate');
function LeadUpdate(&$arFields){
	$dbResult = CCrmLead::GetList(array(),array("ID"=>$arFields["ID"]),array());//Получаем полный вектор полей заявки, не зависимо от того, какие поля сохранялись
	$existFields = $dbResult->Fetch();

	$ar = CCrmStatus::GetStatusListEx('SOURCE');
	$list = array();
	foreach ($ar as $key => $value){
		$list[$key] = $value;
	}
	//Bitrix\Main\Diag\Debug::writeToFile(array( 'source'=>$list ),"","/lead.txt");
	
	$rsData = CUserFieldEnum::GetList(array(), array(
		"ID" => (isset($arFields["UF_CRM_1486022615"]))?$arFields["UF_CRM_1486022615"]:$existFields["UF_CRM_1486022615"],
		"USER_FIELD_NAME"=>"UF_CRM_1486022615"
	));
	
	if($rs = $rsData->GetNext()) $direction = $rs['VALUE'];
	
	$type = "кое-что";
	$type_id = (isset($arFields["UF_CRM_1490011939"]))?$arFields["UF_CRM_1490011939"]:$existFields["UF_CRM_1490011939"];
	switch ($type_id){
		case 1:
			$type = "комната";
			$rooms = "";
			break;
		case 2:
			$type = "квартира";
			$rooms = ($arFields["UF_CRM_1486191523"]>0)?$arFields["UF_CRM_1486191523"]."-к":"количество комнат неизвестно";
			break;
		case 3:
			$type = "дом";
			$rooms = ($arFields["UF_CRM_1486191523"]>0)?$arFields["UF_CRM_1486191523"]."-к":"количество комнат неизвестно";
			break;
		case 4:
			$type = "таунхаус";
			$rooms = ($arFields["UF_CRM_1486191523"]>0)?$arFields["UF_CRM_1486191523"]."-к":"количество комнат неизвестно";
			break;
		case 5:
			$type = "дача";
			$rooms = "";
			break;
		case 6:
			$type = "участок";
			$rooms = "";
			break;
		case 7:
			$type = "коммерческий";
			$rooms = "";
			break;
			
	}
	if (isset($arFields['NAME'])){
		$name = ($arFields['NAME'] != "")?$arFields['NAME']:"кто-то";
	}else{
		$name = ($existFields['NAME'] != "")?$existFields['NAME']:"кто-то";
	}
	if (isset($arFields['SOURCE_ID'])) $source_id = $list[$arFields['SOURCE_ID']];
	else $source_id = $list[$existFields['SOURCE_ID']];
	
	if (in_array($direction,array("Покупка","Продажа", "Сдать","Снять","Новостройки","Ипотека"))){
		$arFields['TITLE'] = $direction.": ".$name.", ".$type.", ".$rooms." (".$source_id.")";
	}else{
		$arFields['TITLE'] = $direction.": ".$name.", (".$source_id.")";
	}
}
?>