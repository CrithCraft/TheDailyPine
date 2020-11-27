<html>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="apple-touch-icon" href="images/app_icon.png"/>
<style>
    body {
        display: flex;
        width: 100%;
        height: 100%;
    }

    .center {
        margin: auto;
    }

    .default-input {
        position: relative;
        vertical-align: top;
        
        height: 32px;
        font-size: 15px;
        color: black;
        text-align: left;
        padding: 0px 12px;
        border: 0;
        cursor: pointer;
        border-radius: 21px;
        font-weight: 400;
        font-family: 'Source Sans Pro', sans-serif;
        border: 0.5px solid #CECCCE;
        margin-bottom: 12px;
    }

    .default-button {
        -webkit-appearance: none;
        appearance: none;
        position: relative;
        vertical-align: middle;
        /* width: 72px; */
        height: 32px;
        font-size: 15px;
        color: #fff;
        text-align: center;
        background: #339b87;
        border: 0;
        cursor: pointer;
        border-radius: 21px;
        font-weight: 400;
        font-family: 'Source Sans Pro', sans-serif;
        padding: 0px 12px;
        margin-bottom: 12px;
    }

    h1 {
    font-family: 'Source Sans Pro', sans-serif;
    font-weight: 700;
    
    }
</style>
<body>
    <div class="center">
    <h1>Регистрация</h1>
        <form action="include/code.php" method="post" style="display: grid;">
            <input type="text" name="uid" placeholder="Никнейм" class="default-input" required>
            <input type="text" name="mail" placeholder="E-mail" class="default-input" required>
            <input type="password" name="pwd" placeholder="Пароль" class="default-input" required>
            <input type="password" name="pwd-repeat" placeholder="Повторите пароль" class="default-input" required>
            <button type="submit" name="signup-submit" class="default-button">Create account</button>
        </form>
        <h1>Вход</h1>
        <form action="include/code.php" method="post" style="display: grid;">
            <input type="text" name="mailuid" placeholder="E-mail / Никнейм" class="default-input" required>
            <input type="password" name="pwd" placeholder="Пароль" class="default-input" required>
            <button type="submit" name="login-submit" class="default-button">Log In</button>
        </form>
    </div>
</body>
</html>