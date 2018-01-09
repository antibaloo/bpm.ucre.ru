<link href="/include/custom_css/custom_paging.css?<?=time();?>" rel="stylesheet">
<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Очередь выгрузки на Авито");
?>
<p>Выгрузка на авито происходит 3 раза в день в <b>00:15, 8:15 и 16:15</b>. Формирование реальных пакетов происходит в <b>7:36, 15:35 и 23:35.</b></p>
<p>Т.е. если вы хотите внести изменения в уже выгружаемые заявки, то это нужно сделать до этого времени.</p>
<p>Попадание заявок на выгрузку происходит как в ручном режиме (за счет сотрудинка и с его контактными данными) через задачу поставленную на Абалакова А.С с Н.П. Молчановой в качестве наблюдателя (она сделает отметку об оплате), так и в автоматическом
  режиме.
</p>
<p>Автоматический отбор проводится 2 раза в сутки (<b>7:25 и 15:25</b>) по следующим критериям: 
<ul>
  <li>5 заявок на поиск покупателя в стадии "Агентский договор подписан" с непустой ценой, описанием, с фото и/или планировкой, не относящиеся к категории "земельные участки" или
    "коммерческая недвижимость". Сотрировка этой очереди проходит по убыванию даты изменения, т.е. новые и недавно отредактированные заявки находятся в начале очереди
  </li>
  <li>
    если потребность в 5 объявлений не была удовлетворена за счет договорных объектов, то производится аналогичный поиск среди заявок в статусе "Заявка на поиск покупателя подписана"
  </li>
</ul>
  <p>
    <b>Месячный лимит составляет 330 объектов. При текущем объеме потерянных лидов и заявок на покупку расширять этот пакет нецелесообразно.</b>
  </p>
  <div>
    <input id="deal_id"> <input id="stockSearch" type="button" value="Искать"> <label id="searchResult"></label>
  </div>
  <br>
  <?
$statuses = array(
  '1' => 'Агентский договор подписан',
  '13' => 'Заявка на поиск покупателя подписана'
);
$rsData = $DB->Query("select b_crm_deal.ID,b_crm_deal.TITLE, b_crm_deal.STAGE_ID, b_crm_deal.ASSIGNED_BY_ID, b_crm_deal.COMMENTS,b_uts_crm_deal.UF_CRM_58958B5734602, b_uts_crm_deal.UF_CRM_1472038962, b_uts_crm_deal.UF_CRM_1476517423,b_iblock_element.ID as ELEMENT_ID, b_iblock_element.CODE, b_iblock_element_prop_s42.PROPERTY_210, b_iblock_element_prop_s42.PROPERTY_300, b_iblock_element_prop_s42.PROPERTY_213, b_iblock_element_prop_s42.PROPERTY_214, b_iblock_element_prop_s42.PROPERTY_215,b_iblock_element_prop_s42.PROPERTY_216,b_iblock_element_prop_s42.PROPERTY_217,b_iblock_element_prop_s42.PROPERTY_218, b_iblock_element_prop_s42.PROPERTY_298, b_iblock_element_prop_s42.PROPERTY_299, b_iblock_element_prop_s42.PROPERTY_229, b_iblock_element_prop_s42.PROPERTY_228, b_iblock_element_prop_s42.PROPERTY_224, b_iblock_element_prop_s42.PROPERTY_292, b_iblock_element_prop_s42.PROPERTY_225, b_iblock_element_prop_s42.PROPERTY_226, b_iblock_element_prop_s42.PROPERTY_221, b_iblock_element_prop_s42.PROPERTY_222, b_iblock_element_prop_s42.PROPERTY_242, b_iblock_element_prop_s42.PROPERTY_243, b_iblock_element_prop_s42.PROPERTY_238, b_iblock_element_prop_s42.PROPERTY_295 from b_crm_deal LEFT JOIN b_uts_crm_deal ON b_crm_deal.ID = b_uts_crm_deal.VALUE_ID LEFT JOIN b_iblock_element ON b_uts_crm_deal.UF_CRM_1469534140 = b_iblock_element.ID LEFT JOIN b_iblock_element_prop_s42 ON b_uts_crm_deal.UF_CRM_1469534140 = b_iblock_element_prop_s42.IBLOCK_ELEMENT_ID where b_crm_deal.CATEGORY_ID = 0 and b_uts_crm_deal.UF_CRM_1469534140 <> '' and (b_crm_deal.STAGE_ID = '1' OR b_crm_deal.STAGE_ID = '13') AND b_uts_crm_deal.UF_CRM_1512621495<>1 AND (TIMESTAMP(b_iblock_element_prop_s42.PROPERTY_260) < NOW() OR b_iblock_element_prop_s42.PROPERTY_260 is null) AND b_crm_deal.COMMENTS<>'' AND b_uts_crm_deal.UF_CRM_58958B5734602 > 0 AND (b_uts_crm_deal.UF_CRM_1472038962<>'a:0:{}' OR b_uts_crm_deal.UF_CRM_1476517423 <> 'a:0:{}') AND b_iblock_element_prop_s42.PROPERTY_210<>387 AND b_iblock_element_prop_s42.PROPERTY_210<>386 ORDER BY b_crm_deal.STAGE_ID ASC,b_crm_deal.DATE_MODIFY DESC");
$count = $rsData->SelectedRowsCount();
$rows = 20;
$pages = ($count % $rows)?intval($count/$rows)+1:$count/$rows;
$n = 0;


for ($i=1;$i<=$pages;$i++){
?>
    <div class="page<?=($i == 1)?" active ":" "?>" id="page<?=$i?>">
      <table>
        <tr>
          <th width="5%">Очередь</th>
          <th width="5%">id</th>
          <th width="30%">Название заявки</th>
          <th width="5%">Статус</th>
          <th width="8%">Цена, руб.</th>
          <th width="15%">Ответственный</th>
        </tr>
        <?  
  for ($j=1;$j<=$rows;$j++){
    if ($aRes = $rsData->Fetch()){
      $assigned_user = CUser::GetByID($aRes['ASSIGNED_BY_ID'])->Fetch();
      $n++;
?>
          <tr class="row">
            <td id="R<?=$aRes['ID']?>">
              <?=$n?>
            </td>
            <td>
              <?=$aRes['ID']?>
            </td>
            <td style="text-align: left; padding-left: 5px;" title="<?=$aRes['TITLE']?>">
              <a href="/crm/deal/show/<?=$aRes['ID']?>/" target="_blank">
                <?=$aRes['TITLE']?>
              </a>
            </td>
            <td title="<?=$statuses[$aRes['STAGE_ID']]?>"><?=$statuses[$aRes['STAGE_ID']]?></td>
            <td style="text-align: right; padding-right: 5px;" title="<?=($aRes['UF_CRM_58958B5734602'])?number_format($aRes['UF_CRM_58958B5734602'],0," . "," "):"цена не указана "?>">
              <?=($aRes['UF_CRM_58958B5734602'])?number_format($aRes['UF_CRM_58958B5734602'],0,"."," "):"<span style='color:red;'>цена не указана</span>"?></td>
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
      </table>
    </div>
    <?                           
}
?>
      <table style="width:100%;border: 1px solid black;border-collapse: collapse;margin-bottom:15px;font-size: 14px;">
        <tr>
          <td style="border: 1px solid black;text-align:center;" width="4%"><b>Всего:</b></td>
          <td style="border: 1px solid black;text-align:left;" style="text-align: left; padding-left: 5px;"><b><span id="count"><?=$count?></span></b></td>
        </tr>
      </table>
      <div class="pages">
        <center>
          <?
  for ($i=1;$i<=$pages;$i++){//Цикл по страницам для номеров страниц
    echo "<span class='pages".(($i == 1)?" active":"")."' onclick='set_active(this)'>".$i."</span>&nbsp;";
  }
?>
        </center>
      </div>
      <?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>
        <script>
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
          $("#stockSearch").click(function() {
            var position = $("#R" + $("#deal_id").val()).html();
            if (position === undefined) $("#searchResult").html("Такая заявка не найдена!");
            else $("#searchResult").html("Искомая заявка находится на позиции <b>" + position + "</b> в поиске");
          });
        </script>