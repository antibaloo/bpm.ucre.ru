<?php
$user_cookie_file = 'mts_pbx.txt'; //Полный путь до файла, где будем хранить куки
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL,"http://vpbx.mts.ru/gwt/enterprise/421608456/");//Куда идём
curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);//Возвращаем строку
curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; SV1; .NET CLR 1.1.4322)");//Юзер агент
curl_setopt($ch, CURLOPT_REFERER,"");
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);//Автоматом идём по редиректам
//curl_setopt($ch, CURLOPT_HEADER, array("Content-Type: application/x-www-form-urlencoded; charset=utf-8")); //Хеадер
curl_setopt($ch, CURLOPT_COOKIEFILE, $user_cookie_file); //Куки раз
curl_setopt($ch, CURLOPT_COOKIEJAR, $user_cookie_file); //Куки два
curl_setopt($ch, CURLOPT_AUTOREFERER,1);//Автоматическое выставление рефа, иногда косячит
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);//В большенстве случаев помогает, если используется https
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);//В большенстве случаев помогает, если используется https
$html = curl_exec($ch);

curl_setopt($ch, CURLOPT_POST,1); //Будем отправлять POST запрос
//curl_setopt($ch, CURLOPT_HEADER, true);
curl_setopt($ch, CURLOPT_POSTFIELDS,"j_username=&j_password=");//Что отправляем: ПРОБЛЕМА БЫЛА В КОДИРОВКЕ СИМВОЛА +
curl_setopt($ch, CURLOPT_URL,'http://vpbx.mts.ru/j_spring_security_check');//Куда отправляем
curl_setopt($ch,CURLOPT_REFERER,"http://vpbx.mts.ru/login/");//Откуда пришли
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);//Автоматом идём по редиректам
$html = curl_exec($ch);

//далее идём на нужную нам страницу
curl_setopt($ch, CURLOPT_POST, 0); //Будем отправлять POST запрос
//curl_setopt($ch, CURLOPT_HEADER, true);
curl_setopt($ch, CURLOPT_URL,'http://vpbx.mts.ru/gwt/enterprise/421608456/');
$html = curl_exec($ch);
//ну и дальше парсим $html предварительно стерев заголовок
echo $html;
?>
