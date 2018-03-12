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
  .usersWrapper{
    margin-top: 10px;
    display: grid;
    grid-template-rows: 30px;
    grid-template-columns: 1fr 1fr;
    grid-gap: 2px;
  }
  .usersHeader{
    height: 25px;
    line-height: 25px;
    text-align: center;
    background: #cccccc;
    font-weight: bold;
  }
  .usersRow{
    height: 25px;
    line-height: 25px;
    text-align: center;
    background: #eee;
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
<?
//Список пользователей портала, авктивных сотрудников
$filter = array("ACTIVE" => "Y", "GROUPS_ID" => array(12));
$rsUsers = CUser::GetList(($by="id"), ($order="asc"), $filter); 
while($arUser = $rsUsers->Fetch()){$users[$arUser['ID']] = array('FULL_NAME' => $arUser['LAST_NAME']." ".$arUser['NAME']);}

$rsBUsers = `curl -X GET --header 'X-MPBX-API-AUTH-TOKEN: {$config['token']}' 'https://cloudpbx.beeline.ru/apis/portal/abonents'`;
$arUsers = json_decode($rsBUsers,true);
foreach($arUsers as $arUser){
  $bUsers[$arUser['userId']] = array("phone" => $arUser["phone"],"firstName" => $arUser["firstName"],"lastName" => $arUser["lastName"],"department" => $arUser["department"],"extension" => $arUser["extension"]);
}


//Ассоциированный список пользователей системы и АТС
$rsAssoc = $DB->Query("select * from b_beelinepbx_users");
while ($arAssoc = $rsAssoc->Fetch()){$assoc[$arAssoc['id']] = array('bitrix_user' => $arAssoc['bitrix_user'],'beeline_user' => $arAssoc['beeline_user']);}
//echo "<pre>";print_r($assoc);echo "</pre>";
?>
<form id="usersTable">
  <div class="usersWrapper">
    <div class="usersHeader">Сотрудник компании</div>
    <div class="usersHeader">Пользователь АТС</div>
    <?foreach ($assoc as $assocUser/*$id=>$userId*/){?>
    
    <div class="usersRow">
      <?=$users[$assocUser['bitrix_user']]['FULL_NAME']?>
    </div>
    <div class="usersRow">
      <select name="assocUsers[<?=$assocUser['bitrix_user']?>_<?=rand(100,1000)?>]">
        <option value="">Не выбран</option>
        <?foreach($bUsers as $key=>$bUser){?>
        <option value="<?=$key?>" <?=($key==$assocUser['beeline_user'])?"selected":""?>><?=$bUser['lastName']." ".$bUser['firstName']." (".$bUser['department']."): +7".$bUser['phone']?></option>
        <?}?>
      </select>
    </div>
    <?}?>
    <?foreach($users as $key=>$user){?>
    <div class="usersRow">
      <select class="bitrix" id-data="<?=$key?>">
        <option value="">Не выбран</option>
        <?foreach($users as $key1=>$user1){?>
        <option value="<?=$key1?>"><?=$user1['FULL_NAME']?></option>
        <?}?>
      </select>
    </div>
    <div class="usersRow">
      <select id="beeline<?=$key?>">
        <option value="">Не выбран</option>
        <?foreach($bUsers as $key2=>$bUser){?>
        <option value="<?=$key2?>"><?=$bUser['lastName']." ".$bUser['firstName']." (".$bUser['department']."): +7".$bUser['phone']?></option>
        <?}?>
      </select>
    </div>
    <?}?>
  </div>
  <div class="buttonWrapper">
    <div class="empty"></div>
    <div class="empty"></div>
    <div class="empty"></div>
    <div class="formButton" action="save">Сохранить соответствие</div>
    <div class="empty"></div>
    <div class="empty"></div>
    <div class="empty"></div>
  </div>
</form>
<div id="saveResult"></div>
<script>
   function getRandomInt(min, max) {
    return Math.floor(Math.random() * (max - min)) + min;
  }
  $(".bitrix").change(function (){
    console.log($(this).attr("id-data"));
    console.log($(this).val());
    $("#beeline"+$(this).attr("id-data")).attr("name", "assocUsers["+$(this).val()+"_"+getRandomInt(2000,20000)+"]");
  });
  $(".formButton").click(function (){
    var action = $(this).attr("action");
    if (action != 'save'){
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
    }else{
      var form = $("#usersTable").serialize();
      $.ajax({
        url:'./ajax.php',
        type: "POST",
        dataType: "html",
        data: {
          table:form,
          action: action
        },
        success: function (html) {
          $("#saveResult").html(html);
          location.reload(true);
        },
        error: function (html) {
          $("#saveResult").html("Технические неполадки! В ближайшее время все будет исправлено!");
        },
      });
    }
  });
</script>
<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");
?>