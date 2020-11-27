<?php
    session_start();
    require 'db.php';

    // простая проверка переменной - && isset($_POST['type'])
    if (isset($_POST['start']) && is_numeric($_POST['start']) && isset($_POST['type'])){

        $articles = array(); // массив для результата выборки записей
        $start = $_POST['start'];
        if($_POST['type'] == "post") {
            $pdo = new PDO("mysql:host=localhost;dbname=u1153644_default;charset=utf8", $user, $pass);
            $data = $pdo->prepare("SELECT * FROM source_background_data ORDER BY id LIMIT ".$start.", 10");
            // $data->bindValue(1, (int)$start, PDO::PARAM_INT);
            $data->execute();

            while ($row = $data->fetch(PDO::FETCH_ASSOC))
                $articles[count($articles)] = $row;
            $pdo = null;
        }

        if($_POST['type'] == "favorite"){
            $pdo = new PDO("mysql:host=localhost;dbname=u1153644_default;charset=utf8", $user, $pass);
            $data = $pdo->prepare("SELECT *
            FROM source_background_data LEFT JOIN users_favorite
            ON source_background_data.id = users_favorite.post_id
            WHERE users_favorite.user=? LIMIT ".$start.", 10");
            $data->bindParam(1, $_SESSION['userUid'], PDO::PARAM_STR);
            $data->execute();

            while ($row = $data->fetch(PDO::FETCH_ASSOC))
                $articles[count($articles)] = $row;
            $pdo = null;
        }

        if($_POST['type'] == "uploaded") {
            $pdo = new PDO("mysql:host=localhost;dbname=u1153644_default;charset=utf8", $user, $pass);
            $data = $pdo->prepare("SELECT * FROM source_background_data WHERE author=? LIMIT ".$start.", 10");
            $data->bindParam(1, $_SESSION['userUid'], PDO::PARAM_STR);
            $data->execute();

            while ($row = $data->fetch(PDO::FETCH_ASSOC))
                $articles[count($articles)] = $row;
            $pdo = null;
        }

        echo json_encode($articles);
    }

    // Превращаем массив статей в json-строку для передачи через Ajax-запрос

?>
