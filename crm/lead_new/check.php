<?
/*echo "<pre>";
print_r($_POST);
echo "</pre>";*/
$result = array('params' => $_POST,'result'=>'', 'status' => '', 'errors' => array());

if ($_POST['email']){
  $validation = filter_var($_POST['email'], FILTER_VALIDATE_EMAIL);
  if ( !$validation ) $result['errors'][] = array("field" => "email", "text" => "Адрес введен некорректно!");
}
if (!$_POST['name']) $result['errors'][]= array("field" => "name","text" =>"Не заполнено!");
if (!$_POST['tel1']) $result['errors'][]= array("field" => "tel1","text" =>"Не заполнено!");
if (!$_POST['goal']) $result['errors'][]= array("field" => "goal","text" =>"Не заполнено!");
if (!$_POST['roType']) $result['errors'][]= array("field" => "roType","text" =>"Не заполнено!");

if (count($result['errors'])) $result['result'] ='error';
else $result['result'] ='ok';
header('Content-Type: application/json');
echo json_encode($result);