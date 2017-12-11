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

$arResult['FILTER'] = (isset($_POST['filter']))?array_merge( $_POST['filter'],$arParams['FILTER']):$arParams['FILTER'];
$rsData = $PlotDataClass::getList(
  array(
    "select" => array('*'), //выбираем все поля
    "filter" => $arResult['FILTER'],
    "order" => array("ID"=>"DESC"), // сортировка по полю ID, будет работать только, если вы завели такое поле в hl'блоке
  )
);

$arResult['ON_PAGE'] = ($arParams['ON_PAGE']>0)?$arParams['ON_PAGE']:25;
$arResult['ACTIVE_PAGE'] = ($_POST['page'] >0)?$_POST['page']:1;
$arResult['COUNT'] = $rsData->getSelectedRowsCount();
$arResult['PAGES'] = ($arResult['COUNT'] / $arResult['ON_PAGE'] > 1)?ceil($arResult['COUNT'] / $arResult['ON_PAGE']):1;
$arResult['DATA'] = array_slice ($rsData->FetchAll(),($arResult['ACTIVE_PAGE']-1)*$arResult['ON_PAGE'],$arResult['ON_PAGE']);//Вырезаем часть результата в соответствии с активной страницей и кол-вом записей на странице
$arResult['NO_MENU'] = ($arParams['NO_MENU'])?$arParams['NO_MENU']: false;
$this->IncludeComponentTemplate();
?>