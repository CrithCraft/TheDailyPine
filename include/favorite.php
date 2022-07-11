<?php
    try {
        session_start();
        require 'db.php';

        $result = array();
        $result['error'] = "";

        $pdo = new PDO("mysql:host=localhost;dbname=u1153644_legacy;charset=utf8", $user, $pass);

        $php = "SELECT * FROM `favorites` WHERE `user_id` = ? AND `post_id` = ?";
        $data = $pdo->prepare($php);
        $data->bindParam(1, $_SESSION['userId']);
        $data->bindParam(2, $_POST['id']);
        $data->execute();
        $arr_data = array();
        while ($row = $data->fetch(PDO::FETCH_ASSOC))
                $arr_data[count($arr_data)] = $row;
        if (count($arr_data) > 0) {
            $result['error'] = "Данный пост уже был добавлен в избранное.";
        } else {
            $php = "INSERT INTO `favorites`(`user_id`, `post_id`) VALUES (?,?)";
            $data = $pdo->prepare($php);
            $data->bindParam(1, $_SESSION['userId']);
            $data->bindParam(2, $_POST['id']);
            $data->execute();
        }
        $pdo = null;
        echo json_encode($result);
    }
    catch (PDOException $e) {
        // print "Error!: " . $e->getMessage() . "<br/>";
        // $file = fopen("logfile.log", "a+");
        // fwrite($file, "Error!: ".$e->getMessage()."\n");
        // fwrite($file, "In file: ".$e->getFile().", line: ".$e->getLine()."\n");
        // fclose($file);
        // die();
    }
?>

