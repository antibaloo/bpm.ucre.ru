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
function getUserByExt($ext){
  $rsUsers = CUser::GetList(($by="ID"), ($order="desc"), array('UF_PHONE_INNER' => $ext));
  if ($arUser = $rsUsers->Fetch()){
    return $arUser['ID'];
  }else{
    return false;
  }
}
function getExtByOperName($name, $pbx_params){
  $postdata = http_build_query(array('cmd' => 'accounts','token' => $pbx_params->pbx_key));
  $opts = array('http' =>array('method'  => 'POST','header'  => 'Content-type: application/x-www-form-urlencoded','content' => $postdata));
  $context  = stream_context_create($opts); 
  $result = file_get_contents($pbx_params->pbx_url, false, $context);
  if ($result){
    $accounts = json_decode($result,true);
    foreach ($accounts as $account){
      if ($account['name'] == $name) return $account['ext'];
    }
    return false;
  } else {
    return false;
  }
}
require ($_SERVER['DOCUMENT_ROOT'] . "/bitrix/modules/main/include/prolog_before.php");
include('../mts/connector/functions.php');
CModule::IncludeModule('pull');
CModule::IncludeModule('voximplant');
$megapbx = json_decode(file_get_contents($_SERVER['DOCUMENT_ROOT']."/../megapbx_params"));
if ($_POST['crm_token'] == $megapbx->crm_key){
  $DB->Query("INSERT INTO b_megapbx_mess VALUES ('', NOW(),'".trim($_POST['callid'])."','".trim($_POST['cmd'])."','".trim($_POST['phone'])."','".trim($_POST['type'])."','".trim($_POST['user'])."','".trim($_POST['ext'])."','".trim($_POST['telnum'])."','".trim($_POST['diversion'])."','".trim($_POST['duration'])."','".trim($_POST['link'])."','".trim($_POST['status'])."')");
  if ($_POST['cmd'] == 'event'){
    $user = getUserByExt($_POST['ext']);
    if ($_POST['type'] == 'INCOMING'){
      CPullStack::AddByUser(//Выводим карточку звонка
        $user,
        array(
          'module_id' => 'voximplant',
          'command' => 'showExternalCall',
          'params' => array('callId' => $_POST['callid'], 'toUserId' => $user,'phoneNumber' => $_POST['phone'], 'crm' => ''), 
          'push' => ''
        )
      );
    }
    if ($_POST['type'] == 'ACCEPTED'){
      CPullStack::AddByUser(//Убираем карточку звонка
        $user,
        array(
          'module_id' => 'voximplant',
          'command' => 'hideExternalCall',
          'params' => array('callId' => $_POST['callid']),
          'push' => ''
        )
      );
    }
    if ($_POST['type'] == 'OUTGOING'){
      $ext_out = getExtByOperName(trim($_POST['user']), $megapbx);
      $user_out = getUserByExt($ext_out);
      CPullStack::AddByUser(//Выводим карточку звонка
        $user_out,
        array(
          'module_id' => 'voximplant',
          'command' => 'showExternalCall',
          'params' => array('callId' => $_POST['callid'], 'fromUserId' => $user_out,'phoneNumber' => $_POST['phone'], 'crm' => ''), 
          'push' => ''
        )
      );
    }
    if ($_POST['type'] == 'COMPLETED' || $_POST['type'] == 'CANCELLED'){
      CPullStack::AddByUser(//Убираем карточку 
        $user,
        array(
          'module_id' => 'voximplant',
          'command' => 'hideExternalCall',
          'params' => array('callId' => $_POST['callid']),
          'push' => ''
        )
      );
    }
  }
  if ($_POST['cmd'] == 'history'){
    if ($_POST['type'] == 'in'){}
    if ($_POST['type'] == 'out'){
      CPullStack::AddByUser(//Убираем карточку 
        $user_out,
        array(
          'module_id' => 'voximplant',
          'command' => 'hideExternalCall',
          'params' => array('callId' => $_POST['callid']),
          'push' => ''
        )
      );

    }
  }
}else {
  echo "<center><img style='margin: 0 auto;' src='images/away.jpg'></center>";
}
?>