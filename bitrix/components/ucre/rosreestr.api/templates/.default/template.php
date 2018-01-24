<?php
if(!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED!==true) die();
?>
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.4/css/select2.min.css" rel="stylesheet" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.4/js/select2.min.js"></script>
<form id="apiSearch" method="POST" action="<?=$_SERVER["REQUEST_URI"]?>" enctype="multipart/formdata">
  <div class="paramsWrapper">
    <div class="label paramBox">Субъект РФ</div>
    <div class="label paramBox">Регион/НП</div>
    <div class="label paramBox">НП/Район НП</div>
    <div class="label paramBox">улица</div>
    <div class="label paramBox">дом</div>
    <div class="label paramBox">корпус</div>
    <div class="label paramBox">строение</div>
    <div class="label paramBox">помещение</div>
  </div>
  <div class="paramsWrapper">
    <div class="param paramBox"><select class="selectList" id="macroRegionList" name="macroRegionId"></select></div>
    <div class="param paramBox"><select class="selectList" id="regionList" name="RegionId"></select></div>
    <div class="param paramBox"><select class="selectList" id="settlementList" name="settlementId"></select></div>
    <div class="param paramBox"><input class="block-input" type="search" id="street" name="street" value="<?=$arResult['street']?>"></div>
    <div class="param paramBox"><input class="block-input" type="search" id="house" name="house" value="<?=$arResult['house']?>"></div>
    <div class="param paramBox"><input class="block-input" type="search" id="building" name="building" value="<?=$arResult['building']?>"></div>
    <div class="param paramBox"><input class="block-input" type="search" id="structure" name="structure" value="<?=$arResult['structure']?>"></div>
    <div class="param paramBox"><input class="block-input" type="search" id="apartment" name="apartment" value="<?=$arResult['apartment']?>"></div>
  </div>
  
  <input id="page" name="page" type="hidden" value="<?=$arResult['ACTIVE_PAGE']?>">
  <!--
  <input id="page" name="macroRegionId" type="hidden" value="<?=$arResult['macroRegionId']?>">
  <input id="page" name="RegionId" type="hidden" value="<?=$arResult['RegionId']?>">
  <input id="page" name="settlementId" type="hidden" value="<?=$arResult['settlementId']?>">
-->
  <input id="action" name="action" type="hidden" value="">
  <button id="submit" type="submit" style="display:none;"></button>
  <div class="buttonWrapper">
    <div class="empty"></div>
    <div class="empty"></div>
    <div class="empty"></div>
    <div class="empty"></div>
    <div class="formButton" action="search">Искать</div>
    <div class="empty"></div>
    <div class="empty"></div>
    <div class="empty"></div>
    <div class="empty"></div>
  </div>
</form>
<?echo "<pre>";print_r($arResult);echo "</pre>";?>
<script>
  $(document).ready(function() {
    $('#macroRegionList').select2({data: <?=$arResult['macroRegionList']?>});
    $('#regionList').select2({data: <?=$arResult['regionList']?>});
    $('#settlementList').select2({data: <?=$arResult['settlementList']?>});
  });
  $(".formButton").click(function (){
    $("#action").val($(this).attr("action"));
    $('#submit').trigger('click');
  });
  $('#macroRegionList').on('change', function (e) {
    $('#submit').trigger('click');
  });
  $('#regionList').on('change', function (e) {
    $('#submit').trigger('click');
  });  
</script>