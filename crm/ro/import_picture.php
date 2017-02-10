<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Импорт картинок в инфоблоки");
?>
<?
$arIMAGE = CFile::MakeFileArray("http://ecrplus.ru/storage/2015/July/week1/1722_5595972920a95.jpg");
//$fid = CFile::SaveFile($arIMAGE, "iblock");
echo $fid;
echo "<hr>";
echo CFile::ShowImage($fid);
echo "<hr>";

?>
<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");
?>