<style>
  .buttonWrapper{
  margin-top: 10px;
  display: grid;
  grid-template-rows: 25px;
  grid-template-columns: 1fr 1fr 1fr 1fr 1fr 1fr 1fr;
  grid-gap: 20px;
}
.formButton{
  height: 25px;
  line-height: 25px;
  text-align: center;
  color:  white;
  font-weight: bold;
  background: #5CCCCC;
}
.formButton:hover{
  background: #009999;
  cursor: pointer;
}
</style>
<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Настройки интеграции с АТС Билайн");
if (!$USER->IsAdmin()) die("Доступ запрещен!");
$rsConfig = $DB->Query("select * from b_beelinepbx_config");
while ($arConfig = $rsConfig->Fetch()){$config[$arConfig['param']] = $arConfig['value'];}
echo "<pre>";print_r($config);echo "</pre>";
?>
<center><h2>После удаления и создания подписки необходимо обновить страницу!</h2></center>
<div class="buttonWrapper">
  <div class="empty"></div>
  <div class="formButton" action="check">Проверить текущую подписку</div>
  <div class="empty"></div>
  <div class="formButton" action="delete">Удалить текущую подписку</div>
  <div class="empty"></div>
  <div class="formButton" action="create">Создать новую подписку</div>
  <div class="empty"></div>
</div>

<div id="subcriptionResult"></div>

<script>
  $(".formButton").click(function (){
    var action = $(this).attr("action");
    $.ajax({
      url:'./ajax.php',
      type: "POST",
      dataType: "html",
      data: {
        subscriptionId:'<?=$config['subscriptionId']?>',
        token:'<?=$config['token']?>',
        action: action
      },
      success: function (html) {
        $("#subcriptionResult").html(html);
      },
      error: function (html) {
        $("#subcriptionResult").html("Технические неполадки! В ближайшее время все будет исправлено!");
      },
    });
  });
</script>
<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");
?>