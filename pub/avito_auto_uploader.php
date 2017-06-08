<?php
if ($_GET['count']>0 && in_array($_SERVER['REMOTE_ADDR'], array('178.21.8.184','188.186.237.57','95.71.157.100','188.186.238.88'))){
  require ($_SERVER['DOCUMENT_ROOT'] . "/bitrix/modules/main/include/prolog_before.php");
  CModule::IncludeModule('iblock');
  CModule::IncludeModule('crm');
  $db_res = $DB->Query("select b_crm_deal.ID, b_crm_deal.COMMENTS,b_uts_crm_deal.UF_CRM_58958B5734602, b_uts_crm_deal.UF_CRM_1472038962, b_uts_crm_deal.UF_CRM_1476517423,b_iblock_element.ID as ELEMENT_ID, b_iblock_element.CODE, b_iblock_element_prop_s42.PROPERTY_210, b_iblock_element_prop_s42.PROPERTY_300, b_iblock_element_prop_s42.PROPERTY_213, b_iblock_element_prop_s42.PROPERTY_214, b_iblock_element_prop_s42.PROPERTY_215,b_iblock_element_prop_s42.PROPERTY_216,b_iblock_element_prop_s42.PROPERTY_217,b_iblock_element_prop_s42.PROPERTY_218, b_iblock_element_prop_s42.PROPERTY_298, b_iblock_element_prop_s42.PROPERTY_299, b_iblock_element_prop_s42.PROPERTY_229, b_iblock_element_prop_s42.PROPERTY_228, b_iblock_element_prop_s42.PROPERTY_224, b_iblock_element_prop_s42.PROPERTY_292, b_iblock_element_prop_s42.PROPERTY_225, b_iblock_element_prop_s42.PROPERTY_226, b_iblock_element_prop_s42.PROPERTY_221, b_iblock_element_prop_s42.PROPERTY_222, b_iblock_element_prop_s42.PROPERTY_242, b_iblock_element_prop_s42.PROPERTY_243, b_iblock_element_prop_s42.PROPERTY_238, b_iblock_element_prop_s42.PROPERTY_295 from b_crm_deal LEFT JOIN b_uts_crm_deal ON b_crm_deal.ID = b_uts_crm_deal.VALUE_ID LEFT JOIN b_iblock_element ON b_uts_crm_deal.UF_CRM_1469534140 = b_iblock_element.ID LEFT JOIN b_iblock_element_prop_s42 ON b_uts_crm_deal.UF_CRM_1469534140 = b_iblock_element_prop_s42.IBLOCK_ELEMENT_ID where b_crm_deal.CATEGORY_ID = 0 and b_uts_crm_deal.UF_CRM_1469534140 <> '' and b_crm_deal.STAGE_ID = 'PROPOSAL' AND (TIMESTAMP(b_iblock_element_prop_s42.PROPERTY_260) < NOW() OR b_iblock_element_prop_s42.PROPERTY_260 is null) AND b_crm_deal.COMMENTS<>'' AND b_uts_crm_deal.UF_CRM_58958B5734602 > 0 AND (b_uts_crm_deal.UF_CRM_1472038962<>'a:0:{}' OR b_uts_crm_deal.UF_CRM_1476517423 <> 'a:0:{}') AND b_iblock_element_prop_s42.PROPERTY_210<>387 AND b_iblock_element_prop_s42.PROPERTY_210<>386 ORDER BY b_crm_deal.ID DESC LIMIT ".$_GET['count']);
  while($aRes = $db_res->Fetch()){
    CIBlockElement::SetPropertyValuesEx($aRes['ELEMENT_ID'], false, array('INIT_BY' => 1357, 'AVITO_UPLOAD_DATE' => date("d.m.Y", strtotime("+30 days"))));
    CEventLog::Add(array(
      "SEVERITY" => "SECURITY",
      "AUDIT_TYPE_ID" => "AVT_EXPORT_ADD",
      "MODULE_ID" => "main",
      "ITEM_ID" => 'Автовырузка на Авито',
      "DESCRIPTION" => "Заявка на продажу № ".$aRes['ID']." с объектом № ".$aRes['ELEMENT_ID']." автоматически добавлена в выгрузку на Авито по инициативе компании.",
    ));
  }
  require ($_SERVER['DOCUMENT_ROOT'] . "/bitrix/modules/main/include/epilog_after.php"); 
}else echo "Нет обязательного параметра запуска или он не число!";
?>