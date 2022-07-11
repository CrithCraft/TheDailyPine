<?php
    try {
        session_start();
        require 'db.php';
        $pdo = new PDO("mysql:host=localhost;dbname=u1153644_legacy;charset=utf8", $user, $pass);
        $php = "UPDATE `posts` SET status = 'checked' WHERE `post_id`= ?";
        $data = $pdo->prepare($php);
        $data->bindParam(1, $_POST['id']);
        $data->execute();
        $pdo = null;
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

