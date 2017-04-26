<?
CModule::IncludeModule('crm');
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED!==true) die();//Запрет вызова из адресной строки браузера
$rsDeal = CCrmDeal::GetListEx(
	array(),
	array("ID" => $arParams['DEAL_ID']),
	false,
	false,
	array("CATEGORY_ID", "UF_CRM_1469534140"),
	array()
);
$mainDeal = $rsDeal->Fetch();
if ($mainDeal["CATEGORY_ID"] == 0 || $mainDeal["CATEGORY_ID"] == 4){
	if ($mainDeal["UF_CRM_1469534140"]){
    $rsObject = CIBlockElement::GetById($mainDeal["UF_CRM_1469534140"]);
    $mainObject = $rsObject->GetNextElement();
    $arResult['RO'] = $mainObject->GetFields();
    $arResult['RO']['PROPERTIES'] = $mainObject->GetProperties();
    switch ($arResult['RO']['PROPERTIES']['TYPE']['VALUE']) {
      case 'комната':
        $componentPage = 'room';
        break;
      case 'квартира':
        $componentPage = 'flat';
        break;
      case 'дом':
        $componentPage = 'house';
        break;
      case 'таунхаус':
        $componentPage = 'townhouse';
        break;
      case 'дача':
        $componentPage = 'dacha';
        break;
      case 'участок':
        $componentPage = 'plot';
        break;
      case 'коммерческий':
        $componentPage = 'comm';
        break;
    }
    $this->IncludeComponentTemplate($componentPage);
  }else{
    $this->IncludeComponentTemplate('no_object');
  }
}
?>