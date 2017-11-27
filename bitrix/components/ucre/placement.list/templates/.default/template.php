<?php
if(!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED!==true) die();
?>
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
<?//echo "<pre>";print_r($arResult);echo "</pre>";?>
<form id="plotList" method="POST" action="<?=$_SERVER["REQUEST_URI"]?>" enctype="multipart/formdata">
  <div class="gridWrapper">
    <div class="gridHeader textCenter"></div>
    <div class="gridHeader textCenter sort">id</div>
    <div class="gridHeader textCenter">Кадастровый номер</div>
    <div class="gridHeader textCenter">Тип</div>
    <div class="gridHeader textCenter">Адрес</div>
    <div class="gridHeader textCenter">№ помещения</div>
    <div class="gridHeader textCenter">Комнат</div>
    <div class="gridHeader textCenter">Площадь, м<sup>2</sup></div>
    <div class="gridHeader textCenter">Подъезд</div>
    <div class="gridHeader textCenter">Этажность</div>
    
    
    <?foreach ($arResult['DATA'] as $plot){?>
    <div class="gridCell textCenter">
      <a href="/townbase/placement/show/<?=$plot['ID']?>/"><i class="fa fa-eye" title="Смотреть"></i></a>&nbsp;
      <a href="/townbase/placement/edit/<?=$plot['ID']?>/"><i class="fa fa-pencil-square-o" title="Редактировать"></i></a>&nbsp;
    </div>
    <div class="gridCell textCenter"><?=$plot['ID']?></div>
    <div class="gridCell textleft"><?=$plot['UF_PLACEMENT_KAD_NUM']?></div>
    <div class="gridCell textCenter"><?=$plot['UF_PLACEMENT_TYPE']?></div>
    <div class="gridCell textRight"><a href="/townbase/building/show/<?=$plot['UF_BUILDING_ID']?>/" target="_blank"><?=$plot['UF_BUILDING_ADDRESS']?></a></div>
    <div class="gridCell textRight"><?=$plot['UF_PL_NUMBER']?></div>
    <div class="gridCell textRight"><?=$plot['UF_ROOMS']?></div>
    <div class="gridCell textRight"><?=$plot['UF_PL_SQUARE']?></div>
    <div class="gridCell textRight"><?=$plot['UF_ACCESS']?></div>
    <div class="gridCell textRight"><?=$plot['FLOORS']?></div>
    
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