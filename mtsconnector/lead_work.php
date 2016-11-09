<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Программное создание лида");
if (IsModuleInstalled("crm") && CModule::IncludeModule("crm"))
{
  $oLead = new CCrmLead;
  $arFields = Array(
    "TITLE" => "Программный лид",
    //"COMPANY_TITLE" => "Программная фигня",
    "NAME" => "Некто",
    "LAST_NAME" => "Некая",
    "SECOND_NAME" => "Нектович",
    //"POST" => "Большой начальник",
    //"ADDRESS" => $_POST['address'],
    "COMMENTS" => "Создан молулем сопрояжения АС МТС",
    //"SOURCE_DESCRIPTION" => $_POST['description'],
    // "STATUS_DESCRIPTION" => "",
    //"OPPORTUNITY" => 123456,
    //"CURRENCY_ID" => "USD",
    // "PRODUCT_ID" => "PRODUCT_1",
    
    "SOURCE_ID" => "SELF",
    "STATUS_ID" => "NEW",
    "ASSIGNED_BY_ID" => 24
  );
  
  if($LidID=$oLead->Add($arFields))
    echo "success: ".$LidID."<br>";
  else
    echo "error<br>""; 
  
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