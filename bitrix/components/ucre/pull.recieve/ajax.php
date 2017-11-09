<?
require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");
echo "<pre>";
print_r($_POST['params']);
echo "<pre>";
if ($_POST['params']['TYPE'] == 2) echo '<h2>Входящий звонок с номера: '.$_POST['params']['PHONE'].'</h2>';
else echo '<h2>Исходящий звонок на номер: '.$_POST['params']['PHONE'].'</h2>';
switch ($_POST['params']['SOURCE']){
  case "avito":
    echo '<h3>Звонок по объявлению на Авито</h3>';
    break;
  case "irr":
    echo '<h3>Звонок по объявлению на ИРР</h3>';
    break;
    case "main":
    echo '<h3>Звонок на основной номер компании</h3>';
    break;
  case "tv":
    echo '<h3>Звонок по ТВ рекламе</h3>';
    break;
  default:
    echo "Источник звонка не определен";
    break;
}

require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/epilog_after.php");
?>  