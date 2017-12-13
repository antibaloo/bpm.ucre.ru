<?php
$start = microtime(true);//Засекаем время выполнения скрипта
require ($_SERVER['DOCUMENT_ROOT'] . "/bitrix/modules/main/include/prolog_before.php");
CModule::IncludeModule('iblock');
CModule::IncludeModule('crm');
$locations = simplexml_load_file('http://autoload.avito.ru/format/Locations.xml');
$cityspr = array();//Справочник населенных пунктов Оренбургской области по версии Авито
$cityCoords = array();
$districtspr = array("Ленинский", "Промышленный", "Центральный", "Дзержинский","отсутствует");//Справочник районов города
foreach ($locations->Region as $region) {
  if ($region['Id']=='642480') {
    foreach ($region->City as $city){
      array_push($cityspr,trim((string)$city['Name']));
			$tempCoords = explode(" ",(string)$city['Coord']);
			$cityCoords[trim((string)$city['Name'])] =array(
				"lat" => $tempCoords[0],
				"lon" => $tempCoords[1]
			);
    }
  }
}
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
$num = 0;
$r = 0;
$f = 0;
$h = 0;
$p = 0;
$c = 0;
$xml = new XMLWriter();
$xml->openURI('/home/bitrix/www_bpm/orenburg_avito.xml');
$xml->startDocument("1.0", "utf-8");
$xml->startElement("Ads");//Корневой элемент Ads
$xml->writeAttribute("formatVersion","3");
$xml->writeAttribute("target","Avito.ru");

$db_res = $DB->Query("select b_crm_deal.ID, b_crm_deal.COMMENTS,b_uts_crm_deal.UF_CRM_58958B5734602, b_uts_crm_deal.UF_CRM_1472038962, b_uts_crm_deal.UF_CRM_1476517423,b_iblock_element.ID as ELEMENT_ID, b_iblock_element.CODE, b_iblock_element_prop_s42.PROPERTY_210, b_iblock_element_prop_s42.PROPERTY_300, b_iblock_element_prop_s42.PROPERTY_212, b_iblock_element_prop_s42.PROPERTY_213, b_iblock_element_prop_s42.PROPERTY_214, b_iblock_element_prop_s42.PROPERTY_215,b_iblock_element_prop_s42.PROPERTY_216,b_iblock_element_prop_s42.PROPERTY_217,b_iblock_element_prop_s42.PROPERTY_218, b_iblock_element_prop_s42.PROPERTY_298, b_iblock_element_prop_s42.PROPERTY_299, b_iblock_element_prop_s42.PROPERTY_229, b_iblock_element_prop_s42.PROPERTY_228, b_iblock_element_prop_s42.PROPERTY_224, b_iblock_element_prop_s42.PROPERTY_292, b_iblock_element_prop_s42.PROPERTY_225, b_iblock_element_prop_s42.PROPERTY_226, b_iblock_element_prop_s42.PROPERTY_221, b_iblock_element_prop_s42.PROPERTY_222, b_iblock_element_prop_s42.PROPERTY_242, b_iblock_element_prop_s42.PROPERTY_243, b_iblock_element_prop_s42.PROPERTY_238, b_iblock_element_prop_s42.PROPERTY_295, b_iblock_element_prop_s42.PROPERTY_374, b_iblock_element_prop_s42.PROPERTY_258, b_iblock_element_prop_s42.PROPERTY_313, b_iblock_element_prop_s42.PROPERTY_375 from b_crm_deal LEFT JOIN b_uts_crm_deal ON b_crm_deal.ID = b_uts_crm_deal.VALUE_ID LEFT JOIN b_iblock_element ON b_uts_crm_deal.UF_CRM_1469534140 = b_iblock_element.ID LEFT JOIN b_iblock_element_prop_s42 ON b_uts_crm_deal.UF_CRM_1469534140 = b_iblock_element_prop_s42.IBLOCK_ELEMENT_ID where b_crm_deal.CATEGORY_ID = 0 and b_uts_crm_deal.UF_CRM_1469534140 <> '' and (b_crm_deal.STAGE_ID = 'PROPOSAL' OR b_crm_deal.STAGE_ID = '1' OR b_crm_deal.STAGE_ID = '13') AND b_uts_crm_deal.UF_CRM_1512621495<>1  AND TIMESTAMP(b_iblock_element_prop_s42.PROPERTY_260) >=NOW() AND (PROPERTY_374=1356 OR PROPERTY_374=1357) ORDER BY b_crm_deal.ID DESC");
while($aRes = $db_res->Fetch()){
  $xml->startElement("Ad");
  $xml->writeElement("Id",(!empty($aRes['CODE']))? $aRes['CODE'] : $aRes['ELEMENT_ID']); // Создаём узел "Id" с ID(CODE) элемента инфоблока (объекта недвижимости) внутри
  switch ($aRes['PROPERTY_210']){
    case 382:
      $xml->writeElement("Category","Квартиры");
      $f++;
      break;
    case 381:
      $xml->writeElement("Category","Комнаты");
      $r++;
      break;
    case 386:
      $xml->writeElement("Category","Земельные участки");
      $p++;
      break;
    case 383:
    case 384:
    case 385:
      $xml->writeElement("Category","Дома, дачи, коттеджи");
      $h++;
      break;
    case 387:
      $xml->writeElement("Category","Коммерческая недвижимость");
      $c++;
      break;
    default:
      $xml->writeElement("Category","ХЗ");
      break;
  }
  $xml->writeElement("OperationType",$aRes['PROPERTY_300']);
  $xml->writeElement('DateEnd', date("Y-m-d", strtotime("+30 days")));
	$xml->writeElement('ListingFee','Package');
  $xml->writeElement("Region",($aRes['PROPERTY_213']=="Оренбургская обл")?"Оренбургская область":$aRes['PROPERTY_213']);
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
    $xml->writeElement("City",'Оренбург');
    if(in_array(trim($aRes['PROPERTY_216']),$districtspr)){//Если район города есть в справочнике
      if ($aRes['PROPERTY_216']!="отсутствует") $xml->writeElement("District",$aRes['PROPERTY_216']);
      if ($aRes['PROPERTY_210']==382 || $aRes['PROPERTY_210']==381) {
        if ($aRes['PROPERTY_258']=="") $xml->writeElement("Street",$street.', '.$aRes['PROPERTY_218']);//Улица, дом
      }else{
        if ($aRes['PROPERTY_258']=="") $xml->writeElement("Street",$street);//Улица
      }
    }else{//Если района в справочнике нет
      if ($aRes['PROPERTY_210']==382 || $aRes['PROPERTY_210']==381) {
        if ($aRes['PROPERTY_258']=="") $xml->writeElement("Street",$aRes['PROPERTY_216'].','.$street.', '.$aRes['PROPERTY_218']);//Район, улица, дом
      }else{
        if ($aRes['PROPERTY_258']=="") $xml->writeElement("Street",$aRes['PROPERTY_216'].','.$street);//Район, улица
      }
    }
  }else{//Если объект не в Оренбурге
    if (in_array(substr($aRes['PROPERTY_215'],0,strrpos($aRes['PROPERTY_215']," ")),$cityspr)){//Если населенный пункт есть в справочнике Авито
      $xml->writeElement("City",substr($aRes['PROPERTY_215'],0,strrpos($aRes['PROPERTY_215']," ")));
      if ($aRes['PROPERTY_210']==382 || $aRes['PROPERTY_210']==381) {
        if ($aRes['PROPERTY_258']=="") $xml->writeElement("Street",$street.', '.$aRes['PROPERTY_218']);//Улица, дом
      }else{
        if ($aRes['PROPERTY_258']=="") $xml->writeElement("Street",$street);//Улица
      }
    }else{//Если населенного пункта нет в справочнике Авито
			$city = "";
			$distance = 17.846840;//условное расстояние по координатам до Москвы
			foreach($cityCoords as $name=>$coords){
				$temp = sqrt(($aRes['PROPERTY_298']-$coords['lat'])*($aRes['PROPERTY_298']-$coords['lat'])+($aRes['PROPERTY_299']-$coords['lon'])*($aRes['PROPERTY_299']-$coords['lon']));
				if ($temp<$distance) {
					$distance = $temp;
					$city = $name;
				}
			}
			$xml->writeElement("City",$city);
			if ($city=="Оренбург") $xml->writeElement("District","Ленинский");			
      if ($aRes['PROPERTY_210']==382 || $aRes['PROPERTY_210']==381) {
        if ($aRes['PROPERTY_258']=="") $xml->writeElement("Street",$aRes['PROPERTY_214'].','.$aRes['PROPERTY_215'].', '.$street.', '.$aRes['PROPERTY_218']);//Район, нас. пункт, улица, дом
      }else{
        if ($aRes['PROPERTY_258']=="") $xml->writeElement("Street",$aRes['PROPERTY_214'].','.$aRes['PROPERTY_215'].', '.$street);//Район, нас. пункт, улица
      }
    }
  }
  if ($aRes['PROPERTY_298'] && $aRes['PROPERTY_299']){
    if ($aRes['PROPERTY_258']==""){
      $xml->writeElement("Latitude", $aRes['PROPERTY_298']);
      $xml->writeElement("Longitude", $aRes['PROPERTY_299']);
    }
  }
  if ($aRes['PROPERTY_210']==382 || $aRes['PROPERTY_210']==381) $xml->writeElement("Rooms", intval($aRes['PROPERTY_229']));
  switch ($aRes['PROPERTY_210']){
    case 381:
      $xml->writeElement("Square",number_format($aRes['PROPERTY_228'],2,".",""));
      break;
    case 382:
    case 387:
      $xml->writeElement("Square",number_format($aRes['PROPERTY_224'],2,".",""));
      break;
    case 386:
      $xml->writeElement("LandArea",number_format($aRes['PROPERTY_292'],2,".",""));
      break;
    case 383:
    case 384:
    case 385:
      $xml->writeElement("Square",number_format($aRes['PROPERTY_224'],2,".",""));
      $xml->writeElement("LandArea",number_format($aRes['PROPERTY_292'],2,".",""));
      break;
  }
  if ($aRes['PROPERTY_210']==382){
		if ($aRes['PROPERTY_226'] > 0) $xml->writeElement("KitchenSpace",number_format($aRes['PROPERTY_226'],2,".",""));
		if ($aRes['PROPERTY_225'] > 0) $xml->writeElement("LivingSpace",number_format($aRes['PROPERTY_225'],2,".",""));
	}
  if ($aRes['PROPERTY_210']==382 || $aRes['PROPERTY_210']==381) $xml->writeElement("HouseType", $housetype[$aRes['PROPERTY_243']]);
  if ($aRes['PROPERTY_210']==382 || $aRes['PROPERTY_210']==381) $xml->writeElement("Floor", $aRes['PROPERTY_221']);
  if ($aRes['PROPERTY_210']!=386) $xml->writeElement("Floors", $aRes['PROPERTY_222']);
  if ($aRes['PROPERTY_210']==383 || $aRes['PROPERTY_210']==384 || $aRes['PROPERTY_210']==385) $xml->writeElement("WallsType", $walls[$aRes['PROPERTY_242']]);
	$xml->writeElement("PropertyRights","Собственник");
  switch($aRes['PROPERTY_210']){
    case 383:
      $xml->writeElement("ObjectType", "Дом");
      break;
    case 384:
      $xml->writeElement("ObjectType", "Таунхаус");
      break;
    case 385:
      $xml->writeElement("ObjectType", "Дача");
      break;
    case 386:
      $xml->writeElement("ObjectType", $plotcat[$aRes['PROPERTY_295']]);
      break;
    case 387:
      $xml->writeElement("ObjectType", $appointment[$aRes['PROPERTY_238']]);
      break;
  }
	if ($aRes['PROPERTY_212'] != "") $xml->writeElement("CadastralNumber", $aRes['PROPERTY_212']);
  switch($aRes['PROPERTY_210']){
    case 383:
    case 384:
    case 385:
    case 386:
      $xml->writeElement("DistanceToCity", intval($aRes['PROPERTY_375']));
      break;
  }
  $xml->writeElement("Price", $aRes['UF_CRM_58958B5734602']);
  $xml->writeElement("Description", html_entity_decode($aRes['COMMENTS'])." Номер заявки в базе ЕЦН: ".$aRes['ID'].". При обращении в компанию назовите этот номер сотруднику, это поможет быстрее обработать Ваш запрос.");//Номер в базе - новый ID
  if ($aRes['PROPERTY_210']==382) $xml->writeElement("MarketType", ($aRes['PROPERTY_258']=="")? "Вторичка":"Новостройка");
  if ($aRes['PROPERTY_258']!="") $xml->writeElement("NewDevelopmentId",substr($aRes['PROPERTY_258'],0,strpos($aRes['PROPERTY_258']," ")));

  $imageLink = array();
	foreach (unserialize($aRes['UF_CRM_1472038962']) as $imageid){
		if (CFile::GetPath($imageid))	$imageLink[] = "https://bpm.ucre.ru".CFile::GetPath($imageid);
	}
	foreach (unserialize($aRes['UF_CRM_1476517423']) as $imageid){
		if (CFile::GetPath($imageid)) $imageLink[] = "https://bpm.ucre.ru".CFile::GetPath($imageid);
	}
	$max = (count($imageLink) > 20)?20:count($imageLink);
  $xml->startElement("Images");//Images
	for ($i=0;$i<$max;$i++){
		$xml->startElement("Image");//Image		
		$xml->writeAttribute("url", $imageLink[$i]);
		$xml->endElement();//Image		
	}
  $xml->endElement();//Images
  $xml->writeElement("CompanyName","Единый центр недвижимости");
	$xml->writeElement("AllowEmail","Нет");
  if ($aRes['PROPERTY_374'] == 1356){
    $rsUser = CUser::GetByID($aRes['PROPERTY_313']);
    $arUser = $rsUser->Fetch();
    $xml->writeElement("ManagerName",$arUser['LAST_NAME']." ".$arUser['NAME']." ".$arUser['SECOND_NAME']);
    $xml->writeElement("EMail", $arUser['EMAIL']);
    $xml->writeElement("ContactPhone", $arUser['PERSONAL_PHONE']);
  }
  if ($aRes['PROPERTY_374'] == 1357){
    $xml->writeElement("ManagerName", "Менеджер по работе с клиентами");
    $xml->writeElement("EMail", 'info@ucre.ru');
    $xml->writeElement("ContactPhone", "+7 (932) 536-06-57");
  }
  $xml->endElement();//Ad
  $num++;
}
$xml->endElement();//Ads
$xml->endDocument();//Закрываем документ
$xml->flush(); 
$time = microtime(true) - $start;
CEventLog::Add(array(
  "SEVERITY" => "SECURITY",
  "AUDIT_TYPE_ID" => "AVT_EXPORT",
  "MODULE_ID" => "main",
  "ITEM_ID" => 'Каталог недвижимости',
  "DESCRIPTION" => "Результат записи фида: ".$result."<br>Выгрузка скриптом объектов недвижимости в формате АВИТО, выгружено ".$num." объектов за ".$time." секунд (включая комнат - ".$r.", квартир - ".$f.", домов, дач, коттеджей - ".$h.", участков - ".$p.", коммерческих - ".$c.").",
));
echo "Выгрузка скриптом объектов недвижимости в формате АВИТО, выгружено ".$num." объектов за ".$time." секунд (включая комнат - ".$r.", квартир - ".$f.", домов, дач, коттеджей - ".$h.", участков - ".$p.", коммерческих - ".$c.").";
//echo "<pre>";print_r($cityCoords);echo "</pre>";
require ($_SERVER['DOCUMENT_ROOT'] . "/bitrix/modules/main/include/epilog_after.php");      
?>