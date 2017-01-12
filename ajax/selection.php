<?php
require_once($_SERVER['DOCUMENT_ROOT'] . "/bitrix/modules/main/include/prolog_before.php");
CModule::IncludeModule("crm");
if (isset($_POST['id'])){
  $tempDeal = new CCrmDeal;
  $tempob = $tempDeal->GetListEx(
    array(), 
    array("ID" => $_POST['id']), 
    false, 
    false, 
    array("CATEGORY_ID","UF_CRM_575FDFDDE0CC4","UF_CRM_1476448884","UF_CRM_1479793392","UF_CRM_1479793417"),//Тип, кол-во комнат, цена от, цена до
    array()
  );
  $tempFields = $tempob->Fetch();
  if ($tempFields['CATEGORY_ID'] == 0){//Продажа
    echo "Подходящие заявки на покупку";
  }
  if ($tempFields['CATEGORY_ID'] == 2){//Покупка
    echo "Подходящие заявки на продажу";
  }
  //echo "Подбор встречных заявок для заявки с id = ".$_POST['id']." - ".\Bitrix\Crm\Category\DealCategory::getName($tempFields['CATEGORY_ID']);
  $rsEnum = CUserFieldEnum::GetList(array(), array("ID" =>$tempFields['UF_CRM_575FDFDDE0CC4'])); // $ENUM_ID - возвращаемый ID значения 
  $arEnum = $rsEnum->GetNext(); 
  //echo $arEnum["VALUE"]; 
}
?>
<table>
  <tr>
    <th>Тип</th><th>Кол-во комнат</th><th></th><th></th>
  </tr>
  <tr>
    <td><?=$arEnum["VALUE"]?></td><td><?=$tempFields['UF_CRM_1476448884']?></td><td></td><td></td>
  </tr>
</table>