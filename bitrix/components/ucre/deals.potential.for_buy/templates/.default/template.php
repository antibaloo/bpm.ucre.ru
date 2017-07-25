<link href="/include/custom_css/custom_paging.css?<?=time();?>" rel="stylesheet">
<?php
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED!==true)die();
echo "Потенциальные сделки для заявок на покупку!<br>";
?>
<button class="submit" value="new">Новые</button><button class="submit" value="yes">Положительные</button><button class="submit" value="no">Отрицательные</button>
<div id="potentials">
</div>
<script>
  $(function() {//Вызов при начальной загрузке странице с фильтром по-умолчанию
    $.ajax({
      url:'<?=$arResult['COMPONENT_PATH']?>/ajax.php',
      type: "POST",
      dataType: "html",
      data: {
        id:<?=$arResult['ID']?>,
        filter:'new'
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
        filter:$(this).val() //filter:$(this).html()
      },
      success: function (html) {
        $("#potentials").html(html);
      },
      error: function (html) {
        $("#potentials").html("Технические неполадки! В ближайшее время все будет исправлено!");
      },
    });
  });
</script>