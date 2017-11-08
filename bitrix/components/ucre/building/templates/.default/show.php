<?php
if(!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED!==true) die();
?>
<h2>
	Карточка здания
</h2>
<?
$APPLICATION->IncludeComponent(
		'ucre:building.show',
		'',
		array(
			'TEMPLATE' => $arResult['template'],
		),
		$component
	);
?>