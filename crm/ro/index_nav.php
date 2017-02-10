<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Объекты недвижимости");
?>
<?
$objects = $DB->Query("SELECT * FROM b_crm_ro");
$objects->NavStart(20);
if (intval($objects->SelectedRowsCount())>0):
    echo $objects->NavPrint("Объекты недвижимости");
    while($objects->NavNext(true, "f_")):
         echo "[".$f_ID."] ".$f_ADDRESS."<br>";
    endwhile;
    echo $objects->NavPrint("Объекты недвижимости");
endif;
?>

<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");
?>