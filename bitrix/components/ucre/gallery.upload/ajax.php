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
  
  
  echo "Исходный вектор<pre>";print_r($_POST);echo "</pre>";
  foreach($_POST as $fieldName=>$value){//Перебор переменных $_POST без приставок _del и _old
    if(stripos($fieldName,'_old') === false && stripos($fieldName,'_del') === false && $fieldName != "deal_id"){
      $images2save[$fieldName] = array();
      foreach($value as $key=>$image){//Перебор данных внутри массивов изображений
        if (is_array($image)){//Если элемент массив (добаленное или измененное изображение)
          $image["MODULE_ID"] = "crm";
          $image["tmp_name"] = $_SERVER["DOCUMENT_ROOT"]."/upload/tmp".$image["tmp_name"];
          $fileId = CFile::SaveFile($image, "crm");
          if (intval($fileId)>0) {$images2save[$fieldName][] = intval($fileId);echo "Зарегистрирован новый файл с id ".$fileId."<br>";} 
        }else{//существующее изображение
          if ($_POST[$fieldName."_del"][$key] == "Y"){//Проверка на удаление
            //удаляем файл $image из таблицы
            echo "Файл с id ".$image." был удален из поля ".$fieldName." и из таблицы<br>";
            CFile::Delete($image);
          }else{ 
            $images2save[$fieldName][] = $image;
          }
          unset($_POST[$fieldName."_old"][$key-1]);//-1 необходим для компенсации костыля в component.php
        }
      }
    }
  }
  
  foreach($_POST as $fieldName=>$value){//Перебор переменных $_POST с приставкой _old
    if(stripos($fieldName,'_old') !== false){
      foreach($value as $image2delete){//Все id, которые остались в векторах старых значений были заменены в результате редактирования и подлежатудалению
        //удаляем файл $image2delete из таблицы
        echo "Файл с id ".$image2delete." был изменен в поле ".$fieldName." и удален из таблицы<br>";
        CFile::Delete($image2delete);
      }
    }
  }
  
  global $DB;
  foreach($images2save as $fieldName=>$value){
    if (true){
      if ($DB->Query("UPDATE b_uts_crm_deal SET ".$fieldName."='".serialize($value)."' WHERE VALUE_ID =".$_POST['deal_id'], true)){
        CEventLog::Add(array(
          "SEVERITY" => "SECURITY",
          "AUDIT_TYPE_ID" => "USER_IMG_DEAL_EDIT",
          "MODULE_ID" => "ucre_crm",
          "ITEM_ID" => 'Сохранение пользовательских изображений в заявках',
          "DESCRIPTION" => "Изображения в поле ".$fieldName.", в заявке ".$_POST['deal_id']." успешно обновлены. Новые значения: ".serialize($value),
        ));
      }else{
        CEventLog::Add(array(
          "SEVERITY" => "SECURITY",
          "AUDIT_TYPE_ID" => "USER_IMG_DEAL_EDIT",
          "MODULE_ID" => "ucre_crm",
          "ITEM_ID" => 'Ошибка сохранения пользовательских изображений в заявках',
          "DESCRIPTION" => "Ошибка записи изображений в поле ".$fieldName.", в заявке ".$_POST['deal_id'],
        ));
      }
    }
  }

  echo "Исходный вектор с правками<pre>";print_r($_POST);echo "</pre>";
  echo "Сформированный вектор<pre>";print_r($images2save);echo "</pre>";
}
?>