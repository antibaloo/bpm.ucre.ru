<?
$start = microtime(true);//Засекаем время выполнения скрипта
require ($_SERVER['DOCUMENT_ROOT'] . "/bitrix/modules/main/include/prolog_before.php");
CModule::IncludeModule('crm');
require($_SERVER["DOCUMENT_ROOT"]."/include/ocr/ocr.php");
echo "<pre>";
print_r($_POST);
echo "</pre>";
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

$itemQuery = $xpath->query("//*[contains(@class, 'item item_list js-catalog-item-enum clearfix')]");

$avitoAds = array();
foreach ($itemQuery as $key=>$item){
	
	$itemDOM = new DOMDocument();														//Создаем временный документ DOM
	$itemDOM->appendChild($itemDOM->importNode($item,true));//Загружаем в него данные отдельного объявления
	if ($key==1) echo $itemDOM->saveHTML();//Отладка
	$itemXpath = new DomXPath($itemDOM);										//Закидываем их в объект DomXPath для поиска отдельных компонент
	
	$price = preg_replace("/[^0-9]/", '', $itemXpath->query("//div[contains(@class,'price')]")->item(0)->nodeValue);

  $avitoId = substr($item->getAttribute('id'),1);
  $arFilter = array('UF_CRM_1486619563'=>$avitoId);
  $arSelect = array('ID','STATUS_ID');
  $rs = CCrmLead::GetList(array(), $arFilter, $arSelect);
  $leadId = 0;
	$leadStatus = "отсутствует";
	//Если лид по этому объявлению есть
  if ($curLead = $rs->Fetch()) {
		$leadStatus = CCrmLead::GetSemanticID($curLead['STATUS_ID']);
		$leadId = $curLead['ID'];
	}
  $avitoAds[] = array('avitoId' => $avitoId, 'leadId' => $leadId, 'leadStatus' => $leadStatus, 'price' => $price);
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
    $avitoId = substr($item->getAttribute('id'),1);
    $arFilter = array('UF_CRM_1486619563'=>$avitoId);
    $arSelect = array('ID','STATUS_ID');
    $rs = CCrmLead::GetList(array(), $arFilter, $arSelect);
    $leadId = 0;
		$leadStatus = "отсутствует";
    if ($curLead = $rs->Fetch()) {
			$leadStatus = CCrmLead::GetSemanticID($curLead['STATUS_ID']);
			$leadId = $curLead['ID'];
		}
    $avitoAds[] = array('avitoId' => $avitoId, 'leadId' => $leadId, 'leadStatus' => $leadStatus);
  }  
  $page++;
}

/*Перебор массива $avitoAds для формирования итоговой страницы результатов парсинга,*/
/*производится парсинг всех данных из объявлений по ссылке кроме номера телефона*/
$time = microtime(true) - $start;
echo "Результат парсинга АВИТО, обработано ".$countAd." объявлений за ".$time." секунд.";
?>
<pre>
<?print_r($avitoAds);?>
</pre>