<html>
<head>
    <title>Главная</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Иконка для избранного -->
    <link rel="apple-touch-icon" href="images/app_icon.png"/>
    <!-- Подключаем css загружем шрифты -->
    <link rel="stylesheet" media="(max-width: 800px)" href="css/style-mobile.css">
    <link rel="stylesheet" media="(min-width: 1200px)" href="css/style.css">
    <link rel='stylesheet' media='screen and (min-width: 800px) and (max-width: 1200px)' href='css/style-mini.css' /> 
    <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:400,600,700&display=swap" rel="stylesheet">
    <!-- Дополнительные стили для больших экранов -->
    <style type="text/css">
        html {
            background-image: url(/images/tumb_background.jpg);
            background-size: 100%;
            background-attachment: fixed;
        }
        body {
            grid-template: 60px 320px 1fr 100px/ 145px 1fr 1fr 330px 145px;
        }
        .shadow {
            grid-area: 1/1/4/6;
        }
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
        header {
            grid-area: 1/1/2/6;
        }
        .blur-header {
            grid-area: 1/1/2/6;
        }
        hr {
            background-color: #0F1A1C; 
            height: 2px;
        }
        footer {
            grid-area: 4/1/5/6;
            background-color: #339b87;
        }
        footer p, footer .footer-title {
            color: white;
        }
        hr {
            background-color: white;
        }
        article {
            grid-area: 3/1/4/6;
            background-color: #339b87;
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            padding: 0px;
        }
        article h1 {
            width: 100%;
            font-size: 3em;
            font-weight: 700;
            margin: 0px;
            margin-top: 20px;
            text-align: center;
            color: white;
        }
        .author {
            background-color: rgba(0,0,0,0.3);
            color: white;
            grid-area: 2/1/3/6;
            height: 100%;
            display: flex;
            align-items: center;
        }
    </style>
    <!-- Аддаптация под мобильные устройства -->
    <style media="(max-width: 800px)">
        body {
            grid-template: 60px 200px 1fr 130px/ 0px 1fr 1fr 330px 0px;
        }
        article h1 {
            font-size: 2em;
        }
        .author {
            padding-top: 40px;
            height: 160px;
        }
    </style>
</head>
<body>
    <!-- Подключаем главный php и js файлы -->
    <?php require ($_SERVER["DOCUMENT_ROOT"]."/include/code.php"); ?>
    <script src="/include/code.js"></script>
    <!-- шапка -->
    <header>
        <!-- навигация -->
        <nav>
            <img src="images/logo.png" width="36px" height="18px" alt="icon">
            <?php
                for( $i = 0; $i < sizeof($nav_lables); $i++)
                    echo "<li><a href='",$nav_lables[$i],"'>",$nav_lables_name[$i],"</a></li>";
            ?>
        </nav>
        <!-- поле входа -->
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
    <!-- Приветствие -->
    <div class="author">
        <div class="author-name">Свобода поиска фото-ресурсов уже сегодня!</div>
    </div>
    <!-- Основной контент -->
    <article>
        <h1>Одно правило. Множество возможностей.</h1>
        <div class="text-gray">
            <p>Данный сайт рассчитан на фотографов и иллюстраторов. 
            Он позволяет выкладывать/скачивать фотографии или иллюстрации пользователям сайта. 
            Главная особенность сайта в том, что файлы сайта будут распространятся под лицензией подразумевающее его полное свободное использование, 
            единнственным обязательным условием является, указание автора ресурса в работе или проекте в котором он фигурируется. 
            Такой подход нацелен на облегчении жизни пользователям сайта при нахождении ресурсов для игр, дизайна или любой другой деятельности,
            поэтому вам как пользователем не придётся узнавать под какой лицензией распространяемся фото, и что данная лицензия запрещает.</p>
            <p class="text-gray" style="padding: 0px; text-align: right; font-weight: bold; font-style: normal; background: unset;">- Создатель PineArt</p>
        </div>
        <div class="dialoge" style="background-image: url('images/photo_1.png');">
            <div class="dialoge-text">
                <div class="dialoge-title">О создателе сайта</div>
                Меня зовут Илья Волков, я студент 3 курса ЮУГК. Чтобы стать тем, кем я являюсь сейчас, мне пришлось
            изрядно потрудится, но ради любимого дела, я готов преодолеть любые приграды. Все началось в
        7 классе, когда один из студентов, который проходил производственную практику у нас в школе, по моей просьбе, научил меня основам C++. В это же время я посещал центр детского творчества,
        где научился основам web програмирования. Позже, в 9 класе, мне удалось попасть в школу Samsung IT, но из-за
        сильного давления со стороны ОГЭ, и недостатка свободного времени, я решил покинуть Samsung IT, о чем немного сожалею. Сейчас я пытаюсь наверстать упущенное и самостоятельно обучится разработке Android приложений.</div>
        </div>
    </article>
    <!-- Подвал -->
    <footer>
        <hr>
        <div class="footer-title">Photo by Samuel Ferrara on Unsplash</div>
        <p>Илья Волков - Дизайнер и программист сайта PineArt</p>
    </footer>
</body>
</html>