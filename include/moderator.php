<?php
    session_start();
    require 'db.php';

    function hashtagDraw($id,$n){
        $output = "";
        require 'db.php';
        $pdo = new PDO("mysql:host=localhost;dbname=u1153644_legacy;charset=utf8", $user, $pass);
        $data = $pdo->prepare("SELECT * FROM hashtags WHERE hashtag_id IN (SELECT hashtag_id FROM hashtags_link where post_id=?) LIMIT 2");
        $data->bindParam(1,$id);
        $data->execute();

        $arr_data = array();
        while ($row = $data->fetch(PDO::FETCH_ASSOC)) {
            $output = $output.'<div onClick="searchSelect('."'".$row['name']."'".')" style = "background-color: '.$row['color'].';" class = "hashtag">'.$row['name'].'</div>';
        }
        $pdo = null;
        return $output;
    }

    function getAuthorName($id) {
        require 'db.php';
        $pdo = new PDO("mysql:host=localhost;dbname=u1153644_legacy;charset=utf8", $user, $pass);
        $data = $pdo->prepare("SELECT `name` FROM `users` WHERE `user_id` = ?");
        $data->bindParam(1, $id);
        $data->execute();

        $arr_data = array();
        while ($row = $data->fetch(PDO::FETCH_ASSOC))
            $arr_data[count($arr_data)] = $row;
        return $arr_data;
        $pdo = null;
        return $arr_data;
    }

    $articles = array(); // массив для результата выборки записей
    $pdo = new PDO("mysql:host=localhost;dbname=u1153644_legacy;charset=utf8", $user, $pass);
    $data = $pdo->prepare("SELECT * FROM posts WHERE status = 'verify'");
    $data->execute();

    while ($row = $data->fetch(PDO::FETCH_ASSOC)){
        // Disable for a while
        $row["hashtag"] = hashtagDraw($row["post_id"], 2);
        $row["author"] = getAuthorName($row["user_id"])[0]["name"];
        $articles[count($articles)] = $row;
    }
    $pdo = null;
    

    // Превращаем массив в json-строку для передачи через Ajax-запрос
    echo json_encode($articles);



?>
