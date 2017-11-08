<?php
if(!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED!==true) die();
?>
<h2>
	Шахматка здания
</h2>
<?
$APPLICATION->IncludeComponent(
		'ucre:building.chess',
		'',
		array(
			'ELEMENT_ID' => $arResult['VARIABLES']['element_id'],
			'AJAX_MODE' => 'Y',
			'AJAX_OPTION_SHADOW' => 'Y',
			'AJAX_OPTION_JUMP' => 'N'
		),
		$component
	);
?>