<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
include('functions.php');
$APPLICATION->SetTitle("Поиск сотрудника по номеру телефона");
$phone = "9877955780";
$mask_phone = "+7 (".substr($phone,0,3).") ".substr($phone,3,2)."-".substr($phone,5,2)."-".substr($phone,7); //+7 (999) 99-99-999
$mask_mobile = "+7 (".substr($phone,0,3).") ".substr($phone,3,3)."-".substr($phone,6,2)."-".substr($phone,8);//+7 (999) 999-99-99
echo "Вхождение: ".$phone."<br>";
echo "Телефон для поиска: ".$mask_phone."<br>";
echo "Мобильный для поиска: ".$mask_mobile."<br>";
echo "<table border='1'><tr><th>ID</th><th>Имя</th><th>Фамилия</th><th>Телефон рабочий</th><th>Телефон личный</th></tr>";
$results = $DB->Query("SELECT ID, NAME, LAST_NAME, PERSONAL_PHONE, PERSONAL_MOBILE FROM b_user WHERE ACTIVE='Y' AND (PERSONAL_PHONE ='".$mask_phone."' OR PERSONAL_MOBILE = '".$mask_mobile."')");
while ($row = $results->Fetch())
{
  echo "<tr>";
  foreach ($row as $key=>$value){
        switch ($key) {
          case 'EventType':
            echo "<td>".$event_code[$value]."</td>";
            break;
          default:
            echo "<td>".$value."</td>";
        }    
      }
  echo "</tr>";
}


echo "</table>";

$type = phonetype($phone, $DB);
$id = whose($phone,$type, $DB);
echo $type." ".$id;
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");
?>