<?php
	try {
        require 'db.php';

    	$pdo = new PDO("mysql:host=localhost;dbname=u1153644_legacy;charset=utf8", $user, $pass);
    	$data = $pdo->prepare("SELECT posts.* FROM posts, hashtags, hashtags_link WHERE posts.post_id=hashtags_link.post_id and hashtags_link.hashtag_id = hashtags.hashtag_id and hashtags.name = '#animal'");
    	$data->execute();

    	$arPosts = array();
    	while ($row = $data->fetch(PDO::FETCH_ASSOC))
        	$arPosts[count($arPosts)] = $row;
        var_dump($arPosts);
        $pdo = null;
    }
    catch (PDOException $e) {
        // Оставлять логи опасно
        print "Error!: " . $e->getMessage() . "<br/>";
        $file = fopen("logfile.log", "a+");
        fwrite($file, "Error!: ".$e->getMessage()."\n");
        fwrite($file, "In file: ".$e->getFile().", line: ".$e->getLine()."\n");
        fclose($file);
        die();
    }
?>