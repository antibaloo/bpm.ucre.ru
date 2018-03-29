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
    )
  ),
  false
);
?>