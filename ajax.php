<!doctype html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Database search</title>
    <script src="jquery.js"></script>
    <script>
        //jquery код
        //Анонимная функция, которая обрабатывает скрипт только после полной загрузки html и css
        $(function () {
            //Проверка текстого поля на наличие запроса
            $("form #submit").on("click", function () {
                if ($.trim($("#searchArea").val()) === '') {
                    alert('Пустая форма');
                    return false;
                }
                //AJAX запрос методом POST
                $.ajax({
                    url: "handler.php",
                    method: "post",
                    data: {searchString: $("#searchArea").val()}
                }).done(function (data) {  //Вывод иформации, полученой из php скрипта
                    $("#results").html(data);
                });
            });
        });
    </script>
</head>
<body>
    <form onsubmit="return false;">
        <input type="search" id="searchArea" placeholder="Username">
        <input type="submit" value="Search" id="submit">
    </form>
    <div id="results"></div>
</body>
</html>


<?php //handler.php
//Обработчик. Совместил файлы для простоты просмотра
//Для работы скрипта необходимо перенести весь PHP код в отдельный файл с названием handler.php

//Подключаемся к БД
require_once ('PAGES/db_connect.php');
//Разбитие запроса
$strings = explode('|', $_POST['searchString']);
//Подготовка запроса
$query = "SELECT * FROM users WHERE uname LIKE :str";
$str = $pdo->prepare($query);
try {
    foreach ($strings as $key => $value) {
        //Очистка и проверка строки
        $strings[$key] = htmlentities(trim($strings[$key]));
        if ($strings[$key] === '') continue;
        //Выполнение запроса и вывод данных
        $str->execute(['str' => '%'.$strings[$key].'%']);
        while ($string = $str->fetch()) {
            echo "<p>" . $string['uname'] . "</p>";
        }
    }
} catch (PDOException $e){
    echo "Ошибка подключения: ".$e->getMessage();
}