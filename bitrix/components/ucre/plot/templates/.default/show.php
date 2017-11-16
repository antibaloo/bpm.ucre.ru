<?php
if(!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED!==true) die();
?>
<?
$APPLICATION->IncludeComponent(
		'ucre:plot.show',
		'',
		array(
			'ELEMENT_ID' => $arResult['VARIABLES']['element_id'],
		),
		$component
	);
?>