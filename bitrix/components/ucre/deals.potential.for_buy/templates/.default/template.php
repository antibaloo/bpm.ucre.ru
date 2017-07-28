<link href="/include/custom_css/custom_paging.css?<?=time();?>" rel="stylesheet">
<?php
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED!==true)die();
?>
<button class="submit" value="new">Новые</button><button class="submit" value="yes">Положительные</button><button class="submit" value="no">Отрицательные</button>
<div id="potentials">
</div>
<div id="grid" style="width: 100%; height: 250px;"></div>
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
  function addplus(id){
    
  }
  function addminus(id){
    
  }
  function deletefrom(id){
    $('#P'+id).remove();
    $('#countP').text($('.rowP').length);
  }
</script>