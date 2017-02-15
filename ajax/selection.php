<?php
/*----------------------------------------------------------------------------------------*/
// Скрипт подбора встречных заявок. Абалаков А.С. baloo2000@mail.ru, a.s.abalakov@ucre.ru
// Поля с параметрами заявки на покупку
// UF_CRM_5895BC940ED3F - Первичная/Вторичная   
// UF_CRM_58958B5724514 - тип недвижимости (комната. квартира и т.д.) 
// UF_CRM_58958B529E628 - минимальное кол-во комнат
// UF_CRM_58958B52BA439 - общая площадь не менее
// UF_CRM_58958B52F2BAC - площадь кухни не менее
// UF_CRM_58958B51B667E - исключить этажи
// UF_CRM_58958B576448C - цена от
// UF_CRM_58958B5751841 - цена до

// Поля с параметрами заявки на продажу в заявке
// UF_CRM_1469534140 - id связанного объекта
// UF_CRM_58958B5734602 - стоимость объекта
// Поля с параметрами заявки на продажу в объекте
// PROPERTY_210 - тип объекта недвижимости
// PROPERTY_221 - этаж
// PROPERTY_222 - этажность
// PROPERTY_224 - общ площадь
// PROPERTY_226 - площадь кухни
// PROPERTY_229 - кол-во комнат
/*----------------------------------------------------------------------------------------*/
require_once($_SERVER['DOCUMENT_ROOT'] . "/bitrix/modules/main/include/prolog_before.php");
CModule::IncludeModule("crm");
if (isset($_POST['id']) && $_POST['id'] > 0){
  if ($_POST['category'] == '2'){
    //Читаем параметры поиска
    $rsDeal = CCrmDeal::GetListEx(
      array(), 
      array("ID" => $_POST['id']), 
      false, 
      false, 
      array("UF_CRM_5895BC940ED3F","UF_CRM_58958B5724514","UF_CRM_58958B529E628","UF_CRM_58958B52BA439","UF_CRM_58958B52F2BAC","UF_CRM_58958B51B667E","UF_CRM_58958B576448C","UF_CRM_58958B5751841"),
      array()
    );
    $mainDeal = $rsDeal->Fetch();
    //Фильтр по рынку поиска (Вторичка и /или Новостройки)
    if (count($mainDeal['UF_CRM_5895BC940ED3F']) == 0 || count($mainDeal['UF_CRM_5895BC940ED3F']) == 2) {
      $market = " AND (b_crm_deal.CATEGORY_ID = 0 or b_crm_deal.CATEGORY_ID = 4)";
    } elseif ($mainDeal['UF_CRM_5895BC940ED3F'][0] == '827') {
      $market = " AND b_crm_deal.CATEGORY_ID = 0";
    } elseif ($mainDeal['UF_CRM_5895BC940ED3F'][0] == '828') {
      $market = " AND b_crm_deal.CATEGORY_ID = 4";
    }



    //Выборка всех нужных полей и объединения таблицы Заявки+Польз_поля_заявки_Инфоблок_ОН+Польз_поля_инфоблок_ОН    
    $rsQuery = "SELECT b_crm_deal.ID,b_crm_deal.CATEGORY_ID, b_crm_deal.TITLE,b_uts_crm_deal.UF_CRM_1469534140,b_uts_crm_deal.UF_CRM_58958B5734602, b_iblock_element.NAME, b_iblock_element_prop_s42.PROPERTY_210, b_iblock_element_prop_s42.PROPERTY_229, b_iblock_element_prop_s42.PROPERTY_224, b_iblock_element_prop_s42.PROPERTY_226, b_iblock_element_prop_s42.PROPERTY_221, b_iblock_element_prop_s42.PROPERTY_222 FROM b_crm_deal INNER JOIN b_uts_crm_deal ON b_crm_deal.ID=b_uts_crm_deal.VALUE_ID INNER JOIN b_iblock_element ON b_uts_crm_deal.UF_CRM_1469534140 = b_iblock_element.ID INNER JOIN b_iblock_element_prop_s42 ON b_iblock_element.ID = b_iblock_element_prop_s42.IBLOCK_ELEMENT_ID WHERE (b_crm_deal.STAGE_ID = 'PROPOSAL' or b_crm_deal.STAGE_ID = 'C4:1' or b_crm_deal.STAGE_ID = 'C4:PROPOSAL')";
    $rsQuery .= $market;
    $rsData = $DB->Query($rsQuery);
    echo $rsData->SelectedRowsCount()."<br>";
    /*while ($aRes = $rsData->GetNext()){
      print_r($aRes);
    }*/
    echo "Для этой категории заявок поиск встречных ведем среди заявок на продажу и/или новостроек!";
  } elseif (in_array($_POST['category'], array(0,4))){
    //Читаем параметры поиска
    $rsQuery = "SELECT b_crm_deal.ID,b_crm_deal.CATEGORY_ID, b_crm_deal.TITLE,b_uts_crm_deal.UF_CRM_1469534140,b_uts_crm_deal.UF_CRM_58958B5734602, b_iblock_element.NAME, b_iblock_element_prop_s42.PROPERTY_210, b_iblock_element_prop_s42.PROPERTY_229, b_iblock_element_prop_s42.PROPERTY_224, b_iblock_element_prop_s42.PROPERTY_226, b_iblock_element_prop_s42.PROPERTY_221, b_iblock_element_prop_s42.PROPERTY_222 FROM b_crm_deal INNER JOIN b_uts_crm_deal ON b_crm_deal.ID=b_uts_crm_deal.VALUE_ID INNER JOIN b_iblock_element ON b_uts_crm_deal.UF_CRM_1469534140 = b_iblock_element.ID INNER JOIN b_iblock_element_prop_s42 ON b_iblock_element.ID = b_iblock_element_prop_s42.IBLOCK_ELEMENT_ID WHERE b_crm_deal.ID=".$_POST['id'];
    $rsData = $DB->Query($rsQuery);
    echo $rsData->SelectedRowsCount()."<br>";
    $mainDeal = $rsData->Fetch();
    print_r($mainDeal);
    echo "Для этой категории заявок поиск встречных ведем среди заявок на покупку!";
  }
  /*
  $rsDeal = CCrmDeal::GetListEx(
    array(), 
    array("ID" => $_POST['id']), 
    false, 
    false, 
    array("CATEGORY_ID","UF_CRM_58958B5724514", "UF_CRM_5895BC940ED3F","UF_CRM_1476448884","UF_CRM_1479793392","UF_CRM_1479793417", "UF_CRM_1469534140"),
    array()
  );
  $mainDeal = $rsDeal->Fetch();
  if (in_array($mainDeal['CATEGORY_ID'], array(0,4))){
    echo "<br>Для этой категории заявок поиск встречных ведем среди заявок на покупку!";
  } elseif ($mainDeal['CATEGORY_ID'] == 2){
    $rsEnum = CUserFieldEnum::GetList(array(), array("ID" => $mainDeal['UF_CRM_58958B5724514'])); // $ENUM_ID - возвращаемый ID значения 
    $arEnum = $rsEnum->GetNext(); 
    if (!$mainDeal['UF_CRM_58958B5724514']){
      echo "Не задан тип недвижимости";
      die();
    }
    $type = $arEnum['VALUE'];
    echo $type."<br>";
    
    print_r($mainDeal['UF_CRM_5895BC940ED3F']);
    
    $rooms = $mainDeal['UF_CRM_1476448884'];
    $minprice = $mainDeal['UF_CRM_1479793392'];
    $maxprice = $mainDeal['UF_CRM_1479793417'];
    echo "<br>Для этой категории заявок поиск встречных ведем среди заявок на продажу и новостроек!";
  } else {
    echo "<br>Для этой категории заявок поиск встречных не предусмотрен!";
  }*/
}



?>
