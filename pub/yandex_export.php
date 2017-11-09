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
//------Наполняем справочник категрий земель
$lotType = array(
	'530' => 'ИЖС',
	'531' => 'садоводство',
	'532' => 'промышленность'
);
//------Наполнили справочник категорий земель
$dom = new domDocument("1.0", "utf-8");

$realty_feed = $dom->createElement("realty-feed"); // Создаём корневой элемент
$realty_feed->setAttribute("xmlns","http://webmaster.yandex.ru/schemas/feed/realty/2010-06");//Добавляем элементу свойство
$generation_date = $dom->createElement("generation-date",date("c"));
$realty_feed->appendChild($generation_date);
$dom->appendChild($realty_feed);//Присоединяем его к документу

$db_res = $DB->Query("select b_crm_deal.ID, b_crm_deal.COMMENTS, b_crm_deal.DATE_CREATE, b_crm_deal.DATE_MODIFY, b_uts_crm_deal.UF_CRM_58958B5734602, b_uts_crm_deal.UF_CRM_1472038962, b_uts_crm_deal.UF_CRM_1476517423,b_iblock_element.ID as ELEMENT_ID, b_iblock_element.CODE, b_iblock_element_prop_s42.PROPERTY_210, b_iblock_element_prop_s42.PROPERTY_300, b_iblock_element_prop_s42.PROPERTY_213, b_iblock_element_prop_s42.PROPERTY_214, b_iblock_element_prop_s42.PROPERTY_215,b_iblock_element_prop_s42.PROPERTY_216,b_iblock_element_prop_s42.PROPERTY_217,b_iblock_element_prop_s42.PROPERTY_218, b_iblock_element_prop_s42.PROPERTY_272, b_iblock_element_prop_s42.PROPERTY_298, b_iblock_element_prop_s42.PROPERTY_299, b_iblock_element_prop_s42.PROPERTY_301, b_iblock_element_prop_s42.PROPERTY_229, b_iblock_element_prop_s42.PROPERTY_228, b_iblock_element_prop_s42.PROPERTY_224, b_iblock_element_prop_s42.PROPERTY_292, b_iblock_element_prop_s42.PROPERTY_225, b_iblock_element_prop_s42.PROPERTY_226, b_iblock_element_prop_s42.PROPERTY_221, b_iblock_element_prop_s42.PROPERTY_222, b_iblock_element_prop_s42.PROPERTY_241, b_iblock_element_prop_s42.PROPERTY_242, b_iblock_element_prop_s42.PROPERTY_243, b_iblock_element_prop_s42.PROPERTY_238, b_iblock_element_prop_s42.PROPERTY_295, b_iblock_element_prop_s42.PROPERTY_374, b_iblock_element_prop_s42.PROPERTY_258, b_iblock_element_prop_s42.PROPERTY_313, b_iblock_element_prop_s42.PROPERTY_375 from b_crm_deal LEFT JOIN b_uts_crm_deal ON b_crm_deal.ID = b_uts_crm_deal.VALUE_ID LEFT JOIN b_iblock_element ON b_uts_crm_deal.UF_CRM_1469534140 = b_iblock_element.ID LEFT JOIN b_iblock_element_prop_s42 ON b_uts_crm_deal.UF_CRM_1469534140 = b_iblock_element_prop_s42.IBLOCK_ELEMENT_ID where b_crm_deal.CATEGORY_ID = 0 and b_uts_crm_deal.UF_CRM_1469534140 <> '' and b_crm_deal.STAGE_ID = 'PROPOSAL' AND (b_uts_crm_deal.UF_CRM_1472038962<>'a:0:{}' OR b_uts_crm_deal.UF_CRM_1476517423 <> 'a:0:{}') AND b_uts_crm_deal.UF_CRM_58958B5734602>0 AND b_iblock_element_prop_s42.PROPERTY_210 <> 387 ORDER BY b_crm_deal.DATE_MODIFY DESC");

while($aRes = $db_res->Fetch()){
  $offer = $dom->createElement("offer");
  $offer->setAttribute("internal-id","Y".$aRes['ID']);
  $type = $dom->createElement("type","Продажа");
  $offer->appendChild($type);
  $property_type = $dom->createElement("property-type","жилая");
  $offer->appendChild($property_type);
  switch ($aRes['PROPERTY_210']){
    case 381://Комнаты
      $category = $dom->createElement("category","комната");
      $offer->appendChild($category);
      $area = $dom->createElement("area");
      $value = $dom->createElement("value",number_format($aRes['PROPERTY_228'],2,".",""));//228 - площадь команты, 224 - общая площадь
      $area->appendChild($value);
      $unit = $dom->createElement("unit","кв. м");
      $area->appendChild($unit);
      $offer->appendChild($area);
      if ($aRes['PROPERTY_229']>0){
        $rooms = $dom->createElement("rooms",intval($aRes['PROPERTY_229']));
        $offer->appendChild($rooms);
      }
      $rooms_offered = $dom->createElement("rooms-offered",1);
      $offer->appendChild($rooms_offered);
      

      $floor = $dom->createElement("floor",$aRes['PROPERTY_221']);
      $offer->appendChild($floor);
      $floors_total = $dom->createElement("floors-total", $aRes['PROPERTY_222']);
      $offer->appendChild($floors_total);
      
      $property_enums = CIBlockPropertyEnum::GetList(array(), array("IBLOCK_ID"=>42, "CODE"=>"HOUSE_TYPE","ID" => $aRes['PROPERTY_243']));
      $enum_fields = $property_enums->GetNext();
      if ($enum_fields["VALUE"]){
        $building_type = $dom->createElement("building-type", $enum_fields["VALUE"]);
        $offer->appendChild($building_type);
      }
      
      $r++;
      break;
    case 382:
      $category = $dom->createElement("category","квартира");
      $offer->appendChild($category);
      $rooms = $dom->createElement("rooms",intval($aRes['PROPERTY_229']));
      $offer->appendChild($rooms);
      $area = $dom->createElement("area");
      $value = $dom->createElement("value",number_format($aRes['PROPERTY_224'],2,".",""));
      $area->appendChild($value);
      $unit = $dom->createElement("unit","кв. м");
      $area->appendChild($unit);
      $offer->appendChild($area);
      if($aRes['PROPERTY_225']>0){
        $living_space = $dom->createElement("living-space");
        $value = $dom->createElement("value",number_format($aRes['PROPERTY_225'],2,".",""));
        $living_space->appendChild($value);
        $unit = $dom->createElement("unit","кв. м");
        $living_space->appendChild($unit);
        $offer->appendChild($living_space);
      }
      if($aRes['PROPERTY_226']>0){
        $kitchen_space = $dom->createElement("kitchen-space");
        $value = $dom->createElement("value",number_format($aRes['PROPERTY_226'],2,".",""));
        $kitchen_space->appendChild($value);
        $unit = $dom->createElement("unit","кв. м");
        $kitchen_space->appendChild($unit);
        $offer->appendChild($kitchen_space);
      }
      $floor = $dom->createElement("floor",$aRes['PROPERTY_221']);
      $offer->appendChild($floor);
      $floors_total = $dom->createElement("floors-total", $aRes['PROPERTY_222']);
      $offer->appendChild($floors_total);
      
      $property_enums = CIBlockPropertyEnum::GetList(array(), array("IBLOCK_ID"=>42, "CODE"=>"HOUSE_TYPE","ID" => $aRes['PROPERTY_243']));
      $enum_fields = $property_enums->GetNext();
      if ($enum_fields["VALUE"]){
        $building_type = $dom->createElement("building-type", $enum_fields["VALUE"]);
        $offer->appendChild($building_type);
      }
      
      $property_enums = CIBlockPropertyEnum::GetList(array(), array("IBLOCK_ID"=>42, "CODE"=>"BALKON_TYPE","ID" => $aRes['PROPERTY_241']));
      $enum_fields = $property_enums->GetNext();
      if ($enum_fields["VALUE"]){
        $balkony = $dom->createElement("balcony", $enum_fields["VALUE"]);
        $offer->appendChild($balkony);
      }
      $property_enums = CIBlockPropertyEnum::GetList(array(), array("IBLOCK_ID"=>42, "CODE"=>"WC","ID" => $aRes['PROPERTY_272']));
      $enum_fields = $property_enums->GetNext();
      if ($enum_fields["VALUE"]){
        $bathroom_unit = $dom->createElement("bathroom-unit", $enum_fields["VALUE"]);
        $offer->appendChild( $bathroom_unit);
      }
      $f++;
      break;
    case 383:
      $category = $dom->createElement("category","дом с участком");
      $offer->appendChild($category);
      $area = $dom->createElement("area");
      $value = $dom->createElement("value",number_format($aRes['PROPERTY_224'],2,".",""));
      $area->appendChild($value);
      $unit = $dom->createElement("unit","кв. м");
      $area->appendChild($unit);
      $offer->appendChild($area);
      $lot_area = $dom->createElement("lot-area");
      $value = $dom->createElement("value",number_format($aRes['PROPERTY_292'],2,".",""));
      $lot_area->appendChild($value);
      $unit = $dom->createElement("unit","сотка");
      $lot_area->appendChild($unit);
      $offer->appendChild($lot_area);
      $floors_total = $dom->createElement("floors-total", $aRes['PROPERTY_222']);
      $offer->appendChild($floors_total);
      $property_enums = CIBlockPropertyEnum::GetList(array(), array("IBLOCK_ID"=>42, "CODE"=>"WALLS","ID" => $aRes['PROPERTY_242']));
      $enum_fields = $property_enums->GetNext();
      if ($enum_fields["VALUE"]){
        $building_type = $dom->createElement("building-type", $enum_fields["VALUE"]);
        $offer->appendChild($building_type);
      }
      $h++;
      break;
    case 384:
      $category = $dom->createElement("category","таунхаус");
      $offer->appendChild($category);
      $area = $dom->createElement("area");
      $value = $dom->createElement("value",number_format($aRes['PROPERTY_224'],2,".",""));
      $area->appendChild($value);
      $unit = $dom->createElement("unit","кв. м");
      $area->appendChild($unit);
      $offer->appendChild($area);
      $lot_area = $dom->createElement("lot-area");
      $value = $dom->createElement("value",number_format($aRes['PROPERTY_292'],2,".",""));
      $lot_area->appendChild($value);
      $unit = $dom->createElement("unit","сотка");
      $lot_area->appendChild($unit);
      $offer->appendChild($lot_area);
      $floors_total = $dom->createElement("floors-total", $aRes['PROPERTY_222']);
      $offer->appendChild($floors_total);
      $property_enums = CIBlockPropertyEnum::GetList(array(), array("IBLOCK_ID"=>42, "CODE"=>"WALLS","ID" => $aRes['PROPERTY_242']));
      $enum_fields = $property_enums->GetNext();
      if ($enum_fields["VALUE"]){
        $building_type = $dom->createElement("building-type", $enum_fields["VALUE"]);
        $offer->appendChild($building_type);
      }
      $t++;
      break;
    case 385:
      $category = $dom->createElement("category","дача");
      $offer->appendChild($category);
      $area = $dom->createElement("area");
      $value = $dom->createElement("value",number_format($aRes['PROPERTY_224'],2,".",""));
      $area->appendChild($value);
      $unit = $dom->createElement("unit","кв. м");
      $area->appendChild($unit);
      $offer->appendChild($area);
      $lot_area = $dom->createElement("lot-area");
      $value = $dom->createElement("value",number_format($aRes['PROPERTY_292'],2,".",""));
      $lot_area->appendChild($value);
      $unit = $dom->createElement("unit","сотка");
      $lot_area->appendChild($unit);
      $offer->appendChild($lot_area);
      $floors_total = $dom->createElement("floors-total", $aRes['PROPERTY_222']);
      $offer->appendChild($floors_total);
      $property_enums = CIBlockPropertyEnum::GetList(array(), array("IBLOCK_ID"=>42, "CODE"=>"WALLS","ID" => $aRes['PROPERTY_242']));
      $enum_fields = $property_enums->GetNext();
      if ($enum_fields["VALUE"]){
        $building_type = $dom->createElement("building-type", $enum_fields["VALUE"]);
        $offer->appendChild($building_type);
      }
      $d++;
      break;
    case 386:
      $category = $dom->createElement("category","участок");
      $offer->appendChild($category);
      $lot_area = $dom->createElement("lot-area");
      $value = $dom->createElement("value",number_format($aRes['PROPERTY_292'],2,".",""));
      $lot_area->appendChild($value);
      $unit = $dom->createElement("unit","сотка");
      $lot_area->appendChild($unit);
      $offer->appendChild($lot_area);
      $lot_type = $dom->createElement("lot-type",$lotType[$aRes['PROPERTY_295']]);
      $offer->appendChild($lot_type);
      $p++;
      break;
  }
  if ($aRes['PROPERTY_301']){
    $url = $dom->createElement("url",$aRes['PROPERTY_301']);
    $offer->appendChild($url);
  }
  $creation_date = $dom->createElement("creation-date",date("c",strtotime($aRes['DATE_CREATE'])));
  $offer->appendChild($creation_date);
  $last_update_date	 = $dom->createElement("last-update-date",date("c",strtotime($aRes['DATE_MODIFY'])));
  $offer->appendChild($last_update_date);
  $expire_date = $dom->createElement("expire-date", date("c",strtotime("+1 day")));
  $offer->appendChild($expire_date);
  /*------------------------Адрес объекта------------------------*/
  $location = $dom->createElement("location");
  $country = $dom->createElement("country","Россия");
  $location->appendChild($country);
  $region = $dom->createElement("region", $aRes['PROPERTY_213']);
  $location->appendChild($region);
  if ($aRes['PROPERTY_214'] != "обл. подчинения"){
    $district = $dom->createElement("district",$aRes['PROPERTY_214']);
    $location->appendChild($district);
  }
  $locality_name = $dom->createElement("locality-name",$aRes['PROPERTY_215']);
  $location->appendChild($locality_name);
  if ($aRes['PROPERTY_216'] != "отсутствует" && $aRes['PROPERTY_216'] != ""){
    $sub_locality_name = $dom->createElement("sub-locality-name", $aRes['PROPERTY_216']);
    $location->appendChild($sub_locality_name);
  }
  switch ($aRes['PROPERTY_210']){
    case 381:
    case 382:
      $address = $dom->createElement("address", $aRes['PROPERTY_217'].", ".$aRes['PROPERTY_218']);
      break;
    case 383:
    case 384:
    case 385:
    case 386:
      $address = $dom->createElement("address", $aRes['PROPERTY_217']);
      break;
  }
  $location->appendChild($address);
  if ($aRes['PROPERTY_298'] && $aRes['PROPERTY_299']){
    $latitude = $dom->createElement("latitude",$aRes['PROPERTY_298']);
    $location->appendChild($latitude);
    $longitude = $dom->createElement("longitude",$aRes['PROPERTY_299']);
    $location->appendChild($longitude);
  }
  
  $offer->appendChild($location);
  /*------------------------Адрес объекта------------------------*/
  
  if (count(unserialize($aRes['UF_CRM_1476517423']))){
		if (unserialize($aRes['UF_CRM_1476517423'])[0]){
		  $image = $dom->createElement("image","https://bpm.ucre.ru".CFile::GetPath(unserialize($aRes['UF_CRM_1476517423'])[0]));
			$offer->appendChild($image);
		}
	}
  
  if (count(unserialize($aRes['UF_CRM_1472038962']))){
		foreach (unserialize($aRes['UF_CRM_1472038962']) as $imageid){
      $image = $dom->createElement("image","https://bpm.ucre.ru".CFile::GetPath($imageid));
      $offer->appendChild($image);
		}
	}
  /*-------------------Информация о продавце--------------------*/
  $sales_agent = $dom->createElement("sales-agent");
  $name = $dom->createElement("name","Отдел по работе с покупателями");
  $sales_agent->appendChild($name);
  $phone = $dom->createElement("phone","+7 (922) 829-90-57");
  $sales_agent->appendChild($phone);
  $category = $dom->createElement("category","агентство");
  $sales_agent->appendChild($category);
  $organization = $dom->createElement("organization","Единый центр недвижимости");
  $sales_agent->appendChild($category);
  $url = $dom->createElement("url","http://ucre.ru");
  $sales_agent->appendChild($url);
  $email = $dom->createElement("email","yandex_realty@ucre.ru");
  $sales_agent->appendChild($email);
  $photo = $dom->createElement("photo","https://bpm.ucre.ru/include/logo.8601.jpg");
  $sales_agent->appendChild($photo);
  $offer->appendChild($sales_agent);
  /*-------------------Информация о продавце--------------------*/
  /*---------------Информация об условиях сделки----------------*/
  $price = $dom->createElement("price");
  $value = $dom->createElement("value",$aRes['UF_CRM_58958B5734602']);
  $price->appendChild($value);
  $currency = $dom->createElement("currency","RUR");
  $price->appendChild($currency);
  $offer->appendChild($price);
  /*---------------Информация об условиях сделки----------------*/
  $description = $dom->createElement("description",html_entity_decode($aRes['COMMENTS'])." Номер заявки в базе ЕЦН: ".$aRes['ID'].". При обращении в компанию назовите этот номер сотруднику, это поможет быстрее обработать Ваш запрос.");
  $offer->appendChild($description);
  $num++;
  $realty_feed->appendChild($offer);
}

$result = $dom->save("/home/bitrix/www_bpm/orenburg_yandex.xml"); // Сохраняем полученный XML-документ в файл
$time = microtime(true) - $start;
CEventLog::Add(array(
  "SEVERITY" => "SECURITY",
  "AUDIT_TYPE_ID" => "YANDEX_EXPORT",
  "MODULE_ID" => "main",
  "ITEM_ID" => 'Каталог недвижимости',
  "DESCRIPTION" => "Результат записи фида: ".$result."<br>Выгрузка скриптом объектов недвижимости в формате YANDEX, выгружено ".$num." объектов за ".$time." секунд (включая комнат - ".$r.", квартир - ".$f.", домов, дач - ".$h.", таунхаусов - ".$t.", участков - ".$p.").",
));
echo "Результат записи фида: ".$result."<br>Выгрузка скриптом объектов недвижимости в формате YANDEX, выгружено ".$num." объектов за ".$time." секунд (включая комнат - ".$r.", квартир - ".$f.", домов, дач - ".$h.", таунхаусов - ".$t.", участков - ".$p.").";
require ($_SERVER['DOCUMENT_ROOT'] . "/bitrix/modules/main/include/epilog_after.php");      
?>
