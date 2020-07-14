<?php

require_once('globals.php');
include('auth.php');

session_start();

$username = loggedIn() ? $_SESSION['username'] : 'гость';
$message = "Добро пожаловать, $username!";
if (!empty($_SESSION['message'])) {
    $message = $_SESSION['message'];
    unset($_SESSION['message']);
}

?>

<link rel="stylesheet" type="text/css" href="style.css">

<div class="center_block">
    <p><?= $message ?></p>

    <?php if (!loggedIn()): ?>
        <div class="auth-button">
            <a href="logon.php?action=sign_up">
                <span>Зарегистрироваться</span>
            </a>
        </div>
        <br>
        <div class="auth-button">
            <a href="logon.php?action=sign_in">
                <span>Войти</span>
            </a>
        </div>
    <?php else: ?>
        <div class="auth-button">
            <a href="settings.php">
                <span>Настройки</span>
            </a>
        </div>
        <div class="auth-button">
            <a href="logout.php">
                <span>Выйти</span>
            </a>
        </div>
    <?php endif ?>
</div>
