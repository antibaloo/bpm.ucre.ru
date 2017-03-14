<?php
require ($_SERVER['DOCUMENT_ROOT'] . "/bitrix/modules/main/include/prolog_before.php");
$megapbx = json_decode(file_get_contents($_SERVER['DOCUMENT_ROOT']."/../megapbx_params"));
$postdata = file_get_contents("php://input");

if ($postdata!=''){
  $megapbx_log = fopen('megapbx.log', 'a');
  fwrite( $megapbx_log, $postdata ."\r\n");
  fclose( $megapbx_log );
}
?>