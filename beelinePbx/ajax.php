<?
require_once($_SERVER['DOCUMENT_ROOT'] . "/bitrix/modules/main/include/prolog_before.php");
if ($_SERVER['HTTP_REFERER'] == 'https://bpm.ucre.ru/beelinePbx/settings.php'){
  //echo "<pre>";print_r($_POST);echo "</pre>";
  switch ($_POST['action']){
    case "check":
      $responce = `curl -X GET --header 'X-MPBX-API-AUTH-TOKEN: {$_POST['token']}' 'https://cloudpbx.beeline.ru/apis/portal/subscription?subscriptionId={$_POST['subscriptionId']}'`;
      echo "<pre>";
      print_r(json_decode($responce,true));
      echo "</pre>";
      break;
    case "delete":
      $responce =`curl -X DELETE --header 'X-MPBX-API-AUTH-TOKEN: {$_POST['token']}' 'https://cloudpbx.beeline.ru/apis/portal/subscription?subscriptionId={$_POST['subscriptionId']}'`;
      $DB->Query("update b_beelinepbx_config set value='' where param = 'subscriptionId'");
      break;
    case "create":
      $responce = `curl -X PUT --header 'X-MPBX-API-AUTH-TOKEN: {$_POST['token']}' --header 'Content-Type: application/json' -d ' { "expires" : 90000, "subscriptionType" : "BASIC_CALL", "url" : "http://bpm.ucre.ru/beelinePbx" } ' 'https://cloudpbx.beeline.ru/apis/portal/subscription'`;
      echo "<pre>";
      $result = json_decode($responce,true);
      print_r($result);      
      echo "</pre>";
      $DB->Query("update b_beelinepbx_config set value='".$result['subscriptionId']."' where param = 'subscriptionId'");
      break;
  }
}else{
  echo "<br><center>Мимо кассы!</center>";
}
?>