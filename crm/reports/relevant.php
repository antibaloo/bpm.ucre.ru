<link href="/include/custom_css/custom_paging.css?<?=time();?>" rel="stylesheet">
<?
/*function compare ($v1, $v2) {   
  if ($v1["COUNT"] == $v2["COUNT"]) return 0;
  return ($v1["COUNT"] < $v2["COUNT"])? -1: 1;
}*/

function customMultiSort($array,$field) {
    $sortArr = array();
    foreach($array as $key=>$val){
        $sortArr[$key] = $val[$field];
    }

    array_multisort($sortArr,$array);

    return $array;
}

require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
CModule::IncludeModule('intranet');
require($_SERVER["DOCUMENT_ROOT"]."/include/reports/functions.php");
$APPLICATION->SetTitle("Отчет по встречным заявкам");
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
<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>