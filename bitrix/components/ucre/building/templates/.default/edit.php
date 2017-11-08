<?php
if(!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED!==true) die();
?>
<h2>
	Редактировать здание
</h2>
<?
$APPLICATION->IncludeComponent(
		'ucre:building.edit',
		'',
		array(
			'TEMPLATE' => $arResult['template'],
		),
		$component
	);
?>