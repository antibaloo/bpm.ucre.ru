<?php
require_once($_SERVER['DOCUMENT_ROOT'] . "/bitrix/modules/main/include/prolog_before.php");
if (strripos ($_SERVER['HTTP_REFERER'], 'bpm.ucre.ru')!==false){
  CModule::IncludeModule("crm");
  $ifp = fopen( $_SERVER["DOCUMENT_ROOT"]."/upload/temp_photo.png", 'wb'); 
  $data = explode( ',', $_POST['photo'] );
  fwrite( $ifp, base64_decode( $data[ 1 ] ) );
  fclose( $ifp );
  $arPhoto = CFile::MakeFileArray($_SERVER["DOCUMENT_ROOT"]."/upload/temp_photo.png");
  $arContact=new CCrmContact(false);
  $arFields = array('PHOTO' => $arPhoto);
  $arContact->Update($_POST['id'],$arFields);
  unlink($_SERVER["DOCUMENT_ROOT"]."/upload/temp_photo.png");
}
?>