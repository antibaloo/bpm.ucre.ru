<?
require ($_SERVER['DOCUMENT_ROOT'] . "/bitrix/modules/main/include/prolog_before.php");
CModule::IncludeModule('crm');
require($_SERVER["DOCUMENT_ROOT"]."/include/ocr/ocr.php");
$avitoAds = array();
if( $curl = curl_init() ) {
  $link = 'https://www.avito.ru/orenburg/kvartiry/prodam/2-komnatnye/vtorichka?user=1&view=list';
  curl_setopt($curl, CURLOPT_URL, $link);
  curl_setopt($curl, CURLOPT_RETURNTRANSFER,true);
  curl_setopt($curl, CURLOPT_COOKIEJAR, $_SERVER['DOCUMENT_ROOT'].'/avitocookie.txt');
  curl_setopt($curl, CURLOPT_COOKIEFILE, $_SERVER['DOCUMENT_ROOT'].'/avitocookie.txt');
  curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1);
  curl_setopt($curl, CURLOPT_USERAGENT, "Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:50.0) Gecko/20100101 Firefox/50.0");
  $out = curl_exec($curl);
  curl_close($curl);
  $dom = new DomDocument();
  $dom->loadHTML($out);
  $xpath = new DomXPath($dom);
  $countQuery = $xpath->query("//*[contains(@class, 'breadcrumbs-link-count')]");
  $count = intval(trim($countQuery->item(0)->nodeValue));
  $pagesQuery = $xpath->query("//a[contains(@class, 'pagination-page')]");
  $lastPageLink = utf8_decode($pagesQuery->item($pagesQuery->length-1)->getAttribute('href'));
  $lastPage =  intval(substr($lastPageLink,strpos($lastPageLink,"?p=")+3,strpos($lastPageLink,"&") - strpos($lastPageLink,"?p=")-3));
  /*Выборка реузльтатов с 1-й страницы*/
  $itemQuery = $xpath->query("//*[contains(@class, 'item item_list js-catalog-item-enum clearfix')]");
  $adCount = 0;
  foreach ($itemQuery as $item){
    $avitoId = substr($item->getAttribute('id'),1);
    $avitoLink = "https://www.avito.ru/items/".$avitoId;
    $avitoPrice =  preg_replace("/[^0-9]/", '', $xpath->query("*[contains(@class, 'price')]",$item)->item(0)->nodeValue);
    foreach($xpath->query("*[contains(@class, 'params clearfix')]",$item)->item(0)->childNodes as $param){
      if ($param->nodeName == "div"){
        if ($param->getAttribute('class') == 'param area'){ 
          $temp = explode(" ",$param->nodeValue);
          $avitoSquare = floatval($temp[0]);
        }
        if ($param->getAttribute('class') == 'param floor'){ 
          $temp = explode("/",$param->nodeValue);
          $avitoFloor = preg_replace("/[^0-9]/", '', $temp[0]);
          $avitoFloors = preg_replace("/[^0-9]/", '', $temp[1]);
        }
        if ($param->getAttribute('class') == 'param address'){
          foreach($param->childNodes as $addressPart){
            if($addressPart->nodeName == "div"){
              if ($addressPart->getAttribute('class')=='fader'){$avitoStreet = trim(utf8_decode($addressPart->nodeValue));}
              if ($addressPart->getAttribute('class')=='metro-info__wrap'){$avitoDistrict = trim(utf8_decode($addressPart->nodeValue));}
            }
          }
        }
      }
    }
    
    foreach ($xpath->query("*[contains(@class, 'title description-title')]",$item)->item(0)->childNodes as $param){
      if ($param->nodeName != '#text'){
        if($param->nodeName == 'span'){
          $avitoDate = trim(utf8_decode ($param->nodeValue));
          if (strpos($avitoDate, "Сегодня") !== false){
            $avitoDate = strtolower(str_replace("Сегодня",rus_date("j F"),$avitoDate));
          }
          if (strpos($avitoDate, "Вчера") !== false){
            $avitoDate = strtolower(str_replace("Вчера",rus_date("j F",strtotime("-1 days")),$avitoDate));
          }
        }
        if($param->nodeName == 'h3'){
          $avitoName = trim(utf8_decode($param->childNodes->item(1)->getAttribute('title')));
        }
      }
    }
    $adCount++;
    $avitoAds[] = array(
      'id'      =>  $avitoId,
      'name'    =>  $avitoName,
      'link'    =>  $avitoLink,
      'price'   =>  $avitoPrice,
      'square'  =>  $avitoSquare,
      'floor'   =>  $avitoFloor,
      'floors'  =>  $avitoFloors,
      'address' =>  ($avitoDistrict)?$avitoDistrict.", ".$avitoStreet:$avitoStreet,
      'date'    =>  $avitoDate,
    );
  }
  /*Выборка реузльтатов с 2-й страницы и последующих*/  
  for ($i = 2;$i<=$lastPage;$i++){
    $curl = curl_init();
    $link = 'https://www.avito.ru/orenburg/kvartiry/prodam/2-komnatnye/vtorichka?user=1&view=list&p='.$i;
    curl_setopt($curl, CURLOPT_URL, $link);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER,true);
    curl_setopt($curl, CURLOPT_COOKIEJAR, $_SERVER['DOCUMENT_ROOT'].'/avitocookie.txt');
    curl_setopt($curl, CURLOPT_COOKIEFILE, $_SERVER['DOCUMENT_ROOT'].'/avitocookie.txt');
    curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1);
    curl_setopt($curl, CURLOPT_USERAGENT, "Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:50.0) Gecko/20100101 Firefox/50.0");
    $out = curl_exec($curl);
    curl_close($curl);
    $dom = new DomDocument();
    $dom->loadHTML($out);
    $xpath = new DomXPath($dom);
    $itemQuery = $xpath->query("//*[contains(@class, 'item item_list js-catalog-item-enum clearfix')]");
    foreach ($itemQuery as $item){
      $avitoId = substr($item->getAttribute('id'),1);
      $avitoLink = "https://www.avito.ru/items/".$avitoId;
      $avitoPrice =  preg_replace("/[^0-9]/", '', $xpath->query("*[contains(@class, 'price')]",$item)->item(0)->nodeValue);
      foreach($xpath->query("*[contains(@class, 'params clearfix')]",$item)->item(0)->childNodes as $param){
        if ($param->nodeName == "div"){
          if ($param->getAttribute('class') == 'param area'){ 
            $temp = explode(" ",$param->nodeValue);
            $avitoSquare = floatval($temp[0]);
          }
          if ($param->getAttribute('class') == 'param floor'){ 
            $temp = explode("/",$param->nodeValue);
            $avitoFloor = preg_replace("/[^0-9]/", '', $temp[0]);
            $avitoFloors = preg_replace("/[^0-9]/", '', $temp[1]);
          }
          if ($param->getAttribute('class') == 'param address'){
            foreach($param->childNodes as $addressPart){
              if($addressPart->nodeName == "div"){
                if ($addressPart->getAttribute('class')=='fader'){$avitoStreet = trim(utf8_decode($addressPart->nodeValue));}
                if ($addressPart->getAttribute('class')=='metro-info__wrap'){$avitoDistrict = trim(utf8_decode($addressPart->nodeValue));}
              }
            }
          }
        }
      }
      foreach ($xpath->query("*[contains(@class, 'title description-title')]",$item)->item(0)->childNodes as $param){
        if ($param->nodeName != '#text'){
          if($param->nodeName == 'span'){
            $avitoDate = trim(utf8_decode ($param->nodeValue));
            if (strpos($avitoDate, "Сегодня") !== false){
              $avitoDate = strtolower(str_replace("Сегодня",rus_date("j F"),$avitoDate));
            }
            if (strpos($avitoDate, "Вчера") !== false){
              $avitoDate = strtolower(str_replace("Вчера",rus_date("j F",strtotime("-1 days")),$avitoDate));
            }
          }
          if($param->nodeName == 'h3'){
            $avitoName = trim(utf8_decode($param->childNodes->item(1)->getAttribute('title')));
          }
        }
      }      
      $adCount++;
      $avitoAds[] = array(
        'id'      =>  $avitoId,
        'name'    =>  $avitoName,        
        'link'    =>  $avitoLink,
        'price'   =>  $avitoPrice,
        'square'  =>  $avitoSquare,
        'floor'   =>  $avitoFloor,
        'floors'  =>  $avitoFloors,
        'address' =>  ($avitoDistrict)?$avitoDistrict.", ".$avitoStreet:$avitoStreet,
        'date'    =>  $avitoDate,
      );
    }
  }
  
  foreach ($avitoAds as $avitoAd){
    $arFilter = array('UF_CRM_1486619563'=>$avitoAd['id']);
    $arSelect = array('ID','STATUS_ID');
    $rs = CCrmLead::GetList(array(), $arFilter, $arSelect);
    if ($curLead = $rs->Fetch()){
      if (in_array($curLead['STATUS_ID'],$finishedStatusId)){
        echo "Лид ".$curLead['ID']." закрыт! Ничего не делаем!<br>";
      } else {
        $leadFields = array(
          'UF_CRM_1486118874' => $avitoAd['address'],
          'UF_CRM_1486194356' => $avitoAd['price'],
        );
        $Lead = new CCrmLead;
        if($Lead->Update($curLead['ID'],$leadFields)){
          echo "Лид <a href='https://bpm.ucre.ru/crm/lead/show/".$curLead['ID']."/'>".$curLead['ID']."</a> обновлен!<br>";
        } else {
          echo "При обновлении лида <a href='https://bpm.ucre.ru/crm/lead/show/".$curLead['ID']."/".$curLead['ID']."</a> произошла ошибка: ".$Lead->LAST_ERROR."<br>";
        }
      }
    } else {
      $leadFields = array(
        'TITLE' => $avitoAd['name'],
        'STATUS_ID' => 'NEW',
        'ASSIGNED_BY_ID' => 206,
        'SOURCE_ID' => 10,
        'SOURCE_DESCRIPTION' => "Размещено: ".$avitoAd['date'],
        'UF_CRM_1486022615' => 590, //Направление лида - продажа
        'UF_CRM_1486119847' => 616, //Тип недвижимости - квартира
        'UF_CRM_1486207685' => array('0'=>'825'), //Признак недвижимости - вторичная
        'UF_CRM_1486191523' => 2,
        'UF_CRM_1486118874' => $avitoAd['address'],
        'UF_CRM_1486194356' => $avitoAd['price'],
        'UF_CRM_1486619563' => $avitoAd['id'],
        'UF_CRM_1486619533' => $avitoAd['link'],
        'UF_CRM_1486189899' => $avitoAd['square'],
        'UF_CRM_1486119588' => $avitoAd['floor'],
        'UF_CRM_1486119569' => $avitoAd['floors'],
      );
      $newLead = new CCrmLead;
      if($leadId = $newLead->Add($leadFields)){
        echo "Для объявления ".$avitoAd['id']." лида нет. Создан лид <a href='https://bpm.ucre.ru/crm/lead/show/".$leadId."/'>".$leadId."</a>!<br>";
      } else {
        echo "Для объявления ".$avitoAd['id']." лида нет. При создании лида произошла ошибка: ".$newLead->LAST_ERROR."<br>";
      }
    }
  }
  echo "Итого: ".$adCount;
}else{
  echo "Error curl!";
}
?>