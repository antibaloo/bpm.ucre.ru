<?php
require ($_SERVER['DOCUMENT_ROOT'] . "/bitrix/modules/main/include/prolog_before.php");
include('../include/megapbx/functions.php');
CModule::IncludeModule('pull');
CModule::IncludeModule('voximplant');
CModule::IncludeModule("im");
CModule::IncludeModule("timeman");
CModule::IncludeModule('crm');
CModule::IncludeModule('search');
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
      $results[] = $arUser['ID'];
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
  if ($_POST['cmd'] == 'contact'){
    $phone_res = findByPhoneNumber(trim($_POST['phone']));
    if ($phone_res['FOUND'] == 'N'){
      //Задаем параметры лида
      $oLead = new CCrmLead;
      $arFields = array(
        "TITLE" => "Входящий звонок с номера +7(".substr($_POST['phone'],1,3).")".substr($_POST['phone'],4,3)."-".substr($_POST['phone'],7,2)."-".substr($_POST['phone'],9)." на ВАТС Мегафон",
        "NAME" => "неизвестно",
        "COMMENTS" => "",
        "SOURCE_ID" => "CALL",
        "OPPORTUNITY" =>0,
        "CURRENCY_ID" => "RUB",
        "OPPORTUNITY_ACCOUNT" => 0,
        "ACCOUNT_CURRENCY_ID" => "RUB",
        "LAST_NAME" => "",
        "SECOND_NAME" => "",
        "COMPANY_TITLE" => "",
        "POST" => "",
        "SOURCE_DESCRIPTION" =>"Создан модулем сопряжения ВАТС Мегофон",
        "STATUS_ID" => "NEW",
        "UF_CRM_1486022615" => 1317,
        "ASSIGNED_BY_ID" => 206,
        "FM" => array("PHONE" => array("n0" => array("VALUE" => "+7(".substr($_POST['phone'],1,3).")".substr($_POST['phone'],4,3)."-".substr($_POST['phone'],7,2)."-".substr($_POST['phone'],9),"VALUE_TYPE" => "OTHER"))),
      );
      //Создаем лид
      $LeadId = $oLead->Add($arFields, true, array('CURRENT_USER' => 24));
      //Сообщение ответственным лицам
      $arMessageFields = array(
        // получатель
        "TO_USER_ID" => 98,
        // отправитель
        "FROM_USER_ID" => 0, 
        // тип уведомления
        "NOTIFY_TYPE" => IM_NOTIFY_SYSTEM,
        // модуль запросивший отправку уведомления
        "NOTIFY_MODULE" => "crm",
        // символьный тэг для группировки (будет выведено только одно сообщение), если это не требуется - не задаем параметр
        //"NOTIFY_TAG" => "CRM|LEAD|NEW|MEGAPBX",
        // текст уведомления на сайте (доступен html и бб-коды)
        "NOTIFY_MESSAGE" => "[b]Создан новый лид по входящему звонку[/b] с номера +7(".substr($_POST['phone'],1,3).")".substr($_POST['phone'],4,3)."-".substr($_POST['phone'],7,2)."-".substr($_POST['phone'],9)." на ВАТС Мегафон, перед принятием в работу необходимо перевести его на себя. <a href='/crm/lead/show/".$LeadId."/' target='_blank'>Перейти к лиду</a>",
      );
      CIMNotify::Add($arMessageFields);
      $arMessageFields = array(
        // получатель
        "TO_USER_ID" => 202,
        // отправитель
        "FROM_USER_ID" => 0, 
        // тип уведомления
        "NOTIFY_TYPE" => IM_NOTIFY_SYSTEM,
        // модуль запросивший отправку уведомления
        "NOTIFY_MODULE" => "crm",
        // символьный тэг для группировки (будет выведено только одно сообщение), если это не требуется - не задаем параметр
        //"NOTIFY_TAG" => "CRM|LEAD|NEW|MEGAPBX",
        // текст уведомления на сайте (доступен html и бб-коды)
        "NOTIFY_MESSAGE" => "[b]Создан новый лид по входящему звонку[/b] с номера +7(".substr($_POST['phone'],1,3).")".substr($_POST['phone'],4,3)."-".substr($_POST['phone'],7,2)."-".substr($_POST['phone'],9)." на ВАТС Мегафон, перед принятием в работу необходимо перевести его на себя. <a href='/crm/lead/show/".$LeadId."/' target='_blank'>Перейти к лиду</a>",
      );
      CIMNotify::Add($arMessageFields);
      $arMessageFields = array(
        // получатель
        "TO_USER_ID" => 24,
        // отправитель
        "FROM_USER_ID" => 0, 
        // тип уведомления
        "NOTIFY_TYPE" => IM_NOTIFY_SYSTEM,
        // модуль запросивший отправку уведомления
        "NOTIFY_MODULE" => "crm",
        // символьный тэг для группировки (будет выведено только одно сообщение), если это не требуется - не задаем параметр
        //"NOTIFY_TAG" => "CRM|LEAD|NEW|MEGAPBX",
        // текст уведомления на сайте (доступен html и бб-коды)
        "NOTIFY_MESSAGE" => "[b]Создан новый лид по входящему звонку[/b] с номера +7(".substr($_POST['phone'],1,3).")".substr($_POST['phone'],4,3)."-".substr($_POST['phone'],7,2)."-".substr($_POST['phone'],9)." на ВАТС Мегафон, перед принятием в работу необходимо перевести его на себя. <a href='/crm/lead/show/".$LeadId."/' target='_blank'>Перейти к лиду</a>",
      );
      CIMNotify::Add($arMessageFields);
    }
  }
  if ($_POST['cmd'] == 'history' && $_POST['type'] == 'in'){
    $phone_res = findByPhoneNumber(trim($_POST['phone']));
    if ($phone_res['Y'] && $phone_res['LEAD']['ID']>0){
      $entity_type = 'LEAD';
      $begintime = beginTimeIncoming($_POST['callid']);
      $entity_id = $phone_res['LEAD']['ID'];
      $arBindings[] = array('OWNER_TYPE_ID' => CCrmOwnerType::ResolveID($entity_type),
                            'OWNER_ID' => $entity_id
                           );
      $arFields = array(
        'OWNER_ID' => $entity_id,
        'OWNER_TYPE_ID' => CCrmOwnerType::ResolveID($entity_type),
        'TYPE_ID' =>  CCrmActivityType::Call,
        'SUBJECT' => 'Входящий звонок с номера +7('.substr($_POST['phone'],1,3).")".substr($_POST['phone'],4,3)."-".substr($_POST['phone'],7,2)."-".substr($_POST['phone'],9),
        'START_TIME' => date("d.m.Y H:i:s",strtotime($begintime['TIME'])),
        'END_TIME' => date("d.m.Y H:i:s",strtotime($begintime['TIME'])+$_POST['duration']),
        'COMPLETED' => ($_POST['status'] == 'Success')?'Y':'N',
        'RESPONSIBLE_ID' => 206,
        'PRIORITY' => CCrmActivityPriority::Medium,
        'DESCRIPTION' => ($_POST['link']?"Запись входящего звонка прилагается":"Необходимо заполнить!!!"),
        'DESCRIPTION_TYPE' => CCrmContentType::Html,
        'DIRECTION' => CCrmActivityDirection::Incoming,
        'LOCATION' => '',
        'PROVIDER_DATA' => ($_POST['link']?$_POST['link']:""), 
        'NOTIFY_TYPE' => CCrmActivityNotifyType::None,
        'BINDINGS' => array_values($arBindings)
      );
      CCrmActivity::Add($arFields, false, false, array('REGISTER_SONET_EVENT' => true));
    }
  }
}else {
  echo "<center><img style='margin: 0 auto;' src='images/away.jpg'></center>";
}
?>