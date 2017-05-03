<?php
if(!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED!==true) die();
?>
<h2>
  Шаблон компонента потенциальных сделок <?=$arResult['FILTER']?>
</h2>
<button class="filter" id="new"><span <?=($arResult['FILTER'] == "new")?'style="color:red"':''?>>Новые!</span></button>
<button class="filter" id="yes"><span <?=($arResult['FILTER'] == "yes")?'style="color:red"':''?>>Скорее да!</span></button>
<button class="filter" id="no"><span <?=($arResult['FILTER'] == "no")?'style="color:red"':''?>>Скорее нет!</span></button>
<button class="filter" id="all"><span <?=($arResult['FILTER'] == "all")?'style="color:red"':''?>>Все!</span></button>
<form id="myform">
  <input name="id" type="hidden" value="<?=$arResult['ID']?>">
  <input name="category" type="hidden" value="<?=$arResult['CATEGORY']?>">
  <input name="tabid" type="hidden" value="<?=$arResult['TABID']?>">
</form>
<pre>
<?print_r($arResult);?>
</pre>

<script>
  $(document).ready(function() {
    $('.filter').on('click', function () {
      var data = $('#myform').serialize();
      data +='&filter='+this.id;
      $.ajax({
        type: "POST",
        url: "/ajax/potentials.php",
        dataType: "text",
        data: data,
        success: function (html) {
          $("#<?=$arResult['TABID']?>").html(html);
        },
        error: function (html) {
          $("#<?=$arResult['TABID']?>").html("Что-то пошло не так!");
        },
      });
    });
  });
</script>