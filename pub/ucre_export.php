<?php
require ($_SERVER['DOCUMENT_ROOT'] . "/bitrix/modules/main/include/prolog_before.php");
$start = microtime(true);//Засекаем время выполнения скрипта
$num = 0;
$f = 0;
$r = 0;
$h = 0;
$d = 0;
$p = 0;
$c = 0;
$json_ro = array();

if(CModule::IncludeModule('iblock') && CModule::IncludeModule("crm")) {
  $arSelect = Array("ID", "IBLOCK_ID", "CODE", "NAME","CREATED_BY","MODIFIED_BY","DATE_CREATE","TIMESTAMP_X", "DATE_ACTIVE_FROM","DETAIL_TEXT","PROPERTY_*");
  $iblock_filter = array ("IBLOCK_ID" => 42, /*"ACTIVE"=>"Y"*/"PROPERTY_266" => array("Активная стадия","Активный","Свободный"));
  $db_res = CIBlockElement::GetList(array("ID"=>"ASC"), $iblock_filter, false, false, $arSelect);
  while($aRes = $db_res->GetNext()){
    $dealFilter = array("ID" => $aRes['PROPERTY_319'],"CHECK_PERMISSIONS" => "N");
    $dealSelect = array("ID","UF_CRM_58958B5734602","COMMENTS","UF_CRM_1472038962","UF_CRM_1476517423");
    $deal_res = CCrmDeal::GetList(Array('DATE_CREATE' => 'DESC'), $dealFilter, $dealSelect);
    $deal = $deal_res->GetNext();
    
    $photos = array();
    $plans = array();
    $md5 = array();
    foreach (/*$aRes['PROPERTY_237']*/$deal['UF_CRM_1472038962'] as $imgid){
      $photos[] = stripslashes("https://bpm.ucre.ru".CFile::GetPath($imgid));
      $file_path = "/home/bitrix/www_bpm".CFile::GetPath($imgid);
      $tmp = explode(".",$file_path);
      $md5_path = $tmp[0].".md5";
      if (file_exists($md5_path)){
        $md5[] = file_get_contents($md5_path);
      }else{
        $result = explode("  ", exec("md5sum $file_path"));
        $md5[] = $result[0];
        file_put_contents($md5_path, $result[0]);
      }
    }
    foreach (/*$aRes['PROPERTY_236']*/$deal['UF_CRM_1476517423'] as $plnid){
      $plans[] = stripslashes("https://bpm.ucre.ru".CFile::GetPath($plnid));
      $file_path = "/home/bitrix/www_bpm".CFile::GetPath($plnid);
      $tmp = explode(".",$file_path);
      $md5_path = $tmp[0].".md5";
      if (file_exists($md5_path)){
        $md5[] = file_get_contents($md5_path);
      }else{
        $result = explode("  ", exec("md5sum $file_path"));
        $md5[] = $result[0];
        file_put_contents($md5_path, $result[0]);
      }
    }

    $tmp_type = CIBlockPropertyEnum::GetByID($aRes["PROPERTY_210"]);
    $tmp_appointment = CIBlockPropertyEnum::GetByID($aRes["PROPERTY_238"]);
    /*-Подмена улицы для выгрузки на сайт (клиентов пугает снт в адресе)-*/
    if (strripos($aRes['PROPERTY_217'],"|")){
      $arr_street = explode("|",$aRes['PROPERTY_217']);
      $street = $arr_street[1];
    }else{
      $street = $aRes['PROPERTY_217'];
    }
    /*--------------------------------------------------------------------*/

    
    switch ($aRes['PROPERTY_210']){
      case 381:
        $name = mb_strtoupper(substr($tmp_type["VALUE"],0,1)).substr($tmp_type["VALUE"],1)." ".number_format($aRes['PROPERTY_228'],2,".","")." кв.м., ".$street.", ".$aRes["PROPERTY_218"];
        break;
      case 382:
        $name = number_format($aRes["PROPERTY_229"],0)."-к ".$tmp_type["VALUE"]." ".number_format($aRes['PROPERTY_224'],2,".","")." кв.м., ".$street.", ".$aRes["PROPERTY_218"];
        break;
      case 383:
        $name = number_format($aRes["PROPERTY_229"],0)."-к ".$tmp_type["VALUE"]." ".number_format($aRes['PROPERTY_224'],2,".","")." кв.м., ".$street;
        break;
      case 384:
        $name = number_format($aRes["PROPERTY_229"],0)."-к ".$tmp_type["VALUE"]." ".number_format($aRes['PROPERTY_224'],2,".","")." кв.м., ".$street;
        break;
      case 385:
        $name = mb_strtoupper(substr($tmp_type["VALUE"],0,1)).substr($tmp_type["VALUE"],1)." ".number_format($aRes['PROPERTY_292'],2,".","")." сот., ".$street;
        break;
      case 386:
        $name = mb_strtoupper(substr($tmp_type["VALUE"],0,1)).substr($tmp_type["VALUE"],1)." ".number_format($aRes['PROPERTY_292'],2,".","")." сот., ".$street;
        break;
      case 387:
        $name = mb_strtoupper(substr($tmp_appointment["VALUE"],0,1)).substr($tmp_appointment["VALUE"],1)." ".number_format($aRes['PROPERTY_224'],2,".","")." кв.м., ".$street.", ".$aRes["PROPERTY_218"];
        break;
    }
    $json_ro[] = array('ID'               => $aRes['ID'],
                       'RO_TYPE'			  	=> $aRes['PROPERTY_210'],
                       'NEW_BUILDING'		  => ($aRes['PROPERTY_258'])? "Y":"N",
                       'NAME'             => $name,
                       'REGION'           => $aRes['PROPERTY_213'],
                       'DISTRICT'         => ($aRes['PROPERTY_214']=='обл. подчинения')? "":$aRes['PROPERTY_214'],
                       'CITY'             => $aRes['PROPERTY_215'],
                       'AREA'             => ($aRes['PROPERTY_216']=="" || $aRes['PROPERTY_216']=="отсутствует")? "":$aRes['PROPERTY_216'],
                       'ADDRESS'          => ($aRes['TYPE']==381 || $aRes['PROPERTY_210']==382)? $street.", ".$aRes['PROPERTY_218']:$street,
                       'LATITUDE'				  => $aRes['PROPERTY_298'],
                       'LONGITUDE'			  => $aRes['PROPERTY_299'],
                       'SQUARE'           => number_format($aRes['PROPERTY_224'],2,".",""),
                       'LIVING'					  => number_format($aRes['PROPERTY_225'],2,".",""),
                       'KITCHEN'				  => number_format($aRes['PROPERTY_226'],2,".",""),
                       'ROOMS'            => number_format($aRes['PROPERTY_229'],0),
                       'FLOOR'            => $aRes['PROPERTY_221'],
                       'FLOORS'           => $aRes['PROPERTY_222'],
                       'TYPE_HOUSE'       => $housetype[$aRes['PROPERTY_243']],
                       'TYPE'             => $aRes['PROPERTY_300'],
                       'APPOINTMENT'      => $appointment[$aRes['PROPERTY_238']],
                       'PLOT'             => number_format($aRes['PROPERTY_292'],2,".",""),
                       'ASSIGNED_BY'      => $aRes['PROPERTY_313'],
                       'PRICE'            => $deal['UF_CRM_58958B5734602'],//Цену берем из заявки //UF_CRM_58958B5734602 новая цена //UF_CRM_579897C010103
                       'DESCRIPTION'      => $deal['COMMENTS']." Номер заявки в базе ЕЦН: ".$deal['ID'].". При обращении в компанию назовите этот номер сотруднику, это поможет быстрее обработать Ваш запрос.",
                       'PHOTOS'           => $photos,
                       'PLANS'            => $plans,
                       'MD5'						  => $md5,
                       'LINK'						  => $aRes['PROPERTY_301'],
                      );
    $num++;
  }
  //echo json_encode($json_ro, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
  // Перезаписываем файл
  file_put_contents('/home/bitrix/www_bpm/ro.json', json_encode($json_ro, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));

  //тут сохранение файла json
  $time = microtime(true) - $start;
  CEventLog::Add(array(
    "SEVERITY" => "SECURITY",
    "AUDIT_TYPE_ID" => "UCRE_EXPORT",
    "MODULE_ID" => "main",
    "ITEM_ID" => 'Каталог недвижимости',
    "DESCRIPTION" => "Выгрузка агентом объектов недвижимости для обмена с сайтом ucre.ru, выгружено ".$num." объектов за ".$time." секунд.",
  ));
}

echo "Выгрузка агентом объектов недвижимости для обмена с сайтом ucre.ru, выгружено ".$num." объектов за ".$time." секунд.";
require ($_SERVER['DOCUMENT_ROOT'] . "/bitrix/modules/main/include/epilog_after.php");
?>