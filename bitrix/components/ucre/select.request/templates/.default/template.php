<?php
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED!==true)die();
?>
<table style="width:100%!important;border-collapse:collapse!important; text-align:center!important; margin-top: 10px;!important;margin-bottom: 10px;!important">
  <tr style="border-bottom: 1px solid black!important;"><td colspan="8" ><b>Параметры поиска</b></td></tr>
  <tr style="border-bottom: 1px solid black!important; font-weight:bold!important">
    <td>Рынок</td><td>Тип объекта</td><td>N<sub>комнат</sub></td><td>S<sub>общая</sub></td><td>S<sub>кухни</sub></td><td>Исключить этажи</td><td>Цена<sub>min</sub></td><td>Цена<sub>max</sub></td>
  </tr>
  <tr>
    <td><?=$arResult['SELECT_PARAMS']['MARKET']?></td>
    <td><?=$arResult['SELECT_PARAMS']['TYPE']?></td>
    <td><?=$arResult['SELECT_PARAMS']['ROOMS']?></td>
    <td><?=$arResult['SELECT_PARAMS']['TOTAL_AREA']?></td>
    <td><?=$arResult['SELECT_PARAMS']['KITCHEN_AREA']?></td>
    <td><?=$arResult['SELECT_PARAMS']['EX_FLOORS']?></td>
    <td><?=$arResult['SELECT_PARAMS']['MINPRICE']?></td>
    <td><?=$arResult['SELECT_PARAMS']['MAXPRICE']?></td>
  </tr>
</table>
<?
$APPLICATION->IncludeComponent(
  'bitrix:main.interface.grid',
  '',
  array('GRID_ID'=>$arResult['GRID_ID'],
				'HEADERS'=>$arResult['HEADERS'],
				'SORT'=>$arResult['SORT'],
				'SORT_VARS'=>$arResult['SORT_VARS'],
				'ROWS'=>$arResult['ROWS'],
				'FOOTER'=>array(array('title'=>"Всего", 'value'=>$arResult['ROWS_COUNT'])),
        'ACTION_ALL_ROWS'=>false,
        'EDITABLE'=>false,
        'NAV_OBJECT'=>$arResult['NAV_OBJECT'],
        'AJAX_MODE'=>"Y",
        'AJAX_OPTION_JUMP'=>"Y",
        'AJAX_OPTION_STYLE'=>"Y",
        'SHOW_FORM_TAG'=>false,
       ),
  $component
);
?>