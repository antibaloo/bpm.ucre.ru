<?
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED!==true)die();
require($_SERVER['DOCUMENT_ROOT'].'/include/htmlSQL/htmlsql.class.php');
require($_SERVER['DOCUMENT_ROOT'].'/include/htmlSQL/snoopy.class.php');
// подключаем модули
CModule::IncludeModule('iblock');
CModule::IncludeModule('highloadblock');
// необходимые классы
use Bitrix\Highloadblock as HL;
use Bitrix\Main\Entity;
if ($arParams['OBJECT_ID']>0) {//Если передан ID объекта в качестве параметра
  $res = CIBlockElement::GetByID(intval($arParams['OBJECT_ID']));
  $ar_res = $res->GetNext();
  $elementID = ($ar_res['CODE'] > 0)?$ar_res['CODE']: $ar_res['ID'];
  // $hlblock - это массив, 21 - hl блок AvitoLog
  $hlblock   = HL\HighloadBlockTable::getById(21)->fetch();
  $AvitoLog   = HL\HighloadBlockTable::compileEntity( $hlblock );
  $AvitoLogDataClass = $AvitoLog->getDataClass();
  
  // $hlblock - это массив, 22 - hl блок AvitoLogElement
  $hlblock   = HL\HighloadBlockTable::getById(22)->fetch();
  $AvitoLogElement   = HL\HighloadBlockTable::compileEntity( $hlblock );
  $AvitoLogElementDataClass = $AvitoLogElement->getDataClass();
  $rsData = $AvitoLogElementDataClass::getList(
    array(
      "select" => array('*'), //выбираем все поля
      "filter" =>  array('UF_CRM_ID' => $elementID),
      "order" => array("UF_AVITO_LOG_ID"=>"DESC"), // сортировка по полю UF_AVITO_LOG_ID, будет работать только, если вы завели такое поле в hl'блоке
    )
  );
  $arResult['COUNT'] = $rsData->getSelectedRowsCount();
  $arResult['DATA'] = array_slice ($rsData->FetchAll(),0,$arParams['COUNT']);//Вырезаем часть результата в соответствии с активной страницей и кол-вом записей на странице
  $link = $arResult['DATA'][0]['UF_AVITO_LINK'];
  $item = substr(strrchr($link,"_"),1);
  if( $curl = curl_init() ) {
    curl_setopt($curl, CURLOPT_URL, "https://www.avito.ru/items/stat/".$item);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER,true);
    curl_setopt($curl, CURLOPT_COOKIEJAR, $_SERVER['DOCUMENT_ROOT'].'/avitocookie.txt');
    curl_setopt($curl, CURLOPT_COOKIEFILE, $_SERVER['DOCUMENT_ROOT'].'/avitocookie.txt');
    curl_setopt($curl, CURLOPT_FOLLOWLOCATION, false);
    curl_setopt($curl, CURLOPT_REFERER, $link);
    curl_setopt($curl, CURLOPT_USERAGENT, "Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:50.0) Gecko/20100101 Firefox/50.0");
    $content = curl_exec($curl);
    curl_close($curl);
  }
  $wsql = new htmlsql();
  $wsql->connect('string', $content);
  $wsql->query('SELECT * FROM div');
  $divs = $wsql->fetch_array();
  $arResult['DATE'] = substr($divs[0]['text'], strpos($divs[0]['text'],"Дата"));
  $arResult['VIEWS'] = "Всего: ".$divs[1]['text'];
   
  foreach ($arResult['DATA'] as $key => $logItem){//Получаем время загрузки объекта на Авито
    $rsData = $AvitoLogDataClass::getList(
      array(
        "select" => array('UF_TIME'), //выбираем все поля
        "filter" =>  array('UF_AVITO_ID' => $logItem['UF_AVITO_LOG_ID']),
        "order" => array(), 
      )
    );
    $avitoLog = $rsData->Fetch();
    $arResult['DATA'][$key]['UF_TIME'] = $avitoLog['UF_TIME'];
  }
  //echo "<pre>";print_r($arResult);echo "</pre>";
  //echo "<pre>";print_r($arParams);echo "</pre>";
  $template = ($arResult['COUNT'] > 0)?'log':'nolog';
  $this->IncludeComponentTemplate($template);
}else{
  echo "<h2>Нет связанного объекта!<h2>";
}
?>