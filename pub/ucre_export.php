<?php
require ($_SERVER['DOCUMENT_ROOT'] . "/bitrix/modules/main/include/prolog_before.php");
$start = microtime(true);//Засекаем время выполнения скрипта
$num = 0;

$json_ro = array();
if(CModule::IncludeModule('iblock') && CModule::IncludeModule("crm")) {
  //------Наполняем справочник типов домов
  $property_enums = CIBlockPropertyEnum::GetList(Array(), Array("IBLOCK_ID"=>42, "CODE"=>"HOUSE_TYPE"));
  $housetype = array();
  while($enum_fields = $property_enums->GetNext()){
    $housetype[$enum_fields["ID"]] = $enum_fields["VALUE"];
  }
  //------Наполнили справочник типов домов
  //------Наполняем справочник материалов стен
  $property_enums = CIBlockPropertyEnum::GetList(Array(), Array("IBLOCK_ID"=>42, "CODE"=>"WALLS"));
  $walls = array();
  while($enum_fields = $property_enums->GetNext()){
    $walls[$enum_fields["ID"]] = $enum_fields["VALUE"];
  }
  //------Наполнили справочник материалов стен
  //------Наполняем справочник категорий участков
  $property_enums = CIBlockPropertyEnum::GetList(Array(), Array("IBLOCK_ID"=>42, "CODE"=>"PLOT_CAT"));
  $plotcat = array();
  while($enum_fields = $property_enums->GetNext()){
    $plotcat[$enum_fields["ID"]] = $enum_fields["VALUE"];
  }
  //------Наполнили справочник категорий участков
  //------Наполняем справочник назначений коммерческих объектов
  $property_enums = CIBlockPropertyEnum::GetList(Array(), Array("IBLOCK_ID"=>42, "CODE"=>"APPOINTMENT"));
  $appointment = array();
  while($enum_fields = $property_enums->GetNext()){
    $appointment[$enum_fields["ID"]] = $enum_fields["VALUE"];
  }
  //------Наполнили справочник назначений коммерческих объект
  
  
  $dbRes = $DB->Query("select b_iblock_element.ID as ELEMENT_ID,
                              b_iblock_element_prop_s42.PROPERTY_210,
                              b_iblock_element_prop_s42.PROPERTY_258,
                              b_iblock_element_prop_s42.PROPERTY_213, 
                              b_iblock_element_prop_s42.PROPERTY_214,
                              b_iblock_element_prop_s42.PROPERTY_215,
                              b_iblock_element_prop_s42.PROPERTY_216,
                              b_iblock_element_prop_s42.PROPERTY_217,
                              b_iblock_element_prop_s42.PROPERTY_218, 
                              b_iblock_element_prop_s42.PROPERTY_298,
                              b_iblock_element_prop_s42.PROPERTY_299,
                              b_iblock_element_prop_s42.PROPERTY_224,
                              b_iblock_element_prop_s42.PROPERTY_225,
                              b_iblock_element_prop_s42.PROPERTY_226,
                              b_iblock_element_prop_s42.PROPERTY_229,
                              b_iblock_element_prop_s42.PROPERTY_221, 
                              b_iblock_element_prop_s42.PROPERTY_222,
                              b_iblock_element_prop_s42.PROPERTY_243,
                              b_iblock_element_prop_s42.PROPERTY_300,
                              b_iblock_element_prop_s42.PROPERTY_238, 
                              b_iblock_element_prop_s42.PROPERTY_292,
                              b_uts_crm_deal.UF_CRM_58958B5734602,
                              b_crm_deal.COMMENTS,
                              b_crm_deal.ID,
                              b_crm_deal.ASSIGNED_BY_ID,
                              b_uts_crm_deal.UF_CRM_1472038962, 
                              b_uts_crm_deal.UF_CRM_1476517423,
                              b_iblock_element_prop_s42.PROPERTY_301,
                              b_iblock_element_prop_s42.PROPERTY_228,
                              b_iblock_element_prop_s42.PROPERTY_242,
                              b_iblock_element_prop_s42.PROPERTY_295 from b_crm_deal LEFT JOIN b_uts_crm_deal ON b_crm_deal.ID = b_uts_crm_deal.VALUE_ID LEFT JOIN b_iblock_element ON b_uts_crm_deal.UF_CRM_1469534140 = b_iblock_element.ID LEFT JOIN b_iblock_element_prop_s42 ON b_uts_crm_deal.UF_CRM_1469534140 = b_iblock_element_prop_s42.IBLOCK_ELEMENT_ID where (b_crm_deal.CATEGORY_ID = 0 OR b_crm_deal.CATEGORY_ID = 4) and b_uts_crm_deal.UF_CRM_1469534140 <> '' and (b_crm_deal.STAGE_ID = 'PROPOSAL' OR b_crm_deal.STAGE_ID = 'C4:PROPOSAL') ORDER BY b_crm_deal.ID DESC");
  while($aRes = $dbRes->Fetch()){
    $photos = array();
    $plans = array();
    $md5 = array();
    foreach (unserialize($aRes['UF_CRM_1472038962']) as $imgid){
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
    foreach (unserialize($aRes['UF_CRM_1476517423']) as $plnid){
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
    $json_ro[] = array('ID'               => $aRes['ELEMENT_ID'], 
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
                       'ASSIGNED_BY'      => $aRes['ASSIGNED_BY_ID'],
                       'PRICE'            => $aRes['UF_CRM_58958B5734602'],//Цену берем из заявки //UF_CRM_58958B5734602 новая цена //UF_CRM_579897C010103
                       'DESCRIPTION'      => $aRes['COMMENTS']." Номер заявки в базе ЕЦН: ".$aRes['ID'].". При обращении в компанию назовите этот номер сотруднику, это поможет быстрее обработать Ваш запрос.",
                       'PHOTOS'           => $photos,
                       'PLANS'            => $plans,
                       'MD5'						  => $md5,
                       'LINK'						  => $aRes['PROPERTY_301'],
                       'WALLS'            => $walls[$aRes['PROPERTY_242']],
                       'PLOT_CAT'         => $plotcat[$aRes['PROPERTY_295']]
                      );
    $num++;
  }
  // Перезаписываем файл
  file_put_contents('/home/bitrix/www_bpm/ro.json', json_encode($json_ro, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));
  
  $time = microtime(true) - $start;
  CEventLog::Add(array(
    "SEVERITY" => "SECURITY",
    "AUDIT_TYPE_ID" => "UCRE_EXPORT_NEW",
    "MODULE_ID" => "main",
    "ITEM_ID" => 'Каталог недвижимости',
    "DESCRIPTION" => "Выгрузка агентом объектов недвижимости для обмена с сайтом ucre.ru, выгружено ".$num." объектов за ".$time." секунд.",
  ));
}
echo "Выгрузка агентом объектов недвижимости для обмена с сайтом ucre.ru, выгружено ".$num." объектов за ".$time." секунд.";
require ($_SERVER['DOCUMENT_ROOT'] . "/bitrix/modules/main/include/epilog_after.php");
?>