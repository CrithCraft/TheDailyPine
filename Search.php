<html>
<head>
    <title>Поиск</title>

    <link rel="apple-touch-icon" href="images/app_icon.png"/>
    <!-- <meta http-equiv="Content-Security-Policy" content="script-src 'self'"> -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" media="(max-width: 950px)" href="css/style-mobile.css">
    <link rel="stylesheet" media="(min-width: 1200px)" href="css/style.css">
    <link rel="stylesheet" media="(min-width: 1500px)" href="css/style-big.css">
    <link rel='stylesheet' media='screen and (min-width: 951px) and (max-width: 1200px)' href='css/style-mini.css' />
    <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:400,600,700&display=swap" rel="stylesheet">
    <style type="text/css">
        nav li {
            border-top: 4px solid transparent;
        }
    </style>
</head>
<body>
    <?php require ($_SERVER["DOCUMENT_ROOT"]."/include/code.php"); ?>
    <script src="/include/code.js"></script>
    
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
    <div id="second-level">
        <div id="dialog-box">
            <button style="background-image: url(images/back.png);" onclick="showRemoveField('images/back.png')" id="back-button"></button>
            <h1>Введите код администратора</h1>
            <form action="#" method="post" style="width: 100%;">
                <div style="display:flex; flex-wrap: nowrap;">
                    <input name="password-admin" maxlength="16" class="password-admin" type="password" autocomplete="off" placeholder="Пароль">
                    <div style="background-image: url(images/trash.png);" class = "trash-accept">
                        <input name="trash-accept" style="opacity: 0; height: 100%; width: 100%;" type="submit" id="trash-accept" value="" />
                    </div>
                </div>
            </form>
        </div>
        <button class="preview-button" onclick="showPreviewField('images/preview.jpg')"></button>
        <div id="preview-field">
            <img src="images/tumb_background.jpg" width="100%" id="photo-image">
        </div>
    </div>

    <div class="shadow"></div>
    <header>
        <nav>
            <img src="images/logo.png" width="36px" height="18px" alt="icon">
            <?php
                for( $i = 0; $i < sizeof($nav_lables); $i++)
                    echo "<li><a href='",$nav_lables[$i],"'>",$nav_lables_name[$i],"</a></li>";
            ?>
        </nav>
        <div class="user-label">
            <input type="submit" value="ВХОД" class="login-button">
        </div>
    </header>
    <div class="blur-header"></div>
    <div class = "title">
        <div class = "title-items">
            <div><h1>Результаты поиска</h1></div>
            <div class="title-icons">
                <button id="add-photo-button" onclick="showAddField()"></button>
                <button id="user-folder-button" onclick="showAddField()"></button>
            </div>
        </div>
        <hr>
    </div>
    <article>
        <div id="add-photo-field">
            <div id="add-photo-block">
            <form style="width: 100%; display: flex;" method="post" enctype="multipart/form-data">
                <div class = "photo-block-left">
                    <div class="photo-block-header">
                        <div class = "photo-block-header-text">
                            <div style="display:flex; align-items:center;">
                                <img src="images/photo_icon.png" width="22px" height="22px" alt="icon">
                                <input name="upload-name" maxlength="12" type="text" id="upload-input-name" placeholder="Название поста">
                            </div>
                            <input name="upload-author" maxlength="12" id="upload-input-author" type="text" placeholder="Автор">
                            <?php
                                date_default_timezone_set('UTC');
                                echo '<div class = "date">'.date("Y-m-d").'</div>';
                            ?>
                        </div>
                        <div class="photo-block-header-download">
                            <input type="submit" id="upload-photo-button" value="">
                        </div>
                    </div>
                    <label class="label">
                        <span class="upload-title">Добавить файл</span>
                        <input name="upload-file" type="file" id="upload-photo-input">
                    </label>
                </div>
                <div class="vr"></div>
                <div class = "photo-block-right">
                    <div class = "photo-block-right-header">
                        <img src="images/desc_icon.png" width="24px" height="24px" alt="icon">
                        <h3>Описание</h3>
                        <textarea name="upload-photo-description" id="upload-input-description" type="text" placeholder="Опиши данное фото, или добавьте что-то от себя..."></textarea>
                        <div class="hashtag-label">
                            <?php
                                hashtagDraw(1,3);
                            ?>
                        </div>
                        <div class="location-label">
                            <img src="images/location_icon.png" width="20px" height="20px" alt="icon">
                            <div id="location-text" class="location-text">Unlocated</div>
                        </div>
                    </div>
                </div>
            </form>
            </div>
        </div>
        <?php
        if(isset($_POST['search'])){
            $data=strip_tags($_POST['search']);
            if ($data{0} == "#") {
                echo "<h1 style='width: 100%; font-size: 30px; margin: 0px;'>Фотографии</h1>";
                renderSearchResult($data,"photo");
                echo "<h1 style='width: 100%; font-size: 30px; margin: 0px;'>Арты</h1>";
                renderSearchResult($data,"art");
            }
            else {
                echo "<h1 style='width: 100%; font-size: 30px; margin: 0px;'>Фотографии</h1>";
                renderSearchResult("#".$data, "photo");
                
                echo "<h1 style='width: 100%; font-size: 30px; margin: 0px;'>Арты</h1>";
                renderSearchResult("#".$data,"art");
            }
        }
        ?>
    </article>
    <aside>
        <div class="hash-list">
            <div class = "right-nav">
                <img src="images/search_icon.png" width="22px" height="22px" alt="icon">
                <form name="search_form" action="Search.php" method="POST">
                    <input type="search" placeholder="Поиск..." id="search-input" class="search-input" name="search">
                </form>
            </div>
            <h3>Может вам понравится?</h3>
            <?php
                renderHashtag(40);
            ?>
        </div>
    </aside>
    <footer>
        <hr>
        <div class="footer-title">Спасибо что зашли, возращайтесь поскорее!</div>
        <p>Илья Волков - Дизайнер и програмист сайта PineArt</p>
    </footer>
</body>
</html>