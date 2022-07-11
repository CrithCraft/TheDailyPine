<?php
    session_start();
    if ($_SESSION["role"]=="admin"){
        try {
            if ($_POST["table"] == "photos"){
                $table = "posts";
            }
            if ($_POST["table"] == "users"){
                $table = "users";
            }
            if ($_POST["table"] == "hashtags"){
                $table = "hashtags";
            }
            if ($_POST["table"] == "favorite"){
                $table = "favorites";
            }
            
            require 'db.php';
            $pdo = new PDO("mysql:host=localhost;dbname=u1153644_legacy;charset=utf8", $user, $pass);
    
            $data = $pdo->prepare("SELECT * FROM ".$table);
            $data->execute();
    
            $arr_data = array();
            while ($row = $data->fetch(PDO::FETCH_ASSOC))
                $arr_data[count($arr_data)] = $row;
            $pdo = null;
    
            echo json_encode($arr_data);
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
?>