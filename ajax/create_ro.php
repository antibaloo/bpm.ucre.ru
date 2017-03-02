<?
require ($_SERVER['DOCUMENT_ROOT'] . "/bitrix/modules/main/include/prolog_before.php");
CModule::IncludeModule('crm');
if (isset($_POST['deal_id']) && !empty($_POST['deal_id'])){
  $rsDeal = CCrmDeal::GetListEx(
    array(),
    array("ID" => $_POST['deal_id']),
    false,
    false,
    array('TITLE',
          'UF_CRM_58958B52BA439',	//общая
          'UF_CRM_58958B52D6C9B',	//жилая
          'UF_CRM_58958B52F2BAC',	//площадь кухни
          'UF_CRM_5895994ED0C7B',	//этаж
          'UF_CRM_58958B51C2F36',	//этажность
          'UF_CRM_58958B529E628',	//кол-во комнат
          'UF_CRM_5895994EB2646',	//адрес
          'UF_CRM_58958B5207D0C',	//тип дома
          'ASSIGNED_BY_ID'        //Ответственный по заявке
         ),
    array()
  );
  $mainDeal = $rsDeal->Fetch();
  $el = new CIBlockElement;
  $PROP = array();
  $PROP[209] = $mainDeal['UF_CRM_5895994EB2646'];
  if (strpos($mainDeal['TITLE'], "квартира") !== false){
    $PROP[210] = 382;
  }
  $PROP[221] = $mainDeal['UF_CRM_5895994ED0C7B'];
  $PROP[222] = $mainDeal['UF_CRM_58958B51C2F36'];
  $PROP[229] = $mainDeal['UF_CRM_58958B529E628'];
  $PROP[224] = $mainDeal['UF_CRM_58958B52BA439'];
  $PROP[225] = $mainDeal['UF_CRM_58958B52D6C9B'];
  $PROP[226] = $mainDeal['UF_CRM_58958B52F2BAC'];
  
  if ($mainDeal['UF_CRM_58958B5207D0C'] == 757){
    $PROP[243] =428;
  }
  
  
  $PROP[266] = "Предлистинг";  // Статус объекта недвижимости
  $PROP[300] = "Продам"; 
  $PROP[313] = $mainDeal['ASSIGNED_BY_ID']; //Ответственный по объекту
  $PROP[319] = $_POST['deal_id'];
  $arROFields = array(
    "NAME"           => $mainDeal['TITLE'],
    "CREATED_BY"    => $USER->GetID(), // элемент изменен текущим пользователем
    "IBLOCK_ID"      => 42,
    "PROPERTY_VALUES"=> $PROP,
    "ACTIVE"         => "Y",            // активен
  );
  
  if ($RO_ID = $el->Add($arROFields)){
    $DB->StartTransaction();
    $arUpdateData = array('UF_CRM_1469534140' => $RO_ID);
    $DealToUpdate = new CCrmDeal;
    if($DealToUpdate->Update($_POST['deal_id'], $arUpdateData, true, true, array('DISABLE_USER_FIELD_CHECK' => true))){
      $DB->Commit();
    }else{
      $DB->Rollback();
    }
    echo "Создан объект с ID: ".$RO_ID;
  }else{
    echo "Error: ".$el->LAST_ERROR;
  }  
} else {
  echo "Не все необходимые данные переданы!";
}
?>