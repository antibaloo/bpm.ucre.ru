<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Районы города Оренбург");
?>
<div id="districts"></div>
<script type="text/javascript" charset="utf-8" async src="https://api-maps.yandex.ru/services/constructor/1.0/js/?um=constructor%3A75f264a713c83546709b1bb6c2fe20f604afdbb2d4e8576ff254d7cf8acb124b&amp;id=districts&amp;width=100%25&amp;height=558&amp;lang=ru_RU&amp;scroll=true"></script>
<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");
?>