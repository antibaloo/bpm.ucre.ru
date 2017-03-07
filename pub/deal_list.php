<?php if ($_SERVER['HTTP_ORIGIN'] = "http://r.ucre.ru")  header('Access-Control-Allow-Origin: *'); ?>
<?
require ($_SERVER['DOCUMENT_ROOT'] . "/bitrix/modules/main/include/prolog_before.php");
CModule::IncludeModule('crm');
$rsQuery = "SELECT b_crm_deal.ID, b_crm_deal.TITLE, b_crm_deal.ASSIGNED_BY_ID, b_uts_crm_deal.UF_CRM_5895BC940ED3F,b_uts_crm_deal.UF_CRM_58958B5724514,b_uts_crm_deal.UF_CRM_58958B529E628,b_uts_crm_deal.UF_CRM_58958B52BA439,b_uts_crm_deal.UF_CRM_58958B52F2BAC,b_uts_crm_deal.UF_CRM_58958B51B667E, b_uts_crm_deal.UF_CRM_58958B576448C, b_uts_crm_deal.UF_CRM_58958B5751841 FROM b_crm_deal INNER JOIN b_uts_crm_deal ON b_crm_deal.ID=b_uts_crm_deal.VALUE_ID WHERE b_crm_deal.CATEGORY_ID = 2 AND b_crm_deal.STAGE_ID = 'C2:PROPOSAL'";
$rsQuery .= " ORDER BY RAND() LIMIT 10";
?>
<div class="row">
  <div class="col-sm-1">№ заявки</div>
  <div class="col-sm-3">Название</div>
  <div class="col-sm-1">N<sub>к</sub> от</div>
  <div class="col-sm-1">S<sub>общ.</sub> от</div>
  <div class="col-sm-1">S<sub>кухни</sub> от</div>
  <div class="col-sm-1">Этажи</div>
  <div class="col-sm-1">Цена от</div>
  <div class="col-sm-1">Цена до</div>
  <div class="col-sm-2">Ответственный</div>
</div>
<?
$rsData = $DB->Query($rsQuery);
while ($aRes = $rsData->Fetch()){
  $assigned_user = CUser::GetByID($aRes['ASSIGNED_BY_ID'])->Fetch();
  $floors = unserialize($aRes['UF_CRM_58958B51B667E']);
?>
<div class="row">
  <div class="col-sm-1"><?=$aRes['ID']?></div>
  <div class="col-sm-3" style="white-space: nowrap;text-overflow: ellipsis;overflow: hidden;"><?=$aRes['TITLE']?></div>
  <div class="col-sm-1"><?=($aRes['UF_CRM_58958B529E628'])?$aRes['UF_CRM_58958B529E628']:"-"?></div>
  <div class="col-sm-1"><?=($aRes['UF_CRM_58958B52BA439'])?$aRes['UF_CRM_58958B52BA439']:"-"?></div>
  <div class="col-sm-1"><?=($aRes['UF_CRM_58958B52F2BAC'])?$aRes['UF_CRM_58958B52F2BAC']:"-"?></div>
  <div class="col-sm-1"><?=(in_array(754, $floors))?"<span title='не первый'><s>&#8595;</s></span>":""?><?=(count($floors) == 2)?"/":""?><?=(in_array(755, $floors))?"<span title='не последний'><s>&#8593;</s></span>":""?></div>
  <div class="col-sm-1"><?=($aRes['UF_CRM_58958B576448C'])?$aRes['UF_CRM_58958B576448C']:"-"?></div>
  <div class="col-sm-1"><?=($aRes['UF_CRM_58958B5751841'])?$aRes['UF_CRM_58958B5751841']:"-"?></div>
  <div class="col-sm-2"><span class="label label-info" title="<?=$assigned_user['PERSONAL_PHONE']?>"><?=$assigned_user['LAST_NAME']." ".$assigned_user['NAME']?></span></div>
</div>
<?}?>
<div class="row">
  <div class="col-sm-12"><span class="label label-success">И это только начало ...</span></div>
</div>