<?php
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED!==true) die();
CModule::IncludeModule("crm");
use Bitrix\Main\UI\Extension;

Extension::load('ui.buttons');
Extension::load('ui.buttons.icons');
Extension::load('ui.forms');

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
    "b_uts_crm_deal.UF_CRM_58958B5734602,". //Цена
    "b_uts_crm_deal.UF_CRM_58CFC7CDAAB96,". //Тип недвижимости
    "b_uts_crm_deal.UF_CRM_1512621495, b_iblock_element.NAME, b_iblock_element_prop_s42.PROPERTY_209, b_iblock_element_prop_s42.PROPERTY_210, b_iblock_element_prop_s42.PROPERTY_215, b_iblock_element_prop_s42.PROPERTY_216, b_iblock_element_prop_s42.PROPERTY_217, b_iblock_element_prop_s42.PROPERTY_218, b_iblock_element_prop_s42.PROPERTY_229, b_iblock_element_prop_s42.PROPERTY_224, b_iblock_element_prop_s42.PROPERTY_226, b_iblock_element_prop_s42.PROPERTY_221, b_iblock_element_prop_s42.PROPERTY_222, b_iblock_element_prop_s42.PROPERTY_241, b_iblock_element_prop_s42.PROPERTY_242, b_iblock_element_prop_s42.PROPERTY_243, b_iblock_element_prop_s42.PROPERTY_298, b_iblock_element_prop_s42.PROPERTY_299".
    " FROM b_crm_deal".                                                                                           //из таблицы заявок
    " INNER JOIN b_uts_crm_deal ON b_crm_deal.ID=b_uts_crm_deal.VALUE_ID".                                        //из таблицы пользовательских свойств заявок
    " INNER JOIN b_iblock_element ON b_uts_crm_deal.UF_CRM_1469534140 = b_iblock_element.ID".                     //из таблицы инфоблоков
    " INNER JOIN b_iblock_element_prop_s42 ON b_iblock_element.ID = b_iblock_element_prop_s42.IBLOCK_ELEMENT_ID". //из таблицы пользовательских свойств инфоблоков
    " WHERE b_crm_deal.STAGE_ID in ('10','PROPOSAL','1','13','C4:1','C4:PROPOSAL')".                              //в списоке подходящий статусов
    " AND UF_CRM_58CFC7CDAAB96 = '".$arResult['PARAMS']['UF_CRM_58CFC7CDAAB96']."'";                              //выбранного типа недвижимости
  
  $sqlString .= " ORDER BY DATE_MODIFY DESC";  //Сортировка
  echo $sqlString;
  $rsData = $DB->Query($sqlString);
  
  echo "<pre>";
  print_r($rsData->Fetch());
  echo "</pre>";
}

$this->IncludeComponentTemplate($template);
?>