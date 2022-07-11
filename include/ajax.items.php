<?php
    //запускаем сессию
    session_start();
    // подключаем файл с данными пользователя БД
    require 'db.php';

    // Функция загрузки всех данных в таблицу и сбор их в один массив
    function sql_load($table){
        try {
            // подключаем файл с данными пользователя БД
            require 'db.php';
            // Создаем соединение с mysql
            $pdo = new PDO("mysql:host=localhost;dbname=u1153644_legacy;charset=utf8", $user, $pass);
            // подготавливаем запрос
            $data = $pdo->prepare("SELECT * FROM ".$table);
            // отправляем запрос
            $data->execute();
            // Создаем массив и заполняем его полученными данными
            $arr_data = array();
            while ($row = $data->fetch(PDO::FETCH_ASSOC))
                $arr_data[count($arr_data)] = $row;
            // Закрываем соединение
            $pdo = null;
            // Возращаем массив
            return $arr_data;
        }
        catch (PDOException $e) {
            // Оставлять логи опасно - сохраняем информацию об ошибке
            // print "Error!: " . $e->getMessage() . "<br/>";
            // $file = fopen("logfile.log", "a+");
            // fwrite($file, "Error!: ".$e->getMessage()."\n");
            // fwrite($file, "In file: ".$e->getFile().", line: ".$e->getLine()."\n");
            // fclose($file);
            // return null;
            // die();
        }
    }
    // Функция для генерации строки хештегов в виде html
    function hashtagDraw($id,$n){
        // Готовим переменную для строки хештегов
        $output = "";
        // подключаем файл с данными пользователя БД
        require 'db.php';
        // Создаем соединение с mysql
        $pdo = new PDO("mysql:host=localhost;dbname=u1153644_legacy;charset=utf8", $user, $pass);
        // подготавливаем запрос
        $data = $pdo->prepare("SELECT * FROM hashtags WHERE hashtag_id IN (SELECT hashtag_id FROM hashtags_link where post_id=?) LIMIT 2");
        $data->bindParam(1,$id);
        // отправляем запрос
        $data->execute();
        // Генерируем строку хештегов в виде html
        while ($row = $data->fetch(PDO::FETCH_ASSOC)) {
            $output = $output.'<div onClick="searchSelect('."'".$row['name']."'".')" style = "background-color: '.$row['color'].';" class = "hashtag">'.$row['name'].'</div>';
        }
        // Закрываем соединение
        $pdo = null;
        // Возращаем массив
        return $output;
    }

    // Функция для получения имени автора поста
    function getAuthorName($id) {
        // подключаем файл с данными пользователя БД
        require 'db.php';
        // Создаем соединение с mysql
        $pdo = new PDO("mysql:host=localhost;dbname=u1153644_legacy;charset=utf8", $user, $pass);
        // подготавливаем запрос
        $data = $pdo->prepare("SELECT `name` FROM `users` WHERE `user_id` = ?");
        $data->bindParam(1, $id);
        // отправляем запрос
        $data->execute();
        // Создаем массив и заполняем его полученными данными
        $arr_data = array();
        while ($row = $data->fetch(PDO::FETCH_ASSOC))
            $arr_data[count($arr_data)] = $row;
        // Закрываем соединение
        $pdo = null;
        // Возращаем массив
        return $arr_data;
    }

    // простая проверка переменной - && isset($_POST['type'])
    if (isset($_POST['start']) && is_numeric($_POST['start']) && isset($_POST['type'])){
        // массив для результата выборки записей
        $articles = array(); 
        // Точка отсчета для загрузки постов
        $start = $_POST['start'];
        // Если тип пост, то передаем Ajax значения из БД данные о постах
        if($_POST['type'] == "post") {
            $pdo = new PDO("mysql:host=localhost;dbname=u1153644_legacy;charset=utf8", $user, $pass);
            $data = $pdo->prepare("SELECT * FROM posts WHERE status = 'checked' ORDER BY post_id LIMIT ".$start.", 10");
            $data->execute();
            while ($row = $data->fetch(PDO::FETCH_ASSOC)){
                // Disable for a while
                $row["hashtag"] = hashtagDraw($row["post_id"], 2);
                $row["author"] = getAuthorName($row["user_id"])[0]["name"];
                $articles[count($articles)] = $row;
            }
            $pdo = null;
        }
        // Если тип избраное, то передаем Ajax значения из БД данные об избранном
        if($_POST['type'] == "favorite"){
            $pdo = new PDO("mysql:host=localhost;dbname=u1153644_legacy;charset=utf8", $user, $pass);
            $data = $pdo->prepare("SELECT *
            FROM posts LEFT JOIN favorites
            ON posts.post_id = favorites.post_id
            WHERE favorites.user_id=? AND posts.status = 'checked' LIMIT ".$start.", 10");
            $data->bindParam(1, $_SESSION['userId'], PDO::PARAM_STR);
            $data->execute();
            while ($row = $data->fetch(PDO::FETCH_ASSOC)){
                $row["hashtag"] = hashtagDraw($row["post_id"], 2);
                $row["author"] = getAuthorName($row["user_id"])[0]["name"];
                $articles[count($articles)] = $row;
                
            }
            $pdo = null;
        }

        // Если тип загруженное, то передаем Ajax значения из БД данные о загруженном
        if($_POST['type'] == "uploaded") {
            $pdo = new PDO("mysql:host=localhost;dbname=u1153644_legacy;charset=utf8", $user, $pass);
            $data = $pdo->prepare("SELECT * FROM `posts` WHERE  status = 'checked' AND `user_id` = ? LIMIT ".$start.", 10");
            $data->bindParam(1, $_SESSION['userId'], PDO::PARAM_STR);
            $data->execute();
            while ($row = $data->fetch(PDO::FETCH_ASSOC)){
                $row["hashtag"] = hashtagDraw($row["post_id"], 2);
                $row["author"] = getAuthorName($row["user_id"])[0]["name"];
                $articles[count($articles)] = $row;
            }
            $pdo = null;
        }

        // Превращаем массив в json-строку для передачи через Ajax-запрос
        echo json_encode($articles);
    }


?>
