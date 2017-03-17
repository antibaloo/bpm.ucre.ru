<?php
function isEmployee ($phone){
  global $DB;
  $temp_phone = substr($phone,1);
  $result = $DB->Query("SELECT ID FROM b_user WHERE ACTIVE='Y' AND (REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(PERSONAL_PHONE,'+',''),' ',''),'(',''),')',''),'-','') LIKE '%".$temp_phone."' OR REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(PERSONAL_MOBILE,'+',''),' ',''),'(',''),')',''),'-','') LIKE '%".$temp_phone."' OR REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(WORK_PHONE,'+',''),' ',''),'(',''),')',''),'-','') LIKE '%".$temp_phone."');");
  if ($result->SelectedRowsCount() == 0) {
    return false;
  }else {
    $arUser = $result->Fetch();
    return $arUser['ID'];
  }
}
require ($_SERVER['DOCUMENT_ROOT'] . "/bitrix/modules/main/include/prolog_before.php");
include('../mts/connector/functions.php');
CModule::IncludeModule('pull');
CModule::IncludeModule('voximplant');
$megapbx = json_decode(file_get_contents($_SERVER['DOCUMENT_ROOT']."/../megapbx_params"));
if ($_POST['crm_token'] == $megapbx->crm_key){
  $DB->Query("INSERT INTO b_megapbx_mess VALUES ('', NOW(),'".$_POST['callid']."','".$_POST['cmd']."','".$_POST['phone']."','".$_POST['type']."','".$_POST['user']."','".$_POST['ext']."','".$_POST['telnum']."','".$_POST['diversion']."','".$_POST['duration']."','".$_POST['link']."','".$_POST['status']."')");
  if ($_POST['cmd'] == 'event'){
    echo "";
  }
  if ($_POST['cmd'] == 'history'){
    echo "";
  }
}else {
  echo "<center><img style='margin: 0 auto;' src='images/away.jpg'></center>";
}
?>