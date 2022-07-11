<?php
    function sql_load($table){
        try {
            require 'db.php';
            $pdo = new PDO("mysql:host=localhost;dbname=u1153644_legacy;charset=utf8", $user, $pass);
            $data = $pdo->prepare("SELECT * FROM ".$table);
            $data->execute();

            $arr_data = array();
            while ($row = $data->fetch(PDO::FETCH_ASSOC))
                $arr_data[$row['post_id']] = $row;
            return $arr_data;
            $pdo = null;
        }
        catch (PDOException $e) {
            // Оставлять логи опасно
            // print "Error!: " . $e->getMessage() . "<br/>";
            // $file = fopen("logfile.log", "a+");
            // fwrite($file, "Error!: ".$e->getMessage()."\n");
            // fwrite($file, "In file: ".$e->getFile().", line: ".$e->getLine()."\n");
            // fclose($file);
            // return null;
            // die();
        }
    }

    try {
        session_start();
        require 'db.php';
        
        $type_db = "posts";
        $type = "photo";

        switch ($type) {
            case "photo": $type_db = "posts"; break;
            case "art": $type_db = "posts"; break;
        }
        $data_photos = sql_load($type_db);

        $result = array();
        $result['error'] = "";

        if (isset($_SESSION['userId'])){
            if (($data_photos[$_POST['id']]['user_id'] == $_SESSION['userId']) || ($_SESSION['role'] == "admin") || ($_SESSION['role'] == "moderator")) {
                $pdo = new PDO("mysql:host=localhost;dbname=u1153644_legacy;charset=utf8", $user, $pass);
                $STH = $pdo->prepare("DELETE FROM posts WHERE `post_id` = ?");
                $STH->bindParam('1', $_POST['id']);
                $STH->execute();
                $pdo = null;
                unlink("images/".$type_db."/".$data_photos[$_POST['id']]['img']);
            }
            else $result['error'] = "Вы не являетесь владельцем данного поста.";
        }
        else $result['error'] = "Для выполнения данного действия требуется регистрация.";
        $pdo = null;

        echo json_encode($result);
    }
    catch (PDOException $e) {
        // Оставлять логи опасно
        // print "Error!: " . $e->getMessage() . "<br/>";
        $file = fopen("logfile.log", "a+");
        fwrite($file, "Error!: ".$e->getMessage()."\n");
        fwrite($file, "In file: ".$e->getFile().", line: ".$e->getLine()."\n");
        fclose($file);
        die();
    }
?>