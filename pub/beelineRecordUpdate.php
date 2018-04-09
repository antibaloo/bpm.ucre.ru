<?
require ($_SERVER['DOCUMENT_ROOT'] . "/bitrix/modules/main/include/prolog_before.php");
CModule::IncludeModule('voximplant');
if ($_GET['count'] > 0){
  $rsConfig = $DB->Query("select * from b_beelinepbx_config");
  while ($arConfig = $rsConfig->Fetch()){$config[$arConfig['param']] = $arConfig['value'];}
  $rsRecords = $DB->Query("select * from b_beeline_record_log where recordState = 'RecordNotFound' order by id DESC LIMIT ".$_GET['count']);
  while ($arRecord = $rsRecords->Fetch()){
    $client = curl_init('https://cloudpbx.beeline.ru/apis/portal/v2/records/'.$arRecord['extTrackingId'].'/'.$arRecord['targetId'].'/download');
    curl_setopt($client, CURLOPT_CUSTOMREQUEST, "GET");
    curl_setopt($client, CURLOPT_HTTPHEADER, array('X-MPBX-API-AUTH-TOKEN: '.$config['token']));
    curl_setopt($client, CURLOPT_RETURNTRANSFER, 1);
    $responce = curl_exec($client);
    $json = json_decode($responce, true);
    if (isset($json['errorCode'])){
      echo $arRecord['logTime']." - ".$arRecord['extTrackingId'].": Запись не найдена ". date("H:i:s d-m-Y")."<br>";
    }else{
      file_put_contents('/home/bitrix/www_bpm/callRecordTmp/'.$arRecord['bitrixCallId'].'.mp3', $responce);
      $restHelper = new \Bitrix\Voximplant\Rest\Helper;
      $result = $restHelper->attachRecordWithUrl($arRecord['bitrixCallId'],'https://bpm.ucre.ru/callRecordTmp/'.$arRecord['bitrixCallId'].'.mp3');
      echo $arRecord['logTime']." - ".$arRecord['extTrackingId']." Запись найдена ". date("H:i:s d-m-Y")."<br>";
      $DB->Query("update b_beeline_record_log set recordState='Uploaded', uploadTime = ".$DB->GetNowFunction()."  where id = '".$arRecord['id']."'");
      sleep (15);
      unlink('/home/bitrix/www_bpm/callRecordTmp/'.$arRecord['bitrixCallId'].'.mp3');
    }
  }
}else{
  echo "ПНХ!";
}
?>