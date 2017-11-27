<?
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED!==true)die();
// подключаем модули
CModule::IncludeModule('iblock');
CModule::IncludeModule('highloadblock');

// необходимые классы
use Bitrix\Highloadblock as HL;
use Bitrix\Main\Entity;
// $hlblock - это массив, 1 - hl блок Placement
$hlblock   = HL\HighloadBlockTable::getById(1)->fetch();
$Placement   = HL\HighloadBlockTable::compileEntity($hlblock);
$PlacementDataClass = $Placement->getDataClass();

// $hlblock - это массив, 2 - hl блок Building
$hlblock   = HL\HighloadBlockTable::getById(2)->fetch();
$Building   = HL\HighloadBlockTable::compileEntity( $hlblock );
$BuildingDataClass = $Building->getDataClass();


$arResult['FILTER'] = (isset($_POST['filter']))?array_merge( $_POST['filter'],$arParams['FILTER']):$arParams['FILTER'];
$rsData = $PlacementDataClass::getList(
  array(
    "select" => array('*'), //выбираем все поля
    "filter" => $arResult['FILTER'],
    "order" => array("ID"=>"ASC"), // сортировка по полю ID, будет работать только, если вы завели такое поле в hl'блоке
  )
);

$arResult['ON_PAGE'] = ($arParams['ON_PAGE']>0)?$arParams['ON_PAGE']:25;
$arResult['ACTIVE_PAGE'] = ($_POST['page'] >0)?$_POST['page']:1;
$arResult['COUNT'] = $rsData->getSelectedRowsCount();
$arResult['PAGES'] = ($arResult['COUNT'] / $arResult['ON_PAGE'] > 1)?ceil($arResult['COUNT'] / $arResult['ON_PAGE']):1;

$arResult['DATA'] = array_slice ($rsData->FetchAll(),($arResult['ACTIVE_PAGE']-1)*$arResult['ON_PAGE'],$arResult['ON_PAGE']);//Вырезаем часть результата в соответствии с активной страницей и кол-вом записей на странице
foreach ($arResult['DATA'] as $key=>$placement){
  if ($placement['UF_PLACEMENT_TYPE_ID'] == 1) $arResult['DATA'][$key]['UF_PLACEMENT_TYPE'] = "нежилое";
  elseif ($placement['UF_LIVING_TYPE_ID'] == 1) $arResult['DATA'][$key]['UF_PLACEMENT_TYPE'] ="жилое/комната";
  else $arResult['DATA'][$key]['UF_PLACEMENT_TYPE'] ="жилое/квартира";
  $temp = $BuildingDataClass::getRowById($placement['UF_BUILDING_ID']);
  $arResult['DATA'][$key]['UF_BUILDING_ADDRESS'] = $temp['UF_BUILDING_ADDRESS'];
   $arResult['DATA'][$key]['FLOORS'] = $placement['UF_FLOOR']."/".$temp['UF_FLOORS_MAX'];
}
echo "<pre>";print_r($arResult);echo "</pre>";
$this->IncludeComponentTemplate();
?>