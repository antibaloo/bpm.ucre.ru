<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Импорт объектов недвижимости");
$APPLICATION->IncludeComponent(
	"bitrix:main.interface.toolbar",
	"",
	array(
		"BUTTONS"=>array(
			array(
				"TEXT"=>"Добавить новый объект",
				"TITLE"=>"Добавить новый объект недвижимости",
				"LINK"=>"../ro/?edit&id=0",
				"ICON"=>"btn-new",
			),
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
      array(
				"TEXT"=>"Импорт объектов недвижимости",
				"TITLE"=>"Импорт объектов недвижимости",
				"LINK"=>"import.php",
				"ICON"=>"btn-list",
			),
		),
	),
	$component
);
?>
<?

if ($USER->GetID() == 24) {
}else{
	die("Вы не тот, кому это позволено!");
}
$ecrplus_ro = new DOMDocument();
$ecrplus_ro->load('http://ecrplus.ru/orenburg_bpm.xml');

$objects = $ecrplus_ro->getElementsByTagName('Object');
$num = 0;
$none = 0;
$est = 0;
$added = 0;
foreach ($objects as $object) {
	if ($added==70) die("Достаточно!");
  $num++;
  $arFields = array();
  $PROP = array();
  foreach($object->childNodes as $nodename){
    switch ($nodename->nodeName){
      case "Id":
        $arSelect = Array("ID", "IBLOCK_ID", "CODE","ACTIVE", "NAME","CREATED_BY","MODIFIED_BY","DATE_CREATE","TIMESTAMP_X", "DATE_ACTIVE_FROM","PROPERTY_*");
        $db_res = CIBlockElement::GetList(Array(), array("IBLOCK_ID" => 42, "CODE" => $nodename->nodeValue ), false, Array(), $arSelect);
        if ($aRes = $db_res->GetNext()){
          $ID = $aRes['ID'];
          $arFields['CODE'] = $nodename->nodeValue;
          $est++;
					continue 3;
        }else {
          $ID = 0;
          $arFields['CODE'] = $nodename->nodeValue;
          $none++;
        }
        break;
			case "STATUS":
				$PROP[266] = $nodename->nodeValue;
				break;
			case "Operation":
				$PROP[300] = $nodename->nodeValue;
				break;
			case "DATE_CREATE":
				$temp1 = explode(" ",$nodename->nodeValue);
				$crdate = explode("-",$temp1[0]);
				$crtime = explode(":",$temp1[1]);
				$arFields['DATE_CREATE'] = $crdate[2].".".$crdate[1].".".$crdate[0]." ".$crtime[0].":".$crtime[1].":".$crtime[2];
				break;
      case "Type":
				switch ($nodename->nodeValue) {
					case "комната":
						$PROP[210] = 381;
						break;
					case "квартира":
						$PROP[210] = 382;
						break;
					case "дом":
						$PROP[210] = 383;
						break;
					case "таунхаус":
						$PROP[210] = 384;
						break;
					case "дача":
						$PROP[210] = 385;
						break;
					case "участок":
						$PROP[210] = 386;
						break;
					case "коммерческий":
						$PROP[210] = 387;
						break;
				}
        break;
			case "Appointment":
				switch ($nodename->nodeValue){
					case "Бизнес-центр":
						$PROP[238] = 388;
						break;
					case "Гараж":
						$PROP[238] = 389;
						break;
					case "Гостиница":
						$PROP[238] = 390;
						break;
					case "Иное":
						$PROP[238] = 391;
						break;
					case "Магазин":
						$PROP[238] = 392;
						break;
					case "Отдельно стоящее здание":
						$PROP[238] = 393;
						break;
					case "Офис":
						$PROP[238] = 394;
						break;
					case "Помещение свободного назначения":
						$PROP[238] = 395;
						break;
					case "Предприятие питания":
						$PROP[238] = 396;
						break;
					case "Производственно-промышленное помещение":
						$PROP[238] = 397;
						break;
					case "Склад":
						$PROP[238] = 398;
						break;
					case "Торговый центр":
						$PROP[238] = 399;
						break;
				}
				break;
			case "OWNER_TYPE":
				$PROP[246] = $nodename->nodeValue;
				break;
			case "OWNER_TITLE":
				$PROP[245] = $nodename->nodeValue;
				break;
			case "OWNER_FIO":
				$PROP[244] = $nodename->nodeValue;
				break;
			case "OWNER_PHONE":
				$PROP[247] = $nodename->nodeValue;
				break;
			case "OWNER_OTHER":
				$PROP[248] = $nodename->nodeValue;
				break;
			case "OWNER_FAX":
				$PROP[249] = $nodename->nodeValue;
				break;
			case "OWNER_EMAIL1":
				$PROP[250] = $nodename->nodeValue;
				break;
			case "OWNER_EMAIL2":
				$PROP[251] = $nodename->nodeValue;
				break;
			case "OWNER_WEBSITE":
				$PROP[252] = $nodename->nodeValue;
				break;
			case "OWNER_INN":
				$PROP[253] = $nodename->nodeValue;
				break;
			case "OWNER_KPP":
				$PROP[254] = $nodename->nodeValue;
				break;
			case "OWNER_MOBILE":
				$PROP[255] = $nodename->nodeValue;
				break;
			case "OWNER_HOME":
				$PROP[256] = $nodename->nodeValue;
				break;
			case "OWNER_BIRTHDAY":
				if ($nodename->nodeValue!=""){
					$temp1 = explode(" ",$nodename->nodeValue);
					$crdate = explode("-",$temp1[0]);
					$crtime = explode(":",$temp1[1]);
					$PROP[257] = $crdate[2].".".$crdate[1].".".$crdate[0]." 00:00:00";
				}
				break;
			case "AVITO_UPLOAD_DATE":
				if ($nodename->nodeValue!=""){
					$temp1 = explode(" ",$nodename->nodeValue);
					$crdate = explode("-",$temp1[0]);
					$crtime = explode(":",$temp1[1]);
					$PROP[260] = $crdate[2].".".$crdate[1].".".$crdate[0]." 00:00:00";
				}
				break;
			case "AVITO_NEWBUILDING_ID":
				$PROP[258] = $nodename->nodeValue;
				break;
			case "PRICE":
				$PROP[259] = $nodename->nodeValue;
				break;
			case "CONTRACT_NUMBER":
				$PROP[261] = $nodename->nodeValue;
				break;
			case "CONTRACT_TYPE":
				$PROP[262] = $nodename->nodeValue;
				break;
			case "CONTRACT_B_DATE":
				if ($nodename->nodeValue!=""){
					$temp1 = explode(" ",$nodename->nodeValue);
					$crdate = explode("-",$temp1[0]);
					$crtime = explode(":",$temp1[1]);
					$PROP[263] = $crdate[2].".".$crdate[1].".".$crdate[0]." 00:00:00";
				}
				break;
			case "CONTRACT_E_DATE":
				if ($nodename->nodeValue!=""){
					$temp1 = explode(" ",$nodename->nodeValue);
					$crdate = explode("-",$temp1[0]);
					$crtime = explode(":",$temp1[1]);
					$PROP[264] = $crdate[2].".".$crdate[1].".".$crdate[0]." 00:00:00";
				}
				break;
      case "Region":
        $PROP[213] = $nodename->nodeValue;
				$region = explode(" ",$nodename->nodeValue);
				$PROP[209] = $region[1].". ".$region[0];
        break;
      case "District":
        $PROP[214] = $nodename->nodeValue;
				$district = explode(" ",$nodename->nodeValue);
				$PROP[209].= ($nodename->nodeValue == "обл. подчинения") ? "": ", ".$district[1]." ".$district[0];
        break;
      case "City":
        $PROP[215] = $nodename->nodeValue;
				$city = explode(" ",$nodename->nodeValue);
				$PROP[209].=", ".$city[1].". ".$city[0];
        break;
      case "Locality":
        $PROP[216] = $nodename->nodeValue;
        break;
      case "Street":
        $PROP[217] = $nodename->nodeValue;
				$street = explode (" ",$nodename->nodeValue);
				$PROP[209].=", ".$street[1].". ".$street[0];
        break;
      case "House":
        $PROP[218] = $nodename->nodeValue;
				$PROP[209].=", д. ".$nodename->nodeValue;
        break;
      case "Access":
        $PROP[219] = $nodename->nodeValue;
        break;
      case "Flat":
        $PROP[220] = $nodename->nodeValue;
				$PROP[209].=($PROP[210] == "квартира" || $PROP[210] == "комната") ? ", кв. ".$nodename->nodeValue : "";
        break;
      case "Floor":
        $PROP[221] = $nodename->nodeValue;
        break;
      case "FloorAll":
        $PROP[222] = $nodename->nodeValue;
        break;
      case "Rooms":
        $PROP[229] = $nodename->nodeValue;
        break;
      case "AreaTotal":
        $PROP[224] = $nodename->nodeValue;
        break;
      case "AreaLiving":
        $PROP[225] = $nodename->nodeValue;
        break;
      case "AreaKitchen":
        $PROP[226] = $nodename->nodeValue;
        break;
			case "BALKON_AREA":
				$PROP[239] = $nodename->nodeValue;
        break;
			case "Wall":
				switch ($nodename->nodeValue){
					case "блок":
						$PROP[242] = 413;
						break;
					case "бревно":
						$PROP[242] =414;
						break;
					case "брус":
						$PROP[242] =415;
						break;
					case "иное":
						$PROP[242] =416;
						break;
					case "каркасно-щитовой":
						$PROP[242] =417;
						break;
					case "кирпич":
						$PROP[242] =418;
						break;
					case "монолит":
						$PROP[242] =419;
						break;
					case "нет":
						$PROP[242] =420;
						break;
					case "оцилиндрованное бревно":
						$PROP[242] =421;
						break;
					case "панели":
						$PROP[242] =422;
						break;
					case "пеноблок":
						$PROP[242] =423;
						break;
					case "сендвич":
						$PROP[242] =424;
						break;
					case "шлакоблок":
						$PROP[242] =425;
						break;
				}
        break;
			case "HouseType":
				switch ($nodename->nodeValue){
					case "Блочный":
						$PROP[243] =426;
						break;
					case "Деревянный":
						$PROP[243] =427;
						break;
					case "Кирпичный":
						$PROP[243] =428;
						break;
					case "Монолитно-кипичный":
						$PROP[243] =429;
						break;
					case "Монолитный":
						$PROP[243] =430;
						break;
					case "Панельный":
						$PROP[243] =431;
						break;
					case "Сталинский":
						$PROP[243] =432;
						break;
					case "Элитный":
						$PROP[243] =433;
						break;
				}
        break;
			case "AreaHouse":
				$PROP[265] = $nodename->nodeValue;
				break;
			case "HEIGHT":
				$PROP[267] = $nodename->nodeValue;
				break;
			case "BUILD_YEAR":
				$PROP[268] = $nodename->nodeValue;
				break;
			case "Guard":
				$PROP[269] = $nodename->nodeValue;
				break;
			case "Condition":
				switch($nodename->nodeValue){
					case "без отделки":
						$PROP[270] =434;
						break;
					case "евроремонт":
						$PROP[270] =435;
						break;
					case "отличное состояние":
						$PROP[270] =436;
						break;
					case "первичная отделка":
						$PROP[270] =437;
						break;
					case "плохое состояние":
						$PROP[270] =438;
						break;
					case "сделан ремонт":
						$PROP[270] =439;
						break;
					case "среднее состояние":
						$PROP[270] =440;
						break;
					case "требуется капитальный ремонт":
						$PROP[270] =441;
						break;
					case "требуется ремонт":
						$PROP[270] =442;
						break;
					case "хорошее состояние":
						$PROP[270] =443;
						break;
					case "эксклюзивный евроремонт":
						$PROP[270] =444;
						break;
				}
				break;
			case "ANGLE":
				switch($nodename->nodeValue){
					case 0:
						$PROP[271]=464;
						break;
					case 1:
						$PROP[271]=463;
						break;
				}
				break;
			case "WC":
				switch($nodename->nodeValue){
					case "2 совмещенных":
						$PROP[272]=445;
						break;
					case "2 раздельных":
						$PROP[272]=446;
						break;
					case "2 санузла":
						$PROP[272]=447;
						break;
					case "3 раздельных":
						$PROP[272]=448;
						break;
					case "3 санузла":
						$PROP[272]=449;
						break;
					case "3 совмещенных":
						$PROP[272]=450;
						break;
					case "4 раздельных":
						$PROP[272]=451;
						break;
					case "4 санузла":
						$PROP[272]=452;
						break;
					case "4 раздельных":
						$PROP[272]=453;
						break;
					case "Есть":
						$PROP[272]=454;
						break;
					case "Нет":
						$PROP[272]=455;
						break;
					case "Раздельный":
						$PROP[272]=456;
						break;
					case "Совмещенный":
						$PROP[272]=457;
						break;
				}
				break;	
			case "STOVE":
				switch($nodename->nodeValue){
					case "нет":
						$PROP[273]=458;
						break;
					case "газовая":
						$PROP[273]=459;
						break;
					case "электрическая":
						$PROP[273]=460;
						break;
				}
				break;
			case "NEGH_ROOMS":
				switch($nodename->nodeValue){
					case 0:
						$PROP[274]=465;
						break;
					case 1:
						$PROP[274]=466;
						break;
				}
				break;
			case "Window":
				switch($nodename->nodeValue){
					case "окна во двор":
						$PROP[275]=467;
						break;
					case "окна во двор и на улицу":
						$PROP[275]=468;
						break;
					case "окна на улицу":
						$PROP[275]=469;
						break;
				}
				break;
			case "WINDOWS_MAT":
				switch($nodename->nodeValue){
					case "дерево":
						$PROP[276]=470;
						break;
					case "пластик":
						$PROP[276]=471;
						break;
					case "аллюминий":
						$PROP[276]=472;
						break;
				}
				break;
			case "Elevator":
				$PROP[277] = $nodename->nodeValue;
				break;
			case "BalconyType":
				switch($nodename->nodeValue){
					case "2 балкона":
						$PROP[241]=400;
						break;
					case "2 балкона, 2 лоджии":
						$PROP[241]=401;
						break;
					case "2 лоджии":
						$PROP[241]=402;
						break;
					case "3 балкона":
						$PROP[241]=403;
						break;
					case "3 лоджии":
						$PROP[241]=404;
						break;
					case "4 лоджии":
						$PROP[241]=405;
						break;
					case "балкон":
						$PROP[241]=406;
						break;
					case "Балкон, 2 лоджии":
						$PROP[241]=407;
						break;
					case "Балкон, лоджия":
						$PROP[241]=408;
						break;
					case "лоджия":
						$PROP[241]=409;
						break;
					case "нет":
						$PROP[241]=410;
						break;
					case "Эркер":
						$PROP[241]=411;
						break;
					case "Эркер и лоджия":
						$PROP[241]=412;
						break;
				}
				break;
			case "ISFURNITURE":
				$PROP[278] = $nodename->nodeValue;
				break;	
			case "GARBAGE":
				$PROP[279] = $nodename->nodeValue;
				break;
			case "Direction":
				switch($nodename->nodeValue){
					case "в черте города":
						$PROP[280]=473;
						break;
					case "Беляевское шоссе":
						$PROP[280]=474;
						break;
					case "Нежинское шоссе":
						$PROP[280]=475;
						break;
					case "Илекское шоссе":
						$PROP[280]=476;
						break;
					case "Шарлыкское шоссе":
						$PROP[280]=477;
						break;
					case "Р-239 (Донгузская улица)":
						$PROP[280]=478;
						break;
					case "Самарская трасса (Цвиллинга улица)":
						$PROP[280]=479;
						break;
					case "Р-240 (Терешковой улица)":
						$PROP[280]=480;
						break;
				}
				break;
			case "TOKAD":
				$PROP[281] = $nodename->nodeValue;
				break;
			case "Parking":
				$PROP[282] = $nodename->nodeValue;
				break;
			case "SNT":
				$PROP[283] = $nodename->nodeValue;
				break;
			case "Water":
				switch($nodename->nodeValue){
					case "Есть":
						$PROP[284]=481;
						break;
					case "иное":
						$PROP[284]=482;
						break;
					case "колодец":
						$PROP[284]=483;
						break;
					case "магистральный":
						$PROP[284]=484;
						break;
					case "Нет":
						$PROP[284]=485;
						break;
					case "скважина":
						$PROP[284]=486;
						break;
					case "центральный":
						$PROP[284]=487;
						break;
				}
				break;
			case "Gas":
				switch($nodename->nodeValue){
					case "баллоны":
						$PROP[285]=488;
						break;
					case "иное":
						$PROP[285]=489;
						break;
					case "магистральный":
						$PROP[285]=490;
						break;
					case "Нет":
						$PROP[285]=491;
						break;
					case "перспектива":
						$PROP[285]=492;
						break;
					case "на границе":
						$PROP[285]=493;
						break;
					case "рядом":
						$PROP[285]=494;
						break;
				}
				break;
			case "Electricity":
				switch($nodename->nodeValue){
					case "10 КВт":
						$PROP[286]=495;
						break;
					case "220 В":
						$PROP[286]=496;
						break;
					case "380 В":
						$PROP[286]=497;
						break;
					case "есть":
						$PROP[286]=498;
						break;
					case "иное":
						$PROP[286]=499;
						break;
					case "нет":
						$PROP[286]=500;
						break;
					case "перспектива":
						$PROP[286]=501;
						break;
					case "по границе":
						$PROP[286]=502;
						break;
				}
				break;
			case "Sewage":
				switch($nodename->nodeValue){
					case "вне дома":
						$PROP[287]=503;
						break;
					case "есть":
						$PROP[287]=504;
						break;
					case "иное":
						$PROP[287]=505;
						break;
					case "нет":
						$PROP[287]=506;
						break;
					case "септик":
						$PROP[287]=507;
						break;
					case "центральная":
						$PROP[287]=508;
						break;
				}
				break;
			case "Heating":
				switch($nodename->nodeValue){
					case "АГВ":
						$PROP[288]=509;
						break;
					case "газовый котел":
						$PROP[288]=510;
						break;
					case "есть":
						$PROP[288]=511;
						break;
					case "жидкотопливный котел":
						$PROP[288]=512;
						break;
					case "иное":
						$PROP[288]=513;
						break;
					case "нет":
						$PROP[288]=514;
						break;
					case "печь":
						$PROP[288]=515;
						break;
					case "центральное":
						$PROP[288]=516;
						break;
					case "электрокотел":
						$PROP[288]=517;
						break;
				}
				break;
			case "Phone":
				switch($nodename->nodeValue){
					case "Нет":
						$PROP[289]=518;
						break;
					case "Телефон":
						$PROP[289]=519;
						break;
					case "2 телефона":
						$PROP[289]=520;
						break;
				}
				break;
			case "NET":
				switch($nodename->nodeValue){
					case "отсутствует":
						$PROP[290]=521;
						break;
					case "радиоканал":
						$PROP[290]=522;
						break;
					case "кабельная линия":
						$PROP[290]=523;
						break;
					case "спутниковый":
						$PROP[290]=524;
						break;
				}
				break;
			case "TV":
				switch($nodename->nodeValue){
					case "отсутствует":
						$PROP[291]=525;
						break;
					case "спутниковое":
						$PROP[291]=526;
						break;
					case "эфирное":
						$PROP[291]=527;
						break;
					case "кабельное":
						$PROP[291]=528;
						break;
					case "IPTV":
						$PROP[291]=529;
						break;
				}
				break;
			case "KAD_NUMBER":
				$PROP[212] = $nodename->nodeValue;
				break;
			case "PLOT_AREA":
				$PROP[292] = $nodename->nodeValue;
				break;
			case "PLOT_CAT":
				switch($nodename->nodeValue){
					case "Поселений (ИЖС)":
						$PROP[295]=530;
						break;
					case "Сельхозназначения (СНТ, ДНП)":
						$PROP[295]=531;
						break;
					case "Промназначения":
						$PROP[295]=532;
						break;
				}
				break;
			case "REAL_NOTE":
				$PROP[296] = $nodename->nodeValue;
				break;
			case "MY_NOTE":
				$PROP[297] = $nodename->nodeValue;
				break;
			case "Agent":
				$user_name = explode(" ", $nodename->nodeValue);
				$users_res = CUser::GetList(($by="id"),($order="asc"),array("ACTIVE" => "Y", "LAST_NAME"=>$user_name[0],"NAME"=>$user_name[1],"SECOND_NAME"=>$user_name[2]));
				if ($uRes = $users_res->GetNext()){
          $arFields['CREATED_BY'] = $uRes['ID'];
        } else {
					$arFields['CREATED_BY'] = 24;
        }
				break;
			case "Description":
				$arFields['DETAIL_TEXT'] = $nodename->nodeValue;
				break;
			case "Latitude":
				$PROP[298] = $nodename->nodeValue;
				break;
			case "Longtitude":
				$PROP[299] = $nodename->nodeValue;
				break;
			case "Image":
				$PROP[237][] = array('VALUE' => CFile::MakeFileArray($nodename->nodeValue), 'DESCRIPTION' => '');
				break;
			case "OWNERDOC":
				$PROP[294][] = array('VALUE' => CFile::MakeFileArray($nodename->nodeValue), 'DESCRIPTION' => '');
				break;
			case "RODOC":
				$PROP[293][] = array('VALUE' => CFile::MakeFileArray($nodename->nodeValue), 'DESCRIPTION' => '');
				break;
    }
	}
	
	
	$arFields['NAME'] = $PROP[210];
  $arFields['PROPERTY_VALUES'] = $PROP;
  
	$ro =  new CIBlockElement;
	if ($ID) {
		echo "уже есть в базе<br>";
		//$ro->Update($ID, $arFields);
	}else {
		$arFields['IBLOCK_ID'] = 42;
		if($ID = $ro->Add($arFields)){
			echo "<br>Добавлен объект с ID: ".$ID;
			$added++;
		}else{
			echo "<br>Ошибка добавления объекта".$arFields['CODE'].": ".$ro->LAST_ERROR;
		}
	}
	echo "<hr>";
}

echo "Всего объектов ".$num.", из них ".$none." нет в bpm.ucre.ru, а ".$est." есть.";
?>

<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");
?>
