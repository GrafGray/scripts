<?php

if(!isset($_POST['submit'])){ ?>
    <form action="diagramGenerator.php" method="post">
        <input type="number" placeholder="Value 1" name="value1" max="9999" min="0">
        <input type="number" placeholder="Value 2" name="value2" max="9999" min="0">
        <input type="number" placeholder="Value 3" name="value3" max="9999" min="0">
        <input type="submit" value="submit" name="submit">
    </form> 
<?php
}elseif(isset($_POST['value1']) & isset($_POST['value2']) & isset($_POST['value3'])){
    //Создание картинки и вычисление ее параметров
    //Высота и ширина картинки могут быть переменными, диаграмма отобразится корректно почти при любом размере картинки
    $image = imagecreatetruecolor(1000, 500);
    $imageWidth = imagesx($image);
    $imageHeight = imagesy($image);
    // Установка относительной высоты для каждого столбца диаграммы
    $divider = max($_POST['value1'], $_POST['value2'], $_POST['value3']) / $imageHeight;
    $value1 = calculateY($_POST['value1']);
    $value2 = calculateY($_POST['value2']);
    $value3 = calculateY($_POST['value3']);
    //Настройка цвета
    $imageBackgroundColor = imagecolorallocate($image, 255, 255, 255);
    $firstColumnColor = imagecolorallocate($image, 255, 0, 0);
    $secondColumnColor = imagecolorallocate($image, 0, 255, 0);
    $thirdColumnColor = imagecolorallocate($image, 0, 0, 255);
    $lineColor = imagecolorallocate($image, 0, 0, 0);
    //Отрисовка диаграммы
    imagefill($image, 0, 0, $imageBackgroundColor);
    drawLines(5);
    drawColumn($value1, $firstColumnColor);
    drawColumn($value2, $secondColumnColor);
    drawColumn($value3, $thirdColumnColor);
    //Отправка диаграммы на страницу
    header('Content-type:image/jpeg');
    imagejpeg($image);
}else{
    echo "Not enough data";
}
//Functions
//Функция для правильного позиционирования столбцов при любой ширине картинки
function calculateX(bool $indent = false){
    global $imageWidth;
    static $x = 0;
    if($indent){
        return round($x += $imageWidth / 16);
    }else{
        return round($x += $imageWidth / 4);
    }
}
//Функция для расчета относительной высоты столбца
function calculateY($val){
    global $divider;
    return round($val / $divider * 0.9);
}
//Функция для отрисовки столбцов
function drawColumn($height, $color){
    global $image, $imageHeight;
    imagefilledrectangle($image, calculateX(true), $imageHeight, calculateX(), $imageHeight - $height, $color);
}
//Функция для отрисовки разметки
function drawLines($number){
    global $image, $imageHeight, $imageWidth, $lineColor;
    $lineHeight = $imageHeight / $number;
    imageline($image, $imageWidth/32, 0, $imageWidth/32, $imageHeight, $lineColor);
    for($i = 0; $i < $number; $i++){
        imageline($image, 0, $lineHeight * $i, $imageWidth, $lineHeight * $i, $lineColor);
    }
}