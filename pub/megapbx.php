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
  $DB->Query("INSERT INTO b_megapbx_mess VALUES ('', NOW(),'".trim($_POST['callid'])."','".trim($_POST['cmd'])."','".trim($_POST['phone'])."','".trim($_POST['type'])."','".trim($_POST['user'])."','".trim($_POST['ext'])."','".trim($_POST['telnum'])."','".trim($_POST['diversion'])."','".trim($_POST['duration'])."','".trim($_POST['link'])."','".trim($_POST['status'])."')");
  if (getBitrixByMegapbx(trim($_POST['callid']))){
    //Если найдено соответствие, то ничего не делаем
  }else{
    $restHelper = new \Bitrix\Voximplant\Rest\Helper;
    $assignedById = (getUserByExt(trim($_POST['ext'])))?getUserByExt(trim($_POST['ext'])):206;
    switch (trim($_POST['type'])){
      case "":
      case "ACCEPTED":
      case "COMPLETED":
      case "INCOMING":
      case "in":
        $callDirection = 2;
        break;
      case "out":
      case "OUTGOING":
        $callDirection = 1;
        break;
    }
    $callParams = array(
      'USER_ID' =>$assignedById,
      'PHONE_NUMBER'   => trim($_POST['phone']),
      'CRM_CREATE' => false,
      'TYPE' => $callDirection,
    );
    $registerResults = $restHelper-> registerExternalCall($callParams);
    $registerData = $registerResults->getData();
    if($registerData['CALL_ID']) linkCallIds(trim($_POST['callid']),$registerData['CALL_ID']);
  }
  $phone_res = findByPhoneNumber(trim($_POST['phone']));
  $prefix = (substr($_POST['phone'],0,1)=="8")?"":"+";
  if ($_POST['cmd'] == 'event' && $_POST['type'] == 'INCOMING'){
    $assignedById = (getUserByExt(trim($_POST['ext'])))?getUserByExt(trim($_POST['ext'])):206;
    if ($phone_res['FOUND'] == 'N'){
      //Задаем параметры лида
      $oLead = new CCrmLead;
      //79325360157 - ИРР - 13
      //79325360657 - Авито - 14
      //79228299057 - Веб-сайт - WEB
      switch ($_POST['diversion']){
        case '79325360157':
          $SOURCE_ID = '13';
          break;
        case '79325360657':
          $SOURCE_ID = '10';
          break;
        case '79228299057':
          $SOURCE_ID = 'WEB';
          break;
        default:
          $SOURCE_ID = "SELF";
          break;
      }
      $arFields = array(
        "TITLE" => "Лид по входящему звонку с номера ".$prefix.substr($_POST['phone'],0,1)."(".substr($_POST['phone'],1,3).")".substr($_POST['phone'],4,3)."-".substr($_POST['phone'],7,2)."-".substr($_POST['phone'],9)." на ВАТС Мегафон",
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
      //Создаем лид
      $LeadId = $oLead->Add($arFields, true, array('CURRENT_USER' => 24));
      //Сообщение ответственным лицам
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
        "NOTIFY_MESSAGE" => "[b]Создан новый лид №".$LeadId." по входящему звонку[/b] с номера ".$prefix.substr($_POST['phone'],0,1)."(".substr($_POST['phone'],1,3).")".substr($_POST['phone'],4,3)."-".substr($_POST['phone'],7,2)."-".substr($_POST['phone'],9)." на ВАТС Мегафон. <a href='/crm/lead/show/".$LeadId."/' target='_blank'>Перейти к лиду</a>",
      );
      CIMNotify::Add($arMessageFields);
      $arMessageFields = array(
        // получатель
        "TO_USER_ID" => 202,
        // отправитель
        "FROM_USER_ID" => 206, 
        // тип уведомления
        "NOTIFY_TYPE" => IM_NOTIFY_SYSTEM,
        // модуль запросивший отправку уведомления
        "NOTIFY_MODULE" => "crm",
        // символьный тэг для группировки (будет выведено только одно сообщение), если это не требуется - не задаем параметр
        //"NOTIFY_TAG" => "CRM|LEAD|NEW|MEGAPBX",
        // текст уведомления на сайте (доступен html и бб-коды)
        "NOTIFY_MESSAGE" => "[b]Создан новый лид №".$LeadId." по входящему звонку[/b] с номера ".$prefix.substr($_POST['phone'],0,1)."(".substr($_POST['phone'],1,3).")".substr($_POST['phone'],4,3)."-".substr($_POST['phone'],7,2)."-".substr($_POST['phone'],9)." на ВАТС Мегафон. <a href='/crm/lead/show/".$LeadId."/' target='_blank'>Перейти к лиду</a>",
      );
      CIMNotify::Add($arMessageFields);
      $arMessageFields = array(
        // получатель
        "TO_USER_ID" => 24,
        // отправитель
        "FROM_USER_ID" => 206, 
        // тип уведомления
        "NOTIFY_TYPE" => IM_NOTIFY_SYSTEM,
        // модуль запросивший отправку уведомления
        "NOTIFY_MODULE" => "crm",
        // символьный тэг для группировки (будет выведено только одно сообщение), если это не требуется - не задаем параметр
        //"NOTIFY_TAG" => "CRM|LEAD|NEW|MEGAPBX",
        // текст уведомления на сайте (доступен html и бб-коды)
        "NOTIFY_MESSAGE" => "[b]Создан новый лид №".$LeadId." по входящему звонку[/b] с номера ".$prefix.substr($_POST['phone'],0,1)."(".substr($_POST['phone'],1,3).")".substr($_POST['phone'],4,3)."-".substr($_POST['phone'],7,2)."-".substr($_POST['phone'],9)." на ВАТС Мегафон. <a href='/crm/lead/show/".$LeadId."/' target='_blank'>Перейти к лиду</a>",
      );
      CIMNotify::Add($arMessageFields);
    }
  }
  if ($_POST['cmd'] == 'event' && $_POST['type'] == 'OUTGOING'){
    $assignedById = (getUserByExt(getExtByOperName(trim($_POST['user']), $megapbx)))?getUserByExt(getExtByOperName(trim($_POST['user']), $megapbx)):206;
    if ($phone_res['FOUND'] == 'N'){
      $oLead = new CCrmLead;
      $arFields = array(
        "TITLE" => "Лид по исходящему звонку на  номер ".$prefix.substr($_POST['phone'],0,1)."(".substr($_POST['phone'],1,3).")".substr($_POST['phone'],4,3)."-".substr($_POST['phone'],7,2)."-".substr($_POST['phone'],9)." на ВАТС Мегафон",
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
        "SOURCE_DESCRIPTION" =>"Создан модулем сопряжения ВАТС Мегафон",
        "STATUS_ID" => "NEW",
        "UF_CRM_1486022615" => 1317,
        "ASSIGNED_BY_ID" => $assignedById,
        "FM" => array("PHONE" => array("n0" => array("VALUE" => $prefix.substr($_POST['phone'],0,1)."(".substr($_POST['phone'],1,3).")".substr($_POST['phone'],4,3)."-".substr($_POST['phone'],7,2)."-".substr($_POST['phone'],9),"VALUE_TYPE" => "OTHER"))),
      );
      //Создаем лид
      $LeadId = $oLead->Add($arFields, true, array('CURRENT_USER' => 24));
      //Сообщение ответственным лицам
      $arMessageFields = array(
        // получатель
        "TO_USER_ID" => $assignedById,
        // отправитель
        "FROM_USER_ID" => 206, 
        // тип уведомления
        "NOTIFY_TYPE" => IM_NOTIFY_SYSTEM,
        // модуль запросивший отправку уведомления
        "NOTIFY_MODULE" => "crm",
        // символьный тэг для группировки (будет выведено только одно сообщение), если это не требуется - не задаем параметр
        //"NOTIFY_TAG" => "CRM|LEAD|NEW|MEGAPBX",
        // текст уведомления на сайте (доступен html и бб-коды)
        "NOTIFY_MESSAGE" => "[b]Создан новый лид №".$LeadId." по исходящему звонку[/b] на номер ".$prefix.substr($_POST['phone'],0,1)."(".substr($_POST['phone'],1,3).")".substr($_POST['phone'],4,3)."-".substr($_POST['phone'],7,2)."-".substr($_POST['phone'],9)." на ВАТС Мегафон. <a href='/crm/lead/show/".$LeadId."/' target='_blank'>Перейти к лиду</a>",
      );
      CIMNotify::Add($arMessageFields);
      $arMessageFields = array(
        // получатель
        "TO_USER_ID" => 24,
        // отправитель
        "FROM_USER_ID" =>206, 
        // тип уведомления
        "NOTIFY_TYPE" => IM_NOTIFY_SYSTEM,
        // модуль запросивший отправку уведомления
        "NOTIFY_MODULE" => "crm",
        // символьный тэг для группировки (будет выведено только одно сообщение), если это не требуется - не задаем параметр
        //"NOTIFY_TAG" => "CRM|LEAD|NEW|MEGAPBX",
        // текст уведомления на сайте (доступен html и бб-коды)
        "NOTIFY_MESSAGE" => "[b]Создан новый лид №".$LeadId." по исходящему звонку[/b] на номер ".$prefix.substr($_POST['phone'],0,1)."(".substr($_POST['phone'],1,3).")".substr($_POST['phone'],4,3)."-".substr($_POST['phone'],7,2)."-".substr($_POST['phone'],9)." на ВАТС Мегафон. <a href='/crm/lead/show/".$LeadId."/' target='_blank'>Перейти к лиду</a>",
      );
      CIMNotify::Add($arMessageFields);
    }
  }
  if ($_POST['cmd'] == 'history' && $_POST['type'] == 'in'){
    $restHelper = new \Bitrix\Voximplant\Rest\Helper;
    $assignedById = (getUserByExt(trim($_POST['ext'])))?getUserByExt(trim($_POST['ext'])):206;
    $callParams = array(
      'CALL_ID' => getBitrixByMegapbx($_POST['callid']),
      'USER_ID' => $assignedById,
      'DURATION' => $_POST['duration'],
      'ADD_TO_CHAT' => true,
      'RECORD_URL' => $_POST['link']
    );
    $restHelper->finishExternalCall($callParams);
    
    if ($phone_res['FOUND']['Y'] && ($phone_res['LEAD']['ID']>0 || $phone_res['CONTACT']['ID']>0)){
      if ($phone_res['LEAD']['ID']>0){
        $entity_type = 'LEAD';
        $entity_id = $phone_res['LEAD']['ID'];
        $messageText = 'лиду';
        $messageLog = 'лида ';
      }elseif($phone_res['CONTACT']['ID']>0){
        $entity_type = 'CONTACT';
        $entity_id = $phone_res['CONTACT']['ID'];
        $messageText = 'контакту';
        $messageLog = 'контакта ';
      }elseif($phone_res['COMPANY']['ID']>0){
        $entity_type = 'COMPANY';
        $entity_id = $phone_res['COMPANY']['ID'];
        $messageText = 'компании';
        $messageLog = 'компании ';
      }
      
      $begintime = beginTimeIncoming($_POST['callid']);
      
      $arBindings[] = array('OWNER_TYPE_ID' => CCrmOwnerType::ResolveID($entity_type),
                            'OWNER_ID' => $entity_id
                           );
      $ismissed = ($_POST['status'] == 'Success')?'':'(пропущенный) ';
      $arFields = array(
        'OWNER_ID' => $entity_id,
        'OWNER_TYPE_ID' => CCrmOwnerType::ResolveID($entity_type),
        'TYPE_ID' =>  CCrmActivityType::Call,
        'SUBJECT' => 'Входящий '.$ismissed.'звонок с номера '.$prefix.substr($_POST['phone'],0,1).'('.substr($_POST['phone'],1,3).")".substr($_POST['phone'],4,3)."-".substr($_POST['phone'],7,2)."-".substr($_POST['phone'],9),
        'START_TIME' => date("d.m.Y H:i:s",strtotime($begintime['TIME'])),
        'END_TIME' => date("d.m.Y H:i:s",strtotime($begintime['TIME'])+$_POST['duration']),
        'COMPLETED' => ($_POST['status'] == 'Success')?'Y':'N',
        'RESPONSIBLE_ID' => $assignedById,
        'PRIORITY' => CCrmActivityPriority::Medium,
        'DESCRIPTION' => ($_POST['link']?"Запись входящего звонка прилагается":"Необходимо заполнить!!!"),
        'DESCRIPTION_TYPE' => CCrmContentType::Html,
        'DIRECTION' => CCrmActivityDirection::Incoming,
        'PROVIDER_DATA' => ($_POST['link']?$_POST['link']:""), 
        'NOTIFY_TYPE' => CCrmActivityNotifyType::None,
        'BINDINGS' => array_values($arBindings)
      );
      $oActivity = new CCrmActivity;
      $activityId = $oActivity->Add($arFields, false, true, array('REGISTER_SONET_EVENT' => true));
      if ($activityId){
        CEventLog::Add(array(
          "SEVERITY" => "SECURITY",
          "AUDIT_TYPE_ID" => "MEGAPBX_CALL_ADD",
          "MODULE_ID" => "main",
          "ITEM_ID" => 'Звонок на ВАТС Мегафон',
          "DESCRIPTION" => "Создан звонок с ID ".$activityId." для ".$messageLog.$entity_id,
        ));
        if ($_POST['status'] == 'Success') $notifyMess = "Добавлен входящий звонок к ".$messageText." № ".$entity_id.", <a href='/crm/".strtolower($entity_type)."/show/".$entity_id."/' target='_blank'>Перейти к ".$messageText."</a>";
        else $notifyMess = "Добавлен [b]пропущенный[/b] вызов к ".$messageText." № ".$entity_id.", <a href='/crm/".strtolower($entity_type)."/show/".$entity_id."/' target='_blank'>Перейти к ".$messageText."</a>";
        $arMessageFields = array(
          // получатель
          "TO_USER_ID" => 24,
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
          "TO_USER_ID" => $assignedById,
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
       
      }else{
        CEventLog::Add(array(
          "SEVERITY" => "SECURITY",
          "AUDIT_TYPE_ID" => "MEGAPBX_CALL_ADD",
          "MODULE_ID" => "main",
          "ITEM_ID" => 'Ошибка создания звонка на ВАТС Мегафон',
          "DESCRIPTION" => $oActivity->LAST_ERROR,
          ));         
      }
    }
    /*if ($phone_res['FOUND']['N']){
      //Задаем параметры лида
      $oLead = new CCrmLead;
      $arFields = array(
        "TITLE" => "Лид по неотвеченному звонку с номера +7(".substr($_POST['phone'],1,3).")".substr($_POST['phone'],4,3)."-".substr($_POST['phone'],7,2)."-".substr($_POST['phone'],9)." на ВАТС Мегафон",
        "NAME" => "неизвестно",
        "COMMENTS" => "Звонок поступил на номер ".trim($_POST['user']),
        "SOURCE_ID" => "CALL",
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
        "FROM_USER_ID" => 206, 
        // тип уведомления
        "NOTIFY_TYPE" => IM_NOTIFY_SYSTEM,
        // модуль запросивший отправку уведомления
        "NOTIFY_MODULE" => "crm",
        // символьный тэг для группировки (будет выведено только одно сообщение), если это не требуется - не задаем параметр
        //"NOTIFY_TAG" => "CRM|LEAD|NEW|MEGAPBX",
        // текст уведомления на сайте (доступен html и бб-коды)
        "NOTIFY_MESSAGE" => "[b]Создан новый лид по неотвеченному вызову[/b] с номера +7(".substr($_POST['phone'],1,3).")".substr($_POST['phone'],4,3)."-".substr($_POST['phone'],7,2)."-".substr($_POST['phone'],9)." на ВАТС Мегафон. <a href='/crm/lead/show/".$LeadId."/' target='_blank'>Перейти к лиду</a>",
      );
      CIMNotify::Add($arMessageFields);
      $arMessageFields = array(
        // получатель
        "TO_USER_ID" => 202,
        // отправитель
        "FROM_USER_ID" => 206, 
        // тип уведомления
        "NOTIFY_TYPE" => IM_NOTIFY_SYSTEM,
        // модуль запросивший отправку уведомления
        "NOTIFY_MODULE" => "crm",
        // символьный тэг для группировки (будет выведено только одно сообщение), если это не требуется - не задаем параметр
        //"NOTIFY_TAG" => "CRM|LEAD|NEW|MEGAPBX",
        // текст уведомления на сайте (доступен html и бб-коды)
        "NOTIFY_MESSAGE" => "[b]Создан новый лид по неотвеченному вызову[/b] с номера +7(".substr($_POST['phone'],1,3).")".substr($_POST['phone'],4,3)."-".substr($_POST['phone'],7,2)."-".substr($_POST['phone'],9)." на ВАТС Мегафон. <a href='/crm/lead/show/".$LeadId."/' target='_blank'>Перейти к лиду</a>",
      );
      CIMNotify::Add($arMessageFields);
      $arMessageFields = array(
        // получатель
        "TO_USER_ID" => 24,
        // отправитель
        "FROM_USER_ID" => 206, 
        // тип уведомления
        "NOTIFY_TYPE" => IM_NOTIFY_SYSTEM,
        // модуль запросивший отправку уведомления
        "NOTIFY_MODULE" => "crm",
        // символьный тэг для группировки (будет выведено только одно сообщение), если это не требуется - не задаем параметр
        //"NOTIFY_TAG" => "CRM|LEAD|NEW|MEGAPBX",
        // текст уведомления на сайте (доступен html и бб-коды)
        "NOTIFY_MESSAGE" => "[b]Создан новый лид по неотвеченному вызову[/b] с номера +7(".substr($_POST['phone'],1,3).")".substr($_POST['phone'],4,3)."-".substr($_POST['phone'],7,2)."-".substr($_POST['phone'],9)." на ВАТС Мегафон. <a href='/crm/lead/show/".$LeadId."/' target='_blank'>Перейти к лиду</a>",
      );
      CIMNotify::Add($arMessageFields);
      $entity_type = 'LEAD';
      $entity_id = $LeadId;

      $arFields = array(
        'OWNER_ID' => $entity_id,
        'OWNER_TYPE_ID' => CCrmOwnerType::ResolveID($entity_type),
        'TYPE_ID' =>  CCrmActivityType::Call,
        'SUBJECT' => 'Неотвеченный вызов с номера +7('.substr($_POST['phone'],1,3).")".substr($_POST['phone'],4,3)."-".substr($_POST['phone'],7,2)."-".substr($_POST['phone'],9),
        'START_TIME' => date("d.m.Y H:i:s"),
        'END_TIME' => date("d.m.Y H:i:s"),
        'COMPLETED' => 'N',
        'RESPONSIBLE_ID' => $assignedById,
        'PRIORITY' => CCrmActivityPriority::Medium,
        'DESCRIPTION' => "Необходимо перезвонить!!!",
        'DESCRIPTION_TYPE' => CCrmContentType::Html,
        'DIRECTION' => CCrmActivityDirection::Incoming,
        'PROVIDER_DATA' => ($_POST['link']?$_POST['link']:""), 
        'NOTIFY_TYPE' => CCrmActivityNotifyType::None,
        'BINDINGS' => array_values($arBindings)
      );
      $oActivity = new CCrmActivity;
      $activityId = $oActivity->Add($arFields, false, true, array('REGISTER_SONET_EVENT' => true));

    }*/
  }
  if ($_POST['cmd'] == 'history' && $_POST['type'] == 'out'){
    $assignedById = (getUserByExt(trim($_POST['ext'])))?getUserByExt(trim($_POST['ext'])):206;
    $restHelper = new \Bitrix\Voximplant\Rest\Helper;
    $callParams = array(
      'CALL_ID' => getBitrixByMegapbx($_POST['callid']),
      'USER_ID' => $assignedById,
      'DURATION' => $_POST['duration'],
      'ADD_TO_CHAT' => true,
      'RECORD_URL' => $_POST['link']
    );
    $restHelper->finishExternalCall($callParams);
    if ($phone_res['FOUND']['Y'] && ($phone_res['LEAD']['ID']>0 || $phone_res['CONTACT']['ID']>0)){
      if ($phone_res['LEAD']['ID']>0){
        $entity_type = 'LEAD';
        $entity_id = $phone_res['LEAD']['ID'];
        $messageText = 'лиду';
        $messageLog = 'лида ';
      }elseif($phone_res['CONTACT']['ID']>0){
        $entity_type = 'CONTACT';
        $entity_id = $phone_res['CONTACT']['ID'];
        $messageText = 'контакту';
        $messageLog = 'контакта ';
      }
      $begintime = beginTimeOutgoing($_POST['callid']);
      $arBindings[] = array('OWNER_TYPE_ID' => CCrmOwnerType::ResolveID($entity_type),
                            'OWNER_ID' => $entity_id
                           );
      switch ($_POST['status']){
        case 'Cancel':
          $outRes = "(нет ответа)";
          break;
        case 'Busy':
          $outRes = "(занято)";
          break;
        case 'NotAvailable':
          $outRes = "(не доступен)";
          break;
        default:
          $outRes = "";
          break;
      }
      $arFields = array(
        'OWNER_ID' => $entity_id,
        'OWNER_TYPE_ID' => CCrmOwnerType::ResolveID($entity_type),
        'TYPE_ID' =>  CCrmActivityType::Call,
        'SUBJECT' => 'Исходящий '.$outRes.' звонок на номер '.$prefix.substr($_POST['phone'],0,1).'('.substr($_POST['phone'],1,3).")".substr($_POST['phone'],4,3)."-".substr($_POST['phone'],7,2)."-".substr($_POST['phone'],9),
        'START_TIME' => date("d.m.Y H:i:s",strtotime($begintime['TIME'])),
        'END_TIME' => date("d.m.Y H:i:s",strtotime($begintime['TIME'])+$_POST['duration']),
        'COMPLETED' => ($_POST['status'] == 'Success')?'Y':'N',
        'RESPONSIBLE_ID' => $assignedById,
        'PRIORITY' => CCrmActivityPriority::Medium,
        'DESCRIPTION' => ($_POST['link']?"Запись исходящего звонка прилагается":"Необходимо заполнить!!!"),
        'DESCRIPTION_TYPE' => CCrmContentType::Html,
        'DIRECTION' => CCrmActivityDirection::Outgoing,
        'PROVIDER_DATA' => ($_POST['link']?$_POST['link']:""), 
        'NOTIFY_TYPE' => CCrmActivityNotifyType::None,
        'BINDINGS' => array_values($arBindings)
      );
      $oActivity = new CCrmActivity;
      $activityId = $oActivity->Add($arFields, false, true, array('REGISTER_SONET_EVENT' => true));
      if ($activityId){
        CEventLog::Add(array(
          "SEVERITY" => "SECURITY",
          "AUDIT_TYPE_ID" => "MEGAPBX_CALL_ADD",
          "MODULE_ID" => "main",
          "ITEM_ID" => 'Звонок на ВАТС Мегафон',
          "DESCRIPTION" => "Создан звонок с ID ".$activityId." для ".$messageLog.$entity_id,
        ));
        if ($_POST['status'] == 'Success') $notifyMess = "Добавлен исходящий вызов к ".$messageText." № ".$entity_id.", <a href='/crm/".strtolower($entity_type)."/show/".$entity_id."/' target='_blank'>Перейти к ".$messageText."</a>";     
        elseif ($_POST['status'] == 'Cancel') $notifyMess = "Добавлен исходящий вызов с результатом 'нет ответа' к ".$messageText." № ".$entity_id.", <a href='/crm/".strtolower($entity_type)."/show/".$entity_id."/' target='_blank'>Перейти к ".$messageText."</a>";
        elseif ($_POST['status'] == 'Busy') $notifyMess = "Добавлен исходящий вызов с результатом 'занято' к ".$messageText." № ".$entity_id.", <a href='/crm/".strtolower($entity_type)."/show/".$entity_id."/' target='_blank'>Перейти к ".$messageText."</a>";     
        elseif ($_POST['status'] == 'NotAvailable') $notifyMess = "Добавлен исходящий вызов  с результатом 'недоступен' к ".$messageText." № ".$entity_id.", <a href='/crm/".strtolower($entity_type)."/show/".$entity_id."/' target='_blank'>Перейти к ".$messageText."</a>";     
        $arMessageFields = array(
          // получатель
          "TO_USER_ID" => 24,
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
          "TO_USER_ID" => $assignedById,
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
        
      }else{
        CEventLog::Add(array(
          "SEVERITY" => "SECURITY",
          "AUDIT_TYPE_ID" => "MEGAPBX_CALL_ADD",
          "MODULE_ID" => "main",
          "ITEM_ID" => 'Ошибка создания звонка на ВАТС Мегафон',
          "DESCRIPTION" => $oActivity->LAST_ERROR,
          ));         
      }
    }
  }
}else {
  echo "<center><img style='margin: 0 auto;' src='images/away.jpg'></center>";
}
?>