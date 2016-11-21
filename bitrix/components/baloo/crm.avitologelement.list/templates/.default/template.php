<?php
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
global $APPLICATION;
$APPLICATION->IncludeComponent(
  'bitrix:main.interface.grid',
  '',
  array('GRID_ID'          => $arResult['GRID_ID'],
        'HEADERS'          => $arResult['HEADERS'],
        'SORT'             => $arResult['SORT'],
        'SORT_VARS'        => $arResult['SORT_VARS'],
        'ROWS'             => $arResult['ROWS'],
        'FOOTER'           => array(array('title'=>'Всего', 'value' => $arResult['AVITOELEMENT_COUNT'])),
        'ACTIONS'          => array(),
        'EDITABLE'         => true,
        'NAV_OBJECT'       => $arResult['NAV_OBJECT'],
        'AJAX_MODE'        => 'Y',
        'AJAX_OPTION_JUMP' => 'N',
        'AJAX_OPTION_STYLE'=> 'Y',
        "FILTER_TEMPLATE_NAME" => "tabbed",
        'FILTER'           => $arResult['FILTER']
       ),
  $component
);
?>