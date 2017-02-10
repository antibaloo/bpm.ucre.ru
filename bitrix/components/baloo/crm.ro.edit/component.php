<?
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED!==true)die();//Запрет вызова из адресной строки браузера

//Form submitted
if($_SERVER["REQUEST_METHOD"] == "POST" && check_bitrix_sessid())
{
  
  $PROP = array();
  $PROP[206] = $_POST['PROPERTY_206']; //Связанный объект БГ, берется не из формы, а из значения БД
  $PROP[209] = $_POST['PROPERTY_209'];//Адрес
  $PROP[210] = $_POST['PROPERTY_210'];//Тип объекта
  $PROP[212] = $_POST['PROPERTY_212'];//Кадастровый номер
  $PROP[213] = $_POST['PROPERTY_213'];//Субъект федерации
  $PROP[214] = $_POST['PROPERTY_214'];//Район субъекта
  $PROP[215] = $_POST['PROPERTY_215'];//Населенный пункт
  $PROP[216] = $_POST['PROPERTY_216'];//Район
  $PROP[217] = $_POST['PROPERTY_217'];//Улица
  $PROP[218] = $_POST['PROPERTY_218'];//Номер дома
  $PROP[219] = $_POST['PROPERTY_219'];//Подъезд
  $PROP[220] = $_POST['PROPERTY_220'];//Квартира
  $PROP[221] = $_POST['PROPERTY_221'];//Этаж
  $PROP[222] = $_POST['PROPERTY_222'];//Этажность
  $PROP[223] = $_POST['PROPERTY_223'];//Индекс
  $PROP[224] = $_POST['PROPERTY_224'];//Общая площадь
  $PROP[225] = $_POST['PROPERTY_225'];//Жилая площадь
  $PROP[226] = $_POST['PROPERTY_226'];//Площадь кухни
  $PROP[227] = $_POST['PROPERTY_227'];//Площади комнат ХХХ/ХХХ/ХХХ
  $PROP[228] = $_POST['PROPERTY_228'];//Площадь комнаты
  $PROP[229] = $_POST['PROPERTY_229'];//Количество комнат
  
  
  $el = new CIBlockElement;
   //When Save or Apply buttons was pressed
   if(isset($_POST["save"]) || isset($_POST["apply"]))
   {
     if(isset($_POST["save"])){
       Header("Location: https://bpm.ucre.ru/crm/ro/");
     }
      //Gather fields for update
      $arFields = array(
        "ACTIVE" => $_POST["ACTIVE"],
        "MODIFIED_BY" => $USER->GetID(),
        "PROPERTY_VALUES"=> $PROP,
      );
     if (isset($_REQUEST["copy"])) {
       $el->Add($arFields);
     } else {
      $el->Update($_REQUEST["id"], $arFields);
     }
   }
}

$arSelect = Array("ID", "IBLOCK_ID", "CODE","ACTIVE", "NAME","CREATED_BY","MODIFIED_BY","DATE_CREATE","TIMESTAMP_X", "DATE_ACTIVE_FROM","PROPERTY_*");
$db_res = CIBlockElement::GetList(Array(), array("IBLOCK_ID" => 42, "ID" => $_REQUEST['id']), false, Array(), $arSelect);
$aRes = $db_res->GetNext();
$arResult['FORM_ID'] = 'ro_form';
$arResult['DATA'] = $aRes;
$arResult['USERS'] = $arParams['USERS'];



switch ($arResult['DATA']['PROPERTY_210']) {
  case 'комната':
  case 'Комната':
  case '381':
    $componentPage = 'room';
    break;
  case 'квартира':
  case 'Квартира':
  case '382':
    $componentPage = 'flat';
    break;
  case 'дом':
  case 'Дом':
  case '383':
    $componentPage = 'house';
    break;
  case 'таунхаус':
  case 'Таунхаус':
  case '384':
    $componentPage = 'townhouse';
    break;
  case 'дача':
  case 'Дача':
  case '385':
    $componentPage = 'dacha';
    break;
  case 'участок':
  case 'Участок':
  case '386':
    $componentPage = 'plot';
    break;
  case 'коммерческий':
  case 'Коммерческая':
  case '387':
    $componentPage = 'comm';
    break;
  default:
    $componentPage = 'new';
}
$this->IncludeComponentTemplate($componentPage);
?>