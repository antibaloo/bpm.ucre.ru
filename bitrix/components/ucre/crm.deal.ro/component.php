<?
CModule::IncludeModule('crm');
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED!==true) die();//Запрет вызова из адресной строки браузера
$rsDeal = CCrmDeal::GetListEx(
	array(),
	array("ID" => $arParams['DEAL_ID']),
	false,
	false,
	array("CATEGORY_ID", "UF_CRM_1469534140"),
	array()
);
$mainDeal = $rsDeal->Fetch();
if ($mainDeal["CATEGORY_ID"] == 0 || $mainDeal["CATEGORY_ID"] == 4){
	if ($mainDeal["UF_CRM_1469534140"]){
    $rsObject = CIBlockElement::GetById($mainDeal["UF_CRM_1469534140"]);
    $mainObject = $rsObject->GetNextElement();
    $objectFields = $mainObject->GetFields();
    $objectProperties = $mainObject->GetProperties();
    $arResult['ADDRESS'] = ($objectProperties['ADDRESS']['VALUE'])?$objectProperties['ADDRESS']['VALUE']:'<span style="color: red">нет данных</span>';
    $arResult['LINK'] = ($objectProperties['LINK']['VALUE'])?'<a href="'.$objectProperties['LINK']['VALUE'].'" traget="_blank">'.$objectProperties['LINK']['VALUE'].'</a>':'<span style="color: red">нет данных</span>';
    switch ($objectProperties['TYPE']['VALUE']) {
			case 'комната':
				if ($objectProperties['ISHOSTEL']['VALUE'] == 'да'){
					$arResult['TYPE'] = $objectProperties['TYPE']['VALUE']." в общежитии";
				}elseif ($objectProperties['ISHOSTEL']['VALUE'] == 'нет'){
					$rooms = ($objectProperties['ROOMS']['VALUE'])?$objectProperties['ROOMS']['VALUE']."-к ":'<span style="color: red">?-к </span>';
					$arResult['TYPE'] = $objectProperties['TYPE']['VALUE']." в ".$rooms." квартире";
				}else {
					$arResult['TYPE'] = $objectProperties['TYPE']['VALUE']." неизвестно где";
				}
				$arResult['ROOM_AREA'] = ($objectProperties['ROOM_AREA']['VALUE'])?$objectProperties['ROOM_AREA']['VALUE']." м<sup>2</sup>":'<span style="color: red">нет данных</span>';
				$arResult['HOUSE_TYPE'] = ($objectProperties['HOUSE_TYPE']['VALUE'])?$objectProperties['HOUSE_TYPE']['VALUE']:'<span style="color: red">нет данных</span>';
				$arResult['FLOOR'] = ($objectProperties['FLOOR']['VALUE'])?$objectProperties['FLOOR']['VALUE']:'<span style="color: red">нет данных</span>';
        $arResult['FLOORALL'] = ($objectProperties['FLOORALL']['VALUE'])?$objectProperties['FLOORALL']['VALUE']:'<span style="color: red">нет данных</span>';
        $componentPage = 'room';
        break;
      case 'квартира':
        $rooms = ($objectProperties['ROOMS']['VALUE'])?$objectProperties['ROOMS']['VALUE']."-к ":'<span style="color: red">?-к </span>';
        $arResult['TYPE'] = $rooms.$objectProperties['TYPE']['VALUE'];
        $arResult['TOTAL_AREA'] = ($objectProperties['TOTAL_AREA']['VALUE'])?$objectProperties['TOTAL_AREA']['VALUE']." м<sup>2</sup>":'<span style="color: red">нет данных</span>';
        $arResult['LIVE_AREA'] = ($objectProperties['LIVE_AREA']['VALUE'])?$objectProperties['LIVE_AREA']['VALUE']." м<sup>2</sup>":'<span style="color: red">нет данных</span>';
        $arResult['KITCHEN_AREA'] = ($objectProperties['KITCHEN_AREA']['VALUE'])?$objectProperties['KITCHEN_AREA']['VALUE']." м<sup>2</sup>":'<span style="color: red">нет данных</span>';
        $arResult['HOUSE_TYPE'] = ($objectProperties['HOUSE_TYPE']['VALUE'])?$objectProperties['HOUSE_TYPE']['VALUE']:'<span style="color: red">нет данных</span>';
        $arResult['FLOOR'] = ($objectProperties['FLOOR']['VALUE'])?$objectProperties['FLOOR']['VALUE']:'<span style="color: red">нет данных</span>';
        $arResult['FLOORALL'] = ($objectProperties['FLOORALL']['VALUE'])?$objectProperties['FLOORALL']['VALUE']:'<span style="color: red">нет данных</span>';
        $componentPage = 'flat';
        break;
      case 'дом':
			case 'таунхаус':
			case 'дача':
        $arResult['TYPE'] = $objectProperties['TYPE']['VALUE'];
				$arResult['TOTAL_AREA'] = ($objectProperties['TOTAL_AREA']['VALUE'])?$objectProperties['TOTAL_AREA']['VALUE']." м<sup>2</sup>":'<span style="color: red">нет данных</span>';
				$arResult['PLOT_AREA'] = ($objectProperties['PLOT_AREA']['VALUE'])?$objectProperties['PLOT_AREA']['VALUE']." соток":'<span style="color: red">нет данных</span>';
				$arResult['FLOORALL'] = ($objectProperties['FLOORALL']['VALUE'])?$objectProperties['FLOORALL']['VALUE']:'<span style="color: red">нет данных</span>';
				$arResult['WALLS'] = ($objectProperties['WALLS']['VALUE'])?$objectProperties['WALLS']['VALUE']:'<span style="color: red">нет данных</span>';
        $componentPage = 'house';
        break;
       case 'участок':
        $arResult['TYPE'] = $objectProperties['TYPE']['VALUE'];
				$arResult['PLOT_AREA'] = ($objectProperties['PLOT_AREA']['VALUE'])?$objectProperties['PLOT_AREA']['VALUE']." соток":'<span style="color: red">нет данных</span>';
				$arResult['PLOT_CAT'] = ($objectProperties['PLOT_CAT']['VALUE'])?$objectProperties['PLOT_CAT']['VALUE']:'<span style="color: red">нет данных</span>';
        $componentPage = 'plot';
        break;
      case 'коммерческий':
        $arResult['TYPE'] = $objectProperties['TYPE']['VALUE'];
				$arResult['APPOINTMENT'] = ($objectProperties['APPOINTMENT']['VALUE'])?$objectProperties['APPOINTMENT']['VALUE']:'<span style="color: red">нет данных</span>';
				$arResult['GUARD'] = ($objectProperties['GUARD']['VALUE'])?$objectProperties['GUARD']['VALUE']:'<span style="color: red">нет данных</span>';
				$arResult['PARKING'] = ($objectProperties['PARKING']['VALUE'])?$objectProperties['PARKING']['VALUE']:'<span style="color: red">нет данных</span>';
        $componentPage = 'comm';
        break;
    }
    $this->IncludeComponentTemplate($componentPage);
  }else{
    $this->IncludeComponentTemplate('no_object');
  }
}
?>