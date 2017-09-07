<?
$referer = explode("?",$_SERVER['HTTP_REFERER']);
if ($referer[0] == 'https://bpm.ucre.ru/crm/reports/potentials.php' && $_SERVER['REQUEST_METHOD'] == 'POST'){
  require ($_SERVER['DOCUMENT_ROOT'] . "/bitrix/modules/main/include/prolog_before.php");
  require($_SERVER["DOCUMENT_ROOT"]."/include/reports/functions.php");
  CModule::IncludeModule('intranet');
  $departments = explode("|", GetSubStructure($_POST['department']));
  $structure = CIntranetUtils::GetStructure();
  $emploees = array();
  if (is_array($departments)){
    foreach ($departments as $department){
      $emploees = array_merge($emploees, $structure['DATA'][$department]['EMPLOYEES']);
    }
  }else {
    $emploees = $structure['DATA'][$departments]['EMPLOYEES'];
  }
  echo "<option value='".implode(",",$emploees)."'>Все</option>";
  foreach ($emploees as $emploee){
    $arUser = $USER->GetById($emploee)->Fetch();
    echo "<option value='".$emploee."'>".$arUser['LAST_NAME']." ".$arUser['NAME']." ".$arUser['SECOND_NAME']."</option>";
  }
}else {
  echo "<option value='error'>Ошибка!!!</option>";
}
?>