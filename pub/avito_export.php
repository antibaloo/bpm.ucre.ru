<?php
$start = microtime(true);//Засекаем время выполнения скрипта
require ($_SERVER['DOCUMENT_ROOT'] . "/bitrix/modules/main/include/prolog_before.php");
CModule::IncludeModule('iblock');
CModule::IncludeModule('crm');
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
//------Наполняем справочник типов домов
$property_enums = CIBlockPropertyEnum::GetList(Array(), Array("IBLOCK_ID"=>42, "CODE"=>"HOUSE_TYPE"));
$housetype = array();
while($enum_fields = $property_enums->GetNext()){
  $housetype[$enum_fields["ID"]] = $enum_fields["VALUE"];
}
$housetype[429] = "Монолитный";
//------Наполнили справочник типов домов
//------Наполняем справочник материалов стен
$property_enums = CIBlockPropertyEnum::GetList(Array(), Array("IBLOCK_ID"=>42, "CODE"=>"WALLS"));
$walls = array();
while($enum_fields = $property_enums->GetNext()){
  $walls[$enum_fields["ID"]] = $enum_fields["VALUE"];
}
$walls[416] = 'Экспериментальные материалы';
$walls[417] = 'Экспериментальные материалы';
$walls[413] = 'Пеноблоки';
$walls[425] = 'Пеноблоки';
$walls[423] = 'Пеноблоки';
$walls[422] = 'Ж/б панели';
//------Наполнили справочник материалов стен
//------Наполняем справочник категорий участков
$property_enums = CIBlockPropertyEnum::GetList(Array(), Array("IBLOCK_ID"=>42, "CODE"=>"PLOT_CAT"));
$plotcat = array();
while($enum_fields = $property_enums->GetNext()){
  $plotcat[$enum_fields["ID"]] = $enum_fields["VALUE"];
}
//------Наполнили справочник категорий участков
//------Наполняем справочник назначений коммерческих объектов
$property_enums = CIBlockPropertyEnum::GetList(Array(), Array("IBLOCK_ID"=>42, "CODE"=>"APPOINTMENT"));
$appointment = array();
while($enum_fields = $property_enums->GetNext()){
  $appointment[$enum_fields["ID"]] = $enum_fields["VALUE"];
}
//------Наполнили справочник назначений коммерческих объектов
$dom = new domDocument("1.0", "utf-8");
$Ads = $dom->createElement("Ads"); // Создаём корневой элемент
$Ads->setAttribute("formatVersion","3");//Добавляем элементу свойство
$Ads->setAttribute("target","Avito.ru");//Добавляем элементу свойство
$dom->appendChild($Ads);//Присоединяем его к документу	
$db_res = $DB->Query("select b_crm_deal.ID, b_crm_deal.COMMENTS,b_uts_crm_deal.UF_CRM_58958B5734602, b_uts_crm_deal.UF_CRM_1472038962, b_uts_crm_deal.UF_CRM_1476517423,b_iblock_element.ID as ELEMENT_ID, b_iblock_element.CODE, b_iblock_element_prop_s42.PROPERTY_210, b_iblock_element_prop_s42.PROPERTY_300, b_iblock_element_prop_s42.PROPERTY_213, b_iblock_element_prop_s42.PROPERTY_214, b_iblock_element_prop_s42.PROPERTY_215,b_iblock_element_prop_s42.PROPERTY_216,b_iblock_element_prop_s42.PROPERTY_217,b_iblock_element_prop_s42.PROPERTY_218, b_iblock_element_prop_s42.PROPERTY_298, b_iblock_element_prop_s42.PROPERTY_299, b_iblock_element_prop_s42.PROPERTY_229, b_iblock_element_prop_s42.PROPERTY_228, b_iblock_element_prop_s42.PROPERTY_224, b_iblock_element_prop_s42.PROPERTY_292, b_iblock_element_prop_s42.PROPERTY_225, b_iblock_element_prop_s42.PROPERTY_226, b_iblock_element_prop_s42.PROPERTY_221, b_iblock_element_prop_s42.PROPERTY_222, b_iblock_element_prop_s42.PROPERTY_242, b_iblock_element_prop_s42.PROPERTY_243, b_iblock_element_prop_s42.PROPERTY_238, b_iblock_element_prop_s42.PROPERTY_295, b_iblock_element_prop_s42.PROPERTY_374, b_iblock_element_prop_s42.PROPERTY_258, b_iblock_element_prop_s42.PROPERTY_313, b_iblock_element_prop_s42.PROPERTY_375 from b_crm_deal LEFT JOIN b_uts_crm_deal ON b_crm_deal.ID = b_uts_crm_deal.VALUE_ID LEFT JOIN b_iblock_element ON b_uts_crm_deal.UF_CRM_1469534140 = b_iblock_element.ID LEFT JOIN b_iblock_element_prop_s42 ON b_uts_crm_deal.UF_CRM_1469534140 = b_iblock_element_prop_s42.IBLOCK_ELEMENT_ID where b_crm_deal.CATEGORY_ID = 0 and b_uts_crm_deal.UF_CRM_1469534140 <> '' and b_crm_deal.STAGE_ID = 'PROPOSAL' AND TIMESTAMP(b_iblock_element_prop_s42.PROPERTY_260) >=NOW() AND (PROPERTY_374=1356 OR PROPERTY_374=1357) ORDER BY b_crm_deal.ID DESC");
while($aRes = $db_res->Fetch()){
  $Ad = $dom->createElement("Ad");// Создаём узел "Object"
  $Id = $dom->createElement("Id", (!empty($aRes['CODE']))? $aRes['CODE'] : $aRes['ELEMENT_ID']); // Создаём узел "Id" с ID(CODE) элемента инфоблока (объекта недвижимости) внутри
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
	$ListingFee = $dom->createElement('ListingFee','PackageSingle');
	$Ad->appendChild($ListingFee);
  $Region = $dom->createElement("Region",($aRes['PROPERTY_213']=="Оренбургская обл")?"Оренбургская область":$aRes['PROPERTY_213']);
  $Ad->appendChild($Region);
	/*-Подмена улицы для выгрузки на Авито (клиентов пугает снт в адресе)-*/
	if (strripos($aRes['PROPERTY_217'],"|")){
		$arr_street = explode("|",$aRes['PROPERTY_217']);
		$street = $arr_street[1];
	}else{
		$street = $aRes['PROPERTY_217'];
	}
	/*--------------------------------------------------------------------*/
  //Формирование адреса объекта в зависимости от типа объекта и наличия населенного пункта в справочнике Авито
  if (trim($aRes['PROPERTY_215']) == 'Оренбург г'){//Если объект в Оренбурге
    $City = $dom->createElement("City",'Оренбург');
    $Ad->appendChild($City);
    if(in_array(trim($aRes['PROPERTY_216']),$districtspr)){//Если район города есть в справочнике
      if ($aRes['PROPERTY_216']!="отсутствует") {
        $District = $dom->createElement("District",$aRes['PROPERTY_216']);
        $Ad->appendChild($District);
      }
      if ($aRes['PROPERTY_210']==382 || $aRes['PROPERTY_210']==381) {
        if ($aRes['PROPERTY_258']==""){
          $Street = $dom->createElement("Street",$street.', '.$aRes['PROPERTY_218']);//Улица, дом
          $Ad->appendChild($Street);
        }
      }else{
        if ($aRes['PROPERTY_258']==""){
          $Street = $dom->createElement("Street",$street);//Улица
          $Ad->appendChild($Street);
        }
      }
    }else{//Если района в справочнике нет
      if ($aRes['PROPERTY_210']==382 || $aRes['PROPERTY_210']==381) {
        if ($aRes['PROPERTY_258']==""){
          $Street = $dom->createElement("Street",$aRes['PROPERTY_216'].','.$street.', '.$aRes['PROPERTY_218']);//Район, улица, дом
          $Ad->appendChild($Street);
        }
      }else{
        if ($aRes['PROPERTY_258']==""){
          $Street = $dom->createElement("Street",$aRes['PROPERTY_216'].','.$street);//Район, улица
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
          $Street = $dom->createElement("Street",$street.', '.$aRes['PROPERTY_218']);//Улица, дом
          $Ad->appendChild($Street);
        }
      }else{
        if ($aRes['PROPERTY_258']==""){
          $Street = $dom->createElement("Street",$street);//Улица
          $Ad->appendChild($Street);
        }
      }
    }else{//Если населенного пункта нет в справочнике Авито
      switch ($aRes['PROPERTY_214']){
				case "Саракташский р-н":
					$City = $dom->createElement("City",'Саракташ');
					$Ad->appendChild($City);
					break;
				case "Беляевский р-н":
					$City = $dom->createElement("City",'Беляевка');
					$Ad->appendChild($City);
					break;
				case "Сакмарский р-н":
					$City = $dom->createElement("City",'Сакмара');
					$Ad->appendChild($City);
					break;
				case "Оренбургский р-н":
					$City = $dom->createElement("City",'Оренбург');
					$Ad->appendChild($City);
					$District = $dom->createElement("District","Ленинский");
					$Ad->appendChild($District);
					break;
				case "Октябрьский р-н":
					$City = $dom->createElement("City",'Октябрьское');
					$Ad->appendChild($City);
					break;
				default:
					$City = $dom->createElement("City",'Оренбург');
					$Ad->appendChild($City);
					$District = $dom->createElement("District","Ленинский");
					$Ad->appendChild($District);
					break;
			}
      if ($aRes['PROPERTY_210']==382 || $aRes['PROPERTY_210']==381) {
        if ($aRes['PROPERTY_258']==""){
          $Street = $dom->createElement("Street",$aRes['PROPERTY_214'].','.$aRes['PROPERTY_215'].', '.$street.', '.$aRes['PROPERTY_218']);//Район, нас. пункт, улица, дом
          $Ad->appendChild($Street);
        }
      }else{
        if ($aRes['PROPERTY_258']==""){
          $Street = $dom->createElement("Street",$aRes['PROPERTY_214'].','.$aRes['PROPERTY_215'].', '.$street);//Район, нас. пункт, улица
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
    case 381:
      $Square = $dom->createElement("Square",number_format($aRes['PROPERTY_228'],2,".",""));
      $Ad->appendChild($Square);
      break;
    case 382:
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
	if ($aRes['PROPERTY_210']==382){
		if ($aRes['PROPERTY_226'] > 0){
			$kitchenSpace = $dom->createElement("KitchenSpace",number_format($aRes['PROPERTY_226'],2,".",""));
			$Ad->appendChild($kitchenSpace);
		}
		if ($aRes['PROPERTY_225'] > 0){
			$livingSpace = $dom->createElement("LivingSpace",number_format($aRes['PROPERTY_225'],2,".",""));
			$Ad->appendChild($livingSpace);
		}
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
	$PropertyRights = $dom->createElement("PropertyRights","Собственник");
	$Ad->appendChild($PropertyRights);
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
      $DistanceToCity = $dom->createElement("DistanceToCity", intval($aRes['PROPERTY_375']));
      $Ad->appendChild($DistanceToCity);
      break;
  }
  $Price = $dom->createElement("Price", $aRes['UF_CRM_58958B5734602']);
  $Ad->appendChild($Price);
  $Description = $dom->createElement("Description", html_entity_decode($aRes['COMMENTS'])." Номер заявки в базе ЕЦН: ".$aRes['ID'].". При обращении в компанию назовите этот номер сотруднику, это поможет быстрее обработать Ваш запрос.");//Номер в базе - новый ID
  $Ad->appendChild($Description);
  if ($aRes['PROPERTY_210']==382){
    $MarketType = $dom->createElement("MarketType", ($aRes['PROPERTY_258']=="")? "Вторичка":"Новостройка");
    $Ad->appendChild($MarketType);
  }
  if ($aRes['PROPERTY_258']!=""){
    $NewDevelopmentId = $dom->createElement("NewDevelopmentId",substr($aRes['PROPERTY_258'],0,strpos($aRes['PROPERTY_258']," ")));
    $Ad->appendChild($NewDevelopmentId);
  }
	
	$imageLink = array();
	foreach (unserialize($aRes['UF_CRM_1472038962']) as $imageid){
		if (CFile::GetPath($imageid))	$imageLink[] = "https://bpm.ucre.ru".CFile::GetPath($imageid);
	}
	foreach (unserialize($aRes['UF_CRM_1476517423']) as $imageid){
		if (CFile::GetPath($imageid)) $imageLink[] = "https://bpm.ucre.ru".CFile::GetPath($imageid);
	}
	$max = (count($imageLink) > 20)?20:count($imageLink);
  $Images = $dom->createElement("Images");
	for ($i=0;$i<$max;$i++){
		$Image = $dom->createElement("Image");
		$Image->setAttribute("url", $imageLink[$i]);
		$Images->appendChild($Image);		
	}
	/*
  foreach (unserialize($aRes['UF_CRM_1472038962']) as $imageid){
		if (CFile::GetPath($imageid)){
			$Image = $dom->createElement("Image");
			$Image->setAttribute("url", "https://bpm.ucre.ru".CFile::GetPath($imageid));
			$Images->appendChild($Image);
		}
  }
  foreach (unserialize($aRes['UF_CRM_1476517423']) as $imageid){
		if (CFile::GetPath($imageid)){
			$Image = $dom->createElement("Image");
			$Image->setAttribute("url", "https://bpm.ucre.ru".CFile::GetPath($imageid));
			$Images->appendChild($Image);
		}
  }*/
  $Ad->appendChild($Images);
		
  $CompanyName = $dom->createElement("CompanyName","Единый центр недвижимости");
  $Ad->appendChild($CompanyName);
	$AllowEmail = $dom->createElement("AllowEmail","Нет");
	$Ad->appendChild($AllowEmail);
  if ($aRes['PROPERTY_374'] == 1356){
    $rsUser = CUser::GetByID($aRes['PROPERTY_313']);
    $arUser = $rsUser->Fetch();
    $ManagerName = $dom->createElement("ManagerName",$arUser['LAST_NAME']." ".$arUser['NAME']." ".$arUser['SECOND_NAME']);
    $Ad->appendChild($ManagerName);
    $EMail = $dom->createElement("EMail", $arUser['EMAIL']);
    $Ad->appendChild($EMail);
    $ContactPhone = $dom->createElement("ContactPhone", $arUser['PERSONAL_PHONE']);
    $Ad->appendChild($ContactPhone);    
  }
  if ($aRes['PROPERTY_374'] == 1357){
    $ManagerName = $dom->createElement("ManagerName", "Менеджер по работе с клиентами");
    $Ad->appendChild($ManagerName);
    $EMail = $dom->createElement("EMail", 'info@ucre.ru');
    $Ad->appendChild($EMail);
    $ContactPhone = $dom->createElement("ContactPhone", "+7 (932) 536-06-57");
    $Ad->appendChild($ContactPhone);
  }
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
  "DESCRIPTION" => "Результат записи фида: ".$result."<br>Выгрузка скриптом объектов недвижимости в формате АВИТО, выгружено ".$num." объектов за ".$time." секунд (включая комнат - ".$r.", квартир - ".$f.", домов, дач, коттеджей - ".$h.", участков - ".$p.", коммерческих - ".$c.").",
));
echo "Результат записи фида: ".$result."<br>Выгрузка скриптом объектов недвижимости в формате АВИТО, выгружено ".$num." объектов за ".$time." секунд (включая комнат - ".$r.", квартир - ".$f.", домов, дач, коттеджей - ".$h.", участков - ".$p.", коммерческих - ".$c.").";
require ($_SERVER['DOCUMENT_ROOT'] . "/bitrix/modules/main/include/epilog_after.php");      
?>