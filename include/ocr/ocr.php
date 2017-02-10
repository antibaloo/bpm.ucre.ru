
<?
/*----------------------------------------------------------------------------*/
//Класс для распознавания телефона в объявлениях Авито взят из статьи по ссылке: 
//http://lifeexample.ru/php-primeryi-skriptov/raspoznavanie-kapchi-php.html
//Модифицирован ассоциативный массив соответствий для распознавания символов 
//нужного размера
/*----------------------------------------------------------------------------*/
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