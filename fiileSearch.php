<?php
if(!isset($_POST["submit"])){ ?>
    <form enctype="multipart/form-data" action="fiileSearch.php" method="post">
        <input type="hidden" name="MAX_FILE_SIZE" value="30000"/>
        <input type="file" name="inputFile">
        <select name="sensitive[]">
            <option disabled selected>Регистр</option>
            <option value="sens">Чувствительный</option>
            <option value="insens">Нечувствительный</option>
        </select>
        <input type="text" placeholde="Искомые слова" name="inputWord">
        <input type="submit" value="Искать" name="submit">
    </form>
<?php
}else{
    //Получение данных из формы
    $inputFile = stripslashes($_FILES["inputFile"]["name"]);
    $inputWord = htmlentities(trim($_POST["inputWord"]));
    $insens = $_POST[sensitive][0] == 'insens' ? true : false ;
    // Перевод запращиваемого слова в нижний регистр в случае, если задан параметр insens
    if($insens) $inputWord = mb_strtolower($inputWord, 'UTF-8');
    //Проверка данных
    if(isset($inputFile) && isset($inputWord)){
        if(checkExtеnsion($inputFile, 'txt')){
            //Чтение файла в массив
            $file = file($_FILES['inputFile']['tmp_name']);
            foreach ($file as $key => $value) {
                htmlentities($file[$key]);
                // Перевод строки в нижний регистр в случае, если задан параметр insens
                if($insens) $file[$key] = mb_strtolower($file[$key], 'UTF-8');
                for($pos = 0; true; $pos++){
                    //Запоминание предыдущего вхожени
                    $pos = strpos($file[$key], $inputWord, $pos);
                    //Если вхождения больше не найдены цикл прерывается и управление возвращяется к циклу foreach
                    if ($pos === false) break;
                    //Первращение чисел из программистских в человечиские (начало с еденицы, а не с нуля)
                    echo "В строке " . ($key + 1) . " на позиции " . ($pos + 1) . "<br>";
                }
            }
        }else{
            echo "Неверный тип файла";
        }
    }else{
        echo "Данные не получены";
    }
}

//functions
//Функця для проверки расширения файла
function checkExtеnsion($f, $ext){
    if($ext == pathinfo($f, PATHINFO_EXTENSION)){
        return true;
    }else{
        return false;
    }
}