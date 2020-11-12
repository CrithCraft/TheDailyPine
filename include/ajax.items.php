<?php
    $articles = array(); // массив для результата выборки записей
    
    // простая проверка переменной
    if (isset($_POST['start']) && is_numeric($_POST['start'])){

        $start = $_POST['start'];

        $pdo = new PDO("mysql:host=localhost;dbname=pineforest_db;charset=utf8", $user, $pass);
        $data = $pdo->prepare("SELECT * FROM source_background_data ORDER BY id LIMIT ".$start.", 10");
        // $data->bindValue(1, (int)$start, PDO::PARAM_INT);
        $data->execute();
        
        while ($row = $data->fetch(PDO::FETCH_ASSOC))
            $articles[count($articles)] = $row;
        $pdo = null;
    }
    
    // Превращаем массив статей в json-строку для передачи через Ajax-запрос
    echo json_encode($articles);
?>