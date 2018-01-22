<?
# Инициализируем профайлер
xhprof_enable(XHPROF_FLAGS_CPU + XHPROF_FLAGS_MEMORY);

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED!==true)die();
$macroRegions = json_decode(file_get_contents('http://rosreestr.ru/api/online/macro_regions'),true);

if ($_POST['AJAX_CALL'] == 'Y'){//вызов из формы
  foreach ($_POST as $key=>$value) $arResult[$key] = $value;
  foreach($macroRegions as $macroRegion){
    if ($macroRegion['id'] == $arResult['macroRegionId']) $arResult['macroRegionList'][] =  "{id: ".$macroRegion['id'].", text: '".$macroRegion['name']."', selected: true}";
    else $arResult['macroRegionList'][] = "{id: ".$macroRegion['id'].", text: '".$macroRegion['name']."'}";
  }
  $arResult['macroRegionList'] = "[".implode(",",$arResult['macroRegionList'])."]";
  
  $regions = json_decode(file_get_contents('http://rosreestr.ru/api/online/regions/'.$arResult['macroRegionId']),true);
  foreach($regions as $region){
    if ($region['id'] == $arResult['RegionId']) $arResult['regionList'][] = "{id: ".$region['id'].", text: '".$region['name']."', selected: true}";
    else $arResult['regionList'][] = "{id: ".$region['id'].", text: '".$region['name']."'}";
  }
  $arResult['regionList'] = "[".implode(",",$arResult['regionList'])."]";
  
  $settlements = json_decode(file_get_contents('http://rosreestr.ru/api/online/regions/'.$arResult['RegionId']),true);
  if (!$arResult['settlementId']) $arResult['settlementList'] =  array("{id : '', text: 'выберите населенный пункт или его район'}");
  foreach($settlements as $settlement){
    if ($settlement['id'] == $arResult['settlementId'] ) $arResult['settlementList'][] = "{id: ".$settlement['id'].", text: '".$settlement['name']."', selected: true}";
    else $arResult['settlementList'][] = "{id: ".$settlement['id'].", text: '".$settlement['name']."'}";
  }
  $arResult['settlementList'] = "[".implode(",",$arResult['settlementList'])."]";
  if ($arResult['action'] == 'search'){
    $arResult['params'] = array();
    $arResult['errors'] = array();
    if ($arResult['macroRegionId']) $arResult['params']['macroRegionId'] = $arResult['macroRegionId'];
    else $arResult['errors'][] = 'Не задан код макрорегиона';
    
    if ($arResult['RegionId']) $arResult['params']['RegionId'] = $arResult['RegionId'];
    else $arResult['errors'][] = 'Не задан регион поиска/населенный пункт';
    
    if ($arResult['settlementId']) $arResult['params']['settlementId'] = $arResult['settlementId'];
    else $arResult['errors'][] = 'Не задан населенный пункт/район н.п.';
    
    if ($arResult['street']) $arResult['params']['street'] = $arResult['street'];
    else $arResult['errors'][] = 'Не задана улица';
    
    if ($arResult['house']) $arResult['params']['house'] = $arResult['house'];
    else $arResult['errors'][] = 'Не задан номер дома';
    
    if ($arResult['apartment']) $arResult['params']['apartment'] = $arResult['apartment'];
    else $arResult['errors'][] = 'Не задан номер квартиры';
    $arResult['request'] = 'http://rosreestr.ru/api/online/address/fir_objects?'.http_build_query($arResult['params']);
    $arResult['objects'] = json_decode(file_get_contents($arResult['request']));

    
  }
}else{//Чистый вызов
  foreach($macroRegions as $macroRegion){
    if ($macroRegion['id'] == '153000000000') $arResult['macroRegionList'][] =  "{id: ".$macroRegion['id'].", text: '".$macroRegion['name']."', selected: true}";
    else $arResult['macroRegionList'][] = "{id: ".$macroRegion['id'].", text: '".$macroRegion['name']."'}";
  }
  $arResult['macroRegionList'] = "[".implode(",",$arResult['macroRegionList'])."]";
  
  $regions = json_decode(file_get_contents('http://rosreestr.ru/api/online/regions/153000000000'),true);
  foreach($regions as $region){
    if ($region['id'] == '153401000000') $arResult['regionList'][] = "{id: ".$region['id'].", text: '".$region['name']."', selected: true}";
    else $arResult['regionList'][] = "{id: ".$region['id'].", text: '".$region['name']."'}";
  }
  $arResult['regionList'] = "[".implode(",",$arResult['regionList'])."]";
  
  $settlements = json_decode(file_get_contents('http://rosreestr.ru/api/online/regions/153401000000'),true);
  $arResult['settlementList'] =  array("{id : '', text: 'выберите населенный пункт или его район'}");
  foreach($settlements as $settlement){
    $arResult['settlementList'][] = "{id: ".$settlement['id'].", text: '".$settlement['name']."'}";
  }
  $arResult['settlementList'] = "[".implode(",",$arResult['settlementList'])."]";
}

//echo "<pre>";print_r($_POST);echo "</pre>";
$this->IncludeComponentTemplate();

$xhprof_data = xhprof_disable();
include_once "/home/bitrix/xhprof-0.9.4/xhprof_lib/utils/xhprof_lib.php";
include_once "/home/bitrix/xhprof-0.9.4/xhprof_lib/utils/xhprof_runs.php";
$xhprof_runs = new XHProfRuns_Default();
$run_id = $xhprof_runs->save_run($xhprof_data, "rosreest");
?>