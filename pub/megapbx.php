<?php
require ($_SERVER['DOCUMENT_ROOT'] . "/bitrix/modules/main/include/prolog_before.php");
include('../mts/connector/functions.php');
CModule::IncludeModule('pull');
CModule::IncludeModule('voximplant');
CModule::IncludeModule("im");
CModule::IncludeModule("timeman");
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
  $rsUsers = CUser::GetList(($by="ID"), ($order="desc"), array('ACTIVE' => 'Y','GROUPS_ID' => array(12), 'UF_MEGAPBX' => $ext));
  if ($rsUsers->SelectedRowsCount() == 0) return false;
  if ($rsUsers->SelectedRowsCount() == 1) {
    $arUser = $rsUsers->Fetch();
    return $arUser['ID'];
  }
  if ($rsUsers->SelectedRowsCount() > 1) {
    $results = array();
    while ($arUser = $rsUsers->Fetch()){
      $TimemanUser = new CTimeManUser($arUser['ID']);
      if ($TimemanUser->State()=='OPEN') $results[] = $arUser['ID'];
    }
    return $results;
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

$megapbx = json_decode(file_get_contents($_SERVER['DOCUMENT_ROOT']."/../megapbx_params"));
if ($_POST['crm_token'] == $megapbx->crm_key){
  $DB->Query("INSERT INTO b_megapbx_mess VALUES ('', NOW(),'".trim($_POST['callid'])."','".trim($_POST['cmd'])."','".trim($_POST['phone'])."','".trim($_POST['type'])."','".trim($_POST['user'])."','".trim($_POST['ext'])."','".trim($_POST['telnum'])."','".trim($_POST['diversion'])."','".trim($_POST['duration'])."','".trim($_POST['link'])."','".trim($_POST['status'])."')");
  if ($_POST['cmd'] == 'event'){
    $user = getUserByExt($_POST['ext']);
    if ($_POST['type'] == 'INCOMING'){
      /*CPullStack::AddByUser(//Выводим карточку звонка
        $user,
        array(
          'module_id' => 'voximplant',
          'command' => 'showExternalCall',
          'params' => array('callId' => $_POST['callid'], 'toUserId' => $user,'phoneNumber' => $_POST['phone'], 'crm' => ''), 
          'push' => ''
        )
      );*/
    }
    if ($_POST['type'] == 'ACCEPTED'){
      /*CPullStack::AddByUser(//Убираем карточку звонка
        $user,
        array(
          'module_id' => 'voximplant',
          'command' => 'hideExternalCall',
          'params' => array('callId' => $_POST['callid']),
          'push' => ''
        )
      );
      $crm_data = CVoxImplantCrmHelper::GetDataForPopup($_POST['callid'],$_POST['phone'],$user);
      CPullStack::AddByUser(//Выводим карточку звонка c данными поиска CRM
        $user,
        array(
          'module_id' => 'voximplant',
          'command' => 'showExternalCall',
          'params' => array('callId' => $_POST['callid'], 'toUserId' => $user,'phoneNumber' => $_POST['phone'], 'crm' => $crm_data), 
          'push' => ''
        )
      );*/
    }
    if ($_POST['type'] == 'OUTGOING'){
      /*$ext_out = getExtByOperName(trim($_POST['user']), $megapbx);
      $user_out = getUserByExt($ext_out);
      
       $test_pbx = fopen('test_pbx.log', 'a');
       fwrite( $test_pbx, serialize(array('user'=>trim($_POST['user']), 'ext' => $ext_out, 'user_id' => $user_out)) ."\r\n");
       fclose( $test_pbx );
      
      CPullStack::AddByUser(//Выводим карточку звонка
        $user_out,
        array(
          'module_id' => 'voximplant',
          'command' => 'showExternalCall',
          'params' => array('callId' => $_POST['callid'], 'fromUserId' => $user_out,'phoneNumber' => $_POST['phone'], 'crm' => ''), 
          'push' => ''
        )
      );*/
    }
    if ($_POST['type'] == 'COMPLETED' || $_POST['type'] == 'CANCELLED'){
      /*CPullStack::AddByUser(//Убираем карточку 
        $user,
        array(
          'module_id' => 'voximplant',
          'command' => 'hideExternalCall',
          'params' => array('callId' => $_POST['callid']),
          'push' => ''
        )
      );*/
    }
  }
  if ($_POST['cmd'] == 'history'){
    if ($_POST['type'] == 'in'){
      $crm_data = CVoxImplantCrmHelper::GetDataForPopup($_POST['callid'],$_POST['phone']);
      if ($crm_data['FOUND'] == 'N'){//Нет записей этого телефона в лида/контактах
        $employee = isEmployee($_POST['phone']);
        if ($employee){//Служебный телефон сотрудника
          $rsUser = CUser::GetByID($employee);
          $arUser = $rsUser->Fetch();
          //Сообщение сотруднику во избежании повторных звонков на номер автосекретаря
          $arMessageFields = array(
            "TO_USER_ID" => $employee, // получатель
            "FROM_USER_ID" => 0,// отправитель (может быть >0)
            "NOTIFY_TYPE" => IM_NOTIFY_SYSTEM, // тип уведомления
            "NOTIFY_MODULE" => "im", // модуль запросивший отправку уведомления
            "NOTIFY_TAG" => "IM_CONFIG_NOTICE", // символьный тэг для группировки (будет выведено только одно сообщение), если это не требуется - не задаем параметр
            "NOTIFY_MESSAGE" => '[b]Предупреждение:[/b] '.$arUser['LAST_NAME'].' '.$arUser['NAME'].', вы позвонили на публичный номер компании +'.(($_POST['diversion'])?$_POST['diversion']:$_POST['user']).', что строжайше запрещено, для общения с сотрудниками, необходимо набирать прямой номер!'// текст уведомления на сайте (доступен html и бб-коды)
          );
          CIMNotify::Add($arMessageFields);
          //Сообщение руководителю
          $arMessageFields = array(
            "TO_USER_ID" => 1, // получатель
            "FROM_USER_ID" => 0,// отправитель (может быть >0)
            "NOTIFY_TYPE" => IM_NOTIFY_SYSTEM, // тип уведомления
            "NOTIFY_MODULE" => "im", // модуль запросивший отправку уведомления
            "NOTIFY_TAG" => "IM_CONFIG_NOTICE", // символьный тэг для группировки (будет выведено только одно сообщение), если это не требуется - не задаем параметр
            "NOTIFY_MESSAGE" => '[b]Сообщение:[/b] Сотрудник: [url=/company/personal/user/'.$employee.'/]'.$arUser['LAST_NAME'].' '.$arUser['NAME'].'[/url], позвонил на публичный номер компании +'.(($_POST['diversion'])?$_POST['diversion']:$_POST['user']).', что строжайше запрещено, для общения с сотрудниками, необходимо набирать прямой номер!'// текст уведомления на сайте (доступен html и бб-коды)
          );
          CIMNotify::Add($arMessageFields);
        }else{
          if (trim($_POST['status']) == 'Succsess'){}
          if (trim($_POST['status']) == 'missed'){}
        }
      }
      if ($crm_data['FOUND'] == 'Y'){}
    }
    if ($_POST['type'] == 'out'){
      /*CPullStack::AddByUser(//Убираем карточку 
        $user_out,
        array(
          'module_id' => 'voximplant',
          'command' => 'hideExternalCall',
          'params' => array('callId' => $_POST['callid']),
          'push' => ''
        )
      );*/

    }
  }
}else {
  echo "<center><img style='margin: 0 auto;' src='images/away.jpg'></center>";
}
?>