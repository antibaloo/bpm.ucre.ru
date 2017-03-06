<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Отчет по действующим договорам с застройщиками");
?>
<a href="/crm/reports/report">Назад</a>
<?
$arUser = $USER->GetById($USER->GetID())->Fetch();
$dateFrom = ($_POST['report_date'])?$_POST['report_date']:date("Y-m-d");
if (1/*$arUser['WORK_DEPARTMENT']=='АУП'*/){?>
<form action="" method="POST">
  Дата отчета <input name="report_date" type="date" value="<?=$dateFrom?>">
  <input type="submit">
</form>
<hr>
<?
  /*">=UF_CRM_1484894007" => $dateFrom." 00:00:00", "<=UF_CRM_1484894007" => $dateTo." 23:59:59"*/
  $arFilter = array("COMPANY_TYPE" => 3, ">=UF_CRM_1484894007" => ConvertTimeStamp(strtotime($dateFrom),"FULL"));
  $arSelect = array("ID","TITLE", "COMPANY_TYPE","UF_CRM_1484893928","UF_CRM_1484893952","UF_CRM_1484893989","UF_CRM_1484894007");
  $arOrder = array('DATE_CREATE' => 'DESC');
  $rsData = CCRmCompany::GetList($arOrder, $arFilter, $arSelect);
  $grid_options = new CGridOptions($arUser['ID']."_".time());
  $aNav = $grid_options->GetNavParams(array("nPageSize"=>15));
  $rsData->NavStart($aNav["nPageSize"]);
  while($aRes = $rsData->Fetch()){
    $rsContractType = CUserFieldEnum::GetList(array(), array("ID" => $aRes["UF_CRM_1484893928"]));
    $contractType = $rsContractType->Fetch();
    $aCols = array(
      "TITLE" => "<a href='/crm/company/show/".$aRes['ID']."/' target='_blank'>".$aRes['TITLE']."</a>",
      "UF_CRM_1484893928" => $contractType['VALUE'],
    );
    $aActions = array();
    $aRows[] = array("data"=>$aRes, "actions"=>$aActions, "columns"=>$aCols, "editable"=>false);
  }                                     
 
  $APPLICATION->IncludeComponent(
    'bitrix:main.interface.grid',
    '',
    array('GRID_ID'=>$arUser['ID']."_".time(),
          'HEADERS'=>array(array("id"=>"ID", "name"=>"id", "default"=>true, "editable"=>false),
                           array("id"=>"TITLE", "name"=>"Название", "default"=>true, "editable"=>false),
                           array("id"=>"UF_CRM_1484893928", "name"=>"Тип договора", "default"=>true, "editable"=>false),
                           array("id"=>"UF_CRM_1484893952", "name"=>"Номер договора", "default"=>true, "editable"=>false),
                           array("id"=>"UF_CRM_1484893989", "name"=>"Дата подписания", "default"=>true, "editable"=>false),
                           array("id"=>"UF_CRM_1484894007", "name"=>"Дата окончания", "default"=>true, "editable"=>false),),
          'ROWS'=>$aRows,
          'FOOTER'=>array(array('title'=>"Всего", 'value'=>$rsData->SelectedRowsCount())),
          'ACTION_ALL_ROWS'=>false,
          'EDITABLE'=>false,
          'NAV_OBJECT'=>$rsData,
          'AJAX_MODE'=>"Y",
          'AJAX_OPTION_JUMP'=>"N",
          'AJAX_OPTION_STYLE'=>"Y",
         ),
    false
  );
}else{?>
<h1>
  Отчет доступен только для сотрудников АУП
</h1>
<?}?>
<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>