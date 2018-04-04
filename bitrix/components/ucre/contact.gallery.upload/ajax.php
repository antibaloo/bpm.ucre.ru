<?php
require_once($_SERVER['DOCUMENT_ROOT'] . "/bitrix/modules/main/include/prolog_before.php");
if (strripos ($_SERVER['HTTP_REFERER'], 'bpm.ucre.ru')!==false){
  CModule::IncludeModule('crm');
  $images2save = array();
  foreach($_POST as $key=>$value){//Восттановление  массивов исходных значений
    if (stripos($key,'_old') !== false ) {
      if ($value !="") $_POST[$key] = explode("|",$value);//Из пустых строк восстановление не происходит
    }
  } 

  foreach($_POST as $fieldName=>$value){//Перебор переменных $_POST без приставок _del и _old
    if(stripos($fieldName,'_old') === false && stripos($fieldName,'_del') === false && $fieldName != "deal_id"){
      $images2save[$fieldName] = array();
      foreach($value as $key=>$image){//Перебор данных внутри массивов изображений
        if (is_array($image)){//Если элемент массив (добаленное или измененное изображение)
          $image["MODULE_ID"] = "crm";
          $image["tmp_name"] = $_SERVER["DOCUMENT_ROOT"]."/upload/tmp".$image["tmp_name"];
          $fileId = CFile::SaveFile($image, "crm");
          if (intval($fileId)>0) $images2save[$fieldName][] = intval($fileId); 
        }else{//существующее изображение
          if ($_POST[$fieldName."_del"][$key] == "Y"){//Проверка на удаление
            //удаляем файл $image из таблицы
            CFile::Delete($image);
          }else{ 
            $images2save[$fieldName][] = $image;
          }
          if (isset($_POST[$fieldName."_old"][$key-1])) unset($_POST[$fieldName."_old"][$key-1]);//-1 необходим для компенсации костыля в component.php
        }
      }
    }
  }
  
  foreach($_POST as $fieldName=>$value){//Перебор переменных $_POST с приставкой _old
    if(stripos($fieldName,'_old') !== false){
      foreach($value as $image2delete){//Все id, которые остались в векторах старых значений были заменены в результате редактирования и подлежатудалению
        //удаляем файл $image2delete из таблицы
        CFile::Delete($image2delete);
      }
    }
  }
  
  global $DB, $USER;
  foreach($images2save as $fieldName=>$value){
    if (true){
      if ($DB->Query("UPDATE b_uts_crm_contact SET ".$fieldName."='".serialize($value)."' WHERE VALUE_ID =".$_POST['contact_id'], true)){
        CEventLog::Add(array(
          "SEVERITY" => "SECURITY",
          "AUDIT_TYPE_ID" => "USER_IMG_CONTACT_EDIT",
          "MODULE_ID" => "ucre_crm",
          "ITEM_ID" => 'Сохранение пользовательских изображений в контактах',
          "DESCRIPTION" => "Изображения в поле ".$fieldName.", в контакте ".$_POST['contact_id']." успешно обновлены. Новые значения: ".serialize($value),
        ));
      }else{
        CEventLog::Add(array(
          "SEVERITY" => "SECURITY",
          "AUDIT_TYPE_ID" => "USER_IMG_CONTACT_EDIT",
          "MODULE_ID" => "ucre_crm",
          "ITEM_ID" => 'Ошибка сохранения пользовательских изображений в контактах',
          "DESCRIPTION" => "Ошибка записи изображений в поле ".$fieldName.", в контакте ".$_POST['contact_id'],
        ));
      }
    }
  }
  $contact = new CCrmContact;
  $entity = $contact->GetListEx(
    array(), 
    array("ID" => $_POST['contact_id']), 
    false, 
    false, 
    array('UF_CRM_1522587902','ASSIGNED_BY_ID'),
    array()
  )->Fetch();
  foreach($entity as $fileldName=>$value){$entity[$fileldName] = array('VALUE' => $value);}//Приводим параметр к виду понятному вызываемому компоненту
  $entity['ID'] = $entity['ID']['VALUE'];//Микрокостыль, это поле должно выглядеть нормально
  $entity['ASSIGNED_BY_ID'] = $entity['ASSIGNED_BY_ID']['VALUE'];//Микрокостыль, это поле должно выглядеть нормально

  //Добавление кнопки редактировать по условию
  echo $edit = CCrmContact::CheckUpdatePermission($entity['ID'], CCrmPerms::GetUserPermissions($USER->GetID()))?'<div class="galleryUploadWrapper"><div></div><div></div><div></div><div id="galleryUpload">Редактировать</div><div></div><div></div><div></div></div>':"";
  
  /*Общий компонент для отображения галереи изображений из обозначенных полей*/
  $rsUser = $USER->GetByID($USER->GetID()); 
  $arUser = $rsUser->Fetch();
  if ($USER->IsAdmin() || $arUser['WORK_DEPARTMENT'] == 'АУП' || $USER->GetID() == $entity['ASSIGNED_BY_ID']){
    $APPLICATION->IncludeComponent(
      'ucre:contact.gallery',
      '',
      array('ENTITY' => $entity,
            'FIELDS' => array(
              'Копии документов' => 'UF_CRM_1522587902',
            )
           )
    );
  }
  /*----------------------------------------------------------*/
}
?>