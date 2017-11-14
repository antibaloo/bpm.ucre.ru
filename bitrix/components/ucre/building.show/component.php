<?
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED!==true)die();
// подключаем модули
CModule::IncludeModule('iblock');
CModule::IncludeModule('highloadblock');

// необходимые классы
use Bitrix\Highloadblock as HL;
use Bitrix\Main\Entity;

// $hlblock - это массив, 2 - hl блок Building
$hlblock   = HL\HighloadBlockTable::getById(2)->fetch();
$Building   = HL\HighloadBlockTable::compileEntity( $hlblock );
$BuildingDataClass = $Building->getDataClass();
$arResult['DATA'] = $BuildingDataClass::getRowById($arParams['ELEMENT_ID']);

// 6 - hl блок Plots
$hlblock   = HL\HighloadBlockTable::getById(6)->fetch();
$Plots   = HL\HighloadBlockTable::compileEntity( $hlblock );
$PlotsDataClass = $Plots->getDataClass();
if($arResult['DATA']['UF_B_PLOT_ID']>0){
  $temp = $PlotsDataClass::getRowById($arResult['DATA']['UF_B_PLOT_ID']);
  $arResult['DATA']['PLOT_NAME'] = trim ($temp['UF_PLOT_KAD_NUM']." ".$temp['UF_PLOT_ADDRESS']);
}
// 23 - hl блок RealEstate - Жилые комплексы
$hlblock   = HL\HighloadBlockTable::getById(23)->fetch();
$ZHKs   = HL\HighloadBlockTable::compileEntity( $hlblock );
$ZHKDataClass = $ZHKs->getDataClass();
if($arResult['DATA']['UF_RS']>0){
  $temp = $ZHKDataClass::getRowById($arResult['DATA']['UF_RS']);
  $arResult['DATA']['RS_NAME'] = $temp['UF_NAMERS'];
}

// 24 - hl блок Materials - материалы здания
$hlblock   = HL\HighloadBlockTable::getById(24)->fetch();
$Materials   = HL\HighloadBlockTable::compileEntity( $hlblock );
$MaterialsDataClass = $Materials->getDataClass();
if ($arResult['DATA']['UF_MATERIAL']>0){
  $temp = $MaterialsDataClass::getRowById($arResult['DATA']['UF_MATERIAL']);
  $arResult['DATA']['MATERIAL_NAME'] = $temp['UF_MATERIAL_NAME'];
}

switch ($arResult['DATA']['UF_BUILDING_TYPE_ID']){
  case 1:
    $template = 'nonresidental';
    break;
  case 2:
    $template = 'private';
    break;
  case 3:
    $template = 'multiflat';
    break;
}
$this->IncludeComponentTemplate($template);
?>