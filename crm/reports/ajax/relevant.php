<?
$referer = explode("?",$_SERVER['HTTP_REFERER']);
require ($_SERVER['DOCUMENT_ROOT'] . "/bitrix/modules/main/include/prolog_before.php");
CModule::IncludeModule('intranet');
require($_SERVER["DOCUMENT_ROOT"]."/include/reports/functions.php");
if ($referer[0] == 'https://bpm.ucre.ru/crm/reports/relevant.php' && $_SERVER['REQUEST_METHOD'] == 'POST'){
  if ($_POST['report'] == 'emploee'){
    
    $departments = explode("|", GetSubStructure(94));
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
?>
<center>Все<input name="status" type="radio" value="all" >&nbsp;Активные<input name="status" type="radio" value="active" checked>&nbsp;Успешные<input name="status" type="radio" value="success">&nbsp;Проваленные<input name="status" type="radio" value="fail">&nbsp;
  <select>
    <option value="all">Все</option>
<?
    $departments = explode("|", GetSubStructure(94));
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
?>
  <option value="<?=$arUser['ID']?>"><?=$arUser['LAST_NAME']." ".$arUser['NAME']." ".$arUser['SECOND_NAME']?></option>
<?}?>
  </select>
  <button>
    Применить
  </button>
</center>
<?
  }
  if ($_POST['report'] == 'sell'){
?>
<center>Все<input name="status" type="radio" value="all" >&nbsp;Активные<input name="status" type="radio" value="active" checked>&nbsp;Успешные<input name="status" type="radio" value="success">&nbsp;Проваленные<input name="status" type="radio" value="fail">&nbsp;
  <select>
    <option value="all">Все</option>
<?
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
?>
  <option value="<?=$arUser['ID']?>"><?=$arUser['LAST_NAME']." ".$arUser['NAME']." ".$arUser['SECOND_NAME']?></option>
<?}?>
  </select>
  <button>
    Применить
  </button>
</center>
<?
  }
}else {
  echo "<center><img style='margin: 0 auto;' src='../../../pub/images/away.jpg'></center>";
}
?>