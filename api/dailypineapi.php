<?php

class dailyPineAPI {

    private function sql_load($table, $start, $col){
        try {
            if ($start == NULL) $start=0;
            if ($col == NULL) $col=0;

            require 'db.php';

            $pdo = new PDO("mysql:host=localhost;dbname=u1153644_default;charset=utf8", $user, $pass);
            $data = $pdo->prepare("SELECT * FROM ".strip_tags($table)." LIMIT ?, ?");
            $data->bindParam(1, $start, PDO::PARAM_INT);
            $data->bindParam(2, $col, PDO::PARAM_INT);
            $data->execute();

            $arr_data = array();
            while ($row = $data->fetch(PDO::FETCH_ASSOC))
                $arr_data[count($arr_data)] = $row;
            return $arr_data;
            $pdo = null;
        }
        catch (PDOException $e) {
            return "error";
        }
    }

    function createDefaultJson() {
        $retObject = json_decode('{}');
        return $retObject;
    }

    function loadPage($parameters) {
        $retJSON = $this->createDefaultJson();
        $retJSON->data = $this->sql_load("source_background_data", ($parameters->page-1)*10, 10);
        return $retJSON;
    }

    function loadUser($parameters) {
        $pdo = new PDO("mysql:host=localhost;dbname=u1153644_default;charset=utf8", $user, $pass);
        $data = $pdo->prepare("SELECT uidUsers,	emailUsers FROM users WHERE api_key = ?");
        $data->bindParam(1, $parameters->api_key, PDO::PARAM_STR);
        $data->execute();
        while ($row = $data->fetch(PDO::FETCH_ASSOC))
            $result = [
                "user" => $row["uidUsers"],
                "email" => $row["emailUsers"],
            ];
        $pdo = null;

        $retJSON = $this->createDefaultJson();
        $retJSON->data = $result;
        return $retJSON;
    }

}

?>
