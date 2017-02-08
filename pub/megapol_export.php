<?php
require ($_SERVER['DOCUMENT_ROOT'] . "/bitrix/modules/main/include/prolog_before.php");
$start = microtime(true);//Засекаем время выполнения скрипта
$num = 0;
$f =0;
$r = 0;
$h = 0;
$d = 0;
$p = 0;
$c = 0;
$dom = new domDocument("1.0", "utf-8"); // Создаём XML-документ версии 1.0 с кодировкой utf-8
$Agency = $dom->createElement("Agency"); // Создаём корневой элемент
$Agency->setAttribute("agencyId",154);//Добавляем элементу свойство
$dom->appendChild($Agency);//Присоединяем его к документу
$Objects = $dom->createElement("Objects");//Создаем вложенный элемент
$Agency->appendChild($Objects);//Присоединяем его к корневому
if(CModule::IncludeModule('iblock') && CModule::IncludeModule("crm")) {
  $arSelect = Array("ID", "IBLOCK_ID", "CODE", "NAME","CREATED_BY","MODIFIED_BY","DATE_CREATE","TIMESTAMP_X", "DATE_ACTIVE_FROM","DETAIL_TEXT","PROPERTY_*");
  $iblock_filter = array ("IBLOCK_ID" => 42, /*"ACTIVE"=>"Y"*/"PROPERTY_266" => array("Активная стадия","Активный","Свободный"));
  $db_res = CIBlockElement::GetList(array("ID"=>"ASC"), $iblock_filter, false, false, $arSelect);
  while($ob = $db_res->GetNextElement()){
    $aRes = $ob->GetFields();
    $arProps = $ob->GetProperties();
    $Object = $dom->createElement("Object"); // Создаём узел "Object"
    $Id = $dom->createElement("Id", ($aRes['CODE'])? $aRes['CODE']: $aRes['ID']); // Создаём узел "Id" с текстом внутри
    $Object->appendChild($Id); // Добавляем в узел "Object" узел "Id"
    $Operation = $dom->createElement("Operation",$aRes['PROPERTY_300']);
    $Object->appendChild($Operation);// Добавляем в узел "Object" узел "Operation"
    switch ($aRes['PROPERTY_210']){
      case 381:
        $Type = $dom->createElement("Type","Комнаты");
        $Object->appendChild($Type);// Добавляем в узел "Object" узел "Type"
        $r++;
        break;
      case 382:
        $Type = $dom->createElement("Type","Квартиру");
        $Object->appendChild($Type);// Добавляем в узел "Object" узел "Type"
        $f++;
        break;
      case 383:
      case 384:
        $Type = $dom->createElement("Type","Дома, коттеджи");
        $Object->appendChild($Type);// Добавляем в узел "Object" узел "Type"
        $h++;
        break;
      case 385:
        $Type = $dom->createElement("Type","Дачи");
        $Object->appendChild($Type);// Добавляем в узел "Object" узел "Type"
        $d++;
        break;
      case 386:
        $Type = $dom->createElement("Type","Участки");
        $Object->appendChild($Type);// Добавляем в узел "Object" узел "Type"
        $p++;
        break;
      case 387:
        $Type = $dom->createElement("Type","Коммерческая недвижимость");
        $Object->appendChild($Type);// Добавляем в узел "Object" узел "Type"
        $c++;
        break;
        
    }
    $Region = $dom->createElement("Region",$aRes['PROPERTY_213']);
    $Object->appendChild($Region);
    $District = $dom->createElement("District",$aRes['PROPERTY_214']);
    $Object->appendChild($District);
    $City = $dom->createElement("City",$aRes['PROPERTY_215']);
    $Object->appendChild($City);
    $Loc = array("Ленинский", "Промышленный", "Центральный", "Дзержинский", "отсутствует","");
    if (!in_array($aRes['PROPERTY_216'], $Loc)){
      $Locality = $dom->createElement("Locality", $aRes['PROPERTY_216']);
      $Object->appendChild($Locality);
    }
		/*-Не подменяем название улицы для выгрузки на Мегаполис (клиентов пугает снт в адресе)-*/
		if (strripos($aRes['PROPERTY_217'],"|")){
			$arr_street = explode("|",$aRes['PROPERTY_217']);
			$street = $arr_street[0];
		}else{
			$street = $aRes['PROPERTY_217'];
		}
		/*--------------------------------------------------------------------*/

    $Street = $dom->createElement("Street",$street);
    $Object->appendChild($Street);
    $House = $dom->createElement("House",$aRes['PROPERTY_218']);
    $Object->appendChild($House);
    $Direction = $dom->createElement("Direction","");
    $Object->appendChild($Direction);
    $ToMKAD = $dom->createElement("ToMKAD","0");
    $Object->appendChild($ToMKAD);
    If ($aRes['PROPERTY_210']==387){
      $Appoinment = $dom->createElement("Appointment",$arProps['APPOINTMENT']['VALUE']);
      $Object->appendChild($Appoinment);
    }
    If ($aRes['PROPERTY_210']==385 || $aRes['PROPERTY_210']==386){
	  	$SNTname = $dom->createElement("SNTname",$arProps['SNT']['VALUE']);
	  	$Object->appendChild($SNTname);
	  }
    if ($aRes['PROPERTY_210']==383|| $aRes['PROPERTY_210']==384) {
	  	$CountruHouseType = $dom->createElement("CountryHouseType", mb_convert_case($arProps['TYPE']['VALUE'], MB_CASE_TITLE));
	  	$Object->appendChild($CountruHouseType);
	  }
    if ($aRes['PROPERTY_210']==383 || $aRes['PROPERTY_210']==384 || $aRes['PROPERTY_210']==385){
	  	$Wall = $dom->createElement("Wall", $arProps['WALLS']['VALUE']);
	  	$Object->appendChild($Wall);
	  	$AreaHouse = $dom->createElement("AreaHouse", $arProps['TOTAL_AREA']['VALUE']);
	  	$Object->appendChild($AreaHouse);
	  }
    if ($aRes['PROPERTY_210']==383 || $aRes['PROPERTY_210']==384 || $aRes['PROPERTY_210']==385 || $aRes['PROPERTY_210']==386) {
	  	$AreaPlot = $dom->createElement("AreaPlot", $arProps['PLOT_AREA']['VALUE']);
	  	$Object->appendChild($AreaPlot);
	  	$Water = $dom->createElement("Water", $arProps['WATER']['VALUE']);
	  	$Object->appendChild($Water);
			if ($aRes['PROPERTY_210']==383 || $aRes['PROPERTY_210']==384){
				$Gas = $dom->createElement("Gas", ($arProps['GAS']['VALUE']=="")?"Магистральный":$arProps['GAS']['VALUE']);
				$Object->appendChild($Gas);
			}
			if ($aRes['PROPERTY_210']==385 || $aRes['PROPERTY_210']==386){
				$Gas = $dom->createElement("Gas", ($arProps['GAS']['VALUE']=="")?"Нет":$arProps['GAS']['VALUE']);
				$Object->appendChild($Gas);
			}
			
	  	$Electricity = $dom->createElement("Electricity", $arProps['ELECTRICITY']['VALUE']);
	  	$Object->appendChild($Electricity);
	  	$Sewage = $dom->createElement("Sewage", $arProps['SEWAGE']['VALUE']);
	  	$Object->appendChild($Sewage);
	  	$Heating = $dom->createElement("Heating", $arProps['HEATING']['VALUE']);
	  	$Object->appendChild($Heating);
	  }
    if ($aRes['PROPERTY_210']==381 || $aRes['PROPERTY_210']==382){
	  	$Rooms = $dom->createElement("Rooms", $arProps['ROOMS']['VALUE']);
	  	$Object->appendChild($Rooms);
	  	$AreaTotal = $dom->createElement("AreaTotal",$arProps['TOTAL_AREA']['VALUE']);
	  	$Object->appendChild($AreaTotal);
	  	$AreaLiving = $dom->createElement("AreaLiving", $arProps['LIVE_AREA']['VALUE']);
	  	$Object->appendChild($AreaLiving);
	  	$AreaKitchen = $dom->createElement("AreaKitchen", $arProps['KITCHEN_AREA']['VALUE']);
	  	$Object->appendChild($AreaKitchen);
	  }
    If ($aRes['PROPERTY_210']==387){
      $AreaMin = $dom->createElement("AreaMin", $arProps['TOTAL_AREA']['VALUE']);
      $Object->appendChild($AreaMin);
      $AreaMax = $dom->createElement("AreaMax", $arProps['TOTAL_AREA']['VALUE']);
      $Object->appendChild($AreaMax);
    }
    if ($aRes['PROPERTY_210']==381){
      $SaleRooms = $dom->createElement("SaleRooms", 1);
      $Object->appendChild($SaleRooms);
	  }
    if ($aRes['PROPERTY_210']==381 || $aRes['PROPERTY_210']==382 || $aRes['PROPERTY_210']==383 || $aRes['PROPERTY_210']==384 || $aRes['PROPERTY_210']==385){
      $FloorAll = $dom->createElement("FloorAll", $arProps['FLOORALL']['VALUE']);
      $Object->appendChild($FloorAll);
      $Floor = $dom->createElement("Floor", $arProps['FLOOR']['VALUE']);
      $Object->appendChild($Floor);
      $HouseType = $dom->createElement("HouseType", $arProps['HOUSE_TYPE']['VALUE']);
      $Object->appendChild($HouseType);	 
			
      $BalconyType = $dom->createElement("BalconyType", ($arProps['BALKON_TYPE']['VALUE']=="")?"Нет":$arProps['BALKON_TYPE']['VALUE']);
      $Object->appendChild($BalconyType);
			
      $WC =$dom->createElement("WC", $arProps['WC']['VALUE']);
      $Object->appendChild($WC);
			$Window = $dom->createElement("Window", $arProps['WINDOWS_DIR']['VALUE']);
			$Object->appendChild($Window);
			$Elevator = $dom->createElement("Elevator", $arProps['ELEVATOR']['VALUE']);
			$Object->appendChild($Elevator);
			$Phone = $dom->createElement("Phone", ($arProps['PHONE']['VALUE']=="")?"Нет":$arProps['PHONE']['VALUE']);
			$Object->appendChild($Phone);
			if ($aRes['PROPERTY_210']==381 || $aRes['PROPERTY_210']==382){
				$DirectSale = $dom->createElement("DirectSale","да");
			$Object->appendChild($DirectSale);
			}
			
			$State = $dom->createElement("State","свободна");
			$Object->appendChild($State);
			$Condition = $dom->createElement("Condition", "хорошее состояние");
			$Object->appendChild($Condition);
			$Mortgage = $dom->createElement("Mortgage","Да");
			$Object->appendChild($Mortgage);
		}
		$Guard = $dom->createElement("Guard", ($arProps['GUARD']['VALUE']=="")?"Нет":$arProps['GUARD']['VALUE']);
	  $Object->appendChild($Guard);
		If ($aRes['PROPERTY_210']==387) {
	  	$Parking = $dom->createElement("Parking", ($arProps['PARKING']['VALUE']=="")?"Нет":$arProps['PARKING']['VALUE']);
	  	$Object->appendChild($Parking);
	  }
		$DistType = $dom->createElement("DistType", "неизвестно");
	  $Object->appendChild($DistType);
	  $DistMetro = $dom->createElement("DistMetro", "1");
	  $Object->appendChild($DistMetro);
		
		$dealFilter = array("ID" => $arProps['ID_DEAL']['VALUE'],"CHECK_PERMISSIONS" => "N");
    $dealSelect = array("ID","COMMENTS","UF_CRM_1469533039","UF_CRM_579897C010103","UF_CRM_1472038962","UF_CRM_1476517423");
    $deal_res = CCrmDeal::GetList(Array('DATE_CREATE' => 'DESC'), $dealFilter, $dealSelect);
    $deal = $deal_res->GetNext();
		$rsContract = CUserFieldEnum::GetList(array(), array("ID" => $deal['UF_CRM_1469533039'],));
		$arContract = $rsContract->GetNext();
		
		$Relationship = $dom->createElement("Relationship", ($arContract['VALUE']==""||$arContract['VALUE']=="Без договора")?"Без договора":"Договор");
		$Object->appendChild($Relationship);	  
	  $PMG = $dom->createElement("PMG", "Да");
	  $Object->appendChild($PMG);
		$Price = $dom->createElement("Price",$deal['UF_CRM_58958B5734602']);//UF_CRM_58958B5734602 - новая //UF_CRM_579897C010103 - старая
		$Object->appendChild($Price);
		$Elite = $dom->createElement("Elite", "0");
	  $Object->appendChild($Elite);
	  $Description = $dom->createElement("Description", html_entity_decode($deal['COMMENTS']/*$aRes['DETAIL_TEXT']*/)." Номер в базе: ".$aRes['ID'].", <a href='".$arProps['LINK']['VALUE']."'>ссылка на сайт.</a>");
	  $Object->appendChild($Description);
	  $Reward = $dom->createElement("Reward", "0");
	  $Object->appendChild($Reward);
		
		$rsUser = CUser::GetByID($arProps['ASSIGNED_BY']['VALUE']);
		$arUser = $rsUser->Fetch();
		$Agent = $dom->createElement("Agent",$arUser['LAST_NAME']." ".$arUser['NAME']." ".$arUser['SECOND_NAME']);
	  $Object->appendChild($Agent);
		$Latitude = $dom->createElement("Latitude",($arProps['LATITUDE']['VALUE'])?$arProps['LATITUDE']['VALUE']:"51.7727000");
	  $Object->appendChild($Latitude);
		$Longtitude = $dom->createElement("Longtitude",($arProps['LONGITUDE']['VALUE'])?$arProps['LONGITUDE']['VALUE']:"55.0988000");
		$Object->appendChild($Longtitude);
		
		foreach ($deal['UF_CRM_1472038962'] as $imageid){
			$Image = $dom->createElement("Image","http://bpm.ucre.ru/".CFile::GetPath($imageid));
			$Object->appendChild($Image);
		}
		foreach ($deal['UF_CRM_1476517423'] as $imageid){
			$Image = $dom->createElement("Image","http://bpm.ucre.ru/".CFile::GetPath($imageid));
			$Object->appendChild($Image);
		}
		
		$Objects->appendChild($Object); // Добавляем в корневой узел "Objects" узел "Object"
		$num++;
	}
	$dom->save("/home/bitrix/www_bpm/orenburg_v3.xml"); // Сохраняем полученный XML-документ в файл
	$time = microtime(true) - $start;
  CEventLog::Add(array(
    "SEVERITY" => "SECURITY",
    "AUDIT_TYPE_ID" => "MGP_EXPORT",
    "MODULE_ID" => "main",
    "ITEM_ID" => 'Каталог недвижимости',
    "DESCRIPTION" => "Выгрузка объектов недвижимости в формате Мегаполис-Сервис, выгружено ".$num." объектов за ".$time." секунд.( (включая комнат - ".$r.", квартир - ".$f.", домов, таунхаусов - ".$h.", дач - ".$d.", участков - ".$p.", коммерческих - ".$c.").)",
  ));
}
echo "Выгрузка объектов недвижимости в формате Мегаполис-Сервис, выгружено ".$num." объектов за ".$time." секунд.(включая комнат - ".$r.", квартир - ".$f.", домов, таунхаусов - ".$h.", дач - ".$d.", участков - ".$p.", коммерческих - ".$c.").";			
require ($_SERVER['DOCUMENT_ROOT'] . "/bitrix/modules/main/include/epilog_after.php");      
?>