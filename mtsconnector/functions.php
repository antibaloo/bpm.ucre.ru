<?
function phonetype($phone, $db_current){//Определяем тип телефонного номера (O - существующий сотрудник, C - существующий клиент, L - действующий лид, N - номера нет в базе или телефон закрытого лида)
  //Поиск по базе сотрудников
  $mask_phone = "+7 (".substr($phone,0,3).") ".substr($phone,3,2)."-".substr($phone,5,2)."-".substr($phone,7); //+7 (999) 99-99-999
  $mask_mobile = "+7 (".substr($phone,0,3).") ".substr($phone,3,3)."-".substr($phone,6,2)."-".substr($phone,8);//+7 (999) 999-99-99
  $result = $db_current->Query("SELECT ID FROM b_user WHERE ACTIVE='Y' AND (PERSONAL_PHONE ='".$mask_phone."' OR PERSONAL_MOBILE = '".$mask_mobile."')");
  if ($result->SelectedRowsCount()>0){
    return "O";
  }
  //Поиск по базе клиентов
  $mask_mobile = "+7(".substr($phone,0,3).")".substr($phone,3,3)."-".substr($phone,6,2)."-".substr($phone,8);//+7(999)999-99-99
  $result = $db_current->Query("SELECT ID from b_crm_field_multi WHERE ENTITY_ID = 'CONTACT' AND VALUE = '".$mask_mobile."'");
  if ($result->SelectedRowsCount()>0) {
    return "C";
  }
  //Поиск по базе лидов
  $result = $db_current->Query("SELECT ID,ELEMENT_ID from b_crm_field_multi WHERE ENTITY_ID = 'LEAD' AND VALUE = '".$mask_mobile."'");
  if ($result->SelectedRowsCount()>0) {
    $row = $result->Fetch();
    $result = $db_current->Query("SELECT STATUS_ID from b_crm_lead WHERE ID='".$row['ELEMENT_ID']."'");
    $row = $result->Fetch();
    switch ($row['STATUS_ID']){//Проверка закрытых лидов со статусом
      case "1"://не явился на собеседование
      case "2"://не прошел собеседование
      case "3"://риелтор
      case "4"://отказ клиента
      case "JUNK"://некачественный лид
      case "CANNOT_CONTACT"://не удалось свсязаться
        return "N"; //возвращаем номера нет в базе или лид закрыт с неуспехом
        break;
      default:
        return "L"; //действующий лид
    }
  }
  return "N";//номера нет в базе или лид закрыт с неуспехом (неконвертирован в контакт)
}

function whose($phone, $type, $db_current){ //Определяем ID собственника номера в зависимости от типа номера (О, С или L )
  switch ($type){
    case "O":
      $mask_phone = "+7 (".substr($phone,0,3).") ".substr($phone,3,2)."-".substr($phone,5,2)."-".substr($phone,7); //+7 (999) 99-99-999
      $mask_mobile = "+7 (".substr($phone,0,3).") ".substr($phone,3,3)."-".substr($phone,6,2)."-".substr($phone,8);//+7 (999) 999-99-99
      $result = $db_current->Query("SELECT ID FROM b_user WHERE ACTIVE='Y' AND (PERSONAL_PHONE ='".$mask_phone."' OR PERSONAL_MOBILE = '".$mask_mobile."')");
      $row = $result->Fetch();
      return $row['ID'];
      break;
    case "C":
      $mask_mobile = "+7(".substr($phone,0,3).")".substr($phone,3,3)."-".substr($phone,6,2)."-".substr($phone,8);//+7(999)999-99-99
      $result = $db_current->Query("SELECT ID,ELEMENT_ID from b_crm_field_multi WHERE ENTITY_ID = 'CONTACT' AND VALUE = '".$mask_mobile."'");
      $row = $result->Fetch();
      return $row['ELEMENT_ID'];
      break;
    case "L":
      $mask_mobile = "+7(".substr($phone,0,3).")".substr($phone,3,3)."-".substr($phone,6,2)."-".substr($phone,8);//+7(999)999-99-99
      $result = $db_current->Query("SELECT ID,ELEMENT_ID from b_crm_field_multi WHERE ENTITY_ID = 'LEAD' AND VALUE = '".$mask_mobile."'");
      $row = $result->Fetch();
      return $row['ELEMENT_ID'];
      break;
  }
}
?>