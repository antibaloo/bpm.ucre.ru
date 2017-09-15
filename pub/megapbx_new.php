<?php
require ($_SERVER['DOCUMENT_ROOT'] . "/bitrix/modules/main/include/prolog_before.php");
include('../include/megapbx/functions.php');
CModule::IncludeModule('pull');
CModule::IncludeModule('voximplant');
CModule::IncludeModule("im");
CModule::IncludeModule("timeman");
CModule::IncludeModule('crm');
CModule::IncludeModule('search');
$megapbx = json_decode(file_get_contents($_SERVER['DOCUMENT_ROOT']."/../megapbx_params"));
if ($_POST['crm_token'] == $megapbx->crm_key){
  //$megalog = fopen('mepbx.log', 'a');
  //fwrite( $megalog, serialize($_POST)."\r\n");
  //fclose( $megalog );
  //Пишем лог сообщений АТС
  $DB->Query("INSERT INTO b_megapbx_mess VALUES ('', NOW(),'".trim($_POST['callid'])."','".trim($_POST['cmd'])."','".trim($_POST['phone'])."','".trim($_POST['type'])."','".trim($_POST['user'])."','".trim($_POST['ext'])."','".trim($_POST['telnum'])."','".trim($_POST['diversion'])."','".trim($_POST['duration'])."','".trim($_POST['link'])."','".trim($_POST['status'])."')");
  
  if (trim($_POST['cmd']) == 'contact') exit; //После сообщения типа contact записываем лог и останавливаем скрипт, в этом сообщении слишком мало информации
  
  //Определяем ответственного по звонку
  if ($_POST['ext']=='') $assignedById = (getUserByExt(getExtByOperName(trim($_POST['user']), $megapbx)))?getUserByExt(getExtByOperName(trim($_POST['user']), $megapbx)):206;
  else $assignedById = (getUserByExt(trim($_POST['ext'])))?getUserByExt(trim($_POST['ext'])):206;
  
  $phone_res = findByPhoneNumber(trim($_POST['phone']));//Ищем сущность в CRM по номеру телефона
  $prefix = (substr($_POST['phone'],0,1)=="8")?"":"+";  //Определяем префикс по первой цифре номера
  
  switch (trim($_POST['type'])){
    case "":
    case "ACCEPTED":
    case "COMPLETED":
    case "INCOMING":
    case "in":
      $callDirection = 2;
      $callDirectionText = 'входящему звонку с номера ';
      break;
    case "out":
    case "OUTGOING":
      $callDirection = 1;
      $callDirectionText = 'исходящему звонку на номер ';
      break;
  }
  switch ($_POST['diversion']){
    case '79325360157':
      $SOURCE_ID = 'WEB';
      break;
    case '79325360657':
      $SOURCE_ID = '10';
      break;
    case '79228299057':
      $SOURCE_ID = 'WEB';
      break;
    default:
      if ($_POST['diversion'] ==""){
        $SOURCE_ID = "3";
      }else{
        $SOURCE_ID = "CALL";
      }
      break;
  }
  
  if ($phone_res['FOUND'] == 'N'){//Если не найдена сущность, то ее надо создать
    $entityType = 'LEAD';
    $oLead = new CCrmLead;
    $arFields = array(
      "TITLE" => "Лид по ".$callDirectionText.$prefix.substr($_POST['phone'],0,1)."(".substr($_POST['phone'],1,3).")".substr($_POST['phone'],4,3)."-".substr($_POST['phone'],7,2)."-".substr($_POST['phone'],9)." на ВАТС Мегафон",
      "NAME" => "неизвестно",
      "COMMENTS" => "",
      "SOURCE_ID" => $SOURCE_ID,
      "OPPORTUNITY" =>0,
      "CURRENCY_ID" => "RUB",
      "OPPORTUNITY_ACCOUNT" => 0,
      "ACCOUNT_CURRENCY_ID" => "RUB",
      "LAST_NAME" => "",
      "SECOND_NAME" => "",
      "COMPANY_TITLE" => "",
      "POST" => "",
      "SOURCE_DESCRIPTION" =>"Создан модулем сопряжения ВАТС Мегафон",
      "STATUS_ID" => "NEW",
      "UF_CRM_1486022615" => 1317,
      "ASSIGNED_BY_ID" => $assignedById,
      "FM" => array("PHONE" => array("n0" => array("VALUE" => $prefix.substr($_POST['phone'],0,1)."(".substr($_POST['phone'],1,3).")".substr($_POST['phone'],4,3)."-".substr($_POST['phone'],7,2)."-".substr($_POST['phone'],9),"VALUE_TYPE" => "OTHER"))),
    );
    $entityId = $oLead->Add($arFields, true, array('CURRENT_USER' => 24));
  }else{//А если найдена, запоминаем тип и идентификатор
    if ($phone_res['LEAD']['ID'] > 0) {
      $entityType = 'LEAD';
      $entityId = $phone_res['LEAD']['ID'];
    }elseif ($phone_res['CONTACT']['ID'] > 0){
      $entityType = 'CONTACT';
      $entityId = $phone_res['CONTACT']['ID'];
    }elseif ($phone_res['COMPANY']['ID'] > 0){
      $entityType = 'COMPANY';
      $entityId = $phone_res['COMPANY']['ID'];
    }
  }
   
  $restHelper = new \Bitrix\Voximplant\Rest\Helper;
  if (getBitrixByMegapbx(trim($_POST['callid']))){
    //Если найдено соответствие, то ничего не делаем
  }else{//Если не найдено соответствие, то регистрируем звонок
    $callParams = array(
      'USER_ID' =>$assignedById,
      'PHONE_NUMBER'   => trim($_POST['phone']),
      'CRM_ENTITY_TYPE' => $entityType,
      'CRM_ENTITY_ID' => $entityId,
      'CRM_CREATE' => true,
      'TYPE' => $callDirection,
      'CRM_SOURCE' => $SOURCE_ID,
      'SHOW' => true,
    );
    $registerResults = $restHelper-> registerExternalCall($callParams);
    $registerData = $registerResults->getData();
    if($registerData['CALL_ID']) linkCallIds(trim($_POST['callid']),$registerData['CALL_ID']);//Запоминаем соответствие callid
  }
  
  if ($_POST['cmd'] == 'history'){
    switch (trim($_POST['status'])){
      case "Success":
        $statusCode = 200;
        break;
      case "missed":
        $statusCode = 304;
        break;
      case "Busy":
        $statusCode = 486;
        break;
      case "NotAvailable":
      case "Cancel":
        $statusCode = 480;
        break;
      case "NotAllowed":
        $statusCode = 403;
        break;
    }
    $callParams = array(
      'CALL_ID' => getBitrixByMegapbx(trim($_POST['callid'])),
      'DURATION' => $_POST['duration'],
      'USER_ID' => $assignedById,
      'ADD_TO_CHAT' => true,
      'RECORD_URL' => trim($_POST['link']),
      'STATUS_CODE' => $statusCode,
    );
    $finishResults = $restHelper->finishExternalCall($callParams);
    $finishData = $finishResults->getData();
    if ($statusCode != 200){
      $oActivity = new CCrmActivity;
      $acFields = array('COMPLETED' => 'N');
      $oActivity->Update($finishData['CRM_ACTIVITY_ID'], $acFields,true, true, array('CURRENT_USER' => 24));
    }
    if ($assignedById == 206){
      $notifyMess = "Неотвеченный вызов в нерабочее время в сущности ";
      if ($entityType = 'LEAD') $notifyMess.='<a href="/crm/lead/show/'.$entityId.'/">Лид №'.$entityId.'</a>';
      if ($entityType = 'CONTACT') $notifyMess.='<a href="/crm/contact/show/'.$entityId.'/">Контакт №'.$entityId.'</a>';
      if ($entityType = 'COMPANY') $notifyMess.='<a href="/crm/company/show/'.$entityId.'/">Компания №'.$entityId.'</a>';
      $arMessageFields = array(
          // получатель
          "TO_USER_ID" => 98,
          // отправитель
          "FROM_USER_ID" => 206, 
          // тип уведомления
          "NOTIFY_TYPE" => IM_NOTIFY_SYSTEM,
          // модуль запросивший отправку уведомления
          "NOTIFY_MODULE" => "crm",
          // символьный тэг для группировки (будет выведено только одно сообщение), если это не требуется - не задаем параметр
          //"NOTIFY_TAG" => "CRM|LEAD|NEW|MEGAPBX",
          // текст уведомления на сайте (доступен html и бб-коды)
          "NOTIFY_MESSAGE" => $notifyMess,
        );
      CIMNotify::Add($arMessageFields);
      $arMessageFields = array(
          // получатель
          "TO_USER_ID" => 203,
          // отправитель
          "FROM_USER_ID" => 206, 
          // тип уведомления
          "NOTIFY_TYPE" => IM_NOTIFY_SYSTEM,
          // модуль запросивший отправку уведомления
          "NOTIFY_MODULE" => "crm",
          // символьный тэг для группировки (будет выведено только одно сообщение), если это не требуется - не задаем параметр
          //"NOTIFY_TAG" => "CRM|LEAD|NEW|MEGAPBX",
          // текст уведомления на сайте (доступен html и бб-коды)
          "NOTIFY_MESSAGE" => $notifyMess,
        );
      CIMNotify::Add($arMessageFields);
    }
  }
}else{
  echo "<center><img style='margin: 0 auto;' src='images/away.jpg'></center>";
}
?>