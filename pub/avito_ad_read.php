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
      function phoneDemixer($key,$id) {
        preg_match_all("/[\da-f]+/",$key,$pre);
        $pre = $id%2==0 ? array_reverse($pre[0]) : $pre[0];
        $mixed = join('',$pre);
        $s = strlen($mixed);
        $r='';
        for($k=0; $k<$s; ++$k) {
          if ($k%3==0) {
            $r .= substr($mixed,$k,1);
          }
        }
        return $r;
      }
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
            //echo "<img src='".$pic_dump['image64']."'/><br>";
            
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