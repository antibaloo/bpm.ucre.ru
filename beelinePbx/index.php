<?php
require ($_SERVER['DOCUMENT_ROOT'] . "/bitrix/modules/main/include/prolog_before.php");
CModule::IncludeModule('pull');
CModule::IncludeModule('voximplant');
CModule::IncludeModule("im");
CModule::IncludeModule("timeman");
CModule::IncludeModule('crm');
CModule::IncludeModule('search');
include('../include/beeline/functions.php');
if ($_SERVER['CONTENT_TYPE'] != 'application/xml; charset=UTF-8') exit;
$rsConfig = $DB->Query("select * from b_beelinepbx_config");
while ($arConfig = $rsConfig->Fetch()){$config[$arConfig['param']] = $arConfig['value'];}
$xml = file_get_contents("php://input");
//Bitrix\Main\Diag\Debug::writeToFile(array('DATE' => date("c"),'$_SERVER' => $_SERVER, '$_POST' => $_POST), "","/beelinePbx/beeline.log");
//Bitrix\Main\Diag\Debug::writeToFile(array('DATE' => date("c"),'xml' => $xml), "","/beelinePbx/beeline.log");
$dom = new DOMDocument;
$dom->loadXML($xml);

//События типа HookStatusEvent исключаем из лога
if ($dom->getElementsByTagName("eventData")->item(0)->attributes->getNamedItem("type")->nodeValue == "xsi:HookStatusEvent") exit;

//Пишем лог сообщений АТС
$DB->PrepareFields("b_beelinepbx_mess");
$lenRedirect = $dom->getElementsByTagName("redirections")->length;
$lenAddress = $dom->getElementsByTagName("address")->length;
$beelineCall = array(
  "b_time"                  => $DB->GetNowFunction(),
  "eventID"                 => "'".trim($dom->getElementsByTagName("eventID")->item(0)->nodeValue)."'",
  "eventType"               => "'".trim($dom->getElementsByTagName("eventData")->item(0)->attributes->getNamedItem("type")->nodeValue)."'",
  "sequenceNumber"          => "'".trim($dom->getElementsByTagName("sequenceNumber")->item(0)->nodeValue)."'",
  "userId"                  => "'".trim($dom->getElementsByTagName("userId")->item(0)->nodeValue)."'",
  "externalApplicationId"   => "'".trim($dom->getElementsByTagName("externalApplicationId")->item(0)->nodeValue)."'",
  "httpContact"             => "'".trim($dom->getElementsByTagName("httpContact")->item(0)->nodeValue)."'",
  "subscriptionId"          => "'".trim($dom->getElementsByTagName("subscriptionId")->item(0)->nodeValue)."'",
  "targetId"                => "'".trim($dom->getElementsByTagName("targetId")->item(0)->nodeValue)."'",
  "callId"                  => "'".trim($dom->getElementsByTagName("callId")->item(0)->nodeValue)."'",
  "extTrackingId"           => "'".trim($dom->getElementsByTagName("extTrackingId")->item(0)->nodeValue)."'",
  "networkCallId"           => "'".trim($dom->getElementsByTagName("networkCallId")->item(0)->nodeValue)."'",
  "hookStatus"              => "'".trim($dom->getElementsByTagName("hookStatus")->item(0)->nodeValue)."'",
  "personality"             => "'".trim($dom->getElementsByTagName("personality")->item(0)->nodeValue)."'",
  "state"                   => "'".trim($dom->getElementsByTagName("state")->item(0)->nodeValue)."'",
  "releasingParty"          => "'".trim($dom->getElementsByTagName("releasingParty")->item(0)->nodeValue)."'",
  "internalReleaseCause"    => "'".trim($dom->getElementsByTagName("internalReleaseCause")->item(0)->nodeValue)."'",
  "cdrTerminationCause"     => "'".trim($dom->getElementsByTagName("cdrTerminationCause")->item(0)->nodeValue)."'",
  
  "remotePartyName"         => "'".trim($dom->getElementsByTagName("name")->item(0)->nodeValue)."'",
  "remotePartyAddress"      => "'".trim($dom->getElementsByTagName("address")->item(0)->nodeValue)."'",
  "remotePartyUserId"       => "'".trim($dom->getElementsByTagName("userId")->item(1)->nodeValue)."'",
  "remotePartyUserDN"       => "'".trim($dom->getElementsByTagName("userDN")->item(0)->nodeValue)."'",
  "remotePartyPrivacy"      => "'".trim($dom->getElementsByTagName("privacy")->item(0)->nodeValue)."'",
  "remotePartyCallType"     => "'".trim($dom->getElementsByTagName("callType")->item(0)->nodeValue)."'",
  "source"                  => ($lenRedirect)?"'".trim($dom->getElementsByTagName("address")->item($lenAddress-1)->nodeValue)."'":NULL,
  "appearance"              => "'".trim($dom->getElementsByTagName("appearance")->item(0)->nodeValue)."'",
  "huntGroupUserId"         => "'".trim($dom->getElementsByTagName("huntGroupUserId")->item(0)->nodeValue)."'",
  "startTime"               => "'".trim($dom->getElementsByTagName("startTime")->item(0)->nodeValue)."'",
  "answerTime"              => "'".trim($dom->getElementsByTagName("answerTime")->item(0)->nodeValue)."'",
  "releaseTime"             => "'".trim($dom->getElementsByTagName("releaseTime")->item(0)->nodeValue)."'",
  //"endpoint"                => "'".trim($dom->getElementsByTagName("endpoint")->item(0)->attributes->getNamedItem("type")->nodeValue)."'",
  "addressOfRecord"         => "'".trim($dom->getElementsByTagName("addressOfRecord")->item(0)->nodeValue)."'",
  "recorded"                => "'".trim($dom->getElementsByTagName("recorded")->item(0)->nodeValue)."'",
  "allowedRecordingControls"=> "'".trim($dom->getElementsByTagName("allowedRecordingControls")->item(0)->nodeValue)."'",
  "recordingState"          => "'".trim($dom->getElementsByTagName("recordingState")->item(0)->nodeValue)."'",
  "xmlString"               => "'".trim($xml)."'",
);
$DB->StartTransaction();

$ID = $DB->Insert("b_beelinepbx_mess", $beelineCall, $err_mess.__LINE__);
$ID = intval($ID);

if (strlen($strError)<=0) $DB->Commit();
else $DB->Rollback();


$callId = str_replace("'","",$beelineCall['callId']);
$targetId = str_replace("'","",$beelineCall['targetId']);
$extTrackingId = str_replace("'","",$beelineCall['extTrackingId']);
$remotePartyCallType = str_replace("'","",$beelineCall['remotePartyCallType']);
$remotePartyUserId = str_replace("'","",$beelineCall['remotePartyUserId']);
$remotePartyAddress = str_replace("'","",$beelineCall['remotePartyAddress']);
$eventType = str_replace("'","",$beelineCall['eventType']);
$source = str_replace("'","",$beelineCall['source']);
$internalReleaseCause = str_replace("'","",$beelineCall['internalReleaseCause']);
$startTime = str_replace("'","",$beelineCall['startTime']);
$answerTime = str_replace("'","",$beelineCall['answerTime']);
$releaseTime = str_replace("'","",$beelineCall['releaseTime']);
$addressOfRecord = str_replace("'","",$beelineCall['addressOfRecord']);
$recordingState = str_replace("'","",$beelineCall['recordingState']);

$personality = str_replace("'","",$beelineCall['personality']);


$restHelper = new \Bitrix\Voximplant\Rest\Helper;

if ($eventType == 'xsi:SubscriptionTerminatedEvent'){
  $responce = `curl -X PUT --header 'X-MPBX-API-AUTH-TOKEN: {$config['token']}' --header 'Content-Type: application/json' -d ' { "expires" : 86400, "subscriptionType" : "BASIC_CALL", "url" : "http://bpm.ucre.ru/beelinePbx" } ' 'https://cloudpbx.beeline.ru/apis/portal/subscription'`;
  $result = json_decode($responce,true);
  $DB->Query("update b_beelinepbx_config set value='".$result['subscriptionId']."' where param = 'subscriptionId'");
}
//Разбор сообщения
if ($eventType == 'xsi:CallOriginatedEvent' || $eventType == 'xsi:CallReceivedEvent') {
  $assignedById = getUserByTargetId($targetId); //Определяем ответственного
  
  if ($remotePartyCallType == 'Group'){
    $phone_res = array(
      'FOUND'    => 'Y',
      'EMLOYEE'  => array('ID' =>  getUserByTargetId($remotePartyUserId))
    );
  }else{
    if ($remotePartyAddress !="") $phone_res = findByPhoneNumber(substr($remotePartyAddress,5));
  }
  
  
  
  switch ($eventType){
    case "xsi:CallReceivedEvent":
      $callDirection = 2;
      $callDirectionText = 'входящему звонку с номера ';
      break;
    case "xsi:CallOriginatedEvent":
      $callDirection = 1;
      $callDirectionText = 'исходящему звонку на номер ';
      break;
  }
  switch (substr($source,5)){
    case '79325360157':
      $SOURCE_ID = '79325360157';
      break;
    case '79228090357':
      $SOURCE_ID = '79228090357';
      break;
    case '79325360657':
      $SOURCE_ID = '79325360657';
      break;
    case '79877959090':
      $SOURCE_ID = '79877959090';
      break;
    case '532675700':
    case '79619475700':
      $SOURCE_ID = '79619475700';
      break;
    case '79878473434':
      $SOURCE_ID = '79878473434';
      break;
    case '79878479292':
      $SOURCE_ID = '79878479292';
      break;
    default:
    default:
      if ($eventType =="xsi:CallOriginatedEvent"){
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
      "TITLE" => "Название лида",
      "NAME" => "Переименовать",
      "COMMENTS" => "Лид по ".$callDirectionText."+".substr($remotePartyAddress,5,1)."(".substr($remotePartyAddress,6,3).")".substr($remotePartyAddress,9,3)."-".substr($remotePartyAddress,12,2)."-".substr($remotePartyAddress,14)." на ВАТС Билайн",
      "SOURCE_ID" => $SOURCE_ID,
      "OPPORTUNITY" =>0,
      "CURRENCY_ID" => "RUB",
      "OPPORTUNITY_ACCOUNT" => 0,
      "ACCOUNT_CURRENCY_ID" => "RUB",
      "LAST_NAME" => "",
      "SECOND_NAME" => "",
      "COMPANY_TITLE" => "",
      "POST" => "",
      "SOURCE_DESCRIPTION" =>"Создан модулем сопряжения ВАТС Билайн",
      "STATUS_ID" => "NEW",
      "UF_CRM_1486022615" => 1317,
      "ASSIGNED_BY_ID" => $assignedById,
      "FM" => array(
        "PHONE" => array(
          "n0" => array(
            "VALUE" => "+".substr($remotePartyAddress,5,1)."(".substr($remotePartyAddress,6,3).")".substr($remotePartyAddress,9,3)."-".substr($remotePartyAddress,12,2)."-".substr($remotePartyAddress,14),
            "VALUE_TYPE" => "OTHER"
          )
        )
      ),
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
  
  //Bitrix\Main\Diag\Debug::writeToFile(array('DATE' => date("c"),'eventType' => $eventType, 'targetId' => $targetId, 'remotePartyAddress' =>$remotePartyAddress, 'remotePartyCallType' => $remotePartyCallType,'assignedById' => $assignedById,'phone_res' => $phone_res, 'entityType' => $entityType, 'entityId' => $entityId), "","/beelinePbx/beeline.log");
  
  
  
  if (!getBitrixByBeelinepbx($callId)){//Если не найдено соответствие, то регистрируем звонок
    if ($remotePartyCallType == 'Group'){
      $callParams = array(
        'USER_ID' =>$assignedById,
        'PHONE_NUMBER'   => substr($remotePartyAddress,4),
        'CRM_CREATE' => false,
        'TYPE' => $callDirection,
        'SHOW' => false,
      );
    }else{
      $callParams = array(
        'USER_ID' =>$assignedById,
        'PHONE_NUMBER'   => substr($remotePartyAddress,5),
        'CRM_ENTITY_TYPE' => $entityType,
        'CRM_ENTITY_ID' => $entityId,
        'CRM_CREATE' => true,
        'TYPE' => $callDirection,
        'CRM_SOURCE' => $SOURCE_ID,
        'SHOW' => true,
      );
    }
    $registerResults = $restHelper-> registerExternalCall($callParams);
    $registerData = $registerResults->getData();
    if($registerData['CALL_ID']) linkCallIds($callId,$registerData['CALL_ID']);//Запоминаем соответствие callid
  }
}

if ($eventType == 'xsi:CallReleasedEvent') {
  switch ($internalReleaseCause){
    case "Busy":
      $statusCode = 486;
      break;
    case "Temporarily Unavailable":
      $statusCode = 480;
      break;
    case "Forbidden":
      $statusCode = 403;
      break;
    case "":
      if ($answerTime == "") $statusCode = 304;
      else $statusCode = 200;
      break;
  }
  if ($answerTime == "") {
    $duration = 0;
    $waiting = ($releaseTime - $startTime)/1000;
  }else{
    $duration = ($releaseTime - $answerTime)/1000;
    $waiting = ($answerTime - $startTime)/1000;
  }
  
  $callParams = array(
    'CALL_ID' => getBitrixByBeelinepbx($callId),
    'DURATION' => $duration,
    'USER_ID' => $assignedById,
    'ADD_TO_CHAT' => false,
    'RECORD_URL' => '',
    'STATUS_CODE' => $statusCode,
  );
  $finishResults = $restHelper->finishExternalCall($callParams);
  $finishData = $finishResults->getData();
  
  $client = curl_init('https://cloudpbx.beeline.ru/apis/portal/abonents/'.$targetId.'/recording');
  curl_setopt($client, CURLOPT_CUSTOMREQUEST, "GET");
  curl_setopt($client, CURLOPT_HTTPHEADER, array('X-MPBX-API-AUTH-TOKEN: '.$config['token']));
  curl_setopt($client, CURLOPT_RETURNTRANSFER, 1);
  $responce = curl_exec($client);
  $recordStatus = str_replace('"','',$responce);
  
  //Bitrix\Main\Diag\Debug::writeToFile(array('DATE' => date("c"), 'targetId' => $targetId,'recordStatus' => $recordStatus), "","/beelinePbx/beeline.log");
  
  if ($statusCode == 200 && $recordStatus == 'ON' && $recordingState != 'Failed'){
    //Пишем лог записи разговоров АТС
    $DB->PrepareFields("b_beeline_record_log");
    $beelineRecordLog = array(
      "logTime"       => $DB->GetNowFunction(),
      "extTrackingId" => "'".$extTrackingId."'",
      "bitrixCallId"  => "'".$callParams['CALL_ID']."'",
      "targetId"      => "'".$targetId."'",
      "recordState"   => "'RecordNotFound'"
    );
    $DB->StartTransaction();
    $ID = $DB->Insert("b_beeline_record_log", $beelineRecordLog, $err_mess.__LINE__);
    $ID = intval($ID);
    if (strlen($strError)<=0) $DB->Commit();
    else $DB->Rollback();
  }
  if ($statusCode != 200){
    $oActivity = new CCrmActivity;
    $acFields = array('COMPLETED' => 'N');
    $oActivity->Update($finishData['CRM_ACTIVITY_ID'], $acFields,true, true, array('CURRENT_USER' => 24));
    
    //Уведомление о внутренних пропущенных звонках
    if($remotePartyCallType == 'Group' && $personality == 'Terminator'){
      switch ($statusCode){
        case 486:
          $message = "Ваш телефон был занят (".date("H:i:s d.m.Y").")";
          break;
        case 480:
          $message = "Ваш телефон был недоступен (".date("H:i:s d.m.Y").")";
          break;
        default:
          $message = "Пропущенный вызов (".date("H:i:s d.m.Y").")";
          break;
      }
      $arFields = array(
        "MESSAGE_TYPE" => "P", # P - private chat, G - group chat, S - notification
        "TO_USER_ID" =>  getUserByTargetId($targetId),
        "FROM_USER_ID" => getUserByTargetId($remotePartyUserId),
        "MESSAGE" => $message,
        "AUTHOR_ID" => getUserByTargetId($remotePartyUserId),
      );
      CIMMessenger::Add($arFields);
    }
  }
}
?>