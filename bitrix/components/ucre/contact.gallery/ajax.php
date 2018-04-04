<?php
require_once($_SERVER['DOCUMENT_ROOT'] . "/bitrix/modules/main/include/prolog_before.php");
CModule::IncludeModule('crm');
if (strripos ($_SERVER['HTTP_REFERER'], 'bpm.ucre.ru')!==false){
  $contact = new CCrmContact;
  $entity = $contact->GetListEx(
    array(), 
    array("ID" => $_POST['id']), 
    false, 
    false, 
    array('UF_CRM_1522587902','ASSIGNED_BY_ID'),
    array()
  )->Fetch();
  
  foreach($entity as $fileldName=>$value){$entity[$fileldName] = array('VALUE' => $value);}//Приводим параметр к виду понятному вызываемому компоненту
  $entity['ID'] = $entity['ID']['VALUE'];//Микрокостыль, это поле должно выглядеть нормально
  $entity['ASSIGNED_BY_ID'] = $entity['ASSIGNED_BY_ID']['VALUE'];//Микрокостыль, это поле должно выглядеть нормально
  
  /*Общий компонент для загрузки и редактирования галереи изображений из обозначенных полей*/
  $APPLICATION->IncludeComponent(
    'ucre:contact.gallery.upload',
    '',
    array('ENTITY' => $entity,
          'FIELDS' => array(
            'Копии документов' => 'UF_CRM_1522587902',
          )
         )
  );
  
  /*--------------------------------------------------------------------------*/
}
?>