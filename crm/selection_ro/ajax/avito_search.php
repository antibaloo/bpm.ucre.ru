<?
require ($_SERVER['DOCUMENT_ROOT'] . "/bitrix/modules/main/include/prolog_before.php");
CModule::IncludeModule('crm');
require($_SERVER["DOCUMENT_ROOT"]."/include/ocr/ocr.php");
echo "<pre>";
print_r($_POST);
//echo "<hr>";
//print_r(CCrmLead::GetStatuses());
echo "</pre>";
$count = $_POST['count'];
$avitoUrl = $_POST['base_url']."/".$_POST['type']."/".$_POST['operation'];
if ($_POST['type'] == 'kvartiry') {
  if ($_POST['notfirst']){
    $avitoUrl.="/ne_posledniy";
  }
  if ($_POST['rooms'] != ""){
    $avitoUrl.="/".$_POST['rooms'];
  }
  if ($_POST['market'] != ""){
    $avitoUrl.="/".$_POST['market'];
  }
  if ($_POST['type_house'] != ""){
    $avitoUrl.="/".$_POST['type_house'];
  }
}
$data = array(
  'p'    => 1,
  'user' => 1,
  'view' => 'list'
);
$avitoUrlPage = $avitoUrl."?".http_build_query($data);
echo $avitoUrlPage."<br>";
$searchResult = file_get_contents($avitoUrlPage);
$dom = new DomDocument();
$dom->loadHTML($searchResult);
$xpath = new DomXPath($dom);
$itemQuery = $xpath->query("//*[contains(@class, 'item item_list js-catalog-item-enum clearfix')]");
$statuses = CCrmLead::GetStatuses();
foreach ($itemQuery as $item){
  $avitoId = substr($item->getAttribute('id'),1);
  $arFilter = array('UF_CRM_1486619563'=>$avitoId);
  $arSelect = array('ID','STATUS_ID');
  $rs = CCrmLead::GetList(array(), $arFilter, $arSelect);
  $leadId = 0;
  if ($curLead = $rs->Fetch()){//Если лид по этому объявлению есть
    if (CCrmLead::GetSemanticID($curLead['STATUS_ID'])=='F')   $leadId = $curLead['ID']; //Если он провален, запоминаем ID
    else continue;// Если закрыт успехом или находится в обработке, пропускаем итерацию (игнорируем)
  }
  $leadFields = array();
  $adOut = file_get_contents("https://www.avito.ru/items/".$avitoId);

  $url1quote = strpos($adOut,"'",strpos($adOut,"avito.item.url"));
  $url2quote = strpos($adOut,"'",$url1quote+1);
  $url = "https://www.avito.ru".substr($adOut, $url1quote+1, $url2quote - $url1quote-1); //Вычисляем прямой url
  $leadFields['UF_CRM_1486619533'] = $url;
  
  $price1quote = strpos($adOut,"'",strpos($adOut,"avito.item.price"));//Цена
  $price2quote = strpos($adOut,"'",$price1quote+1);
  $price = substr($adOut, $price1quote+1, $price2quote - $price1quote-1);
  $leadFields['UF_CRM_1486194356'] = $price;
  
  $latitude1quote = strpos($adOut,'"',strpos($adOut,"data-map-lat"));
  $latitude2quote = strpos($adOut,'"',$latitude1quote+1);
  $latitude = substr($adOut,$latitude1quote+1,$latitude2quote - $latitude1quote-1);
  
  $longitude1quote = strpos($adOut,'"',strpos($adOut,"data-map-lon"));
  $longitude2quote = strpos($adOut,'"',$longitude1quote+1);
  $longitude = substr($adOut,$longitude1quote+1,$longitude2quote - $longitude1quote-1);
  $leadFields['UF_CRM_1492065525'] = serialize(array('latitude' => $latitude,'longitude'=> $longitude));

  $phonekey1quote = strpos($adOut,"'",strpos($adOut,"avito.item.phone"));
  $phonekey2quote = strpos($adOut,"'",$phonekey1quote+1);
  $phonekey = substr($adOut,$phonekey1quote+1,$phonekey2quote - $phonekey1quote-1);
  $hash = phoneDemixer($phonekey,$avitoId);  
  if( $curl = curl_init() ) {
    $link = 'https://www.avito.ru/items/phone/'.$avitoId."?pkey=".$hash;
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
    $ifp = fopen("temp_".$avitoId.".png", "wb");
    $data = explode(',', $pic_dump['image64']);
    fwrite($ifp, base64_decode($data[1])); 
    fclose($ifp);
    $phoneImage = new crackCapcha("temp_".$avitoId.".png"); 
    $phoneNumber = $phoneImage->resolve;
    unlink("temp_".$avitoId.".png");
  }  
  $leadFields['UF_CRM_1486723225'] = $pic_dump['image64'];
  $leadFields['FM'] = array('PHONE' => array('n0'=>array('VALUE' => "+7".substr($phoneNumber,1), 'VALUE_TYPE' => 'MOBILE')));
  
  $dom = new DomDocument();
  $dom->loadHTML($adOut);
  $xpath = new DomXPath($dom);
  
  //Имя продавца (наименование) и ссылка на профиль
  $name_query = $xpath->query("//*[contains(@class, 'seller-info-name')]");
  $name = trim(utf8_decode ($name_query->item(0)->nodeValue));
  $profile = "https://www.avito.ru".$name_query->item(0)->childNodes->item(1)->getAttribute('href');
  $leadFields['NAME'] = $name;
  $leadFields['UF_CRM_1487055132'] = $profile;
  
  
  echo "<pre>";
  print_r($leadFields);
  echo "</pre>";
  
  if ($leadId){
    
  }else{
    
  }
  
  $count--;
  if ($count == 0) break;
}
?>