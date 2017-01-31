<?php
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED!==true)die();
global $APPLICATION;
?>

<div id="search-form"> Форма поиска </div>
<div id="map">
<?$APPLICATION->IncludeComponent(
  "bitrix:map.yandex.view",
  ".default",
  array(
    "INIT_MAP_TYPE" => "PUBLIC",
    "DEV_MODE" => "Y",
    "MAP_DATA" => serialize($map_data),
    "MAP_WIDTH" => "auto",
    "MAP_HEIGHT" => "500",
    "CONTROLS" => array(
    "ZOOM",
    "SMALLZOOM",
    "SCALELINE"
  ),
  "OPTIONS" => array(
      "ENABLE_SCROLL_ZOOM",
      "ENABLE_DBLCLICK_ZOOM",
      "ENABLE_DRAGGING"
  ),
  "MAP_ID" => "yam_1"
  )
);
?>
</div>
<div id="search-result-grid"> Список заявок </div>