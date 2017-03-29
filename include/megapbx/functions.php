<?
CModule::IncludeModule('crm');
function beginTimeIncoming($callid){
  global $DB;
  $db_res = $DB->Query("SELECT m_time from b_megapbx_mess WHERE cmd='contact' AND callid='".$callid."'");
  if ($db_res->SelectedRowsCount()>0){
    if ($db_res->SelectedRowsCount()>1) return array("RESULT" => "ERROR", "MESSAGE" => "CALLID IS DUPLICATED");
    if ($aRes = $db_res->Fetch()){
      return array("RESULT" => "SUCCESS", "TIME" => $aRes['m_time']);
    }else {
      return array("RESULT" => "ERROR", "MESSAGE" => "DB ERROR");
    }
  }else{
    return array("RESULT" => "ERROR", "MESSAGE" => "CALLID IS NOT FOUND");
  }
}
function findByPhoneNumber($number, $params = array()){
  if (!is_string($number)){
    throw new \Bitrix\Main\ArgumentTypeException('number', 'string');
  }
  if ($number === ''){
    throw new \Bitrix\Main\ArgumentException('Is empty', 'number');
  }
  if (!is_array($params)){
    $params = array();
  }
  $dups = array();
  $criterion = new \Bitrix\Crm\Integrity\DuplicateCommunicationCriterion('PHONE', $number);
  $entityTypes = array(CCrmOwnerType::Contact, CCrmOwnerType::Company, CCrmOwnerType::Lead);
  foreach ($entityTypes as $entityType){
    $duplicate = $criterion->find($entityType);
    if ($duplicate !== null){
      $dups[] = $duplicate;
    }
  }
  $entityByType = array();
  foreach ($dups as &$dup){
    /** @var \Bitrix\Crm\Integrity\Duplicate $dup */
    $entities = $dup->getEntities();
    if (!(is_array($entities) && !empty($entities))){
      continue;
    }
    //Each entity type limited by 50 items
    foreach ($entities as &$entity){
      /** @var \Bitrix\Crm\Integrity\DuplicateEntity $entity */
      $entityTypeID = $entity->getEntityTypeID();
      $entityID = $entity->getEntityID();
      
      $fields = CCrmSipHelper::getEntityFields($entityTypeID, $entityID, $params);
      if(!is_array($fields)) continue;
      $entityTypeName = CCrmOwnerType::ResolveName($entityTypeID);
      if (!isset($entityByType[$entityTypeName])){
        $entityByType[$entityTypeName] = array($fields);
      }elseif (!in_array($entityID, $entityByType[$entityTypeName], true)){
        $entityByType[$entityTypeName][] = $fields;
      }
    }
  }
  unset($dup);
  if (isset($entityByType['CONTACT'])) unset($entityByType['LEAD']);//Если есть контакт, то лиды игнорируем
  if (isset($entityByType['LEAD'])){//Если есть лид (значит контакта нет)
    foreach($entityByType['LEAD'] as $key=>$lead){//удаляем инфу о закрытых лидах (способ закрытия неважен)
      $curLead = CCrmLead::GetByID($lead['ID'],false);
      if(CCrmLead::IsStatusFinished($curLead['STATUS_ID'])) unset($entityByType['LEAD'][$key]);
    }
  }

  if (count($entityByType)){
    if (isset($entityByType['CONTACT'])) return array('FOUND' => 'Y', 'CONTACT' => end($entityByType['CONTACT']));//Выдаем в результат полений по ID контакт, если найдено несколько
    if (isset($entityByType['LEAD'])) return array('FOUND' => 'Y', 'LEAD' => end($entityByType['LEAD']));//Выдаем в результат полений по ID лид, если найдено несколько не закрытых
  }else {//Если результат нулевой, ищем среди сотрудников, во измежании создания лида с номером сотрудника
    global $DB;
    $temp_phone = substr($number,1);
    $result = $DB->Query("SELECT ID,NAME,SECOND_NAME,LAST_NAME,EMAIL FROM b_user WHERE ACTIVE='Y' AND (REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(PERSONAL_PHONE,'+',''),' ',''),'(',''),')',''),'-','') LIKE '%".$temp_phone."' OR REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(PERSONAL_MOBILE,'+',''),' ',''),'(',''),')',''),'-','') LIKE '%".$temp_phone."' OR REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(WORK_PHONE,'+',''),' ',''),'(',''),')',''),'-','') LIKE '%".$temp_phone."');");
    if ($result->SelectedRowsCount() == 0) {//нет таких
      return array ('FOUND' => 'N');
    }else {//найден
      $arUser = $result->Fetch();
      return array('FOUND'    => 'Y', 
                   'EMPLOYEE' =>  array('ID'          =>  $arUser['ID'],
                                        'NAME'        =>  $arUser['NAME'],
                                        'SECOND_NAME' =>  $arUser['SECOND_NAME'],
                                        'LAST_NAME'   =>  $arUser['LAST_NAME'],
                                        'EMAIL'       =>  $arUser['EMAIL'],
                                        'URL'         =>  '/company/personal/user/'.$arUser['ID'].'/'
                                       )
                  );
    }    
  }
}
?>