<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Курсы подчиненных");
$userID = $USER->GetID();
if (count(CIntranetUtils::GetSubordinateDepartments($userID))){
  $SubordinateEmployees = CIntranetUtils::getSubordinateEmployees($userID, true);
  while ($employee = $SubordinateEmployees->GetNext()){
    echo $employee['ID']." - ".$employee['LAST_NAME']." - ".$employee['LOGIN']."<hr>";
    $APPLICATION->IncludeComponent(
      "ucre:learning.student.certificates",
      "",
      array(
        "STUDENT_ID" => $employee['ID'],
        "COURSE_DETAIL_TEMPLATE" => "course.php?COURSE_ID=#COURSE_ID#&INDEX=Y",
        "TESTS_LIST_TEMPLATE" => "course.php?COURSE_ID=#COURSE_ID#&TEST_LIST=Y",
        "SET_TITLE" => "N"
      )
    );
    echo "<hr>";
  }
}else echo "У вас нет сотрудников в подчинении.";
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>