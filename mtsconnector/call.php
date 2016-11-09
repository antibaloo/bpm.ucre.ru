<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
include('functions.php');
$APPLICATION->SetTitle("Добавление события звонка");
$AN = "9877955786";
$DN1 = "89877955693";
$UN = "9877959090";
$ASSIGNED_BY_ID = whose(substr($DN1,1),"O",$DB);//Определяем ID сотрудника, которому поступил звонок
$begin_time = "2016-05-13 07:51:38";
$end_time = "2016-05-13 07:52:14";
$prefix = "З";
//Задаем параметры события - телефонный звонок
$activityFields = array(
  'TYPE_ID' => CCrmActivityType::Call,
  'SUBJECT' => $prefix.'вонок c номера'."+7(".substr($AN,0,3).")".substr($AN,3,3)."-".substr($AN,6,2)."-".substr($AN,8),
  'COMPLETED' => 'Y',
  'PRIORITY' => CCrmActivityPriority::Medium,
  'DESCRIPTION' => 'Создан модулем сопряжения АС МТС, номер: '."+7(".substr($UN,0,3).")".substr($UN,3,3)."-".substr($UN,6,2)."-".substr($UN,8),
  'DESCRIPTION_TYPE' => CCrmContentType::PlainText,
  'LOCATION' => '',
  'DIRECTION' => CCrmActivityDirection::Incoming,
  'NOTIFY_TYPE' => CCrmActivityNotifyType::None,
  'SETTINGS' => array(),
  'STORAGE_TYPE_ID' => CCrmActivity::GetDefaultStorageTypeID(),
  'START_TIME' => $DB->FormatDate($begin_time, "YYYY-MM-DD HH:MI:SS", "DD.MM.YYYY HH:MI:SS"),
  'END_TIME' => $DB->FormatDate($end_time, "YYYY-MM-DD HH:MI:SS", "DD.MM.YYYY HH:MI:SS"),
  'OWNER_ID' => whose($AN, "C", $DB),
  'OWNER_TYPE_ID' => CCrmOwnerType::Contact,
  'RESPONSIBLE_ID' => $ASSIGNED_BY_ID, 
  'BINDINGS' => ''
);
if(!($callId = CCrmActivity::Add($activityFields, false, true, array('REGISTER_SONET_EVENT' => true)))) { 
  die('Ошибка при создании');
}
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");
?>;