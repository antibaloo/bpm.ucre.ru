<?
if ($arResult['ENTITY_ID']>0){
  if ($arResult['CATEGORY_ID'] == 0 || $arResult['CATEGORY_ID'] == 4){
    
  }
  if ($arResult['CATEGORY_ID'] == 3 || $arResult['CATEGORY_ID'] == 9){
    
  }
  if ($arResult['CATEGORY_ID'] == 2){
    
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