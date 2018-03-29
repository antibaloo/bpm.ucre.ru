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
      ),
      array()
    );
    $mainDeal = $rsDeal->Fetch();
    unset($mainDeal['ID']); //Убираем ненужный параметр
    $arResult['PARAMS'] = $mainDeal;
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


$this->IncludeComponentTemplate($template);
?>