<?php
if(!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED!==true) die();
$APPLICATION->SetTitle("Редактирование шахматки");
?>
<form id="chessForm" method="POST" action="<?=$_SERVER["REQUEST_URI"]?>">
  <div class="buttonWrapper">
    <div class="empty"></div>
    <div class="empty"></div>
    <div class="formButton" href="/townbase/building/show/<?=$arResult['DATA']['ID']?>/">Карточка здания</div>
    <div class="formButton">Сохранить шахматку</div>
    <div class="formButton" href="/townbase/building/list/">к списку</div>
    <div class="empty"></div>
    <div class="empty"></div>
  </div>
  <button id="submit" type="submit" style="display:none;"></button>
</form>

<?echo "<pre>"; print_r($arResult);echo "</pre>";?>
<script>
  $(".formButton").click(function (){
    if ($(this).attr("href")!==udefined) document.location.href = $(this).attr("href");
  });
</script>