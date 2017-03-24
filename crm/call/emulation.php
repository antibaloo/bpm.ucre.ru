<?php
require_once($_SERVER['DOCUMENT_ROOT']."/bitrix/header.php");
$megapbx = json_decode(file_get_contents($_SERVER['DOCUMENT_ROOT']."/../megapbx_params"));
$APPLICATION->SetTitle("Эмулятор ВАТС Мегафон");
?>
<form id="megapbx">
  Идентирфикатор звонка
  <input type="text" name="callid" id="callid" readonly="" size="37">
  <input type="button" id="gencallid" value="Создать callid">
  <hr>
  Команда API
  <select name="cmd">
    <option value="contact">contact</option>
    <option value="event">event</option>
    <option value="history">history</option>
  </select>
  <hr>
  <input type="hidden" name="crm_token" readonly="" size="45" value="<?=$megapbx->crm_key?>">
  Номер телефона клиента
  <input type="text" name="phone" value="79877955786">
  <hr>
  <input id="send" type="button" value="Отправить">
  <input type="hidden" name="user" value="test">
</form>
<div id="crm_answer"></div>
<?
require_once($_SERVER['DOCUMENT_ROOT']."/bitrix/footer.php");
?>
<script>
  function makeid(){
    var text = "";
    var possible = "abcdefghijklmnopqrstuvwxyz0123456789";   
    for( var i=0; i < 32; i++ ) text += possible.charAt(Math.floor(Math.random() * possible.length));
    return text;
}
  $(document).ready(function() {
    $('#send').on('click', function () {
      var data = $('#megapbx').serialize();
      $.ajax({
        type: "POST",
        url: "../../pub/megapbx.php",
        dataType: "text",
        data: data,
        success: function (html) {
          $("#crm_answer").html(html);
        },
        error: function (html) {
          $("#crm_answer").html("Технические неполадки! В ближайшее время все будет исправлено!");
        },
      });
    });
    $('#gencallid').on('click', function(){
      $("#callid").val(makeid());       
    });
  });
</script>