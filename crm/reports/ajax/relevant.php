<?
$referer = explode("?",$_SERVER['HTTP_REFERER']);
require ($_SERVER['DOCUMENT_ROOT'] . "/bitrix/modules/main/include/prolog_before.php");
CModule::IncludeModule('intranet');
require($_SERVER["DOCUMENT_ROOT"]."/include/reports/functions.php");
if ($referer[0] == 'https://bpm.ucre.ru/crm/reports/relevant.php' && $_SERVER['REQUEST_METHOD'] == 'POST'){
  if ($_POST['report'] == 'emploee'){
    $departments = explode("|", GetSubStructure(55));
    $emploees = array();
    $structure = CIntranetUtils::GetStructure();
    if (is_array($departments)){
      foreach ($departments as $department){
        $emploees = array_merge($emploees, $structure['DATA'][$department]['EMPLOYEES']);
      }
    }else {
      $emploees = $structure['DATA'][$departments]['EMPLOYEES'];
    }
    $rows = array();
    foreach ($emploees as $emploee){
      $arUser = $USER->GetById($emploee)->Fetch();
      $rows[] = array('ID' => $arUser['ID'], 'FIO' => $arUser['LAST_NAME']." ".$arUser['NAME']." ".$arUser['SECOND_NAME'], 'COUNT' => $DB->Query("select * from b_crm_relevant_search where user_id=".$arUser['ID'])->SelectedRowsCount());
    }
    usort($rows, function($a,$b){
      return ($b['COUNT']-$a['COUNT']);
    });
?>
<div class="page active">
  <table>
    <tr>
      <th>id</th>
      <th>Фио</th>
      <th>Вызовы</th>
    </tr>
<?    
foreach ($rows as $row){
?>
    <tr class="row">
      <td><?=$row['ID']?></td>
      <td><?=$row['FIO']?></td>
      <td><?=$row['COUNT']?></td>
    </tr>  
<?}?>
  </table>
</div>    
<?} 
  if ($_POST['report'] == 'buy'){
    echo "Отчет по покупкам";
  }
  if ($_POST['report'] == 'sell'){
    echo "Отчет по продажам";
  }
}else {
  echo "<center><img style='margin: 0 auto;' src='../../../pub/images/away.jpg'></center>";
}
?>