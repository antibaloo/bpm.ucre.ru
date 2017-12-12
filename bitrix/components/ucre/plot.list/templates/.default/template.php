<?php
if(!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED!==true) die();
?>
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
<?//echo "<pre>";print_r($arResult);echo "</pre>";?>
<?if (!$arResult['NO_MENU']){?>
<div class="menu">
  <div class="button" href="/townbase/plot/edit/0/">
    Новый участок
  </div>
</div>
<div class="clearFloat"></div>
<?}else{?>
<hr>
<center>Дубликаты</center>
<hr>
<?}?>
<form id="plotList" method="POST" action="<?=$_SERVER["REQUEST_URI"]?>" enctype="multipart/formdata">
  <div class="gridWrapper">
    <div class="gridHeader textCenter"></div>
    <div class="gridHeader textCenter">id</div>
    <div class="gridHeader textCenter">Кадастровый номер</div>
    <div class="gridHeader textCenter">Адрес</div>
    <div class="gridHeader textCenter">Площадь, м<sup>2</sup></div>
    <?foreach ($arResult['DATA'] as $plot){?>
    <div class="gridCell textCenter">
      <a href="/townbase/plot/show/<?=$plot['ID']?>/"><i class="fa fa-eye" title="Смотреть"></i></a>&nbsp;
      <a href="/townbase/plot/edit/<?=$plot['ID']?>/"><i class="fa fa-pencil-square-o" title="Редактировать"></i></a>&nbsp;
    </div>
    <div class="gridCell textCenter"><?=$plot['ID']?></div>
    <div class="gridCell textleft"><?=$plot['UF_PLOT_KAD_NUM']?></div>
    <div class="gridCell textRight"><?=$plot['UF_PLOT_ADDRESS']?></div>
    <div class="gridCell textRight"><?=$plot['UF_P_SQUARE']?></div>
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