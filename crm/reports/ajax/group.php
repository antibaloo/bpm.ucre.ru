<?
$referer = explode("?",$_SERVER['HTTP_REFERER']);
if ($referer[0] == 'https://bpm.ucre.ru/crm/reports/group.php' && $_SERVER['REQUEST_METHOD'] == 'POST'){
  require ($_SERVER['DOCUMENT_ROOT'] . "/bitrix/modules/main/include/prolog_before.php");
  require($_SERVER["DOCUMENT_ROOT"]."/include/reports/functions.php");
  CModule::IncludeModule('intranet');
  echo "<pre>";
  print_r($_POST);
  echo "</pre>";
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
?>
<div class="report-table-wrap">
  <div class="reports-list-left-corner"></div>
  <div class="reports-list-right-corner"></div>
  <table cellspacing="0" class="reports-list-table" id="groupreport">
    <tbody>
      <tr>
        <th class="reports-first-column reports-selected-column" colid="2" defaultsort="ASC">
          <div class="reports-head-cell"><span class="reports-table-arrow"></span><span class="reports-head-cell-title">Ответственный</span></div>
        </th>
        <th class="reports-head-cell" colid="3" defaultsort="DESC">
          <div class="reports-head-cell"><span class="reports-table-arrow"></span><span class="reports-head-cell-title">Вх. звонков</span></div>
        </th>
        <th class="reports-head-cell" colid="4" defaultsort="DESC">
          <div class="reports-head-cell"><span class="reports-table-arrow"></span><span class="reports-head-cell-title">Исх. звонков</span></div>
        </th>
        <th class="reports-head-cell" colid="5" defaultsort="DESC">
          <div class="reports-head-cell"><span class="reports-table-arrow"></span><span class="reports-head-cell-title">Встреч</span></div>
        </th>
        <th class="reports-head-cell" colid="6" defaultsort="DESC">
          <div class="reports-head-cell"><span class="reports-table-arrow"></span><span class="reports-head-cell-title">Вх. писем</span></div>
        </th>
        <th class="reports-head-cell" colid="7" defaultsort="DESC">
          <div class="reports-head-cell"><span class="reports-table-arrow"></span><span class="reports-head-cell-title">Исх. писем</span></div>
        </th>
        <th class="reports-head-cell" colid="8" defaultsort="DESC">
          <div class="reports-head-cell"><span class="reports-table-arrow"></span><span class="reports-head-cell-title">Задач</span></div>
        </th>
        <th class="reports-last-column" colid="9" defaultsort="DESC">
          <div class="reports-head-cell"><span class="reports-table-arrow"></span><span class="reports-head-cell-title">Расклеек</span></div>
        </th>
      </tr>
      
    </tbody>
  </table>
</div>
<?  
  
  
  foreach ($emploees as $emploee){
    $arUser = $USER->GetById($emploee)->Fetch();
    $arFilter = array(
      "RESPONSIBLE_ID" => $emploee,
    );
    $rsActivity = CCrmActivity::GetList(array(),$arFilter);
    echo $arUser['NAME']." ".$arUser['SECOND_NAME']." ".$arUser['LAST_NAME'].", всего найденно ".$rsActivity->SelectedRowsCount()." дел.<br>";
  }

  echo "<pre>";
  print_r($rsActivity->Fetch());
  echo "</pre>";
}else {
  echo "<center><img style='margin: 0 auto;' src='../../../pub/images/away.jpg'></center>";
}
?>
