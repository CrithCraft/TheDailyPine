<?php
    try {   // Пробуем отправить запрос
        // Запускаем сессию
        session_start();
        // подключаем файл с данными пользователя БД
        require 'db.php';
        // Создаем соединение с mysql
        $pdo = new PDO("mysql:host=localhost;dbname=u1153644_legacy;charset=utf8", $user, $pass);
        // подготавливаем запрос
        $php = "UPDATE `users` SET `status`=?,`avatar`=?,`background`=? WHERE `user_id` = ?";
        // отправляем запрос
        $data = $pdo->prepare($php);
        // Создаем массив и заполняем его полученными данными
        $data->bindParam(1, $_POST['desc']);
        $data->bindParam(2, $_POST['avatar']);
        $data->bindParam(3, $_POST['back']);
        $data->bindParam(4, $_SESSION['userId']);
        // отправляем запрос
        $data->execute();
        // Закрываем соединение
        $pdo = null;
        // Обновляем данные супермасива сессии
        $_SESSION['status'] = $_POST['desc'];
        $_SESSION['avatar'] = $_POST['avatar'];
        $_SESSION['background'] = $_POST['back'];
    } // Ловим и выводим ошибку при ее возникновении
    catch (PDOException $e) {
        // Оставлять логи опасно - сохраняем информацию об ошибке
        // print "Error!: " . $e->getMessage() . "<br/>";
        $file = fopen("logfile.log", "a+");
        fwrite($file, "Error!: ".$e->getMessage()."\n");
        fwrite($file, "In file: ".$e->getFile().", line: ".$e->getLine()."\n");
        fclose($file);
        die();
    }
?>