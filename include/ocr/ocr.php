
<?
/*----------------------------------------------------------------------------*/
//Функция русификации форматного вывода date()
//Функция расчета hash для ссылки на png с телефоном продавца на Авито из статьи
//по ссылке: http://rche.ru/1683_parsing-telefonov-s-avito.html
//Класс для распознавания телефона в объявлениях Авито взят из статьи по ссылке: 
//http://lifeexample.ru/php-primeryi-skriptov/raspoznavanie-kapchi-php.html
//Модифицирован ассоциативный массив соответствий для распознавания символов 
//нужного размера
/*----------------------------------------------------------------------------*/
function rus_date() {
  $translate = array( "am" => "дп", "pm" => "пп","AM" => "ДП","PM" => "ПП","Monday" => "Понедельник","Mon" => "Пн","Tuesday" => "Вторник", "Tue" => "Вт", "Wednesday" => "Среда", "Wed" => "Ср",
                     "Thursday" => "Четверг", "Thu" => "Чт", "Friday" => "Пятница","Fri" => "Пт",    "Saturday" => "Суббота", "Sat" => "Сб","Sunday" => "Воскресенье","Sun" => "Вс","January" => "Января",
                     "Jan" => "Янв","February" => "Февраля","Feb" => "Фев","March" => "Марта","Mar" => "Мар","April" => "Апреля","Apr" => "Апр","May" => "Мая","May" => "Мая","June" => "Июня",
                     "Jun" => "Июн","July" => "Июля","Jul" => "Июл","August" => "Августа","Aug" => "Авг","September" => "Сентября","Sep" => "Сен","October" => "Октября","Oct" => "Окт",
                     "November" => "Ноября","Nov" => "Ноя","December" => "Декабря","Dec" => "Дек","st" => "ое","nd" => "ое","rd" => "е","th" => "ое");
  if (func_num_args() > 1) {
    $timestamp = func_get_arg(1);
    return strtr(date(func_get_arg(0), $timestamp), $translate);
  } else {
    return strtr(date(func_get_arg(0)), $translate);
  }
}
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

class crackCapcha {
  public $im;
  public $binaryMartix;
  public $assocNumber = array(
    '358' => 0,//
    '162' => 1,//
    '331' => 2,//
    '328' => 3,//
    '285' => 4,
    '336' => 5,//
    '396' => 6,//
    '216' => 7,//
    '403' => 8,//
    '383' => 9 //
  );
  public $resolve;

  function __construct($path) {
    $this->im = @imagecreatefrompng($path);
    if (!$this->im){ 
      return false;
    }
    
    $this->binaryMartix =  $this->imageToMatrix($this->im, true);
    //$this->printMatrix($this->binaryMartix); //Вывод полученной матрицы
    $explode =  $this->explodeMatrix($this->binaryMartix);
    $this->resolve = '';
    //print_r($explode); //Для отладки и настройки ассоциативного массива соответствий
    foreach ($explode as $number) {
       $this->resolve .= $this->assocNumber[$number];
    } 
  }


  function explodeMatrix($binaryMartix) {
    $temp = array();

    // сложение столбцов для выявления интервалов
    for ($i = 0; $i < count($binaryMartix); $i++) {
      $sum = 0;
      for ($j = 0; $j < count($binaryMartix[0]); $j++) {
        $sum += $binaryMartix[$i][$j];
      }
      $temp[] = $sum ? 1 : 0;
    }

    // вычисление интервалов по полученной строке
    $start = false;
    $countPart = 0;
    $arrayInterval = array();
    foreach ($temp as $k => $v) {

      if ($v == 1 && !$start) {
        $arrayInterval[$countPart]['start'] = $k;
        $start = true;
      }

      if ($v == 0 && $start) {
        $arrayInterval[$countPart]['end'] = $k - 1;
        $start = false;
        $countPart++;
      }
    }

    // сложение всех единиц в полученных интервалах столбцов
    foreach ($arrayInterval as $interval) {
      $sum = 0;
      for ($i = 0; $i < count($binaryMartix); $i++) {
        for ($j = 0; $j < count($binaryMartix[0]); $j++) {
          if ($i >= $interval['start'] && $i <= $interval['end']) {
            $sum += $binaryMartix[$i][$j];
          }
        }
      }
      $result[] = $sum;
    }

    return $result;
  }

  /**
   * Конвертация рисунка в бинарную матрицу
   * Все пиксели отличные от фона получают значение 1
   * @param imagecreatefrompng $im - картинка в формате PNG
   * @param bool $rotate - горизонтальная или вертикальная матрица 
   */
  function imageToMatrix($im, $rotate = false) {
    $height = imagesy($im);
    $width = imagesx($im);

    if ($rotate) {
      $height = imagesx($im);
      $width = imagesy($im);
    }

    $background = 0;
    for ($i = 0; $i < $height; $i++)
      for ($j = 0; $j < $width; $j++) {

        if ($rotate) {
          $rgb = imagecolorat($im, $i, $j);
        } else {
          $rgb = imagecolorat($im, $j, $i);
        }

        //получаем индексы цвета RGB 
        list($r, $g, $b) = array_values(imageColorsForIndex($im, $rgb));

        //вычисляем индекс красного, для фона изображения
        if ($i == 0 && $j == 0) {
          $background = $r;
        }

        // если цвет пикселя не равен фоновому заполняем матрицу единицей
        $binary[$i][$j] = ($r == $background) ? 0 : 1;
      }
    return $binary;
  }

  /**
   * Выводит матрицу на экран
   * @param type $binaryMartix
   */
  function printMatrix($binaryMartix) {
    for ($i = 0; $i < count($binaryMartix); $i++) {
      echo "<br/>";
      for ($j = 0; $j < count($binaryMartix[0]); $j++) {
        echo $binaryMartix[$i][$j]." ";
      }
    }
  }

}
?>