<!DOCTYPE html>
<html>
  <header>
    <title>Считывание данных из объявления</title>
  </header>
  <body>
    <div>
      <form action="" method="post">
        Ссылка на объявление <input type="text" name="url" size="100">
        <input type="submit" value="Прочитать">
      </form>
    </div>
    <div>
      <?
      require($_SERVER["DOCUMENT_ROOT"]."/include/ocr/ocr.php");
      /*-------------------------------------------------*/
      //Получение телеыонов в объявлениях организовано по
      //статье по ссылке
      //http://rche.ru/1683_parsing-telefonov-s-avito.html/ 
      /*-------------------------------------------------*/
      if (isset($_POST['url']) && !empty($_POST['url'])) {
        if(get_headers($_POST['url'], 1)){
          echo "Ведется поиск информации по ссылке: ".$_POST['url']."<br>";
          $adOut = file_get_contents($_POST['url']);
          
          $url1quote = strpos($adOut,"'",strpos($adOut,"avito.item.url"));
          $url2quote = strpos($adOut,"'",$url1quote+1);
          $url = "https://www.avito.ru".substr($adOut, $url1quote+1, $url2quote - $url1quote-1);
          echo "url : ".$url."<br>";
          
          $id1quote = strpos($adOut,"'",strpos($adOut,"avito.item.id"));
          $id2quote = strpos($adOut,"'",$id1quote+1);
          $id = substr($adOut, $id1quote+1, $id2quote - $id1quote-1);
          echo "id : ".$id."<br>";
          
          $price1quote = strpos($adOut,"'",strpos($adOut,"avito.item.price"));
          $price2quote = strpos($adOut,"'",$price1quote+1);
          $price = substr($adOut, $price1quote+1, $price2quote - $price1quote-1);
          echo "price : ".$price."<br>";
          
          $latitude1quote = strpos($adOut,'"',strpos($adOut,"data-map-lat"));
          $latitude2quote = strpos($adOut,'"',$latitude1quote+1);
          $latitude = substr($adOut,$latitude1quote+1,$latitude2quote - $latitude1quote-1);
          echo "Широта: ".$latitude."<br>";
          
          $longitude1quote = strpos($adOut,'"',strpos($adOut,"data-map-lon"));
          $longitude2quote = strpos($adOut,'"',$longitude1quote+1);
          $longitude = substr($adOut,$longitude1quote+1,$longitude2quote - $longitude1quote-1);
          echo "Долгота: ".$longitude."<br>";
          
          $phonekey1quote = strpos($adOut,"'",strpos($adOut,"avito.item.phone"));
          $phonekey2quote = strpos($adOut,"'",$phonekey1quote+1);
          $phonekey = substr($adOut,$phonekey1quote+1,$phonekey2quote - $phonekey1quote-1);
          echo "phonekey : ".$phonekey."<br>";
          $hash = phoneDemixer($phonekey,$id);
          echo "hash: ".$hash."<br>";
          if( $curl = curl_init() ) {
            $link = 'https://www.avito.ru/items/phone/'.$id."?pkey=".$hash;
            curl_setopt($curl, CURLOPT_URL, $link);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER,true);
            curl_setopt($curl, CURLOPT_COOKIEJAR, $_SERVER['DOCUMENT_ROOT'].'/avitocookie.txt');
            curl_setopt($curl, CURLOPT_COOKIEFILE, $_SERVER['DOCUMENT_ROOT'].'/avitocookie.txt');
            curl_setopt($curl, CURLOPT_REFERER, $_POST['url']);
            curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1);
            curl_setopt($curl, CURLOPT_USERAGENT, "Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:50.0) Gecko/20100101 Firefox/50.0");
            $pic = curl_exec($curl);
            curl_close($curl);
            echo $link."<br>";
            
            $pic_dump = json_decode($pic, true);
            echo "<img src='".$pic_dump['image64']."'/><br>";
            
            $ifp = fopen("phone_image_".$id.".png", "wb");
            $data = explode(',', $pic_dump['image64']);
            fwrite($ifp, base64_decode($data[1])); 
            fclose($ifp);
            $phoneImage = new crackCapcha("phone_image_".$id.".png"); 
            $phoneNumber = $phoneImage->resolve;
            echo "Номер телефона: ".$phoneNumber."<br>";
          }
                
          
          $dom = new DomDocument();
          $dom->loadHTML($adOut);
          $xpath = new DomXPath($dom);
          $adItem = $xpath->query("/html/body/div[@class='item-view-page-layout item-view-page-layout_content']/div[@class='l-content clearfix']/div[@class='item-view']/div[@class='item-view-content']");
          
          //Имя продавца
          $name_query = $xpath->query("//*[contains(@class, 'seller-info-name')]");
          $name = trim(utf8_decode ($name_query->item(0)->nodeValue));
      
          echo "Имя продавца: ".$name."<br>";
          
          //Профиль на Авито
          //seller-info-avatar-image seller-info-avatar-image-company js-public-profile-link
          //$profile_query = $xpath->query("//*[contains(@class, 'seller-info-avatar-image  js-public-profile-link')]");
          //$profile_query = $xpath->query("//*[contains(@class, 'seller-info-avatar-image seller-info-avatar-image-company js-public-profile-link')]");
          $profile_query = $xpath->query("//*[contains(@class, 'js-public-profile-link')]");
          $profile = "https://www.avito.ru".$profile_query->item(0)->getAttribute('href');

          echo "Ссылка на профиль Авито: ".$profile."<br>";
          
          //Параметры объявления
          $params_query = $xpath->query("//*[contains(@class, 'item-params-list')]");
          $params = array();
          foreach ($params_query->item(0)->childNodes as $param){
            $temp_param = explode(":",trim(utf8_decode ($param->nodeValue)));
            switch ($temp_param[0]){
              case "Количество комнат":
                $params['rooms'] = trim(substr($temp_param[1],1,strpos($temp_param[1],"-")-1));
                break;
              case "Этаж":
                $params['floor'] = trim($temp_param[1]);
                break;
              case "Этажей в доме":
                $params['floors'] = trim($temp_param[1]);
                break;
              case "Тип дома":
                $params['wallstype'] = trim($temp_param[1]);
                break;
              case "Площадь":
                $params['square'] = preg_replace("/[^0-9]/", '', $temp_param[1]);
                break;
            }

          }
          
          echo "<pre>";
          print_r($params);
          echo "</pre>"; 
          
          //Адрес
          
          $address_query = $xpath->query("//*[contains(@class, 'item-map-location')]");
          $address = trim(utf8_decode($address_query->item(0)->nodeValue));
          $address = str_replace("Адрес:","",$address);
          $address = str_replace("Скрыть карту","",$address);
          echo "Адрес объекта: ".$address."<br>";
          
          //Фотографии
          
          $photos_query = $xpath->query("//*[contains(@class, 'gallery-img-frame js-gallery-img-frame')]");
          $photos = array();
          foreach ($photos_query as $photo_query){
            $photos[] = "https:".$photo_query->getAttribute('data-url');
          }
          echo "<pre>";
          print_r(serialize($photos));
          echo "</pre>";
          
          //Описание
          
          $desc_query = $xpath->query("//*[contains(@class, 'item-description-text')]");
          $description = trim(utf8_decode ($desc_query->item(0)->nodeValue));
          echo "Описание объекта: ".$description."<br>";
          
          print_r($adItem->item(0)->childNodes); 
        } else {
          echo "Ссылка: ".$_POST['url']." никуда не ведет.";
        }
      } 
      ?>
    </div>
    <div>
      <?
        if (isset($adOut) && !empty($adOut)){
          echo $adOut;
        }
      ?>
    </div>
  </body>
</html>