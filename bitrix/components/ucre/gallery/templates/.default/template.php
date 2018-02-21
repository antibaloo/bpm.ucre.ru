<?php
if(!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED!==true) die();
//echo "<pre>";print_r($arResult);echo "</pre>";
?>
<script type='text/javascript' src='/include/unitegallery/js/unitegallery.min.js'></script> 
<link rel='stylesheet' href='/include/unitegallery/css/unite-gallery.css' type='text/css' /> 
<script type='text/javascript' src='/include/unitegallery/themes/default/ug-theme-default.js'></script> 
<link rel='stylesheet' href='/include/unitegallery/themes/default/ug-theme-default.css' type='text/css' /> 
<script src='/include/unitegallery/themes/carousel/ug-theme-carousel.js' type='text/javascript'></script>
<script src='/include/unitegallery/themes/tilesgrid/ug-theme-tilesgrid.js' type='text/javascript'></script>
<!-- Собственно структура галереи -->
<?foreach ($arResult['FIELDS'] as $header=>$field){
  if (count($arResult[$field])){?>
<center><h2><?=$header?></h2></center>
<div id="<?=$field?>" style="display:none;">
  <?foreach($arResult[$field] as $fileId=>$link){
      $file = CFile::ResizeImageGet($fileId, array('width'=>150, 'height'=>150), BX_RESIZE_IMAGE_EXACT, true);
  ?>
  <img alt="<?$fileId?>" src="<?=$file['src']?>" data-image="<?=$link?>" data-description="<?$fileId?>">
  <?}?>
</div>
<?}
}?>

<!-- Инициирующий js -->
<script type="text/javascript">
  $("#galleryUpload").click(function () {
    $.ajax({
      url:'<?=$arResult['COMPONENT_PATH']?>/ajax.php',
      type: "POST",
      data: {id: '<?=$arResult['DEAL_ID']?>'},
      success: function (html) {
        $("#ucreImageDiv").html(html);
      },
      error: function (html) {
        $("#ucreImageDiv").html("Технические неполадки! В ближайшее время все будет исправлено!");
      },
    });
  });
  
  $(document).ready(function(){
    <?foreach ($arResult['FIELDS'] as $header=>$field){
        if (count($arResult[$field])){?>
          $("#<?=$field?>").unitegallery({gallery_theme: "tilesgrid"}); 
        <?}
    }?>
  });
</script>