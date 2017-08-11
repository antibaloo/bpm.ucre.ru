<link href="/include/custom_css/custom_paging.css?<?=time();?>" rel="stylesheet">
<?
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
?>
<div class="page active">
  <table>
    <tr>
      <th>id</th>
      <th>Фио</th>
      <th>Вызовы</th>
    </tr>
<?    
foreach ($emploees as $emploee){
  $arUser = $USER->GetById($emploee)->Fetch();
?>
    <tr class="row">
      <td><?=$arUser['ID']?></td>
      <td><?=$arUser['LAST_NAME']." ".$arUser['NAME']." ".$arUser['SECOND_NAME']?></td>
      <td><?=$DB->Query("select * from b_crm_relevant_search where user_id=".$arUser['ID'])->SelectedRowsCount();?></td>
    </tr>  
<?}?>
  </table>
</div>
<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>