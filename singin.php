<html>
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="apple-touch-icon" href="images/app_icon.png"/>
    <style>
        #reg {
            display: none;
        }

        #log {
            display: grid;
        }

        * {
            font-family: Rubik;
        }
        body {
            display: flex;
            width: 100%;
            height: 100%;
            overflow: hidden;
        }

        .center {
            margin: auto;
        }

        .default-input {
            position: relative;
            vertical-align: top;
            height: 40px;
            font-size: 15px;
            color: black;
            text-align: left;
            padding: 0px 12px;
            border: 0;
            cursor: pointer;
            border-radius: 8px;
            font-weight: 400;
            border: 0.5px solid #CECCCE;
            margin-bottom: 12px;
        }

        .default-button {
            position: relative;
            vertical-align: top;
            height: 40px;
            font-size: 15px;
            background: #339b87;
            color: white;
            padding: 0px 12px;
            border: 0;
            cursor: pointer;
            border-radius: 8px;
            font-weight: 400;
            border: 0.5px solid #CECCCE;
            margin-bottom: 12px;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        h1 {
            font-family: Montserrat;
            font-weight: 600;
            font-size: 1.5em;
        }

        ul {
            display: flex;
            padding: 0px;
            margin-bottom: 0px;
        }

        ul li {
            display: inline-block;
            padding-right: 8px;
        }

        ul li a {
            all: unset;
            color: gray;
            transition: all 0.5s;
            cursor: pointer;
        }

        ul li a:hover {
            color: #339b87;
        }

        form {
            width: 280px;
        }

        .label {
            display: flex;
            flex-wrap: nowrap;
            justify-content: space-between;
            margin-bottom: 16px;
        }

        a {
            color: #389B88;
            cursor: pointer;
        }
        input[type=checkbox] {
            cursor: pointer;
        }

        .exit-label {
            display: flex;
            justify-content: center;
            margin-bottom: 40px;
        }

        .exit-label a {
            text-decoration: none;
            font-weight: 600;
            font-size: 1em;
            font-family: Montserrat;
            text-transform: uppercase;
            box-sizing: border-box;
        }
    </style>
    <link href="https://fonts.googleapis.com/css2?family=Rubik:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700&display=swap" rel="stylesheet">
    <!-- Функция для смены форм -->
    <script>
        function changeForm(type) {
            if(type == "log"){
                document.getElementById("log").style.display = "grid";
                document.getElementById("reg").style.display = "none";
            } else {
                document.getElementById("log").style.display = "none";
                document.getElementById("reg").style.display = "grid"; 
            }
        }
    </script>
</head>
<body>
    <div class="center">
        <div class="exit-label"><a href="/">Вернуться назад</a></div>
        <ul>
            <li>
                <a onClick="changeForm('reg')">Регистрация</a>
            </li>
            <li>
                <a onClick="changeForm('log')">Вход</a>
            </li>
        </ul>
        <form action="include/code.php" method="post" id="reg">
            <h1>Регистрация</h1>
            <input type="text" name="uid" placeholder="Никнейм" class="default-input" required>
            <input type="email" name="mail" placeholder="E-mail" class="default-input" required>
            <input type="password" name="pwd" placeholder="Пароль" class="default-input" required>
            <input type="password" name="pwd-repeat" placeholder="Повторите пароль" class="default-input" required>
            <div class="label">
                <label for="license">Примите <a href="/policy" target="_blank">политику конфедациальности</a></label>
                <input type="checkbox" name="license" id="license" required>
            </div>
            <div class="label">
                <label for="license">Подтверждаю использование <a href="/cookie" target="_blank">cookie</a> на этом сайте</label>
                <input type="checkbox" name="license" id="license" required>
            </div>
            <button type="submit" name="signup-submit" class="default-button">Создать аккаунт</button>
        </form>
        <form action="include/code.php" method="post" id="log">
            <h1>Вход</h1>
            <input type="text" name="mailuid" placeholder="E-mail / Никнейм" class="default-input" required>
            <input type="password" name="pwd" placeholder="Пароль" class="default-input" required>
            <button type="submit" name="login-submit" class="default-button">Вход в аккаунт</button>
        </form>
    </div>
</body>
</html>