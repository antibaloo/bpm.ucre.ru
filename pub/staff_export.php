<?php
require ($_SERVER['DOCUMENT_ROOT'] . "/bitrix/modules/main/include/prolog_before.php");
$start = microtime(true);//Засекаем время выполнения скрипта
$num = 0;
$filter = array(
  "ACTIVE"    => "Y",
  "GROUPS_ID" => array(12),
  "WORK_DEPARTMENT" => "Руководство | Отдел продаж | АУП"
);
$arParams["SELECT"] = array("UF_*");
$rsUsers = CUser::GetList(($by="id"), ($order="asc"), $filter,$arParams); // выбираем пользователей
$dom = new domDocument("1.0", "utf-8"); // Создаём XML-документ версии 1.0 с кодировкой utf-8
$Staff = $dom->createElement("Staff"); // Создаём корневой элемент
$Staff->setAttribute("ExportDate", date(DATE_RFC822));//Добавляем элементу свойство
$dom->appendChild($Staff);//Присоединяем его к документу
while($aUser = $rsUsers->GetNext()) {
  $photo = ($aUser['PERSONAL_PHOTO'])?"http://bpm.ucre.ru".CFile::GetPath($aUser['PERSONAL_PHOTO']):"";
  $Agent = $dom->createElement("Agent");
  $Staff->appendChild($Agent);
  $Id = $dom->createElement("Id",$aUser['ID']);
  $Agent->appendChild($Id);
  $Fio = $dom->createElement("FIO",$aUser['LAST_NAME']." ".$aUser['NAME']." ".$aUser['SECOND_NAME']);
  $Agent->appendChild($Fio);
  $Department = $dom->createElement("Department", $aUser['WORK_DEPARTMENT']);
  $Agent->appendChild($Department);
  $Position = $dom->createElement("Position",$aUser['WORK_POSITION']);
  $Agent->appendChild($Position);
  $Phone = $dom->createElement("Phone", $aUser['PERSONAL_PHONE']);
  $Agent->appendChild($Phone);
  $Email = $dom->createElement("Email", $aUser['EMAIL']);
  $Agent->appendChild($Email);
  $Photo = $dom->createElement("Photo", $photo);
  $Agent->appendChild($Photo);
  $Rating = $dom->createElement("Rating", $aUser['UF_RATING']);
  $Agent->appendChild($Rating);
  $Best = $dom->createElement("Best",$aUser['UF_BEST']);
  $Agent->appendChild($Best);
  $n++;
}
$dom->save("/home/bitrix/www_bpm/staff.xml"); // Сохраняем полученный XML-документ в файл
$time = microtime(true) - $start;
CEventLog::Add(array(
    "SEVERITY" => "SECURITY",
    "AUDIT_TYPE_ID" => "STAFF_EXPORT",
    "MODULE_ID" => "main",
    "ITEM_ID" => 'Список сотрудников',
    "DESCRIPTION" => "Выгрузка актуального списка сотрудников, выгружено ".$n." сотрудников за ".$time." секунд.",
  ));
echo "Выгружено ".$n." сотрудников за ".$time." секунд.";
require ($_SERVER['DOCUMENT_ROOT'] . "/bitrix/modules/main/include/epilog_after.php");
?>