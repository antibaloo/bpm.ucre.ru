<?
if ($_SERVER['HTTP_REFERER'] == 'https://bpm.ucre.ru/crm/selection_ro/leads.php' && $_SERVER['REQUEST_METHOD'] == 'POST'){
  $start = microtime(true);//Засекаем время выполнения скрипта
  require ($_SERVER['DOCUMENT_ROOT'] . "/bitrix/modules/main/include/prolog_before.php");
  require($_SERVER["DOCUMENT_ROOT"]."/include/ocr/ocr.php");
  CModule::IncludeModule('crm');
  $adParamsString = str_replace("'",'"',$_POST['adParams']);
  $adParams = unserialize($adParamsString);
  //Загружаем текст объявления
  $adOut = file_get_contents($adParams['link']);
  //Широта
  $latitude1quote = strpos($adOut,'"',strpos($adOut,"data-map-lat"));
  $latitude2quote = strpos($adOut,'"',$latitude1quote+1);
  $adParams['latitude'] = substr($adOut,$latitude1quote+1,$latitude2quote - $latitude1quote-1);
  //Долгота
  $longitude1quote = strpos($adOut,'"',strpos($adOut,"data-map-lon"));
  $longitude2quote = strpos($adOut,'"',$longitude1quote+1);
  $adParams['longitude'] = substr($adOut,$longitude1quote+1,$longitude2quote - $longitude1quote-1);
  $dom = new DomDocument();
  $dom->loadHTML($adOut);
  $xpath = new DomXPath($dom);
  //Имя продавца (наименование) и ссылка на профиль
  $name_query = $xpath->query("//*[contains(@class, 'seller-info-name')]");
  $adParams['name'] = trim(utf8_decode ($name_query->item(0)->nodeValue));
  $adParams['profile'] = "https://www.avito.ru".$name_query->item(0)->childNodes->item(1)->getAttribute('href');
  //Адрес
  $address_query = $xpath->query("//*[contains(@class, 'item-map-location')]");
  $adParams['fullAddress'] = utf8_decode($address_query->item(0)->nodeValue);
  $adParams['fullAddress'] = str_replace("Адрес:","",$adParams['fullAddress']);
  $adParams['fullAddress'] = str_replace("Скрыть карту","",$adParams['fullAddress']);
  $adParams['fullAddress'] = preg_replace('/[\x00-\x1F\x7F]/', '', $adParams['fullAddress']);
  $adParams['fullAddress'] = trim($adParams['fullAddress']);
  $adParams['fullAddress'] = preg_replace("/ {2,}/"," ",$adParams['fullAddress']);
  //Описание
  $desc_query = $xpath->query("//*[contains(@class, 'item-description-text')]");
  $adParams['description'] = trim(utf8_decode ($desc_query->item(0)->nodeValue));
    //Фото
  $photos_query = $xpath->query("//*[contains(@class, 'gallery-img-frame js-gallery-img-frame')]");
  foreach ($photos_query as $photo_query){
    $adParams['photo'][] = "https:".$photo_query->getAttribute('data-url');
  }
  //Параметры объявления
  $params_query = $xpath->query("//*[contains(@class, 'item-params-list-item')]");
  $params = array();
  foreach ($params_query as $param){
    $temp_param = explode(":",trim(utf8_decode ($param->nodeValue)));
    $params[$temp_param[0]] = trim($temp_param[1]);
  }
  $adParams['params'] = $params;
  
  $phonekey1quote = strpos($adOut,"'",strpos($adOut,"avito.item.phone"));
  $phonekey2quote = strpos($adOut,"'",$phonekey1quote+1);
  $adParams['phonekey'] = substr($adOut,$phonekey1quote+1,$phonekey2quote - $phonekey1quote-1);
  $adParams['hash'] = phoneDemixer($adParams['phonekey'],$adParams['avitoId']);
  if( $curl = curl_init() ) {
    $link = 'https://www.avito.ru/items/phone/'.$adParams['avitoId']."?pkey=".$adParams['hash'];
    curl_setopt($curl, CURLOPT_URL, $link);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER,true);
    curl_setopt($curl, CURLOPT_COOKIEJAR, $_SERVER['DOCUMENT_ROOT'].'/avitocookie.txt');
    curl_setopt($curl, CURLOPT_COOKIEFILE, $_SERVER['DOCUMENT_ROOT'].'/avitocookie.txt');
    curl_setopt($curl, CURLOPT_REFERER, $adParams['link']);
    curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1);
    curl_setopt($curl, CURLOPT_USERAGENT, "Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:50.0) Gecko/20100101 Firefox/50.0");
    $pic = curl_exec($curl);
    curl_close($curl);
    $pic_dump = json_decode($pic, true);
    $ifp = fopen("temp_".$adParams['avitoId'].".png", "wb");
    $data = explode(',', $pic_dump['image64']);
    fwrite($ifp, base64_decode($data[1])); 
    fclose($ifp);
    $phoneImage = new crackCapcha("temp_".$adParams['avitoId'].".png"); 
    $phoneNumber = $phoneImage->resolve;
    $adParams['picDump'] = $pic_dump['image64'];
    $adParams['phoneNumber'] = $phoneNumber;
    unlink("temp_".$adParams['avitoId'].".png");
  }
?>
<table class="crm-offer-info-table crm-offer-main-info-text">
  <tr><td colspan="5"><div class="crm-offer-title"><?=$adParams['title']?>: объявление № <?=$adParams['avitoId']?>, размещено <?=$adParams['date']?></div></td></tr>
  <tr class="crm-offer-row">
    <td class="crm-offer-info-drg-btn"></td>
    <td class="crm-offer-info-left">
      <div class="crm-offer-info-label-wrap"><span class="crm-offer-info-label">Ссылка на объявление:</span></div>
    </td>
    <td class="crm-offer-info-right">
      <div class="crm-offer-info-label-wrap"><span class="crm-offer-info-label"><a href="<?=$adParams['link']?>" target="_blank">Перейти в объявление</a></span></div>
    </td>
    <td class="crm-offer-info-left">
      <div class="crm-offer-info-label-wrap"><span class="crm-offer-info-label">Профиль Авито:</span></div>
    </td>
    <td class="crm-offer-info-right">
      <div class="crm-offer-info-label-wrap"><span class="crm-offer-info-label"><a href="<?=$adParams['profile']?>" target="_blank">Перейти в профиль</a></span></div>
    </td>
  </tr>
  <tr class="crm-offer-row">
    <td class="crm-offer-info-drg-btn"></td>
    <td class="crm-offer-info-left">
      <div class="crm-offer-info-label-wrap"><span class="crm-offer-info-label">Имя продавца:</span></div>
    </td>
    <td class="crm-offer-info-right">
      <div class="crm-offer-info-label-wrap"><span><?=$adParams['name']?></span></div>
    </td>
    <td class="crm-offer-info-left">
      <div class="crm-offer-info-label-wrap"><span class="crm-offer-info-label">Телефон:</span></div>
    </td>
    <td class="crm-offer-info-right">
      <div class="crm-offer-info-label-wrap"><span><?=$adParams['phoneNumber']?></span></div>
    </td>
  </tr>
  <tr class="crm-offer-row">
    <td class="crm-offer-info-drg-btn"></td>
    <td class="crm-offer-info-left">
      <div class="crm-offer-info-label-wrap"><span class="crm-offer-info-label">Фотографии с Авито:</span></div>
    </td>
    <td class="crm-offer-info-right" colspan="3">
      <div class="crm-offer-info-label-wrap" style="text-align: center;">
        <?foreach($adParams['photo'] as $photoLink){?>
        <a class="fancybox" rel="image_gallery" href="<?=$photoLink?>"><img style="margin-right: 10px; border:1px solid #cccccc;" src="<?=$photoLink?>" width = "auto" height ="50"/></a>
        <?}?>
      </div>
    </td>
  </tr>
  <tr class="crm-offer-row">
    <td class="crm-offer-info-drg-btn"></td>
    <td class="crm-offer-info-left">
      <div class="crm-offer-info-label-wrap"><span class="crm-offer-info-label">Цена:</span></div>
    </td>
    <td class="crm-offer-info-right">
      <div class="crm-offer-info-label-wrap"><span><?=$adParams['price']?></span></div>
    </td>
    <td class="crm-offer-info-left">
      <div class="crm-offer-info-label-wrap"><span class="crm-offer-info-label">Адрес:</span></div>
    </td>
    <td class="crm-offer-info-right">
      <div class="crm-offer-info-label-wrap"><span><?=$adParams['fullAddress']?></span></div>
    </td>
    
  </tr>
  <tr class="crm-offer-row">
    <td class="crm-offer-info-drg-btn"></td>
    <td class="crm-offer-info-left">
      <div class="crm-offer-info-label-wrap"><span class="crm-offer-info-label">Параметры:</span></div>
    </td>
    <td class="crm-offer-info-right">
      <div class="crm-offer-info-label-wrap" >
        <span  style="text-align: left;">
          <ul>
          <?foreach($adParams['params'] as $key=>$param){?>
            <li><?=$key?>: <?=$param?></li>
          <?}?>
          </ul>
        </span>
      </div>
    </td>
    <td class="crm-offer-info-right" colspan="2">
      <div class="crm-offer-info-label-wrap" style="text-align: center;">
        <span class="crm-offer-info-label">
          <img src="https://static-maps.yandex.ru/1.x/?l=map&pt=<?=$adParams['longitude']?>,<?=$adParams['latitude']?>,pm2wtm&z=16&size=450,150">
        </span>
      </div>
    </td>
  </tr>
  <tr class="crm-offer-row">
    <td class="crm-offer-info-drg-btn"></td>
    <td class="crm-offer-info-left">
      <div class="crm-offer-info-label-wrap"><span class="crm-offer-info-label">Описание:</span></div>
    </td>
    <td class="crm-offer-info-right" colspan="3">
      <div class="crm-offer-info-label-wrap">
        <?=$adParams['description']?>
      </div>
    </td>
  </tr>
</table>
<form id="toLead_<?=$adParams['avitoId']?>">
  <input name="avitoId" type="hidden" value="<?=$adParams['avitoId']?>">
  <input name="leadId" type="hidden" value="<?=$adParams['leadId']?>">
  <input name="title" type="hidden" value="<?=$adParams['title']?>">
  <input name="date" type="hidden" value="<?=$adParams['date']?>">
  <input name="name" type="hidden" value="<?=$adParams['name']?>">
  <input name="phoneNumber" type="hidden" value="<?=$adParams['phoneNumber']?>">
  <input name="profile" type="hidden" value="<?=$adParams['profile']?>">
  <input name="params" type="hidden" value="<?=str_replace('"', "'", serialize($adParams['params']))?>">
  <input name="description" type="hidden" value="<?=$adParams['description']?>">
  <input name="photo" type="hidden" value="<?=str_replace('"', "'", serialize($adParams['photo']))?>">
  <input name="address" type="hidden" value="<?=$adParams['fullAddress']?>">
  <input name="price" type="hidden" value="<?=$adParams['price']?>">
  <input name="latitude" type="hidden" value="<?=$adParams['latitude']?>">
  <input name="longitude" type="hidden" value="<?=$adParams['longitude']?>">
  <input name="description" type="hidden" value="<?=$adParams['description']?>">
  <input name="link" type="hidden" value="<?=$adParams['link']?>">
  <input name="searchUrl" type="hidden" value="<?=$adParams['searchUrl']?>">
  <input name="picDump" type="hidden" value="<?=$adParams['picDump']?>">
  <input name="assignedById" type="hidden" value="<?=$USER->GetID()?>">
</form>
<pre>
<?//print_r($adParams);?>
</pre>
<input type="button" value="<?=($adParams['leadId'] > 0)?"Обновить лид":"Создать лид"?>" onclick="convertToLead(<?=$adParams['avitoId']?>)">&nbsp;
<input type="button" value="Close" onclick="document.getElementById('avitoAd' ).style.display = 'none';">
<script>
  $(document).ready(function() {
    $("a.fancybox").fancybox({'cyclic': false,});
  });
</script>
<?
    $time = microtime(true) - $start;
    //echo "<br>Парсинг объявления отработал за ".$time." секунд.";
}else {
  echo "<center><img style='margin: 0 auto;' src='../../../pub/images/away.jpg'></center>";
}
?>
