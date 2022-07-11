<?php
    session_start();
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Домашняя</title>
    <!-- Google проверка -->
    <meta name="google-site-verification" content="HbFLZijaHOd6bIiyBA6ZU75K88FXksqOH5H8A6spwXg" />
    <!-- Подключение стилей -->
    <link rel="stylesheet" type="text/css" href="css/style.css">
    <link rel="stylesheet" type="text/css" media="screen and (max-width: 934px)" href="css/style_mobile.css">
    <!-- Подключение JQuery -->
    <script src="include/jquery.min.js" type="application/javascript"></script>
    <!-- Шрифты -->
    <link href="https://fonts.googleapis.com/css2?family=Rubik:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Anonymous+Pro:wght@400;700&display=swap" rel="stylesheet">
    <!-- Дополнительные стили -->
    <style type="text/css">
        nav li {
            border-top: 4px solid transparent;
        }
        nav li:nth-of-type(1) {
            border-top: 4px solid #339b87;
        }
        nav li:nth-of-type(1):hover {
            border-top: 0px solid #339b87;
            padding-top: 4px;
        }
    </style>
</head>
<body>
    <div id="message-box"></div>
    <!-- "Модельное окно" -->
    <div id="second-page" onclick='closewindow()'>
        <div class="area" style="display: flex; height: min-content; flex-wrap: wrap; justify-content: space-between;">
            <div class="second-user-image-area">
                <img id="second-user-image" src='images/avatar.jpg' alt="avatar">
            </div>
            <div id="second-user-data" style="width: 50%; margin: auto;"></div>
        </div>
    </div>
    <!-- Экран загрузки -->
    <div id="loading-screen">
        <div id="load-area">
            <div class="image"></div>
            <div id="load-bar">
                <div class="line-load-bar"></div>
                <div class="line-load-bar"></div>
                <div class="line-load-bar"></div>
                <div class="line-load-bar"></div>
                <div class="line-load-bar"></div>
                <div class="line-load-bar"></div>
            </div>
        </div>
    </div>
    <!-- подключение главного PHP файла -->
    <?php require ($_SERVER["DOCUMENT_ROOT"]."/include/code.php"); ?>
    <!-- Шапка -->
    <header id="myHeader">
        <!-- Навигация -->
        <nav>
            <div class="logo">
                <img src="images/logo.svg" height="32px" alt="icon" alt="logo">
            </div>
            <div class="vr"></div>
            <?php
                for( $i = 0; $i < sizeof($nav_lables); $i++) {
                    echo "<li><a href='",$nav_lables[$i],"'>",$nav_lables_name[$i],"</a></li>";
                }
            ?>
        </nav>
        <!-- Поле пользователя вход/регистрация -->
        <div class="user-label">
            <!-- Вход -->
            <!-- Выход -->
            <?php
                if (isset($_SESSION['userId'])) echo '
                    <form method="post" style="margin: auto 0px;">
                        <button type="submit" class="logout-button" name="login-button">'.$_SESSION['userUid'].'</button>
                    </form>
                ';
            ?>
            <form method="post" action="<?php if (isset($_SESSION['userId'])) echo "#"; else echo "signup"; ?>" style="margin: auto 0px;">
                <button type="submit" class="login-button" name="logout-button">
                    <?php if (isset($_SESSION['userId'])) echo "Выход"; else echo "Вход"; ?>
                </button>
            </form>
        </div>
    </header>
    <!-- Размытость фона шапки -->
    <div id="blur-header"></div>

    <aside id="myAside">
        <div class="option" id="option-review" onclick="opensidebar('review')"></div>
        <div class="option" id="option-favorite" onclick="opensidebar('favorite')"></div>
        <div class="option" id="option-search" onclick="opensidebar('search')"></div>
        <div class="option" id="option-user" onclick="opensidebar('user')"></div>
        <div class="option" id="option-add-photo" onclick="opensidebar('add-photo')"></div>
        <?php
            if (isset($_SESSION['role'])) {
                if ($_SESSION['role'] == "admin")
                    echo '<div class="option" id="option-admin" onclick="opensidebar('."'"."admin"."'".')"></div>';
                if ($_SESSION['role'] == "moderator")
                    echo '<div class="option" id="option-admin" onclick="opensidebar('."'"."moderator"."'".')"></div>';
            }
        ?>
    </aside>
    <!-- Поле фото домашней страницы -->
    <div id="tumb">
        <!-- Поле приветствия в вкладке личного кабинета -->
        <div id="tumb-info" style="background: linear-gradient(0deg, rgba(255,255,255,1) 0%, rgba(255,255,255,1) 35%,rgba(255,255,255,0) 35%, rgba(0,0,0,0.25) 35%, rgba(0,0,0,0.25) 100%), url('<?php echo $_SESSION['background'] ?>')">
            <img src="<?php echo $_SESSION['avatar']; ?>" alt="avatar">
            <div>
                <h1><div id="hiMessage"></div> <?php echo $_SESSION['userUid']; ?></h1>
                <p><?php echo $_SESSION['status']; ?></p>
            </div>
        </div>
    </div>
    <!-- Поле с постами -->
    <article id="myArticle">
        <!-- Загаловок -->
        <div id = "title">
            <div class = "title-items">
                <!-- Текст -->
                <div id = "page-title"><h1>Новые посты</h1></div>
                <!-- Панель взаимодействия с конентом -->
                <div class="title-icons">
                    <button id="add-photo-button" onclick="removePhotoSelect();"></button>
                    <button id="user-folder-button"></button>
                </div>
            </div>
            <hr>
        </div>
        <!-- Поле поиска -->
        <div id="search-area">
            <input type="text" name="search-input" id="search-input" placeholder="Введите искомое слово...">
        </div>
        <!-- Тег для загрузки постов посредством AJAX -->
        <div id="articles"></div>
        <button onClick="loadMorePosts()" id="more">Дальше</button>
        <script type="text/javascript">
            $(document).ready(function(){
                load_posts("post", false);
            });
        </script>
    </article>
    <!-- Поле для загрузки фото -->
    <div id="add-photo-area">
        <div style="width: 50%; box-sizing: border-box; padding-right: 50px;">
            <form style="width: 100%; display: flex; display: -webkit-flex" method="post" enctype="multipart/form-data">
                <div>
                    <label class="label">
                        <span class="upload-title">Добавить файл</span>
                        <input name="upload-file" type="file" id="upload-photo-input">
                    </label>
                </div>
                <div>
                    <textarea style="display: none;" name="upload-photo-description" id="upload-input-description" type="text" placeholder="Опиши данное фото, или добавьте что-то от себя..."></textarea>
                    <input name="upload-name" maxlength="12" type="text" id="upload-input-name" placeholder="Название">
                    <input name="upload-hashtag" type="text" id="upload-input-hashtag" placeholder="Теги поста">
                    <input type="submit" id="upload-photo-button" name="upload-photo-button" value="Отправить">
                </div>
            </form>
        </div>
        <div style="width: 50%; text-align: justify;">
            Формат поддерживаемого фото: gif, jpg, jpeg, png. Размер файла не должен превышать 7 Мегабайт. Любые попытки загрузки файла другого типа, будут замечены и заблокированы. Фото будет размещено на хосте с указан авторства от вашего имени.
        </div>
    </div>
    <!-- Личный кабинет -->
    <div id="user-page">
        <div class = "title">
            <div class = "title-items">
                <div>
                    <h1>
                        <?php if (isset($_SESSION['userId'])) echo $_SESSION['userUid']; else echo "Вход не выполнен"; ?> - Редактирование профиля
                    </h1>
                </div>
            </div>
            <hr>
        </div>
        <!-- Информационное поле -->
        <div class="content" style="display: flex;">
            <!-- Поле для настройки личной информации -->
            <div style="width: 50%; box-sizing: border-box; padding-right: 50px;">
                <label for="user-name">Фраза приветствия</label>
                <input name="user-name" maxlength="200" type="text" id="user-desc" value="<?php echo $_SESSION['status'] ?>" placeholder="Введите приветствие">
                <label for="user-mail">Аватар</label>
                <input name="user-mail" maxlength="128" type="text" id="user-avatar" value="<?php echo $_SESSION['avatar'] ?>" placeholder="Введите ссылку">
                <label for="user-hello">Фон</label>
                <input name="user-hello" maxlength="128" type="text" value="<?php echo $_SESSION['background'] ?>" id="user-back" placeholder="Введите ссылку">
                <button onClick="changeBio();" id="save-user-data" name="save-user-button">Отправить</button>
            </div>
            <div style="width: 50%;">
                <div>
                <label>Права доступа</label></br>
                <?php echo $_SESSION['role']; ?></br>
                </br>
                <label>Почтовый ящик</label></br>
                <?php echo $_SESSION['mail']; ?></br>
                </div>
            </div>
        </div>
    </div>
    <footer id="myFooter">
        <div class="footer-title">Спасибо что зашли, возращайтесь поскорее!</div>
        <p>Илья Волков - Дизайнер и программист сайта "Еловый лес"</p>
    </footer>
    <script src="/include/code.js" type="application/javascript"></script>
</body>
</html>