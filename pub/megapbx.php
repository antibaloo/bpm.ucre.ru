<?php
require ($_SERVER['DOCUMENT_ROOT'] . "/bitrix/modules/main/include/prolog_before.php");
$megapbx = json_decode(file_get_contents($_SERVER['DOCUMENT_ROOT']."/../megapbx_params"));
$postdata = file_get_contents("php://input");
if ($_POST['crm_token'] == $megapbx->crm_key){
  echo "Yes";
  $DB->Query("INSERT INTO b_megapbx_log VALUES ('', NOW(),'".serialize($_POST)."')");
  $megapbx_log = fopen('megapbx.log', 'a');
  fwrite( $megapbx_log, $postdata ."\r\n");
  fclose( $megapbx_log );
}
?>