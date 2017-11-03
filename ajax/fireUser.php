<?
require ($_SERVER['DOCUMENT_ROOT'] . "/bitrix/modules/main/include/prolog_before.php");
if ($_POST['userId']>0 && stripos ($_SERVER['HTTP_REFERER'],"bpm.ucre.ru/company/personal/user/")){
  CModule::IncludeModule('intranet');
  CModule::IncludeModule('crm');
  //echo $_POST['userId'];
  $rsUser = CUser::GetByID($_POST['userId']);
  $arUser = $rsUser->Fetch();
  
  $boss = CIntranetUtils::GetDepartmentManagerID($arUser['UF_DEPARTMENT'][0]);
  //echo "<pre>";print_r($arUser);echo "</pre>";
  //echo "<pre>";print_r($_SERVER);echo "</pre>";
  $obRes = CCrmLead::GetList($arOrder = array('DATE_CREATE' => 'DESC'), $arFilter = array("ASSIGNED_BY_ID" => $_POST['userId'], "DATE_CLOSED" => ""), $arSelect = array(), $nPageTop = false); 
  echo "Незакрытых лидов: ".$obRes->SelectedRowsCount()."<br>";
  while ($arRes = $obRes->Fetch()) { 
    echo "ID: ".$arRes["ID"]." TITLE: ".$arRes['TITLE']." ".$arRes['DATE_CLOSED']."<br>";
    //CCrmDeal::Update($arRes["ID"],array("ASSIGNED_BY_ID" => $boss));
  } 
  $obRes = CCrmContact::GetList($arOrder = array('DATE_CREATE' => 'DESC'), $arFilter = array("ASSIGNED_BY_ID" => $_POST['userId'])); 
  echo "Контактов: ".$obRes->SelectedRowsCount()."<br>";
  while ($arRes = $obRes->Fetch()) { 
    echo "ID: ".$arRes["ID"]." FULL_NAME: ".$arRes['FULL_NAME']."<br>";
    //echo "<pre>";print_r($arRes);echo "</pre>";
    //CCrmContact::Update($arRes["ID"],array("ASSIGNED_BY_ID" => $boss));
  } 
  $obRes = CCrmCompany::GetListEx($arOrder = array('DATE_CREATE' => 'DESC'), $arFilter = array("ASSIGNED_BY_ID" => $_POST['userId'])); 
  echo "Компаний: ".$obRes->SelectedRowsCount()."<br>";
  while ($arRes = $obRes->Fetch()) { 
    echo "ID: ".$arRes["ID"]." TITLE: ".$arRes['TITLE']."<br>";
    //echo "<pre>";print_r($arRes);echo "</pre>";
    //CCrmCompany::Update($arRes["ID"],array("ASSIGNED_BY_ID" => $boss));
  } 
  $obRes = CCrmDeal::GetList($arOrder = array('DATE_CREATE' => 'DESC'), $arFilter = array("ASSIGNED_BY_ID" => $_POST['userId'], "CLOSED" => "N"), $arSelect = array(), $nPageTop = false); 
  echo "Незакрытых заявок: ".$obRes->SelectedRowsCount()."<br>";
  while ($arRes = $obRes->Fetch()) { 
    echo "ID: ".$arRes["ID"]." TITLE: ".$arRes['TITLE']." ".$arRes['CLOSED']."<br>";
    //echo "<pre>";print_r($arRes);echo "</pre>";
    //CCrmDeal::Update($arRes["ID"],array("ASSIGNED_BY_ID" => $boss));
  }
  $obRes = CCrmActivity::GetList($arOrder = array('DATE_CREATE' => 'DESC'), $arFilter = array("RESPONSIBLE_ID" => $_POST['userId'], 'COMPLETED' => 'N')); 
  echo "Незакрытых активностей: ".$obRes->SelectedRowsCount()."<br>";
  while ($arRes = $obRes->Fetch()) { 
    echo "ID: ".$arRes["ID"]." SUBJECT: ".$arRes['SUBJECT']." ".$arRes['PROVIDER_TYPE_ID']."<br>";
    //CCrmActivity::Complete($arRes["ID"]);
    //echo "<pre>";print_r($arRes);echo "</pre>";
  }
  //CUser::Update($_POST['userId'],array("ACTIVE" => "N"));
}else{
   echo "<center><img style='margin: 0 auto;' src='https://bpm.ucre.ru/upload/away.jpg'></center>";
}
?>