<?
require ($_SERVER['DOCUMENT_ROOT'] . "/bitrix/modules/main/include/prolog_before.php");
include('functions.php');
$postdata = file_get_contents("php://input");

if ($postdata!=''){
  $bitrix = fopen('aa_mts.log', 'a');
  fwrite( $bitrix, $postdata ."\r\n");
  fclose( $bitrix );
  $CallEvent = json_decode($postdata,true);
  $type = phonetype($CallEvent['AN'], $DB);//Определение типа входящего номера ()
  
  if ($CallEvent['EventType']=="1" && $type == "O"){
    if (IsModuleInstalled("im") && CModule::IncludeModule("im")){
      $userid = whose($CallEvent['AN'],$type, $DB);
      $rsUser = CUser::GetByID($userid);
      $arUser = $rsUser->Fetch();
      //Сообщение сотруднику во избежании повторных звонков на номер автосекретаря
      $arMessageFields = array(
        "TO_USER_ID" => $userid, // получатель
        "FROM_USER_ID" => 0,// отправитель (может быть >0)
        "NOTIFY_TYPE" => IM_NOTIFY_SYSTEM, // тип уведомления
        "NOTIFY_MODULE" => "im", // модуль запросивший отправку уведомления
        "NOTIFY_TAG" => "IM_CONFIG_NOTICE", // символьный тэг для группировки (будет выведено только одно сообщение), если это не требуется - не задаем параметр
        "NOTIFY_MESSAGE" => '[b]Предупреждение: [/b]'.$arUser['LAST_NAME'].' '.$arUser['NAME'].', вы позвонили на номер автосекретаря '.$CallEvent['UN'].', что строжайше запрещено, для общения с сотрудниками, необходимо набирать прямой номер!'// текст уведомления на сайте (доступен html и бб-коды)
      );
      CIMNotify::Add($arMessageFields);
      
      //Сообщение руководителю
      $arMessageFields = array(
        "TO_USER_ID" => 1, // получатель
        "FROM_USER_ID" => 0,// отправитель (может быть >0)
        "NOTIFY_TYPE" => IM_NOTIFY_SYSTEM, // тип уведомления
        "NOTIFY_MODULE" => "im", // модуль запросивший отправку уведомления
        "NOTIFY_TAG" => "IM_CONFIG_NOTICE", // символьный тэг для группировки (будет выведено только одно сообщение), если это не требуется - не задаем параметр
        "NOTIFY_MESSAGE" => '[b]Сообщение:[/b] Сотрудник: '.$arUser['LAST_NAME'].' '.$arUser['NAME'].', позвонил на номер автосекретаря '.$CallEvent['UN'].', что строжайше запрещено, для общения с сотрудниками, необходимо набирать прямой номер!'// текст уведомления на сайте (доступен html и бб-коды)
      );
      CIMNotify::Add($arMessageFields);
    }

  }
  
  if ($CallEvent['EventType']=="15") {
    if (IsModuleInstalled("crm") && CModule::IncludeModule("crm")){
      if ($type=="C"){//Существующий клиент
        //Определяем было ли успешное соединение с сотрудником
        $success_talk = $DB->Query("SELECT DN1, EventTime from b_mts_events WHERE CallID = '".$CallEvent['CallID']."' AND EventType='9' AND Result='1280'");
        if ($success_talk->SelectedRowsCount()>0){//Есть событие успешного соединения
          $row_talk = $success_talk->Fetch();
          $ASSIGNED_BY_ID = whose(substr($row_talk['DN1'],1),"O",$DB);//Определяем ID сотрудника, которому поступил звонок
          $begin_time = $row_talk['EventTime'];//Задаем время начала соединения
          $end_time = $CallEvent['EventTime'];//Задаем время окончания разговора
          $prefix = "З";
        } else {//Если успешного соединения не было
          $ASSIGNED_BY_ID = 98; //ID Морозовой А.П. (44 - Прытковой Ю.Е.) 1 - Черкасов А.С. 98 - Салимова Л.М.
          $begin_time = $CallEvent['EventTime'];//Время начала и окончания разговора совпадают
          $end_time = $CallEvent['EventTime'];
          $prefix = "Неотвеченный з";
        }
        //Задаем параметры события - телефонный звонок
        $activityFields = array(
          'TYPE_ID' => CCrmActivityType::Call,
          'SUBJECT' => $prefix.'вонок c номера '."+7(".substr($CallEvent['AN'],0,3).")".substr($CallEvent['AN'],3,3)."-".substr($CallEvent['AN'],6,2)."-".substr($CallEvent['AN'],8),
          'COMPLETED' => 'Y',
          'PRIORITY' => CCrmActivityPriority::Medium,
          'DESCRIPTION' => 'Создан модулем сопряжения АС МТС, номер: '."+7(".substr($CallEvent['UN'],0,3).")".substr($CallEvent['UN'],3,3)."-".substr($CallEvent['UN'],6,2)."-".substr($CallEvent['UN'],8),
          'DESCRIPTION_TYPE' => CCrmContentType::PlainText,
          'LOCATION' => '',
          'DIRECTION' => CCrmActivityDirection::Incoming,
          'NOTIFY_TYPE' => CCrmActivityNotifyType::None,
          'SETTINGS' => array(),
          'STORAGE_TYPE_ID' => CCrmActivity::GetDefaultStorageTypeID(),
          'START_TIME' => $DB->FormatDate($begin_time, "YYYY-MM-DD HH:MI:SS", "DD.MM.YYYY HH:MI:SS"),
          'END_TIME' => $DB->FormatDate($end_time, "YYYY-MM-DD HH:MI:SS", "DD.MM.YYYY HH:MI:SS"),
          'OWNER_ID' => whose($CallEvent['AN'], "C", $DB),
          'OWNER_TYPE_ID' => CCrmOwnerType::Contact,
          'RESPONSIBLE_ID' => $ASSIGNED_BY_ID, 
          'BINDINGS' => ''
        );
        //Привязываем событие - телефонный звонок к контакту
        $callId = CCrmActivity::Add($activityFields, false, true, array('REGISTER_SONET_EVENT' => true));
        
      }
      if ($type=="L"){//Существующий лид
        //Определяем было ли успешное соединение с сотрудником
        $success_talk = $DB->Query("SELECT DN1, EventTime from b_mts_events WHERE CallID = '".$CallEvent['CallID']."' AND EventType='9' AND Result='1280'");
        if ($success_talk->SelectedRowsCount()>0){//Есть событие успешного соединения
          $row_talk = $success_talk->Fetch();
          $ASSIGNED_BY_ID = whose(substr($row_talk['DN1'],1),"O",$DB);//Определяем ID сотрудника, которому поступил звонок
          $begin_time = $row_talk['EventTime'];//Задаем время начала соединения
          $end_time = $CallEvent['EventTime'];//Задаем время окончания разговора
          $prefix = "З";
        } else {//Если успешного соединения не было
          $ASSIGNED_BY_ID = 98; //ID Морозовой А.П. (44 - Прытковой Ю.Е.) 1 - Черкасов А.С. 98 - Салимова Л.М.
          $begin_time = $CallEvent['EventTime'];//Время начала и окончания разговора совпадают
          $end_time = $CallEvent['EventTime'];
          $prefix = "Неотвеченный з";
        }
        //Задаем параметры события - телефонный звонок
        $activityFields = array(
          'TYPE_ID' => CCrmActivityType::Call,
          'SUBJECT' => $prefix.'вонок c номера '."+7(".substr($CallEvent['AN'],0,3).")".substr($CallEvent['AN'],3,3)."-".substr($CallEvent['AN'],6,2)."-".substr($CallEvent['AN'],8),
          'COMPLETED' => 'Y',
          'PRIORITY' => CCrmActivityPriority::Medium,
          'DESCRIPTION' => 'Создан модулем сопряжения АС МТС, номер: '."+7(".substr($CallEvent['UN'],0,3).")".substr($CallEvent['UN'],3,3)."-".substr($CallEvent['UN'],6,2)."-".substr($CallEvent['UN'],8),
          'DESCRIPTION_TYPE' => CCrmContentType::PlainText,
          'LOCATION' => '',
          'DIRECTION' => CCrmActivityDirection::Incoming,
          'NOTIFY_TYPE' => CCrmActivityNotifyType::None,
          'SETTINGS' => array(),
          'STORAGE_TYPE_ID' => CCrmActivity::GetDefaultStorageTypeID(),
          'START_TIME' => $DB->FormatDate($begin_time, "YYYY-MM-DD HH:MI:SS", "DD.MM.YYYY HH:MI:SS"),
          'END_TIME' => $DB->FormatDate($end_time, "YYYY-MM-DD HH:MI:SS", "DD.MM.YYYY HH:MI:SS"),
          'OWNER_ID' => whose($CallEvent['AN'], "L", $DB),
          'OWNER_TYPE_ID' => CCrmOwnerType::Lead,
          'RESPONSIBLE_ID' => $ASSIGNED_BY_ID, 
          'BINDINGS' => ''
        );
        //Привязываем событие - телефонный звонок к лиду
        $callId = CCrmActivity::Add($activityFields, false, true, array('REGISTER_SONET_EVENT' => true));
      }
      if ($type=="N"){//Новый клиент
        //Определяем было ли успешное соединение с сотрудником
        $success_talk = $DB->Query("SELECT DN1, EventTime from b_mts_events WHERE CallID = '".$CallEvent['CallID']."' AND EventType='9' AND Result='1280'");
        if ($success_talk->SelectedRowsCount()>0){//Есть событие успешного соединения
          $row_talk = $success_talk->Fetch();
          $ASSIGNED_BY_ID = whose(substr($row_talk['DN1'],1),"O",$DB);//Определяем ID сотрудника, которому поступил звонок
          $begin_time = $row_talk['EventTime'];//Задаем время начала соединения
          $end_time = $CallEvent['EventTime'];//Задаем время окончания разговора
          $prefix = "З";
        } else {//Если успешного соединения не было
          $ASSIGNED_BY_ID = 98; //ID Морозовой А.П. (44 - Прытковой Ю.Е.) 1 - Черкасов А.С. 98 - Салимова Л.М.
          $begin_time = $CallEvent['EventTime'];//Время начала и окончания разговора совпадают
          $end_time = $CallEvent['EventTime'];
          $prefix = "Неотвеченный з";
        }
        //Задаем параметры лида
        $oLead = new CCrmLead;
        $arFields = Array(
          "TITLE" => "Лид по звонку на АС МТС",
          "COMMENTS" => "",
          "SOURCE_ID" => "CALL",
          "SOURCE_DESCRIPTION" =>"Создан модулем сопряжения АС МТС, номер: "."+7(".substr($CallEvent['UN'],0,3).")".substr($CallEvent['UN'],3,3)."-".substr($CallEvent['UN'],6,2)."-".substr($CallEvent['UN'],8),
          "STATUS_ID" => "ASSIGNED",
          "ASSIGNED_BY_ID" => $ASSIGNED_BY_ID
        );
        //Создаем лид
        $LeadId = $oLead->Add($arFields, true, array('CURRENT_USER' => $ASSIGNED_BY_ID));
        //Задаем параметры номера телефона
        $oPhone = new CCrmFieldMulti;
        $arFields = Array(
          "ENTITY_ID"    => "LEAD",
          "ELEMENT_ID"=> $LeadId,
          "TYPE_ID"    => "PHONE",
          "VALUE_TYPE"=> "OTHER",
          "COMPLEX_ID"=> "PHONE_OTHER",
          "VALUE"        => "+7(".substr($CallEvent['AN'],0,3).")".substr($CallEvent['AN'],3,3)."-".substr($CallEvent['AN'],6,2)."-".substr($CallEvent['AN'],8)
        );
        //Привязываем номер телефона к лиду
        $PhoneID = $oPhone->Add($arFields);
        //Задаем параметры события - телефонный звонок
        $activityFields = array(
          'TYPE_ID' => CCrmActivityType::Call,
          'SUBJECT' => $prefix.'вонок c номера '."+7(".substr($CallEvent['AN'],0,3).")".substr($CallEvent['AN'],3,3)."-".substr($CallEvent['AN'],6,2)."-".substr($CallEvent['AN'],8),
          'COMPLETED' => 'Y',
          'PRIORITY' => CCrmActivityPriority::Medium,
          'DESCRIPTION' => 'Создан модулем сопряжения АС МТС, номер: '."+7(".substr($CallEvent['UN'],0,3).")".substr($CallEvent['UN'],3,3)."-".substr($CallEvent['UN'],6,2)."-".substr($CallEvent['UN'],8),
          'DESCRIPTION_TYPE' => CCrmContentType::PlainText,
          'LOCATION' => '',
          'DIRECTION' => CCrmActivityDirection::Incoming,
          'NOTIFY_TYPE' => CCrmActivityNotifyType::None,
          'SETTINGS' => array(),
          'STORAGE_TYPE_ID' => CCrmActivity::GetDefaultStorageTypeID(),
          'START_TIME' => $DB->FormatDate($begin_time, "YYYY-MM-DD HH:MI:SS", "DD.MM.YYYY HH:MI:SS"),
          'END_TIME' => $DB->FormatDate($end_time, "YYYY-MM-DD HH:MI:SS", "DD.MM.YYYY HH:MI:SS"),
          'OWNER_ID' => $LeadId,
          'OWNER_TYPE_ID' => CCrmOwnerType::Lead,
          'RESPONSIBLE_ID' => $ASSIGNED_BY_ID,
          'BINDINGS' => ''
        );
        //Привязываем событие - телефонный звонок к лиду
        $callId = CCrmActivity::Add($activityFields, false, true, array('REGISTER_SONET_EVENT' => true));
        $type = "A";
      }
    }
  }
  $results = $DB->Query("INSERT INTO b_mts_events VALUES ('','".$CallEvent['CallID']."','".$CallEvent['EventTime']."','".$CallEvent['AN']."','".$CallEvent['UN']."','".$CallEvent['DN1']."','".$CallEvent['DN2']."','".$CallEvent['EXT1']."','".$CallEvent['EXT2']."','".$CallEvent['DTMF']."','".$CallEvent['Result']."','".$CallEvent['EventType']."','".$type."')");
}
//Формируем положительный ответ при приеме HTTP запроса
$sock = fsockopen($_SERVER['GEOIP_ADDR'], 80);
fputs($sock, "POST HTTP/1.1 200 OK\r\n");
fputs($sock, "Cache-Control: no-cache\r\n");
fputs($sock, "Pragma: no-cache\r\n");
fputs($sock, "Content-Type: application/json; charset=utf-8\r\n");
fputs($sock, "Expires: -1\r\n");
fputs($sock, "Server: Microsoft-IIS/8.5\r\n");
fputs($sock, "X-AspNet-Version: 4.0.30319\r\n");
fputs($sock, "X-Powered-By: ASP.NET\r\n");
fputs($sock, "Date: ".date('r')."\r\n");
fputs($sock, "Content-Length: 7\r\n");
fputs($sock, "hello\r\n");
fclose($sock);
require ($_SERVER['DOCUMENT_ROOT'] . "/bitrix/modules/main/include/epilog_after.php");
?>