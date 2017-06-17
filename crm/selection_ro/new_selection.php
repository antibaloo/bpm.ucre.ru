<link href="custom.css?<?=time();?>" rel="stylesheet">
<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
use \Bitrix\Crm\Category\DealCategory;
$APPLICATION->SetTitle("Подбор заявок на продажу");
//-Список сотрудников, включая уволеных-//
$rsUsers = CUser::GetList(($by="id"), ($order="asc"), array("GROUPS_ID"=> array(12))); // выбираем пользователей
//--------------------------------------//
?>
  <div id="search_form">
    <form id="formid" action="" title="" method="post">
      Ищем заявку на <b>продажу</b>,
      <input name="goal" type="hidden" value="sell"> Рынок поиска
      <select name="market" required>
      <option value="Любой" <?=($_POST['market'] =='Любой'? 'selected':'')?>>Любой</option>
      <option value="Первичный" <?=($_POST['market'] =='Первичный'? 'selected':'')?>>Первичный</option>
      <option value="Вторичный" <?=($_POST['market'] =='Вторичный'? 'selected':'')?>>Вторичный</option>
    </select> Ответственный
      <select id="assigned" name="assigned">
      <option value="" <?=($_POST['assigned'] ==''? 'selected':'')?>>(выберите ответственного)</option>
      <?
              while($arUser = $rsUsers->Fetch()) {
                if ($arUser['ACTIVE']=="Y"){
      ?>
      <option value="<?=$arUser['ID']?>" <?=($_POST['assigned'] == $arUser['ID']? 'selected':'')?>><?=$arUser['LAST_NAME'].' '.$arUser['NAME']?></option>
      <?
                }else{
                  if ($_POST['withfired']){
      ?>
      <option value="<?=$arUser['ID']?>" <?=($_POST['assigned'] == $arUser['ID']? 'selected':'')?>><?=$arUser['LAST_NAME'].' '.$arUser['NAME']."(уволен)"?></option>
      <?
                  }else {
      ?>
      <option style ="display:none;" value="<?=$arUser['ID']?>" <?=($_POST['assigned'] == $arUser['ID']? 'selected':'')?>><?=$arUser['LAST_NAME'].' '.$arUser['NAME']."(уволен)"?></option>
      <?
                    
                  }
                }
              }
      ?>
    </select>
      <input name="withfired" type="checkbox" <?=($_POST[ 'withfired'])? "checked": ""?> onchange="toggle_fired(this)"> с уволенными
      <hr> Тэги (через запятую) <input name="tags" type="text" size="100" value="<?=$_POST['tags']?>">
      <div id="sell" <?=($_POST[ 'goal']=='buy' ? 'style="display:none"': '')?>>
        <fieldset>
          <legend>
            Параметры заявки на продажу
          </legend>
          <select name="stage_id">
          <option value="А+П" <?=($_POST['stage_id']=='А+П' || !$_POST['stage_id'])?'selected':''?>>А+П</option>
          <option value="Активные" <?=($_POST['stage_id']=='Активные')?'selected':''?>>Активные</option>
          <option value="Предложения" <?=($_POST['stage_id']=='Предложения')?'selected':''?>>Предложения</option>
        </select>
          <select name="type_s">
          <option value="" <?=($_POST['type_s'] ==''? 'selected':'')?>>тип объекта</option>
          <option value="381" <?=($_POST['type_s'] =='381'? 'selected':'')?>>комната</option>
          <option value="382" <?=($_POST['type_s'] =='382'? 'selected':'')?>>квартира</option>
          <option value="383" <?=($_POST['type_s'] =='383'? 'selected':'')?>>дом</option>
          <option value="384" <?=($_POST['type_s'] =='384'? 'selected':'')?>>таунхаус</option>
          <option value="385" <?=($_POST['type_s'] =='385'? 'selected':'')?>>дача</option>
          <option value="386" <?=($_POST['type_s'] =='386'? 'selected':'')?>>участок</option>
          <option value="387" <?=($_POST['type_s'] =='387'? 'selected':'')?>>коммерческий</option>
        </select> , цена от <input name="price_min" type="number" min="0" step="100000" style="width: 7em;" value="<?=$_POST['price_min']?>"> до <input name="price_max" type="number" min="0" step="100000" style="width: 7em;" value="<?=$_POST['price_max']?>">          , комнат
          <select name="rooms_rule">
          <option value=">=" <?=($_POST['rooms_rule'] =='>='? 'selected':'')?>>от</option>
          <option value="=" <?=($_POST['rooms_rule'] =='='? 'selected':'')?>>=</option>
          <option value="<=" <?=($_POST['rooms_rule'] =='<='? 'selected':'')?>>до</option>
        </select>
          <input type="number" name="rooms_s" min="1" value="<?=$_POST['rooms_s']?>" style="width: 3em;">,
          <hr> S <sub>общ.</sub> от <input type="number" min="10" name="square_s" style="width: 4em;" value="<?=$_POST['square_s']?>">, S <sub>кух.</sub> от <input type="number" min="1" name="kitchen_s" style="width: 4em;" value="<?=$_POST['kitchen_s']?>">,
          не 1-й<input type="checkbox" name="nfirst" <?=($_POST[ 'nfirst'])? "checked": ""?>>, не последний <input type="checkbox" name="nlast" <?=($_POST[ 'nlast'])? "checked": ""?>>, район <input type="text" name="locality" size="40" value="<?=$_POST['locality']?>">,
          улица <input type="text" name="street" size="40" value="<?=$_POST['street']?>">
        </fieldset>
      </div>
      <br><input type="submit" id="submitButton" name="submitButton" value="Найти">
      <!--<input type="button" name="reset_form" value="Сброс параметров" onclick="reset_form();">-->
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
    if ($_POST['goal']=='sell') {
  $rsQuery = "SELECT b_crm_deal.ID,b_crm_deal.CATEGORY_ID, b_crm_deal.TITLE, b_crm_deal.ASSIGNED_BY_ID, b_uts_crm_deal.UF_CRM_1469534140,b_uts_crm_deal.UF_CRM_58958B5734602, b_iblock_element.NAME, b_iblock_element_prop_s42.PROPERTY_210, b_iblock_element_prop_s42.PROPERTY_216, b_iblock_element_prop_s42.PROPERTY_209, b_iblock_element_prop_s42.PROPERTY_229, b_iblock_element_prop_s42.PROPERTY_224, b_iblock_element_prop_s42.PROPERTY_226, b_iblock_element_prop_s42.PROPERTY_221, b_iblock_element_prop_s42.PROPERTY_222, b_iblock_element_prop_s42.PROPERTY_292, b_iblock_element_prop_s42.PROPERTY_298, b_iblock_element_prop_s42.PROPERTY_299, b_iblock_element_prop_s42.PROPERTY_301 FROM b_crm_deal INNER JOIN b_uts_crm_deal ON b_crm_deal.ID=b_uts_crm_deal.VALUE_ID INNER JOIN b_iblock_element ON b_uts_crm_deal.UF_CRM_1469534140 = b_iblock_element.ID INNER JOIN b_iblock_element_prop_s42 ON b_iblock_element.ID = b_iblock_element_prop_s42.IBLOCK_ELEMENT_ID WHERE";
    //Фильтр по стадии заявки
    if ($_POST['stage_id'] == 'А+П') {
      $rsQuery.=" (b_crm_deal.STAGE_ID = 'PROPOSAL' or b_crm_deal.STAGE_ID = '8' or b_crm_deal.STAGE_ID = 'C4:1' or b_crm_deal.STAGE_ID = 'C4:PROPOSAL')";
    }elseif($_POST['stage_id'] == 'Активные'){
      $rsQuery.=" (b_crm_deal.STAGE_ID = 'PROPOSAL' or b_crm_deal.STAGE_ID = 'C4:1')";
    }elseif ($_POST['stage_id'] == 'Предложения'){
      $rsQuery.=" (b_crm_deal.STAGE_ID = '8' or b_crm_deal.STAGE_ID = 'C4:PROPOSAL')";
    }
    //Фильтр по рынку
    if ($_POST['market'] == "Первичный") $rsQuery.=" AND CATEGORY_ID=4";
    if ($_POST['market'] == "Вторичный") $rsQuery.=" AND CATEGORY_ID=0";
    //Фильтр по типу недвижимости
    if ($_POST['type_s'] != "") $rsQuery.=" AND PROPERTY_210=".$_POST['type_s'];
    //Фильтр по ценам
    if ($_POST['price_min'] > 0) $rsQuery.=" AND UF_CRM_58958B5734602>=".$_POST['price_min'];
    if ($_POST['price_max'] > 0) $rsQuery.=" AND UF_CRM_58958B5734602<=".$_POST['price_max'];
    //Фильтр по комнатам
    if ($_POST['rooms_s'] > 0) $rsQuery.=" AND PROPERTY_229".$_POST['rooms_rule'].$_POST['rooms_s'];
    //Фильтр по общей площади
    if ($_POST['square_s'] > 0) $rsQuery.=" AND PROPERTY_224>=".$_POST['square_s'];
    //Фильтр по площади кухни
    if ($_POST['kitchen_s'] > 0) $rsQuery.=" AND PROPERTY_226>=".$_POST['kitchen_s'];
    //Не первый
    if ($_POST['nfirst']) $rsQuery.=" AND PROPERTY_221<>1";
    //Не последний
    if ($_POST['nlast']) $rsQuery.=" AND PROPERTY_221<>PROPERTY_222";
    //Фильтр по району н.п.
    if ($_POST['locality']) $rsQuery.=" AND PROPERTY_216 LIKE '%".$_POST['locality']."%'";
    //Фильтр по улице
    if ($_POST['street']) $rsQuery.=" AND PROPERTY_217 LIKE '%".$_POST['street']."%'";
    //Фильтр по ответственному
    if ($_POST['assigned'] !='') $rsQuery.=" AND ASSIGNED_BY_ID=".$_POST['assigned'] ;
    //Фильтр по тэгам
    if ($_POST['tags']){
      $tags = explode(",",$_POST["tags"]);
      $rsQuery.= " AND (";
      foreach ($tags as $key=>$tag){
        $rsQuery.= "UF_CRM_1494396942 LIKE '%".trim($tag)."%'";
        if ($key != count($tags)-1) $rsQuery.= " OR ";
      }
      $rsQuery.= ")";
    }
    $rsQuery .= " ORDER BY b_uts_crm_deal.UF_CRM_58958B5734602 ASC";
    $rsData = $DB->Query($rsQuery);
    $count = $rsData->SelectedRowsCount();
    $pages = ($count % $rows)?intval($count/$rows)+1:$count/$rows;
    if ($count == 0) echo "<h2>По заданным параметрам ничего не найдено!</h2>";
    for ($i=1;$i<=$pages;$i++){//Цикл по страницам
  ?>
      <div class="page<?=($i == 1)?" active ":" "?>" id="page<?=$i?>">
        <table>
          <tr>
            <th>id</th>
            <th>Название</th>
            <th>Цена, руб.</th>
            <th>Наименование объекта</th>
            <th title="Количество комнат">N<sub>к</sub></th>
            <th>S<sub>общ.</sub></th>
            <th>S<sub>кухни</sub></th>
            <th>Этажность</th>
            <th>Ответственный</th>
          </tr>
          <?
      for ($j=1;$j<=$rows;$j++){//Цикл по строкам
        if ($aRes = $rsData->Fetch()){//Если есть значение
          $assigned_user = CUser::GetByID($aRes['ASSIGNED_BY_ID'])->Fetch();
          $map_data['PLACEMARKS'][] = array('LAT' => $aRes['PROPERTY_298'],'LON' => $aRes['PROPERTY_299'],'TEXT' => '<a href="/crm/deal/show/'.$aRes['ID'].'/" target="_blank" title="'.$aRes['PROPERTY_209'].'">'.$aRes['TITLE']."<br>".number_format($aRes['UF_CRM_58958B5734602'],2,"."," ").' &#8381;<br>'.$aRes['PROPERTY_221'].'/'.$aRes['PROPERTY_222'].' эт.<br>'.$assigned_user['LAST_NAME'].' '.$assigned_user['NAME'].'</a>',);
          
          if ($aRes['PROPERTY_210'] == 386) $square = $aRes['PROPERTY_292'];
          else $square = $aRes['PROPERTY_224'];
      ?>
            <tr id="<?=$aRes['ID']?>" onclick="select_row(<?=$aRes['ID']?>);">
              <td>
                <?=$aRes['ID']?>
              </td>
              <td style="text-align: left; padding-left: 5px;">
                <a href="/crm/deal/show/<?=$aRes['ID']?>/" target="_blank">
                  <?=$aRes['TITLE']?>
                </a>
              </td>
              <td style="text-align: right; padding-right: 5px;">
                <?=($aRes['UF_CRM_58958B5734602'])?$aRes['UF_CRM_58958B5734602']:"-"?>
              </td>
              <td style="text-align: left; padding-left: 5px;">
                <a href="<?=$aRes['PROPERTY_301']?>" target="_blank">
                  <?=$aRes['NAME']?>
                </a>
              </td>
              <!--<td style="text-align: left; padding-left: 5px;"><a href="/crm/ro/?show&id=<?=$aRes['UF_CRM_1469534140']?>" target="_blank"><?=$aRes['NAME']?></a></td>-->
              <td>
                <?=($aRes['PROPERTY_229'])?intval($aRes['PROPERTY_229']):"-"?>
              </td>
              <td style="text-align: right; padding-right: 5px;">
                <?=($square)?number_format($square,2):"-"?>
              </td>
              <td style="text-align: right; padding-right: 5px;">
                <?=($aRes['PROPERTY_226'])?number_format($aRes['PROPERTY_226'],2):"-"?>
              </td>
              <td>
                <?=($aRes['PROPERTY_221'])?$aRes['PROPERTY_221']:"-"?>/
                  <?=($aRes['PROPERTY_222'])?$aRes['PROPERTY_222']:"-"?>
              </td>
              <td>
                <a href="/company/personal/user/<?=$assigned_user['ID']?>/" target="_blank" title="<?=$assigned_user['PERSONAL_PHONE']?>">
                  <?=$assigned_user['LAST_NAME']." ".$assigned_user['NAME']?>
                </a>
              </td>
            </tr>
            <?
        }
      }
  ?>
              <tr>
                <td></td>
                <td colspan="8" style="text-align: left; padding-left: 5px;">Всего:
                  <?=$count?>
                </td>
              </tr>
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
    }
  $arUser =$USER->GetById($USER->GetID())->Fetch();
  ?>
    <hr>
    <div class="page active" id="basket">
      <table id="basketTable">

        <tbody id="basketTableBody">
          <tr>
            <th rowspan="2">id</th>
            <th rowspan="2">Резюме объекта недвижимости</th>
            <th rowspan="2">Цена, руб.</th>
            <th colspan="5">Оценки по параметрам (+/-)</th>
            <th rowspan="2">Итог</th>
            <th rowspan="2">Комментарий</th>
          </tr>
          <tr>
            <th>Цена</th>
            <th>Объект</th>
            <th>Подъезд</th>
            <th>Двор</th>
            <th>Инф-ра</th>
          </tr>
          <tr id="lastRow">
            <td></td>
            <td colspan="9" style="text-align: left; padding-left: 5px;">Всего: <span id="count">0</span></td>
          </tr>
        </tbody>
      </table>
    </div>
</div>
<div id="search_map" style="display: none;">
    <?
  $APPLICATION->IncludeComponent(
    "bitrix:map.yandex.view",
    "",
    array("INIT_MAP_TYPE" => "PUBLIC",
          "DEV_MODE" => "Y",
          "MAP_DATA" => serialize($map_data),
          "MAP_WIDTH" => "100%",
          "MAP_HEIGHT" => "100%",
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
  $('.basket_row').click(function(){
    alert(this.attr('id'));
    //$('#count').text($('.basket_row').length);
  })
  function remove_row(row_id){
    if(confirm("Вы действительно хотите удалить эту заявку из подбора?")){
      //alert(row_id.getAttribute("id"));
      $("#"+row_id.getAttribute("id")).remove();
      //var tableBody = document.getElementById('basketTableBody');
      //var del_row = document.getElementById(row_id);
      //tableBody.removeChild(del_row);
      $('#count').text($('.basket_row').length);
    }
  }
  function select_row(id) {
    if (!document.getElementById('s'+id)){
      var tableBody = document.getElementById('basketTableBody');
      var last = document.getElementById('lastRow');
      var tr = document.createElement('tr');
      tr.setAttribute('id', 's' + id);
      tr.classList.add("basket_row");
      tr.setAttribute('onclick', 'remove_row(s'+id+')');
      tr.innerHTML = '<td>' + id + '</td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td>';
      tableBody.insertBefore(tr, last);
      $('#count').text($('.basket_row').length);
    }
  }
  function toggle(el) {
    el.style.display = (el.style.display == 'none') ? 'block' : 'none'
  }

      function set_active(object) {
        if (!object.classList.contains('active')) {
          var el = document.getElementById("page" + object.innerHTML);
          var a_page = document.getElementsByClassName("page active");
          var a_pages = document.getElementsByClassName("pages active");
          a_page[0].classList.remove('active');
          a_pages[0].classList.remove('active');
          el.classList.add('active');
          object.classList.add('active');
        }
      }

      function toggle_fired(object) {
        if (object.checked) {
          $('#assigned option').filter(function() {
            var str = $(this).html();
            return !(str.indexOf("уволен") == -1);
          }).show();
        } else {
          $('#assigned option').filter(function() {
            var str = $(this).html();
            return !(str.indexOf("уволен") == -1);
          }).hide();
        }
      }
    </script>