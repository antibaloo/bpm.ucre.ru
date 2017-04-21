<link rel="stylesheet" href="/bitrix/js/baloo/fancyapps/source/jquery.fancybox.css" type="text/css" media="screen" />
<script type="text/javascript" src="/bitrix/js/baloo/fancyapps/source/jquery.fancybox.pack.js"></script>
<?
$start = microtime(true);//Засекаем время выполнения скрипта
require ($_SERVER['DOCUMENT_ROOT'] . "/bitrix/modules/main/include/prolog_before.php");
CModule::IncludeModule('crm');
require($_SERVER["DOCUMENT_ROOT"]."/include/ocr/ocr.php");
echo "<pre>";
print_r($_POST);
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
  'user' => 1,
  'view' => 'list'
);
$avitoUrlPage = $avitoUrl."?".http_build_query($data);
echo $avitoUrlPage."<br>";
$searchResult = file_get_contents($avitoUrlPage);

$dom = new DomDocument();
$dom->loadHTML($searchResult);
$xpath = new DomXPath($dom);

$countAdQuery = $xpath->query("//*[contains(@class, 'breadcrumbs-link-count')]");
$countAd = preg_replace("/[^0-9]/", '', $countAdQuery->item(0)->nodeValue);
$pagesQuery = $xpath->query("//a[contains(@class, 'pagination-page')]");

if ($pagesQuery->length >0 ) {//Вычисляем кол-во страниц в результатах поиска
 $pages = $pagesQuery->length-1;
}else {
  $pages = 1;
}
echo "Всего объявлений в поиске: ".$countAd."<br>";
echo "Всего страниц результатов: ".$pages."<br>";

$itemQuery = $xpath->query("//*[contains(@class, 'item item_list js-catalog-item-enum clearfix')]");
$statuses = CCrmLead::GetStatuses();

echo "Объявлений на текущей странице: ".$itemQuery->length."<br>";

$avitoAds = array();
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
  $avitoAds[] = array('avitoId' => $avitoId, 'leadId' => $leadId);
  $count--;
  if ($count == 0) break;
}

/*Если заданное кол-во объявлений не найдено, но страниц с результатами больше 1, то произвести*/
/*дальнейший перебор до достижения заданного кол-ва или до окончания доступных объявлений*/
$page = 2;
while ($count>0 && $pages>1 && $page<=$pages){
  $searchResult = file_get_contents($avitoUrlPage."&p=".$page);
  $dom = new DomDocument();
  $dom->loadHTML($searchResult);
  $xpath = new DomXPath($dom);
  $itemQuery = $xpath->query("//*[contains(@class, 'item item_list js-catalog-item-enum clearfix')]");
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
    $avitoAds[] = array('avitoId' => $avitoId, 'leadId' => $leadId);
    $count--;
    if ($count == 0) break;
  }  
  $page++;
}

/*Перебор массива $avitoAds для формирования итоговой страницы результатов парсинга,*/
/*производится парсинг всех данных из объявлений по ссылке кроме номера телефона*/
foreach ($avitoAds as $avitoAd){
  usleep (rand(20,90)); //случайная пауза 20-90 мс
  $adOut = file_get_contents("https://www.avito.ru/items/".$avitoAd['avitoId']);
  
  $url1quote = strpos($adOut,"'",strpos($adOut,"avito.item.url"));
  $url2quote = strpos($adOut,"'",$url1quote+1);
  $avitoAd['Ссылка'] = "https://www.avito.ru".substr($adOut, $url1quote+1, $url2quote - $url1quote-1); //Вычисляем прямой url
  $price1quote = strpos($adOut,"'",strpos($adOut,"avito.item.price"));
  $price2quote = strpos($adOut,"'",$price1quote+1);
  $avitoAd['Цена'] = substr($adOut, $price1quote+1, $price2quote - $price1quote-1);
  $latitude1quote = strpos($adOut,'"',strpos($adOut,"data-map-lat"));
  $latitude2quote = strpos($adOut,'"',$latitude1quote+1);
  $avitoAd['Широта'] = substr($adOut,$latitude1quote+1,$latitude2quote - $latitude1quote-1);
  $longitude1quote = strpos($adOut,'"',strpos($adOut,"data-map-lon"));
  $longitude2quote = strpos($adOut,'"',$longitude1quote+1);
  $avitoAd['Долгота'] = substr($adOut,$longitude1quote+1,$longitude2quote - $longitude1quote-1);
  
  $dom = new DomDocument();
  $dom->loadHTML($adOut);
  $xpath = new DomXPath($dom);
  
  //Имя продавца (наименование) и ссылка на профиль
  $name_query = $xpath->query("//*[contains(@class, 'seller-info-name')]");
  $avitoAd['Имя (наименование) продавца'] = trim(utf8_decode ($name_query->item(0)->nodeValue));
  $avitoAd['Ссылка на профиль'] = "https://www.avito.ru".$name_query->item(0)->childNodes->item(1)->getAttribute('href');
  
  //Адрес
  $address_query = $xpath->query("//*[contains(@class, 'item-map-location')]");
  $avitoAd['Адрес объекта'] = utf8_decode($address_query->item(0)->nodeValue);
  $avitoAd['Адрес объекта'] = str_replace("Адрес:","",$avitoAd['Адрес объекта']);
  $avitoAd['Адрес объекта'] = str_replace("Скрыть карту","",$avitoAd['Адрес объекта']);
  $avitoAd['Адрес объекта'] = preg_replace('/[\x00-\x1F\x7F]/', '', $avitoAd['Адрес объекта']);
  $avitoAd['Адрес объекта'] = trim($avitoAd['Адрес объекта']);
  $avitoAd['Адрес объекта'] = preg_replace("/ {2,}/"," ",$avitoAd['Адрес объекта']);
  
  //Описание
  $desc_query = $xpath->query("//*[contains(@class, 'item-description-text')]");
  $avitoAd['Описание'] = trim(utf8_decode ($desc_query->item(0)->nodeValue));
  
  //Фото
  $photos_query = $xpath->query("//*[contains(@class, 'gallery-img-frame js-gallery-img-frame')]");
  foreach ($photos_query as $photo_query){
    $avitoAd['Фото'][] = "https:".$photo_query->getAttribute('data-url');
  }
  
  //Параметры объявления
  $params_query = $xpath->query("//*[contains(@class, 'item-params-list-item')]");
  $params = array();
  foreach ($params_query as $param){
    $temp_param = explode(":",trim(utf8_decode ($param->nodeValue)));
    $params[$temp_param[0]] = trim($temp_param[1]);
  }
  $avitoAd['Параметры'] = $params;
?>
<form id="form_<?=$avitoAd['avitoId']?>">
  <input name="leadId" type="hidden" value="<?=$avitoAd['leadId']?>">
  <input name="linkAd" type="hidden" value="<?=$avitoAd['Ссылка']?>">
  <input name="priceAd" type="hidden" value="<?=$avitoAd['Цена']?>">
  <input name="lanAd" type="hidden" value="<?=$avitoAd['Широта']?>">
  <input name="longAd" type="hidden" value="<?=$avitoAd['Долгота']?>">
  <input name="sellerAd" type="hidden" value="<?=$avitoAd['Имя (наименование) продавца']?>">
  <input name="profileAd" type="hidden" value="<?=$avitoAd['Ссылка на профиль']?>">
  <input name="addressAd" type="hidden" value="<?=$avitoAd['Адрес объекта']?>">
  <input name="descriptionAd" type="hidden" value="<?=$avitoAd['Описание']?>">
  <input name="photoAd" type="hidden" value='<?=serialize($avitoAd['Фото'])?>'>
  <input name="paramsAd" type="hidden" value='<?=serialize($avitoAd['Параметры'])?>'>
  <input name="userId" type="hidden" value="<?=$USER->GetID()?>">
  <table class="crm-offer-info-table crm-offer-main-info-text">
    <tbody>
      <tr><td colspan="5"><div class="crm-offer-title">Объявление № <?=$avitoAd['avitoId']?></div></td></tr>
      <tr class="crm-offer-row">
        <td class="crm-offer-info-drg-btn"></td>
        <td class="crm-offer-info-left">
          <div class="crm-offer-info-label-wrap"><span class="crm-offer-info-label">Ссылка на объявление:</span></div>
        </td>
        <td class="crm-offer-info-right">
          <div class="crm-offer-info-label-wrap"><span class="crm-offer-info-label"><a href='<?=$avitoAd['Ссылка']?>' target='_blank'>Перейти</a></span></div>
        </td>
        <td class="crm-offer-info-left">
          <div class="crm-offer-info-label-wrap"><span class="crm-offer-info-label">Ссылка на профиль:</span></div>
        </td>
        <td class="crm-offer-info-right">
          <div class="crm-offer-info-label-wrap"><span class="crm-offer-info-label"><a href='<?=$avitoAd['Ссылка на профиль']?>' target='_blank'>Перейти</a></span></div>
        </td>
      </tr>
      <tr class="crm-offer-row">
        <td class="crm-offer-info-drg-btn"></td>
        <td class="crm-offer-info-left">
          <div class="crm-offer-info-label-wrap"><span class="crm-offer-info-label">Адрес:</span></div>
        </td>
        <td class="crm-offer-info-right">
          <div class="crm-offer-info-label-wrap"><span class="crm-offer-info-label"><?=$avitoAd['Адрес объекта']?></span></div>
        </td>
        <td class="crm-offer-info-left">
          <div class="crm-offer-info-label-wrap"><span class="crm-offer-info-label">Координаты:</span></div>
        </td>
        <td class="crm-offer-info-right">
          <div class="crm-offer-info-label-wrap"><span class="crm-offer-info-label"><?=$avitoAd['Широта']?>, <?=$avitoAd['Долгота']?></span></div>
        </td>
      </tr>
      <tr class="crm-offer-row">
        <td class="crm-offer-info-drg-btn"></td>
        <td class="crm-offer-info-left">
          <div class="crm-offer-info-label-wrap"><span class="crm-offer-info-label">Цена:</span></div>
        </td>
        <td class="crm-offer-info-right">
          <div class="crm-offer-info-label-wrap"><span class="crm-offer-info-label"><?=$avitoAd['Цена']?></span></div>
        </td>
        <td class="crm-offer-info-left">
          <div class="crm-offer-info-label-wrap"><span class="crm-offer-info-label">Имя продавца:</span></div>
        </td>
        <td class="crm-offer-info-right">
          <div class="crm-offer-info-label-wrap"><span class="crm-offer-info-label"><?=$avitoAd['Имя (наименование) продавца']?></span></div>
        </td>
      </tr>
      <tr class="crm-offer-row">
        <td class="crm-offer-info-drg-btn"></td>
        <td class="crm-offer-info-left">
          <div class="crm-offer-info-label-wrap"><span class="crm-offer-info-label">Описание:</span></div>
        </td>
        <td class="crm-offer-info-right">
          <div class="crm-offer-info-label-wrap"><span class="crm-offer-info-label" style="text-align: left;"><?=$avitoAd['Описание']?></span></div>
        </td>
        <td class="crm-offer-info-left">
          <div class="crm-offer-info-label-wrap"><span class="crm-offer-info-label">Параметры:</span></div>
        </td>
        <td class="crm-offer-info-right">
          <div class="crm-offer-info-label-wrap"><span class="crm-offer-info-label" style="text-align: left;">
            <table>
            <?foreach($avitoAd['Параметры'] as $key=>$parameter){?>
              <tr>
                <td style="text-align: left;"><?=$key.":"?></td><td><?=$parameter?></td>
              </tr>
            <?}?>
            </table>
            </span></div>
        </td>
      </tr>
      <tr class="crm-offer-row">
        <td class="crm-offer-info-drg-btn"></td>
        <td class="crm-offer-info-left">
          <div class="crm-offer-info-label-wrap"><span class="crm-offer-info-label">Фотографии:</span></div>
        </td>
        <td class="crm-offer-info-right">
          <div class="crm-offer-info-label-wrap" style="text-align: center;">
            <?foreach ($avitoAd['Фото'] as $avitolink){?>
            <a class='fancybox' rel='image_gallery' href='<?=$avitolink?>'><img style='margin-right: 10px; border:1px solid #cccccc;' src='<?=$avitolink?>' width = 'auto' height ='50'/></a>
            <?}?>
          </div>
        </td>
        <td class="crm-offer-info-left">
          <div class="crm-offer-info-label-wrap"><input type="button" value="Синхронизировать" onclick="parse(<?=$avitoAd['avitoId']?>);"></div>
        </td>
        <td class="crm-offer-info-right">
          <div class="crm-offer-info-label-wrap"><span class="crm-offer-info-label" style="text-align: left;"><div id="result_<?=$avitoAd['avitoId']?>"></div></span></div>
        </td>
      </tr>
    </tbody>
  </table>
</form>

<?  
}
$time = microtime(true) - $start;
echo "Результат парсинга АВИТО, обработано ".$_POST['count']." объявлений за ".$time." секунд.";
?>
<script>
  $(document).ready(function() {
		$("a.fancybox").fancybox();
	});
  function parse (avitoId){
     var data = $('#form_'+avitoId).serialize();
    $.ajax({
      type: "POST",
      url: "./ajax/avito_parse.php",
      dataType: "text",
      data: data,
      success: function (html) {
        $("#result_"+avitoId).html(html);
        //if (html.indexOf("Ошибка:") ==-1) location.reload(true);
      },
      error: function (html) {
        $("#result_"+avitoId).html("Что-то пошло не так!");
      },
    });
  }
</script>