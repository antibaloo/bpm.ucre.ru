<?php
if(!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED!==true) die();
?>
<h2>
	Новое здание
</h2>
<?
$APPLICATION->IncludeComponent(
		'ucre:building.new',
		'',
		array(
			'TEMPLATE' => $arResult['template'],
		),
		$component
	);
?>