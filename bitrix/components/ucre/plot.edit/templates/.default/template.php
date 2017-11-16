<?php
if(!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED!==true) die();
$APPLICATION->SetTitle("Заполните параметры участка");
?>
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.4/css/select2.min.css" rel="stylesheet" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.4/js/select2.min.js"></script>
<script src="https://api-maps.yandex.ru/2.0/?load=package.full&lang=ru-RU"></script>
<form id ="plotForm" method="POST" action="<?=$_SERVER["REQUEST_URI"]?>">
  <input id="ID" name="ID" type="hidden" value="<?=$arResult['ID']?>">
  <button id="submit" type="submit" style="display:none;"></button>
  <div class="buttonWrapper">
    <div class="empty"></div>
    <div class="empty"></div>
    <div class="formButton" action="save">Сохранить</div>
    <div class="empty"></div>
    <div class="formButton" action="cancel">Отменить</div>
    <div class="empty"></div>
    <div class="empty"></div>
  </div>
</form>
<script>
  $(".formButton").click(function (){
    if ($(this).attr("action") == 'cancel'){
      document.location.href = '/townbase/plot/list/';
    }else{
      $("#ACTION").val($(this).attr("action"));
      $('#submit').trigger('click');
    }
  });
</script>