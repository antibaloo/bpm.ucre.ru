<?
require ($_SERVER['DOCUMENT_ROOT'] . "/bitrix/modules/main/include/prolog_before.php");
$rsConfig = $DB->Query("select * from b_beelinepbx_config");
while ($arConfig = $rsConfig->Fetch()){$config[$arConfig['param']] = $arConfig['value'];}
$client = curl_init('https://cloudpbx.beeline.ru/apis/portal/subscription?subscriptionId='.$config['subscriptionId']);
curl_setopt($client, CURLOPT_CUSTOMREQUEST, "GET");
curl_setopt($client, CURLOPT_HTTPHEADER, array('X-MPBX-API-AUTH-TOKEN: '.$config['token']));
curl_setopt($client, CURLOPT_RETURNTRANSFER, 1);
$responce = curl_exec($client);
$json = json_decode($responce,true)
if (isset($json["errorCode"])){
  $responce = `curl -X PUT --header 'X-MPBX-API-AUTH-TOKEN: {$config['token']}' --header 'Content-Type: application/json' -d ' { "expires" : 90000, "subscriptionType" : "BASIC_CALL", "url" : "http://bpm.ucre.ru/beelinePbx" } ' 'https://cloudpbx.beeline.ru/apis/portal/subscription'`;
  $result = json_decode($responce,true);
  $DB->Query("update b_beelinepbx_config set value='".$result['subscriptionId']."' where param = 'subscriptionId'");      
}
?>