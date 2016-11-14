<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
global $USER;
$APPLICATION->SetTitle("Лог загрузки объявлений на avito.ru");
echo 'Ваш идентификатор в системе: '.$USER::GetID();


require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");
?>