<?php
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED!==true) die();
foreach($arResult['ERRORS'] as $error){
  echo "Ошибка: ".$error."<br>";
}
?>