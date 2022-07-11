<html>
<head>
    <title>Главная</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="apple-touch-icon" href="images/app_icon.png"/>

    <link rel="stylesheet" href="css/style.css">
    <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:400,600,700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Rubik:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style type="text/css">
        * {
            font-family: 'Source Sans Pro';
        }
        
        html {
            background-image: url(/images/tumb_background.jpg);
            background-size: 100%;
            background-attachment: fixed;
        }
        body {
            grid-template: 50px 320px 1fr min-content / 0px 1fr 1fr 330px 0px;
            display: grid;
            width: 100%;
            box-sizing: border-box;
        }
        .shadow {
            grid-area: 1/1/4/6;
        }
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
        header {
            grid-area: 1/1/2/6;
        }
        .blur-header {
            backdrop-filter: blur(8px);
            height: 50px;
            position: fixed;
            width: 100%;
        }
        hr {
            background-color: #0F1A1C; 
            height: 2px;
        }
        footer {
            grid-area: 4/1/5/6;
            border: 0px;
            background-color: #339b87;
            padding: 25px 50px;
        }
        footer p, footer .footer-title {
            color: white;
        }
        hr {
            padding-bottom: 8px;
            background-color: transparent;
            border-color: white;
        }
        article {
            grid-area: 3/1/4/6;
            background-color: #339b87;
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            padding: 0px 50px;
        }

        article  * {
            color: white;
            font-size: 1.2em;
        }

        article h1 {
            width: 100%;
            font-size: 1.7em;
            text-transform: uppercase;
            font-weight: 600;
            margin: 0px;
            margin-top: 40px;
            text-align: center;
            color: white;
            font-family: 'Montserrat';
        }
        .author {
            background-color: rgba(0,0,0,0.3);
            color: white;
            grid-area: 2/1/3/6;
            height: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 45px;
            font-weight: 600;
            text-align: center;
            padding: 0px 50px;
        }

        .author-name {
            font-size: 0.85em;
            font-weight: 700;
            font-family: 'Montserrat';
            text-transform: uppercase;
        }

        .user-label {
            width: unset;
        }
    </style>
</head>
<body>
    <?php require ($_SERVER["DOCUMENT_ROOT"]."/include/code.php"); ?>
    
    <!-- <div class="shadow"></div> -->
    <header>
        <nav>
            <div class="logo">
                <img src="images/logo.svg" height="32px" alt="icon">
            </div>
            <?php
                for( $i = 0; $i < sizeof($nav_lables); $i++)
                    echo "<li><a href='",$nav_lables[$i],"'>",$nav_lables_name[$i],"</a></li>";
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

    <div class="author">
        <div class="author-name">Свобода поиска фото-ресурсов уже сегодня!</div>
    </div>
    <article>
        <h1>Одно правило. Множество возможностей.</h1>
        <div class="text-gray">
            <p>Данный сайт рассчитан на фотографов и иллюстраторов. 
            Он позволяет выкладывать/скачивать фотографии или иллюстрации пользователям сайта. 
            Главная особенность сайта в том, что файлы сайта будут распространятся под лицензией подразумевающее его полное свободное использование, 
            единнственным обязательным условием является, указание автора ресурса в работе или проекте в котором он фигурируется. 
            Такой подход нацелен на облегчении жизни пользователям сайта при нахождении ресурсов для игр, дизайна или любой другой деятельности,
            поэтому вам как пользователем не придётся узнавать под какой лицензией распространяемся фото, и что данная лицензия запрещает.</p>
            <p class="text-gray" style="padding: 0px; text-align: right; font-weight: bold; font-style: normal; background: unset;">- Создатель фотохостинга "Еловый лес"</p>
        </div>
    </article>

    <footer>
        <hr>
        <div class="footer-title">Фото было сделано Самуэлем Ферара</div>
        <p>Илья Волков - Дизайнер и программист сайта "Еловый лес"</p>
    </footer>
</body>
</html>