<?
include '../include/kladr/kladr.php';
function getContentType($type){
  switch ($type){
    case 'region':
      return Kladr\ObjectType::Region;
    case 'district':
      return Kladr\ObjectType::District;
    case 'city':
      return Kladr\ObjectType::City;
    case 'street':
      return Kladr\ObjectType::Street;
    case 'building':
      return Kladr\ObjectType::Building;
    default:
      return '';
  }
}
function getId($contentname, $contenttype, $token){
  $localapi = new Kladr\Api($token);
  $query = new Kladr\Query();
  $query->ContentName = $contentname;
  $query->ContentType = getContentType($contenttype);
  $query->Limit = 1;
  $arResult = $localapi->QueryToJson($query,true);
  return $arResult['result'][0]['id'];
}
//print_r($_POST);
$kladr_api = json_decode(file_get_contents($_SERVER['DOCUMENT_ROOT']."/../kladr_api_params"));
$api = new Kladr\Api($kladr_api->token);
// Формирование запроса
$query              = new Kladr\Query();
$query->ContentName = $_POST['contentname'];
$query->ContentType = getContentType($_POST['contenttype']);
$query->ParentType = getContentType($_POST['parenttype']);
$query->ParentId = getId($_POST['parentvalue'],$_POST['parenttype'],$kladr_api->token);
$query->Limit     = 10;

// Получение данных в виде ассоциативного массива
$arResult = $api->QueryToJson($query,true);
if (count($arResult['result'])){
  foreach($arResult['result'] as $result){
    echo "<option>".$result['name']."</option>";
  }
}
?>