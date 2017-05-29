<?
if ($_SERVER['HTTP_REFERER'] == 'https://bpm.ucre.ru/crm/selection_ro/leads.php' && $_SERVER['REQUEST_METHOD'] == 'POST'){
  require ($_SERVER['DOCUMENT_ROOT'] . "/bitrix/modules/main/include/prolog_before.php");
  CModule::IncludeModule('crm');
  $_POST['params'] = unserialize(str_replace("'",'"',$_POST['params']));
  $_POST['photo'] = str_replace("'",'"',$_POST['photo']);
  echo "<pre>";
  //print_r($_POST);
  echo "</pre>";
  if (substr_count($_POST['link'],"kvartiry")) $type=616;
  if (substr_count($_POST['searchUrl'],"vtorichka")) $market=array('0'=>'825');
  if (substr_count($_POST['searchUrl'],"novostroyka")) $market=array('0'=>'826');
  
  switch ($_POST['params']['Тип дома']){
    case "панельный":
      $houseType = 610;
      break;
    case "кирпичный":
      $houseType = 611;
      break;
    case "монолитный":
      $houseType = 612;
      break;
    case "деревянный":
      $houseType = 613;
      break;
    case "блочный":
      $houseType = 614;
      break;
  }
  $arFields = array(
    "TITLE" => $_POST['title'],
    "STATUS_ID" => "NEW",
    "SOURCE_ID" => 10,
    'SOURCE_DESCRIPTION' => "Размещено: ".$_POST['date'],
    "ASSIGNED_BY_ID" => $_POST['assignedById'],
    "NAME" => $_POST['name'],
    'UF_CRM_1487055132' =>  $_POST['profile'],
    "FM" => array("PHONE" => array("n0" => array("VALUE" => $_POST['phoneNumber'],"VALUE_TYPE" => "OTHER"))),
    "COMMENTS" => $_POST['description'],
    'UF_CRM_1486022615' => 590, //Направление лида - продажа
    'UF_CRM_1486119847' => $type, //Тип недвижимости
    'UF_CRM_1486207685' => $market, //Признак недвижимости
    'UF_CRM_1486118874' => $_POST['address'], 
    'UF_CRM_1486194356' => $_POST['price'],
    'UF_CRM_1486619563' => $_POST['avitoId'],
    'UF_CRM_1486619533' => $_POST['link'],
    'UF_CRM_1486189899' => preg_replace("/[^0-9.]/", '', $_POST['params']['Общая площадь']),
    'UF_CRM_1486190922' => preg_replace("/[^0-9.]/", '', $_POST['params']['Жилая площадь']),//UF_CRM_1486190922 Жилая
    'UF_CRM_1486191334' => preg_replace("/[^0-9.]/", '', $_POST['params']['Площадь кухни']),//UF_CRM_1486191334 - Кухня
    'UF_CRM_1486191523' => preg_replace("/[^0-9]/", '', $_POST['params']['Количество комнат']),
    'UF_CRM_1486119738' => $houseType,
    'UF_CRM_1486723225' => $_POST['picDump'],
    'UF_CRM_1487058104' => $_POST['photo'],
    'UF_CRM_1492065525' => serialize(array('latitude' => $_POST['latitude'], 'longitude' => $_POST['longitude'])),
    'UF_CRM_1486119588' => $_POST['params']['Этаж'],
    'UF_CRM_1486119569' => $_POST['params']['Этажей в доме'],
    "OPPORTUNITY" =>0,
    "CURRENCY_ID" => "RUB",
    "OPPORTUNITY_ACCOUNT" => 0,
    "ACCOUNT_CURRENCY_ID" => "RUB",
  );
  echo "<pre>";
  //print_r($arFields);
  echo "</pre>";
  $Lead = new CCrmLead;
  if ($_POST['leadId'] > 0){
    $Lead->Update($_POST['leadId'],$arFields);
    echo 'Обновлен лид №<a href="/crm/lead/show/'.$_POST['leadId'].'/" target="_blank">'.$_POST['leadId'].'</a>';
  }else{
    $LeadId = $Lead->Add($arFields);
    echo 'Создан лид №<a href="/crm/lead/show/'.$LeadId.'/" target="_blank">'.$LeadId.'</a>';
  }
}else {
  echo "<center><img style='margin: 0 auto;' src='../../../pub/images/away.jpg'></center>";
}
?>