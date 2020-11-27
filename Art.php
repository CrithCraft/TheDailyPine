<?php
    session_start();
?>
<html>
<head>
    <title>Иллюстрации</title>
    <!-- <meta http-equiv="Content-Security-Policy" content="script-src 'self'"> -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" media="(max-width: 950px)" href="css/style-mobile.css">
    <link rel="stylesheet" media="(min-width: 1250px)" href="css/style.css">
    <link rel="stylesheet" media="(min-width: 1912px)" href="css/style-big.css">
    <link rel='stylesheet' media='screen and (min-width: 951px) and (max-width: 1250px)' href='css/style-mini.css' />
    <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:400,600,700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Rubik:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style type="text/css">
        nav li {
            border-top: 4px solid transparent;
        }
        nav li:nth-of-type(3) {
            border-top: 4px solid #339b87;
        }
        nav li:nth-of-type(3):hover {
            border-top: 0px solid #339b87;
            padding-top: 4px;
        }
    </style>
</head>
<body>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <?php require ($_SERVER["DOCUMENT_ROOT"]."/include/code.php"); ?>

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
        <button class="preview-button" onclick="showPreviewField('images/preview.jpg')"></button>
        <div id="preview-field">
            <img src="images/tumb_background.jpg" id="photo-image">
        </div>
    </div>
    <!-- <div class="shadow"></div> -->
    <header>
    <nav>
        <div class="logo">
            <img src="images/logo.png" height="32px" alt="icon">
        </div>
        <div class="vr"></div>
        <?php
            for( $i = 0; $i < sizeof($nav_lables); $i++)
            {
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
    <div class="blur-header"></div>
    <div class="preview" style="background-image: url(images/montain_green.png);" >
        <img src="images/montain_green.png" width="100%" style="visibility: hidden;">
    </div>
    <div class="filled"></div>
    <div class = "title">
        <!-- <img src="images/montain_green.png" width="100%" > -->
        <div class = "title-items">
            <div><h1>Иллюстрации</h1></div>
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
                                <input name="upload-name" type="text" maxlength="12" id="upload-input-name" placeholder="Название поста">
                            </div>
                            <p id="upload-input-author"><?php if (isset($_SESSION['userId'])) echo $_SESSION['userUid']; else echo "Unkown"; ?></p>
                            <?php
                                date_default_timezone_set('UTC');
                                echo '<div class = "date">'.date("Y-m-d").'</div>';
                            ?>
                        </div>
                        <div class="photo-block-header-download">
                            <input type="submit" id="upload-photo-button" name="upload-photo-button" value="">
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
                        <textarea name="upload-art-description" id="upload-input-description" type="text" placeholder="Опиши данное фото, или добавьте что-то от себя..."></textarea>
                        <div class="hashtag-label">
                            <?php
                                hashtagDraw(1,3);
                            ?>
                        </div>
                        <div class="location-label">
                            <img src="images/location_icon.png" width="20px" height="20px" alt="icon">
                            <div id="location-text" class="location-text">Воображение</div>
                        </div>
                    </div>
                </div>
            </form>
            </div>
        </div>
        <button id="more">Дальше</button>
        <div id="articles">
        </div>
        <script type="text/javascript">
            $(document).ready(function(){

                var inProgress = false; // идет процесс загрузки
                var startFrom = 0; // позиция с которой начинается вывод данных
                $('#more').click(function() {
                    if (!inProgress) {
                        $.ajax({
                            url: 'include/ajax.items.php', // путь к ajax-обработчику
                            method: 'POST',
                            data: {
                                "start" : startFrom
                            },
                            beforeSend: function() {
                                inProgress = true;
                            }
                        }).done(function(data){
                            data = jQuery.parseJSON(data); // данные в json
                            if (data.length > 0){
                                // добавляем записи в блок в виде html
                                $.each(data, function(index, data){
                                    $("#articles").append('<div class="photo-block">'
                                        +'<div style="background: linear-gradient(to bottom, rgba(0,0,0,.0), rgba(0,0,0,.4)), url(/images/backgrounds/low_'+data.image+');" class="image" onClick="openPicBlock(this)">' 
                                            +'<div class="likes">3505</div>'
                                        +'</div>'
                                        +'<div class="text-area">'
                                            +'<div class="name">'+data.name+'</div>'  
                                            +'<div class="author-label">'
                                                +'<div class="author">'
                                                    +'<div class="icon"></div>'
                                                    +'<div class="text">'+data.author+'</div>'
                                                +'</div>'
                                                +'<div><button class="share-button" onClick=""></button></div>'
                                            +'</div>'
                                            +'<input type="submit" class="donwload-button" value="Загрузить">'
                                            +'<hr>'
                                            +'<div class="hashtag-list">'
                                                +'<div class="hashtag">#wow</div>'
                                                +'<div class="hashtag">#wow</div>'
                                                +'<div class="hashtag">#wow</div>'
                                            +'</div>'
                                            +'<div class="bottom">'
                                                +'<div class="views">3450 views</div>'
                                                +'<div class="date">'+data.date+'</div>'
                                            +'</div>'
                                        +'</div>'
                                    +'</div>');
                                });
                                inProgress = false;
                                startFrom += 10;
                            }
                        });
                    }
                });
            });
        </script>
    </article>
    <!-- <aside>
        <div class="hash-list">
            <div class = "right-nav">
                <img src="images/search_icon.png" width="22px" height="22px" alt="icon">
                <form name="search_form" action="Search.php" id="search-form" method="POST">
                    <input type="search" placeholder="Поиск..." id="search-input" class="search-input" name="search">
                </form>
            </div>
            <h3>Может вам понравится?</h3>
            <?php
                renderHashtag(40);
            ?>
        </div>
    </aside> -->
    <footer>
        <hr>
        <div class="footer-title">Спасибо что зашли, возращайтесь поскорее!</div>
        <p>Илья Волков - Дизайнер и программист сайта PineArt</p>
    </footer>
    <script src="/include/code.js"></script>
</body>
</html>