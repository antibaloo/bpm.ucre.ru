<?php
if(!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED!==true) die();
?>
<?
$APPLICATION->IncludeComponent(
		'ucre:building.new',
		'',
		array(
			'AJAX_MODE' => 'Y',
			'AJAX_OPTION_SHADOW' => 'Y',
			'AJAX_OPTION_JUMP' => 'N'
		),
		$component
	);
?>