<?php
if(!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED!==true) die();
echo "<pre>";print_r($arResult);echo "</pre>";
?>
<form id="saveImgForm">
  <input type='hidden' name='deal_id' value='<?=$arResult['DEAL_ID']?>'>
<?
foreach($arResult['FIELDS'] as $header=>$field){
  echo "<center><h2>".$header."</h2></center>";
  if (class_exists('\Bitrix\Main\UI\FileInput', true)){
    echo \Bitrix\Main\UI\FileInput::createInstance(array(
      "name" => $field."[#IND#]",
      "description" => false,
      "upload" => true,
      "allowUpload" => "I",
      "medialib" => true,
      "fileDialog" => true,
      "cloud" => true,
      "delete" => true,
      "maxCount" => 20
    ))->show(count($arResult[$field])?$arResult[$field]:0);
    echo "<input type='hidden' name='".$field."_old' value='".implode("|",$arResult[$field])."'>";//Массив старых значений для сравнения с полученными обработки файлов
  }else{
    echo CFileInput::Show(
      $field.'[#IND#]', 
      count($arResult[$field])?$arResult[$field]:0,
      array(
        "IMAGE" => "Y",
        "PATH" => "Y",
        "FILE_SIZE" => "Y",
        "DIMENSIONS" => "Y",
        "IMAGE_POPUP" => "Y",
        "MAX_SIZE" => array(
          "W" => 150,
          "H" => 150,
        ),
      ), array(
        'upload' => true,
        'medialib' => true,
        'file_dialog' => true,
        'cloud' => true,
        'del' => true,
        'description' => false,
      )
    );
    echo "<input type='hidden' name='".$field."_old' value='".serialize($arResult[$field])."'>";//Массив старых значений для сравнения с полученными обработки файлов
  }
}
?>
  <div id="saveResults"></div>
  <div class="buttonWrapper">
    <div class="empty"></div>
    <div class="empty"></div>
    <div class="empty"></div>
    <div class="empty"></div>
    <div class="saveButton formButton" action="save">Сохранить</div>
    <div class="cancelButton formButton" action="cancel">Отмена</div>
    <div class="empty"></div>
    <div class="empty"></div>
    <div class="empty"></div>
    <div class="empty"></div>
  </div>
</form>
<script>
  $(".formButton").click(function () {
    if ($(this).attr("action") == 'cancel'){
      document.location.href = '/crm/deal/details/<?=$arResult['DEAL_ID']?>/';
    }else{
      $.ajax({
        url:'<?=$arResult['COMPONENT_PATH']?>/ajax.php',
        type: "POST",
        data: $("#saveImgForm").serialize(),
        success: function (html) {
          $("#saveResults").html(html);
        },
        error: function (html) {
          $("#saveResults").html("Технические неполадки! В ближайшее время все будет исправлено!");
        },
      });
    }
  });  
</script>