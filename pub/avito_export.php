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