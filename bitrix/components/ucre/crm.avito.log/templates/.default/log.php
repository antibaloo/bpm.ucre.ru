<?php
if(!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED!==true) die();
echo $arResult['CONTENT'];
?>
<div style="background-color: white; padding: 5px;">
  <div class="gridWrapper">
    <div class="gridHeader textCenter">Дата загрузки</div>
    <div class="gridHeader textCenter">Ссылка на объявление</div>
    <div class="gridHeader textCenter">Статус</div>
    <div class="gridHeader textCenter">Дополнительно</div>
    <div class="gridHeader textCenter">Срок размещения</div>
    <div class="gridHeader textCenter">Сообщение</div>
    <?foreach ($arResult['DATA'] as $avitoLogItem){?>
    <div class="gridCell textCenter"><?=$avitoLogItem['UF_TIME']?></div>
    <div class="gridCell textRight"><a href="<?=$avitoLogItem['UF_AVITO_LINK']?>" target="_blank">Ссылка на объявление</a></div>
    <div class="gridCell textCenter"><?=$avitoLogItem['UF_STATUS']?></div>
    <div class="gridCell textRight"><?=$avitoLogItem['UF_STATUS_MORE']?></div>
    <div class="gridCell textRight"><?=$avitoLogItem['UF_TILL']?></div>
    <div class="gridCell textRight"><?=$avitoLogItem['UF_MESSAGE']?></div>
    <?}?>
  </div>
</div>
