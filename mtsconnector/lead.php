<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Программное создание лида");
$phone="+7(987)654-32-10";
if (IsModuleInstalled("crm") && CModule::IncludeModule("crm"))
{
  $oLead = new CCrmLead;
  $arFields = Array(
            "TITLE" => "Лид по звонку на АС МТС",
            "COMMENTS" => "",
            "SOURCE_ID" => "CALL",
            "SOURCE_DESCRIPTION" =>"Создан молулем сопряжения АС МТС",
            "STATUS_ID" => "ASSIGNED",
            
            "ASSIGNED_BY_ID" => 24 //whose(substr($CallEvent['DN1'],1),"O",$DB)
          );
  
  if($LidID=$oLead->Add($arFields))
    echo "success: ".$LidID."<br>";
  else
    echo "error<br>"; 
  
  $phone = "+7(987)654-32-10";
  $oPhone = new CCrmFieldMulti;
  $arFields = Array(
    "ENTITY_ID"    => "LEAD",
    "ELEMENT_ID"=> $LidID,
    "TYPE_ID"    => "PHONE",
    "VALUE_TYPE"=> "OTHER",
    "COMPLEX_ID"=> "PHONE_OTHER",
    "VALUE"        => $phone,
  );
  if ($PhoneID = $oPhone->Add($arFields))
    echo "success: ".$PhoneID;
  else
    echo "error phone add";
}


require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");
?>;