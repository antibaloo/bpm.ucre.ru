<?php
$start = microtime(true);//Засекаем время выполнения скрипта
require ($_SERVER['DOCUMENT_ROOT'] . "/bitrix/modules/main/include/prolog_before.php");
CModule::IncludeModule('iblock');
CModule::IncludeModule('crm');
$num = 0;
$r = 0;
$f = 0;
$h = 0;
$t = 0;
$p = 0;
$c = 0;
//------Наполняем справочник типов домов
$materialtype = array(
	'426' => 'block',
	'427' => 'wood',
	'428' => 'brick',
	'429' => 'monolithBrick',
	'430' => 'monolith',
	'431' => 'panel',
	'432' => 'stalin'
);
//------Наполнили справочник типов домов

//------Наполняем справочник материалов домов/дач
$wallstype = array(
	'413' => 'block',
	'414' => 'wood',
	'415' => 'wood',
	'416' => 'other',
	'417' => 'boards',
	'418' => 'brick',
	'419' => 'monolith',
	'420' => 'no',
	'421' => 'wood',
	'422' => 'panel',
	'423' => 'block',
	'424' => 'sandwich',
	'425' => 'block'
);
//------Наполнили справочник типов домов
//------Наполняем справочник категрий земель
$statusPlot = array(
	'530' => 'individualHousingConstruction',
	'531' => 'suburbanNonProfitPartnership',
	'532' => 'industrialLand'
);
//------Наполнили справочник категорий земель
$dom = new domDocument("1.0", "utf-8");
$feed = $dom->createElement("feed"); // Создаём корневой элемент
$dom->appendChild($feed);//Присоединяем его к документу	
$feed_version = $dom->createElement("feed_version",2);
$feed->appendChild($feed_version);
$db_res = $DB->Query("select b_crm_deal.ID, b_crm_deal.COMMENTS,b_uts_crm_deal.UF_CRM_58958B5734602, b_uts_crm_deal.UF_CRM_1472038962, b_uts_crm_deal.UF_CRM_1476517423,b_iblock_element.ID as ELEMENT_ID, b_iblock_element.CODE, b_iblock_element_prop_s42.PROPERTY_210, b_iblock_element_prop_s42.PROPERTY_300, b_iblock_element_prop_s42.PROPERTY_213, b_iblock_element_prop_s42.PROPERTY_214, b_iblock_element_prop_s42.PROPERTY_215,b_iblock_element_prop_s42.PROPERTY_216,b_iblock_element_prop_s42.PROPERTY_217,b_iblock_element_prop_s42.PROPERTY_218, b_iblock_element_prop_s42.PROPERTY_298, b_iblock_element_prop_s42.PROPERTY_299, b_iblock_element_prop_s42.PROPERTY_229, b_iblock_element_prop_s42.PROPERTY_228, b_iblock_element_prop_s42.PROPERTY_224, b_iblock_element_prop_s42.PROPERTY_292, b_iblock_element_prop_s42.PROPERTY_225, b_iblock_element_prop_s42.PROPERTY_226, b_iblock_element_prop_s42.PROPERTY_221, b_iblock_element_prop_s42.PROPERTY_222, b_iblock_element_prop_s42.PROPERTY_242, b_iblock_element_prop_s42.PROPERTY_243, b_iblock_element_prop_s42.PROPERTY_238, b_iblock_element_prop_s42.PROPERTY_295, b_iblock_element_prop_s42.PROPERTY_374, b_iblock_element_prop_s42.PROPERTY_258, b_iblock_element_prop_s42.PROPERTY_313, b_iblock_element_prop_s42.PROPERTY_375 from b_crm_deal LEFT JOIN b_uts_crm_deal ON b_crm_deal.ID = b_uts_crm_deal.VALUE_ID LEFT JOIN b_iblock_element ON b_uts_crm_deal.UF_CRM_1469534140 = b_iblock_element.ID LEFT JOIN b_iblock_element_prop_s42 ON b_uts_crm_deal.UF_CRM_1469534140 = b_iblock_element_prop_s42.IBLOCK_ELEMENT_ID where b_crm_deal.CATEGORY_ID = 0 and b_uts_crm_deal.UF_CRM_1469534140 <> '' and b_crm_deal.STAGE_ID = 'PROPOSAL' AND (b_uts_crm_deal.UF_CRM_1472038962<>'a:0:{}' OR b_uts_crm_deal.UF_CRM_1476517423 <> 'a:0:{}') ORDER BY b_crm_deal.DATE_MODIFY DESC");
while($aRes = $db_res->Fetch()){
	$object = $dom->createElement("object");// Создаём узел "Object"
	$ExternalId = $dom->createElement("ExternalId", "C".$aRes['ID']);
	$object->appendChild($ExternalId);
	$AddressString = ($aRes['PROPERTY_214'] == 'обл. подчинения')?$aRes['PROPERTY_213'].", ".$aRes['PROPERTY_215'].", ".$aRes['PROPERTY_217']:$aRes['PROPERTY_213'].", ".$aRes['PROPERTY_214'].", ".$aRes['PROPERTY_215'].", ".$aRes['PROPERTY_217'];
	switch ($aRes['PROPERTY_210']){
		case 381: //Комнаты
			$Category = $dom->createElement("Category", "roomSale");
			$object->appendChild($Category);
			$RoomsForSaleCount = $dom->createElement("RoomsForSaleCount", 1);
			$object->appendChild($RoomsForSaleCount);
			$RoomArea = $dom->createElement("RoomArea", number_format($aRes['PROPERTY_228'],2,".",""));
			$object->appendChild($RoomArea);
			if ($aRes['PROPERTY_224'] > 0){
				$TotalArea = $dom->createElement("TotalArea", number_format($aRes['PROPERTY_224'],2,".",""));
				$object->appendChild($TotalArea);
			}
			$FloorNumber = $dom->createElement("FloorNumber", $aRes['PROPERTY_221']);
			$object->appendChild($FloorNumber);
			$Building = $dom->createElement("Building");
				$FloorsCount = $dom->createElement("FloorsCount", $aRes['PROPERTY_222']);
				$Building->appendChild($FloorsCount);
				$MaterialType = $dom->createElement("MaterialType", $materialtype[$aRes['PROPERTY_243']]);
				$Building->appendChild($MaterialType);
			$object->appendChild($Building);
			$Address = $dom->createElement("Address", $AddressString.", ".$aRes['PROPERTY_218']);
			$object->appendChild($Address);
			$r++;
			$num++;
			break;
		case 382://Квартиры
			$Category = $dom->createElement("Category", "flatSale");
			$object->appendChild($Category);
			$FlatRoomsCount = $dom->createElement("FlatRoomsCount", intval($aRes['PROPERTY_229']));
			$object->appendChild($FlatRoomsCount);
			if ($aRes['PROPERTY_224'] > 0){
				$TotalArea = $dom->createElement("TotalArea", number_format($aRes['PROPERTY_224'],2,".",""));
				$object->appendChild($TotalArea);
			}
			if ($aRes['PROPERTY_225'] > 0){
				$LivingArea = $dom->createElement("LivingArea", number_format($aRes['PROPERTY_225'],2,".",""));
				$object->appendChild($LivingArea);
			}
			if ($aRes['PROPERTY_226'] > 0){
				$KitchenArea = $dom->createElement("KitchenArea", number_format($aRes['PROPERTY_226'],2,".",""));
				$object->appendChild($KitchenArea);
			}
			$FloorNumber = $dom->createElement("FloorNumber", $aRes['PROPERTY_221']);
			$object->appendChild($FloorNumber);
			$Building = $dom->createElement("Building");
				$FloorsCount = $dom->createElement("FloorsCount", $aRes['PROPERTY_222']);
				$Building->appendChild($FloorsCount);
				$MaterialType = $dom->createElement("MaterialType", $materialtype[$aRes['PROPERTY_243']]);
				$Building->appendChild($MaterialType);
			$object->appendChild($Building);
			$Address = $dom->createElement("Address", $AddressString.", ".$aRes['PROPERTY_218']);
			$object->appendChild($Address);
			$f++;
			$num++;
			break;
		case 383://Дома, дачи
		case 385:
			$Category = $dom->createElement("Category", "houseSale");
			$object->appendChild($Category);
			if ($aRes['PROPERTY_224'] > 0){
				$TotalArea = $dom->createElement("TotalArea", number_format($aRes['PROPERTY_224'],2,".",""));
				$object->appendChild($TotalArea);
			}
			$Building = $dom->createElement("Building");
				$FloorsCount = $dom->createElement("FloorsCount", $aRes['PROPERTY_222']);
				$Building->appendChild($FloorsCount);
				$MaterialType = $dom->createElement("MaterialType", $wallstype[$aRes['PROPERTY_242']]);
				$Building->appendChild($MaterialType);
			$object->appendChild($Building);
			$Land = $dom->createElement("Land");
				$Area = $dom->createElement("Area", number_format($aRes['PROPERTY_292'],2,".",""));
				$Land->appendChild($Area);
				$AreaUnitType =  $dom->createElement("AreaUnitType","sotka");
				$Land->appendChild($AreaUnitType);
			$object->appendChild($Land);
			$Address = $dom->createElement("Address", $AddressString);
			$object->appendChild($Address);
			$h++;
			$num++;
			break;
		case 384://Таунхаусы
			$Category = $dom->createElement("Category", "townhouseSale");
			$object->appendChild($Category);
			if ($aRes['PROPERTY_224'] > 0){
				$TotalArea = $dom->createElement("TotalArea", number_format($aRes['PROPERTY_224'],2,".",""));
				$object->appendChild($TotalArea);
			}
			$Building = $dom->createElement("Building");
				$FloorsCount = $dom->createElement("FloorsCount", $aRes['PROPERTY_222']);
				$Building->appendChild($FloorsCount);
				$MaterialType = $dom->createElement("MaterialType", $wallstype[$aRes['PROPERTY_242']]);
				$Building->appendChild($MaterialType);
			$object->appendChild($Building);
			$Land = $dom->createElement("Land");
				$Area = $dom->createElement("Area", number_format($aRes['PROPERTY_292'],2,".",""));
				$Land->appendChild($Area);
				$AreaUnitType =  $dom->createElement("AreaUnitType","sotka");
				$Land->appendChild($AreaUnitType);
			$object->appendChild($Land);
			$Address = $dom->createElement("Address", $AddressString);
			$object->appendChild($Address);
			$t++;
			$num++;
			break;
		case 386://Участки
			$Category = $dom->createElement("Category", "landSale");
			$object->appendChild($Category);
			$Land = $dom->createElement("Land");
				$Area = $dom->createElement("Area", number_format($aRes['PROPERTY_292'],2,".",""));
				$Land->appendChild($Area);
				$AreaUnitType =  $dom->createElement("AreaUnitType","sotka");
				$Land->appendChild($AreaUnitType);
				$Status = $dom->createElement("Status",$statusPlot[$aRes['PROPERTY_295']]);
				$Land->appendChild($Status);
			$object->appendChild($Land);
			$Address = $dom->createElement("Address", $AddressString);
			$object->appendChild($Address);
			$p++;
			$num++;
			break;
		case 387://Коммерческая
			switch ($aRes['PROPERTY_238']){
				case 388://Бизнес-центр
					break;
				case 389://Гараж
					$Category = $dom->createElement("Category", "garageSale");
					$object->appendChild($Category);
					$Garage = $dom->createElement("Garage");
						$Type = $dom->createElement("Type","garage");
						$Garage->appendChild($Type);
					$object->appendChild($Garage);
					if ($aRes['PROPERTY_224'] > 0){
						$TotalArea = $dom->createElement("TotalArea", number_format($aRes['PROPERTY_224'],2,".",""));
						$object->appendChild($TotalArea);
					}
					break;
				case 390://Гостиница
					$Category = $dom->createElement("Category", "businessSale");
					$object->appendChild($Category);
					if ($aRes['PROPERTY_224'] > 0){
						$TotalArea = $dom->createElement("TotalArea", number_format($aRes['PROPERTY_224'],2,".",""));
						$object->appendChild($TotalArea);
					}
					$FloorNumber = $dom->createElement("FloorNumber", $aRes['PROPERTY_221']);
					$object->appendChild($FloorNumber);
					$Specialty = $dom->createElement("Specialty");
						$Types = $dom->createElement("Types","hotel");
						$Specialty->appendChild($Types);
					$object->appendChild($Specialty);
					$Building = $dom->createElement("Building");
						$FloorsCount = $dom->createElement("FloorsCount", $aRes['PROPERTY_222']);
						$Building->appendChild($FloorsCount);
					$object->appendChild($Building);
					break;
				case 391://Иное
					break;
				case 392://Магазин
					$Category = $dom->createElement("Category", "shoppingAreaSale");
					$object->appendChild($Category);
					$PlacementType = $dom->createElement("PlacementType", "streetRetail");
					$object->appendChild($PlacementType);
					if ($aRes['PROPERTY_224'] > 0){
						$TotalArea = $dom->createElement("TotalArea", number_format($aRes['PROPERTY_224'],2,".",""));
						$object->appendChild($TotalArea);
					}
					$FloorNumber = $dom->createElement("FloorNumber", $aRes['PROPERTY_221']);
					$object->appendChild($FloorNumber);
					$Building = $dom->createElement("Building");
						$FloorsCount = $dom->createElement("FloorsCount", $aRes['PROPERTY_222']);
						$Building->appendChild($FloorsCount);
					$object->appendChild($Building);
					break;
				case 393://Отдельно-стоящее здание
					$Category = $dom->createElement("Category", "buildingSale");
					$object->appendChild($Category);
					$Building = $dom->createElement("Building");
						$FloorsCount = $dom->createElement("FloorsCount", $aRes['PROPERTY_222']);
						$Building->appendChild($FloorsCount);
						$TotalArea = $dom->createElement("TotalArea", number_format($aRes['PROPERTY_224'],2,".",""));
						$Building->appendChild($TotalArea);
					$object->appendChild($Building);
					break;
				case 394://Офис
					$Category = $dom->createElement("Category", "officeSale");
					$object->appendChild($Category);
					$TotalArea = $dom->createElement("TotalArea", number_format($aRes['PROPERTY_224'],2,".",""));
					$object->appendChild($TotalArea);
					$FloorNumber = $dom->createElement("FloorNumber", $aRes['PROPERTY_221']);
					$object->appendChild($FloorNumber);
					$Building = $dom->createElement("Building");
						$FloorsCount = $dom->createElement("FloorsCount", $aRes['PROPERTY_222']);
						$Building->appendChild($FloorsCount);
					$object->appendChild($Building);
					break;
				case 395://Помещение свободного назначения
					$Category = $dom->createElement("Category", "freeAppointmentObjectSale");
					$object->appendChild($Category);
					$TotalArea = $dom->createElement("TotalArea", number_format($aRes['PROPERTY_224'],2,".",""));
					$object->appendChild($TotalArea);
					$FloorNumber = $dom->createElement("FloorNumber", $aRes['PROPERTY_221']);
					$object->appendChild($FloorNumber);
					$Building = $dom->createElement("Building");
						$FloorsCount = $dom->createElement("FloorsCount", $aRes['PROPERTY_222']);
						$Building->appendChild($FloorsCount);
					$object->appendChild($Building);
					break;
				case 396://Предприятие питания
					$Category = $dom->createElement("Category", "businessSale");
					$object->appendChild($Category);
					if ($aRes['PROPERTY_224'] > 0){
						$TotalArea = $dom->createElement("TotalArea", number_format($aRes['PROPERTY_224'],2,".",""));
						$object->appendChild($TotalArea);
					}
					$FloorNumber = $dom->createElement("FloorNumber", $aRes['PROPERTY_221']);
					$object->appendChild($FloorNumber);
					$Specialty = $dom->createElement("Specialty");
						$Types = $dom->createElement("Types","publicCatering");
						$Specialty->appendChild($Types);
					$object->appendChild($Specialty);
					$Building = $dom->createElement("Building");
						$FloorsCount = $dom->createElement("FloorsCount", $aRes['PROPERTY_222']);
						$Building->appendChild($FloorsCount);
					$object->appendChild($Building);
					break;
				case 397://Производственно-промышленное помещение
					$Category = $dom->createElement("Category", "industrySale");
					$object->appendChild($Category);
					$TotalArea = $dom->createElement("TotalArea", number_format($aRes['PROPERTY_224'],2,".",""));
					$object->appendChild($TotalArea);
					$FloorNumber = $dom->createElement("FloorNumber", $aRes['PROPERTY_221']);
					$object->appendChild($FloorNumber);
					$Building = $dom->createElement("Building");
						$FloorsCount = $dom->createElement("FloorsCount", $aRes['PROPERTY_222']);
						$Building->appendChild($FloorsCount);
					$object->appendChild($Building);
					break;
				case 398://Склад
					$Category = $dom->createElement("Category", "warehouseSale");
					$object->appendChild($Category);
					$TotalArea = $dom->createElement("TotalArea", number_format($aRes['PROPERTY_224'],2,".",""));
					$object->appendChild($TotalArea);
					$FloorNumber = $dom->createElement("FloorNumber", $aRes['PROPERTY_221']);
					$object->appendChild($FloorNumber);
					$Building = $dom->createElement("Building");
						$FloorsCount = $dom->createElement("FloorsCount", $aRes['PROPERTY_222']);
						$Building->appendChild($FloorsCount);
					$object->appendChild($Building);
					break;
				case 399://Торговый центр
					$Category = $dom->createElement("Category", "businessSale");
					$object->appendChild($Category);
					if ($aRes['PROPERTY_224'] > 0){
						$TotalArea = $dom->createElement("TotalArea", number_format($aRes['PROPERTY_224'],2,".",""));
						$object->appendChild($TotalArea);
					}
					$FloorNumber = $dom->createElement("FloorNumber", $aRes['PROPERTY_221']);
					$object->appendChild($FloorNumber);
					$Specialty = $dom->createElement("Specialty");
						$Types = $dom->createElement("Types","tradingCenter");
						$Specialty->appendChild($Types);
					$object->appendChild($Specialty);
					$Building = $dom->createElement("Building");
						$FloorsCount = $dom->createElement("FloorsCount", $aRes['PROPERTY_222']);
						$Building->appendChild($FloorsCount);
					$object->appendChild($Building);
					break;
			}
			$Address = $dom->createElement("Address", $AddressString);
			$object->appendChild($Address);
			$c++;
			$num++;
			break;
	}
	$Coodinates = $dom->createElement("Coordinates");
		$Lat = $dom->createElement("Lat",$aRes['PROPERTY_298']);
		$Coodinates->appendChild($Lat);
		$Lng = $dom->createElement("Lng",$aRes['PROPERTY_299']);
		$Coodinates->appendChild($Lng);
	$object->appendChild($Coodinates);
	
	$Description = $dom->createElement("Description", html_entity_decode($aRes['COMMENTS'])." Номер заявки в базе ЕЦН: ".$aRes['ID'].". При обращении в компанию назовите этот номер сотруднику, это поможет быстрее обработать Ваш запрос.");
	$object->appendChild($Description);
	
	$Photos = $dom->createElement("Photos");
	foreach (unserialize($aRes['UF_CRM_1472038962']) as $key=>$imageid){
		$PhotoSchema = $dom->createElement("PhotoSchema");
		$FullUrl = $dom->createElement("FullUrl","https://bpm.ucre.ru".CFile::GetPath($imageid));
		$PhotoSchema->appendChild($FullUrl);
		$IsDefault = $dom->createElement("IsDefault",($key)?false:true);
		$PhotoSchema->appendChild($IsDefault);
		$Photos->appendChild($PhotoSchema);
	}
	$object->appendChild($Photos);
	
	if (unserialize($aRes['UF_CRM_1472038962'])[0]){
		$LayoutPhoto = $dom->createElement("LayoutPhoto");
		$FullUrl = $dom->createElement("FullUrl","https://bpm.ucre.ru".CFile::GetPath(unserialize($aRes['UF_CRM_1472038962'])[0]));
		$LayoutPhoto->appendChild($FullUrl);
		$IsDefault = $dom->createElement("IsDefault",1);
		$LayoutPhoto->appendChild($IsDefault);
		$object->appendChild($LayoutPhoto);
	}
	$BargainTerms = $dom->createElement("BargainTerms");
	$Price = $dom->createElement("Price",$aRes['UF_CRM_58958B5734602']);
	$BargainTerms->appendChild($Price);
	$object->appendChild($BargainTerms);
	$Phones = $dom->createElement("Phones");
		$PhoneSchema = $dom->createElement("PhoneSchema");
			$CountryCode = $dom->createElement("CountryCode","+7");
			$PhoneSchema->appendChild($CountryCode);
			$Number = $dom->createElement("Number","9228090357");
			$PhoneSchema->appendChild($Number);
		$Phones->appendChild($PhoneSchema);
	$object->appendChild($Phones);
	$feed->appendChild($object); // Добавляем в корневой узел "feed" узел "object"
}


$result = $dom->save("/home/bitrix/ucre.ru/orenburg_cian.xml"); // Сохраняем полученный XML-документ в файл
$time = microtime(true) - $start;
CEventLog::Add(array(
  "SEVERITY" => "SECURITY",
  "AUDIT_TYPE_ID" => "CIAN_EXPORT",
  "MODULE_ID" => "main",
  "ITEM_ID" => 'Каталог недвижимости',
  "DESCRIPTION" => "Результат записи фида: ".$result."<br>Выгрузка скриптом объектов недвижимости в формате CIAN, выгружено ".$num." объектов за ".$time." секунд (включая комнат - ".$r.", квартир - ".$f.", домов, дач - ".$h.", таунхаусов - ".$t.", участков - ".$p.", коммерческих - ".$c.").",
));
echo "Результат записи фида: ".$result."<br>Выгрузка скриптом объектов недвижимости в формате CIAN, выгружено ".$num." объектов за ".$time." секунд (включая комнат - ".$r.", квартир - ".$f.", домов, дач - ".$h.", таунхаусов - ".$t.", участков - ".$p.", коммерческих - ".$c.").";
require ($_SERVER['DOCUMENT_ROOT'] . "/bitrix/modules/main/include/epilog_after.php");      
?>