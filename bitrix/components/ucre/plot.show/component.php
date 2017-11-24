<?
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED!==true)die();
// подключаем модули
CModule::IncludeModule('iblock');
CModule::IncludeModule('highloadblock');

// необходимые классы
use Bitrix\Highloadblock as HL;
use Bitrix\Main\Entity;

// $hlblock - это массив, 6 - hl блок Plot
$hlblock   = HL\HighloadBlockTable::getById(6)->fetch();
$Plot   = HL\HighloadBlockTable::compileEntity($hlblock);
$PlotDataClass = $Plot->getDataClass();
$arResult['DATA'] = $PlotDataClass::getRowById($arParams['ELEMENT_ID']);

// $hlblock - это массив, 7 - hl блок PlotType
$hlblock   = HL\HighloadBlockTable::getById(7)->fetch();
$PlotType   = HL\HighloadBlockTable::compileEntity( $hlblock );
$PlotTypeDataClass = $PlotType->getDataClass();


if($arResult['DATA']['UF_PLOT_TYPE_ID']>0){
  $temp = $PlotTypeDataClass::getRowById($arResult['DATA']['UF_PLOT_TYPE_ID']);
  $arResult['DATA']['UF_PLOT_TYPE_SHORT'] = $temp['UF_PLOT_TYPE_SHORT'];
}


//echo "<pre>";print_r($arResult);echo "</pre>";
$this->IncludeComponentTemplate();
?>