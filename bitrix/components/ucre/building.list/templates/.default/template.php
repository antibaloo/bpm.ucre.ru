<?php
if(!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED!==true) die();
?>
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
<?//echo "<pre>";print_r($arResult);echo "</pre>";?>
<div class="menu">
  <div class="button" href="/townbase/building/edit/0/">
    Новое здание
  </div>
</div>
<div class="clearFloat"></div>
<form id="buildingList" method="POST" action="<?=$_SERVER["REQUEST_URI"]?>" enctype="multipart/formdata">
  <div class="gridWrapper">
    <div class="gridHeader textCenter"></div>
    <div class="gridHeader textCenter">id</div>
    <div class="gridHeader textCenter">Адрес</div>
    <div class="gridHeader textCenter">N<sub>кад</sub></div>
    <div class="gridHeader textCenter">S<sub>общ</sub></div>
    <div class="gridHeader textCenter">Помещений</div>
    <div class="gridHeader textCenter">жилых</div>
    <div class="gridHeader textCenter">Этажность<sub>min</sub></div>
    <div class="gridHeader textCenter">Этажность<sub>max</sub></div>
    <div class="gridHeader textCenter">Подъездов</div>
    <?foreach ($arResult['DATA'] as $building){?>
    <div class="gridCell textCenter">
      <a href="/townbase/building/show/<?=$building['ID']?>/"><i class="fa fa-eye" title="Смотреть"></i></a>&nbsp;
      <a href="/townbase/building/edit/<?=$building['ID']?>/"><i class="fa fa-pencil-square-o" title="Редактировать"></i></a>&nbsp;
      <a href="/townbase/chess/show/<?=$building['ID']?>/"><i class="fa fa-table" aria-hidden="true" title="Шахматка"></i></a>
    </div>
    <div class="gridCell textCenter"><?=$building['ID']?></div>
    <div class="gridCell textRight"><?=$building['UF_BUILDING_ADDRESS']?></div>
    <div class="gridCell textCenter"><?=$building['UF_BUILDING_KAD_NUM']?></div>
    <div class="gridCell textRight"><?=$building['UF_B_SQUARE']?></div>
    <div class="gridCell textRight"><?=$building['UF_PLACEMENT_COUNT']?></div>
    <div class="gridCell textRight"><?=$building['UF_LIVE_COUNT']?></div>
    <div class="gridCell textRight"><?=$building['UF_FLOORS_MIN']?></div>
    <div class="gridCell textRight"><?=$building['UF_FLOORS_MAX']?></div>
    <div class="gridCell textRight"><?=$building['UF_SECTIONS']?></div>
    <?}?>
  </div>
  <div class="gridFooter">
  <?for ($i=1;$i<=$arResult['PAGES'];$i++){?>
    <span class="pagesList <?=($arResult['ACTIVE_PAGE'] == $i)?"active":""?>"><?=$i?></span>
  <?}?>
  </div>
  <input id="page" name="page" type="hidden" value="<?=$arResult['ACTIVE_PAGE']?>">
  <button id="submit" type="submit" style="display:none;"></button>
</form>
<script>
   $(".pagesList").click(function(){
   if ($(this).html() != $("#page").val()){
       $("#page").val($(this).html())
     $('#submit').trigger('click');
   }
  });
  $(".button").click(function(){
    location.href = $(this).attr("href");
  });
</script>