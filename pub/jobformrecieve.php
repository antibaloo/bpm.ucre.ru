<?
if ($_SERVER['HTTP_ORIGIN'] == "http://job.ucre.ru"){
  require ($_SERVER['DOCUMENT_ROOT'] . "/bitrix/modules/main/include/prolog_before.php");
  CModule::IncludeModule('iblock');
  //status= OK|ERROR
  //errors - массив строк с сообщениями об ошибках
  $result = array('file' => $_FILES, 'params' => $_POST, 'status' => 'ERROR', 'errors' => '', 'server' => $_SERVER);
  //$result = array('status' => 'ERROR', 'errors' => '');
  $errors = array();
  if ($_POST['FullName'] == "") $errors[] = "Не заполнено поле 'ФИО'.";
  if ($_POST['BirthDate'] == ""){
    $errors[] = "Не заполнено поле 'Дата рождения'.";
  }else {
   $db = explode("-",$_POST['BirthDate']);
    if($db[1] > date('m') || $db[1] == date('m') && $db[2] > date('d'))
      $age = date('Y') - $db[0] - 1;
    else
      $age = date('Y') - $db[0];
    if ($age<20) $errors[] = "Возраст соискателя (".$age.") должен быть больше 20 лет";
    
  }
  if ($_POST['Phone'] == "") $errors[] = "Не заполнено поле 'Телефон'.";
  if ($_POST['Email'] == "") {
    $errors[] = "Не заполнено поле 'Email'.";
  }else {
    $validation = filter_var($_POST['Email'], FILTER_VALIDATE_EMAIL);
    if ( !$validation ) $errors[] = "Поле 'Email' заполнено некорректно!";
  }
  if ($_POST['Resume'] == 'undefined') {
    $errors[] = "Не выбран 'Файл резюме'.";
  }else{
    if (!in_array($_FILES['Resume']['type'], array('application/pdf','application/msword','image/jpeg'))) $errors[] = "'Файл резюме' должен быть в формате MS Word, PDF или JPEG";
  }
  if (count($errors)){
    $result['errors'] = "<span style ='color:red;'>".implode("<br>", $errors)."</span>";
  }else{//Если нет ошибок, сохраняем данные
    $el = new CIBlockElement;
     
    $PROP = array(
      'STATUS'    => 'NEW',
      'FIO'       => $_POST['FullName'],
      'BIRTHDATE' => $db[2].".".$db[1].".".$db[0]." 00:00:00",
      'PHONE'     => $_POST['Phone'],
      'EMAIL'     => $_POST['Email'],
      'RESUME'    => $_FILES['Resume']
    );
    $arResumeArray = array(
      "DATE_ACTIVE_FROM"  => ConvertTimeStamp(time(), "FULL"),
      "IBLOCK_ID"         => 65,
      "PROPERTY_VALUES"   => $PROP,
      "NAME"              => "Резюме из формы на сайте job.ucre.ru, отправлено: ".ConvertTimeStamp(time(), "FULL"),
    );
    
    if($RESUME_ID = $el->Add($arResumeArray))     $result['status'] = "OK";
    else $result['errors'] ="<span style ='color:red;'>".$el->LAST_ERROR."</span>";
    
  }
  header('Content-Type: application/json');
  header('Access-Control-Allow-Origin: *');
  echo json_encode($result); 
}