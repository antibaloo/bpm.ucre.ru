<?
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
    if ($region['id'] == $arResult['regionId']) $arResult['regionList'][] = "{id: ".$region['id'].", text: '".$region['name']."', selected: true}";
    else $arResult['regionList'][] = "{id: ".$region['id'].", text: '".$region['name']."'}";
  }
  $arResult['regionList'] = "[".implode(",",$arResult['regionList'])."]";
  
  $settlements = json_decode(file_get_contents('http://rosreestr.ru/api/online/regions/'.$arResult['regionId']),true);
  if (!$arResult['settlementId']) $arResult['settlementList'] =  array("{id : '', text: 'выберите населенный пункт или его район'}");
  foreach($settlements as $settlement){
    if ($settlement['id'] == $arResult['settlementId'] ) $arResult['settlementList'][] = "{id: ".$settlement['id'].", text: '".$settlement['name']."', selected: true}";
    else $arResult['settlementList'][] = "{id: ".$settlement['id'].", text: '".$settlement['name']."'}";
  }
  $arResult['settlementList'] = "[".implode(",",$arResult['settlementList'])."]";
  if ($arResult['action'] == 'search'){
    
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
?>