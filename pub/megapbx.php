<?php
require ($_SERVER['DOCUMENT_ROOT'] . "/bitrix/modules/main/include/prolog_before.php");
$megapbx = json_decode(file_get_contents($_SERVER['DOCUMENT_ROOT']."/../megapbx_params"));
$postdata = file_get_contents("php://input");
if ($_POST['crm_token'] == $megapbx->crm_key){
  $DB->Query("INSERT INTO b_megapbx_log VALUES ('', NOW(),'".serialize($_POST)."')");
}else {
  echo "<center><img style='margin: 0 auto;' src='images/away.jpg'></center>";
}
?>