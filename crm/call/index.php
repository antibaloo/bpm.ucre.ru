<?php
require_once($_SERVER['DOCUMENT_ROOT']."/bitrix/header.php");
$APPLICATION->SetTitle("Запросы к ВАТС Мегафон");
$megapbx = json_decode(file_get_contents($_SERVER['DOCUMENT_ROOT']."/../megapbx_params"));
if (count($_POST)){
  if ($_POST['cmd'] == 'accounts'){
    $postdata = http_build_query(array('cmd' => $_POST['cmd'],'token' => $_POST['token']));
  } elseif ($_POST['cmd'] == 'makeCall'){
    $postdata = http_build_query(array('cmd' => $_POST['cmd'],'phone'=>'+79877955786','user'=>'703','token' => $_POST['token']));
  }
  $opts = array('http' =>array('method'  => 'POST','header'  => 'Content-type: application/x-www-form-urlencoded','content' => $postdata));
  $context  = stream_context_create($opts); 
  $result = file_get_contents($megapbx->pbx_url, false, $context);
  $answers_headers = $http_response_header;
}
?>
<form id="megapbx" method="POST">
  cmd
  <select name="cmd">
    <option value="accounts">accounts</option>
    <option value="makeCall">makeCall</option>
  </select>
  <input name="token" type="hidden" value="<?=$megapbx->pbx_key?>">
  <input type="submit" value="Отправить">
</form>
<div id="vats_answer">
  <pre>
  <?
  if( preg_match( "#HTTP/[0-9\.]+\s+([0-9]+)#",$answers_headers[0], $out ) ) echo "Код ответа: ".intval($out[1])."<br>";
  if ($result){
    print_r(json_decode($result,true));
  }
  ?>
  </pre>
</div>
<script language="javascript" type="text/javascript">
  var source = new EventSource("/pub/sse/sse_server.php?id=<?=$USER->GetID()?>");

      source.onmessage = function(event) {
				console.log(event.id);
        console.log(event.data);
        //document.getElementById("sse_result").innerHTML = event.data;
      };
</script>
<?
require_once($_SERVER['DOCUMENT_ROOT']."/bitrix/footer.php");
?>