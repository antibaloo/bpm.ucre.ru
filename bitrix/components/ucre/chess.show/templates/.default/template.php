<?php
if(!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED!==true) die();
$APPLICATION->SetTitle("Просмотр шахматки");
?>
<div class="buttonWrapper">
  <div class="empty"></div>
  <div class="empty"></div>
  <div class="formButton" href="/townbase/building/show/<?=$arResult['DATA']['ID']?>/">Карточка здания</div>
  <div class="formButton" href="/townbase/chess/edit/<?=$arResult['DATA']['ID']?>/">Редактировать шахматку</div>
  <div class="formButton" href="/townbase/building/list/">к списку</div>
  <div class="empty"></div>
  <div class="empty"></div>
</div>
<?echo "<pre>"; print_r($arResult);echo "</pre>";?>
<script>
  $(".formButton").click(function (){
    document.location.href = $(this).attr("href");
  });
</script>