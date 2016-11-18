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
AddEventHandler('crm', 'OnBeforeCrmDealUpdate', 'DealCheck');
function DealCheck(&$arFieldsNew){
	$dbResult = CCrmDeal::GetList(array(),array("ID"=>$arFieldsNew["ID"]),array());
	$arFieldsCur = $dbResult->Fetch();
	//file_put_contents('/home/bitrix/www_bpm/new.txt', var_export($arFieldsNew, true));
	//file_put_contents('/home/bitrix/www_bpm/cur.txt', var_export($arFieldsCur, true));
	if ($arFieldsNew['UF_CRM_1469534140']!=''/* && $arFieldsNew['UF_CRM_1469534140']!=$arFieldsCur['UF_CRM_1469534140']*/){
		$ro_res = CIBlockElement::GetByID($arFieldsNew['UF_CRM_1469534140']);
		$ro_element = $ro_res->GetNextElement();
		$ro_props = $ro_element->GetProperties();
		//file_put_contents('/home/bitrix/www_bpm/props.txt', var_export($ro_props, true));
		$arFieldsNew['UF_CRM_1476448884'] = $ro_props['ROOMS']['VALUE']; //Кол-во комнат
		$arFieldsNew['UF_CRM_1476448585'] = $ro_props['FLOOR']['VALUE']; //Этаж
		$arFieldsNew['UF_CRM_1475915490'] = $ro_props['TOTAL_AREA']['VALUE']; //Общ. площадь
		$arFieldsNew['UF_CRM_1479470711'] = $ro_props['STREET']['VALUE']; //улица
		$arFieldsNew['UF_CRM_1479470723'] = $ro_props['HOUSE']['VALUE']; //дом
		$arFieldsNew['UF_CRM_1479470770'] = $ro_props['FLAT']['VALUE']; //квартира
	} else {
		$arFieldsNew['UF_CRM_1476448884'] = 0; //Кол-во комнат
		$arFieldsNew['UF_CRM_1476448585'] = 0; //Этаж
		$arFieldsNew['UF_CRM_1475915490'] = 0; //Общ. площадь
		$arFieldsNew['UF_CRM_1479470711'] = 0; //улица
		$arFieldsNew['UF_CRM_1479470723'] = 0; //дом
		$arFieldsNew['UF_CRM_1479470770'] = 0; //квартира
	}
	CIBlockElement::SetPropertyValuesEx($arFieldsCur['UF_CRM_1469534140'], 42, array("ID_DEAL" => $arFieldsNew['ID']));
	if ($arFieldsCur['UF_CRM_1469534140']){//Если сделка связана с каким-либо объектом
		if ($arFieldsNew['UF_CRM_579897C010103']){//Если поменялась цена, синхронизируем объект
			CIBlockElement::SetPropertyValuesEx($arFieldsCur['UF_CRM_1469534140'], 42, array("PRICE" => $arFieldsNew['UF_CRM_579897C010103']));
			CEventLog::Add(array(
				"SEVERITY" => "SECURITY",
				"AUDIT_TYPE_ID" => "DEAL_BEFORE_UPDATE",
				"MODULE_ID" => "main",
				"ITEM_ID" => 'Заявки (сделки)',
				"DESCRIPTION" => "Для заявки № ".$arFieldsNew['ID']." обновлена цена в объекте № ".$arFieldsCur['UF_CRM_1469534140'],
			));
		}
		
		if ($arFieldsNew['ASSIGNED_BY_ID'] && ($arFieldsNew['ASSIGNED_BY_ID']!=$arFieldsCur['ASSIGNED_BY_ID'])){//Если поменялся ответственный за заявку
			CIBlockElement::SetPropertyValuesEx($arFieldsCur['UF_CRM_1469534140'], 42, array("ASSIGNED_BY" => $arFieldsNew['ASSIGNED_BY_ID']));
			CEventLog::Add(array(
				"SEVERITY" => "SECURITY",
				"AUDIT_TYPE_ID" => "DEAL_BEFORE_UPDATE",
				"MODULE_ID" => "main",
				"ITEM_ID" => 'Заявки (сделки)',
				"DESCRIPTION" => "Для заявки № ".$arFieldsNew['ID']." обновлен ответственный в объекте № ".$arFieldsCur['UF_CRM_1469534140'],
			));
		}
		if ($arFieldsNew['COMMENTS'] && ($arFieldsNew['COMMENTS']!=$arFieldsCur['COMMENTS'])){//Если поменялось описание заявки
			$el = new CIBlockElement;
			if ($el->Update($arFieldsCur['UF_CRM_1469534140'], array("DETAIL_TEXT" => $arFieldsNew['COMMENTS']))){
			CEventLog::Add(array(
				"SEVERITY" => "SECURITY",
				"AUDIT_TYPE_ID" => "DEAL_BEFORE_UPDATE",
				"MODULE_ID" => "main",
				"ITEM_ID" => 'Заявки (сделки)',
				"DESCRIPTION" => "Для заявки № ".$arFieldsNew["ID"]." обновлено описание в связанном объекте № ".$arFieldsCur['UF_CRM_1469534140'],
			));
		}else{
			CEventLog::Add(array(
				"SEVERITY" => "SECURITY",
				"AUDIT_TYPE_ID" => "DEAL_BEFORE_UPDATE",
				"MODULE_ID" => "main",
				"ITEM_ID" => 'Заявки (сделки)',
				"DESCRIPTION" => "Ошибка обновления объекта № ".$arFieldsNew['UF_CRM_1469534140'].", ".$el->LAST_ERROR,
			));
			}
		}
		
		if ($arFieldsNew['STAGE_ID']!=$arFieldsCur['STAGE_ID']){//Если поменялся статус заявки
			if (CCrmDeal::GetStageName($arFieldsCur['STAGE_ID'])=="Предлистинг" && !in_array(CCrmSecurityHelper::GetCurrentUserID(), array(24,1,11,12,26))){
				$arFieldsNew['RESULT_MESSAGE'] = "Заявку из предлистинга может вывести только администратор.";
				return false;
			}
			CIBlockElement::SetPropertyValuesEx($arFieldsCur['UF_CRM_1469534140'], 42, array("STATUS" => CCrmDeal::GetStageName($arFieldsNew['STAGE_ID'])));
			CEventLog::Add(array(
				"SEVERITY" => "SECURITY",
				"AUDIT_TYPE_ID" => "DEAL_BEFORE_UPDATE",
				"MODULE_ID" => "main",
				"ITEM_ID" => 'Заявки (сделки)',
				"DESCRIPTION" => "Для заявки № ".$arFieldsNew['ID']." обновлен статус в объекте № ".$arFieldsCur['UF_CRM_1469534140']." на ".CCrmDeal::GetStageName($arFieldsNew['STAGE_ID']),
			));
		}
	}
	
}

function avito_Export()
{
	$start = microtime(true);//Засекаем время выполнения скрипта
	$locations = simplexml_load_file('http://autoload.avito.ru/format/Locations.xml');
  $cityspr = array();//Справочник населенных пунктов Оренбургской области по версии Авито
  $districtspr = array("Ленинский", "Промышленный", "Центральный", "Дзержинский","отсутствует");//Справочник районов города
  foreach ($locations->Region as $region) {
   if ($region['Id']=='642480') {
     foreach ($region->City as $city){
       array_push($cityspr,trim((string)$city['Name']));
     }
   }
  }
	$num = 0;
	$r = 0;
	$f = 0;
	$h = 0;
	$p = 0;
	$c = 0;
	if(CModule::IncludeModule('iblock') && CModule::IncludeModule("crm")) {
		//------Наполняем справочник типов домов
		$property_enums = CIBlockPropertyEnum::GetList(Array(), Array("IBLOCK_ID"=>42, "CODE"=>"HOUSE_TYPE"));
		$housetype = array();
		while($enum_fields = $property_enums->GetNext())
		{
			$housetype[$enum_fields["ID"]] = $enum_fields["VALUE"];
		}
		$housetype[429] = "Монолитный";
		//------Наполнили справочник типов домов
		
		//------Наполняем справочник материалов стен
		$property_enums = CIBlockPropertyEnum::GetList(Array(), Array("IBLOCK_ID"=>42, "CODE"=>"WALLS"));
		$walls = array();
		while($enum_fields = $property_enums->GetNext())
		{
			$walls[$enum_fields["ID"]] = $enum_fields["VALUE"];
		}
		$walls[417] = 'Экспериментальные материалы';
		$walls[413] = 'Пеноблоки';
		$walls[425] = 'Пеноблоки';
		$walls[423] = 'Пеноблоки';
		$walls[422] = 'Ж/б панели';
		//------Наполнили справочник материалов стен
		
		//------Наполняем справочник категорий участков
		$property_enums = CIBlockPropertyEnum::GetList(Array(), Array("IBLOCK_ID"=>42, "CODE"=>"PLOT_CAT"));
		$plotcat = array();
		while($enum_fields = $property_enums->GetNext())
		{
			$plotcat[$enum_fields["ID"]] = $enum_fields["VALUE"];
		}
		//------Наполнили справочник категорий участков
		
		//------Наполняем справочник назначений коммерческих объектов
		$property_enums = CIBlockPropertyEnum::GetList(Array(), Array("IBLOCK_ID"=>42, "CODE"=>"APPOINTMENT"));
		$appointment = array();
		while($enum_fields = $property_enums->GetNext())
		{
			$appointment[$enum_fields["ID"]] = $enum_fields["VALUE"];
		}
		//------Наполнили справочник назначений коммерческих объектов
		
		$dom = new domDocument("1.0", "utf-8");
		$Ads = $dom->createElement("Ads"); // Создаём корневой элемент
		$Ads->setAttribute("formatVersion","3");//Добавляем элементу свойство
		$Ads->setAttribute("target","Avito.ru");//Добавляем элементу свойство
		$dom->appendChild($Ads);//Присоединяем его к документу
		
		$arSelect = Array("ID", "IBLOCK_ID", "CODE", "NAME","CREATED_BY","MODIFIED_BY","DATE_CREATE","TIMESTAMP_X", "DATE_ACTIVE_FROM","DETAIL_TEXT","PROPERTY_*");
		$iblock_filter = array ("IBLOCK_ID" => 42,/*"ACTIVE"=>"Y"*/"PROPERTY_266" => array("Активная стадия","Активный","Свободный"),">=PROPERTY_260"=>date("Y-m-d"));
		$db_res = CIBlockElement::GetList(array("ID"=>"ASC"), $iblock_filter, false, false, $arSelect);
		while($aRes = $db_res->GetNext()){
			$Ad = $dom->createElement("Ad");// Создаём узел "Object"
			$Id = $dom->createElement("Id", (!empty($aRes['CODE']))? $aRes['CODE'] : $aRes['ID']); // Создаём узел "Id" с текстом внутри
			$Ad->appendChild($Id); // Добавляем в узел "Object" узел "Id"
			switch ($aRes['PROPERTY_210']){
				case 382:
					$Category = $dom->createElement("Category","Квартиры");
					$f++;
					break;
				case 381:
					$Category = $dom->createElement("Category","Комнаты");
					$r++;
					break;
				case 386:
					$Category = $dom->createElement("Category","Земельные участки");
					$p++;
					break;
				case 383:
				case 384:
				case 385:
					$Category = $dom->createElement("Category","Дома, дачи, коттеджи");
					$h++;
					break;
				case 387:
					$Category = $dom->createElement("Category","Коммерческая недвижимость");
					$c++;
					break;
				default:
					$Category = $dom->createElement("Category","ХЗ");
					break;
			}
			$Ad->appendChild($Category);// Добавляем в узел "Ad" узел "Category"
			$OperationType = $dom->createElement("OperationType",$aRes['PROPERTY_300']);
			$Ad->appendChild($OperationType);// Добавляем в узел "Ad" узел "OperationType"
			$DateEnd = $dom->createElement('DateEnd', date("Y-m-d", strtotime("+30 days")));
      $Ad->appendChild($DateEnd);
			$Region = $dom->createElement("Region",($aRes['PROPERTY_213']=="Оренбургская обл")?"Оренбургская область":$aRes['PROPERTY_213']);
      $Ad->appendChild($Region);
			//Формирование адреса объекта в зхависимости от типа объекта и наличия населенного пункта в справочнике Авито
			if ($aRes['PROPERTY_215'] == 'Оренбург г'){//Если объект в Оренбурге
				$City = $dom->createElement("City",'Оренбург');
        $Ad->appendChild($City);
				if(in_array($aRes['PROPERTY_216'],$districtspr)){//Если район города есть в справочнике
					if ($aRes['PROPERTY_216']!="отсутствует") {
						$District = $dom->createElement("District",$aRes['PROPERTY_216']);
						$Ad->appendChild($District);
					}
					if ($aRes['PROPERTY_210']==382 || $aRes['PROPERTY_210']==381) {
						if ($aRes['PROPERTY_258']==""){
							$Street = $dom->createElement("Street",$aRes['PROPERTY_217'].', '.$aRes['PROPERTY_218']);//Улица, дом
							$Ad->appendChild($Street);
						}
					}else{
						if ($aRes['PROPERTY_258']==""){
							$Street = $dom->createElement("Street",$aRes['PROPERTY_217']);//Улица
							$Ad->appendChild($Street);
						}
					}
				}else{//Если района в справочнике нет
					if ($aRes['PROPERTY_210']==382 || $aRes['PROPERTY_210']==381) {
						if ($aRes['PROPERTY_258']==""){
							$Street = $dom->createElement("Street",$aRes['PROPERTY_216'].','.$aRes['PROPERTY_217'].', '.$aRes['PROPERTY_218']);//Район, улица, дом
							$Ad->appendChild($Street);
						}
					}else{
						if ($aRes['PROPERTY_258']==""){
							$Street = $dom->createElement("Street",$aRes['PROPERTY_216'].','.$aRes['PROPERTY_217']);//Район, улица
							$Ad->appendChild($Street);
						}
					}
				}
			}else{//Если объект не в Оренбурге
				if (in_array(substr($aRes['PROPERTY_215'],0,strrpos($aRes['PROPERTY_215']," ")),$cityspr)){//Если населенный пункт есть в справочнике Авито
					$City = $dom->createElement("City",substr($aRes['PROPERTY_215'],0,strrpos($aRes['PROPERTY_215']," ")));
					$Ad->appendChild($City);
					if ($aRes['PROPERTY_210']==382 || $aRes['PROPERTY_210']==381) {
						if ($aRes['PROPERTY_258']==""){
							$Street = $dom->createElement("Street",$aRes['PROPERTY_217'].', '.$aRes['PROPERTY_218']);//Улица, дом
							$Ad->appendChild($Street);
						}
					}else{
						if ($aRes['PROPERTY_258']==""){
							$Street = $dom->createElement("Street",$aRes['PROPERTY_217']);//Улица
							$Ad->appendChild($Street);
						}
					}
				}else{//Если населенного пункта нет в справочнике Авито
					$City = $dom->createElement("City",'Оренбург');
					$Ad->appendChild($City);
					if ($aRes['PROPERTY_210']==382 || $aRes['PROPERTY_210']==381) {
						if ($aRes['PROPERTY_258']==""){
							$Street = $dom->createElement("Street",$aRes['PROPERTY_214'].','.$aRes['PROPERTY_215'].', '.$aRes['PROPERTY_217'].', '.$aRes['PROPERTY_218']);//Район, нас. пункт, улица, дом
							$Ad->appendChild($Street);
						}
					}else{
						if ($aRes['PROPERTY_258']==""){
							$Street = $dom->createElement("Street",$aRes['PROPERTY_214'].','.$aRes['PROPERTY_215'].', '.$aRes['PROPERTY_217']);//Район, нас. пункт, улица
							$Ad->appendChild($Street);
						}
					}
				}
			}
			if ($aRes['PROPERTY_298'] && $aRes['PROPERTY_299']){
				if ($aRes['PROPERTY_258']==""){
					$Latitude = $dom->createElement("Latitude", $aRes['PROPERTY_298']);
					$Ad->appendChild($Latitude);
					$Longitude = $dom->createElement("Longitude", $aRes['PROPERTY_299']);
					$Ad->appendChild($Longitude);
				}
	    }
			
			if ($aRes['PROPERTY_210']==382 || $aRes['PROPERTY_210']==381){
	  	  $Rooms = $dom->createElement("Rooms", intval($aRes['PROPERTY_229']));
	  	  $Ad->appendChild($Rooms);
	    }
			switch ($aRes['PROPERTY_210']){
				case 382:
				case 381:
				case 387:
					$Square = $dom->createElement("Square",number_format($aRes['PROPERTY_224'],2,".",""));
					$Ad->appendChild($Square);
					break;
				case 386:
					$LandArea = $dom->createElement("LandArea",number_format($aRes['PROPERTY_292'],2,".",""));
					$Ad->appendChild($LandArea);
					break;
				case 383:
				case 384:
				case 385:
					$Square = $dom->createElement("Square",number_format($aRes['PROPERTY_224'],2,".",""));
					$Ad->appendChild($Square);
					$LandArea = $dom->createElement("LandArea",number_format($aRes['PROPERTY_292'],2,".",""));
					$Ad->appendChild($LandArea);
					break;
			}
			
			if ($aRes['PROPERTY_210']==382 || $aRes['PROPERTY_210']==381){
				$HouseType = $dom->createElement("HouseType", $housetype[$aRes['PROPERTY_243']]);
				$Ad->appendChild($HouseType);
			}
			
			if ($aRes['PROPERTY_210']==382 || $aRes['PROPERTY_210']==381){
				$Floor = $dom->createElement("Floor", $aRes['PROPERTY_221']);
				$Ad->appendChild($Floor);
			}
			if ($aRes['PROPERTY_210']!=386){
				$Floors = $dom->createElement("Floors", $aRes['PROPERTY_222']);
				$Ad->appendChild($Floors);
			}
			if ($aRes['PROPERTY_210']==383 || $aRes['PROPERTY_210']==384 || $aRes['PROPERTY_210']==385) {
				$WallsType = $dom->createElement("WallsType", $walls[$aRes['PROPERTY_242']]);
				$Ad->appendChild($WallsType);
			}
			
			switch($aRes['PROPERTY_210']){
				case 383:
					$ObjectType = $dom->createElement("ObjectType", "Дом");
					$Ad->appendChild($ObjectType);
					break;
				case 384:
					$ObjectType = $dom->createElement("ObjectType", "Таунхаус");
					$Ad->appendChild($ObjectType);
					break;
				case 385:
					$ObjectType = $dom->createElement("ObjectType", "Дача");
					$Ad->appendChild($ObjectType);
					break;
				case 386:
					$ObjectType = $dom->createElement("ObjectType", $plotcat[$aRes['PROPERTY_295']]);
					$Ad->appendChild($ObjectType);
					break;
				case 387:
					$ObjectType = $dom->createElement("ObjectType", $appointment[$aRes['PROPERTY_238']]);
					$Ad->appendChild($ObjectType);
					break;
			}
			switch($aRes['PROPERTY_210']){
				case 383:
				case 384:
				case 385:
				case 386:
					$DistanceToCity = $dom->createElement("DistanceToCity", intval($aRes['PROPERTY_281']));
					$Ad->appendChild($DistanceToCity);
					break;
			}
			$dealFilter = array("ID" => $aRes['PROPERTY_319'], "CHECK_PERMISSIONS" => "N");//"CHECK_PERMISSIONS" => "N" Обязательный параметр фильтра при вызове из агента, ибо агент выполняется под анонимным пользователем
			$dealSelect = array("ID","UF_CRM_579897C010103", "COMMENTS");
			$deal_res = CCrmDeal::GetList(Array('DATE_CREATE' => 'DESC'), $dealFilter, $dealSelect);
			$deal = $deal_res->GetNext();
			
			$Price = $dom->createElement("Price", $deal['UF_CRM_579897C010103']);
			$Ad->appendChild($Price);
			
			if($aRes['PROPERTY_300']=="Сдам"){
				$PriceType = $dom->createElement("PriceType", "в месяц за м2");
				$Ad->appendChild($PriceType);
			}
			$Description = $dom->createElement("Description", html_entity_decode($deal['COMMENTS']/*$aRes['DETAIL_TEXT']*/)." Номер в базе: ".$aRes['ID']);//Номер в базе - новый ID
	    $Ad->appendChild($Description);
			
			if ($aRes['PROPERTY_210']==382){
				$MarketType = $dom->createElement("MarketType", ($aRes['PROPERTY_258']=="")? "Вторичка":"Новостройка");
				$Ad->appendChild($MarketType);
			}
			if ($aRes['PROPERTY_258']!=""){
				$NewDevelopmentId = $dom->createElement("NewDevelopmentId",substr($aRes['PROPERTY_258'],0,strpos($aRes['PROPERTY_258']," ")));
				$Ad->appendChild($NewDevelopmentId);
			}
			
			$Images = $dom->createElement("Images");
			foreach ($aRes['PROPERTY_237'] as $imageid){
				$Image = $dom->createElement("Image");
        $Image->setAttribute("url", "http://bpm.ucre.ru/".CFile::GetPath($imageid));
        $Images->appendChild($Image);
			}
			foreach ($aRes['PROPERTY_236'] as $imageid){
				$Image = $dom->createElement("Image");
        $Image->setAttribute("url", "http://bpm.ucre.ru/".CFile::GetPath($imageid));
        $Images->appendChild($Image);
			}
			$Ad->appendChild($Images);
			
			$CompanyName = $dom->createElement("CompanyName","Единый центр недвижимости «Этажи»");
  	  $Ad->appendChild($CompanyName);
			
			$rsUser = CUser::GetByID($aRes['PROPERTY_313']);
			$arUser = $rsUser->Fetch();
			$ManagerName = $dom->createElement("ManagerName",$arUser['LAST_NAME']." ".$arUser['NAME']." ".$arUser['SECOND_NAME']);
	    $Ad->appendChild($ManagerName);
	    $EMail = $dom->createElement("EMail", $arUser['EMAIL']);
	    $Ad->appendChild($EMail);
	    $ContactPhone = $dom->createElement("ContactPhone", $arUser['PERSONAL_PHONE']);
	    $Ad->appendChild($ContactPhone);
			
			
			$Ads->appendChild($Ad); // Добавляем в корневой узел "Ads" узел "Ad"
			$num++;
		}
		$result = $dom->save("/home/bitrix/www_bpm/orenburg_avito.xml"); // Сохраняем полученный XML-документ в файл
		$time = microtime(true) - $start;
		CEventLog::Add(array(
			"SEVERITY" => "SECURITY",
			"AUDIT_TYPE_ID" => "AVT_EXPORT",
			"MODULE_ID" => "main",
			"ITEM_ID" => 'Каталог недвижимости',
			"DESCRIPTION" => "Результат записи фида: ".$result."<br>Выгрузка агентом объектов недвижимости в формате АВИТО, выгружено ".$num." объектов за ".$time." секунд (включая комнат - ".$r.", квартир - ".$f.", домов, дач, коттеджей - ".$h.", участков - ".$p.", коммерческих - ".$c.").",
		));
	} else {
		CEventLog::Add(array(
			"SEVERITY" => "SECURITY",
			"AUDIT_TYPE_ID" => "AVT_EXPORT",
			"MODULE_ID" => "main",
			"ITEM_ID" => 'Каталог недвижимости',
			"DESCRIPTION" => "Не сработало нихуа!",
		));
	}
	return "avito_Export();";
}
?>