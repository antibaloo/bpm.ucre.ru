<?php
if(!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED!==true) die();
?>
<?
$APPLICATION->IncludeComponent(
		'ucre:placement.list',
		'',
		array(
			'ON_PAGE' => 20,
			'AJAX_MODE' => 'Y',
			'AJAX_OPTION_SHADOW' => 'Y',
			'AJAX_OPTION_JUMP' => 'N'
		),
		$component
	);
?>