<?
$referer = explode("?",$_SERVER['HTTP_REFERER']);
if ($referer[0] == 'https://bpm.ucre.ru/crm/reports/group.php' && $_SERVER['REQUEST_METHOD'] == 'POST'){
  require ($_SERVER['DOCUMENT_ROOT'] . "/bitrix/modules/main/include/prolog_before.php");
  require($_SERVER["DOCUMENT_ROOT"]."/include/reports/functions.php");
  CModule::IncludeModule('intranet');

  $departments = ($_POST['subdepartments'] == 'on')? explode("|", GetSubStructure($_POST['department'])):$_POST['department'];
  $structure = CIntranetUtils::GetStructure();
  $emploees = array();
  if (is_array($departments)){
    foreach ($departments as $department){
      $emploees = array_merge($emploees, $structure['DATA'][$department]['EMPLOYEES']);
    }
  }else {
    $emploees = $structure['DATA'][$departments]['EMPLOYEES'];
  }
  foreach ($emploees as $emploee){
    $arUser = $USER->GetById($emploee)->Fetch();
    echo $arUser['NAME']." ".$arUser['SECOND_NAME']." ".$arUser['LAST_NAME']."<br>";
  }
}else {
  echo "<center><img style='margin: 0 auto;' src='../../../pub/images/away.jpg'></center>";
}
?>
