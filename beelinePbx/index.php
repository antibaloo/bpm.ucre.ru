<?php
require ($_SERVER['DOCUMENT_ROOT'] . "/bitrix/modules/main/include/prolog_before.php");
include('../include/beeline/functions.php');
$xml = file_get_contents("php://input");
Bitrix\Main\Diag\Debug::writeToFile(array('DATE' => date("c"),'xml' => $xml, 'json' => xmlToJSON($xml)),"","/beelinePbx/beeline.log");
$dom = new DOMDocument;
$dom->loadXML($xml);

//События типа HookStatusEvent исключаем из лога
if ($dom->getElementsByTagName("eventData")->item(0)->attributes->getNamedItem("type")->nodeValue == "xsi:HookStatusEvent") exit;

//Пишем лог сообщений АТС
$DB->PrepareFields("b_beelinepbx_mess");
$lenRedirect = $dom->getElementsByTagName("redirections")->length;
$lenAddress = $dom->getElementsByTagName("address")->length;
$arFields = array(
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

$ID = $DB->Insert("b_beelinepbx_mess", $arFields, $err_mess.__LINE__);
$ID = intval($ID);

if (strlen($strError)<=0) $DB->Commit();
else $DB->Rollback();

?>