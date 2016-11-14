<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
global $USER;
$APPLICATION->SetTitle("Лог загрузки объявлений на avito.ru");
echo $USER::GetFullName().', Ваш идентификатор в системе: '.$USER::GetID();
if ($USER::GetID() == 24) {
  if($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["add_log"]) && $_POST["add_log"]!=""){
    $i++;
  }
?>
<hr>
<h2>Загрузить лог</h2>
<form method="POST" enctype="multipart/form-data">
  <table>
    <tr>
      <td align="right">Логин на avito.ru: </td><td><input type="email" name="avito_login"></td>
    </tr>
    <tr>
      <td align="right">Пароль на avito.ru: </td><td><input type="password" name="avit_pass"></td>
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
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");
?>