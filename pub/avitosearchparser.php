<?php
require ($_SERVER['DOCUMENT_ROOT'] . "/bitrix/modules/main/include/prolog_before.php");
CModule::IncludeModule('crm');
function rus_date() {
  $translate = array( "am" => "дп", "pm" => "пп","AM" => "ДП","PM" => "ПП","Monday" => "Понедельник","Mon" => "Пн","Tuesday" => "Вторник", "Tue" => "Вт", "Wednesday" => "Среда", "Wed" => "Ср",
                     "Thursday" => "Четверг", "Thu" => "Чт", "Friday" => "Пятница","Fri" => "Пт",    "Saturday" => "Суббота", "Sat" => "Сб","Sunday" => "Воскресенье","Sun" => "Вс","January" => "Января",
                     "Jan" => "Янв","February" => "Февраля","Feb" => "Фев","March" => "Марта","Mar" => "Мар","April" => "Апреля","Apr" => "Апр","May" => "Мая","May" => "Мая","June" => "Июня",
                     "Jun" => "Июн","July" => "Июля","Jul" => "Июл","August" => "Августа","Aug" => "Авг","September" => "Сентября","Sep" => "Сен","October" => "Октября","Oct" => "Окт",
                     "November" => "Ноября","Nov" => "Ноя","December" => "Декабря","Dec" => "Дек","st" => "ое","nd" => "ое","rd" => "е","th" => "ое");
  if (func_num_args() > 1) {
    $timestamp = func_get_arg(1);
    return strtr(date(func_get_arg(0), $timestamp), $translate);
  } else {
    return strtr(date(func_get_arg(0)), $translate);
  }
}
if( $curl = curl_init() ) {
  $link = 'https://www.avito.ru/orenburg/kvartiry/prodam/1-komnatnye/vtorichka?user=1';
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
  $catalog1 = $xpath->query("/html/body/div[@class='layout-internal col-12 js-autosuggest__search-list-container']/div[@class='l-content clearfix']/div[@class='clearfix']/div[@class='catalog catalog_table']/div[@class='catalog-list clearfix']/div[@class='js-catalog_before-ads']");
  $catalog2 = $xpath->query("/html/body/div[@class='layout-internal col-12 js-autosuggest__search-list-container']/div[@class='l-content clearfix']/div[@class='clearfix']/div[@class='catalog catalog_table']/div[@class='catalog-list clearfix']/div[@class='js-catalog_after-ads']");
  $avitoAds = array();
  foreach($catalog1->item(0)->childNodes as $cat1_el){
    if ($cat1_el->nodeName == 'div'){
      $adid = substr($cat1_el->getAttribute('id'),1);
      $adlink = "https://www.avito.ru/items/".$adid;
      foreach ($cat1_el->childNodes as $ad_part){
        if($ad_part->nodeName == 'div' && $ad_part->getAttribute('class') == 'description'){
          foreach($ad_part->childNodes as $parameter){
            if ($parameter->nodeName !='#text'){
              if ($parameter->nodeName == 'h3'){
                $adname = trim(utf8_decode ($parameter->nodeValue));
              }
              if ($parameter->getAttribute('class') =='about'){
                $adprice = preg_replace("/[^0-9]/", '', $parameter->nodeValue);
              }
              if ($parameter->getAttribute('class') =='address fader'){
                $adaddress = trim(utf8_decode ($parameter->nodeValue));
              }
              if ($parameter->getAttribute('class') =='data'){
                $addate = trim(utf8_decode ($parameter->nodeValue));
                if (strpos($addate, "Сегодня") !== false){
                  $addate = strtolower(str_replace("Сегодня",rus_date("j F"),$addate));
                }
                if (strpos($addate, "Вчера") !== false){
                  $addate = strtolower(str_replace("Вчера",rus_date("j F",strtotime("-1 days")),$addate));
                }
              }
            }
          }
        }
      }
      $avitoAds[] = array(
        'id' => $adid,
        'link' => $adlink,
        'name' => $adname,
        'price' => intval($adprice),
        'address' => $adaddress,
        'date' => $addate
      );
    }
  }
  foreach($catalog2->item(0)->childNodes as $cat2_el){
    if ($cat2_el->nodeName == 'div'){
      $adid = substr($cat2_el->getAttribute('id'),1);
      $adlink = "https://www.avito.ru/items/".$adid;
      foreach ($cat2_el->childNodes as $ad_part){
        if($ad_part->nodeName == 'div' && $ad_part->getAttribute('class') == 'description'){
          foreach($ad_part->childNodes as $parameter){
            if ($parameter->nodeName !='#text'){
              if ($parameter->nodeName == 'h3'){
                $adname = trim(utf8_decode ($parameter->nodeValue));
              }
              if ($parameter->getAttribute('class') =='about'){
                $adprice = preg_replace("/[^0-9]/", '', $parameter->nodeValue);
              }
              if ($parameter->getAttribute('class') =='address fader'){
                $adaddress = trim(utf8_decode ($parameter->nodeValue));
              }
              if ($parameter->getAttribute('class') =='data'){
                $addate = trim(utf8_decode ($parameter->nodeValue));
                if (strpos($addate, "Сегодня")!==false){
                  $addate = strtolower(str_replace("Сегодня",rus_date("j F"),$addate));
                }
                if (strpos($addate, "Вчера")!==false){
                  $addate = strtolower(str_replace("Вчера",rus_date("j F",strtotime("-1 days")),$addate));
                }
              }
            }
          }
        }
      }      
      $avitoAds[] = array(
        'id' => $adid,
        'link' => $adlink,
        'name' => $adname,
        'price' => intval($adprice),
        'address' => $adaddress,
        'date' => $addate
      );
    }
  }
  $finishedStatusId = array('2','4','5','JUNK','CONVERTED');
  foreach ($avitoAds as $avitoAd){
    $arFilter = array('UF_CRM_1486619563'=>$avitoAd['id']);
    $arSelect = array('ID','STATUS_ID');
    $rs = CCrmLead::GetList(array(), $arFilter, $arSelect);
    if ($curLead = $rs->Fetch()){
      if (in_array($curLead['STATUS_ID'],$finishedStatusId)){
        echo "Лид закрыт! Ничего не делаем!<br>";
      } else {
        $leadFields = array(
          'UF_CRM_1486118874' => $avitoAd['address'],
          'UF_CRM_1486194356' => $avitoAd['price'],
        );
        $Lead = new CCrmLead;
        if($Lead->Update($curLead['ID'],$leadFields)){
          echo "Лид ".$curLead['ID']." обновлен!<br>";
        } else {
          echo "При обновлении лида ".$curLead['ID']." произошла ошибка: ".$newLead->LAST_ERROR."<br>";
        }
      }
    } else {
      $leadFields = array(
        'TITLE' => $avitoAd['name'],
        'STATUS_ID' => 'NEW',
        'ASSIGNED_BY_ID' => 98,
        'SOURCE_ID' => 10,
        'SOURCE_DESCRIPTION' => "Размещено: ".$avitoAd['date'],
        'UF_CRM_1486022615' => 590, //Направление лида - продажа
        'UF_CRM_1486119847' => 616, //Тип недвижимости - квартира
        'UF_CRM_1486207685' => array('0'=>'825'), //Признак недвижимости - вторичная
        'UF_CRM_1486118874' => $avitoAd['address'],
        'UF_CRM_1486194356' => $avitoAd['price'],
        'UF_CRM_1486619563' => $avitoAd['id'],
        'UF_CRM_1486619533' => $avitoAd['link'],
      );
      $newLead = new CCrmLead;
      if($leadId = $newLead->Add($leadFields)){
        echo "Лида нет. Создан лид с id ".$leadId."!<br>";
      } else {
        echo "Лида нет. При создании лида произошла ошибка: ".$newLead->LAST_ERROR."<br>";
      }
    }
  }
  
  $avito_result = fopen('/home/bitrix/www_bpm/avitoparser.html', 'w');
  fwrite( $avito_result, $out);
  fclose( $avito_result );
}
?>