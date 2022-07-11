<?php

    function sql_load($table){
        try {
            require 'db.php';
            $pdo = new PDO("mysql:host=localhost;dbname=u1153644_legacy;charset=utf8", $user, $pass);
            $data = $pdo->prepare("SELECT * FROM ".$table);
            $data->execute();

            $arr_data = array();
            while ($row = $data->fetch(PDO::FETCH_ASSOC))
                $arr_data[count($arr_data)] = $row;
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
    }

    try {
        $search = $_POST['search'];

        require 'db.php';
        $pdo = new PDO("mysql:host=localhost;dbname=u1153644_legacy;charset=utf8", $user, $pass);
        $data = $pdo->prepare("SELECT posts.* FROM posts, hashtags, hashtags_link WHERE posts.post_id=hashtags_link.post_id and hashtags_link.hashtag_id = hashtags.hashtag_id and hashtags.name = ? and posts.status = 'checked'");
        $data->bindParam(1, $search);
        $data->execute();

        $arPosts = array();
        while ($row = $data->fetch(PDO::FETCH_ASSOC)) {
            $row["hashtag"] = hashtagDraw($row["post_id"], 2);
            $row["author"] = getAuthorName($row["user_id"])[0]["name"];
            $arPosts[count($arPosts)] = $row;
        }
        
        $pdo = null;
        echo json_encode($arPosts);
    }
    catch (PDOException $e) {
        //Оставлять логи опасно
        // print "Error!: " . $e->getMessage() . "<br/>";
        // $file = fopen("search_logfile.log", "a+");
        // fwrite($file, "Error!: ".$e->getMessage()."\n");
        // fwrite($file, "In file: ".$e->getFile().", line: ".$e->getLine()."\n");
        // fclose($file);
        // return null;
        // die();
    }
?>