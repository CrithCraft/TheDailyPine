<?php
    require 'db.php';
    $pdo = new PDO("mysql:host=localhost;dbname=u1153644_legacy;charset=utf8", $user, $pass);
    $data = $pdo->prepare("SELECT roles.role, users.name, users.avatar, users.email, users.status FROM users, roles where users.name=? and roles.user_id = users.user_id");
    $data->bindParam(1,$_POST["user"]);
    $data->execute();

    $arr_data = array();
    while ($row = $data->fetch(PDO::FETCH_ASSOC))
        $arr_data[count($arr_data)] = $row;
    
    $pdo = null;

    echo json_encode($arr_data);
?>