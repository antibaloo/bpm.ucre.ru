<?php
if(!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED!==true) die();
//echo "<pre>";print_r($arResult);echo "</pre>";
?>
<div id="<?=$arResult['TAB_ID']?>">
<?if (CUSER::GetID() == 24){?>
<center><input id="edit_<?=$arResult['TAB_ID']?>" type="button" value="Редактировать"></center>
<?}?>
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
</div>  

<!-- Инициирующий js -->
<script type="text/javascript">
$(document).ready(function(){
<?foreach ($arResult['FIELDS'] as $header=>$field){
  if (count($arResult[$field])){?>
 $("#<?=$field?>").unitegallery({gallery_theme: "tilesgrid"}); 
<?}
}?>
});
  $("#edit_<?=$arResult['TAB_ID']?>").click(function () {
    var data = "!!!!!";
    $.ajax({
      url: "<?=$arResult['COMPONENT_PATH']?>/ajax.php",
      type: "POST",
      data: data,
      dataType: "text",
      success: function (html) {
        $("#<?=$arResult['TAB_ID']?>").html(html);
      },
      error: function (html) {
        $('#<?=$arResult['TAB_ID']?>').html("Технические неполадки! В ближайшее время все будет исправлено!");
      },
    });
  });  
</script>