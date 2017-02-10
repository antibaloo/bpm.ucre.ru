<?
if(!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED!==true) die();
$APPLICATION->IncludeComponent(
	"bitrix:main.interface.toolbar",
	"",
	array(
		"BUTTONS"=>array(
			/*array(
				"TEXT"=>"Добавить новый объект",
				"TITLE"=>"Добавить новый объект недвижимости",
				"LINK"=>"../ro/?edit&id=0",
				"ICON"=>"btn-new",
			),*/
			array(
				"TEXT"=>"Объекты недвижимости",
				"TITLE"=>"Список объектов недвижимости",
				"LINK"=>".",
				"ICON"=>"btn-list",
			),
			array("SEPARATOR"=>true), 
			array(
				"TEXT"=>"База города",
				"TITLE"=>"Список объектов базы города",
				"LINK"=>"../bg/",
				"ICON"=>"btn-list",
			),
		),
	),
	$component
);

if (isset($_REQUEST['id']) && !empty($_REQUEST['id'])){
	echo "Конвертируем ID = ".$_REQUEST['id']."<br>";
	$res = CIBlockElement::GetByID($_REQUEST['id']);
	if($ar_res = $res->GetNextElement()){
		$arFields = $ar_res->GetFields();
		$arProps = $ar_res->GetProperties();
		if ($arProps['STATUS']['VALUE']!='Активный' && $arProps['STATUS']['VALUE']!='Свободный'&& $arProps['STATUS']['VALUE']!='Активная стадия'){die ("Объект не является активным или свободным!");}
		$dealFields = array(
			'OPENED'=>'Y', 
			'ASSIGNED_BY_ID' => $arProps['ASSIGNED_BY']['VALUE'],
			//'COMMENTS'=>$arFields['DETAIL_TEXT'],
			'BEGINDATE'=>$arProps['CONTRACT_B_DATE']['VALUE'],
			'CLOSEDATE'=>$arProps['CONTRACT_E_DATE']['VALUE'],
			//'CURRENCY_ID'=>'RUB',
			//'UF_CRM_579897C010103' => $arProps['PRICE']['VALUE'],
			/*'UF_CRM_1469534546' => number_format($arProps['PRICE']['VALUE'],2,"."," "),*/
			'UF_CRM_1469534140' => $_REQUEST['id'],
			'UF_CRM_1469597462' => $arProps['CONTRACT_NUMBER']['VALUE'],
			'UF_CRM_1469597960' => $arProps['ADDRESS']['VALUE'],
		);
		if ($arProps['CONTRACT_TYPE']['VALUE']=='Стандарт' || $arProps['CONTRACT_TYPE']['VALUE']=='Эксклюзивный'){
			if ($arProps['PRICE']['VALUE']>=3500000){
				$dealFields['OPPORTUNITY'] = $arProps['PRICE']['VALUE'] * 0.02;
			}elseif ($arProps['PRICE']['VALUE']<1500000){
				$dealFields['OPPORTUNITY'] = 50000;
			}else{
				$dealFields['OPPORTUNITY'] = 70000;
			}
		} elseif ($arProps['CONTRACT_TYPE']['VALUE']=='VIP'){
			if ($arProps['PRICE']['VALUE']>=3500000){
				$dealFields['OPPORTUNITY'] = $arProps['PRICE']['VALUE'] * 0.025;
			}elseif ($arProps['PRICE']['VALUE']<1500000){
				$dealFields['OPPORTUNITY'] = 60000;
			}else{
				$dealFields['OPPORTUNITY'] = 85000;
			}
		}
		$dealFields['UF_CRM_1469534140'] = $_REQUEST['id'];
		switch ($arProps['CONTRACT_TYPE']['VALUE']){
			case "":
			case "Без договора":
				$dealFields['UF_CRM_1469533039'] = 415;
				break;
			case "Стандарт":
				$dealFields['UF_CRM_1469533039'] = 416;
				break;
			case "Эксклюзивный":
				$dealFields['UF_CRM_1469533039'] = 417;
				break;
			case "VIP":
				$dealFields['UF_CRM_1469533039'] = 418;
				break;
		}
		
		
		switch ($arProps['TYPE']['VALUE']){
			case 'комната':
				$dealFields['UF_CRM_575FDFDDE0CC4'] = 322;
				break;
			case 'квартира':
				$dealFields['UF_CRM_575FDFDDE0CC4'] = 323;
				break;
			case 'дом':
				$dealFields['UF_CRM_575FDFDDE0CC4'] = 324;
				break;
			case 'таунхаус':
				$dealFields['UF_CRM_575FDFDDE0CC4'] = 414;
				break;
			case 'дача':
				$dealFields['UF_CRM_575FDFDDE0CC4'] = 325;
				break;
			case 'участок':
				$dealFields['UF_CRM_575FDFDDE0CC4'] = 326;
				break;
			case 'коммерческий':
				$dealFields['UF_CRM_575FDFDDE0CC4'] = 327;
				break;
		}
		$dealFields['TITLE'] = ($arProps['OPER']['VALUE']=='Продам')? 'Продажа. '.html_entity_decode($arFields['NAME']):'Аренда. '.html_entity_decode($arFields['NAME']);
		$dealFields['TYPE_ID'] = ($arProps['OPER']['VALUE']=='Продам')?'SALE':'GOODS';
		if($arProps['CATEGORY']['VALUE']=="Новостройки"){
			$dealFields['CATEGORY_ID'] = 4;
		}
		$deal = new CCrmDeal;
		echo $arFields['NAME']." ".$arProps['ADDRESS']['VALUE']."<br>";
		if ($arProps['ID_DEAL']['VALUE']){
			echo "Уже сконвертирован в заявку ID = ".$arProps['ID_DEAL']['VALUE']."<br>";
			if ($deal->Update($arProps['ID_DEAL']['VALUE'],$dealFields)){
				echo "Заявка обновлена!";
			}else {
				echo "Ошибка при обновлении заявки: ".$deal->LAST_ERROR;
			}
		}else{
			echo "Еще не сконвертирован в заявку.<br>";
			if ($dealid = $deal->Add($dealFields)){
				CIBlockElement::SetPropertyValuesEx($_REQUEST['id'], false, array("ID_DEAL" => $dealid));
				echo "Создана заявка ID = ".$dealid."<br>";
			}else {
				echo "Ошибка при создании заявки: ".$deal->LAST_ERROR;
			}
		}
	}else {
		echo " Объект с ID = ".$_REQUEST['id']. " не обнаружен!";
	}
}
if (isset($_REQUEST['id']) && empty($_REQUEST['id'])){
	echo "Не передан ID объекта для конверсии!";
}
//var_dump($dealFields);
/*if (!isset($_REQUEST['id'])){
	$count = 0;
	$add = 0;
	$update = 0;
	$error_add = 0;
	$error_update = 0;
	$arSelect = Array("ID", "IBLOCK_ID", "CODE", "NAME","CREATED_BY","MODIFIED_BY","DATE_CREATE","TIMESTAMP_X", "DATE_ACTIVE_FROM","DETAIL_TEXT","PROPERTY_*");
  $iblock_filter = array ("IBLOCK_ID" => 42,"PROPERTY_266"=>array('Активный','Свободный'));
  $db_res = CIBlockElement::GetList(array("ID"=>"ASC"), $iblock_filter, false, false, $arSelect);
	while($ob = $db_res->GetNextElement()){
    $arFields = $ob->GetFields();
    $arProps = $ob->GetProperties();
		echo "Конвертируем ID = ".$arFields['ID']."<br>";
		$dealFields = array(
			'OPENED'=>'Y', 
			'ASSIGNED_BY_ID' => $arProps['ASSIGNED_BY']['VALUE'],
			'COMMENTS'=>$arFields['DETAIL_TEXT'],
			'BEGINDATE'=>$arProps['CONTRACT_B_DATE']['VALUE'],
			'CLOSEDATE'=>$arProps['CONTRACT_E_DATE']['VALUE'],
			'CURRENCY_ID'=>'RUB',
			'UF_CRM_579897C010103' => $arProps['PRICE']['VALUE'],
			//'UF_CRM_1469534546' => number_format($arProps['PRICE']['VALUE'],2,"."," "),
			'UF_CRM_1469534140' => $_REQUEST['id'],
			'UF_CRM_1469597462' => $arProps['CONTRACT_NUMBER']['VALUE'],
			'UF_CRM_1469597960' => $arProps['ADDRESS']['VALUE'],
		);
		if ($arProps['CONTRACT_TYPE']['VALUE']=='Стандарт' || $arProps['CONTRACT_TYPE']['VALUE']=='Эксклюзивный'){
			if ($arProps['PRICE']['VALUE']>=3500000){
				$dealFields['OPPORTUNITY'] = $arProps['PRICE']['VALUE'] * 0.02;
			}elseif ($arProps['PRICE']['VALUE']<1500000){
				$dealFields['OPPORTUNITY'] = 50000;
			}else{
				$dealFields['OPPORTUNITY'] = 70000;
			}
		} elseif ($arProps['CONTRACT_TYPE']['VALUE']=='VIP'){
			if ($arProps['PRICE']['VALUE']>=3500000){
				$dealFields['OPPORTUNITY'] = $arProps['PRICE']['VALUE'] * 0.025;
			}elseif ($arProps['PRICE']['VALUE']<1500000){
				$dealFields['OPPORTUNITY'] = 60000;
			}else{
				$dealFields['OPPORTUNITY'] = 85000;
			}
		}
		$dealFields['UF_CRM_1469534140'] = $arFields['ID'];
		switch ($arProps['CONTRACT_TYPE']['VALUE']){
			case "":
			case "Без договора":
				$dealFields['UF_CRM_1469533039'] = 415;
				break;
			case "Стандарт":
				$dealFields['UF_CRM_1469533039'] = 416;
				break;
			case "Эксклюзивный":
				$dealFields['UF_CRM_1469533039'] = 417;
				break;
			case "VIP":
				$dealFields['UF_CRM_1469533039'] = 418;
				break;
			case "Простой":
				$dealFields['UF_CRM_1469533039'] = 424;
				break;
		}
		
		
		switch ($arProps['TYPE']['VALUE']){
			case 'комната':
				$dealFields['UF_CRM_575FDFDDE0CC4'] = 322;
				break;
			case 'квартира':
				$dealFields['UF_CRM_575FDFDDE0CC4'] = 323;
				break;
			case 'дом':
				$dealFields['UF_CRM_575FDFDDE0CC4'] = 324;
				break;
			case 'таунхаус':
				$dealFields['UF_CRM_575FDFDDE0CC4'] = 414;
				break;
			case 'дача':
				$dealFields['UF_CRM_575FDFDDE0CC4'] = 325;
				break;
			case 'участок':
				$dealFields['UF_CRM_575FDFDDE0CC4'] = 326;
				break;
			case 'коммерческий':
				$dealFields['UF_CRM_575FDFDDE0CC4'] = 327;
				break;
		}
		$dealFields['TITLE'] = ($arProps['OPER']['VALUE']=='Продам')? 'Продажа. '.html_entity_decode($arFields['NAME']):'Аренда. '.html_entity_decode($arFields['NAME']);
		$dealFields['TYPE_ID'] = ($arProps['OPER']['VALUE']=='Продам')?'SALE':'GOODS';
		$deal = new CCrmDeal;
		echo $arFields['NAME']." ".$arProps['ADDRESS']['VALUE']."<br>";
		if ($arProps['ID_DEAL']['VALUE']){
			echo "Уже сконвертирован в заявку ID = ".$arProps['ID_DEAL']['VALUE']."<br>";
			if ($deal->Update($arProps['ID_DEAL']['VALUE'],$dealFields)){
				echo "Заявка обновлена!<br>";
				$update++;
			}else {
				echo "Ошибка при обновлении заявки: ".$deal->LAST_ERROR."<br>";
				$error_update++;
			}
		}else{
			echo "Еще не сконвертирован в заявку.<br>";
			if ($dealid = $deal->Add($dealFields)){
				CIBlockElement::SetPropertyValuesEx($arFields['ID'], false, array("ID_DEAL" => $dealid));
				echo "Создана заявка ID = ".$dealid."<br>";
				$add++;
			}else {
				echo "Ошибка при создании заявки: ".$deal->LAST_ERROR."<br>";
				$error_add++;
			}
		}
		$count++;
	}
	echo "Всего обработано ".$count." объектов, успешно ".$add+$update." объектов, их них создано ".$add.", обновлено ".$update.". Ошибок создания: ">$error_add.", обшибок обновления: ".$error_update;
}*/
?>