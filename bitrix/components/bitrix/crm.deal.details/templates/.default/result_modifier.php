<?
if ($arResult['ENTITY_ID']>0){
  if ($arResult['CATEGORY_ID'] == 0 || $arResult['CATEGORY_ID'] == 4){
    
  }
  if ($arResult['CATEGORY_ID'] == 3 || $arResult['CATEGORY_ID'] == 9){
    
  }
  if ($arResult['CATEGORY_ID'] == 2){
    ob_start();
    /*Компонент для редактирования географии поиска для заявок на покупку*/		
    $APPLICATION->IncludeComponent(
      'ucre:crm.deal.geo',
      '',
      array('DEAL_ID' => $arResult['ENTITY_ID'])
    );
    /*----------------------------------------------------------*/
    $html = ob_get_contents();
    ob_end_clean();
    $geo = array(
      'id' => 'tab_geo',
      'name' => 'Область поиска',
      'html' => $html
    );
    ob_start();
    /*Общий компонент для отображения галереи изображений из обозначенных полей*/
    $APPLICATION->IncludeComponent(
      'ucre:gallery',
      '',
      array('ENTITY' => $arResult['ENTITY_DATA'],
            'FIELDS' => array(
              'Фотографии' => 'UF_CRM_1472038962',
              'Планировки' => 'UF_CRM_1476517423',
              'Фото для внутреннего пользования' => 'UF_CRM_1513322128',
            )
           )
    );
    /*----------------------------------------------------------*/
    /*Общий компонент для отображения галереи изображений из обозначенных полей*/
    $rsUser = $USER->GetByID($USER->GetID()); $arUser = $rsUser->Fetch();
    if ($USER->IsAdmin() || $arUser['WORK_DEPARTMENT'] == 'АУП' || $USER->GetID() == $arResult['ENTITY_DATA']['ASSIGNED_BY_ID']){
      $APPLICATION->IncludeComponent(
        'ucre:gallery',
        '',
        array('ENTITY' => $arResult['ENTITY_DATA'],
              'FIELDS' => array(
                'Документы по заявке' => 'UF_CRM_1472704376',
                'Подписанная заявка' => 'UF_CRM_1512462544',
                'Скан агентского договора' => 'UF_CRM_1512462594',
              )
             )
      );
    }
    /*----------------------------------------------------------*/
    $html = ob_get_contents();
    ob_end_clean();
    //Добавление кнопки редактировать по условию
    $edit = CCrmDeal::CheckUpdatePermission($arResult['ENTITY_ID'], CCrmPerms::GetUserPermissions($USER->GetID()))?'<div class="galleryUploadWrapper"><div></div><div></div><div></div><div id="galleryUpload">Редактировать</div><div></div><div></div><div></div></div>':"";
    $gallery = array(
      'id' => 'tabImg',
      'name' => 'Изображения',
      'html' =>'<div id="ucreImageDiv">'.$edit.$html.'</div>'
    );
    ob_start();
    /*Компонент для отображения информации по встречным заявкам*/
    $APPLICATION->IncludeComponent(
      "ucre:select.relevant.for_buy",
      "",
      array('ID' => $arResult['ENTITY_ID']),
      false
    );
    /*----------------------------------------------------------*/
    $html = ob_get_contents();
    ob_end_clean();
    $relevant = array(
      'id' => 'tab_relevant',
      'name' => 'Встречные заявки',
      'html' => $html
    );
    ob_start();
    /*Компонент для отображения информации по потенциальным сделкам*/
    $APPLICATION->IncludeComponent(
      "ucre:deals.potential.for_buy",
      "",
      array('ID' => $arResult['ENTITY_ID']),
      false
    );
    /*----------------------------------------------------------*/
    $html = ob_get_contents();
    ob_end_clean();
    $potentials = array(
      'id' => 'tab_potentials',
      'name' => 'Потенциальные сделки',
      'html' => $html
    );
    array_unshift($arResult['TABS'], $geo, $gallery, $relevant, $potentials);
  }
}
if ($USER->GetID() == 24 || $USER->GetID() == 1){
  $custom = array(
    'id' => 'tab_custom',
    'name' => 'Something',
    'html' => "Тестовая вкладка из result_modifier.php"
  );
  array_unshift($arResult['TABS'], $custom);
}

?>