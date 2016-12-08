<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Подбор объектов недвижимости:");
?>
<script script type="text/javascript" src="clearForm.js"></script>
<form action="" method="post">
  <label for="type">Тип объекта:</label>
  <select id="type" name="TYPE" size="1">
    <option <?=(!$_REQUEST['TYPE']? 'selected':'')?> value="">(любой)</option>
    <option <?=($_REQUEST['TYPE']==381? 'selected':'')?> value="381">комната</option>
    <option <?=($_REQUEST['TYPE']==382? 'selected':'')?> value="382">квартира</option>
    <option <?=($_REQUEST['TYPE']==383? 'selected':'')?> value="383">дом</option>
    <option <?=($_REQUEST['TYPE']==384? 'selected':'')?> value="384">таунхаус</option>
    <option <?=($_REQUEST['TYPE']==385? 'selected':'')?> value="385">дача</option>
    <option <?=($_REQUEST['TYPE']==386? 'selected':'')?> value="386">участок</option>
    <option <?=($_REQUEST['TYPE']==387? 'selected':'')?> value="387">коммерческий</option>
  </select>
  <label for="rooms">&nbsp;Кол-во комнат:</label>
  <input id="rooms" name="ROOMS" type="number" min="1" max="20" step="1" value="<?=($_REQUEST['ROOMS'])?$_REQUEST['ROOMS']:""?>"/>
  <label for="locality">&nbsp;Район:</label>
  <select id="locality" name="LOCALITY" type="text" value="<?=($_REQUEST['LOCALITY'])?$_REQUEST['LOCALITY']:""?>">
    <option <?=(!$_REQUEST['LOCALITY']? 'selected':'')?> value="">(любой)</option>
    <option <?=($_REQUEST['LOCALITY']=='Дзержинский'? 'selected':'')?> value="Дзержинский">Дзержинский</option>
    <option <?=($_REQUEST['LOCALITY']=='Ленинский'? 'selected':'')?> value="Ленинский">Ленинский</option>
    <option <?=($_REQUEST['LOCALITY']=='Промышленный'? 'selected':'')?> value="Промышленный">Промышленный</option>
    <option <?=($_REQUEST['LOCALITY']=='Центральный'? 'selected':'')?> value="Центральный">Центральный</option>
  </select>
  <label for="street">&nbsp;Улица:</label>
  <input id="street" name="STREET" type="text" value="<?=($_REQUEST['STREET'])?$_REQUEST['STREET']:""?>">
  <br>
  <br>
  <label for="minprice">Цена от:</label>
  <input id="minprice" name="MINPRICE" type="number" min="0" step="100000" value="<?=($_REQUEST['MINPRICE'])?$_REQUEST['MINPRICE']:""?>">
  <label for="maxprice">&nbsp;до:</label>
  <input id="maxprice" name="MAXPRICE" type="number" min="0" step="100000" value="<?=($_REQUEST['MAXPRICE'])?$_REQUEST['MAXPRICE']:""?>">
  <label for="minsquare">&nbsp;Общ. площадь от:</label>
  <input id="minsquare" name="MINSQUARE" type="number" min="1" max ="999"step="1" value="<?=($_REQUEST['MINSQUARE'])?$_REQUEST['MINSQUARE']:""?>">
  <label for="maxsquare">&nbsp;до:</label>
  <input id="maxsquare" name="MAXSQUARE" type="number" min="1" max ="999" step="1" value="<?=($_REQUEST['MAXSQUARE'])?$_REQUEST['MAXSQUARE']:""?>">
  <br>
  <br>
  <label for="minfloor">Этаж от:</label>
  <input id="minfloor" name="MINFLOOR" type="number" min="1" max ="17" step="1" value="<?=($_REQUEST['MINFLOOR'])?$_REQUEST['MINFLOOR']:""?>">
  <label for="maxfloor">&nbsp;до:</label>
  <input id="maxfloor" name="MAXFLOOR" type="number" min="1" max ="17"step="1" value="<?=($_REQUEST['MAXFLOOR'])?$_REQUEST['MAXFLOOR']:""?>">
  <label for="minfloors">&nbsp;Этажность от:</label>
  <input id="minfloors" name="MINFLOORS" type="number" min="1" max ="17" step="1" value="<?=($_REQUEST['MINFLOORS'])?$_REQUEST['MINFLOORS']:""?>">
  <label for="maxfloors">&nbsp;до:</label>
  <input id="maxfloors" name="MAXFLOORS" type="number" min="1" max ="17"step="1" value="<?=($_REQUEST['MAXFLOORS'])?$_REQUEST['MAXFLOORS']:""?>">
  <label for="minplot">&nbsp;Участок от:</label>
  <input id="minplot" name="MINPLOT" type="number" min="1" max ="1000" step="1" value="<?=($_REQUEST['MINPLOT'])?$_REQUEST['MINPLOT']:""?>">
  <label for="maxplot">&nbsp;до:</label>
  <input id="maxplot" name="MAXPLOT" type="number" min="1" max ="1000"step="1" value="<?=($_REQUEST['MAXPLOT'])?$_REQUEST['MAXPLOT']:""?>">
  <p><input type="submit" value="Подбор объектов">&emsp;<input type="button" name="reset_form" value="Сброс параметров" onclick="clearForm(this.form);"></p>
 </form> 
<!--
<?if ($USER->IsAdmin()){?>
<div style="position:fixed; top:50px; right:10px; height:300px; width:600px; border:3px solid #73AD21; z-index: +1000;background-color:#fff;">Отладочное окно: <br><?print_r($_REQUEST);?>
</div>
<?}?>
-->
<?

echo "<br>";
$map_data = array();
$map_data['yandex_lat'] = "51.7687567";
$map_data['yandex_lon'] = "55.1032404";
$map_data['yandex_scale'] = "10";
$num=0;
if (!(empty($_REQUEST['TYPE']) && empty($_REQUEST['ROOMS']) && empty($_REQUEST['STREET']) && empty($_REQUEST['MINPRICE']) && empty($_REQUEST['MAXPRICE']) && empty($_REQUEST['MINSQUARE']) && empty($_REQUEST['MAXSQUARE']) && empty($_REQUEST['LOCALITY']) && empty($_REQUEST['MINFLOOR']) && empty($_REQUEST['MAXFLOOR']) && empty($_REQUEST['MINFLOORS']) && empty($_REQUEST['MAXFLOORS'])&& empty($_REQUEST['MINPLOT'])&& empty($_REQUEST['MAXPLOT']))){
  $arSelect = Array("ID", "IBLOCK_ID", "NAME", "PROPERTY_*");
  $iblock_filter = array (
    "IBLOCK_ID" => 42,
    //"ACTIVE"=>"Y",
    //"?NAME"=>"One | Two"
    "PROPERTY_266" => array("Активный","Активная стадия", "Предложение"),
    "PROPERTY_210" => $_REQUEST['TYPE'], 
    "PROPERTY_229" => $_REQUEST['ROOMS'], 
    "%PROPERTY_217" => $_REQUEST['STREET'],
  );
  if ($_REQUEST['MINPRICE'] && $_REQUEST['MAXPRICE']){$iblock_filter["><PROPERTY_321"] = array($_REQUEST['MINPRICE'],$_REQUEST['MAXPRICE']);}
  if ($_REQUEST['MINPRICE'] && !$_REQUEST['MAXPRICE']){$iblock_filter[">=PROPERTY_321"] = $_REQUEST['MINPRICE'];}
  if ($_REQUEST['MAXPRICE'] && !$_REQUEST['MINPRICE']){$iblock_filter["<=PROPERTY_321"] = $_REQUEST['MAXPRICE'];}
  if ($_REQUEST['MINSQUARE'] && $_REQUEST['MAXSQUARE']){$iblock_filter["><PROPERTY_224"] = array($_REQUEST['MINSQUARE'],$_REQUEST['MAXSQUARE']);}
  if ($_REQUEST['MINSQUARE'] && !$_REQUEST['MAXSQUARE']){$iblock_filter[">=PROPERTY_224"] = $_REQUEST['MINSQUARE'];}
  if ($_REQUEST['MAXSQUARE'] && !$_REQUEST['MINSQUARE']){$iblock_filter["<=PROPERTY_224"] = $_REQUEST['MAXSQUARE'];}
  if ($_REQUEST['LOCALITY']){$iblock_filter["PROPERTY_216"] = $_REQUEST['LOCALITY'];}
  if ($_REQUEST['MINFLOOR'] && $_REQUEST['MAXFLOOR']){$iblock_filter["><PROPERTY_221"] = array($_REQUEST['MINFLOOR'],$_REQUEST['MAXFLOOR']);}
  if ($_REQUEST['MINFLOOR'] && !$_REQUEST['MAXFLOOR']){$iblock_filter[">=PROPERTY_221"] = $_REQUEST['MINFLOOR'];}
  if ($_REQUEST['MAXFLOOR'] && !$_REQUEST['MINFLOOR']){$iblock_filter["<=PROPERTY_221"] = $_REQUEST['MAXFLOOR'];}
  if ($_REQUEST['MINFLOORS'] && $_REQUEST['MAXFLOORS']){$iblock_filter["><PROPERTY_222"] = array($_REQUEST['MINFLOORS'],$_REQUEST['MAXFLOORS']);}
  if ($_REQUEST['MINFLOORS'] && !$_REQUEST['MAXFLOORS']){$iblock_filter[">=PROPERTY_222"] = $_REQUEST['MINFLOORS'];}
  if ($_REQUEST['MAXFLOORS'] && !$_REQUEST['MINFLOORS']){$iblock_filter["<=PROPERTY_222"] = $_REQUEST['MAXFLOORS'];}
  if ($_REQUEST['MINPLOT'] && $_REQUEST['MAXPLOT']){$iblock_filter["><PROPERTY_292"] = array($_REQUEST['MINPLOT'],$_REQUEST['MAXPLOT']);}
  if ($_REQUEST['MINPLOT'] && !$_REQUEST['MAXPLOT']){$iblock_filter[">=PROPERTY_292"] = $_REQUEST['MINPLOT'];}
  if ($_REQUEST['MAXPLOT'] && !$_REQUEST['MINPLOT']){$iblock_filter["<=PROPERTY_292"] = $_REQUEST['MAXPLOT'];}
  $db_res = CIBlockElement::GetList(array("ID"=>"ASC"), $iblock_filter, false, false, $arSelect);
  $ids = array();
  while($aRes = $db_res->GetNext()){
    $map_data['PLACEMARKS'][] = array('LAT' => $aRes['PROPERTY_298'],'LON' => $aRes['PROPERTY_299'],'TEXT' => '<a href="https://bpm.ucre.ru/crm/ro/?show&id='.$aRes['ID'].'" target="_blank" title="'.$aRes['PROPERTY_209'].'">'.$aRes['NAME']."<br>".number_format($aRes['PROPERTY_321'],2,"."," ").' &#8381;<br>'.$aRes['PROPERTY_221'].'/'.$aRes['PROPERTY_222'].' эт.</a>',);
    $objects[$aRes['ID']] = '<a href="https://bpm.ucre.ru/crm/ro/?show&id='.$aRes['ID'].'" target="_blank" title="'.$aRes['PROPERTY_209'].'">'.$aRes['NAME'].'</a>';
    $num++;
  }
}
$str = ($num)?$num:"нет";
$APPLICATION->SetTitle("Подбор объектов недвижимости: ".$str);
?>
<div style="width: 50%; float: left;">
<?$APPLICATION->IncludeComponent("bitrix:map.yandex.view",".default",Array(
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
);?>
</div>
<div style="margin-left: 10px">
  <table>
    <tr><th>ID</th><th>Название</th></tr>
  <?
  foreach ($objects as $key=> $object)
    echo "<tr><td>".$key."</td><td>".$object."</td></tr>";
  ?>
  </table>
</div>  
<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>