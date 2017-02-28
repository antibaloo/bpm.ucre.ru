<?php
require ($_SERVER['DOCUMENT_ROOT'] . "/bitrix/modules/main/include/prolog_before.php");
$avito = json_decode(file_get_contents($_SERVER['DOCUMENT_ROOT']."/../avito_params"));
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL,"https://www.avito.ru/profile/upload/realty/index");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
curl_setopt($ch, CURLOPT_COOKIEJAR, $_SERVER['DOCUMENT_ROOT'].'/cookie.txt');
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_USERAGENT, "Opera/10.0 (Windows NT 5.1; U; en");
curl_setopt($ch, CURLOPT_REFERER, "http://avito.ru/profile"); 
curl_setopt($ch, CURLOPT_POSTFIELDS, "login=".$avito->login."&password=".$avito->password."&submit=logon");
$result = curl_exec($ch);

curl_setopt($ch, CURLOPT_URL,"https://www.avito.ru/profile/upload/api/2/reports/?page=1&offset=0&limit=20&order=-1&order_by=created&created_start=".date("Y-m-d", strtotime("-1 days")));
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
curl_setopt($ch, CURLOPT_COOKIEJAR, $_SERVER['DOCUMENT_ROOT'].'/cookie.txt');
curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-Type: application/json;","Accept: application/json",""));
curl_setopt($ch, CURLOPT_REFERER, "Referer: https://www.avito.ru/profile/upload/realty/index"); 
curl_setopt($ch, CURLOPT_USERAGENT, "Opera/10.0 (Windows NT 5.1; U; en");
$json = curl_exec($ch);
$logs = json_decode($json, true);
foreach ($logs['data'] as $log){
  curl_setopt($ch, CURLOPT_URL,"https://www.avito.ru/profile/upload/api/2/report/".$log['log_id']);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
  curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
  curl_setopt($ch, CURLOPT_USERAGENT, "Opera/10.0 (Windows NT 5.1; U; en");
  $result = curl_exec($ch);
  $dom = new DomDocument();
  $dom->loadHTML($result);
  $xpath = new DomXPath($dom);
  $_error = $xpath->query("/html/body");
  $error = strripos(utf8_decode(utf8_decode($_error->item(0)->nodeValue)), "Ошибка:");
  if ($error===false){

  } else {
    $log_message = utf8_decode($_error->item(0)->nodeValue);
  }
}
/*echo "<pre>";
print_r($logs);
echo "</pre>";*/
?>