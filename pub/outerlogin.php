<?php
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL,"https://www.avito.ru/profile/messenger");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
curl_setopt($ch, CURLOPT_COOKIEJAR, $_SERVER['DOCUMENT_ROOT'].'/cookie.txt');
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_USERAGENT, "Opera/10.0 (Windows NT 5.1; U; en");
curl_setopt($ch, CURLOPT_REFERER, "http://avito.ru/profile"); 
curl_setopt($ch, CURLOPT_POSTFIELDS, "login=<login>&password=<pass>&submit=logon");
/*echo*/ $result = curl_exec($ch);
//curl_setopt($ch, CURLOPT_URL,"https://www.avito.ru/orenburg/kvartiry/prodam/1-komnatnye/vtorichka?view=list");
curl_setopt($ch, CURLOPT_URL,"https://www.avito.ru/profile/upload/api/2/report/4891668");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
curl_setopt($ch, CURLOPT_USERAGENT, "Opera/10.0 (Windows NT 5.1; U; en");
$result = curl_exec($ch);

$avito_result = fopen('/home/bitrix/www_bpm/avito.html', 'w');
fwrite( $avito_result, $result);
fclose( $avito_result );

$dom = new DomDocument();
$dom->loadHTMLFile('/home/bitrix/www_bpm/avito.html');
$xpath = new DomXPath($dom);
$_error = $xpath->query("/html/body");
$error = strripos(utf8_decode($_error->item(0)->nodeValue), "Ошибка:");

if ($error===false){
  echo "Данные отчета о загрузке: <br>";
} else die (utf8_decode($_error->item(0)->nodeValue));
?>
<table border="1">
  <tr><th>AVITO_ID</th><th>STATUS</th><th>LINK</th><th>TIME</th></tr>
<?
$_id = $xpath->query("/html/body/div[@class='width']/div[@class='block block__report-info']/div[@class='block-title']");
echo "<tr><td>".substr(stristr(utf8_decode($_id->item(0)->nodeValue),"№"),1)."</td>";
$_params = $xpath->query("/html/body/div[@class='width']/div[@class='form-section form-section_blue']/fieldset[@class='form-fieldset is-readonly']");
$status = $_params->item(0)->childNodes;
echo "<td>".utf8_decode($status->item(2)->nodeValue)."</td>";
$link = $_params->item(1)->childNodes;
echo "<td>http://avito.ru".$link->item(2)->childNodes->item(1)->getAttributeNode("href")->nodeValue."</td>";
$time = $_params->item(2)->childNodes;
echo "<td>".utf8_decode($time->item(2)->nodeValue)."</td></table>";
?>
</table>
<br>
<table border="1">
  <tr><th>CRM_ID</th><th>AVITO_LINK</th><th>STATUS</th><th>STATUS_MORE</th><th>TILL</th><th>MESSAGE</th></tr>
<?
$_res = $xpath->query("/html/body/div[@class='width']/table[@class='table table__items']/tbody/tr");
foreach ($_res as $row){
  $children = $row->childNodes;
  //echo $children->length."<br>";
  if ($children->length == 10){
    //echo $children->item(0)->nodeValue."|0|";
    //echo $children->item(2)->nodeValue."|2|";
    echo "<tr><td>".trim($children->item(2)->childNodes->item(1)->nodeValue)."</td><td>".$children->item(2)->childNodes->item(3)->nodeValue."</td>";
    //echo "!".trim($children->item(2)->childNodes->item(1)->nodeValue)."!".$children->item(2)->childNodes->item(3)->nodeValue."!";
    echo "<td>".utf8_decode($children->item(4)->childNodes->item(1)->nodeValue)."</td><td>".utf8_decode($children->item(4)->childNodes->item(4)->nodeValue)."</td>";
    //echo utf8_decode($children->item(4)->nodeValue)."|4|";
    echo "<td>".$children->item(6)->childNodes->item(1)->nodeValue."</td>";
    //echo utf8_decode($children->item(6)->nodeValue)."|6|";
    echo "<td>".utf8_decode($children->item(8)->nodeValue)."</td></tr>";
  }
  if ($children->length == 8){
    //echo $children->item(0)->nodeValue."|0|";
    echo "<tr><td>".trim($children->item(2)->childNodes->item(1)->nodeValue)."</td><td>".$children->item(2)->childNodes->item(3)->nodeValue."</td>";
    //echo $children->item(2)->nodeValue."|2|";
    echo "<td>".utf8_decode($children->item(4)->childNodes->item(1)->nodeValue)."</td><td>".utf8_decode($children->item(4)->childNodes->item(4)->nodeValue)."</td>";
    //echo utf8_decode($children->item(4)->nodeValue)."|4|";
    echo "<td></td><td>".utf8_decode($children->item(6)->nodeValue)."</td></tr>";
    //echo utf8_decode($children->item(6)->nodeValue)."|6|<br>";
  }
}
?>
</table>