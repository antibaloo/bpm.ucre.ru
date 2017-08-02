<link href="/include/custom_css/custom_paging.css?<?=time();?>" rel="stylesheet">
<?php
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED!==true)die();
?>
<button class="submit" value="new">Новые</button><button class="submit" value="yes">Положительные</button><button class="submit" value="no">Отрицательные</button>
<div id="potentials">
</div>
<div id="grid" style="width: 100%; height: 250px;"></div>

<div id="markdialog" style="text-align:center; box-shadow: 0px 0px 100px 0px #000000; width: 400px;height: 250px;	margin: auto;display: none;background: #fff;z-index: 200;	position: fixed;left: 0;right: 0;top: 0;bottom: 0;padding: 10px;">
  <div id="markcaption"></div>
  <hr>
  <form id="markform" style="text-align:center;">
    <input name="buy_id" type="hidden" value="<?=$arResult['ID']?>">
    <input id="type" name="type" type="hidden" value="mark">
    <input id="sell_id" name="sell_id" type="hidden">
    <input id="type_mark" name="type_mark" type="hidden">
    <table style="margin:auto;">
      <tr>
        <td>Цена</td><td>+ <input name="price" type="radio" value="+"> <input name="price" type="radio" value="-"> -</td>
      </tr>
      <tr>
        <td>Объект</td><td>+ <input name="object" type="radio" value="+"> <input name="object" type="radio" value="-"> -</td>
      </tr>
      <tr>
        <td>Подъезд</td><td>+ <input name="access" type="radio" value="+"> <input name="access" type="radio" value="-"> -</td>
      </tr>
      <tr>
        <td>Двор</td><td>+ <input name="yard" type="radio" value="+"> <input name="yard" type="radio" value="-"> -</td>
      </tr>
      <tr>
        <td>Инф-ра</td><td>+ <input name="infra" type="radio" value="+"> <input name="infra" type="radio" value="-"> -</td>
      </tr>
      <tr>
        <td>Комментарий</td><td><textarea rows="3" cols="45" style="resize: none;" name="comment"></textarea></td>
      </tr>
    </table>
  </form>
  <hr>
  <a href="javascript:mark_deal()">Оценить</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="javascript:$('#markdialog').hide();">Отменить</a>
  <div id="resultMark"></div>
</div>
<div id="deldialog" style="text-align:center; box-shadow: 0px 0px 100px 0px #000000; width: 400px;height: 45px;	margin: auto;display: none;background: #fff;z-index: 200;	position: fixed;left: 0;right: 0;top: 0;bottom: 0;padding: 16px;">
  <div>
    <b>Удалить заявку из потенциальных сделок?</b>
  </div>
  <hr>
  <form id="delform">
    <input id="del_id"type="hidden">
  </form>
  <a href="javascript:delete_deal($('#del_id').val())">Да</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="javascript:$('#deldialog').hide();">Отменить</a>
  <div id="resultDel"></div>
</div>
<script>
  $(function() {//Вызов при начальной загрузке странице с фильтром по-умолчанию
    $.ajax({
      url:'<?=$arResult['COMPONENT_PATH']?>/ajax.php',
      type: "POST",
      dataType: "html",
      data: {
        id:<?=$arResult['ID']?>,
        filter:'new',
        assigned_by_id:<?=$arResult['ASSIGNED_BY_ID']?>
      },
      success: function (html) {
        $("#potentials").html(html);
      },
      error: function (html) {
        $("#potentials").html("Технические неполадки! В ближайшее время все будет исправлено!");
      },
    });
  });
  
  $(".submit").click(function (){//Вызов при нажатии кнопки странице с соответствующим фильтром
    $.ajax({
      url:'<?=$arResult['COMPONENT_PATH']?>/ajax.php',
      type: "POST",
      dataType: "html",
      data: {
        id:<?=$arResult['ID']?>,
        filter:$(this).val(), //filter:$(this).html()
        assigned_by_id:<?=$arResult['ASSIGNED_BY_ID']?>
      },
      success: function (html) {
        $("#potentials").html(html);
      },
      error: function (html) {
        $("#potentials").html("Технические неполадки! В ближайшее время все будет исправлено!");
      },
    });
  });
  function set_active(object){
    if(!object.classList.contains('active')){
      var el = document.getElementById("page"+object.innerHTML);
      var a_page = document.getElementsByClassName("page active");
      var a_pages = document.getElementsByClassName("pages active");
      a_page[0].classList.remove('active');
      a_pages[0].classList.remove('active');
      el.classList.add('active');
      object.classList.add('active');
    }
  }
  
  
  function mark_deal (){
    var form = $('#markform').serialize();
    $.ajax({
      url:'<?=$arResult['COMPONENT_PATH']?>/action.php',
      type: "POST",
      dataType: "text",
      data: form,
      success: function (html) {
        $("#resultMark").html(html);
        if (!html){
          $("#markdialog").hide();
          $('#P'+$("#sell_id").val()).remove();
          $('#countP').text($('.rowP').length);
        }
      },
      error: function (html) {
        $("#resultMark").html("Технические неполадки! Обратитесь к системному администратору!");
      },
     });
  }
  function delete_deal(id){
    $.ajax({
      url:'<?=$arResult['COMPONENT_PATH']?>/action.php',
      type: "POST",
      dataType: "html",
      data:{
        buy_id: <?=$arResult['ID']?>,
        sell_id: id,
        type: 'delete',
      },
      success: function (html) {
        $("#deldialog").hide();
        $('#P'+id).remove();
        $('#countP').text($('.rowP').length);
      },
      error: function (html) {
        $("#resultDel").html("Технические неполадки! Обратитесь к системному администратору!");
      },
    });
  }
  
function showDialog(id,type){
  if (type=="delete") {
    $('#delform').trigger( 'reset' );
    $("#resultDel").html("");
    $("#del_id").val(id);
    $("#deldialog").show();
  }
  if (type=="plus"){
    $('#markform').trigger( 'reset' );
    $("#resultMark").html("");
    $("#markcaption").html("<b>Принять заявку и оценить ее:<b/>");
    $("#sell_id").val(id);
    $("#type_mark").val('yes');
    $("#markdialog").show();
  }
  if (type=="minus"){
    $('#markform').trigger( 'reset' );
    $("#resultMark").html("");
    $("#markcaption").html("<b>Забраковать заявку и оценить ее:</b>");
    $("#sell_id").val(id);
    $("#type_mark").val('no');
    $("#markdialog").show();
  }
}
</script>