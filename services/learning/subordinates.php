<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("");
$userID = 1;//$USER->GetID()

print_r(CIntranetUtils::GetSubordinateDepartments($userID));
echo "<br>".count(CIntranetUtils::GetSubordinateDepartments($userID))."<br>";

$SubordinateEmployees = CIntranetUtils::getSubordinateEmployees($userID, true);
while ($employee = $SubordinateEmployees->GetNext()){
  echo $employee['ID']." - ".$employee['LAST_NAME']."<br>";
}

require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>