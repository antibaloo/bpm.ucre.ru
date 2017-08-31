<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
IncludeModuleLangFile($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/intranet/public/crm/reports/index.php");
$APPLICATION->SetTitle("Найм сотрудников");
?>
<style>
  .workarea-content-paddings{
    height:100%;
  }
</style>
<iframe src="recruiting.html" width="100%" height="100%">
  
</iframe>
<script>
 // $(document).ready(function(){
   // $(".workarea-content-paddings").height("100%");
//});
</script>
<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>