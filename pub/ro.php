<?
require ($_SERVER['DOCUMENT_ROOT'] . "/bitrix/modules/main/include/prolog_before.php");
if (isset($_GET['id']) && $_GET['id']>0 && isset($_GET['link']) && !empty($_GET['link'])){  
  CIBlockElement::SetPropertyValuesEx($_GET['id'], false, array('LINK' => $_GET['link']));
}else {
  echo "<center><img style='margin: 0 auto;' src='images/away.jpg'></center>";
}
?>