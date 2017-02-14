<?
require ($_SERVER['DOCUMENT_ROOT'] . "/bitrix/modules/main/include/prolog_before.php");
CModule::IncludeModule('crm');
require($_SERVER["DOCUMENT_ROOT"]."/include/ocr/ocr.php");
if (isset($_POST['lead_id']) && !empty($_POST['lead_id']) && isset($_POST['avito_id']) && !empty($_POST['avito_id'])){
  $adOut = file_get_contents("https://www.avito.ru/items/".$_POST['avito_id']);
  $url1quote = strpos($adOut,"'",strpos($adOut,"avito.item.url"));
  $url2quote = strpos($adOut,"'",$url1quote+1);
  $url = "https://www.avito.ru".substr($adOut, $url1quote+1, $url2quote - $url1quote-1); //Высисляем прямой url
  
  $price1quote = strpos($adOut,"'",strpos($adOut,"avito.item.price"));
  $price2quote = strpos($adOut,"'",$price1quote+1);
  $price = substr($adOut, $price1quote+1, $price2quote - $price1quote-1);
  
  $phonekey1quote = strpos($adOut,"'",strpos($adOut,"avito.item.phone"));
  $phonekey2quote = strpos($adOut,"'",$phonekey1quote+1);
  $phonekey = substr($adOut,$phonekey1quote+1,$phonekey2quote - $phonekey1quote-1);
  
  $hash = phoneDemixer($phonekey,$_POST['avito_id']);
  
  if( $curl = curl_init() ) {
    $link = 'https://www.avito.ru/items/phone/'.$_POST['avito_id']."?pkey=".$hash;
    curl_setopt($curl, CURLOPT_URL, $link);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER,true);
    curl_setopt($curl, CURLOPT_COOKIEJAR, $_SERVER['DOCUMENT_ROOT'].'/avitocookie.txt');
    curl_setopt($curl, CURLOPT_COOKIEFILE, $_SERVER['DOCUMENT_ROOT'].'/avitocookie.txt');
    curl_setopt($curl, CURLOPT_REFERER, $url);
    curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1);
    curl_setopt($curl, CURLOPT_USERAGENT, "Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:50.0) Gecko/20100101 Firefox/50.0");
    $pic = curl_exec($curl);
    curl_close($curl);
    $pic_dump = json_decode($pic, true);
    //echo "<img src='".$pic_dump['image64']."' height='25' width='auto'/>";
    $ifp = fopen("temp_".$_POST['avito_id'].".png", "wb");
    $data = explode(',', $pic_dump['image64']);
    fwrite($ifp, base64_decode($data[1])); 
    fclose($ifp);
    $phoneImage = new crackCapcha("temp_".$_POST['avito_id'].".png"); 
    $phoneNumber = $phoneImage->resolve;
  }
  
  $dom = new DomDocument();
  $dom->loadHTML($adOut);
  $xpath = new DomXPath($dom);
  
  //Имя продавца
  $name_query = $xpath->query("//*[contains(@class, 'seller-info-name')]");
  $name = trim(utf8_decode ($name_query->item(0)->nodeValue));
  
  //Профиль на Авито
  $profile_query = $xpath->query("//*[contains(@class, 'seller-info-avatar-image js-public-profile-link')]");
  $profile = "https://www.avito.ru".$profile_query->item(0)->getAttribute('href');
  
  //Параметры объявления
  $params_query = $xpath->query("//*[contains(@class, 'item-params-list')]");
  $params = array();
  foreach ($params_query->item(0)->childNodes as $param){
    $temp_param = explode(":",trim(utf8_decode ($param->nodeValue)));
    switch ($temp_param[0]){
      case "Количество комнат":
        $params['rooms'] = trim(substr($temp_param[1],1,strpos($temp_param[1],"-")-1));
        break;
      case "Этаж":
        $params['floor'] = trim($temp_param[1]);
        break;
      case "Этажей в доме":
        $params['floors'] = trim($temp_param[1]);
        break;
      case "Тип дома":
        $params['wallstype'] = trim($temp_param[1]);
        break;
      case "Площадь":
        $params['square'] = floatval(trim($temp_param[1]));
        break;
    }
  }
  switch (strtolower($params['wallstype'])){
    case "кирпичный":
      $params['wallstype'] = 611;
      break;
    case "панельный":
      $params['wallstype'] = 610;
      break;
    case "блочный":
      $params['wallstype'] = 614;
      break;
    case "деревнный":
      $params['wallstype'] = 613;
      break;
    case "монолитный":
      $params['wallstype'] = 612;
      break;
  }
  
  //Адрес
  $address_query = $xpath->query("//*[contains(@class, 'item-map-location')]");
  $address = trim(utf8_decode($address_query->item(0)->nodeValue));
  $address = str_replace("Адрес:","",$address);
  $address = trim(str_replace("Скрыть карту","",$address));
  
  //Описание
  $desc_query = $xpath->query("//*[contains(@class, 'item-description-text')]");
  $description = trim(utf8_decode ($desc_query->item(0)->nodeValue));
  
  //Фотографии
  $photos_query = $xpath->query("//*[contains(@class, 'gallery-img-frame js-gallery-img-frame')]");
  $photos = array();
  foreach ($photos_query as $photo_query){
    $photos[] = "https:".$photo_query->getAttribute('data-url');
  }
  
  $leadFields = array(
    "FM" => array('PHONE' => array('n0'=>array('VALUE' => "+7".substr($phoneNumber,1), 'VALUE_TYPE' => 'MOBILE'))),
    'NAME'              =>  $name,
    'UF_CRM_1487055132' =>  $profile,
    'UF_CRM_1486119738' =>  $params['wallstype'],
    'UF_CRM_1486119569' =>  $params['floors'],
    'UF_CRM_1486119588' =>  $params['floor'],
    'UF_CRM_1486191523' =>  $params['rooms'],
    'UF_CRM_1486189899' =>  $params['square'],
    'COMMENTS'          =>  $description,
    'UF_CRM_1487058104' =>  serialize($photos),
    'UF_CRM_1486723225' =>  $pic_dump['image64'],  // картинка с номером
    'UF_CRM_1486118874' =>  $address,
    'UF_CRM_1486194356' =>  $price,
    'UF_CRM_1486619533' =>  $url,
  );
  $Lead = new CCrmLead;
  if($Lead->Update($_POST['lead_id'],$leadFields)){
    echo "Лид ".$_POST['lead_id']." обновлен!";
  } else {
    echo "При обновлении лида ".$_POST['lead_id']." произошла ошибка: ".$Lead->LAST_ERROR;
  }
  
} else {
  echo "Не все необходимые данные переданы!";
}
?>