<?php

require_once('globals.php');

session_start();

if (loggedIn()) {
    header("Location: index.php");
    exit();
}

$action = 'sign_in';
$error = '';

if (isset($_REQUEST['action'])) {
    $action = $_REQUEST['action'];
}
if (!empty($_SESSION['error'])) {
    $error = $_SESSION['error'];
    unset($_SESSION['error']);
}

?>

<script>
    function signup() {
        setError('');
        var $username = document.getElementById('id_username').value;
        if ($username.length === 0) {
            setError('Имя пользователя не введено.');
            return;
        }
        var $email = document.getElementById('id_email').value;
        if ($email.length === 0) {
            setError('Почта не введена.');
            return;
        }

        var $password = document.getElementById('id_password').value;
        if ($password.length === 0) {
            setError('Пароль не введен.');
            return;
        }
        if ($password !== document.getElementById('id_password_confirm').value) {
            setError('Пароли не совпадают.');
            return;
        }

        document.forms['sign_up'].submit();
    }

    function signin() {
        var $username = document.getElementById('id_username').value;
        if ($username.length === 0) {
            setError('Имя пользователя не введено.');
            return;
        }

        var $password = document.getElementById('id_password').value;
        if ($password.length === 0) {
            setError('Пароль не введен.');
            return;
        }

        document.forms['sign_in'].submit();
    }

    function setError(text) {
        document.getElementById('id_error').innerHTML = text;
    }
</script>

<link rel="stylesheet" type="text/css" href="style.css">

<form name="<?= $action ?>" action="auth.php" method="POST">
    <div class="center_block">
        <div class="nav-block">
            <a href="index.php" style="text-decoration: none;">< назад</a>
        </div>
        <div class="logon_row">
            <p id="id_error" class="error"><?= $error ?></p>
        </div>
        <div class="logon_row">
            <div class="input_title">Имя пользователя</div>
            <input type="text" name="username" id="id_username" value="">
        </div>
        <div class="logon_row">
            <div class="input_title">Пароль</div>
            <input type="password" name="password" id="id_password" autocomplete="off">
        </div>
        <input type="hidden" name="action" value=<?= $action ?>>
        <?php if ($action == 'sign_up'): ?>
            <div class="logon_row">
                <div class="input_title">Повторите пароль</div>
                <input type="password" name="password_confirm" id="id_password_confirm" autocomplete="off">
            </div>
            <div class="logon_row">
                <div class="input_title">Электронная почта</div>
                <input type="text" name="email" id="id_email" value="">
            </div>
            <div class="logon_row">
                <div class="input_title">ФИО</div>
                <input type="text" name="fullname" id="id_fullname" value="">
            </div>
            <div class="logon_row">
                <button type="button" onclick="signup()">Зарегистрироваться</button>
            </div>
        <?php else: ?>
            <div class="logon_row">
                <button type="button" onclick="signin()">Войти</button>
            </div>
        <?php endif ?>
    </div>
</form>
