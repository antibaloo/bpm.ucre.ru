<?php
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED!==true) die();
include($_SERVER['DOCUMENT_ROOT'] . '/include/map/map.php');
CModule::IncludeModule("crm");
use Bitrix\Main\UI\Extension;

Extension::load('ui.buttons');
Extension::load('ui.buttons.icons');
Extension::load('ui.forms');
$typeOfHousesAndWalls = array(
  '760'  => '426',//Блочный
  '757'  => '428',//Кирпичный
  '758'  => '429',//Монолитный
  '2011' => '430',//Монолитно-кипичный
  '756'  => '431',//Панельный
  '759'  => '427',//Деревянный
  '2046' => '413',//блок
  '2047' => '414',//бревно
  '2048' => '415',//брус
  '2049' => '416',//иное
  '2050' => '417',//каркасно-щитовой
  '2051' => '418',//кирпич
  '2052' => '419',//монолит
  '2053' => '421',//оцилиндрованное бревно
  '2054' => '422',//панели
  '2055' => '423',//пеноблок
  '2056' => '424',//сэндвич
  '2057' => '425'//шлакоблок
);
$template = "notype";

if (isset($arParams["PARAMS"])){//Вызов из формы
  $arResult['PARAMS'] = $arParams["PARAMS"];
}else{                          //Вызов из кода
  if (isset($arParams['ID'])){
    //Читаем параметры поиска из БД
    $rsDeal = CCrmDeal::GetListEx(
      array(), 
      array("ID" => $arParams['ID']), 
      false, 
      false, 
      array(
        "UF_CRM_58CFC7CDAAB96",     //Тип недвижимости
        "UF_CRM_5895BC940ED3F",     //Рынок поиска
        "UF_CRM_58958B529E628",     //Кол-во комнат
        "UF_CRM_58958B5207D0C",     //Тип дома
        "UF_CRM_58958B52BA439",     //Общая площадь не менее
        "UF_CRM_58958B52F2BAC",     //Площадь кухни не менее
        "UF_CRM_1506501917",        //Этаж от
        "UF_CRM_1506501950",        //Этаж до
        "UF_CRM_1521541289",        //Не последний
        "UF_CRM_1522901904",        //Этажность от
        "UF_CRM_1522901921",        //Этажность до
        "UF_CRM_58958B532A119",     //Есть балкон
        "UF_CRM_58958B576448C",     //Цена от
        "UF_CRM_58958B5751841",     //Цена до
        'UF_CRM_1522993078',        //Материал стен
      ),
      array()
    );
    $mainDeal = $rsDeal->Fetch();
    $arResult['PARAMS'] = $mainDeal;
    $arResult['PARAMS']['GEO_USE'] = "1"; //По-умолчанию включен учет области поиска
    //Область поиска
    $rsData = $DB->Query("select polygonArray from b_crm_search_polygon where dealId=".$arParams['ID']);
    if ($aData = $rsData->Fetch()) $arResult['PARAMS']['GEO'] = $aData['polygonArray'];
    else $arResult['PARAMS']['GEO'] = "";
  }else{
    $template = "error";
    $arResult['ERRORS'][] = "Не задан id заявки";
  }
}

if (isset($arParams['OFFER_AJAX_ID'])){
  $arResult['OFFER_AJAX_ID'] = $arParams['OFFER_AJAX_ID'];
}else{
  $template = "error"; $arResult['ERRORS'][] = "Не задан id блока обертки шаблона.";
}

$arResult['COMPONENT_PATH'] = $this->GetPath(); //Пусть к папке компонента для вызова ajax скриптов

switch ($arResult['PARAMS']['UF_CRM_58CFC7CDAAB96']){
  case '1':
    $template="room";
    break;
  case '2':
    $template="flat";
    break;
  case '3':
    $template="house";
    break;
  case '4':
    $template="town";
    break;
  case '5':
    $template="dacha";
    break;
  case '6':
    $template="plot";
    break;
  case '7':
    $template="comm";
    break;
  default:
    $template="notype";
    break;
}

if ($template != 'error' && $template != 'notype'){//Запускаем поиск по уже существующим параметрам
  $sqlString = 
    "SELECT b_crm_deal.ID,b_crm_deal.ID,b_crm_deal.CATEGORY_ID, b_crm_deal.TITLE, b_crm_deal.ASSIGNED_BY_ID, b_crm_deal.STAGE_ID,".// ID, катогория, заголовок, ответственный, стадия
    "b_uts_crm_deal.UF_CRM_58958B5734602,".    //Цена
    "b_uts_crm_deal.UF_CRM_58CFC7CDAAB96,".    //Тип недвижимости
    "b_iblock_element_prop_s42.PROPERTY_209,". //Адрес объекта недвижимости
    "b_uts_crm_deal.UF_CRM_1512621495,".       //Запрет на выгрузку
    "b_iblock_element.NAME,".                  //Название объекта
    "b_iblock_element_prop_s42.PROPERTY_229,". //Количество комнат
    "b_iblock_element_prop_s42.PROPERTY_224,". //Общая площадь
    "b_iblock_element_prop_s42.PROPERTY_226,". //Площадь кухни
    "b_iblock_element_prop_s42.PROPERTY_221,". //Этаж
    "b_iblock_element_prop_s42.PROPERTY_222,". //Этажность
    "b_iblock_element_prop_s42.PROPERTY_241,". //Тип балкона
    "b_iblock_element_prop_s42.PROPERTY_242,". //Материал стен
    "b_iblock_element_prop_s42.PROPERTY_243,". //Тип дома
    "b_iblock_element_prop_s42.PROPERTY_298,". //Широта
    "b_iblock_element_prop_s42.PROPERTY_299".  //Долгота
    " FROM b_crm_deal".                                                                                           //из таблицы заявок
    " INNER JOIN b_uts_crm_deal ON b_crm_deal.ID=b_uts_crm_deal.VALUE_ID".                                        //из таблицы пользовательских свойств заявок
    " INNER JOIN b_iblock_element ON b_uts_crm_deal.UF_CRM_1469534140 = b_iblock_element.ID".                     //из таблицы инфоблоков
    " INNER JOIN b_iblock_element_prop_s42 ON b_iblock_element.ID = b_iblock_element_prop_s42.IBLOCK_ELEMENT_ID". //из таблицы пользовательских свойств инфоблоков
    " WHERE b_crm_deal.STAGE_ID in ('10','PROPOSAL','1','13','C4:1','C4:PROPOSAL')".                              //в списоке подходящий статусов
    " AND UF_CRM_58CFC7CDAAB96 = '".$arResult['PARAMS']['UF_CRM_58CFC7CDAAB96']."'";                              //выбранного типа недвижимости
  
  if (count($arResult['PARAMS']['UF_CRM_5895BC940ED3F']) == 0 || count($arResult['PARAMS']['UF_CRM_5895BC940ED3F']) == 2)
    $sqlString .= " AND (b_crm_deal.CATEGORY_ID = 0 or b_crm_deal.CATEGORY_ID = 4)";
  else
    $sqlString .= ($arResult['PARAMS']['UF_CRM_5895BC940ED3F'][0] == '827')?" AND b_crm_deal.CATEGORY_ID = 0":" AND b_crm_deal.CATEGORY_ID = 4";
  
  //Диапазон цены
  if ($arResult['PARAMS']['UF_CRM_58958B576448C'] > 0) $sqlString .= " AND b_uts_crm_deal.UF_CRM_58958B5734602 >=".$arResult['PARAMS']['UF_CRM_58958B576448C'];
  if ($arResult['PARAMS']['UF_CRM_58958B5751841'] > 0) $sqlString .= " AND b_uts_crm_deal.UF_CRM_58958B5734602 <=".$arResult['PARAMS']['UF_CRM_58958B5751841'];
  
  //Кол-во комнат
  if ($arResult['PARAMS']['UF_CRM_58958B529E628'] > 0) $sqlString .= " AND b_iblock_element_prop_s42.PROPERTY_229 >=".$arResult['PARAMS']['UF_CRM_58958B529E628'];
  
  //Общая площадь
  if ($arResult['PARAMS']['UF_CRM_58958B52BA439'] > 0) $sqlString .= " AND b_iblock_element_prop_s42.PROPERTY_224 >=".$arResult['PARAMS']['UF_CRM_58958B52BA439'];
  
  //Площадь кухни
  if ($arResult['PARAMS']['UF_CRM_58958B52F2BAC'] > 0) $sqlString .= " AND b_iblock_element_prop_s42.PROPERTY_226 >=".$arResult['PARAMS']['UF_CRM_58958B52F2BAC'];
  
  //Этаж от
  if ($arResult['PARAMS']['UF_CRM_1506501917'] > 0) $sqlString .= " AND b_iblock_element_prop_s42.PROPERTY_221 >=".$arResult['PARAMS']['UF_CRM_1506501917'];
  
  //Этаж до
  if ($arResult['PARAMS']['UF_CRM_1506501950'] > 0) $sqlString .= " AND b_iblock_element_prop_s42.PROPERTY_221 <=".$arResult['PARAMS']['UF_CRM_1506501950'];
  
  //Этажность от
  if ($arResult['PARAMS']['UF_CRM_1522901904'] > 0) $sqlString .= " AND b_iblock_element_prop_s42.PROPERTY_222 >=".$arResult['PARAMS']['UF_CRM_1522901904'];
  
  //Этажность до
  if ($arResult['PARAMS']['UF_CRM_1522901921'] > 0) $sqlString .= " AND b_iblock_element_prop_s42.PROPERTY_222 <=".$arResult['PARAMS']['UF_CRM_1522901921'];
  
  //Не последний
  if ($arResult['PARAMS']['UF_CRM_1521541289'] === "1") $sqlString .= " AND b_iblock_element_prop_s42.PROPERTY_221 < b_iblock_element_prop_s42.PROPERTY_222";
  
  //Наличие балкона
  if ($arResult['PARAMS']['UF_CRM_58958B532A119'] === "1") $sqlString .= " AND b_iblock_element_prop_s42.PROPERTY_241 <> '' AND b_iblock_element_prop_s42.PROPERTY_241 <> 410";
  
  //Тип дома
  if ($arResult['PARAMS']['UF_CRM_58958B5207D0C'] != "") $sqlString .= " AND b_iblock_element_prop_s42.PROPERTY_243 = ".$typeOfHousesAndWalls[$arResult['PARAMS']['UF_CRM_58958B5207D0C']];
  
  //Материал стен
  if ($arResult['PARAMS']['UF_CRM_1522993078'] != "") $sqlString .= " AND b_iblock_element_prop_s42.PROPERTY_242 = ".$typeOfHousesAndWalls[$arResult['PARAMS']['UF_CRM_58958B5207D0C']];
  
  
  //Фильтр по уже имеющимся в потенциальных
  $rsPotentials = $DB->Query("select sell_deal_id from b_crm_potential_deals where buy_deal_id=".$arResult['PARAMS'] ['ID']);
  $arrayPotentials = array();
  while ($aPotentials = $rsPotentials->Fetch()){
    $arrayPotentials[] = $aPotentials['sell_deal_id'];
  }
  if (count($arrayPotentials)){
    $sqlString .= " AND b_crm_deal.ID NOT IN(".implode(",",$arrayPotentials).")";
  }
  
  
  
  $sqlString .= " ORDER BY DATE_MODIFY DESC";  //Сортировка
  
  
  //echo $sqlString;
  $rsData = $DB->Query($sqlString);
  
  /*--Проверка условий попадания в географическую область поиска--*/
  if ($arResult['PARAMS']['GEO']!=""){
    while ($aRes=$rsData->Fetch()){
      if ($arResult['PARAMS']['GEO_USE'] === "1"){ //Строго в области поиска
        if ($aRes['PROPERTY_298'] && $aRes['PROPERTY_299']){
          if (isInPoly(makePolyArray($arResult['PARAMS']['GEO']),array("lat" =>$aRes['PROPERTY_298'], "lon" => $aRes['PROPERTY_299']))) 
          {
            $arResult['GRID'][] = $aRes; //Забираем в результат
            $arResult['POINTS'][] = array("lat"=> $aRes['PROPERTY_298'], "lon" => $aRes['PROPERTY_299'], "name" => $aRes['NAME']);
          }
        }
      }else{                                  //Не строго в области поиска
        if ($aRes['PROPERTY_298'] && $aRes['PROPERTY_299']){
          if (isInPoly(makePolyArray($arResult['PARAMS']['GEO']),array("lat" =>$aRes['PROPERTY_298'], "lon" => $aRes['PROPERTY_299']))) 
          {
            $aRes['IN_GEO'] = "1";       //Метим
            $arResult['POINTS'][] = array("lat"=> $aRes['PROPERTY_298'], "lon" => $aRes['PROPERTY_299'], "name" => $aRes['NAME']);
          }else{
            $aRes['IN_GEO'] = "0";
            $arResult['OUT_POINTS'][] = array("lat"=> $aRes['PROPERTY_298'], "lon" => $aRes['PROPERTY_299'], "name" => $aRes['NAME']);
          }
          $arResult['GRID'][] = $aRes;
        }
      }
    }
  }else{
    while ($aRes=$rsData->Fetch()){
      if ($aRes['PROPERTY_298'] && $aRes['PROPERTY_299']){
        $arResult['GRID'][] = $aRes; //Забираем в результат
        $arResult['POINTS'][] = array("lat"=> $aRes['PROPERTY_298'], "lon" => $aRes['PROPERTY_299'], "name" => $aRes['NAME']);
      }
    }
  }
  $rsData->InitFromArray($arResult['GRID']);
  /*-------------------------------------------------------------*/
  
  
  
  //echo $rsData->SelectedRowsCount();
  //echo "<pre>";
  //print_r($rsData->Fetch());
  //echo "</pre>";
}

$this->IncludeComponentTemplate($template);
?>