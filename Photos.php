<!-- Стартуем сессию -->
<?php
    session_start();
?>
<html>
<head>
    <title>Фотографии</title>
    meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <!-- Иконка для избранного -->
    <link rel="apple-touch-icon" href="images/app_icon.png"/>
    <<!-- Подключаем css загружем шрифты -->
    <link rel="stylesheet" media="(max-width: 950px)" href="css/style-mobile.css">
    <link rel="stylesheet" media="(min-width: 1250px)" href="css/style.css">
    <link rel="stylesheet" media="(min-width: 1912px)" href="css/style-big.css">
    <link rel='stylesheet' media='screen and (min-width: 951px) and (max-width: 1250px)' href='css/style-mini.css' />
    <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:400,600,700&display=swap" rel="stylesheet">
    <!-- Дополнительные стили -->
    <style type="text/css">
        nav li {
            border-top: 4px solid transparent;
        }
        nav li:nth-of-type(2) {
            border-top: 4px solid #339b87;
        }
        nav li:nth-of-type(2):hover {
            border-top: 0px solid #339b87;
            padding-top: 4px;
        }
    </style>
</head>
<body>
    <!-- Подключаем главный php -->
    <?php require ($_SERVER["DOCUMENT_ROOT"]."/include/code.php"); ?>

    <!-- Проверка POST на удаление -->
    <?php
        if(isset($_POST['trash'])){
            $delete_post=strip_tags($_POST['trash']);
        }
        if(isset($_POST['trash-accept'])){
            $delete_post=strip_tags($_POST['trash-accept']);
            $key=strip_tags($_POST['password-admin']);
            removePicture($key, $delete_post,"picture");
        }
    ?>
    <!-- Диалоговое окно -->
    <div id="second-level">
        <!-- Диалоговое меню удаления -->
        <div id="dialog-box">
            <button style="background-image: url(images/back.png);" onclick="showRemoveField('images/back.png')" id="back-button"></button>
            <h1>Для подтверждения удаления, введите ваш пароль пользователя:</h1>
            <form action="#" method="post" style="width: 100%;">
                <div style="display:flex; flex-wrap: nowrap;">
                    <input name="password-admin" maxlength="16" class="password-admin" type="password" autocomplete="off" placeholder="Пароль">
                    <div style="background-image: url(images/trash.png);" class = "trash-accept">
                        <input name="trash-accept" style="opacity: 0; height: 100%; width: 100%;" type="submit" id="trash-accept" value="" />
                    </div>
                </div>
            </form>
        </div>
        <!-- Диалоговое меню для предпросмотра фото -->
        <button class="preview-button" onclick="showPreviewField('images/preview.jpg')"></button>
        <div id="preview-field">
            <!-- Тестовое изображение -->
            <img src="images/tumb_background.jpg" id="photo-image">
        </div>
    </div>
    <!-- Главная тень листа -->
    <div class="shadow"></div>
    <!-- Шапка -->
    <header>
        <!-- Навигация -->
        <nav>
            <img src="images/logo.png" width="36px" height="18px" alt="icon">
            <?php
                for( $i = 0; $i < sizeof($nav_lables); $i++)
                    echo "<li><a href='",$nav_lables[$i],"'>",$nav_lables_name[$i],"</a></li>";
            ?>
        </nav>
        <!-- Поле входа -->
        <div class="user-label">
            <form action="<?php if (isset($_SESSION['userId'])) echo "#"; else echo "singin.php"; ?>" style="margin: auto 0px;">
                <button type="submit" class="login-button" name="login-button"> 
                    <?php 
                        if (isset($_SESSION['userId'])) echo $_SESSION['userUid']; else echo "Вход";
                    ?>
                </button>
            </form>
            <?php 
                if (isset($_SESSION['userId'])) echo '
                    <form method="post" style="margin: auto 0px;">
                        <button type="submit" class="logout-button" name="logout-button">Выход</button>
                    </form>
                ';
            ?>
        </div>
    </header>
    <!-- Эффект размытия шапки -->
    <div class="blur-header"></div>
    <!-- Поле загаловка основного контента -->
    <div class = "title">
        <div class = "title-items">
            <!-- Заголовок -->
            <div><h1>Галерея</h1></div>
            <!-- Поле инструментов -->
            <div class="title-icons">
                <button id="add-photo-button" onclick="showAddField()"></button>
                <button id="user-folder-button" onclick="showAddField()"></button>
            </div>
        </div>
        <hr>
    </div>
    <!-- Основной контент -->
    <article>
        <!-- Поле для добавления фото -->
        <div id="add-photo-field">
            <div id="add-photo-block">
            <form style="width: 100%; display: flex;" method="post" enctype="multipart/form-data">
                <!-- Левая часть -->
                <div class = "photo-block-left">
                    <div class="photo-block-header">
                        <div class = "photo-block-header-text">
                            <div style="display:flex; align-items:center;">
                                <img src="images/photo_icon.png" width="22px" height="22px" alt="icon">
                                <input name="upload-name" maxlength="12" type="text" id="upload-input-name" placeholder="Название поста">
                            </div>
                            <p id="upload-input-author"><?php if (isset($_SESSION['userId'])) echo $_SESSION['userUid']; else echo "Unkown"; ?></p>
                            <!-- Вставка актуального времени -->
                            <?php
                                date_default_timezone_set('UTC');
                                echo '<div class = "date">'.date("Y-m-d").'</div>';
                            ?>
                        </div>
                        <div class="photo-block-header-download">
                            <input type="submit" id="upload-photo-button" name="upload-photo-button" value="">
                        </div>
                    </div>
                    <!-- Поле для вставки файла фото -->
                    <label class="label">
                        <span class="upload-title">Добавить файл</span>
                        <input name="upload-file" type="file" id="upload-photo-input">
                    </label>
                </div>
                <!-- Разделитель -->
                <div class="vr"></div>
                <!-- Правая часть -->
                <div class = "photo-block-right">
                    <div class = "photo-block-right-header">
                        <img src="images/desc_icon.png" width="24px" height="24px" alt="icon">
                        <h3>Описание</h3>
                        <textarea name="upload-photo-description" id="upload-input-description" type="text" placeholder="Опиши данное фото, или добавьте что-то от себя..."></textarea>
                        <!-- Поле для прописывания хештегов -->
                        <div class="hashtag-label">
                            <?php
                                hashtagDraw(1,3);
                            ?>
                        </div>
                        <div class="location-label">
                            <img src="images/location_icon.png" width="20px" height="20px" alt="icon">
                            <div id="location-text" class="location-text">Неопознано</div>
                        </div>
                    </div>
                </div>
            </form>
            </div>
        </div>
        <!-- Отображение нужной страницы -->
        <?php
            if(isset($_POST['page'])){
                $data=strip_tags($_POST['page']);
                renderBigPhotoBlockPage($data,6, "photo");
            }
            else {
                renderBigPhotoBlockPage(1,6, "photo");
            }
        ?>
        <?php
            if(isset($_POST['page'])){
                $data=strip_tags($_POST['page']);
                renderPageList($data, "photo", 10);
            }
            else {
                renderPageList(1, "photo", 10);
            }
        ?>
    </article>
    <!-- Вспомогательное поле -->
    <aside>
        <div class="hash-list">
            <!-- Поле поиска -->
            <div class = "right-nav">
                <img src="images/search_icon.png" width="22px" height="22px" alt="icon">
                <form name="search_form" action="Search.php" id="search-form" method="POST">
                    <input type="search" placeholder="Поиск..." id="search-input" class="search-input" name="search">
                </form>
            </div>
            <!-- Поле случайно-сгенерированных хештегов -->
            <h3>Может вам понравится?</h3>
            <?php
                renderHashtag(40);
            ?>
        </div>
    </aside>
    <!-- Подвал -->
    <footer>
        <hr>
        <div class="footer-title">Спасибо что зашли, возращайтесь поскорее!</div>
        <p>Илья Волков - Дизайнер и программист сайта PineArt</p>
    </footer>
    <!-- Подключаем главный js файл -->
    <script src="/include/code.js"></script>
</body>
</html>