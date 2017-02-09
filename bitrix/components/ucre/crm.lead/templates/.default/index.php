<?php
if(!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED!==true) die();
?>
<ul class="main-menu">
  <li><a href="#">Старт</a></li>
  <li class="active"><a href="/crm/lead_ucre/">Лиды</a></li>
  <li><a href="#">Контакты</a></li>
  <li><a href="#">Компании</a></li>
  <li><a href="#">Заявки</a></li>
  <li><a href="#">Сделки</a></li>
</ul>
<div class="ucre_lead_list">
<?
echo "Список лидов";
echo "<pre>";
print_r($arResult);
echo "</pre>";
?>
</div>