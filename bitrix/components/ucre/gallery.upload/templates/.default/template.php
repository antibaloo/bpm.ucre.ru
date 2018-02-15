<?php
if(!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED!==true) die();
//echo "<pre>";print_r($arResult);echo "</pre>";
?>
<?foreach($arResult['FIELDS'] as $header=>$field){?>
<center><h2><?=$header?></h2></center>
<?
  if (class_exists('\Bitrix\Main\UI\FileInput', true)){
    echo \Bitrix\Main\UI\FileInput::createInstance(array(
      "name" => $field."[#IND#]",
      "description" => true,
      "upload" => true,
      "allowUpload" => "I",
      "medialib" => true,
      "fileDialog" => true,
      "cloud" => true,
      "delete" => true,
      "maxCount" => 20
    ))->show(count($arResult[$field])?$arResult[$field]:0);
  }else{
    echo CFileInput::Show($field."[#IND#]", count($arResult[$field])?$arResult[$field]:0,
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
                            'description' => true,
                          )
                         );
  }
?>
<?}?>