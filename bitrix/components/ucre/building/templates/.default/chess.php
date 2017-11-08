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
			'TEMPLATE' => $arResult['template'],
		),
		$component
	);
?>