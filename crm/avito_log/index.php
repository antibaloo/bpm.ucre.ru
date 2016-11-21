<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
global $USER;
$APPLICATION->SetTitle("Лог загрузки объявлений на avito.ru");
echo $USER::GetFullName().', Ваш идентификатор в системе: '.$USER::GetID();
if ($USER::GetID() == 24) {
  if($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["add_log"]) && $_POST["log_link"]!="" && $_POST["avito_login"]!="" && $_POST["avito_pass"]!=""){
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL,"https://www.avito.ru/profile/messenger");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
    curl_setopt($ch, CURLOPT_COOKIEJAR, $_SERVER['DOCUMENT_ROOT'].'/cookie.txt');
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_USERAGENT, "Opera/10.0 (Windows NT 5.1; U; en");
    curl_setopt($ch, CURLOPT_REFERER, "http://avito.ru/profile"); 
    curl_setopt($ch, CURLOPT_POSTFIELDS, "login=".$_POST["avito_login"]."&password=".$_POST["avito_pass"]."&submit=logon");
    $result = curl_exec($ch);
    curl_setopt($ch, CURLOPT_URL,$_POST["log_link"]);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
    curl_setopt($ch, CURLOPT_USERAGENT, "Opera/10.0 (Windows NT 5.1; U; en");
    $result = curl_exec($ch);
    $avito_result = fopen('/home/bitrix/www_bpm/temp_avito_log.html', 'w');
    fwrite( $avito_result, $result);
    fclose( $avito_result );
    
    $dom = new DomDocument();
    $dom->loadHTMLFile('/home/bitrix/www_bpm/temp_avito_log.html');
    $xpath = new DomXPath($dom);
    $_error = $xpath->query("/html/body");
    $error = strripos(utf8_decode(utf8_decode($_error->item(0)->nodeValue)), "Ошибка:");
    if ($error===false){
      //Парсим ID лога из отчета AVITO_ID
      $_avitoid = $xpath->query("/html/body/div[@class='width']/div[@class='block block__report-info']/div[@class='block-title']");
      $avitoid = substr(stristr(utf8_decode($_avitoid->item(0)->nodeValue),"№"),1);
      
      $query = 'SELECT * FROM ucre_avito_log WHERE UF_AVITO_ID="'.$avitoid.'"';
      $rsData = $DB->Query($query);
      if ($avitolog = $rsData->Fetch()){ //Если запись с таким AVITO_ID есть
        $ID = $avitolog['ID']; // то запоминаем id этой записи, будем обновлять даннеы в ней
      } else {
        $ID = 0;// в противном случае будем добавлять новую запись
      }
      $DB->PrepareFields("ucre_avito_log");
      $_params = $xpath->query("/html/body/div[@class='width']/div[@class='form-section form-section_blue']/fieldset[@class='form-fieldset is-readonly']");
      $arFields = array(//Наполняем поля данными
        'UF_AVITO_ID' =>  "'".$avitoid."'", //ID Лога на Авито
        'UF_STATUS'   =>  "'".utf8_decode($_params->item(0)->childNodes->item(2)->nodeValue)."'", //Общий статус загрузки
        'UF_LINK'     =>  "'http://avito.ru".$_params->item(1)->childNodes->item(2)->childNodes->item(1)->getAttributeNode("href")->nodeValue."'", //Ссылка xml фид загрузки
        'UF_LOG_LINK' =>  "'".$_POST["log_link"]."'",
        'UF_TIME'     =>  $DB->CharToDateFunction($_params->item(2)->childNodes->item(2)->nodeValue)  //Время обработки фида
      );
      $DB->StartTransaction();
      if ($ID > 0) {
        $DB->Update("ucre_avito_log", $arFields, "WHERE ID='".$ID."'", $err_mess.__LINE__);
      } else {
        $ID = $DB->Insert("ucre_avito_log", $arFields, $err_mess.__LINE__);
      }
      $ID = intval($ID);
      if (strlen($strError)<=0) {
        $DB->Commit();
      } else $DB->Rollback();
      
      $_res = $xpath->query("/html/body/div[@class='width']/table[@class='table table__items']/tbody/tr");
      $uf_processed = 0;
      $uf_success = 0;
      $uf_problems = 0;
      $uf_errors = 0;
      $uf_deleted = 0;
      foreach ($_res as $row){
        $children = $row->childNodes;
        $DB->PrepareFields("ucre_avito_log_element");
        if ($children->length == 10){
          $arElementFields = array(
            'UF_AVITO_LOG_ID' =>  "'".$avitoid."'",
            'UF_CRM_ID'       =>  "'".trim($children->item(2)->childNodes->item(1)->nodeValue)."'",
            'UF_AVITO_LINK'   =>  "'".$children->item(2)->childNodes->item(3)->nodeValue."'",
            'UF_STATUS'       =>  "'".utf8_decode($children->item(4)->childNodes->item(1)->nodeValue)."'",
            'UF_STATUS_MORE'  =>  "'".utf8_decode($children->item(4)->childNodes->item(4)->nodeValue)."'",
            'UF_TILL'         =>  $DB->CharToDateFunction($children->item(6)->childNodes->item(1)->nodeValue),
            'UF_MESSAGE'      =>  "'".utf8_decode($children->item(8)->nodeValue)."'"
          );
        }
        if ($children->length == 8){
          $arElementFields = array(
            'UF_AVITO_LOG_ID' =>  "'".$avitoid."'",
            'UF_CRM_ID'       =>  "'".trim($children->item(2)->childNodes->item(1)->nodeValue)."'",
            'UF_AVITO_LINK'   =>  "'".$children->item(2)->childNodes->item(3)->nodeValue."'",
            'UF_STATUS'       =>  "'".utf8_decode($children->item(4)->childNodes->item(1)->nodeValue)."'",
            'UF_STATUS_MORE'  =>  "'".utf8_decode($children->item(4)->childNodes->item(4)->nodeValue)."'",
            'UF_TILL'         =>  "",
            'UF_MESSAGE'      =>  "'".utf8_decode($children->item(6)->nodeValue)."'"
          );
        }
        $query = 'SELECT * FROM ucre_avito_log_element WHERE UF_AVITO_LOG_ID="'.$avitoid.'" AND UF_CRM_ID="'.trim($children->item(2)->childNodes->item(1)->nodeValue).'"';
        $rsData = $DB->Query($query);
        if ($avitologelement = $rsData->Fetch()){ //Если запись с таким AVITO_LOG_ID есть
          $ID_EL = $avitologelement['ID']; // то запоминаем id этой записи, будем обновлять даннеы в ней
        } else {
          $ID_EL = 0;// в противном случае будем добавлять новую запись
        }
        $DB->StartTransaction();
        if ($ID_EL > 0) {
          $DB->Update("ucre_avito_log_element", $arElementFields, "WHERE ID='".$ID_EL."'", $err_mess.__LINE__);
        } else {
          $ID = $DB->Insert("ucre_avito_log_element", $arElementFields, $err_mess.__LINE__);
        }
        $ID_EL = intval($ID_EL);
        if (strlen($strError)<=0) {
          $DB->Commit();
        } else $DB->Rollback();
        
        if (utf8_decode($children->item(4)->childNodes->item(1)->nodeValue)=="Успешно опубликовано") $uf_success++;
        if (utf8_decode($children->item(4)->childNodes->item(1)->nodeValue)=="Опубликовано с проблемами") $uf_problems++;
        if (utf8_decode($children->item(4)->childNodes->item(1)->nodeValue)=="Не удалось опубликовать") $uf_errors++;
        if (utf8_decode($children->item(4)->childNodes->item(1)->nodeValue)=="Удалены из файла") $uf_deleted++;
        $uf_processed++;
      }
      
      $arFields = array(
        'UF_PROCESSED'  =>  $uf_processed,
        'UF_SUCCESS'    =>  $uf_success,
        'UF_PROBLEMS'   =>  $uf_problems,
        'UF_ERRORS'     =>  $uf_errors,
        'UF_DELETED'    =>  $uf_deleted
      );
      $DB->StartTransaction();
      $DB->Update("ucre_avito_log", $arFields, "WHERE UF_AVITO_ID = ".$avitoid, $err_mess.__LINE__);
      if (strlen($strError)<=0) {
        $DB->Commit();
      } else $DB->Rollback();
    } else {
      $log_message = utf8_decode($_error->item(0)->nodeValue);
    }
  }
?>
<hr>
<h2>Загрузка лога Авито: <?=$log_message?></h2>
<form method="POST" enctype="multipart/form-data">
  <table>
    <tr>
      <td align="right">Логин на avito.ru: </td><td><input type="email" name="avito_login"></td>
    </tr>
    <tr>
      <td align="right">Пароль на avito.ru: </td><td><input type="password" name="avito_pass"></td>
    </tr>
    <tr>
      <td align="right">Ссылка на отчет об автозагрузке: </td><td><input name = "log_link" type="text" size="70"></td>
    </tr>
    <tr>
      <td></td>
      <td><input type="submit" name="add_log" value="Загрузить лог" title="Загрузить лог с введенным идентификатором"></td>
    </tr>
  </table>
</form>
<hr>
<?
}
echo "Логи автозагрузок: <br>";
$APPLICATION->IncludeComponent(
  'baloo:crm.avitolog.list',
  '',
  array('AVITOLOG_COUNT' => '10')
);
if (isset($_GET['AVITO_ID']) && $_GET['AVITO_ID']!="") {
  echo "<br>Расшифровки логов: <br>";
  $APPLICATION->IncludeComponent(
    'baloo:crm.avitologelement.list',
    '',
    array('AVITOELEMENT_COUNT' => '50',
          'AVITO_ID' => $_GET['AVITO_ID']
         )
  );
}
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");
?>