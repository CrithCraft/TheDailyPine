<style>
    html {
        font-family: 'Source Sans Pro', sans-serif;
    }
</style>
<?php
    // Поля навигации
    $nav_lables = ['home', 'photo', 'art'];
    $nav_lables_name = ['Главная', 'Фото', 'Арты'];

    // Вход в учетную запись
    function login_db() {
        $pdo = new PDO("mysql:host=localhost;dbname=pineforest_db;charset=utf8", 'fa052862', 'Kolombiana026V');
        $mailuid = $_POST['mailuid'];
        $password = $_POST['pwd'];

        if (empty($mailuid) || empty($password)){
            die('You forgot put something in labels');
            exit();
        }
        else {
            $data = $pdo->prepare("SELECT * FROM users WHERE uidUsers=? OR emailUsers=?");
            $data->bindParam(1, $mailuid, PDO::PARAM_STR);
            $data->bindParam(2, $mailuid, PDO::PARAM_STR);
            $data->execute();
            
            $arr_data = array();
            while ($row = $data->fetch(PDO::FETCH_ASSOC)){
                $arr_data[count($arr_data)] = $row;
            }
            
            
            if (count($arr_data) < 0) {
                die('<div style="background-color:white; grid-area:1/1/5/6; margin: auto 0;"><p align="center" style="font-size: 16px; color: gray;">Нет пользователей с таким никнеймом или почтой.</br></br><a href="http://pineart.fo.ua/singin.php">Вернуться назад.</a></p></div>');
                exit();
            }
            else {
                $pwdCheck = password_verify($password, $arr_data[0]['pwdUsers']);
                if ($pwdCheck == false) {
                    die('<div style="background-color:white;  display: flex; justify-content: center; align-items: center; height: 100%;"><p align="center" style="font-size: 16px; color: gray;">Неправильный пароль. </br></br><a href="http://pineart.fo.ua/singin.php">Вернуться на предыдущую страницу.</a></p></div>');
                    exit();
                }
                else if($pwdCheck == true) {
                    session_start();
                    $_SESSION['userId'] = $arr_data[0]['idUsers'];
                    $_SESSION['userUid'] = $arr_data[0]['uidUsers'];

                    header("Location: ../Photos.php?login=success");

                    $content = "User has login";
                    $fp = fopen($_SERVER['DOCUMENT_ROOT'] . "/REGISTER_LOG.txt","a+");
                    fwrite($fp,$content);
                    fclose($fp);
                    exit();
                }
                else {
                    die('<div style="background-color:white;  display: flex; justify-content: center; align-items: center; height: 100%;"><p align="center" style="font-size: 16px; color: gray;">Неправильный пароль. </br></br><a href="http://pineart.fo.ua/singin.php">Вернуться на предыдущую страницу.</a></p></div>');
                    exit();
                }
            }

            $pdo = null;
        }
            
    }

    // Выход из учетной записи
    function logout_db() {
        session_unset();
        session_destroy();
        $content = "four";
        $fp = fopen($_SERVER['DOCUMENT_ROOT'] . "/REGISTER_LOG.txt","a+");
        fwrite($fp,$content);
        fclose($fp);
    }

    // Регистрация учетной записи
    function singup_db(){
        $pdo = new PDO("mysql:host=localhost;dbname=pineforest_db;charset=utf8", 'fa052862', 'Kolombiana026V');
        
        $username = $_POST['uid'];
        $email = $_POST['mail'];
        $password = $_POST['pwd'];
        $passwordRepeat = $_POST['pwd-repeat'];
        
        if (empty($username) || empty($email) || empty($password) || empty($passwordRepeat)){
            $pdo = null;
            die('<div style="background-color:white; display: flex; justify-content: center; align-items: center; height: 100%;"><p align="center" style="font-size: 16px; color: gray;">Заполните все поля!. </br></br><a href="http://pineart.fo.ua/singin.php">Вернуться на предыдущую страницу</a></p></div>');
            exit();
        } 
        else {
            $data = $pdo->prepare("SELECT uidUsers FROM users WHERE uidUsers=?");
            $data->bindParam(1, $username, PDO::PARAM_STR);
            $data->execute();
            $arr_data = array();
            while ($row = $data->fetch(PDO::FETCH_ASSOC))
                $arr_data[count($arr_data)] = $row;
            
            if (count($arr_data) > 0) {
                $pdo = null;
                die('<div style="background-color:white;  display: flex; justify-content: center; align-items: center; height: 100%;"><p align="center" style="font-size: 16px; color: gray;">Никнейм уже занят! </br></br><a href="http://pineart.fo.ua/singin.php">Вернуться на предыдущую страницу.</a></p></div>');
                exit();
            }
            else {
                $hashedPwd = password_hash($password, PASSWORD_DEFAULT);

                $data = $pdo->prepare("INSERT INTO users (uidUsers, emailUsers, pwdUsers) VALUES (?,?,?)");
                $data->bindParam(1, $username, PDO::PARAM_STR);
                $data->bindParam(2, $email, PDO::PARAM_STR);
                $data->bindParam(3, $hashedPwd, PDO::PARAM_STR);
                $data->execute();
                $pdo = null;
                die('<div style="background-color:white; display: flex; justify-content: center; align-items: center; height: 100%;"><p align="center" style="font-size: 16px; color: gray;">Вы успешно зарегистрированы! </br></br><a href="http://pineart.fo.ua/singin.php">Вернуться на предыдущую страницу.</a></p></div>');
                exit();
            }
        }
    }

    // Default функция возращает все данные из определенной таблицы базы базы данных в виде массива
    function sql_load($table){
        try {
            $pdo = new PDO("mysql:host=localhost;dbname=pineforest_db;charset=utf8", 'fa052862', 'Kolombiana026V');
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

    // Генерация поста
    function renderBigPhotoBlockPage($page,$n, $type){
        if ($type == "photo"){
            $path = "images/backgrounds/";
            $photo_data = sql_load("source_background_data");
        }
        if ($type == "art"){
            $path = "images/arts/";
            $photo_data = sql_load("source_art_data");
        }

        if (($page*$n) > count($photo_data)) {
            $target = count($photo_data);
        }
        else {
            $target = $page*$n;
        }
        for ($i = (($page-1)*$n); $i < $target; $i++){
            echo '<div class = "big-photo-block">
            <div class = "photo-block-left">
                <div class="photo-block-header">
                    <div class = "photo-block-header-text">
                        <img src="images/photo_icon.png" width="22px" height="22px" alt="icon">
                        <h3>'.$photo_data[$i]['name'].'</h3>
                        <div class = "author">'.$photo_data[$i]['author'].'</div>
                        <div class = "date">'.$photo_data[$i]['date'].'</div>
                    </div>
                    <div class="photo-block-header-download">
                        <a href="'.$path.$photo_data[$i]['image'].'">
                            <img src="images/download_icon.png" width="20px" height="22px" alt="icon">
                        </a>
                        <div style="background-image: url(images/trash.png);" class = "trash">
                                <button name="trash" style="opacity: 0; width: 100%; height: 100%" onclick="showRemoveField('.$i.')"></button>
                        </div>
                    </div>
                </div>
                <a href="javascript:void(0);" style="display: block;" class="ios_hover">
                <div style="background-image: url('.$path.$photo_data[$i]['image'].');" class="preview-photo"> 
                    <button class="preview-button" onclick="showPreviewField('."'".$path.$photo_data[$i]['image']."'".')"></button>
                    <img src="'.$path.$photo_data[$i]['image'].'" width="100%" style="visibility: hidden;" class="preview-photo-img" />
                </div>
                </a>
            </div>
            <div class="vr"></div>
            <div class = "photo-block-right">
                <div class = "photo-block-right-header">
                    <img src="images/desc_icon.png" width="24px" height="24px" alt="icon">
                    <h3>Описание</h3>
                    <div class="description">'.$photo_data[$i]['description'].'</div>
                    <div class="hashtag-label">';
                    hashtagDraw($photo_data[$i]['hashtag'],3);
                    echo '</div>
                    <div class="location-label">
                        <img src="images/location_icon.png" width="20px" height="20px" alt="icon">
                        <div class="location-text">'.$photo_data[$i]['location'].'</div>
                    </div>
                </div>
            </div>
        </div> ';
        }
    }

    // Генерация случайных хешетгов
    function renderHashtag($a){
        $hashtag_data = sql_load("source_hashtag_data");
        $i = 0; // После заполнения таблиц БД, убрать все это! Заменить k на i.
        $digits = 0;
        $times = 0;
        echo '<form action="Search.php" method="post" class="hashtag-form">';
        echo '<div class="hashtag-line">';
        for ($k = 0; $k < $a; $k++){
            $i = rand(0,count($hashtag_data)-1);
            $digits = $digits + iconv_strlen($hashtag_data[$i]['name'],'UTF-8');
            if ((($digits < 27)&&($times == 2))||(($digits < 24)&&($times == 3))||($times <= 1)){
                echo '<input name="search" style = "background-color: #339b87;" type="submit" value="'.$hashtag_data[$i]['name'].'" class = "hashtag" />';
                $times++;
            }
            else {
                if (($a - 4) < $k){
                    $k=$a+1; // для ровности столбца обрубаем конец оставшихся тегов
                }
                else {
                    echo "</div>";
                    echo '<div class="hashtag-line">';
                    echo '<input name="search" style = "background-color: #339b87;" type="submit" value="'.$hashtag_data[$i]['name'].'" class = "hashtag" />';
                    $digits = iconv_strlen($hashtag_data[$i]['name'],'UTF-8');
                    $times = 1;
                }
            }
        }
        echo "</div>";
        echo '</form>';
    }

    // Генерация хештегов поста
    function hashtagDraw($a,$n){
        $hashtags = explode ("/",$a);
        $hashtag_data = sql_load("source_hashtag_data");
        if(count($hashtags) < $n) {
            echo '<form action="Search.php" method="post">';
            for ($i = 0; $i < count($hashtags); $i++){
                echo '<input name="search" style = "background-color: #'.$hashtag_data[((int)($hashtags[$i]))-1]['color'].';" type="submit" value="'.$hashtag_data[((int)($hashtags[$i]))-1]['name'].'" class = "hashtag" />';
            }
            echo '</form>';
        }
        else {
            for ($i = 0; $i < $n; $i++){
                echo '<input name="search" style = "background-color: #'.$hashtag_data[((int)($hashtags[$i]))-1]['color'].';" type="submit" value="'.$hashtag_data[((int)($hashtags[$i]))-1]['name'].'" class = "hashtag" />';
            }
        }
    }

    // Удадение поста
    function removePicture($key, $id, $type){
        $type_db = "source_background_data";
        
        
        switch ($type) {
            case "photo": $type_db = "source_background_data"; break;
            case "art": $type_db = "source_art_data"; break;
        }
        $data_photos = sql_load($type_db);
        
        if (isset($_SESSION['userId'])){
            if ($data_photos[$id]['author'] == $_SESSION['userUid']) {
                $pdo = new PDO("mysql:host=localhost;dbname=pineforest_db;charset=utf8", 'fa052862', 'Kolombiana026V');
                $user = $data_photos[$id]['author'];
                $data = $pdo->prepare("SELECT * FROM users WHERE uidUsers=?");
                $data->bindParam(1, $user, PDO::PARAM_STR);
                $data->execute();
                
                $usr_data = array();
                while ($row = $data->fetch(PDO::FETCH_ASSOC)){
                    $usr_data[count($usr_data)] = $row;
                }
                
                if (count($usr_data) < 0) {
                    die('<div style="background-color:white; grid-area:1/1/5/6; margin: auto 0;"><p align="center" style="font-size: 16px; color: gray;">Нет пользователей с таким именем или почтой.</br></br><a href="?">Вернуться назад.</a></p></div>');
                    exit();
                }
                else {
                    $pwdCheck = password_verify($key, $usr_data[0]['pwdUsers']);
                    if ($pwdCheck == false) {
                        die("<p>Wrong password</p>");
                        exit();
                    }
                    else if($pwdCheck == true) {
                        $pdo = new PDO("mysql:host=localhost;dbname=pineforest_db;charset=utf8", 'fa052862', 'Kolombiana026V');
                        $STH = $pdo->prepare("DELETE FROM ".$type_db." WHERE id =:id");
                        
                        $STH->bindParam(':id', $data_photos[$id]['id']);
                        $STH->execute();
                        $pdo = null;
                        unlink("images/".$type_db."s/".$data_photos[$id]['image']);
                        die('<div style="background-color:white; grid-area:1/1/5/6; margin: auto 0;"><p align="center" style="font-size: 16px; color: gray;">Удаление прошло успешно.</br></br><a href="?">Вернуться назад.</a></p></div>');
                    }
                }
            }
            else {
                die('<div style="background-color:white; grid-area:1/1/5/6; margin: auto 0;"><p align="center" style="font-size: 16px; color: gray;">Вы не являетесь владельцем данного фото.</br></br><a href="?">Вернуться назад.</a></p></div>');
            }
        }
        else {
            die('<div style="background-color:white; grid-area:1/1/5/6; margin: auto 0;"><p align="center" style="font-size: 16px; color: gray;">Для выполнения данного действия требуется регистрация.</br></br><a href="?">Вернуться на страницу загрузки.</a></p></div>');
        }
        // Надо потом все это разрулить
        $pdo = null;
    }

    // Отображение результатов поиска
    function renderSearchResult($search, $type){
        $posts = 0;
        if ($type == "photo"){
            $path = "images/backgrounds/";
            $photo_data = sql_load("source_background_data");
        }
        if ($type == "art"){
            $path = "images/arts/";
            $photo_data = sql_load("source_art_data");
        }
        
        $hashtag_data = sql_load("source_hashtag_data");
        for ($i = 0; $i < (count($photo_data)); $i++){
            $hashtags = explode ("/",$photo_data[$i]['hashtag']);
            for ($k = 0; $k < count($hashtags); $k++){
                if ($hashtag_data[((int)($hashtags[$k]))-1]['name'] == $search) {
                    echo '<div class = "big-photo-block">
                            <div class = "photo-block-left">
                                <div class="photo-block-header">
                                    <div class = "photo-block-header-text">
                                        <img src="images/photo_icon.png" width="22px" height="22px" alt="icon">
                                        <h3>'.$photo_data[$i]['name'].'</h3>
                                        <div class = "author">'.$photo_data[$i]['author'].'</div>
                                        <div class = "date">'.$photo_data[$i]['date'].'</div>
                                    </div>
                                    <div class="photo-block-header-download">
                                    <a href="'.$path.$photo_data[$i]['image'].'">
                                        <img src="images/download_icon.png" width="20px" height="22px" alt="icon">
                                    </a>
                                    <div style="background-image: url(images/trash.png);" class = "trash">
                                            <button name="trash" style="opacity: 0; width: 100%; height: 100%" onclick="showRemoveField('.$i.')"></button>
                                    </div>
                                </div>
                                </div>
                                <a href="javascript:void(0);" style="display: block;" class="ios_hover">
                                <div style="background-image: url('.$path.$photo_data[$i]['image'].');" class="preview-photo"> 
                                    <button class="preview-button" onclick="showPreviewField('."'".$path.$photo_data[$i]['image']."'".')"></button>
                                    <img src="'.$path.$photo_data[$i]['image'].'" width="100%" style="visibility: hidden;" class="preview-photo-img" />
                                </div>
                                </a>
                            </div>
                            
                            <div class="vr"></div>
                            <div class = "photo-block-right">
                                <div class = "photo-block-right-header">
                                    <img src="images/desc_icon.png" width="24px" height="24px" alt="icon">
                                    <h3>Описание</h3>
                                    <div class="description">'.$photo_data[$i]['description'].'</div>
                                    <div class="hashtag-label">';
                                    hashtagDraw($photo_data[$i]['hashtag'],3);
                                    echo '</div>
                                    <div class="location-label">
                                        <img src="images/location_icon.png" width="20px" height="20px" alt="icon">
                                        <div class="location-text">'.$photo_data[$i]['location'].'</div>
                                    </div>
                                </div>
                            </div>
                        </div> ';
                    $posts++;
                }
            }
        }
        if ($posts == 0){
            echo '<h2 style="width: 100%; opacity: 0.7">Мы ничего не нашли!</h2>';
        }
    }

    // Генерация поста
    function renderPageList ($page, $type, $page_size) {
        if ($type == "photo") $photo_data = sql_load("source_background_data");
        if ($type == "art") $photo_data = sql_load("source_art_data");
        echo '<div class = "page-list">';
        echo '<form action="#" method="post">';
        if ($page >= 3) {
            $start = $page - 2;
            $target = $page + 2;
        }
        else {
            $start = 1;
            $target = 5;
        }
        if ($target*$page_size > count($photo_data)) {
            $target = ((count($photo_data)-1)/$page_size)+1;
        }
        for ($i = $start; $i <= $target; $i++){
            if ($i == $page) {
                echo '<input name="page" style = "background-color: #339b87; color: #fff;" type="submit" value="'.$i.'" class = "hashtag" />';
            }
            else {
                echo '<input name="page" style = "background-color: #fff; color: #339b87;" type="submit" value="'.$i.'" class = "hashtag" />';
            }
        }
        echo '</form>';
        echo '</div>';
    }

    // Парсинг кординат
    function getCoord( $expr ) {
        $expr_p = explode( '/', $expr );
        return $expr_p[0] / $expr_p[1];
    }

    // Загрузка изображения на сайт
    $path_art = 'images/arts/';
    $path_sprite = 'images/sprites/';
    $path_background = 'images/backgrounds/';
    $tmp_path = 'tmp/';
    $types = array('image/png', 'image/jpg', 'image/jpeg', 'image/gif');
    $types_exif = array('image/jpg', 'image/jpeg', 'image/gif');
    $size = 1048576*5;

    if (($_SERVER['REQUEST_METHOD'] == 'POST')&&(isset($_POST['upload-photo-button']))){

        // Проверка на то что файл вообще был загружен
        if(empty($_FILES['upload-file']['tmp_name'])){
            die('<div style="background-color:white; grid-area:1/1/5/6; margin: auto 0;"><p align="center" style="font-size: 16px; color: gray;">Вы не загрузили файл</br></br><a href="?">Вернуться на страницу загрузки фото.</a></p></div>');
        }

        // Проверка на то что файл вообще был загружен
        if(empty($_POST["upload-name"])){
            die('<div style="background-color:white; grid-area:1/1/5/6; margin: auto 0;"><p align="center" style="font-size: 16px; color: gray;">Вы не ввели имя фотографии</br></br><a href="?">Вернуться на страницу загрузки фото.</a></p></div>');
        }

        // $selectOption = $_POST['menu']; -- сделать выбор из переменной отсылаемой в POST

        // Переменные
        if(isset($_POST['upload-photo-description'])){
            $selectOption = "background";
        } 
        if(isset($_POST['upload-art-description'])) {
            $selectOption = "art";
        }

        $location = "Неопределенно";
        $hashtags = "1";
        if (isset($_SESSION['userId'])) 
            $author = $_SESSION['userUid']; 
        else 
            $author = "Unkown"; 

        date_default_timezone_set('UTC');

        //Пока весьма не безопастно из-за возможности схватить вредоносный код из exif данных
        $img = $_FILES['upload-file']['tmp_name'];
        // Проверка поддерживаемого типа
        if (in_array($_FILES['upload-file']['type'], $types_exif)){
            // Вытаскиваем данные изображения
            $exif = exif_read_data( $img, 0, true );
            if(!empty($exif['GPS'])){
                // Ширина
                $latitude['degrees'] = getCoord($exif['GPS']['GPSLatitude'][0]);
                $latitude['degrees'] = floor($latitude['degrees']);
                
                // Долгота
                $longitude['degrees'] = getCoord( $exif['GPS']['GPSLongitude'][0] );
                $longitude['degrees'] = floor($longitude['degrees']);

                // Локация
                $location = $latitude['degrees']." / ".$longitude['degrees'];

            }
            else {
                $location="Неопознано";
            }
        }

        // Check type
        if (!in_array($_FILES['upload-file']['type'], $types))
            die('<div style="background-color:white; grid-area:1/1/5/6; margin: auto 0;"><p align="center" style="font-size: 16px; color: gray;">Запрещённый тип файла.</br></br><a href="?">Попробовать другой файл?</a></p></div>');
        
        if ($selectOption == "background") {
            if (!in_array(mime_content_type($_FILES['upload-file']['tmp_name']), $types))
                die('<div style="background-color:white; grid-area:1/1/5/6; margin: auto 0;"><p align="center" style="font-size: 16px; color: gray;">Хорошая попытка. Запрещенный тип файла.</br></br><a href="?">Попробовать другой файл?</a></p></div>');
        }

        if ($selectOption == "art") {
            if (!in_array(mime_content_type($_FILES['upload-file']['tmp_name']), $types))
                die('<div style="background-color:white; grid-area:1/1/5/6; margin: auto 0;"><p align="center" style="font-size: 16px; color: gray;">Хорошая попытка. Запрещенный тип файла.</br></br><a href="?">Попробовать другой файл?</a></p></div>');
        }

        // Check size
        if ($_FILES['upload-file']['size'] > $size)
            die('<div style="background-color:white; grid-area:1/1/5/6; margin: auto 0;"><p align="center" style="font-size: 16px; color: gray;">Слишком большой размер файла.</br></br><a href="?">Попробовать другой файл?</a></p></div>');    

        if ($selectOption == "background") {
            if (strip_tags($_POST["upload-photo-description"]) == "") {
                $description = "Этот автор решил не добавлять описание к своему шедевру. Наслаждайся тем, что ты имеешь на данный момент.";
            }
            else {
                $description = strip_tags($_POST["upload-photo-description"]);
            }
            if (!@copy($_FILES['upload-file']['tmp_name'], $path_background . $_FILES['upload-file']['name'])){
                // Лог файл
                // $errors= error_get_last();
                // $file = fopen("log.txt", "w+");
                // fwrite($file, "COPY ERROR: ".$errors['type']."\n".$errors['message']);
                // fclose($file);
                // Уведомление об ошибке
                die('<div style="background-color:white; grid-area:1/1/5/6; margin: auto 0;"><p align="center" style="font-size: 16px; color: gray;">Что-то пошло не так.</br></br><a href="?">Вернуться на страницу загрузки.</a></p></div>');
            }
            else {
                $pdo = new PDO("mysql:host=localhost;dbname=pineforest_db;charset=utf8", 'fa052862', 'Kolombiana026V');
                $php = "INSERT INTO `source_background_data` (`id`, `name`, `author`, `date`, `image`, `description`, `location`, `hashtag`) VALUES (NULL,?,?,?,?,?,?,?);";
                $STH = $pdo->prepare($php);
                $STH->bindParam(1, strip_tags($_POST["upload-name"]), PDO::PARAM_STR);
                $STH->bindParam(2, $author, PDO::PARAM_STR);
                $STH->bindParam(3, date("Y-m-d"), PDO::PARAM_STR);
                $STH->bindParam(4, $_FILES['upload-file']['name'], PDO::PARAM_STR);
                $STH->bindParam(5, $description, PDO::PARAM_STR);
                $STH->bindParam(6, $location, PDO::PARAM_STR);
                $STH->bindParam(7, $hashtags, PDO::PARAM_STR);
                $STH->execute();
                $pdo = null;
                die('<div style="background-color:white; grid-area:1/1/5/6; margin: auto 0;"><p align="center" style="font-size: 16px; color: gray;">Загрузка удачна.</br></br><a href="?">Вернуться на страницу загрузки.</a></p></div>');
            }
        }

        if ($selectOption == "art") {
            $location = "Воображение";
            if (strip_tags($_POST["upload-art-description"]) == "") {
                $description = "Этот автор решил не добавлять описание к своему шедевру. Наслаждайся тем, что ты имеешь на данный момент.";
            }
            else {
                $description = strip_tags($_POST["upload-art-description"]);
            }
            if (!@copy($_FILES['upload-file']['tmp_name'], $path_art . $_FILES['upload-file']['name'])){
                // Лог файл
                // $errors= error_get_last();
                // $file = fopen("log.txt", "w+");
                // fwrite($file, "COPY ERROR: ".$errors['type']."\n".$errors['mess']);
                // fclose($file);
                // Уведомление об ошибке
                die('<div style="background-color:white; grid-area:1/1/5/6; margin: auto 0;"><p align="center" style="font-size: 16px; color: gray;">Что-то пошло не так.</br></br><a href="?">Вернуться на страницу загрузки.</a></p></div>');
            }
            else {
                $pdo = new PDO("mysql:host=localhost;dbname=pineforest_db;charset=utf8", 'fa052862', 'Kolombiana026V');
                $php = "INSERT INTO `source_art_data` (`id`, `name`, `author`, `date`, `image`, `description`, `location`, `hashtag`) VALUES (NULL,?,?,?,?,?,?,?);";
                $STH = $pdo->prepare($php);
                $STH->bindParam(1, strip_tags($_POST["upload-name"]), PDO::PARAM_STR);
                $STH->bindParam(2, $author, PDO::PARAM_STR);
                $STH->bindParam(3, date("Y-m-d"), PDO::PARAM_STR);
                $STH->bindParam(4, $_FILES['upload-file']['name'], PDO::PARAM_STR);
                $STH->bindParam(5, $description, PDO::PARAM_STR);
                $STH->bindParam(6, $location, PDO::PARAM_STR);
                $STH->bindParam(7, $hashtags, PDO::PARAM_STR);
                $STH->execute();
                $pdo = null;
                die('<div style="background-color:white; grid-area:1/1/5/6; margin: auto 0;"><p align="center" style="font-size: 16px; color: gray;">Загрузка удачна.</br></br><a href="?">Вернуться на страницу загрузки.</a></p></div>');
            }
        }
    }

    if (($_SERVER['REQUEST_METHOD'] == 'POST')&&(isset($_POST['signup-submit']))){
        singup_db();   
    }

    if (($_SERVER['REQUEST_METHOD'] == 'POST')&&(isset($_POST['login-submit']))){
        login_db();   
    }

    if (($_SERVER['REQUEST_METHOD'] == 'POST')&&(isset($_POST['logout-button']))){
        logout_db();   
    }
?>
