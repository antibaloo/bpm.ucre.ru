<?
if ($arResult['ENTITY_ID']>0){
  //Фиктивный вызов без отображения. Вкладка загрузки отображается только для админов, АУП и ответственных
  if (CCrmContact::CheckUpdatePermission($arResult['ENTITY_ID'], CCrmPerms::GetUserPermissions($USER->GetID()))){
    ob_start();
    $APPLICATION->IncludeComponent(
      'ucre:contact.gallery.upload',
      '',
      array('ENTITY' => $arResult['ENTITY_DATA'],
            'FIELDS' => array(
              'Копии документов' => 'UF_CRM_1522587902',
            )
           )
    );
  }
  $html = ob_get_contents();
  ob_end_clean();
  
  
  $rsUser = $USER->GetByID($USER->GetID()); $arUser = $rsUser->Fetch();
  if ($USER->IsAdmin() || $arUser['WORK_DEPARTMENT'] == 'АУП' || $USER->GetID() == $arResult['ENTITY_DATA']['ASSIGNED_BY_ID']){
    ob_start();
    $APPLICATION->IncludeComponent(
      'ucre:contact.gallery',
      '',
      array('ENTITY' => $arResult['ENTITY_DATA'],
            'FIELDS' => array(
              'Копии документов' => 'UF_CRM_1522587902',
            )
           )
    );
    
    $html = ob_get_contents();
    ob_end_clean();
    //Добавление кнопки редактировать по условию
    $edit = CCrmContact::CheckUpdatePermission($arResult['ENTITY_ID'], CCrmPerms::GetUserPermissions($USER->GetID()))?'<div class="galleryUploadWrapper"><div></div><div></div><div></div><div id="galleryUpload">Редактировать</div><div></div><div></div><div></div></div>':"";
    $docs = array(
      'id' => 'tab_docs',
      'name' => 'Копии документов',
      'html' =>'<div id="ucreImageDiv">'.$edit.$html.'</div>'
    );	
    array_unshift($arResult['TABS'], $docs);
  }      
}
?>