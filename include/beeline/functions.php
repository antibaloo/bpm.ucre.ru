<?
CModule::IncludeModule('crm');
CModule::IncludeModule('timeman');
function linkCallIds($beelinepbxCallId,$bitrixCallId){
  global $DB;
  $DB->Query("INSERT INTO b_beelinepbx_bitrix VALUES ('".$beelinepbxCallId."','".$bitrixCallId."')");
}

function getBitrixByBeelinepbx($beelinebpxCallId){
  global $DB;
  $db_res = $DB->Query("SELECT bitrix_callid from b_beelinepbx_bitrix WHERE beeline_callid='".$beelinebpxCallId."'");
  if ($aRes = $db_res->Fetch()){
    return $aRes['bitrix_callid'];
  }else return false;
}
function getBeelinepbxByBitrix($bitrixCallId){
  global $DB;
  $db_res = $DB->Query("SELECT beeline_callid from b_beelinepbx_bitrix WHERE bitrix_callid='".$bitrixCallId."'");
  if ($aRes = $db_res->Fetch()){
    return $aRes['beeline_callid'];
  }else return false;
}

function getUserByTargetId($targetId){
  global $DB;
  if ($targetId == '' || !$targetId) return (string)206;
  $rsUsers = $DB->Query("select * from b_beelinepbx_users WHERE beeline_user='".$targetId."' ORDER BY bitrix_user DESC");
  if ($rsUsers->SelectedRowsCount()){
    if ($rsUsers->SelectedRowsCount()>1){
      while ($arUser= $rsUsers->Fetch()){
        $TimemanUser = new CTimeManUser($arUser['bitrix_user']);
      if ($TimemanUser->State() == 'OPENED') return $arUser['bitrix_user'];
      }
    }else {
      $arUser= $rsUsers->Fetch();
      return $arUser['bitrix_user'];
    }
  }else return (string)206;
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
  if (isset($entityByType['COMPANY'])) unset($entityByType['LEAD']);//Если есть компания, то лиды игнорируем
  if (isset($entityByType['LEAD'])){//Если есть лид (значит контакта нет)
    foreach($entityByType['LEAD'] as $key=>$lead){//удаляем инфу о закрытых лидах (способ закрытия неважен)
      $curLead = CCrmLead::GetByID($lead['ID'],false);
      if(CCrmLead::IsStatusFinished($curLead['STATUS_ID'])) unset($entityByType['LEAD'][$key]);
    }
  }

  if (count($entityByType)){
    if (isset($entityByType['CONTACT'])) return array('FOUND' => 'Y', 'CONTACT' => end($entityByType['CONTACT']));//Выдаем в результат полений по ID контакт, если найдено несколько
    if (isset($entityByType['COMPANY'])) return array('FOUND' => 'Y', 'COMPANY' => end($entityByType['COMPANY']));//Выдаем в результат полений по ID компании, если найдено несколько
    if (isset($entityByType['LEAD']) && count($entityByType['LEAD'])) return array('FOUND' => 'Y', 'LEAD' => end($entityByType['LEAD']));//Выдаем в результат полений по ID лид, если найдено несколько не закрытых
    else return array('FOUND' => 'N');
  }else return array ('FOUND' => 'N');
}

?>