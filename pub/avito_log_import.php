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

curl_setopt($ch, CURLOPT_URL,"https://www.avito.ru/profile/upload/api/3/reports/?page=1&offset=0&limit=50&order=-1&order_by=created&created_start=".date("Y-m-d", strtotime("-1 days")));
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
curl_setopt($ch, CURLOPT_COOKIEJAR, $_SERVER['DOCUMENT_ROOT'].'/cookie.txt');
curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-Type: application/json;","Accept: application/json",""));
curl_setopt($ch, CURLOPT_REFERER, "Referer: https://www.avito.ru/profile/upload/realty/index"); 
curl_setopt($ch, CURLOPT_USERAGENT, "Opera/10.0 (Windows NT 5.1; U; en");
$json = curl_exec($ch);
$logs = json_decode($json, true);
$num_logs = 0;
$ignore_logs =0;
foreach ($logs['data'] as $log){
  curl_setopt($ch, CURLOPT_URL,"https://www.avito.ru/profile/upload/api/3/reports/".$log['log_id']);
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
    $query = 'SELECT * FROM ucre_avito_log WHERE UF_AVITO_ID="'.$log['log_id'].'"';
    $rsData = $DB->Query($query);
    if ($avitolog = $rsData->Fetch()){ //Если запись с таким AVITO_ID есть
      $ignore_logs++;
      echo $ignore_logs.". Лог с id ".$log['log_id'].", по ссылке https://www.avito.ru/profile/upload/api/3/report/".$log['log_id']." уже был загружен.<br>";
    } else {
      $num_logs++;
      $DB->PrepareFields("ucre_avito_log");
      $_params = $xpath->query("/html/body/div[@class='width']/div[@class='form-section form-section_blue']/fieldset[@class='form-fieldset is-readonly']");
      $arFields = array(//Наполняем поля данными
        'UF_AVITO_ID' =>  "'".$log['log_id']."'", //ID Лога на Авито
        'UF_STATUS'   =>  "'".utf8_decode($_params->item(0)->childNodes->item(2)->nodeValue)."'", //Общий статус загрузки
        'UF_LINK'     =>  "'http://avito.ru".$_params->item(1)->childNodes->item(2)->childNodes->item(1)->getAttributeNode("href")->nodeValue."'", //Ссылка xml фид загрузки
        'UF_LOG_LINK' =>  "'https://www.avito.ru/profile/upload/api/3/reports/".$log['log_id']."'",
        'UF_TIME'     =>  $DB->CharToDateFunction($_params->item(2)->childNodes->item(2)->nodeValue)  //Время обработки фида
      );
      $DB->StartTransaction();
      $ID = $DB->Insert("ucre_avito_log", $arFields, $err_mess.__LINE__);
      $ID = intval($ID);
      if (strlen($strError)<=0) {
        $DB->Commit();
      } else $DB->Rollback();
      $_res = $xpath->query("/html/body/div[@class='width']/table[@class='table table__items']/tbody/tr");
      $uf_processed = 0;
      $uf_success = 0;
      $uf_problems = 0;
      $uf_errors = 0;
      $uf_deleted = 0;
      foreach ($_res as $row){
        $children = $row->childNodes;
        $DB->PrepareFields("ucre_avito_log_element");
        if ($children->length == 10){
          $arElementFields = array(
            'UF_AVITO_LOG_ID' =>  "'".$log['log_id']."'",
            'UF_CRM_ID'       =>  "'".trim($children->item(2)->childNodes->item(1)->nodeValue)."'",
            'UF_AVITO_LINK'   =>  "'".$children->item(2)->childNodes->item(3)->nodeValue."'",
            'UF_STATUS'       =>  "'".utf8_decode($children->item(4)->childNodes->item(1)->nodeValue)."'",
            'UF_STATUS_MORE'  =>  "'".utf8_decode($children->item(4)->childNodes->item(4)->nodeValue)."'",
            'UF_TILL'         =>  $DB->CharToDateFunction($children->item(6)->childNodes->item(1)->nodeValue),
            'UF_MESSAGE'      =>  "'".str_replace("'",'"',utf8_decode($children->item(8)->nodeValue))."'"
          );
        }
        if ($children->length == 8){
          $arElementFields = array(
            'UF_AVITO_LOG_ID' =>  "'".$log['log_id']."'",
            'UF_CRM_ID'       =>  "'".trim($children->item(2)->childNodes->item(1)->nodeValue)."'",
            'UF_AVITO_LINK'   =>  "'".$children->item(2)->childNodes->item(3)->nodeValue."'",
            'UF_STATUS'       =>  "'".utf8_decode($children->item(4)->childNodes->item(1)->nodeValue)."'",
            'UF_STATUS_MORE'  =>  "'".utf8_decode($children->item(4)->childNodes->item(4)->nodeValue)."'",
            'UF_TILL'         =>  "",
            'UF_MESSAGE'      =>  "'".str_replace("'",'"',utf8_decode($children->item(6)->nodeValue))."'"
          );
        }
        $DB->StartTransaction();
        $ID = $DB->Insert("ucre_avito_log_element", $arElementFields, $err_mess.__LINE__);
        $ID_EL = intval($ID_EL);
        if (strlen($strError)<=0) {
          $DB->Commit();
        } else $DB->Rollback();
        
        if (utf8_decode($children->item(4)->childNodes->item(1)->nodeValue)=="Успешно опубликовано") $uf_success++;
        if (utf8_decode($children->item(4)->childNodes->item(1)->nodeValue)=="Опубликовано с проблемами") $uf_problems++;
        if (utf8_decode($children->item(4)->childNodes->item(1)->nodeValue)=="Не удалось опубликовать") $uf_errors++;
        if (utf8_decode($children->item(4)->childNodes->item(1)->nodeValue)=="Удалены из файла") $uf_deleted++;
        $uf_processed++;
      }
      $arFields = array(
        'UF_PROCESSED'  =>  $uf_processed,
        'UF_SUCCESS'    =>  $uf_success,
        'UF_PROBLEMS'   =>  $uf_problems,
        'UF_ERRORS'     =>  $uf_errors,
        'UF_DELETED'    =>  $uf_deleted
      );
      $DB->StartTransaction();
      $DB->Update("ucre_avito_log", $arFields, "WHERE UF_AVITO_ID = ".$log['log_id'], $err_mess.__LINE__);
      if (strlen($strError)<=0) {
        $DB->Commit();
      } else $DB->Rollback();
      
      echo $num_logs.". Обработан лог с ID ".$log['log_id'].", всего объявлений - ".$uf_processed.", из них успешно опубликовано - ".$uf_success.", опубликовано с проблемами - ".$uf_problems.", не удалось опубликовать - ".$uf_errors.", удалены из файла выгрузки - ".$uf_deleted."<br>";
    }
  } else {
    $log_message = utf8_decode($_error->item(0)->nodeValue);
  }
}
?>