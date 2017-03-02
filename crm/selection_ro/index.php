<link href="custom.css?<?=time(); ?>" rel="stylesheet">
<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
use \Bitrix\Crm\Category\DealCategory;
if ($_POST['goal']!='buy')  $APPLICATION->SetTitle("Подбор заявок на продажу");
if ($_POST['goal']=='buy')  $APPLICATION->SetTitle("Подбор заявок на покупку");
/*Блок формирования результатов поиска в зависимости от параметров*/
if ($_POST['goal']=='buy') {/*
    while($aRes = $rsData->Fetch()){
    $assigned_user = CUser::GetByID($aRes['ASSIGNED_BY_ID'])->Fetch();
    switch ($aRes['UF_CRM_5895BC940ED3F']){
      case "a:2:{i:0;i:827;i:1;i:828;}":
        $market = "любой";
        break;
      case "a:0:{}":
        $market = "нет данных";
        break;
      case "a:1:{i:0;i:827;}":
        $market = "вторичка";
        break;
      case "a:1:{i:0;i:828;}":
        $market = "первичка";
        break;
    }
    switch ($aRes['UF_CRM_58958B51B667E']){
      case "a:2:{i:0;i:754;i:1;i:755;}":
        $floors = "не первый,<br>не последний";
        break;
      case "a:0:{}":
        $floors = "любой";
        break;
      case "a:1:{i:0;i:754;}":
        $floors = "не первый";
        break;
      case "a:1:{i:0;i:755;}":
        $floors = "не последний";
        break;
    }
    switch ($aRes['UF_CRM_58958B5724514']){
      case 813:
        $type = "комната";
        break;
      case 814:
        $type = "квартира";
        break;
      case 815:
        $type = "дом";
        break;
      case 816:
        $type = "таунхаус";
        break;
      case 817:
        $type = "дача";
        break;
      case 818:
        $type = "участок";
        break;
      case 819:
        $type = "коммерческий";
        break;
    }
    $aCols = array(
      "TITLE" => "<a href='/crm/deal/show/".$aRes['ID']."/' target='_blank'>".$aRes['TITLE']."</a>",
      "UF_CRM_5895BC940ED3F" => $market,
      "UF_CRM_58958B51B667E" => $floors,
      "UF_CRM_58958B5724514" => $type,
      "UF_CRM_58958B529E628" => (intval($aRes['UF_CRM_58958B529E628']))?intval($aRes['UF_CRM_58958B529E628']):"нет данных",
      "UF_CRM_58958B52BA439" => (floatval($aRes['UF_CRM_58958B52BA439']))?floatval($aRes['UF_CRM_58958B52BA439']):"нет данных",
      "UF_CRM_58958B52F2BAC" => (floatval($aRes['UF_CRM_58958B52F2BAC']))?floatval($aRes['UF_CRM_58958B52F2BAC']):"нет данных",
      "UF_CRM_58958B576448C" => (intval($aRes['UF_CRM_58958B576448C']))?intval($aRes['UF_CRM_58958B576448C']):"не задана",
      "UF_CRM_58958B5751841" => (intval($aRes['UF_CRM_58958B5751841']))?intval($aRes['UF_CRM_58958B5751841']):"не задана",
      "ASSIGNED_BY_ID" => $assigned_user['LAST_NAME']." ".$assigned_user['NAME'],
    );
    $aActions = array();
    $aRows[] = array("data"=>$aRes, "actions"=>$aActions, "columns"=>$aCols, "editable"=>false);
  }
  $rsData->bShowAll = false;
  $count = $rsData->SelectedRowsCount();*/
}
if ($_POST['goal']=='sell') {/*
  while($aRes = $rsData->Fetch()){
    $assigned_user = CUser::GetByID($aRes['ASSIGNED_BY_ID'])->Fetch();
    $aCols = array(
      "TITLE" => "<a href='/crm/deal/show/".$aRes['ID']."/' target='_blank'>".DealCategory::getName($aRes['CATEGORY_ID']).": ".$aRes['TITLE']."</a>",
      "NAME" => "<a href='/crm/ro/?show&id=".$aRes['UF_CRM_1469534140']."' target='_blank'>".$aRes['NAME']."</a>",
      "PROPERTY_222" => $aRes['PROPERTY_221']."/".$aRes['PROPERTY_222'],
      "PROPERTY_229" => number_format($aRes['PROPERTY_229'],0),
      "PROPERTY_224" => number_format($aRes['PROPERTY_224'],2),
      "PROPERTY_226" => number_format($aRes['PROPERTY_226'],2),
      "ASSIGNED_BY_ID" => $assigned_user['LAST_NAME']." ".$assigned_user['NAME'],
    );
    $aActions = array();
    $aRows[] = array("data"=>$aRes, "actions"=>$aActions, "columns"=>$aCols, "editable"=>false);
  }
  $rsData->bShowAll = false;
  $count = $rsData->SelectedRowsCount();*/
}
?>
<div>
  <?
  print_r($_POST);
  ?>
</div>
<div id="search_form">
  <form id="formid" action="" title="" method="post">
    Ищем заявку на 
    <select name="goal" onchange="set_goal(this)">
      <option value='sell' <?=($_POST['goal']!='buy'? 'selected':'')?>>продажу</option>
      <option value='buy' <?=($_POST['goal']=='buy'? 'selected':'')?>>покупку</option>
    </select>
    Рынок поиска
    <select name="market" required>
      <option value="Любой" <?=($_POST['market'] =='Любой'? 'selected':'')?>>Любой</option>
      <option value="Первичный" <?=($_POST['market'] =='Первичный'? 'selected':'')?>>Первичный</option>
      <option value="Вторичный" <?=($_POST['market'] =='Вторичный'? 'selected':'')?>>Вторичный</option>
    </select>
    <div id="buy" <?=($_POST['goal']!='buy'? 'style="display:none"':'')?>>
      <fieldset>
        <legend>
          Параметры заявки на покупку
        </legend>
      </fieldset>
    </div>
    <div id="sell" <?=($_POST['goal']=='buy'? 'style="display:none"':'')?>>
      <fieldset>
        <legend>
          Параметры заявки на продажу
        </legend>
      </fieldset>
    </div>
    <br><input type="submit" id="submitButton"  name="submitButton" value="Найти">
    <input type="button" name="reset_form" value="Сброс параметров" onclick="formid.reset();">
    <?if ($_POST['goal']!='buy'){?>
    <input type="button" id="toggle_map" name="toggle_map" value="Карта" onclick="toggle(search_map);">
    <?}?>
  </form>
</div>
<div id="result_grid">
  <?
  $rows = 20;
  $map_data = array();
  $map_data['yandex_lat'] = "51.7687567";
  $map_data['yandex_lon'] = "55.1032404";
  $map_data['yandex_scale'] = "10";
  if ($_POST['goal']=='buy') {
    $rsQuery = "SELECT b_crm_deal.ID, b_crm_deal.TITLE, b_crm_deal.ASSIGNED_BY_ID, b_uts_crm_deal.UF_CRM_5895BC940ED3F,b_uts_crm_deal.UF_CRM_58958B5724514,b_uts_crm_deal.UF_CRM_58958B529E628,b_uts_crm_deal.UF_CRM_58958B52BA439,b_uts_crm_deal.UF_CRM_58958B52F2BAC,b_uts_crm_deal.UF_CRM_58958B51B667E, b_uts_crm_deal.UF_CRM_58958B576448C, b_uts_crm_deal.UF_CRM_58958B5751841 FROM b_crm_deal INNER JOIN b_uts_crm_deal ON b_crm_deal.ID=b_uts_crm_deal.VALUE_ID WHERE b_crm_deal.CATEGORY_ID = 2 AND b_crm_deal.STAGE_ID = 'C2:PROPOSAL'";
    $rsQuery .= " ORDER BY b_crm_deal.ID DESC";
    $rsData = $DB->Query($rsQuery);
    $count = $rsData->SelectedRowsCount();
    $pages = ($count % $rows)?intval($count/$rows)+1:$count/$rows;
    for ($i=1;$i<=$pages;$i++){//Цикл по страницам
  ?>
  <div class="page<?=($i == 1)?" active":""?>" id="page<?=$i?>">
    <table>
      <tr>
        <th>id</th>
        <th>Название</th>
        <th>Рынок</th>
        <th>Тип объекта</th>
        <th>N<sub>комнат</sub> от</th>
        <th>S<sub>общ.</sub> от</th>
        <th>S<sub>кухни</sub> от</th>
        <th>Этажи</th>
        <th>Цена <sub>min</sub></th>
        <th>Цена <sub>max</sub></th>
        <th>Ответственный</th>
      </tr>
  <?
      for ($j=1;$j<=$rows;$j++){//Цикл по строкам
        if ($aRes = $rsData->Fetch()){//Если есть значение
          $assigned_user = CUser::GetByID($aRes['ASSIGNED_BY_ID'])->Fetch();
      ?>
      <tr>
        <td><?=$aRes['ID']?></td>
        <td style="text-align: left; padding-left: 10px;"><a href="/crm/deal/show/<?=$aRes['ID']?>/" target="_blank"><?=$aRes['TITLE']?></a></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
      </tr>
      <?          
        }
      }
  ?>
      <tr><td></td><td colspan="10" style="text-align: left; padding-left: 5px;">Всего: <?=$count?></td></tr>
    </table>
  </div>
  <?
    }
  ?>
  <div class="pages">
    <center>
  <?
    for ($i=1;$i<=$pages;$i++){//Цикл по страницам для номеров страниц
      echo "<span class='pages".(($i == 1)?" active":"")."' onclick='set_active(this)'>".$i."</span>&nbsp;";
    }
  ?>
    </center>
  </div>
  <?
    echo $count."=".$pages." по ".$rows;
  }
  if ($_POST['goal']=='sell') {
    $rsQuery = "SELECT b_crm_deal.ID,b_crm_deal.CATEGORY_ID, b_crm_deal.TITLE, b_crm_deal.ASSIGNED_BY_ID, b_uts_crm_deal.UF_CRM_1469534140,b_uts_crm_deal.UF_CRM_58958B5734602, b_iblock_element.NAME, b_iblock_element_prop_s42.PROPERTY_210, b_iblock_element_prop_s42.PROPERTY_209, b_iblock_element_prop_s42.PROPERTY_229, b_iblock_element_prop_s42.PROPERTY_224, b_iblock_element_prop_s42.PROPERTY_226, b_iblock_element_prop_s42.PROPERTY_221, b_iblock_element_prop_s42.PROPERTY_222, b_iblock_element_prop_s42.PROPERTY_298, b_iblock_element_prop_s42.PROPERTY_299 FROM b_crm_deal INNER JOIN b_uts_crm_deal ON b_crm_deal.ID=b_uts_crm_deal.VALUE_ID INNER JOIN b_iblock_element ON b_uts_crm_deal.UF_CRM_1469534140 = b_iblock_element.ID INNER JOIN b_iblock_element_prop_s42 ON b_iblock_element.ID = b_iblock_element_prop_s42.IBLOCK_ELEMENT_ID WHERE (b_crm_deal.STAGE_ID = 'PROPOSAL' or b_crm_deal.STAGE_ID = '8' or b_crm_deal.STAGE_ID = 'C4:1' or b_crm_deal.STAGE_ID = 'C4:PROPOSAL')";
    $rsQuery .= " ORDER BY b_crm_deal.ID DESC";
    $rsData = $DB->Query($rsQuery);
    $count = $rsData->SelectedRowsCount();
    $pages = ($count % $rows)?intval($count/$rows)+1:$count/$rows;
    for ($i=1;$i<=$pages;$i++){//Цикл по страницам
  ?>
  <div class="page<?=($i == 1)?" active":""?>" id="page<?=$i?>">
    <table>
      <tr>
        <th>id</th>
        <th>Название</th>
        <th>Цена, руб.</th>
        <th>Наименование  объекта</th>
        <th>N<sub>комнат</sub></th>
        <th>S<sub>общ.</sub></th>
        <th>S<sub>кухни</sub></th>
        <th>Этажность</th>
        <th>Ответственный</th>
      </tr>
      
  <?
      for ($j=1;$j<=$rows;$j++){//Цикл по строкам
        if ($aRes = $rsData->Fetch()){//Если есть значение
          $map_data['PLACEMARKS'][] = array('LAT' => $aRes['PROPERTY_298'],'LON' => $aRes['PROPERTY_299'],'TEXT' => '<a href="https://bpm.ucre.ru/crm/ro/?show&id='.$aRes['UF_CRM_1469534140'].'" target="_blank" title="'.$aRes['PROPERTY_209'].'">'.$aRes['NAME']."<br>".number_format($aRes['UF_CRM_58958B5734602'],2,"."," ").' &#8381;<br>'.$aRes['PROPERTY_221'].'/'.$aRes['PROPERTY_222'].' эт.</a>',);
          $assigned_user = CUser::GetByID($aRes['ASSIGNED_BY_ID'])->Fetch();
      ?>
      <tr>
        <td><?=$aRes['ID']?></td>
        <td style="text-align: left; padding-left: 5px;"><a href="/crm/deal/show/<?=$aRes['ID']?>/" target="_blank"><?=$aRes['TITLE']?></a></td>
        <td style="text-align: right; padding-right: 5px;"><?=($aRes['UF_CRM_58958B5734602'])?$aRes['UF_CRM_58958B5734602']:"-"?></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td><?=($aRes['PROPERTY_221'])?$aRes['PROPERTY_221']:"-"?>/<?=($aRes['PROPERTY_222'])?$aRes['PROPERTY_222']:"-"?></td>
        <td><a href="/company/personal/user/<?=$assigned_user['ID']?>/" target="_blank" title="<?=$assigned_user['PERSONAL_PHONE']?>" ><?=$assigned_user['LAST_NAME']." ".$assigned_user['NAME']?></a></td>
      </tr>
      <?
        }
      }
  ?>
      <tr><td></td><td colspan="8" style="text-align: left; padding-left: 5px;">Всего: <?=$count?></td></tr>
    </table>
  </div>
  <?
    }
  ?>
  <div class="pages">
    <center>
  <?
    for ($i=1;$i<=$pages;$i++){//Цикл по страницам для номеров страниц
      echo "<span class='pages".(($i == 1)?" active":"")."' onclick='set_active(this)'>".$i."</span>&nbsp;";
    }
  ?>
    </center>
  </div>
  <?
    
    echo $count."=".$pages." по ".$rows;
  }
  ?>
</div>
<div id="search_map" style="display: none;">
  <?
  $APPLICATION->IncludeComponent(
    "bitrix:map.yandex.view",
    "",
    array("INIT_MAP_TYPE" => "PUBLIC",
          "DEV_MODE" => "Y",
          "MAP_DATA" => serialize($map_data),
          "MAP_WIDTH" => "auto",
          "MAP_HEIGHT" => "500",
          "CONTROLS" => array("ZOOM", "SMALLZOOM", "SCALELINE"),
          "OPTIONS" => array("ENABLE_SCROLL_ZOOM", "ENABLE_DBLCLICK_ZOOM", "ENABLE_DRAGGING"),
          "MAP_ID" => "yam_1"
         )
  );
  ?>
</div>
<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");
?>
<script type="text/javascript">
  function set_goal(object){
    if(object.value == 'buy'){
      buy.style.display = 'block';
      sell.style.display = 'none';
      document.title = 'Подбор заявок на покупку';
      pagetitle.innerHTML = 'Подбор заявок на покупку';
    }else if (object.value == 'sell'){
      buy.style.display = 'none';
      sell.style.display = 'block';
      document.title = 'Подбор заявок на продажу';
      pagetitle.innerHTML = 'Подбор заявок на продажу';
    }
  }
  function toggle(el) {
    el.style.display = (el.style.display == 'none') ? 'block' : 'none'
  }
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
</script>
