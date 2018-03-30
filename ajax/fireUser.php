<?
require ($_SERVER['DOCUMENT_ROOT'] . "/bitrix/modules/main/include/prolog_before.php");
global $USER;
if ($_POST['userId']>0 && stripos ($_SERVER['HTTP_REFERER'],"bpm.ucre.ru/company/personal/user/")){
  CModule::IncludeModule('intranet');
  CModule::IncludeModule('crm');
  CModule::IncludeModule("tasks");
  $rsUser = CUser::GetByID($_POST['userId']);
  $arUser = $rsUser->Fetch();
  
  $boss = CIntranetUtils::GetDepartmentManagerID($arUser['UF_DEPARTMENT'][0]);
  echo "Руководитель: ".$boss."<br>";
  $obRes = CCrmLead::GetList($arOrder = array('DATE_CREATE' => 'DESC'), $arFilter = array("ASSIGNED_BY_ID" => $_POST['userId'], "DATE_CLOSED" => ""), $arSelect = array(), $nPageTop = false); 
  echo "Незакрытых лидов: ".$obRes->SelectedRowsCount()."<br>";
  while ($arRes = $obRes->Fetch()) { 
    $obLead = new CCrmLead;
    $arFields = array("TITLE" => "Переведена: ".$arRes['TITLE'] ,"ASSIGNED_BY_ID" => 206);
    $obLead->Update($arRes["ID"],$arFields, array('USER_ID' => 24));
  } 
  $obRes = CCrmContact::GetList($arOrder = array('DATE_CREATE' => 'DESC'), $arFilter = array("ASSIGNED_BY_ID" => $_POST['userId'])); 
  echo "Контактов: ".$obRes->SelectedRowsCount()."<br>";
  while ($arRes = $obRes->Fetch()) { 
    $obContact = new CCrmContact;
    $arFields = array("ASSIGNED_BY_ID" => $boss);
    $obContact->Update($arRes["ID"], $arFields, array('USER_ID' => 24));
  } 
  $obRes = CCrmCompany::GetListEx($arOrder = array('DATE_CREATE' => 'DESC'), $arFilter = array("ASSIGNED_BY_ID" => $_POST['userId'])); 
  echo "Компаний: ".$obRes->SelectedRowsCount()."<br>";
  while ($arRes = $obRes->Fetch()) { 
    $obCompany = new CCrmCompany;
    $arFields = array("ASSIGNED_BY_ID" => $boss);
    $obCompany->Update($arRes["ID"],$arFields, array('USER_ID' => 24));
  } 
  $obRes = CCrmDeal::GetList($arOrder = array('DATE_CREATE' => 'DESC'), $arFilter = array("ASSIGNED_BY_ID" => $_POST['userId'], "CLOSED" => "N"), $arSelect = array(), $nPageTop = false); 
  echo "Незакрытых заявок: ".$obRes->SelectedRowsCount()."<br>";
  while ($arRes = $obRes->Fetch()) { 
    $obDeal = new CCrmDeal;
    $arFields = array("TITLE" => "Переведена: ".$arRes['TITLE'],"ASSIGNED_BY_ID" => $boss);
    $obDeal->Update($arRes["ID"],$arFields, array('USER_ID' => 24));
  }
  $obRes = CCrmActivity::GetList($arOrder = array('DATE_CREATE' => 'DESC'), $arFilter = array("RESPONSIBLE_ID" => $_POST['userId'], 'COMPLETED' => 'N')); 
  echo "Незакрытых активностей: ".$obRes->SelectedRowsCount()."<br>";
  while ($arRes = $obRes->Fetch()) { 
    CCrmActivity::Complete($arRes["ID"]);
  }
  $obRes = CTasks::GetList($arOrder = array('DATE_CREATE' => 'DESC'), $arFilter = array("RESPONSIBLE_ID" => $_POST['userId'], 'CLOSED_DATE' => false)); 
  echo "Незакрытых задач: ".$obRes->SelectedRowsCount()."<br>";
  while ($arRes = $obRes->Fetch()) { 
    $obTask = new CTasks;
    $success = $obTask->Update($arRes["ID"], array("REAL_STATUS" => 5), array('USER_ID' => 24));
  }
  $USER->Update($_POST['userId'],array("WORK_PHONE" => "", "WORK_FAX" => $arUser['WORK_PHONE'],"UF_DATE_CLOS" => date("d.m.Y"), "ACTIVE" => "N"));
  echo "Процедура завершена";
}else{
   echo "<center><img style='margin: 0 auto;' src='https://bpm.ucre.ru/upload/away.jpg'></center>";
}
?>