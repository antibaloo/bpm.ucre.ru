<?
CModule::IncludeModule('crm');
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED!==true) die();//Запрет вызова из адресной строки браузера
$rsDeal = CCrmDeal::GetListEx(
	array(),
	array("ID" => $arParams['DEAL_ID']),
	false,
	false,
	array(
		'STAGE_ID', 						//статус заявки
		'UF_CRM_1469534140',		//связанный объект
		'UF_CRM_589C63CD96E82',	//id авито
		'UF_CRM_589C63CE03874',	//ссылка
		'UF_CRM_58A2B07090172',	//профиль
		'UF_CRM_58A2B9F26548F',	//фото авито
		'UF_CRM_58958B52BA439',	//общая
		'UF_CRM_58958B52D6C9B',	//жилая
		'UF_CRM_58958B52F2BAC',	//площадь кухни
		'UF_CRM_5895994ED0C7B',	//этаж
		'UF_CRM_58958B51C2F36',	//этажность
		'UF_CRM_58958B529E628',	//кол-во комнат
		'UF_CRM_5895994EB2646',	//адрес
		'UF_CRM_58958B5207D0C'	//тип дома
	),
	array()
);
$mainDeal = $rsDeal->Fetch();
if ($mainDeal['UF_CRM_589C63CD96E82'] && !$mainDeal["UF_CRM_1469534140"]){
  $arResult['LINK'] = '<a href="'.$mainDeal['UF_CRM_589C63CE03874'].'" target="_blank">'.$mainDeal['UF_CRM_589C63CE03874'].'</a>';
  $arResult['PROFILE'] = '<a href="'.$mainDeal['UF_CRM_58A2B07090172'].'" target="_blank">'.$mainDeal['UF_CRM_58A2B07090172'].'</a>';
  $arResult['PHOTO'] = "";
  foreach (unserialize($mainDeal['UF_CRM_58A2B9F26548F']) as $avitolink){
    $arResult['PHOTO'] .= "<a class='fancybox' rel='image_gallery' href='".$avitolink."'><img style='margin-right: 10px; border:1px solid #cccccc;' src='".$avitolink."' width = 'auto' height ='50'/></a>";
  }
  $total = ($mainDeal['UF_CRM_58958B52BA439'])?$mainDeal['UF_CRM_58958B52BA439']:"-";
  $live = ($mainDeal['UF_CRM_58958B52D6C9B'])?$mainDeal['UF_CRM_58958B52D6C9B']:"-";
  $kitchen = ($mainDeal['UF_CRM_58958B52F2BAC'])?$mainDeal['UF_CRM_58958B52F2BAC']:"-";
  $arResult['SQUARE'] = $total."/".$live."/".$kitchen;
  $floor = ($mainDeal['UF_CRM_5895994ED0C7B'])?$mainDeal['UF_CRM_5895994ED0C7B']:"-";
  $floorall = ($mainDeal['UF_CRM_58958B51C2F36'])?$mainDeal['UF_CRM_58958B51C2F36']:"-";
  $arResult['FLOORS'] = $floor."/".$floorall;
  $arResult['ADDRESS'] = $mainDeal['UF_CRM_5895994EB2646'];
  $arResult['ID'] = $mainDeal['ID'];
  $arResult['BUTTON'] = ($mainDeal['STAGE_ID']!='NEW')? "disabled":"";
  $arResult['RESULT'] = ($mainDeal['STAGE_ID']!='NEW')? "Создать объект можно только в предлистинге":"";
  $this->IncludeComponentTemplate();
}
?>