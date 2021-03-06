<link href="custom.css?<?=time();?>" rel="stylesheet">
<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
use \Bitrix\Crm\Category\DealCategory;
$APPLICATION->SetTitle("Подбор заявок");
if (!in_array($USER->GetID(),array(1,24)) ){
?>
<center>
  <h1>Раздел закрыт!</h1>
  <h2>Пользуётесь "Встречными заявками"!</h2>
  <hr>
  <img src="./closed.jpg">
</center>

<?
goto footer;
}
//-Список сотрудников, включая уволеных-//
$rsUsers = CUser::GetList(($by="id"), ($order="asc"), array("GROUPS_ID"=> array(12))); // выбираем пользователей
//--------------------------------------//
?>
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
    Ответственный
    <select id ="assigned" name="assigned">
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
    <input name="withfired" type="checkbox" <?=($_POST['withfired'])?"checked":""?> onchange="toggle_fired(this)"> с уволенными
    <hr> Тэги (через запятую) <input name="tags" type="text" size="100" value="<?=$_POST['tags']?>">
    <div id="buy" <?=($_POST['goal']!='buy'? 'style="display:none"':'')?>>
      <fieldset>
        <legend>
          Параметры заявки на покупку
        </legend>
        <select name="type">
          <option value="" <?=($_POST['type'] ==''? 'selected':'')?>>тип объекта</option>
          <option value="1" <?=($_POST['type'] =='1'? 'selected':'')?>>комната</option>
          <option value="2" <?=($_POST['type'] =='2'? 'selected':'')?>>квартира</option>
          <option value="3" <?=($_POST['type'] =='3'? 'selected':'')?>>дом</option>
          <option value="4" <?=($_POST['type'] =='4'? 'selected':'')?>>таунхаус</option>
          <option value="5" <?=($_POST['type'] =='5'? 'selected':'')?>>дача</option>
          <option value="6" <?=($_POST['type'] =='6'? 'selected':'')?>>участок</option>
          <option value="7" <?=($_POST['type'] =='7'? 'selected':'')?>>коммерческий</option>
        </select>
        &nbsp;Комнат:&nbsp;
        <input type="number" name="rooms" min="1" style="width: 4em;" value="<?=$_POST['rooms']?>">
        &nbsp;S<sub>общ.</sub>:&nbsp;
        <input type="number" name="square" min="10" style="width: 4em;" value="<?=$_POST['square']?>">
        &nbsp;S<sub>кух.</sub>:&nbsp;
        <input type="number" name="kitchen" min="1" style="width: 4em;" value="<?=$_POST['kitchen']?>">
        &nbsp;Этаж/Этажей&nbsp;
        <input type="number" name="floor" min="1" style="width: 4em;" value="<?=$_POST['floor']?>">/<input type="number" name="floors" min="1" style="width: 4em;" value="<?=$_POST['floors']?>">
        &nbsp;Цена:&nbsp;
        <input type="number" name="price" min="100000" style="width: 7em;" value="<?=$_POST['price']?>">
      </fieldset>
    </div>
    <div id="sell" <?=($_POST['goal']=='buy'? 'style="display:none"':'')?>>
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
        </select>
        , цена от <input name="price_min" type="number" min="0" step="100000" style="width: 7em;" value="<?=$_POST['price_min']?>"> до <input name="price_max" type="number" min="0" step="100000" style="width: 7em;" value="<?=$_POST['price_max']?>">
        , комнат  
        <select name="rooms_rule">
          <option value=">=" <?=($_POST['rooms_rule'] =='>='? 'selected':'')?>>от</option>
          <option value="=" <?=($_POST['rooms_rule'] =='='? 'selected':'')?>>=</option>
          <option value="<=" <?=($_POST['rooms_rule'] =='<='? 'selected':'')?>>до</option>
        </select>
        <input type="number" name="rooms_s" min="1" value="<?=$_POST['rooms_s']?>" style="width: 3em;">,<hr>
        S <sub>общ.</sub> от <input type="number" min="10" name="square_s" style="width: 4em;" value="<?=$_POST['square_s']?>">,
        S <sub>кух.</sub> от <input type="number" min="1" name="kitchen_s" style="width: 4em;" value="<?=$_POST['kitchen_s']?>">,
        не 1-й<input type="checkbox" name="nfirst" <?=($_POST['nfirst'])?"checked":""?>>, 
        не последний <input type="checkbox" name="nlast" <?=($_POST['nlast'])?"checked":""?>>,
        район <input type="text" name="locality" size="40" value="<?=$_POST['locality']?>">, 
        улица <input type="text" name="street" size="40" value="<?=$_POST['street']?>">
      </fieldset>
    </div>
    <br><input type="submit" id="submitButton"  name="submitButton" value="Найти">
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
  
  if ($_POST['goal']=='buy') {
    
    $rsQuery = "SELECT b_crm_deal.ID, b_crm_deal.TITLE, b_crm_deal.ASSIGNED_BY_ID, b_uts_crm_deal.UF_CRM_5895BC940ED3F,b_uts_crm_deal.UF_CRM_58CFC7CDAAB96,b_uts_crm_deal.UF_CRM_58958B529E628,b_uts_crm_deal.UF_CRM_58958B52BA439,b_uts_crm_deal.UF_CRM_58958B52F2BAC,b_uts_crm_deal.UF_CRM_58958B51B667E, b_uts_crm_deal.UF_CRM_58958B576448C, b_uts_crm_deal.UF_CRM_58958B5751841 FROM b_crm_deal INNER JOIN b_uts_crm_deal ON b_crm_deal.ID=b_uts_crm_deal.VALUE_ID WHERE b_crm_deal.CATEGORY_ID = 2 AND b_crm_deal.STAGE_ID = 'C2:PROPOSAL'";
    //Фильтр по рынку
    if ($_POST['market'] == "Первичный") $rsQuery.=" AND UF_CRM_5895BC940ED3F LIKE '%828%'";
    if ($_POST['market'] == "Вторичный") $rsQuery.=" AND UF_CRM_5895BC940ED3F LIKE '%827%'";
    //Фильтр по типу недвижимости
    if ($_POST['type'] !='') $rsQuery.=" AND UF_CRM_58CFC7CDAAB96=".$_POST['type'] ;
    //Фильтр по количеству комнат
    if ($_POST['rooms'] > 0) $rsQuery.=" AND UF_CRM_58958B529E628<=".$_POST['rooms'];
    //Фильтр по общей площади
    if ($_POST['square'] > 0) $rsQuery.=" AND UF_CRM_58958B52BA439<=".$_POST['square']." AND UF_CRM_58958B52BA439<>0";
    //Фильтр по площади кухни
    if ($_POST['kitchen'] > 0) $rsQuery.=" AND UF_CRM_58958B52F2BAC<=".$_POST['kitchen']." AND UF_CRM_58958B52F2BAC<>0";
    //Не первый этаж
    if ($_POST['floor'] == 1) $rsQuery.=" AND UF_CRM_58958B51B667E NOT LIKE '%754%'";
    //Не последний этаж
    if ($_POST['floor'] > 0 && $_POST['floors']>0 && $_POST['floor'] == $_POST['floors']) $rsQuery.=" AND UF_CRM_58958B51B667E NOT LIKE '%755%'";
    //Фильтр по цене
    if ($_POST['price'] > 0) $rsQuery.=" AND UF_CRM_58958B576448C<=".$_POST['price']." AND UF_CRM_58958B5751841>=".$_POST['price'];
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
    $rsQuery .= " ORDER BY b_crm_deal.ID DESC";
    $rsData = $DB->Query($rsQuery);
    $count = $rsData->SelectedRowsCount();
    $pages = ($count % $rows)?intval($count/$rows)+1:$count/$rows;
    if ($count == 0) echo "<h2>По заданным параметрам ничего не найдено!</h2>";
    for ($i=1;$i<=$pages;$i++){//Цикл по страницам
  ?>
  <div class="page<?=($i == 1)?" active":""?>" id="page<?=$i?>">
    <table>
      <tr>
        <th>id</th>
        <th>Название</th>
        <th title="Первичный/Вторичный">Рынок</th>
        <th>Тип объекта</th>
        <th title="Количество комнат от ">N<sub>к</sub> от</th>
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
          $market = unserialize($aRes['UF_CRM_5895BC940ED3F']);
          $type = array(
            ''    =>  "-",
            '1' =>  "комната",
            '2' =>  "квартира",
            '3' =>  "дом",
            '4' =>  "таунхаус",
            '5' =>  "дача",
            '6' =>  "участок",
            '7' =>  "коммерческий"
          );
          $floors = unserialize($aRes['UF_CRM_58958B51B667E']);
      ?>
      <tr>
        <td><?=$aRes['ID']?></td>
        <td style="text-align: left; padding-left: 10px;"><a href="/crm/deal/show/<?=$aRes['ID']?>/" target="_blank"><?=$aRes['TITLE']?></a></td>
        <td><?=(in_array(828, $market))?"П":"-"?>/<?=(in_array(827, $market))?"В":"-"?></td>
        <td><?=$type[$aRes['UF_CRM_58CFC7CDAAB96']]?></td>
        <td><?=($aRes['UF_CRM_58958B529E628'])?$aRes['UF_CRM_58958B529E628']:"-"?></td>
        <td style="text-align: right; padding-right: 5px;"><?=($aRes['UF_CRM_58958B52BA439'])?$aRes['UF_CRM_58958B52BA439']:"-"?></td>
        <td style="text-align: right; padding-right: 5px;"><?=($aRes['UF_CRM_58958B52F2BAC'])?$aRes['UF_CRM_58958B52F2BAC']:"-"?></td>
        <td><?=(in_array(754, $floors))?"<span title='не первый'><s>&#8595;</s></span>":""?><?=(count($floors) == 2)?"/":""?><?=(in_array(755, $floors))?"<span title='не последний'><s>&#8593;</s></span>":""?></td>
        <td style="text-align: right; padding-right: 5px;"><?=($aRes['UF_CRM_58958B576448C'])?$aRes['UF_CRM_58958B576448C']:"-"?></td>
        <td style="text-align: right; padding-right: 5px;"><?=($aRes['UF_CRM_58958B5751841'])?$aRes['UF_CRM_58958B5751841']:"-"?></td>
        <td><a href="/company/personal/user/<?=$assigned_user['ID']?>/" target="_blank" title="<?=$assigned_user['PERSONAL_PHONE']?>" ><?=$assigned_user['LAST_NAME']." ".$assigned_user['NAME']?></a></td>
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
  <!--<a href="toprint.php?sql=<?=htmlspecialchars(serialize($_POST),ENT_QUOTES)?>" target="_blank">Версия для печати</a>-->
  <?
//    echo $count."=".$pages." по ".$rows;
  }
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
  <div class="page<?=($i == 1)?" active":""?>" id="page<?=$i?>">
    <table>
      <tr>
        <th>id</th>
        <th>Название</th>
        <th>Цена, руб.</th>
        <th>Наименование  объекта</th>
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
      <tr>
        <td><?=$aRes['ID']?></td>
        <td style="text-align: left; padding-left: 5px;"><a href="/crm/deal/show/<?=$aRes['ID']?>/" target="_blank"><?=$aRes['TITLE']?></a></td>
        <td style="text-align: right; padding-right: 5px;"><?=($aRes['UF_CRM_58958B5734602'])?$aRes['UF_CRM_58958B5734602']:"-"?></td>
        <td style="text-align: left; padding-left: 5px;"><a href="<?=$aRes['PROPERTY_301']?>" target="_blank"><?=$aRes['NAME']?></a></td>
        <!--<td style="text-align: left; padding-left: 5px;"><a href="/crm/ro/?show&id=<?=$aRes['UF_CRM_1469534140']?>" target="_blank"><?=$aRes['NAME']?></a></td>-->
        <td><?=($aRes['PROPERTY_229'])?intval($aRes['PROPERTY_229']):"-"?></td>
        <td style="text-align: right; padding-right: 5px;"><?=($square)?number_format($square,2):"-"?></td>
        <td style="text-align: right; padding-right: 5px;"><?=($aRes['PROPERTY_226'])?number_format($aRes['PROPERTY_226'],2):"-"?></td>
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
  }
  $arUser =$USER->GetById($USER->GetID())->Fetch();
  ?>
  <form id="formaddress">
    <input type="hidden" name="sql" value="<?=htmlspecialchars(serialize($_POST),ENT_QUOTES)?>">
    <input type="email" name="email" readonly="" value="<?=$arUser["EMAIL"]?>"> <input type="button" id="sendmail" value="Отправить">&nbsp;<div style="display: inline" id="sendresult"></div>
    <?if($_POST['goal']=='sell'){?>
    <br>
    <br>
    <a target="_blank" href="toprintpdf.php?sql=<?=htmlspecialchars(serialize($_POST),ENT_QUOTES)?>">Печатная форма подбора</a>
    <!--<br>
    <input type="button" id="printform" value="Печатная форма">&nbsp;<div style="display: inline" id="printresult"></div>
    <br>
    <input type="button" id="printformnew" value="Печатная форма (в новом окне)">-->
    <?}?>
  </form>
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
footer:
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");
?>
<script type="text/javascript">
  function set_goal(object){
    if(object.value == 'buy'){
      buy.style.display = 'block';
      sell.style.display = 'none';
    }else if (object.value == 'sell'){
      buy.style.display = 'none';
      sell.style.display = 'block';
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
  function toggle_fired(object){
    if (object.checked){
      $('#assigned option').filter(function () { 
        var str = $(this).html();
        return !(str.indexOf("уволен") == -1); 
      }).show();
    }else{
      $('#assigned option').filter(function () { 
        var str = $(this).html();
        return !(str.indexOf("уволен") == -1); 
      }).hide();     
    }
  }
  $(document).ready(function() {
    $('#sendmail').on('click', function () {
      var data = $('#formaddress').serialize();
      $.ajax({
        type: "POST",
        url: "./sendresult.php",
        dataType: "text",
        data: data,
        success: function (html) {
          $("#sendresult").html(html);
        },
        error: function (html) {
          $("#sendresult").html("Технические неполадки! В ближайшее время все будет исправлено!");
        },
      });
    });
  });
  $(document).ready(function() {
    $('#printform').on('click', function () {
      var data = $('#formaddress').serialize();
      $.ajax({
        type: "POST",
        url: "./toprint.php",
        dataType: "text",
        data: data,
        success: function (html) {
          $("#printresult").html(html);
        },
        error: function (html) {
          $("#printresult").html("Технические неполадки! В ближайшее время все будет исправлено!");
        },
      });
    });
  })
  $(document).ready(function() {
    $('#printformnew').on('click', function () {
      var a = window.open('toprintpdf.php?sql=<?=serialize($_POST)?>', "_blank", ""); a.blur();
    });
  })
</script>