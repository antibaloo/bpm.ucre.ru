<?
require ($_SERVER['DOCUMENT_ROOT'] . "/bitrix/modules/main/include/prolog_before.php");
if (isset($_GET['id']) && $_GET['id']>0 && isset($_GET['link']) && !empty($_GET['link'])){
  $arFields['PROPERTY_VALUES'][301] = $_GET['link'];;
  $object = new CIBlockElement;
  if ($object->Update($_GET['id'], $arFields)){
    echo "Updated!";
  } else {
    $object->LAST_ERROR;
  }
}else {
  echo "<center><img style='margin: 0 auto;' src='images/away.jpg'></center>";
}
?>