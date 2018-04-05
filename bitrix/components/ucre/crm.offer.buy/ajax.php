<?require_once($_SERVER['DOCUMENT_ROOT'] . "/bitrix/modules/main/include/prolog_before.php");?>
<?
$APPLICATION->IncludeComponent(
  "ucre:crm.offer.buy",
  "",
  array(
    'OFFER_AJAX_ID' => $_POST['OFFER_AJAX_ID'],
    'PARAMS' => array(
      'UF_CRM_58CFC7CDAAB96'  => $_POST['UF_CRM_58CFC7CDAAB96'],  //Тип недвижимости
      'UF_CRM_5895BC940ED3F'  => $_POST['UF_CRM_5895BC940ED3F'],  //Рынок поиска
      'UF_CRM_58958B529E628'  => $_POST['UF_CRM_58958B529E628'],  //Кол-во комнат 
      'UF_CRM_58958B5207D0C'  => $_POST['UF_CRM_58958B5207D0C'],  //Тип дома
      "UF_CRM_58958B52BA439"  => $_POST['UF_CRM_58958B52BA439'],  //Общая площадь не менее
      "UF_CRM_58958B52F2BAC"  => $_POST['UF_CRM_58958B52F2BAC'],  //Площадь кухни не менее
      "UF_CRM_1506501917"     => $_POST['UF_CRM_1506501917'],     //Этаж от
      "UF_CRM_1506501950"     => $_POST['UF_CRM_1506501950'],     //Этаж до
      "UF_CRM_1521541289"     => $_POST['UF_CRM_1521541289'],     //Не последний
      "UF_CRM_1522901904"     => $_POST['UF_CRM_1522901904'],     //Этажность от
      "UF_CRM_1522901921"     => $_POST['UF_CRM_1522901921'],     //Этажность до
      "UF_CRM_58958B532A119"  => $_POST['UF_CRM_58958B532A119'],  //Есть балкон
      "UF_CRM_58958B576448C"  => $_POST['UF_CRM_58958B576448C'],  //Цена от
      "UF_CRM_58958B5751841"  => $_POST['UF_CRM_58958B5751841'],  //Цена до
      "GEO_USE"               => $_POST['GEO_USE'],               //Учитывать облать поиска
      "GEO"                   => $_POST['GEO'],                   //Координаты полинома области поиска
      "ID"                    => $_POST['ID'],                    //Передаем ID, если он задан, для исключения уже отобранных в потенциальные
    )
  ),
  false
);
?>