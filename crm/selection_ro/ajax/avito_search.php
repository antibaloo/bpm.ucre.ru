<?
$start = microtime(true);//Засекаем время выполнения скрипта
require ($_SERVER['DOCUMENT_ROOT'] . "/bitrix/modules/main/include/prolog_before.php");
CModule::IncludeModule('crm');
require($_SERVER["DOCUMENT_ROOT"]."/include/ocr/ocr.php");
echo "<pre>";
//print_r($_POST);
echo "</pre>";
$avitoUrl = $_POST['base_url']."/".$_POST['type']."/".$_POST['operation'];
if ($_POST['type'] == 'kvartiry') {
	$avitoUrl.="/".$_POST['rooms'];
	$avitoUrl.="/".$_POST['market'];
	$avitoUrl.="/".$_POST['type_house'];
	if ($_POST['content'] != ""){
		$content = explode(" ", $_POST['content']);
		foreach($content as $key=>$word){
			if($key){
				$contentString .= "+".$word;
			}else{
				$contentString = "&q=".$word;
			}
		}
	}
}
$data = array(
  'user' => $_POST['user'],
  'view' => 'list'
);
$avitoUrlPage = $avitoUrl."?".http_build_query($data);
if ($contentString) $avitoUrlPage .= $contentString;
//echo $avitoUrlPage."<br>";
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
//echo "Всего объявлений в поиске: ".$countAd."<br>";

$itemQuery = $xpath->query("//*[contains(@class, 'item item_list js-catalog-item-enum clearfix')]");

$avitoAds = array();
foreach ($itemQuery as $key=>$item){
	$itemDOM = new DOMDocument();														//Создаем временный документ DOM
	$itemDOM->appendChild($itemDOM->importNode($item,true));//Загружаем в него данные отдельного объявления
	//if ($key==1) echo $itemDOM->saveHTML();//Отладка
	
	$itemXpath = new DomXPath($itemDOM);										//Закидываем их в объект DomXPath для поиска отдельных компонент
	$price = preg_replace("/[^0-9]/", '', $itemXpath->query("//div[contains(@class,'price')]")->item(0)->nodeValue);
	$photoCount = $itemXpath->query("//*[contains(@class,'i i-photo')]")->item(0)->nodeValue;
	$type = $itemXpath->query("//a[contains(@class,'description-title-link')]")->item(0)->nodeValue;
	$link = $itemXpath->query("//a[contains(@class,'description-title-link')]")->item(0)->getAttribute('href');
	$title = $itemXpath->query("//a[contains(@class,'description-title-link')]")->item(0)->getAttribute('title');
	$area = preg_replace("/[^0-9.]/", '', $itemXpath->query("//div[contains(@class,'param area')]")->item(0)->nodeValue);
	$temp_floor = explode("/",$itemXpath->query("//div[contains(@class,'param floor')]")->item(0)->nodeValue);
	$floor = preg_replace("/[^0-9]/", '', $temp_floor[0]);
	$floors = preg_replace("/[^0-9]/", '', $temp_floor[1]);
	$address = $itemXpath->query("//div[contains(@class,'fader')]")->item(0)->nodeValue;
	$locality = $itemXpath->query("//span[contains(@class,'metro-name')]")->item(0)->nodeValue;
	$dateAd = $itemXpath->query("//span[contains(@class,'date')]")->item(0)->nodeValue;
 
  $avitoId = substr($item->getAttribute('id'),1);
  $arFilter = array('UF_CRM_1486619563'=>$avitoId);
  $arSelect = array('ID','STATUS_ID');
  $rs = CCrmLead::GetList(array(), $arFilter, $arSelect);
  $leadId = 0;
	$leadStatus = "отсутствует";
	//Если лид по этому объявлению есть
  if ($curLead = $rs->Fetch()) {
		$leadStatus = CCrmLead::GetSemanticID($curLead['STATUS_ID']);
		if ($leadStatus == 'P' || $leadStatus == 'S') continue;
		$leadId = $curLead['ID'];
	}
  $avitoAds[] = array(
		'avitoId' => $avitoId, 
		'leadId' => $leadId,
		'leadLink' => ($leadId)?"/crm/lead/show/".$leadId."/":"", 
		'leadStatus' => $leadStatus, 
		'price' => $price,
		'photoCount' => $photoCount,
		'type' => utf8_decode(trim($type)),
		'link' => 'https://www.avito.ru'.$link,
		'title' => utf8_decode($title),
		'area' => $area,
		'floor' => $floor,
		'floors' => $floors,
		'address' => utf8_decode(trim($address)),
		'locality' => utf8_decode(trim($locality)),
		'date' => str_replace(chr(160)," ", utf8_decode(trim($dateAd))),
		'searchUrl' => $avitoUrlPage,
	);
}
//Считывание объявлений с остальных страниц
$page = 2;
while ($pages>1 && $page<=$pages){
  $searchResult = file_get_contents($avitoUrlPage."&p=".$page);
  $dom = new DomDocument();
  $dom->loadHTML($searchResult);
  $xpath = new DomXPath($dom);
  $itemQuery = $xpath->query("//*[contains(@class, 'item item_list js-catalog-item-enum clearfix')]");
  foreach ($itemQuery as $item){
		$itemDOM = new DOMDocument();														//Создаем временный документ DOM
		$itemDOM->appendChild($itemDOM->importNode($item,true));//Загружаем в него данные отдельного объявления
		//if ($key==1) echo $itemDOM->saveHTML();//Отладка
		$itemXpath = new DomXPath($itemDOM);										//Закидываем их в объект DomXPath для поиска отдельных компонент
		$price = preg_replace("/[^0-9]/", '', $itemXpath->query("//div[contains(@class,'price')]")->item(0)->nodeValue);
		$photoCount = $itemXpath->query("//*[contains(@class,'i i-photo')]")->item(0)->nodeValue;
		$type = $itemXpath->query("//a[contains(@class,'description-title-link')]")->item(0)->nodeValue;
		$link = $itemXpath->query("//a[contains(@class,'description-title-link')]")->item(0)->getAttribute('href');
		$title = $itemXpath->query("//a[contains(@class,'description-title-link')]")->item(0)->getAttribute('title');
		$area = preg_replace("/[^0-9.]/", '', $itemXpath->query("//div[contains(@class,'param area')]")->item(0)->nodeValue);
		$temp_floor = explode("/",$itemXpath->query("//div[contains(@class,'param floor')]")->item(0)->nodeValue);
		$floor = preg_replace("/[^0-9]/", '', $temp_floor[0]);
		$floors = preg_replace("/[^0-9]/", '', $temp_floor[1]);
		$address = $itemXpath->query("//div[contains(@class,'fader')]")->item(0)->nodeValue;	
		$locality = $itemXpath->query("//span[contains(@class,'metro-name')]")->item(0)->nodeValue;
		$dateAd = $itemXpath->query("//span[contains(@class,'date')]")->item(0)->nodeValue;
		$avitoId = substr($item->getAttribute('id'),1);
    $arFilter = array('UF_CRM_1486619563'=>$avitoId);
    $arSelect = array('ID','STATUS_ID');
    $rs = CCrmLead::GetList(array(), $arFilter, $arSelect);
    $leadId = 0;
		$leadStatus = "отсутствует";
    if ($curLead = $rs->Fetch()) {
			$leadStatus = CCrmLead::GetSemanticID($curLead['STATUS_ID']);
			if ($leadStatus == 'P' || $leadStatus == 'S') continue;
			$leadId = $curLead['ID'];
		}
    $avitoAds[] = array(
			'avitoId' => $avitoId,
			'type' => utf8_decode(trim($type)),
			'title' => utf8_decode($title),
			'link' => 'https://www.avito.ru'.$link,
			'area' => $area,
			'floor' => $floor,
			'floors' => $floors,
			'locality' => utf8_decode(trim($locality)),
			'address' => utf8_decode(trim($address)),
			'leadId' => $leadId,
			'leadLink' => ($leadId)?"/crm/lead/show/".$leadId."/":"", 
			'leadStatus' => $leadStatus,
			'price' => $price,
			'photoCount' => $photoCount,
			'date' => str_replace(chr(160)," ", utf8_decode(trim($dateAd))),
			'searchUrl' => $avitoUrlPage,
		);
  }  
  $page++;
}
?>
<div id="result_grid">
<?
	$rows = 20;
	$count = count($avitoAds);
	$pages = ($count % $rows)?intval($count/$rows)+1:$count/$rows;
	if ($count == 0) echo "<h2>По заданным параметрам ничего не найдено!</h2>";
	$index = 0;
	for ($i=1;$i<=$pages;$i++){//Цикл по страницам
?>
	<div class="page<?=($i == 1)?" active":""?>" id="page<?=$i?>">
		<table>
			<tr>
				<th>Название</th>
				<th>Площадь</th>
				<th>Этаж</th>
				<th>Этажность</th>
				<th>Район</th>
				<th>Адрес</th>
				<th>Цена</th>
				<th>Кол-во фото</th>
				<th>Дата размещения</th>
				<th>Лид</th>
			</tr>
<?
		for ($j=1;$j<=$rows;$j++){//Цикл по строкам
			if (array_key_exists($index,$avitoAds)){
				$leadString = '<a>отсутствует</a>';
				if ($avitoAds[$index]['leadStatus'] == "F") $leadString = '<a>провален</a>';
				$adParams = serialize($avitoAds[$index]);
				$adParams = str_replace('"',"'",$adParams);
			?>
			<tr id="<?=$avitoAds[$index]['avitoId']?>" adParams = "<?=$adParams?>" onclick="show_ad(this);">
				<td><a href="<?=$avitoAds[$index]['link']?>" target="_blank"><?=$avitoAds[$index]['title']?></a></td>
				<td><?=$avitoAds[$index]['area']?></td>
				<td><?=$avitoAds[$index]['floor']?></td>
				<td><?=$avitoAds[$index]['floors']?></td>
				<td><?=$avitoAds[$index]['locality']?></td>
				<td><?=$avitoAds[$index]['address']?></td>
				<td><?=($avitoAds[$index]['price'])?$avitoAds[$index]['price']:"не указана"?></td>
				<td><?=($avitoAds[$index]['photoCount'])?$avitoAds[$index]['photoCount']:"нет"?></td>
				<td><?=$avitoAds[$index]['date']?></td>
				<td><?=$leadString?></td>
			</tr>
			<?
			}
			$index++;
		}
?>
			<tr><td colspan="11" style="text-align: left; padding-left: 5px;">Всего: <?=$count?></td></tr>
		</table>
	</div>
<?}?>
</div>
<div class="pages">
	<center>
<?for ($i=1;$i<=$pages;$i++){//Цикл по страницам для номеров страниц?>
		<span class='pages<?=(($i == 1)?" active":"")?>' onclick='set_active(this)'><?=$i?></span>&nbsp;
<?}?>
	</center>
</div>
<div id="avitoAd" style="display: none;">
</div>
<?
$time = microtime(true) - $start;
echo "<br>Результат парсинга АВИТО, обработано ".$countAd." объявлений, из них подходящих ".count($avitoAds).", за ".$time." секунд.";
?>
<pre>
<?//print_r($avitoAds);?>
</pre>