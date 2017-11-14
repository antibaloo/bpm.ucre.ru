<?php
if(!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED!==true) die();
?>
<?
$APPLICATION->IncludeComponent(
		'ucre:building.chess',
		'',
		array(
			'ELEMENT_ID' => $arResult['VARIABLES']['element_id'],
			'ACTION' => $arResult['VARIABLES']['action'],
			'AJAX_MODE' => 'Y',
			'AJAX_OPTION_SHADOW' => 'Y',
			'AJAX_OPTION_JUMP' => 'N'
		),
		$component
	);
?>