<?
  if ($_SERVER['HTTP_ORIGIN'] == "https://bpm.ucre.ru" && $_POST['url'] !=""){
    $response = file_get_contents($_POST['url']);
    echo $response;
  }
?>