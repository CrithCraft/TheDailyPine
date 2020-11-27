<?php
    session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Домашняя</title>
    <!-- <link rel='stylesheet' media='screen and (min-width: 951px) and (max-width: 1250px)' href='css/style-mini.css' /> -->
    <!-- <link rel="stylesheet" media="(max-width: 950px)" href="css/style-mobile.css"> -->
    <link rel="stylesheet" href="css/style.css">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700&display=swap" rel="stylesheet">
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
    <script src="include/jquery.min.js"></script>
    <?php 
        require ($_SERVER["DOCUMENT_ROOT"]."/include/code.php"); 
    ?>
    <!-- Question Menu -->
    <?php
        if(isset($_POST['trash'])){
            $delete_post=strip_tags($_POST['trash']);
        }
        if(isset($_POST['trash-accept'])){
            $delete_post=strip_tags($_POST['trash-accept']);
            $key=strip_tags($_POST['password-admin']);
            removePicture($key, $delete_post, "art");
        }
    ?>
    <header id="myHeader">
        <nav>
            <div class="logo">
                <img src="images/logo.svg" height="32px" alt="icon">
            </div>
            <div class="vr"></div>
            <?php
                for( $i = 0; $i < sizeof($nav_lables); $i++) {
                    echo "<li><a href='",$nav_lables[$i],"'>",$nav_lables_name[$i],"</a></li>";
                }
            ?>
        </nav>
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
    <div id="blur-header"></div>
    <div class="filled"></div>
    <aside id="myAside">
        <div class="option" id="option-review" onclick="opensidebar('review')"></div>
        <div class="option" id="option-favorite" onclick="opensidebar('favorite')"></div>
        <div class="option" id="option-search" onclick="opensidebar('search')"></div>
        <div class="option" id="option-user" onclick="opensidebar('user')"></div>
        <div class="option" id="option-settings" onclick="opensidebar('settings')"></div>
    </aside>
    <!-- left-nav -->
    <div id="left-nav"> 
        <input type="search" class="search" placeholder="Поиск...">
        <h2>Популярное</h2>
        <form>
            <input type="submit" class="hashtag" value="Показать за день" />
            <input type="submit"class="hashtag" value="Показать за месяц" />
            <input type="submit" class="hashtag" value="Показать за год" />
        </form>
        <h2>Тематика</h2>
        <?php
            renderHashtag(10);
        ?>
    </div>
    <div id="tumb"></div>
    <article id="myArticle">
        <div class = "title">
            <div class = "title-items">
                <div><h1>Иллюстрации</h1></div>
                <div class="title-icons">
                    <button id="add-photo-button" onclick="showAddField()"></button>
                    <button id="user-folder-button" onclick="showAddField()"></button>
                </div>
            </div>
            <hr>
        </div>
        <div id="articles"></div>
        <button id="more">Дальше</button>
        <script type="text/javascript">
            $(document).ready(function(){
                load_posts("post");
            });
        </script>
    </article>
    <div id="add-photo-area">
        <form style="width: 100%; display: flex; display: -webkit-flex" method="post" enctype="multipart/form-data">
            <div>
                <label class="label">
                    <span class="upload-title">Добавить файл</span>
                    <input name="upload-file" type="file" id="upload-photo-input">
                </label>
            </div>
            <div>
                <textarea style="display: none;" name="upload-photo-description" id="upload-input-description" type="text" placeholder="Опиши данное фото, или добавьте что-то от себя..."></textarea>
                <input name="upload-name" maxlength="12" type="text" id="upload-input-name" placeholder="Название поста">
                <input type="submit" id="upload-photo-button" name="upload-photo-button" value="Отправить">
            </div>
        </form>
    </div>
    <div id="user-page">
        <div class="preview" style="background: linear-gradient(rgba(255,255,255,0.0), rgba(255,255,255,1)), url(/images/backgrounds/Caumasee.jpg);"></div>
        <div class = "title">
            <div class = "title-items">
                <div>
                    <h1>
                        <?php if (isset($_SESSION['userId'])) echo $_SESSION['userUid']; else echo "Вход не выполнен"; ?>
                    </h1>
                </div>
                <div class="title-icons">
                    <button id="add-photo-button" onclick="showAddField()"></button>
                    <button id="user-folder-button" onclick="showAddField()"></button>
                </div>
            </div>
            <hr>
        </div>
        <div class="content" style="display: flex;">
            <div style="width: 50%; box-sizing: border-box; padding-right: 50px;">
                <label for="user-name">Имя</label>
                <input name="user-name" maxlength="12" type="text" id="user-name" placeholder="Введите имя">
                <label for="user-mail">Почта</label>
                <input name="user-mail" maxlength="12" type="text" id="user-mail" placeholder="Введите почту">
                <label for="user-hello">Фраза приветствия</label>
                <input name="user-hello" maxlength="12" type="text" id="user-hello" placeholder="Название фразу приветсвия">
                <input type="submit" id="save-user-data" name="save-user-button" value="Отправить">
            </div>
            <div style="width: 50%;">
                <div>
                Lorem Ipsum is simply dummy text of the printing and typesetting industry. 
                Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, 
                when an unknown printer took a galley of type and scrambled it to make a type specimen book. 
                It has survived not only five centuries, but also the leap into electronic typesetting, 
                remaining essentially unchanged. 
                It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, 
                and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.
                </div>
            </div>
        </div>
    </div>
    <footer id="myFooter">
        <div class="footer-title">Спасибо что зашли, возращайтесь поскорее!</div>
        <p>Илья Волков - Дизайнер и программист сайта PineArt</p>
    </footer>
    <script src="/include/code.js"></script>
</body>
</html>