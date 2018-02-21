<?php
require_once($_SERVER['DOCUMENT_ROOT'] . "/bitrix/modules/main/include/prolog_before.php");
CModule::IncludeModule('crm');
if (strripos ($_SERVER['HTTP_REFERER'], 'bpm.ucre.ru')!==false){
  //echo "<pre>";print_r($_POST);echo "</pre>";
  $deal = new CCrmDeal;
  $entity = $deal->GetListEx(
    array(), 
    array("ID" => $_POST['id']), 
    false, 
    false, 
    array('UF_CRM_1472038962','UF_CRM_1476517423','UF_CRM_1513322128','UF_CRM_1472704376','UF_CRM_1512462544','UF_CRM_1512462594','ASSIGNED_BY_ID'),
    array()
  )->Fetch();
  
  foreach($entity as $fileldName=>$value){$entity[$fileldName] = array('VALUE' => $value);}//Приводим параметр к виду понятному вызываемому компоненту
  $entity['ID'] = $entity['ID']['VALUE'];//Микрокостыль, это поле должно выглядеть нормально
  $entity['ASSIGNED_BY_ID'] = $entity['ASSIGNED_BY_ID']['VALUE'];//Микрокостыль, это поле должно выглядеть нормально
  //echo "<pre>";print_r($entity);echo "</pre>";
  //ob_start();
  /*Общий компонент для загрузки и редактирования галереи изображений из обозначенных полей*/
  
  $APPLICATION->IncludeComponent(
    'ucre:gallery.upload',
    '',
    array('ENTITY' => $entity,
          'FIELDS' => array(
            'Фотографии' => 'UF_CRM_1472038962',
            'Планировки' => 'UF_CRM_1476517423',
            'Фото для внутреннего пользования' => 'UF_CRM_1513322128',
            'Документы по заявке' => 'UF_CRM_1472704376',
            'Подписанная заявка' => 'UF_CRM_1512462544',
            'Скан агентского договора' => 'UF_CRM_1512462594',
          )
         )
  );
  
  /*--------------------------------------------------------------------------*/
  //$html = ob_get_contents();
  //ob_end_clean();
  //echo $html;
}
?>