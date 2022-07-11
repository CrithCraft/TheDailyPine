<?php
    require 'db.php';

    // Первая загрузка
    $pdo = new PDO("mysql:host=localhost;dbname=u1153644_legacy;charset=utf8", $user, $pass);
    $data = $pdo->prepare("SELECT * FROM posts");
    $data->execute();
    $arPosts = array();
    while ($row = $data->fetch(PDO::FETCH_ASSOC))
        $arPosts[count($arPosts)] = $row;
    $pdo = null;

    // Массив страниц сайта
    $nav_lables = ['home', 'about'];
    // Масив с переводом названий страниц
    $nav_lables_name = ['Главная', 'О нас'];

    // Функция для входа в аккаунт
    function login_db() {
        require 'db.php';
        $pdo = new PDO("mysql:host=localhost;dbname=u1153644_legacy;charset=utf8", $user, $pass);
        $mailuid = $_POST['mailuid'];
        $password = $_POST['pwd'];

        if (empty($mailuid) || empty($password)){
            die('You forgot put something in labels');
            exit();
        }
        else {
            $data = $pdo->prepare("SELECT users.*, roles.role FROM `users`, `roles` WHERE (users.name = ? OR users.email = ?) AND roles.user_id = users.user_id ");
            $data->bindParam(1, $mailuid, PDO::PARAM_STR);
            $data->bindParam(2, $mailuid, PDO::PARAM_STR);
            $data->execute();

            $arr_data = array();
            while ($row = $data->fetch(PDO::FETCH_ASSOC)){
                $arr_data[count($arr_data)] = $row;
            }
            if (count($arr_data) < 0) {
                die('<div class="process_msg">Нет пользователей с таким никнеймом или почтой.</br></br><a href="/signup">Вернуться назад.</a></p></div>');
                exit();
            }
            else {
                $pwdCheck = password_verify($password, $arr_data[0]['pwd']);
                if ($pwdCheck == false) {
                    die('<div class="process_msg"><p align="center" style="font-size: 16px; color: gray;">Неправильный пароль. </br></br><a href="/signup">Вернуться на предыдущую страницу.</a></p></div>');
                    exit();
                }
                else if($pwdCheck == true) {
                    session_start();
                    $_SESSION['userId'] = $arr_data[0]['user_id'];
                    $_SESSION['userUid'] = $arr_data[0]['name'];
                    $_SESSION['avatar'] = $arr_data[0]['avatar'];
                    $_SESSION['status'] = $arr_data[0]['status'];
                    $_SESSION['background'] = $arr_data[0]['background'];
                    $_SESSION['mail'] = $arr_data[0]['email'];
                    $_SESSION['role'] = $arr_data[0]['role'];
                    $_SESSION['api'] = "$arr_data[0]['api']";

                    header("Location: ../?login=success");

                    $content = "User has login";
                    $fp = fopen($_SERVER['DOCUMENT_ROOT'] . "/REGISTER_LOG.txt","a+");
                    fwrite($fp,$content);
                    fclose($fp);
                    exit();
                }
                else {
                    die('<div class="process_msg"><p align="center" style="font-size: 16px; color: gray;">Неправильный пароль. </br></br><a href="/signup">Вернуться на предыдущую страницу.</a></p></div>');
                    exit();
                }
            }

            $pdo = null;
        }

    }

    // Функция для выхода из аккаунта
    function logout_db() {
        //session_start();
        session_unset();
        session_destroy();
        //header("Location: ../Photos.php");
    }

    // Функция для регистрации аккаунта
    function singup_db(){
        require 'db.php';
        $pdo = new PDO("mysql:host=localhost;dbname=u1153644_legacy;charset=utf8", $user, $pass);

        $username = $_POST['uid'];
        $email = $_POST['mail'];
        $password = $_POST['pwd'];
        $passwordRepeat = $_POST['pwd-repeat'];

        if (empty($username) || empty($email) || empty($password) || empty($passwordRepeat)){
            $pdo = null;
            die('<div class="process_msg"><p align="center" style="font-size: 16px; color: gray;">Заполните все поля!. </br></br><a href="/signup">Вернуться на предыдущую страницу</a></p></div>');
            exit();
        }
        else {
            $data = $pdo->prepare("SELECT name FROM users WHERE name=?");
            $data->bindParam(1, $username, PDO::PARAM_STR);
            $data->execute();
            $arr_data = array();
            while ($row = $data->fetch(PDO::FETCH_ASSOC))
                $arr_data[count($arr_data)] = $row;

            if (count($arr_data) > 0) {
                $pdo = null;
                die('<div class="process_msg"><p align="center" style="font-size: 16px; color: gray;">Никнейм уже занят! </br></br><a href="/signup">Вернуться на предыдущую страницу.</a></p></div>');
                exit();
            }
            else {
                $hashedPwd = password_hash($password, PASSWORD_DEFAULT);

                // загрузка пользователя в БД
                $data = $pdo->prepare("INSERT INTO users (name, email, pwd) VALUES (?,?,?)");
                $data->bindParam(1, $username, PDO::PARAM_STR);
                $data->bindParam(2, $email, PDO::PARAM_STR);
                $data->bindParam(3, $hashedPwd, PDO::PARAM_STR);
                $data->execute();

                require 'db.php';
                // get last post id
                $pdo = new PDO("mysql:host=localhost;dbname=u1153644_legacy;charset=utf8", $user, $pass);
                $php = "SELECT * FROM users WHERE user_id=(SELECT max(user_id) FROM users);";
                $STH = $pdo->prepare($php);
                $STH->execute();
                while ($row = $STH->fetch(PDO::FETCH_ASSOC)){
                    $user_id = $row["user_id"];
                }

                // Присвоение роли новосозданному пользоватлю
                $data = $pdo->prepare("INSERT INTO roles (user_id, role) VALUES (?,'user')");
                $data->bindParam(1, $user_id, PDO::PARAM_STR);
                $data->execute();

                $pdo = null;
                die('<div class="process_msg"><p align="center" style="font-size: 16px; color: gray;">Вы успешно зарегистрированы! </br></br><a href="/signup">Вернуться на предыдущую страницу.</a></p></div>');
                exit();
            }



        }
    }

    // Функция для загрузки всех данных по указанной таблице и отправка их в виде массива
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
            // print "Error!: " . $e->getMessage() . "<br/>";
            // $file = fopen("logfile.log", "a+");
            // fwrite($file, "Error!: ".$e->getMessage()."\n");
            // fwrite($file, "In file: ".$e->getFile().", line: ".$e->getLine()."\n");
            // fclose($file);
            // return null;
            // die();
        }
    }

    // Функция для генерации блока поста
    function renderBigPhotoBlockPage($page,$n, $type){
        if ($type == "photo"){
            $path = "images/backgrounds/";
            $photo_data = sql_load("posts");
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

    // Функция для получения кординат из данных фото
    function getCoord( $expr ) {
        $expr_p = explode( '/', $expr );
        return $expr_p[0] / $expr_p[1];
    }

    // Функция для сжвтия изобрвжения.
    function img_resize($src, $dest, $width, $height, $rgb=0xFFFFFF, $quality=100) {
        if (!file_exists($src)) return false;

        $size = getimagesize($src);

        if ($size === false) return false;

        /* Определяем исходный формат по MIME-информации, предоставленной
        функцией getimagesize, и выбираем соответствующую формату imagecreatefrom-функцию. */
        $format = strtolower(substr($size['mime'], strpos($size['mime'], '/')+1));
        $icfunc = "imagecreatefrom" . $format;
        if (!function_exists($icfunc)) return false;

        $x_ratio = $width / $size[0];
        $y_ratio = $height / $size[1];

        $ratio = min($x_ratio, $y_ratio);
        $use_x_ratio = ($x_ratio == $ratio);

        $new_width = $use_x_ratio ? $width : floor($size[0] * $ratio);
        $new_height = !$use_x_ratio ? $height : floor($size[1] * $ratio);
        $new_left = $use_x_ratio ? 0 : floor(($width - $new_width) / 2);
        $new_top = !$use_x_ratio ? 0 : floor(($height - $new_height) / 2);

        $isrc = $icfunc($src);
        $idest = imagecreatetruecolor($width, $height);

        imagefill($idest, 0, 0, $rgb);
        imagecopyresampled($idest, $isrc, $new_left, $new_top, 0, 0,
        $new_width, $new_height, $size[0], $size[1]);

        imagejpeg($idest, $dest, $quality);

        imagedestroy($isrc);
        imagedestroy($idest);

        return true;
    }


    // Обьявление переменных для загрузки изображения на хост
    $path_art = 'images/arts/';
    $path_sprite = 'images/sprites/';
    $path_background = 'images/backgrounds/';
    $tmp_path = 'tmp/';
    $types = array('image/png', 'image/jpg', 'image/jpeg', 'image/gif');
    $types_exif = array('image/jpg', 'image/jpeg', 'image/gif');
    $size = 1048576*5;
    // Если посылается POST запрос с формой для создания нового поста то
    if (($_SERVER['REQUEST_METHOD'] == 'POST')&&(isset($_POST['upload-photo-button']))){
        require 'db.php';

        // Проверка на то что файл вообще был загружен
        if(empty($_FILES['upload-file']['tmp_name'])){
            die('<div class="process_msg"><p align="center" style="font-size: 16px; color: gray;">Вы не загрузили файл</br></br><a href="?">Вернуться на страницу загрузки фото.</a></p></div>');
        }

        // Проверка на то что файл вообще был загружен
        if(empty($_POST["upload-name"])){
            die('<div class="process_msg"><p align="center" style="font-size: 16px; color: gray;">Вы не ввели имя фотографии</br></br><a href="?">Вернуться на страницу загрузки фото.</a></p></div>');
        }

        // $selectOption = $_POST['menu']; -- сделать выбор из переменной отсылаемой в POST

        // Переменные
        if(isset($_POST['upload-photo-description'])){
            $selectOption = "background";
        }
        if(isset($_POST['upload-art-description'])) {
            $selectOption = "art";
        }

        // Заносим юазовые значения поста
        $location = "Неопределенно";
        $hashtags = "1";
        // Заносим имя автота в переменную автора
        if (isset($_SESSION['userId']))
            $author = $_SESSION['userUid'];
        else
            $author = "Unkown"; // Нерелевантно с версии 3.0

        //Установка часового пояса
        date_default_timezone_set('UTC');

        //Пока весьма не безопастно из-за возможности схватить вредоносный код из exif данных
        $img = $_FILES['upload-file']['tmp_name'];
        // Проверка поддерживаемого типа
        if (in_array($_FILES['upload-file']['type'], $types_exif)){
            // Вытаскиваем данные изображения
            $exif = exif_read_data( $img, 0, true );
            // Если фото иеет данные о местоположении ее создания
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


        // Проверка типа изображения
        if (!in_array($_FILES['upload-file']['type'], $types))
            die('<div class="process_msg"><p align="center" style="font-size: 16px; color: gray;">Запрещённый тип файла.</br></br><a href="?">Попробовать другой файл?</a></p></div>');

        // Проверка является ли тип файла тем за кого он себя выдает
        if ($selectOption == "background") {
            if (!in_array(mime_content_type($_FILES['upload-file']['tmp_name']), $types))
                die('<div class="process_msg"><p align="center" style="font-size: 16px; color: gray;">Хорошая попытка. Запрещенный тип файла.</br></br><a href="?">Попробовать другой файл?</a></p></div>');
        }
        if ($selectOption == "art") {
            if (!in_array(mime_content_type($_FILES['upload-file']['tmp_name']), $types))
                die('<div class="process_msg"><p align="center" style="font-size: 16px; color: gray;">Хорошая попытка. Запрещенный тип файла.</br></br><a href="?">Попробовать другой файл?</a></p></div>');
        }

        // Проверка размера
        if ($_FILES['upload-file']['size'] > $size)
            die('<div class="process_msg"><p align="center" style="font-size: 16px; color: gray;">Слишком большой размер файла.</br></br><a href="?">Попробовать другой файл?</a></p></div>');
        
        // Очищаем html вставки из текста
        if (strip_tags($_POST["upload-photo-description"]) == "") {
            $description = "Этот автор решил не добавлять описание к своему шедевру. Наслаждайся тем, что ты имеешь на данный момент.";
        }
        else {
            $description = strip_tags($_POST["upload-photo-description"]);
        }

        // Копируем оригинал фото на сервер, если произошла ошибка то,
        if (!@copy($_FILES['upload-file']['tmp_name'], $path_background . $_FILES['upload-file']['name'])){
            die('<div class="process_msg"><p align="center" style="font-size: 16px; color: gray;">Что-то пошло не так.</br></br><a href="?">Вернуться на страницу загрузки.</a></p></div>');
        }
        else { // Если отправка успешна, то выполняем следующее
            // Достаем размер изображения
            $size = getimagesize($_FILES['upload-file']['tmp_name']);
            // Получаем данные о фото и заносим их в переменные
            list($width, $height, $type, $attr) = getimagesize($img);
            // Если не удалось сжать фото, выводим ошибку 
            if (!img_resize($path_background.$_FILES['upload-file']['name'], $path_background."low_".$_FILES['upload-file']['name'], 480, 480*$height/$width)){
                die('<div class="process_msg"><p align="center" style="font-size: 16px; color: gray;">Что-то пошло не так.</br></br><a href="?">Вернуться на страницу загрузки.</a></p></div>');
            }
            else {

            }
            if (!img_resize($path_background.$_FILES['upload-file']['name'], $path_background."superlow_".$_FILES['upload-file']['name'], 480, 480*$height/$width)){
                die('<div class="process_msg"><p align="center" style="font-size: 16px; color: gray;">Что-то пошло не так.</br></br><a href="?">Вернуться на страницу загрузки.</a></p></div>');
            }
            else {

            }
            // Загружаем данные о посте в БД
            $pdo = new PDO("mysql:host=localhost;dbname=u1153644_legacy;charset=utf8", $user, $pass);
            $php = "INSERT INTO `posts` (`post_id`, `name`, `date`, `img`, `location`, `user_id`) VALUES (NULL,?,?,?,?,?);";
            $STH = $pdo->prepare($php);
            $STH->bindParam(1, strip_tags($_POST["upload-name"]), PDO::PARAM_STR);
            $STH->bindParam(2, date("Y-m-d"), PDO::PARAM_STR);
            $STH->bindParam(3, $_FILES['upload-file']['name'], PDO::PARAM_STR);
            $STH->bindParam(4, $location, PDO::PARAM_STR);
            $STH->bindParam(5, $_SESSION['userId'], PDO::PARAM_STR);
            $STH->execute();
            $pdo = null;
            // Загружаем хештеги в БД
            upload_hashtags($_POST['upload-hashtag']);
            // Выводим сообщение об удачной загрузке поста
            die('<div class="process_msg"><p align="center" style="font-size: 16px; color: gray;">Загрузка удачна.</br></br><a href="?">Вернуться на страницу загрузки.</a></p></div>');
        }
        
    }

    // Функция для загрузки тегов поста в БД
    function upload_hashtags($hashtag_data){
        require 'db.php';
        // Получаем последний id из таблицы постов в БД
        $pdo = new PDO("mysql:host=localhost;dbname=u1153644_legacy;charset=utf8", $user, $pass);
        $php = "SELECT * FROM posts WHERE post_id=(SELECT max(post_id) FROM posts);";
        $STH = $pdo->prepare($php);
        $STH->execute();
        while ($row = $STH->fetch(PDO::FETCH_ASSOC)){
            $post_id = $row["post_id"];
        }
        $pdo = null;
        $hashtag_arr = preg_split('/\s+/', $hashtag_data);

        foreach ($hashtag_arr as &$value) {
            // Подставляем новый хештег в БД
            $hashtag_exist_id = NULL;

            // Если нет решетки в теге, то подставляем его
            if(substr($value, 0, 1) != "#") $value = "#".$value;

            $pdo = new PDO("mysql:host=localhost;dbname=u1153644_legacy;charset=utf8", $user, $pass);
            $php = "SELECT * FROM hashtags WHERE name = ?";
            $STH = $pdo->prepare($php);
            $STH->bindParam(1, $value);
            $STH->execute();
            while ($row = $STH->fetch(PDO::FETCH_ASSOC)){
                // Количество подходящих
                $hashtag_exist_id = $row["hashtag_id"];
            }
            $pdo = NULL;

            if ($hashtag_exist_id != NULL) {
                // Подставляем новый хештег в БД
                $pdo = new PDO("mysql:host=localhost;dbname=u1153644_legacy;charset=utf8", $user, $pass);
                $php = "INSERT INTO `hashtags_link` (`post_id`, `hashtag_id`) VALUES (?,?)";
                $STH = $pdo->prepare($php);
                $STH->bindParam(1, $post_id);
                $STH->bindParam(2, $hashtag_exist_id);
                $STH->execute();
                $pdo = null;
            }
            else {
                $pdo = new PDO("mysql:host=localhost;dbname=u1153644_legacy;charset=utf8", $user, $pass);
                $php = "INSERT INTO `hashtags` (`name`, `color`) VALUES (?,'#304157');";
                $STH = $pdo->prepare($php);
                $STH->bindParam(1, $value, PDO::PARAM_STR);
                $STH->execute();
                $pdo = null;

                // Достаем id последнего хештега из таблицы хештегов в БД
                $pdo = new PDO("mysql:host=localhost;dbname=u1153644_legacy;charset=utf8", $user, $pass);
                $php = "SELECT * FROM hashtags WHERE hashtag_id=(SELECT max(hashtag_id) FROM hashtags);";
                $STH = $pdo->prepare($php);
                $STH->execute();
                while ($row = $STH->fetch(PDO::FETCH_ASSOC)){
                    $hashtag_id = $row["hashtag_id"];
                }
                $pdo = null;

                // Вставляем новый хештег в БД
                $pdo = new PDO("mysql:host=localhost;dbname=u1153644_legacy;charset=utf8", $user, $pass);
                $php = "INSERT INTO `hashtags_link` (`post_id`, `hashtag_id`) VALUES (?,?);";
                $STH = $pdo->prepare($php);
                $STH->bindParam(1, $post_id, PDO::PARAM_STR);
                $STH->bindParam(2, $hashtag_id, PDO::PARAM_STR);
                $STH->execute();
                $pdo = null;
            }
        }
    }

    // Если отправлен POST запрос и форма регистрации, то вызываем ее функцию
    if (($_SERVER['REQUEST_METHOD'] == 'POST')&&(isset($_POST['signup-submit']))){
        singup_db();
    }
    // Если отправлен POST запрос и форма входа, то вызываем ее функцию
    if (($_SERVER['REQUEST_METHOD'] == 'POST')&&(isset($_POST['login-submit']))){
        login_db();
    }
    // Если отправлен POST запрос и форма выхода, то вызываем ее функцию
    if (($_SERVER['REQUEST_METHOD'] == 'POST')&&(isset($_POST['logout-button']))){
        logout_db();
    }

?>
