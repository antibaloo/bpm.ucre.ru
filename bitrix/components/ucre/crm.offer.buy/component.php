<?php
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED!==true) die();
CModule::IncludeModule("crm");
use Bitrix\Main\UI\Extension;

Extension::load('ui.buttons');
Extension::load('ui.buttons.icons');

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
        "CATEGORY_ID",              //Направление заявки
        "UF_CRM_58CFC7CDAAB96",     //Тип недвижимости
      ),
      array()
    );
    $mainDeal = $rsDeal->Fetch();
    if (($mainDeal['CATEGORY_ID']!= 2)) { $template = "error"; $arResult['ERRORS'][] = "Заявка с id: ".$arParams['ID']." не является заявкой на подбор объекта"; }
    unset($mainDeal['CATEGORY_ID']); //Убираем ненужный параметр, чтоб не мешал
    unset($mainDeal['ID']);          //Убираем ненужный параметр, чтоб не мешал
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